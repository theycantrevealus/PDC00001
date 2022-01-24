<?php

namespace PondokCoder;

use PondokCoder\Utility as Utility;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;

class Modul extends Utility {
	static $pdo, $query;
	
	protected static function getConn(){
		return self::$pdo;
	}

	public function __construct($connection) {
		self::$pdo = $connection;
		self::$query = new Query(self::$pdo);
	}

	public function __GET__($parameter = array()) {


		if($parameter[1] == 'detail') {

			//__HOST__/Modul/detail/{id}
			return self::get_detail(array(
				'id' => $parameter[2]
			));
		} else if($parameter[1] == 'tree') {

			//__HOST__/Modul/tree
			return self::get_tree(0);

		} else if($parameter[1] == 'methods_get') {

			if(intval($parameter[2]) > 0) {
				
				//__HOST__/Modul/methods_get
				return self::get_methods(intval($parameter[2]));

			} else {

				//__HOST__/Modul/methods_get/{id}
				return self::get_methods(0);

			}
		} else if($parameter[1] == 'methods_reload') {	
			
			//__HOST__/Modul/methods_reload
			return self::reload_methods();

		} else if($parameter[1] == 'methods_tree') {

			//__HOST__/Modul/methods_tree
			return self::get_methods_tree();

		} else if($parameter[1] == 'get_child') {

			//__HOST__/Modul/methods_tree
			return self::get_child($parameter[2]);			

		} else if($parameter[1] == 'module_server_side') {

			//__HOST__/Modul/module_server_side
			return self::module_server_side($parameter);
        } else if($parameter[1] == 'module_select2') {
            return self::get_select2();
		} else {

			//__HOST__/Modul
			return self::get_all();

		}

	}

	public function __POST__($parameter = array()) {
		switch ($parameter['request']) {
			case 'tambah_modul':
				return self::tambah_modul($parameter);
				break;
			case 'edit_modul':
				return self::edit_modul($parameter);
				break;
            case 'rebase_modul':
                return self::rebaseModulLevel($parameter['parent'], $parameter['level']);
                break;
			default:
				return array(
					'response_message' => 'Unknown Request'
				);
				break;
		}
	}

	public function __DELETE__($parameter) {
		return
			self::$query
				->delete('modul')

				->where(array(
					'modul.id' => '= ?'
				), array(
					$parameter[6]
				))

				->execute();
	}













	private function tambah_modul($parameter) {
		return
			self::$query
				->insert('modul', array(
					'nama' => $parameter['nama'],
					'identifier' => $parameter['identifier'],
					'keterangan' => $parameter['keterangan'],
					'created_at' => parent::format_date(),
					'updated_at' => parent::format_date(),
					'parent' => $parameter['parent'],
					'group_color' => $parameter['colorModule'],
					'icon' => $parameter['icon'],
					'show_on_menu' => $parameter['show_on_menu'],
					'show_order' => $parameter['show_order'],
					'menu_group' => $parameter['menu_group']
				))

				->execute();
	}

	/*private module_server_side($parameter) {
		return
			self::$query
				->select('modul', array(
					'id',
					'nama'
				))

				->where(array(
					'deleted_at' => 'IS NULL'
				))

				->order(array(
					'nama' => 'asc'
				))

				->offset($parameter[2])

				->limit($parameter[3])

				->execute();
	}*/

	private function edit_modul($parameter) {
		//CHECKING GROUP CHILD
		$modul_data = self::get_child($parameter['id']);

		foreach ($modul_data as $key => $value) {
			$update_group = self::$query
				->update('modul', array(
					'menu_group' => $parameter['menu_group']	
				))

				->where(array(
					'modul.deleted_at' => 'IS NULL',
					'AND',
					'modul.id' => '= ?'
				), array(
					$value
				))

				->execute();
		}

        //Update Level
        $LevelCount = 0;
        $ParentLevel = self::$query->select('modul', array(
            'level'
        ))
            ->where(array(
                'modul.deleted_at' => 'IS NULL',
                'AND',
                'modul.id' => '= ?'
            ), array(
                $parameter['parent']
            ))
            ->execute();
        if(count($ParentLevel['response_data']) > 0) {
            $LevelCount = intval($ParentLevel['response_data'][0]['level']);
        }


		$process = self::$query->update('modul', array(
            'nama' => $parameter['nama'],
            'identifier' => $parameter['identifier'],
            'keterangan' => $parameter['keterangan'],
			'group_color' => $parameter['colorModule'],
            'updated_at' => parent::format_date(),
            'parent' => $parameter['parent'],
            'icon' => $parameter['icon'],
            'show_on_menu' => $parameter['show_on_menu'],
            'show_order' => $parameter['show_order'],
            'menu_group' => $parameter['menu_group'],
            'level' => intval($LevelCount) + 1
        ))

        ->where(array(
            'modul.deleted_at' => 'IS NULL',
            'AND',
            'modul.id' => '= ?'
        ), array(
            $parameter['id']
        ))

        ->execute();


		
		return $process;
	}

	private function rebaseModulLevel($parent, $level) {
	    $modul = self::$query->select('modul', array(
	        'id',
	        'parent',
            'level'
        ))
            ->where(array(
                'modul.deleted_at' => 'IS NULL',
                'AND',
                'modul.parent' => '= ?'
            ), array(
                $parent
            ))
            ->execute();
	    foreach ($modul['response_data'] as $key => $value) {
	        $UpdateLevel = self::$query->update('modul', array(
	            'level' => intval($level) + 1
            ))
                ->where(array(
                    'modul.deleted_at' => 'IS NULL',
                    'AND',
                    'modul.id' => '= ?'
                ), array(
                    $value['id']
                ))
                ->execute();

	        //Get Child
            $Child = self::$query->select('modul', array(
                'parent',
                'level'
            ))
                ->where(array(
                    'modul.deleted_at' => 'IS NULL',
                    'AND',
                    'modul.parent' => '= ?'
                ), array(
                    $value['id']
                ))
                ->execute();
            if(count($Child['response_data']) > 0) {
                $UpdateChild = self::rebaseModulLevel($value['id'], ($level + 1));
            }

            $modul['response_data'][$key]['child'] = (count($Child['response_data']) > 0) ? $Child['response_data'] : array();
        }
	    return $modul;
    }

	private function get_child($parameter) {
		$child_list = array();
		$modul_data = self::$query

		->select('modul', array(
			'id'
		))

		->where(array(
			'modul.deleted_at' => 'IS NULL',
			'AND',
			'modul.parent' => '= ?'
		), array(
			$parameter
		))

		->execute();
		
		foreach ($modul_data['response_data'] as $key => $value) {

			array_push($child_list, $value['id']);

			$gs = self::get_child($value['id']);
			if(count($gs) > 0) {
				$child_list = array_merge($child_list, $gs);
			}

		}

		return $child_list;
	}

	private function get_select2() {
        $query = self::$query->select('modul', array(
            'id', 'level', 'nama', 'identifier', 'keterangan', 'created_at', 'updated_at', 'deleted_at', 'parent', 'icon', 'show_on_menu', 'show_order', 'menu_group'
        ))
            ->order(array(
                'parent' => 'asc'
            ))
            ->where(array(
                'modul.deleted_at' => 'IS NULL',
                'AND',
                'modul.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\''
            ))
            ->limit(10)
            ->execute();
        return $query;
    }
	
	public function get_all() {
		//$query = self::$pdo->prepare('SELECT * FROM "public"."modul" WHERE deleted_at IS NULL');
        $query = self::$query->select('modul', array(
            'id', 'level', 'nama', 'identifier', 'keterangan', 'created_at', 'updated_at', 'deleted_at', 'parent', 'icon', 'show_on_menu', 'show_order', 'menu_group'
        ))
            ->order(array(
                'parent' => 'asc'
            ))
            ->where(array(
                'modul.deleted_at' => 'IS NULL'
            ))
            ->execute();
		//$query->execute();
		//$read = $query->fetchAll(\PDO::FETCH_ASSOC);
		return $query['response_data'];
	}

	private function reload_methods() {
		$methodsCheck = array();
		$usedMethod = array();
		foreach (parent::getClassesInNamespace(__NAMESPACE__) as $key => $value) {
			$classDetect = new \ReflectionClass($value);
			//\ReflectionMethod::IS_PUBLIC
			$methodsDetect = $classDetect->getMethods();
			foreach ($methodsDetect as $MKey => $MValue) {
				if(!in_array($MValue->class . '->' . $MValue->name, $methodsCheck)) {

					array_push($methodsCheck, $MValue->class . '->' . $MValue->name);

					$classFormatter = str_replace('\\\\', '\\', $MValue->class);
					$methodsFormatter = str_replace('\\\\', '\\', $MValue->name);

					$check = self::$query
						->select('akses', array(
							'id',
							'class_name'
						))

						->where(array(
							'akses.deleted_at' => 'IS NULL',
							'AND',
							'akses.class_name' => '= ?',
							'AND',
							'akses.methods_name' => '= ?'
						), array(
							$classFormatter,
							$methodsFormatter
						))

						->execute();

					if(count($check["response_data"]) > 0) {
						$update_methods = self::$query
							->update('akses', array(
								'class_name' => $classFormatter,
								'methods_name' => $methodsFormatter,
								'updated_at' => parent::format_date()
							))

							->where(array(
								'deleted_at' => 'IS NULL',
								'AND',
								'akses.class_name' => '= ?',
								'AND',
								'akses.methods_name' => '= ?'
							), array(
								$classFormatter,
								$methodsFormatter
							))

							->execute();
					} else {
						$update_methods = self::$query
							->insert('akses', array(
								'class_name' => $classFormatter,
								'methods_name' => $methodsFormatter,
								'remark' => '',
								'created_at' => parent::format_date(),
								'updated_at' => parent::format_date()
							))

							->execute();
					}

					if($update_methods['response_result'] > 0) {
						if(!in_array($classFormatter . '->' . $methodsFormatter, $usedMethod)) {
							array_push($usedMethod, $classFormatter . '->' . $methodsFormatter);
						}
					}
				}
			}
		}

		$checkUnusedMethod = self::get_methods(0);
		foreach ($checkUnusedMethod['response_data'] as $key => $value) {
			if(!in_array($value['class_name'] . '->' . $value['methods_name'], $usedMethod)) {
				$reportUnUsedCheck = self::$query
					->delete('akses')

					->where(array(
						'akses.id' => '= ?'
					), array(
						$value['id']
					));
			}
		}

		return self::get_methods(0);
	}

	private function get_methods($parameter = 0) {
		if($parameter > 0) {

			$methods = self::$query->select('akses', array(
				'id',
				'class_name',
				'caption',
				'remark',
				'methods_name',
				'created_at',
				'updated_at'
			))

			->where(array(
				'akses.deleted_at' => 'IS NULL',
				'AND',
				'akses.id' => '= ?'
			), array(
				$parameter
			))

			->execute();;
			
			$autoNum = 1;
			
			foreach ($methods['response_data'] as $key => $value) {
				$methods['response_data'][$key]['autoNum'] = $autoNum;
				$autoNum++;
			}
			return $methods;
		} else {
			$methods = self::$query->select('akses', array(
				'id',
				'class_name',
				'caption',
				'remark',
				'methods_name',
				'created_at',
				'updated_at'
			))

			->where(array(
				'akses.deleted_at' => 'IS NULL'
			), array())

			->order(array(
				'id' => 'asc'
			))

			->execute();;

			$autoNum = 1;

			foreach ($methods['response_data'] as $key => $value) {
				$methods['response_data'][$key]['excluded'] = in_array($methods['response_data'][$key]['methods_name'], __EXCLUDE_METHOD__);
				$methods['response_data'][$key]['autoNum'] = $autoNum;
				$autoNum++;
			}

			return $methods;
		}
	}

	private function get_methods_tree() {
		$treeData = array();
		$getData = self::get_methods();

		foreach ($getData['response_data'] as $key => $value) {
			if(!is_array($treeData[$value['class_name']]) && count($treeData[$value['class_name']]) == 0) {
				$treeData[$value['class_name']] = array();
			}
			if(!in_array($value['methods_name'], __EXCLUDE_METHOD__)) {
				array_push($treeData[$value['class_name']], array(
					'id' => $value['id'],
					'name' => $value['methods_name']
				));
			}
		}

		return $treeData;
	}


	public function get_detail($parameter) {
		$query = self::$pdo->prepare('SELECT * FROM modul WHERE id = ? AND deleted_at IS NULL');
		$query->execute(array($parameter['id']));
		$read = $query->fetchAll(\PDO::FETCH_ASSOC);
		return $read[0];
	}

	public function get_tree($parent) {
		$arrayData = array();
		$query = self::$pdo->prepare('SELECT * FROM modul WHERE parent = ? AND deleted_at IS NULL ORDER BY show_order ASC');
		$query->execute(array($parent));
		$read = $query->fetchAll(\PDO::FETCH_ASSOC);
		foreach ($read as $key => $value) {


			$child = self::$pdo->prepare('SELECT * FROM modul WHERE parent = ? AND deleted_at IS NULL');
			$child->execute(array($value['id']));

			array_push($arrayData, array(
				'id' => $value['id'],
				'data' => array(
					'childCount' => $child->rowCount(),
					'nama' => $value['nama'],
					'parent' => $value['parent'],
					'group_color' => $value['group_color'],
					'identifier' => $value['identifier'],
					'keterangan' => $value['keterangan'],
					'icon' => $value['icon'],
					'show_on_menu' => $value['show_on_menu'],
					'show_order' => $value['show_order'],
					'menu_group' => $value['menu_group']
				),
				'text' => $value['nama'],
				'state' => array(
					'opened' => true,
					'selected' => false
				),
				'children' => self::get_tree($value['id'])			
			));
		}
		return $arrayData;
	}
}