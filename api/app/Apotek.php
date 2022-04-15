<?php

namespace PondokCoder;

use PondokCoder\Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Antrian as Antrian;
use PondokCoder\Poli as Poli;
use PondokCoder\Inventori as Inventori;
use PondokCoder\Invoice as Invoice;
use PondokCoder\Utility as Utility;


class Apotek extends Utility
{
  static $pdo;
  static $query;

  protected static function getConn()
  {
    return self::$pdo;
  }

  public function __construct($connection)
  {
    self::$pdo = $connection;
    self::$query = new Query(self::$pdo);
  }

  public function __GET__($parameter = array())
  {
    try {
      switch ($parameter[1]) {
        case 'detail_resep':
          return self::detail_resep($parameter[2]);
          break;
        case 'detail_resep_lunas':
          return self::detail_resep($parameter[2], 'L');
          break;
        case 'detail_resep_verifikator':
          return self::detail_resep_verifikator($parameter[2]);
        case 'detail_resep_verifikator_2':
          return self::detail_resep_verifikator_2($parameter[2]);
          break;
        case 'detail_resep_2':
          return self::detail_resep_2($parameter[2]);
          break;
        case 'lunas':
          return self::get_resep('L');
          break;
        case 'selesai':
          return self::get_resep('D');
          break;
        case 'panggil':
          return self::get_resep('P');
          break;
        case 'serah':
          return self::get_resep('S');
          break;
        default:
          return self::get_resep();
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  public function __POST__($parameter = array())
  {
    try {
      switch ($parameter['request']) {
        case 'check_invoice_obat':
          return self::check_invoice_obat($parameter);
          break;
        case 'sync_harga_obat_resep':
          return self::sync_harga_obat_resep($parameter);
          break;
        case 'revisi_resep':
          //return self::revisi_resep($parameter);
          return array();
          break;
        case 'verifikasi_resep':
          return self::verifikasi_resep($parameter);
          break;
        case 'verifikasi_resep_2':
          return self::verifikasi_resep_2($parameter);
          break;
        case 'get_resep_backend':
          return self::get_resep_backend($parameter);
          break;
        case 'get_resep_backend_v2':
          return self::get_resep_backend_v2($parameter);
          break;
        case 'get_resep_lunas_backend':
          $parameter['status'] = 'L';
          return self::get_resep_backend_v2($parameter);
          break;
        case 'get_resep_backend_v3':
          return self::get_resep_backend_v3($parameter);
          break;
        case 'get_resep_dokter':
          return self::get_resep_dokter($parameter);
          break;
        case 'resep_inap':
          return self::resep_inap($parameter);
          break;
        case 'resep_igd':
          return self::resep_igd($parameter);
          break;
        case 'batalkan_resep':
          return self::batalkan_resep($parameter);
          break;
        case 'aktifkan_resep':
          return self::aktifkan_resep($parameter);
          break;
        case 'extend_resep':
          return self::extend_resep($parameter);
          break;
        case 'get_resep_selesai_backend':
          /*$parameter['status'] = 'D';
                    $selesai = self::get_resep_backend($parameter);

                    $parameter['status'] = 'P';
                    $panggil = self::get_resep_backend($parameter);

                    $parameter['status'] = 'S';
                    $terima = self::get_resep_backend($parameter);

                    $recordsTotal = $selesai['recordsTotal'] + $panggil['recordsTotal'] + $terima['recordsTotal'];
                    $recordsFiltered = $selesai['recordsFiltered'] + $panggil['recordsFiltered'] + $terima['recordsFiltered'];

                    $allData = array_merge(array_merge($terima['response_data'], $panggil['response_data']), $selesai['response_data']);
                    $autonum = 1;
                    foreach ($allData as $key => $value) {
                        $allData[$key]['autonum'] = $autonum;
                        $autonum++;
                    }

                    $terima['response_data'] = $allData;
                    $terima['recordsFiltered'] = $recordsFiltered;
                    $terima['recordsTotal'] = $recordsTotal;*/



          //return self::get_resep_serah_backend($parameter);
          /*$parameter['status'] = 'D';
                    return self::get_resep_backend_v2($parameter);*/

          return self::get_resep_backend_v3($parameter);

          break;
        case 'get_resep_igd':
          $parameter['status'] = 'L';
          return self::get_resep_backend_v2($parameter);
          break;
        case 'get_resep_inap':
          $parameter['status'] = 'K';
          return self::get_resep_backend_v2($parameter);
          break;
        case 'proses_resep':
          return self::proses_resep($parameter);
          break;
        case 'panggil_antrian_selesai':
          return self::panggil_antrian_selesai($parameter);
          break;
        case 'serah_antrian_selesai':
          return self::serah_antrian_selesai($parameter);
          break;
        case 'detail_resep_verifikator_post':
          return self::detail_resep_verifikator_post($parameter);
          break;
        default:
          //return self::get_resep();
          return $parameter;
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  private function panggil_antrian_selesai($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $worker = self::$query->update('resep', array(
      'status_resep' => 'P',
      'waktu_panggil' => parent::format_date(),
      'dipanggil_oleh' => $UserData['data']->uid
    ))
      ->where(array(
        'resep.uid' => '= ?',
        'AND',
        'resep.deleted_at' => 'IS NULL'
      ), array(
        $parameter['uid']
      ))
      ->execute();
    return $worker;
  }

  private function serah_antrian_selesai($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $worker = self::$query->update('resep', array(
      'status_resep' => 'S',
      'waktu_terima' => parent::format_date(),
      'diserahkan_oleh' => $UserData['data']->uid
    ))
      ->where(array(
        'resep.uid' => '= ?',
        'AND',
        'resep.deleted_at' => 'IS NULL'
      ), array(
        $parameter['uid']
      ))
      ->execute();
    return $worker;
  }

  private function check_invoice_obat($parameter)
  {
    $SInvoice = array();
    //Sync Document
    $Resep = self::$query->select('resep', array(
      'uid', 'asesmen', 'kode', 'pasien', 'kunjungan'
    ))
      ->where(array(
        'resep.created_at' => 'BETWEEN ? AND ?'
      ), array(
        $parameter['from'],
        $parameter['to']
      ))
      ->execute();
    foreach ($Resep['response_data'] as $RKey => $RValue) {

      $InvoiceMaster = self::$query->select('invoice', array(
        'uid'
      ))
        ->join('antrian', array(
          'penjamin', 'departemen'
        ))
        ->on(array(
          array('antrian.kunjungan', '=', 'invoice.kunjungan', ' AND ', 'antrian.pasien', '=', 'invoice.pasien')
        ))
        ->where(array(
          'invoice.kunjungan' => '= ?',
          'AND',
          'invoice.pasien' => '= ?'
        ), array(
          $RValue['kunjungan'],
          $RValue['pasien']
        ))
        ->execute();


      $ResepDetail = self::$query->select('resep_change_log', array(
        'item as obat', 'qty'
      ))
        ->where(array(
          'resep_change_log.resep' => '= ?'
        ), array(
          $RValue['uid']
        ))
        ->execute();
      foreach ($ResepDetail['response_data'] as $RDKey => $RDValue) {

        //Check Charged Invoice
        $CheckResep = self::$query->select('invoice_detail', array(
          'id', 'qty', 'penjamin', 'departemen', 'pasien'
        ))
          ->where(array(
            'invoice_detail.item' => '= ?',
            'AND',
            'invoice_detail.pasien' => '= ?',
            'AND',
            'invoice_detail.item_type' => '= ?',
            'AND',
            'invoice_detail.keterangan' => '= ?',
            'AND',
            'invoice_detail.created_at' => 'BETWEEN ? AND ?'
          ), array(
            $RDValue['obat'],
            $RValue['pasien'],
            'master_inv',
            'Biaya resep obat',
            $parameter['from'],
            $parameter['to']
          ))
          ->execute();

        if (count($CheckResep['response_data']) > 0) {
          $InvoiceDetail = self::$query->update('invoice_detail', array(
            'document' => 'RESEP' . $RValue['kode']
          ))
            ->where(array(
              'invoice_detail.item' => '= ?',
              'AND',
              'invoice_detail.pasien' => '= ?',
              'AND',
              '(invoice_detail.document' => 'IS NULL',
              'OR',
              ' invoice_detail.document' => '= ?',
              'OR',
              'invoice_detail.document' => '= ?)',
              'AND',
              'invoice_detail.item_type' => '= ?',
              'AND',
              'invoice_detail.keterangan' => '= ?',
              'AND',
              'invoice_detail.created_at' => 'BETWEEN ? AND ?'
            ), array(
              $RDValue['obat'],
              $RValue['pasien'],
              '',
              '-',
              'master_inv',
              'Biaya resep obat',
              $parameter['from'],
              $parameter['to']
            ))
            ->execute();
        } else {
          $InvoiceDetail = self::$query->insert('invoice_detail', array(
            'invoice' => $InvoiceMaster['response_data'][0]['uid'],
            'item' => $RDValue['obat'],
            'item_type' => 'master_inv',
            'qty' => $RDValue['qty'],
            'harga' => 0,
            'status_bayar' => 'Y',
            'subtotal' => 0,
            'discount' => 0,
            'discount_type' => 'N',
            'pasien' => $RValue['pasien'],
            'penjamin' => $InvoiceMaster['response_data'][0]['penjamin'],
            'billing_group' => 'obat',
            'keterangan' => 'Biaya resep obat',
            'document' => 'RESEP' . $RValue['kode'],
            'departemen' => $InvoiceMaster['response_data'][0]['departemen'],
            'created_at' => date($parameter['created'] . ' H:i:s'),
            'updated_at' => date($parameter['created'] . ' H:i:s')
          ))
            ->execute();
        }

        array_push($SInvoice, $InvoiceDetail);
      }

      $RacikanMaster = self::$query->select('racikan', array(
        'uid'
      ))
        ->where(array(
          'racikan.asesmen' => '= ?'
        ), array(
          $RValue['asesmen']
        ))
        ->execute();

      foreach ($RacikanMaster['response_data'] as $RMKey => $RMValue) {
        //Racikan Code
        $Racikan = self::$query->select('racikan_detail_change_log', array(
          'obat'
        ))
          ->where(array(
            'racikan_detail.racikan' => '= ?'
          ), array(
            $RMValue['uid']
          ))
          ->execute();

        foreach ($Racikan['response_data'] as $RacKey => $RacValue) {
          $CheckResep = self::$query->select('invoice_detail', array(
            'id', 'qty', 'penjamin', 'departemen', 'pasien'
          ))
            ->where(array(
              'invoice_detail.item' => '= ?',
              'AND',
              'invoice_detail.pasien' => '= ?',
              'AND',
              'invoice_detail.item_type' => '= ?',
              'AND',
              'invoice_detail.keterangan' => '= ?',
              'AND',
              'invoice_detail.created_at' => 'BETWEEN ? AND ?'
            ), array(
              $RDValue['obat'],
              $RValue['pasien'],
              'master_inv',
              'Biaya resep obat',
              $parameter['from'],
              $parameter['to']
            ))
            ->execute();

          if (count($CheckResep['response_data']) > 0) {
            $InvoiceDetail = self::$query->update('invoice_detail', array(
              'document' => 'RACIKAN' . $RValue['kode']
            ))
              ->where(array(
                'invoice_detail.item' => '= ?',
                'AND',
                'invoice_detail.pasien' => '= ?',
                'AND',
                '(invoice_detail.document' => 'IS NULL',
                'OR',
                ' invoice_detail.document' => '= ?',
                'OR',
                'invoice_detail.document' => '= ?)',
                'AND',
                'invoice_detail.item_type' => '= ?',
                'AND',
                'invoice_detail.keterangan' => '= ?',
                'AND',
                'invoice_detail.created_at' => 'BETWEEN ? AND ?'
              ), array(
                $RacValue['obat'],
                $RValue['pasien'],
                '',
                '-',
                'master_inv',
                'Biaya racikan obat',
                $parameter['from'],
                $parameter['to']
              ))
              ->execute();
          } else {
            $InvoiceDetail = self::$query->insert('invoice_detail', array(
              'invoice' => $InvoiceMaster['response_data'][0]['uid'],
              'item' => $RDValue['obat'],
              'item_type' => 'master_inv',
              'qty' => $RDValue['qty'],
              'harga' => 0,
              'status_bayar' => 'Y',
              'subtotal' => 0,
              'discount' => 0,
              'discount_type' => 'N',
              'pasien' => $RValue['pasien'],
              'penjamin' => $InvoiceMaster['response_data'][0]['penjamin'],
              'billing_group' => 'obat',
              'keterangan' => 'Biaya racikan obat',
              'document' => 'RACIKAN' . $RValue['kode'],
              'departemen' => $InvoiceMaster['response_data'][0]['departemen'],
              'created_at' => date($parameter['created'] . ' H:i:s'),
              'updated_at' => date($parameter['created'] . ' H:i:s')
            ))
              ->execute();
          }
          array_push($SInvoice, $InvoiceDetail);
        }
      }
    }

    $updated_harga = array();
    $POList = self::$query->select('inventori_po', array(
      'uid', 'tanggal_po'
    ))
      ->where(array(
        'inventori_po.deleted_at' => "IS NULL"
      ), array())
      ->order(array(
        'tanggal_po' => 'ASC'
      ))
      ->execute();

    foreach ($POList['response_data'] as $key => $value) {
      $PODet = self::$query->select('inventori_po_detail', array(
        'barang', 'harga'
      ))
        ->where(array(
          'inventori_po_detail.po' => '= ?',
          'AND',
          'inventori_po_detail.harga' => '> 0',
          'AND',
          'inventori_po_detail.qty' => '> 0'
        ), array(
          $value['uid']
        ))
        ->order(array(
          'inventori_po_detail.id' => 'DESC',
          'inventori_po_detail.harga' => 'ASC'
        ))
        ->execute();
      foreach ($PODet['response_data'] as $DKey => $DValue) {
        $getQtyCharged = self::$query->select('invoice_detail', array(
          'id', 'qty'
        ))
          ->where(array(
            'invoice_detail.item' => '= ?',
            'AND',
            'invoice_detail.item_type' => '= ?',
            'AND',
            '(invoice_detail.created_at' => 'BETWEEN ? AND ?)'
          ), array(
            $DValue['barang'],
            'master_inv',
            $parameter['from'],
            $parameter['to']
          ))
          ->execute();
        if (count($getQtyCharged['response_data']) > 0) {
          foreach ($getQtyCharged['response_data'] as $DKKey => $DKValue) {
            $totalCharged = floatval($getQtyCharged['response_data'][0]['qty']) * floatval($DValue['harga']);
            $UpdateInvoiceDetail = self::$query->update('invoice_detail', array(
              'harga' => floatval($DValue['harga']),
              'subtotal' => $totalCharged
            ))
              ->where(array(
                'invoice_detail.id' => '= ?'
              ), array(
                $DKValue['id']
              ))
              ->execute();

            if ($UpdateInvoiceDetail['response_result'] > 0) {
              //array_push($updated_harga, $UpdateInvoiceDetail);
            }
          }
        }
      }
    }

    return $SInvoice;
  }

  private function sync_harga_obat_resep($parameter)
  {
    $updated_harga = array();
    $POList = self::$query->select('inventori_po', array(
      'uid', 'tanggal_po'
    ))
      ->where(array(
        'inventori_po.deleted_at' => "IS NULL"
      ), array())
      ->order(array(
        'tanggal_po' => 'ASC'
      ))
      ->execute();

    foreach ($POList['response_data'] as $key => $value) {
      $PODet = self::$query->select('inventori_po_detail', array(
        'barang', 'harga'
      ))
        ->where(array(
          'inventori_po_detail.po' => '= ?'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($PODet['response_data'] as $DKey => $DValue) {
        $UpdateHargaResep = self::$query->update('resep_detail', array(
          'harga' => floatval($DValue['harga'])
        ))
          ->where(array(
            'resep_detail.created_at' => '> ?',
            'AND',
            'resep_detail.obat' => '= ?',
          ), array(
            date('Y-m-d', strtotime($value['tanggal_po'])),
            $DValue['barang']
          ))
          ->execute();

        if ($UpdateHargaResep['response_result'] > 0) {

          $getQtyCharged = self::$query->select('invoice_detail', array(
            'id', 'qty'
          ))
            ->where(array(
              'invoice_detail.created_at' => 'BETWEEN ? AND ?',
              'AND',
              'invoice_detail.item' => '= ?',
              'AND',
              'invoice_item.item_type' => '= ?'
            ), array(
              $parameter['from'],
              $parameter['to'],
              'master_inv'

            ))
            ->execute();
          array_push($updated_harga, $getQtyCharged['response_data']);
          if (floatval($getQtyCharged['response_data'][0]) > 0) {
            // $UpdateInvoiceDetail = self::$query->update('invoice_detail', array(
            //     'harga' => floatval($DValue['harga']),
            //     'subtotal' => floatval($getQtyCharged['response_data'][0]) * floatval($DValue['harga'])
            // ))
            //     ->where(array(
            //         'invoice_detail.id' => '= ?'
            //     ), array(
            //         $getQtyCharged['response_data']
            //     ))
            //     ->execute();
          }
        }

        $UpdateHargaRacikan = self::$query->update('racikan_detail', array(
          'harga' => floatval($DValue['harga'])
        ))
          ->where(array(
            'racikan_detail.created_at' => '> ?',
            'AND',
            'racikan_detail.obat' => '= ?',
          ), array(
            date('Y-m-d', strtotime($value['tanggal_po'])),
            $DValue['barang']
          ))
          ->execute();
        if ($UpdateHargaRacikan['response_result'] > 0) {
          array_push($updated_harga, $UpdateHargaRacikan);
        }
      }
    }

    return $updated_harga;
  }

  private function revisi_resep($parameter)
  {
    //
  }

  private function proses_resep($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);







    $usedBatch = array();
    $usedBatchInap = array();
    $rawBatch = array();
    $Inventori = new Inventori(self::$pdo);

    //Potong Stok
    /*$resepItem = self::$query->select('resep_detail', array(
            'obat',
            'qty',
            'penjamin'
        ))
            ->where(array(
                'resep_detail.resep' => '= ?',
                'AND',
                'resep_detail.deleted_at' => 'IS NULL'
            ), array(
                $parameter['resep']
            ))
            ->execute();*/
    $resepItem = self::$query->select('resep_change_log', array(
      'item',
      'batch',
      'qty'
    ))
      ->where(array(
        'resep_change_log.resep' => '= ?',
        'AND',
        'resep_change_log.deleted_at' => 'IS NULL'
      ), array(
        $parameter['resep']
      ))
      ->execute();
    foreach ($resepItem['response_data'] as $key => $value) {

      // TODO : Set Per Batch Hilangkan

      array_push($usedBatch, array(
        'batch' => $value['batch'],
        'barang' => $value['item'],
        'gudang' => __GUDANG_APOTEK__,
        'qty' => floatval($value['qty']),
        'temp_stat' => 'resep'
      ));

      // Potong Batch terdekat
      $InventoriBatch = $Inventori->get_item_batch($value['item']);

      $kebutuhan = floatval($value['qty']);
      foreach ($InventoriBatch['response_data'] as $bKey => $bValue) {
        if ($bValue['gudang']['uid'] === $UserData['data']->gudang) //Ambil gudang dari user yang sedang login
        {
          if ($kebutuhan >= $bValue['stok_terkini']) {
            if ($parameter['departemen'] === __POLI_INAP__ || $parameter['departemen'] === __POLI_IGD__) {
              //Racikan tidak usah charge stok karena stok dianggap habis diproses
              if ($bValue['stok_terkini'] > 0) {
                array_push($usedBatchInap, array(
                  'batch' => $bValue['batch'],
                  'barang' => $value['item'],
                  'gudang' => $bValue['gudang']['uid'],
                  'qty' => $bValue['stok_terkini']
                ));
                $kebutuhan -= $bValue['stok_terkini'];
              }
            }

            if ($bValue['stok_terkini'] > 0) {
              array_push($usedBatch, array(
                'batch' => $bValue['batch'],
                'barang' => $value['item'],
                'gudang' => $bValue['gudang']['uid'],
                'qty' => $bValue['stok_terkini']
              ));
              $kebutuhan -= $bValue['stok_terkini'];
            }
          } else {
            if ($parameter['departemen'] === __POLI_INAP__ || $parameter['departemen'] === __POLI_IGD__) {
              if ($kebutuhan > 0) {
                array_push($usedBatchInap, array(
                  'batch' => $bValue['batch'],
                  'barang' => $value['item'],
                  'gudang' => $bValue['gudang']['uid'],
                  'qty' => $kebutuhan
                ));
                $kebutuhan = 0;
              }
            }

            if ($kebutuhan > 0) {
              array_push($usedBatch, array(
                'batch' => $bValue['batch'],
                'barang' => $value['item'],
                'gudang' => $bValue['gudang']['uid'],
                'qty' => $kebutuhan
              ));
              $kebutuhan = 0;
            }
          }
        }
      }
    }

    $racikan_batch_list = array();

    $racikan = self::$query->select('racikan', array(
      'uid',
      'qty'
    ))
      ->where(array(
        'racikan.asesmen' => '= ?',
        'AND',
        'racikan.status' => '= ?',
        'AND',
        'racikan.deleted_at' => 'IS NULL'
      ), array(
        $parameter['asesmen'], ($parameter['penjamin'] !== __UIDPENJAMINUMUM__) ? 'N' : ((($parameter['departemen'] === __POLI_IGD__ || $parameter['departemen'] === __POLI_INAP__) ? 'N' : 'L'))
      ))
      ->execute();
    foreach ($racikan['response_data'] as $rKey => $rValue) {
      $racikan_change = self::$query->select('racikan_change_log', array(
        'racikan',
        'jumlah'
      ))
        ->where(array(
          'racikan_change_log.racikan' => '= ?',
          'AND',
          'racikan_change_log.deleted_at' => 'IS NULL'
        ), array(
          $rValue['uid']
        ))
        ->execute();

      $racikanItem = self::$query->select('racikan_detail_change_log', array(
        'obat', 'jumlah', 'batch'
      ))
        ->where(array(
          'racikan_detail_change_log.racikan' => '= ?',
          'AND',
          'racikan_detail_change_log.deleted_at' => 'IS NULL'
        ), array(
          $rValue['uid']
        ))
        ->execute();

      $racikan['response_data'][$rKey]['detail'] = $racikanItem['response_data'];

      foreach ($racikanItem['response_data'] as $rIKey => $rIValue) {
        array_push($usedBatch, array(
          'batch' => $rIValue['batch'],
          'barang' => $rIValue['obat'],
          'gudang' => __GUDANG_APOTEK__,
          'qty' => $rIValue['jumlah'],
          'temp_stat' => 'racikan'
        ));
        // $InventoriBatch = $Inventori->get_item_batch($rIValue['obat']);

        // $kebutuhan = floatval($rIValue['jumlah']);

        // foreach ($InventoriBatch['response_data'] as $bKey => $bValue) {

        //     if($bValue['gudang']['uid'] === $UserData['data']->gudang) //Ambil gudang dari user yang sedang login
        //     {
        //         if($kebutuhan >= $bValue['stok_terkini'])
        //         {
        //             if(floatval($bValue['stok_terkini']) > 0) {
        //                 array_push($usedBatch, array(
        //                     'batch' => $bValue['batch'],
        //                     'barang' => $rIValue['obat'],
        //                     'gudang' => $bValue['gudang']['uid'],
        //                     'qty' => $bValue['stok_terkini']
        //                 ));
        //                 $kebutuhan -= $bValue['stok_terkini'];
        //             }
        //         } else {
        //             if($kebutuhan > 0) {
        //                 array_push($usedBatch, array(
        //                     'batch' => $bValue['batch'],
        //                     'barang' => $rIValue['obat'],
        //                     'gudang' => $bValue['gudang']['uid'],
        //                     'qty' => $kebutuhan
        //                 ));
        //                 $kebutuhan = 0;
        //             }
        //         }
        //     }
        // }
      }
    }
    $itemMutasi = array();
    // $log = parent::log(array(
    //   'type' => 'error',
    //   'column' => array(
    //     'type_err',
    //     'class_err',
    //     'logged_at',
    //     'message'
    //   ),
    //   'value' => array(
    //     'CRITICAL',
    //     'APOTEK',
    //     parent::format_date(),
    //     'MUTASI RANAP TEST'
    //   ),
    //   'class' => __CLASS__
    // ));

    if ($parameter['departemen'] === __POLI_INAP__ || $parameter['departemen'] === __POLI_IGD__) {
      if ($parameter['departemen'] === __POLI_INAP__) {
        if (count($usedBatchInap) > 0) {
          foreach ($usedBatchInap as $bKey => $bValue) {
            if (!isset($itemMutasi[$bValue['barang'] . '|' . $bValue['batch']])) {
              $itemMutasi[$bValue['barang'] . '|' . $bValue['batch']] = array(
                'mutasi' => $bValue['qty'],
                'keterangan' => 'Mutasi kebutuhan rawat inap'
              );
            }
          }

          //Ambil informasi nurse station dan gudang tujuan dari rawat inap
          $RawatInap = self::$query->select('rawat_inap', array(
            'nurse_station',
            'pasien',
            'dokter'
          ))
            ->join('nurse_station', array(
              'kode as kode_ns',
              'nama as nama_ns',
              'unit'
            ))
            ->join('master_unit', array(
              'kode as kode_unit',
              'nama as nama_unit',
              'gudang'
            ))
            ->on(array(
              array('rawat_inap.nurse_station', '=', 'nurse_station.uid'),
              array('nurse_station.unit', '=', 'master_unit.uid')
            ))
            ->where(array(
              'rawat_inap.kunjungan' => '= ?',
              'AND',
              'rawat_inap.dokter' => '= ?',
              'AND',
              'rawat_inap.penjamin' => '= ?',
              'AND',
              'rawat_inap.deleted_at' => 'IS NULL',
              'AND',
              'nurse_station.deleted_at' => 'IS NULL'
            ), array(
              /*$parameter['antrian']['kunjungan'],
                            $parameter['antrian']['dokter'],
                            $parameter['antrian']['penjamin']*/
              $parameter['kunjungan'],
              $parameter['dokter']['uid'],
              $parameter['penjamin']
            ))
            ->execute();

          //TODO : Sini seharusnya kalo masa opname
          $CheckGudangStatus = $Inventori->get_gudang_detail($UserData['data']->gudang)['response_data'][0];
          if ($CheckGudangStatus['status'] === 'A') {
            $Mutasi = $Inventori->tambah_mutasi(array(
              'access_token' => $parameter['access_token'],
              'dari' => $UserData['data']->gudang,
              'ke' => $RawatInap['response_data'][0]['gudang'],
              'keterangan' => 'Kebutuhan Resep Rawat Inap untuk pasien a.n. ' . $parameter['nama_pasien'],
              'status' => 'N',
              'mut_resep_pasien' => $parameter['nama_pasien'],
              'special_code_out' => __STATUS_BARANG_KELUAR_INAP__,
              'special_code_in' => __STATUS_BARANG_MASUK_INAP__,
              //'apotek_order' => true,
              'item' => $itemMutasi
            ));

            $log = parent::log(array(
              'type' => 'error',
              'column' => array(
                'type_err',
                'class_err',
                'logged_at',
                'message'
              ),
              'value' => array(
                'CRITICAL',
                'APOTEK',
                parent::format_date(),
                'MASUK A RANAP'
              ),
              'class' => __CLASS__
            ));

            if ($Mutasi['response_result'] > 0) {

              //Update batch rawat inap
              foreach ($itemMutasi as $mutBatch => $mutValue) {
                if (floatval($mutValue['mutasi']) > 0) {
                  $BarangBatch = explode('|', $mutBatch);
                  $inapBatch = self::$query->select('rawat_inap_batch', array(
                    'id'
                  ))
                    ->where(array(
                      'rawat_inap_batch.obat' => '= ?',
                      'AND',
                      'rawat_inap_batch.batch' => '= ?',
                      'AND',
                      'rawat_inap_batch.resep' => '= ?',
                      'AND',
                      'rawat_inap_batch.gudang' => '= ?',
                      'AND',
                      'rawat_inap_batch.deleted_at' => 'IS NULL'
                    ), array(
                      $BarangBatch[0],
                      $BarangBatch[1],
                      $parameter['resep'],
                      $RawatInap['response_data'][0]['gudang']
                    ))
                    ->execute();
                  if (count($inapBatch['response_data']) > 0) {
                    $updateBatchInap = self::$query->update('rawat_inap_batch', array(
                      'status' => 'N',
                      'qty' => floatval($inapBatch['response_data'][0]['qty']) + floatval($mutValue['mutasi']),
                      'mutasi' => $Mutasi['response_unique'],
                      'updated_at' => parent::format_date()
                    ))
                      ->where(array(
                        'rawat_inap_batch.id' => '= ?'
                      ), array(
                        $inapBatch['response_data'][0]['id']
                      ))
                      ->execute();
                  } else {
                    $updateBatchInap = self::$query->insert('rawat_inap_batch', array(
                      'status' => 'N',
                      'gudang' => $RawatInap['response_data'][0]['gudang'],
                      'pasien' => $RawatInap['response_data'][0]['pasien'],
                      'resep' => $parameter['resep'],
                      'obat' => $BarangBatch[0],
                      'batch' => $BarangBatch[1],
                      'qty' => floatval($mutValue['mutasi']),
                      'mutasi' => $Mutasi['response_unique'],
                      'created_at' => parent::format_date(),
                      'updated_at' => parent::format_date()
                    ))
                      ->execute();
                  }
                }
              }

              // $updateResep = self::$query->update('resep', array(
              //     'status_resep' => 'D'
              // ))
              //     ->where(array(
              //         'resep.uid' => '= ?'
              //     ), array(
              //         $parameter['resep']
              //     ))
              //     ->execute();

              // $updateRacikan = self::$query->update('racikan', array(
              //     'status' => 'D'
              // ))
              //     ->where(array(
              //         'racikan.asesmen' => '= ?',
              //         'AND',
              //         'racikan.status' => '= ?',
              //         'AND',
              //         'racikan.deleted_at' => 'IS NULL'
              //     ), array(
              //         $parameter['asesmen'],
              //         'L'
              //     ))
              //     ->execute();
            } else {
              // Log Query Error

              $log = parent::log(array(
                'type' => 'error',
                'column' => array(
                  'type_err',
                  'class_err',
                  'logged_at',
                  'message'
                ),
                'value' => array(
                  'CRITICAL',
                  'APOTEK',
                  parent::format_date(),
                  'MUTASI RANAP - ' . json_encode(array(
                    'access_token' => $parameter['access_token'],
                    'dari' => $UserData['data']->gudang,
                    'ke' => $RawatInap['response_data'][0]['gudang'],
                    'keterangan' => 'Kebutuhan Resep Rawat Inap untuk pasien a.n. ' . $parameter['nama_pasien'],
                    'status' => 'N',
                    'mut_resep_pasien' => $parameter['nama_pasien'],
                    'special_code_out' => __STATUS_BARANG_KELUAR_INAP__,
                    'special_code_in' => __STATUS_BARANG_MASUK_INAP__,
                    //'apotek_order' => true,
                    'item' => $itemMutasi
                  ))
                ),
                'class' => __CLASS__
              ));
            }
          } else {
            //Temp Stok masa mutasi
            foreach ($itemMutasi as $mutBatch => $mutValue) {
              $BarangBatch = explode('|', $mutBatch);
              $Virtual = $Inventori->virtual_stok(array(
                'transact_table' => 'resep',
                'transact_iden' => $parameter['resep'],
                'gudang_asal' => $UserData['data']->gudang,
                'gudang_tujuan' => $RawatInap['response_data'][0]['gudang'],
                'barang' => $BarangBatch[0],
                'batch' => $BarangBatch[1],
                'qty' => floatval($mutValue['mutasi']),
                'remark' => 'Reserved stok resep untuk mutasi rawat inap untuk pasien a.n. ' . $parameter['nama_pasien']
              ));
            }

            $log = parent::log(array(
              'type' => 'error',
              'column' => array(
                'type_err',
                'class_err',
                'logged_at',
                'message'
              ),
              'value' => array(
                'CRITICAL',
                'APOTEK',
                parent::format_date(),
                'STATUS GUDANG INAP'
              ),
              'class' => __CLASS__
            ));
          }
        }
      } else if ($parameter['departemen'] === __POLI_IGD__) {
        $log = parent::log(array(
          'type' => 'error',
          'column' => array(
            'type_err',
            'class_err',
            'logged_at',
            'message'
          ),
          'value' => array(
            'CRITICAL',
            'APOTEK',
            parent::format_date(),
            'NS IGD'
          ),
          'class' => __CLASS__
        ));
        if (count($usedBatchInap) > 0) {
          foreach ($usedBatchInap as $bKey => $bValue) {
            if (!isset($itemMutasi[$bValue['barang'] . '|' . $bValue['batch']])) {
              $itemMutasi[$bValue['barang'] . '|' . $bValue['batch']] = array(
                'mutasi' => $bValue['qty'],
                'keterangan' => 'Mutasi kebutuhan IGD untuk pasien a.n. ' . $parameter['nama_pasien']
              );
            }
          }

          //Ambil informasi nurse station dan gudang tujuan dari rawat inap
          $IGD = self::$query->select('igd', array(
            'nurse_station',
            'pasien',
            'dokter'
          ))
            ->join('nurse_station', array(
              'kode as kode_ns',
              'nama as nama_ns',
              'unit'
            ))
            ->join('master_unit', array(
              'kode as kode_unit',
              'nama as nama_unit',
              'gudang'
            ))
            ->on(array(
              array('igd.nurse_station', '=', 'nurse_station.uid'),
              array('nurse_station.unit', '=', 'master_unit.uid')
            ))
            ->where(array(
              'igd.kunjungan' => '= ?',
              'AND',
              'igd.dokter' => '= ?',
              'AND',
              'igd.penjamin' => '= ?',
              'AND',
              'igd.deleted_at' => 'IS NULL',
              'AND',
              'nurse_station.deleted_at' => 'IS NULL'
            ), array(
              $parameter['kunjungan'],
              $parameter['dokter']['uid'],
              $parameter['penjamin']
            ))
            ->execute();


          $CheckGudangStatus = $Inventori->get_gudang_detail($UserData['data']->gudang)['response_data'][0];
          if ($CheckGudangStatus['status'] === 'A') {
            $Mutasi = $Inventori->tambah_mutasi(array(
              'access_token' => $parameter['access_token'],
              'dari' => $UserData['data']->gudang,
              'ke' => $IGD['response_data'][0]['gudang'],
              'keterangan' => 'Kebutuhan Resep IGD untuk pasien a.n. ' . $parameter['nama_pasien'],
              'status' => 'N',
              'mut_resep_pasien' => $parameter['nama_pasien'],
              'special_code_out' => __STATUS_BARANG_KELUAR_INAP__,
              'special_code_in' => __STATUS_BARANG_MASUK_INAP__,
              //'apotek_order' => true,
              'item' => $itemMutasi
            ));

            if ($Mutasi['response_result'] > 0) {

              //Update batch igd
              foreach ($itemMutasi as $mutBatch => $mutValue) {
                if (floatval($mutValue['mutasi']) > 0) {
                  $BarangBatch = explode('|', $mutBatch);
                  $inapBatch = self::$query->select('igd_batch', array(
                    'id'
                  ))
                    ->where(array(
                      'igd_batch.obat' => '= ?',
                      'AND',
                      'igd_batch.batch' => '= ?',
                      'AND',
                      'igd_batch.resep' => '= ?',
                      'AND',
                      'igd_batch.gudang' => '= ?',
                      'AND',
                      'igd_batch.deleted_at' => 'IS NULL'
                    ), array(
                      $BarangBatch[0],
                      $BarangBatch[1],
                      $parameter['resep'],
                      $IGD['response_data'][0]['gudang']
                    ))
                    ->execute();
                  if (count($inapBatch['response_data']) > 0) {
                    $updateBatchInap = self::$query->update('igd_batch', array(
                      'status' => 'N',
                      'qty' => floatval($inapBatch['response_data'][0]['qty']) + floatval($mutValue['mutasi']),
                      'mutasi' => $Mutasi['response_unique'],
                      'updated_at' => parent::format_date()
                    ))
                      ->where(array(
                        'igd_batch.id' => '= ?'
                      ), array(
                        $inapBatch['response_data'][0]['id']
                      ))
                      ->execute();
                  } else {
                    $updateBatchInap = self::$query->insert('igd_batch', array(
                      'status' => 'N',
                      'gudang' => $IGD['response_data'][0]['gudang'],
                      'pasien' => $IGD['response_data'][0]['pasien'],
                      'resep' => $parameter['resep'],
                      'obat' => $BarangBatch[0],
                      'batch' => $BarangBatch[1],
                      'qty' => floatval($mutValue['mutasi']),
                      'mutasi' => $Mutasi['response_unique'],
                      'created_at' => parent::format_date(),
                      'updated_at' => parent::format_date()
                    ))
                      ->execute();
                  }
                }
              }
            }
          } else {
            $log = parent::log(array(
              'type' => 'error',
              'column' => array(
                'type_err',
                'class_err',
                'logged_at',
                'message'
              ),
              'value' => array(
                'CRITICAL',
                'APOTEK',
                parent::format_date(),
                'STATUS GUDANG IGD'
              ),
              'class' => __CLASS__
            ));
            //Temp Stok masa mutasi
            foreach ($itemMutasi as $mutBatch => $mutValue) {
              $BarangBatch = explode('|', $mutBatch);
              $Virtual = $Inventori->virtual_stok(array(
                'transact_table' => 'resep',
                'transact_iden' => $parameter['resep'],
                'gudang_asal' => $UserData['data']->gudang,
                'gudang_tujuan' => $IGD['response_data'][0]['gudang'],
                'barang' => $BarangBatch[0],
                'batch' => $BarangBatch[1],
                'qty' => floatval($mutValue['mutasi']),
                'remark' => 'Reserved stok resep untuk mutasi rawat inap untuk pasien a.n. ' . $parameter['nama_pasien']
              ));
            }
          }
        }
      }











      //Case Racikan
      $updateResult = 0;
      $updateProgress = array();
      foreach ($usedBatch as $bKey => $bValue) {
        //Stok Sebelum Update
        $getStok = self::$query->select('inventori_stok', array(
          'id',
          'gudang',
          'barang',
          'stok_terkini'
        ))
          ->where(array(
            'inventori_stok.gudang' => '= ?',
            'AND',
            'inventori_stok.barang' => '= ?',
            'AND',
            'inventori_stok.batch' => '= ?'
          ), array(
            $bValue['gudang'],
            $bValue['barang'],
            $bValue['batch']
          ))
          ->execute();


        //Potong Stok
        if (
          floatval($bValue['qty']) > 0 &&
          floatval($getStok['response_data'][0]['stok_terkini']) >= floatval($bValue['qty'])
        ) {
          $CheckGudangStatus = $Inventori->get_gudang_detail($bValue['gudang'])['response_data'][0];

          if ($CheckGudangStatus['status'] === 'A') {
            $targetRacikan = self::$query->select('racikan', array(
              'uid'
            ))
              ->where(array(
                'racikan.asesmen' => '= ?',
                'AND',
                'racikan.status' => '= ?',
                'AND',
                'racikan.deleted_at' => 'IS NULL'
              ), array(
                $parameter['asesmen'],
                ($parameter['penjamin'] !== __UIDPENJAMINUMUM__) ? 'N' : ((($parameter['departemen'] === __POLI_IGD__ || $parameter['departemen'] === __POLI_INAP__) ? 'N' : 'L'))
              ))
              ->execute();

            //Update Temp Stok Status
            $TempStokResep = self::$query->update('inventori_temp_stok', array(
              'status' => 'D'
            ))
              ->where(array(
                'inventori_temp_stok.transact_table' => '= ?',
                'AND',
                'inventori_temp_stok.transact_iden' => '= ?',
                'AND',
                'inventori_temp_stok.barang' => '= ?',
                'AND',
                'inventori_temp_stok.batch' => '= ?'
              ), array(
                'resep', $parameter['resep'], $bValue['barang'], $bValue['batch']
              ))
              ->execute();

            $TempStokRacikan = self::$query->update('inventori_temp_stok', array(
              'status' => 'D'
            ))
              ->where(array(
                'inventori_temp_stok.transact_table' => '= ?',
                'AND',
                'inventori_temp_stok.transact_iden' => '= ?',
                'AND',
                'inventori_temp_stok.barang' => '= ?',
                'AND',
                'inventori_temp_stok.batch' => '= ?'
              ), array(
                'racikan', $targetRacikan['response_data'][0]['uid'], $bValue['barang'], $bValue['batch']
              ))
              ->execute();

            if ($TempStokResep['response_result'] > 0 || $TempStokRacikan['response_result'] > 0) {
              $updateStok = self::$query->update('inventori_stok', array(
                'stok_terkini' => (floatval($getStok['response_data'][0]['stok_terkini']) - floatval($bValue['qty']))
              ))
                ->where(array(
                  'inventori_stok.gudang' => '= ?',
                  'AND',
                  'inventori_stok.barang' => '= ?',
                  'AND',
                  'inventori_stok.batch' => '= ?'
                ), array(
                  $bValue['gudang'],
                  $bValue['barang'],
                  $bValue['batch']
                ))
                ->execute();
              if ($updateStok['response_result'] > 0) {
                //Log Stok
                $stokLog = self::$query->insert('inventori_stok_log', array(
                  'barang' => $bValue['barang'],
                  'batch' => $bValue['batch'],
                  'gudang' => $bValue['gudang'],
                  'masuk' => 0,
                  'keluar' => floatval($bValue['qty']),
                  'saldo' => (floatval($getStok['response_data'][0]['stok_terkini']) - floatval($bValue['qty'])),
                  'type' => __STATUS_BARANG_KELUAR__,
                  'jenis_transaksi' => 'resep',
                  'uid_foreign' => $parameter['resep'],
                  'keterangan' => ''
                ))
                  ->execute();
                $updateResult += $stokLog['response_result'];
              }
              array_push($updateProgress, $updateStok);
            }
          }
        } else {
          array_push($updateProgress, $getStok);
        }
      }
      // if($updateResult === (count($usedBatch) + count($usedBatchInap))) {

      // }

      $updateResep = self::$query->update('resep', array(
        'status_resep' => 'D'
      ))
        ->where(array(
          'resep.uid' => '= ?'
        ), array(
          $parameter['resep']
        ))
        ->execute();

      //Update Racikan
      $updateRacikan = self::$query->update('racikan', array(
        'status' => 'D'
      ))
        ->where(array(
          'racikan.asesmen' => '= ?',
          'AND',
          'racikan.status' => '= ?',
          'AND',
          'racikan.deleted_at' => 'IS NULL'
        ), array(
          $parameter['asesmen'],
          ($parameter['penjamin'] !== __UIDPENJAMINUMUM__) ? 'N' : ((($parameter['departemen'] === __POLI_IGD__ || $parameter['departemen'] === __POLI_INAP__) ? 'N' : 'L'))
        ))
        ->execute();
    } else {
      $log = parent::log(array(
        'type' => 'error',
        'column' => array(
          'type',
          'class',
          'logged_at',
          'message'
        ),
        'value' => array(
          'CRITICAL',
          'APOTEK',
          parent::format_date(),
          'BUKAN RANAP'
        ),
        'class' => __CLASS__
      ));
      //Jika bukan Rawat Inap / IGD potong seperti biasa
      $updateResult = 0;
      $updateProgress = array();
      $tempProgress = array();

      foreach ($usedBatch as $bKey => $bValue) {
        //Stok Sebelum Update
        $getStok = self::$query->select('inventori_stok', array(
          'id',
          'gudang',
          'barang',
          'stok_terkini'
        ))
          ->where(array(
            'inventori_stok.gudang' => '= ?',
            'AND',
            'inventori_stok.barang' => '= ?',
            'AND',
            'inventori_stok.batch' => '= ?'
          ), array(
            $bValue['gudang'],
            $bValue['barang'],
            $bValue['batch']
          ))
          ->execute();


        //Potong Stok
        if (
          floatval($bValue['qty']) > 0 &&
          floatval($getStok['response_data'][0]['stok_terkini']) >= floatval($bValue['qty'])
        ) {
          $CheckGudangStatus = $Inventori->get_gudang_detail($bValue['gudang'])['response_data'][0];

          if ($CheckGudangStatus['status'] === 'A') {
            $targetRacikan = self::$query->select('racikan', array(
              'uid'
            ))
              ->where(array(
                'racikan.asesmen' => '= ?',
                'AND',
                'racikan.status' => '= ?',
                'AND',
                'racikan.deleted_at' => 'IS NULL'
              ), array(
                $parameter['asesmen'],
                ($parameter['penjamin'] !== __UIDPENJAMINUMUM__) ? 'N' : ((($parameter['departemen'] === __POLI_IGD__ || $parameter['departemen'] === __POLI_INAP__) ? 'N' : 'L'))
              ))
              ->execute();

            //Update Temp Stok Status
            if ($bValue['temp_stat'] === 'resep') {
              $TempStokResep = self::$query->update('inventori_temp_stok', array(
                'status' => 'D'
              ))
                ->where(array(
                  'inventori_temp_stok.transact_table' => '= ?',
                  'AND',
                  'inventori_temp_stok.transact_iden' => '= ?',
                  'AND',
                  'inventori_temp_stok.barang' => '= ?',
                  'AND',
                  'inventori_temp_stok.batch' => '= ?'
                ), array(
                  'resep', $parameter['resep'], $bValue['barang'], $bValue['batch']
                ))
                ->execute();
              array_push($tempProgress, $TempStokResep);
            }

            if ($bValue['temp_stat'] === 'racikan') {
              $TempStokRacikan = self::$query->update('inventori_temp_stok', array(
                'status' => 'D'
              ))
                ->where(array(
                  'inventori_temp_stok.transact_table' => '= ?',
                  'AND',
                  'inventori_temp_stok.transact_iden' => '= ?',
                  'AND',
                  'inventori_temp_stok.barang' => '= ?',
                  'AND',
                  'inventori_temp_stok.batch' => '= ?'
                ), array(
                  'racikan', $targetRacikan['response_data'][0]['uid'], $bValue['barang'], $bValue['batch']
                ))
                ->execute();
              array_push($tempProgress, $TempStokRacikan);
            }

            if ($TempStokResep['response_result'] > 0 || $TempStokRacikan['response_result'] > 0) {
              $updateStok = self::$query->update('inventori_stok', array(
                'stok_terkini' => (floatval($getStok['response_data'][0]['stok_terkini']) - floatval($bValue['qty']))
              ))
                ->where(array(
                  'inventori_stok.gudang' => '= ?',
                  'AND',
                  'inventori_stok.barang' => '= ?',
                  'AND',
                  'inventori_stok.batch' => '= ?'
                ), array(
                  $bValue['gudang'],
                  $bValue['barang'],
                  $bValue['batch']
                ))
                ->execute();
              if ($updateStok['response_result'] > 0) {
                //Log Stok
                $stokLog = self::$query->insert('inventori_stok_log', array(
                  'barang' => $bValue['barang'],
                  'batch' => $bValue['batch'],
                  'gudang' => $bValue['gudang'],
                  'masuk' => 0,
                  'keluar' => floatval($bValue['qty']),
                  'saldo' => (floatval($getStok['response_data'][0]['stok_terkini']) - floatval($bValue['qty'])),
                  'type' => __STATUS_BARANG_KELUAR__,
                  'jenis_transaksi' => 'resep',
                  'uid_foreign' => $parameter['resep'],
                  'keterangan' => ''
                ))
                  ->execute();
                $updateResult += $stokLog['response_result'];
              }
              array_push($updateProgress, $updateStok);
            }
          }
        } else {
          array_push($updateProgress, $getStok);
        }
      }

      if ($updateResult === count($usedBatch)) {
        //Update Resep
        $updateResep = self::$query->update('resep', array(
          'status_resep' => 'D'
        ))
          ->where(array(
            'resep.uid' => '= ?'
          ), array(
            $parameter['resep']
          ))
          ->execute();

        //Update Racikan
        $updateRacikan = self::$query->update('racikan', array(
          'status' => 'D'
        ))
          ->where(array(
            'racikan.asesmen' => '= ?',
            'AND',
            'racikan.status' => '= ?',
            'AND',
            'racikan.deleted_at' => 'IS NULL'
          ), array(
            $parameter['asesmen'],
            'L'
          ))
          ->execute();
      } else {
        //Update Resep
        $updateResep = self::$query->update('resep', array(
          'status_resep' => 'D'
        ))
          ->where(array(
            'resep.uid' => '= ?'
          ), array(
            $parameter['resep']
          ))
          ->execute();

        //Update Racikan
        $updateRacikan = self::$query->update('racikan', array(
          'status' => 'D'
        ))
          ->where(array(
            'racikan.asesmen' => '= ?',
            'AND',
            'racikan.status' => '= ?',
            'AND',
            'racikan.deleted_at' => 'IS NULL'
          ), array(
            $parameter['asesmen'],
            'L'
          ))
          ->execute();
      }
    }

    return array(
      'atempres' => $tempProgress,
      'racikan_batch' => $racikanItem,
      'result' => $usedBatch,
      'resep' => $resepItem,
      'racikan' => $racikan,
      'raw_batch' => $rawBatch,
      'stok_progress' => $updateProgress,
      'informasi_inap' => $RawatInap,
      'informasi_igd' => $IGD,
      'mutasi' => $Mutasi,
      'batch' => ($parameter['departemen'] === __POLI_IGD__ || $parameter['departemen'] === __POLI_INAP__) ? $usedBatchInap : $usedBatch,
      'departement' => $parameter['departemen'],
      'parse_mutas' => $itemMutasi,
      'stok_result' => ($updateResult == count($usedBatch)) ? 1 : 0,
      'update_resep' => $updateResep,
      'update_racikan' => $updateRacikan,
    );
  }

  private function detail_resep_2($parameter)
  {
    $dataResponse = array(
      'detail' => array(),
      'resep' => array(),
      'racikan' => array()
    );

    $unique_racikan = array();


    //Resep Detail
    $resep = self::$query->select('resep', array(
      'uid',
      'asesmen',
      'antrian',
      'alergi_obat',
      'keterangan',
      'iterasi',
      'kode',
      'keterangan_racikan',
      'created_at'
    ))
      ->where(array(
        'resep.deleted_at' => 'IS NULL',
        'AND',
        'resep.uid' => '= ?'
      ), array(
        $parameter
      ))
      ->execute();

    $dataResponse['alergi_obat'] = $resep['response_data'][0]['alergi_obat'];
    $dataResponse['iterasi'] = $resep['response_data'][0]['iterasi'];
    $dataResponse['kode'] = $resep['response_data'][0]['kode'];
    $dataResponse['asesmen_uid'] = $resep['response_data'][0]['asesmen'];
    $dataResponse['created_at_parsed'] = date('d F Y', strtotime($resep['response_data'][0]['created_at']));

    $AntrianDetail = self::$query->select('antrian', array(
      'uid',
      'kunjungan',
      'penjamin',
      'pasien',
      'dokter',
      'departemen',
      'created_at'
    ))
      ->where(array(
        'antrian.uid' => '= ?',
        'AND',
        'antrian.deleted_at' => 'IS NULL'
      ), array(
        $resep['response_data'][0]['antrian']
      ))
      ->execute();

    $Penjamin = new Penjamin(self::$pdo);
    $Pasien = new Pasien(self::$pdo);
    $Poli = new Poli(self::$pdo);
    $Dokter = new Pegawai(self::$pdo);
    $ICD10 = new Icd(self::$pdo);
    $Inventori = new Inventori(self::$pdo);

    foreach ($AntrianDetail['response_data'] as $AKey => $AValue) {
      $AntrianDetail['response_data'][$AKey]['pasien'] = $Pasien->get_pasien_detail('pasien', $AValue['pasien'])['response_data'][0];
      $AntrianDetail['response_data'][$AKey]['penjamin'] = $Penjamin->get_penjamin_detail($AValue['penjamin'])['response_data'][0];
      if ($AntrianDetail['response_data'][$AKey]['departemen'] === __POLI_INAP__) {
        $AntrianDetail['response_data'][$AKey]['departemen'] = array(
          'uid' => __POLI_INAP__,
          'nama' => 'Rawat Inap',
          'poli_asesmen' => 'inap'
        );
      } else {
        $AntrianDetail['response_data'][$AKey]['departemen'] = $Poli->get_poli_detail($AValue['departemen'])['response_data'][0];
      }

      $AntrianDetail['response_data'][$AKey]['dokter'] = $Dokter->get_detail_pegawai($AValue['dokter'])['response_data'][0];

      $date = strtotime($AValue['created_at']);

      $datediff = time() - $date;
      $datediff = ($datediff < 1) ? 1 : $datediff;
      $difference = floor($datediff / (60 * 60 * __RECIPE_TIME_TOLERANCE__));
      $AntrianDetail['response_data'][$AKey]['allow_edit'] = ($difference <= 0);
      $AntrianDetail['response_data'][$AKey]['created_time'] = parent::humanTiming($date);
    }

    $dataResponse['detail'] = $AntrianDetail['response_data'][0];

    foreach ($resep['response_data'] as $key => $value) {
      //GET Resep Detail
      $resepDetail = self::$query->select('resep_detail', array(
        'id',
        'resep',
        'obat',
        'harga',
        'signa_qty',
        'signa_pakai',
        'iterasi',
        'keterangan',
        'aturan_pakai',
        'satuan_konsumsi',
        'qty',
        'satuan',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'resep_detail.resep' => '= ?'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($resepDetail['response_data'] as $RDKey => $RDValue) {
        $resepDetail['response_data'][$RDKey]['obat_detail'] = $Inventori->get_item_detail($RDValue['obat'])['response_data'][0];
        $resepDetail['response_data'][$RDKey]['qty_roman'] = parent::numberToRoman($RDValue['qty']);
      }

      $dataResponse['resep'] = $resepDetail['response_data'];
      $dataResponse['keterangan'] = $value['keterangan'];
      $dataResponse['keterangan_racikan'] = $value['keterangan_racikan'];
      //Racikan Detail
      $racikan = self::$query->select('racikan', array(
        'uid',
        'asesmen',
        'kode',
        'keterangan',
        'aturan_pakai',
        'iterasi',
        'signa_qty',
        'signa_pakai',
        'qty',
        'satuan_konsumsi',
        'total'
      ))
        ->where(array(
          'racikan.asesmen' => '= ?'
        ), array(
          $value['asesmen']
        ))
        ->execute();

      foreach ($racikan['response_data'] as $RacikanKey => $RacikanValue) {
        $RacikanDetailData = self::$query->select('racikan_detail', array(
          'asesmen',
          //'resep',
          'obat',
          'ratio',
          'pembulatan',
          'kekuatan',
          'takar_bulat',
          'takar_decimal',
          'harga',
          'racikan',
          'penjamin'
        ))
          ->where(array(
            'racikan_detail.racikan' => '= ?'
          ), array(
            //$value['uid'],
            $RacikanValue['uid']
          ))
          ->execute();

        foreach ($RacikanDetailData['response_data'] as $RVIKey => $RVIValue) {
          $RacikanDetailData['response_data'][$RVIKey]['obat_detail'] = $Inventori->get_item_detail($RVIValue['obat'])['response_data'][0];
        }

        $RacikanValue['item'] = $RacikanDetailData['response_data'];
        $RacikanValue['qty_roman'] = parent::numberToRoman($RacikanValue['qty']);
        array_push($dataResponse['racikan'], $RacikanValue);
      }

      //Asesmen Detail
      $Asesmen = self::$query->select('asesmen_medis_' . $AntrianDetail['response_data'][0]['departemen']['poli_asesmen'], array(
        'uid',
        'kunjungan',
        'antrian',
        'diagnosa_kerja',
        'diagnosa_banding',
        'icd10_kerja',
        'icd10_banding'
      ))
        ->where(array(
          'asesmen_medis_' . $AntrianDetail['response_data'][0]['departemen']['poli_asesmen'] . '.asesmen' => '= ?'
        ), array(
          $value['asesmen']
        ))
        ->execute();
      foreach ($Asesmen['response_data'] as $asesmen_key => $asesmen_value) {
        $ICD10Kerja = array();
        $SplitKerja = explode(',', $asesmen_value['icd10_kerja']);
        foreach ($SplitKerja as $ICD10K => $ICD10V) {
          array_push($ICD10Kerja, $ICD10->get_icd_detail('master_icd_10', $ICD10V)['response_data'][0]);
        }
        $Asesmen['response_data'][$asesmen_key]['icd_kerja'] = $ICD10Kerja;

        $ICD10Banding = array();
        $SplitBanding = explode(',', $asesmen_value['icd10_banding']);
        foreach ($SplitBanding as $ICD10K => $ICD10V) {
          array_push($ICD10Banding, $ICD10->get_icd_detail('master_icd_10', $ICD10V)['response_data'][0]);
        }
        $Asesmen['response_data'][$asesmen_key]['icd_banding'] = $ICD10Banding;
      }

      $dataResponse['asesmen'] = $Asesmen['response_data'][0];
    }



    return array($dataResponse);
  }

  private function detail_resep($parameter, $status = 'N')
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $data = self::$query->select('resep', array(
      'uid',
      'kunjungan',
      'antrian',
      'asesmen',
      'dokter',
      'pasien',
      'total',
      'keterangan',
      'keterangan_racikan',
      'created_at',
      'updated_at'
    ))
      ->where(array(
        'resep.uid' => '= ?',
        'AND',
        'resep.deleted_at' => 'IS NULL',
        'AND',
        'resep.status_resep' => '= ?'
      ), array(
        $parameter,
        $status
      ))
      ->execute();
    $autonum = 1;
    foreach ($data['response_data'] as $key => $value) {
      //Dokter Info
      $Pegawai = new Pegawai(self::$pdo);
      $PegawaiInfo = $Pegawai::get_detail($value['dokter']);
      $data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

      $VerifikatorInfo = $Pegawai::get_detail($value['verifikator']);
      $data['response_data'][$key]['verifikator'] = $VerifikatorInfo['response_data'][0];

      //Get Antrian Detail
      $Antrian = new Antrian(self::$pdo);
      $AntrianInfo = $Antrian::get_antrian_detail('antrian', $value['antrian']);
      $data['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

      //Departemen Info
      $Poli = new Poli(self::$pdo);
      $PoliInfo = $Poli::get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
      $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];


      //Get resep detail
      $resep_detail = self::$query->select('resep_detail', array(
        'id',
        'resep',
        'obat',
        'harga',
        'signa_qty',
        'signa_pakai',
        'qty',
        'satuan',
        'keterangan',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'resep_detail.resep' => '= ?',
          'AND',
          'resep_detail.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($resep_detail['response_data'] as $ResKey => $ResValue) {
        $Inventori = new Inventori(self::$pdo);
        $InventoriInfo = $Inventori::get_item_detail($ResValue['obat']);
        $resep_detail['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];

        //Batch Info
        $Inventori = new Inventori(self::$pdo);
        $InventoriBatch = $Inventori::get_item_batch($ResValue['obat']);
        $resep_detail['response_data'][$ResKey]['batch'] = $InventoriBatch['response_data'];
        $total_sedia = 0;
        foreach ($InventoriBatch['response_data'] as $bKey => $bValue) {
          if ($bValue['gudang']['uid'] === $UserData['data']->gudang) {
            $total_sedia += $bValue['stok_terkini'];
          }
        }
        $resep_detail['response_data'][$ResKey]['sedia'] = $total_sedia;
      }
      $data['response_data'][$key]['detail'] = $resep_detail['response_data'];


      //Racikan Item
      $racikan = self::$query->select('racikan', array(
        'uid',
        'asesmen',
        //'resep',
        'kode',
        'total',
        'keterangan',
        'signa_qty',
        'signa_pakai',
        'qty',
        'keterangan',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'racikan.asesmen' => '= ?',
          'AND',
          'racikan.status' => '= ?',
          'AND',
          'racikan.deleted_at' => 'IS NULL'
        ), array(
          $value['asesmen'],
          $status
        ))
        ->execute();
      foreach ($racikan['response_data'] as $RDKey => $RDValue) {
        $racikan_detail = self::$query->select('racikan_detail', array(
          'id',
          'asesmen',
          //'resep',
          'obat',
          'ratio',
          'pembulatan',
          'kekuatan',
          'harga',
          'racikan',
          'takar_bulat',
          'takar_decimal',
          'penjamin',
          'created_at',
          'updated_at'
        ))
          ->where(array(
            'racikan_detail.deleted_at' => 'IS NULL',
            /*'AND',
                        'racikan_detail.resep' => '= ?',*/
            'AND',
            'racikan_detail.racikan' => '= ?'
          ), array(
            //$value['uid'],
            $RDValue['uid']
          ))
          ->execute();
        foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
          $Inventori = new Inventori(self::$pdo);
          $InventoriInfo = $Inventori::get_item_detail($RDIValue['obat']);
          $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];

          $InventoriBacthRacikan = $Inventori::get_item_batch($RDIValue['obat']);
          $racikan_detail['response_data'][$RDIKey]['batch'] = $InventoriBacthRacikan['response_data'];

          $total_sedia_racikan = 0;
          foreach ($InventoriBacthRacikan['response_data'] as $bRKey => $bRValue) {
            if ($bRValue['gudang']['uid'] === $UserData['data']->gudang) {
              $total_sedia_racikan += floatval($bRValue['stok_terkini']);
            }
          }
          $racikan_detail['response_data'][$RDIKey]['sedia'] = $total_sedia_racikan;
        }
        $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
      }
      $data['response_data'][$key]['racikan'] = $racikan['response_data'];

      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    return $data;
  }

  private function detail_resep_verifikator_post($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $Inventori = new Inventori(self::$pdo);
    $Poli = new Poli(self::$pdo);
    $Antrian = new Antrian(self::$pdo);
    $Pasien = new Pasien(self::$pdo);
    $Pegawai = new Pegawai(self::$pdo);
    $Unit = new Inap(self::$pdo);

    $UnitDetail = $Unit->get_ns_detail($parameter['nurse_station'])['response_data'][0];

    $resep_dokter = self::$query->select('resep', array(
      'uid',
      'kunjungan',
      'antrian',
      'asesmen',
      'dokter',
      'pasien',
      'total',
      'status_resep',
      'keterangan',
      'keterangan_racikan',
      'created_at',
      'updated_at'
    ))
      ->where(array(
        'resep.uid' => '= ?',
        'AND',
        'resep.deleted_at' => 'IS NULL'
      ), array(
        $parameter['uid']
      ))
      ->execute();

    foreach ($resep_dokter['response_data'] as $key => $value) {
      $resep_dokter['response_data'][$key]['created_at_parsed'] = date('d F Y, H:i', strtotime($value['created_at']));
      //Dokter Info
      $PegawaiInfo = $Pegawai->get_detail($value['dokter']);
      $resep_dokter['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

      //Pasien Info
      $PasienInfo = $Pasien->get_pasien_detail('pasien', $value['pasien']);
      $resep_dokter['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];

      //Get Antrian Detail
      $AntrianInfo = $Antrian->get_antrian_detail('antrian', $value['antrian']);
      $resep_dokter['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

      //Departemen Info
      if ($AntrianInfo['response_data'][0]['departemen'] === __POLI_INAP__) {
        $AntrianInfo['response_data'][0]['departemen'] = array(
          'uid' => __POLI_INAP__,
          'nama' => 'Rawat Inap'
        );
      } else {
        $PoliInfo = $Poli->get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
        $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];
      }




      $resep_verifikator = self::$query->select('resep_change_log', array(
        'item',
        'verifikator',
        'keterangan',
        'qty',
        'aturan_pakai',
        'signa_qty',
        'signa_pakai',
        'aturan_pakai'
      ))
        ->where(array(
          'resep_change_log.resep' => '= ?',
          'AND',
          'resep_change_log.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($resep_verifikator['response_data'] as $ResKey => $ResValue) {
        $resep_verifikator['response_data'][$ResKey]['verifikator'] = $Pegawai->get_detail($ResValue['verifikator'])['response_data'][0];
        //Check Ketersediaan Obat pada NS
        $NSInap = self::$query->select('rawat_inap_batch', array(
          'qty', 'batch'
        ))
          ->where(array(
            'rawat_inap_batch.gudang' => '= ?',
            'AND',
            'rawat_inap_batch.obat' => '= ?',
            'AND',
            'rawat_inap_batch.resep' => '= ?'
          ), array(
            $UnitDetail['gudang'],
            $ResValue['item'],
            $value['uid']
          ))
          ->execute();
        $resep_verifikator['response_data'][$ResKey]['stok_ns'] = $NSInap['response_data'];

        $InventoriInfo = $Inventori->get_item_detail($ResValue['item']);
        $resep_verifikator['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];

        //Batch Info
        $InventoriBatch = $Inventori->get_item_batch($ResValue['item']);
        $resep_verifikator['response_data'][$ResKey]['batch'] = $InventoriBatch['response_data'];
        $total_sedia = 0;
        foreach ($InventoriBatch['response_data'] as $bKey => $bValue) {
          if ($bValue['gudang']['uid'] === $UserData['data']->gudang) {
            $total_sedia += $bValue['stok_terkini'];
          }
        }
        $resep_verifikator['response_data'][$ResKey]['sedia'] = $total_sedia;

        $aturan_pakai = self::$query->select('terminologi_item', array(
          'id',
          'nama'
        ))
          ->where(array(
            'terminologi_item.id' => '= ?',
            'AND',
            'terminologi_item.deleted_at' => 'IS NULL'
          ), array(
            $ResValue['aturan_pakai']
          ))
          ->execute();


        $resep_verifikator['response_data'][$ResKey]['aturan_pakai'] = $aturan_pakai['response_data'][0];
      }

      $resep_dokter['response_data'][$key]['detail'] = $resep_verifikator['response_data'];



      //Racikan Item
      $racikan = self::$query->select('racikan', array(
        'uid',
        'asesmen',
        'kode',
        'total',
        'keterangan',
        'signa_qty',
        'signa_pakai',
        'qty',
        'keterangan',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'racikan.asesmen' => '= ?',
          'AND',
          'racikan.deleted_at' => 'IS NULL'
        ), array(
          $value['asesmen']
        ))
        ->execute();
      foreach ($racikan['response_data'] as $RDKey => $RDValue) {
        //Stok Racikan NS
        $history_racikan = self::$query->select('rawat_inap_riwayat_obat', array(
          'qty'
        ))
          ->where(array(
            'rawat_inap_riwayat_obat.obat' => '= ?',
            'AND',
            'rawat_inap_riwayat_obat.resep' => '= ?'
          ), array(
            $RDValue['kode'],
            $value['uid']
          ))
          ->execute();
        $racikan['response_data'][$RDKey]['ns_qty'] = $history_racikan['response_data'];

        $racikan_verifikator = self::$query->select('racikan_change_log', array(
          'jumlah',
          'signa_qty',
          'signa_pakai',
          'aturan_pakai',
          'keterangan'
        ))
          ->where(array(
            'racikan_change_log.racikan' => '= ?',
            'AND',
            'racikan_change_log.deleted_at' => 'IS NULL'
          ), array(
            $RDValue['uid']
          ))
          ->execute();



        $racikan_detail = self::$query->select('racikan_detail_change_log', array(
          'id',
          'obat',
          'kekuatan',
          'created_at',
          'updated_at'
        ))
          ->where(array(
            'racikan_detail_change_log.deleted_at' => 'IS NULL',
            'AND',
            'racikan_detail_change_log.racikan' => '= ?'
          ), array(
            $RDValue['uid']
          ))
          ->execute();
        foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
          $InventoriInfo = $Inventori->get_item_detail($RDIValue['obat']);
          $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];

          $InventoriBacthRacikan = $Inventori->get_item_batch($RDIValue['obat']);
          $racikan_detail['response_data'][$RDIKey]['batch'] = $InventoriBacthRacikan['response_data'];

          $total_sedia_racikan = 0;
          foreach ($InventoriBacthRacikan['response_data'] as $bRKey => $bRValue) {
            if ($bRValue['gudang']['uid'] === $UserData['data']->gudang) {
              $total_sedia_racikan += floatval($bRValue['stok_terkini']);
            }
          }
          $racikan_detail['response_data'][$RDIKey]['sedia'] = $total_sedia_racikan;
        }
        $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
      }
      $resep_dokter['response_data'][$key]['racikan'] = $racikan['response_data'];
    }


    return $resep_dokter;
  }


  private function detail_resep_verifikator($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $Inventori = new Inventori(self::$pdo);
    $Poli = new Poli(self::$pdo);
    $Antrian = new Antrian(self::$pdo);
    $Pasien = new Pasien(self::$pdo);
    $Pegawai = new Pegawai(self::$pdo);

    $totalAll = 0;

    $resep_dokter = self::$query->select('resep', array(
      'uid',
      'kode',
      'kunjungan',
      'antrian',
      'asesmen',
      'dokter',
      'apoteker',
      'pasien',
      'total',
      'alergi_obat',
      'status_resep',
      'keterangan',
      'keterangan_racikan',
      'created_at',
      'updated_at',
      'alasan_ubah'
    ))
      ->where(array(
        'resep.uid' => '= ?',
        'AND',
        'resep.deleted_at' => 'IS NULL'
      ), array(
        $parameter
      ))
      ->execute();

    foreach ($resep_dokter['response_data'] as $key => $value) {
      //Kajian Apotek
      $Kajian = self::$query->select('resep_kajian', array(
        'parameter_kajian', 'nilai'
      ))
        ->where(array(
          'resep_kajian.resep' => '= ?',
          'AND',
          'resep_kajian.pasien' => '= ?',
          'AND',
          'resep_kajian.deleted_at' => 'IS NULL'
        ), array(
          $value['uid'], $value['pasien']
        ))
        ->execute();
      $resep_dokter['response_data'][$key]['kajian'] = $Kajian['response_data'];

      //Charged Item
      $Invoice_detail = self::$query->select('invoice', array(
        'uid'
      ))
        ->where(array(
          'invoice.kunjungan' => '= ?',
          'AND',
          'invoice.pasien' => '= ?'
        ), array(
          $value['kunjungan'],
          $value['pasien']
        ))
        ->execute();

      //Dokter Info
      $PegawaiInfo = $Pegawai->get_detail($value['dokter']);
      $resep_dokter['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

      $ApotekerInfo = $Pegawai->get_detail($value['apoteker']);
      $resep_dokter['response_data'][$key]['verifikator'] = $ApotekerInfo['response_data'][0];

      $resep_dokter['response_data'][$key]['created_at_parsed'] = date('d F Y, H:i', strtotime($value['created_at']));

      //Pasien Info
      $PasienInfo = $Pasien->get_pasien_detail('pasien', $value['pasien']);
      $resep_dokter['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];

      //Get Antrian Detail
      $AntrianInfo = $Antrian->get_antrian_detail('antrian', $value['antrian']);
      $resep_dokter['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

      //Departemen Info
      if ($AntrianInfo['response_data'][0]['departemen'] === __POLI_INAP__) {
        $AntrianInfo['response_data'][0]['departemen'] = array(
          'uid' => __POLI_INAP__,
          'nama' => 'Rawat Inap'
        );
      } else {
        $PoliInfo = $Poli->get_poli_info($AntrianInfo['response_data'][0]['departemen']);
        $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];
      }

      $resep_detail_dokter = self::$query->select('resep_detail', array(
        'id',
        'resep',
        'obat',
        'harga',
        'signa_qty',
        'signa_pakai',
        'qty',
        'satuan_konsumsi',
        'satuan',
        'keterangan',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'resep_detail.resep' => '= ?',
          'AND',
          'resep_detail.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();

      foreach ($resep_detail_dokter['response_data'] as $ResDKey => $ResDValue) {
        $InventoriInfo = $Inventori->get_item_detail($ResDValue['obat']);
        $resep_detail_dokter['response_data'][$ResDKey]['detail'] = $InventoriInfo['response_data'][0];

        //Batch Info
        $aturan_pakai = self::$query->select('terminologi_item', array(
          'id',
          'nama'
        ))
          ->where(array(
            'terminologi_item.id' => '= ?',
            'AND',
            'terminologi_item.deleted_at' => 'IS NULL'
          ), array(
            $ResDValue['aturan_pakai']
          ))
          ->execute();


        $resep_detail_dokter['response_data'][$ResDKey]['aturan_pakai'] = $aturan_pakai['response_data'][0];
      }

      $resep_dokter['response_data'][$key]['detail_dokter'] = $resep_detail_dokter['response_data'];



      $resep_verifikator = self::$query->select('resep_change_log', array(
        'item',
        'keterangan',
        'qty',
        'aturan_pakai',
        'signa_qty',
        'batch',
        'signa_pakai',
        'aturan_pakai',
        'verifikator',
        'alasan_ubah'
      ))
        ->where(array(
          'resep_change_log.resep' => '= ?',
          'AND',
          'resep_change_log.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($resep_verifikator['response_data'] as $ResKey => $ResValue) {
        $VerifikatorInfo = $Pegawai->get_info($ResValue['verifikator']);
        $resep_verifikator['response_data'][$ResKey]['verifikator'] = $VerifikatorInfo['response_data'][0];

        $InventoriInfo = $Inventori->get_item_info($ResValue['item']);
        $resep_verifikator['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];

        //Check Ketersediaan Obat
        $CheckStokResepDetail = self::$query->select('inventori_stok', array(
          'stok_terkini'
        ))
          ->where(array(
            'inventori_stok.batch' => '= ?',
            'AND',
            'inventori_stok.barang' => '= ?',
            'AND',
            'inventori_stok.gudang' => '= ?'
          ), array(
            $ResValue['batch'],
            $ResValue['item'],
            __GUDANG_APOTEK__
          ))
          ->execute();
        $resep_verifikator['response_data'][$ResKey]['stok_terkini'] = floatval($CheckStokResepDetail['response_data'][0]['stok_terkini']);

        $batch_current = array();
        //Batch Info
        // $InventoriBatch = $Inventori->get_item_batch($ResValue['item']);

        // $total_sedia = 0;
        // foreach ($InventoriBatch['response_data'] as $bKey => $bValue)
        // {
        //     if($bValue['gudang']['uid'] === $UserData['data']->gudang) {
        //         array_push($batch_current, $bValue);
        //         $total_sedia += $bValue['stok_terkini'];
        //     }
        // }

        $batchData = $Inventori->get_batch_info($ResValue['batch']);
        foreach ($batchData['response_data'] as $batchKey => $batchValue) {
          $batchData['response_data'][$batchKey]['expired_date_parsed'] = date('d F Y', strtotime($batchValue['expired_date']));
        }
        $resep_verifikator['response_data'][$ResKey]['batch'] = $batchData['response_data'][0];
        //$resep_verifikator['response_data'][$ResKey]['sedia'] = $total_sedia;
        $InvoiceDetailCharged = self::$query->select('invoice_detail', array(
          'qty',
          'harga',
          'subtotal'
        ))
          ->where(array(
            'invoice_detail.invoice' => '= ?',
            'AND',
            'invoice_detail.item' => '= ?',
            'AND',
            'invoice_detail.item_type' => '= ?',
            'AND',
            'invoice_detail.pasien' => '= ?',
            'AND',
            'invoice_detail.status_bayar' => '= ?',
            'AND',
            'invoice_detail.document' => '= ?',
            'AND',
            'invoice_detail.keterangan' => '= ?',
          ), array(
            $Invoice_detail['response_data'][0]['uid'],
            $ResValue['item'],
            'master_inv',
            $value['pasien'],
            'Y',
            'RESEP' . $value['kode'],
            'Biaya resep obat'
          ))
          ->execute();
        foreach ($InvoiceDetailCharged['response_data'] as $InvChargedKey => $InvChargedValue) {
          $totalAll += floatval($InvChargedValue['subtotal']);
        }
        $resep_verifikator['response_data'][$ResKey]['pay'] = $InvoiceDetailCharged['response_data'];

        $aturan_pakai = self::$query->select('terminologi_item', array(
          'id',
          'nama'
        ))
          ->where(array(
            'terminologi_item.id' => '= ?',
            'AND',
            'terminologi_item.deleted_at' => 'IS NULL'
          ), array(
            $ResValue['aturan_pakai']
          ))
          ->execute();


        $resep_verifikator['response_data'][$ResKey]['aturan_pakai'] = $aturan_pakai['response_data'][0];
      }

      $resep_dokter['response_data'][$key]['detail'] = $resep_verifikator['response_data'];



      //Racikan Item
      $racikan = self::$query->select('racikan', array(
        'uid',
        'asesmen',
        'kode',
        'total',
        'keterangan',
        'signa_qty',
        'signa_pakai',
        'qty',
        'satuan_konsumsi',
        'keterangan',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'racikan.asesmen' => '= ?',
          'AND',
          'racikan.deleted_at' => 'IS NULL'
        ), array(
          $value['asesmen']
        ))
        ->execute();
      foreach ($racikan['response_data'] as $RDKey => $RDValue) {
        $racikan_detail_dokter = self::$query->select('racikan_detail', array(
          'id',
          'asesmen',
          'obat',
          'kekuatan',
          'ratio',
          'pembulatan',
          'harga',
          'racikan',
          'takar_bulat',
          'takar_decimal',
          'penjamin',
          'created_at',
          'updated_at'
        ))
          ->where(array(
            'racikan_detail.deleted_at' => 'IS NULL',
            'AND',
            'racikan_detail.racikan' => '= ?'
          ), array(
            $RDValue['uid']
          ))
          ->execute();

        foreach ($racikan_detail_dokter['response_data'] as $RDIDKey => $RDIDValue) {
          $InventoriInfo = $Inventori->get_item_detail($RDIDValue['obat']);
          $racikan_detail_dokter['response_data'][$RDIDKey]['detail'] = $InventoriInfo['response_data'][0];
        }
        $racikan['response_data'][$RDKey]['detail_dokter'] = $racikan_detail_dokter['response_data'];

        $racikan_verifikator = self::$query->select('racikan_change_log', array(
          'jumlah',
          'signa_qty',
          'signa_pakai',
          'aturan_pakai',
          'keterangan',
          'alasan_ubah'
        ))
          ->where(array(
            'racikan_change_log.racikan' => '= ?',
            'AND',
            'racikan_change_log.deleted_at' => 'IS NULL'
          ), array(
            $RDValue['uid']
          ))
          ->execute();
        $racikan['response_data'][$RDKey]['change'] = $racikan_verifikator['response_data'];




        $racikan_detail = self::$query->select('racikan_detail_change_log', array(
          'id',
          'obat',
          'jumlah',
          'batch',
          'kekuatan',
          'created_at',
          'updated_at'
        ))
          ->where(array(
            'racikan_detail_change_log.deleted_at' => 'IS NULL',
            'AND',
            'racikan_detail_change_log.racikan' => '= ?'
          ), array(
            $RDValue['uid']
          ))
          ->execute();
        foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
          $InventoriInfo = $Inventori->get_item_info($RDIValue['obat']);
          $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];

          $BatchInfo = $Inventori->get_batch_info($RDIValue['batch']);
          $BatchInfo['response_data'][0]['expired_date_parsed'] = date('d F Y', strtotime($BatchInfo['response_data'][0]['expired_date']));
          $racikan_detail['response_data'][$RDIKey]['batch'] = $BatchInfo['response_data'][0];

          $CheckStokRacikanDetail = self::$query->select('inventori_stok', array(
            'stok_terkini'
          ))
            ->where(array(
              'inventori_stok.batch' => '= ?',
              'AND',
              'inventori_stok.barang' => '= ?',
              'AND',
              'inventori_stok.gudang' => '= ?'
            ), array(
              $RDIValue['batch'],
              $RDIValue['obat'],
              __GUDANG_APOTEK__
            ))
            ->execute();
          $racikan_detail['response_data'][$RDIKey]['stok_terkini'] = floatval($CheckStokRacikanDetail['response_data'][0]['stok_terkini']);

          // $InventoriBacthRacikan = $Inventori->get_item_batch($RDIValue['obat']);
          // $racikan_detail['response_data'][$RDIKey]['batch'] = $InventoriBacthRacikan['response_data'];

          // $total_sedia_racikan = 0;
          // foreach ($InventoriBacthRacikan['response_data'] as $bRKey => $bRValue)
          // {
          //     if($bRValue['gudang']['uid'] === $UserData['data']->gudang)
          //     {
          //         $total_sedia_racikan += floatval($bRValue['stok_terkini']);
          //     }
          // }
          // $racikan_detail['response_data'][$RDIKey]['sedia'] = $total_sedia_racikan;




          $InvoiceDetailCharged = self::$query->select('invoice_detail', array(
            'qty',
            'harga',
            'subtotal'
          ))
            ->where(array(
              'invoice_detail.invoice' => '= ?',
              'AND',
              'invoice_detail.item' => '= ?',
              'AND',
              'invoice_detail.item_type' => '= ?',
              'AND',
              'invoice_detail.pasien' => '= ?',
              'AND',
              'invoice_detail.status_bayar' => '= ?',
              'AND',
              'invoice_detail.document' => '= ?',
              'AND',
              'invoice_detail.keterangan' => '= ?',
            ), array(
              $Invoice_detail['response_data'][0]['uid'],
              $RDIValue['obat'],
              'master_inv',
              $value['pasien'],
              'Y',
              'RACIKAN' . $value['kode'],
              'Biaya racikan obat'
            ))
            ->execute();
          foreach ($InvoiceDetailCharged['response_data'] as $InvChargedKey => $InvChargedValue) {
            $totalAll += floatval($InvChargedValue['subtotal']);
          }
          $racikan_detail['response_data'][$RDIKey]['pay'] = $InvoiceDetailCharged['response_data'];
        }
        $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
      }
      $resep_dokter['response_data'][$key]['racikan'] = $racikan['response_data'];
      $resep_dokter['response_data'][$key]['total_all'] = $totalAll;
      $resep_dokter['response_data'][$key]['terbilang'] = parent::terbilang($totalAll);
    }




    return $resep_dokter;
  }





  private function detail_resep_verifikator_2($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $Inventori = new Inventori(self::$pdo);
    $Poli = new Poli(self::$pdo);
    $Antrian = new Antrian(self::$pdo);
    $Pasien = new Pasien(self::$pdo);
    $Pegawai = new Pegawai(self::$pdo);

    $totalAll = 0;

    $resep_dokter = self::$query->select('resep', array(
      'uid',
      'kode',
      'kunjungan',
      'antrian',
      'asesmen',
      'dokter',
      'apoteker',
      'pasien',
      'total',
      'alergi_obat',
      'status_resep',
      'keterangan',
      'keterangan_racikan',
      'created_at',
      'updated_at',
      'alasan_ubah'
    ))
      ->where(array(
        'resep.uid' => '= ?',
        'AND',
        'resep.deleted_at' => 'IS NULL'
      ), array(
        $parameter
      ))
      ->execute();

    foreach ($resep_dokter['response_data'] as $key => $value) {
      //Kajian Apotek
      $Kajian = self::$query->select('resep_kajian', array(
        'parameter_kajian', 'nilai'
      ))
        ->where(array(
          'resep_kajian.resep' => '= ?',
          'AND',
          'resep_kajian.pasien' => '= ?',
          'AND',
          'resep_kajian.deleted_at' => 'IS NULL'
        ), array(
          $value['uid'], $value['pasien']
        ))
        ->execute();
      $resep_dokter['response_data'][$key]['kajian'] = $Kajian['response_data'];

      //Charged Item
      $Invoice_detail = self::$query->select('invoice', array(
        'uid'
      ))
        ->where(array(
          'invoice.kunjungan' => '= ?',
          'AND',
          'invoice.pasien' => '= ?'
        ), array(
          $value['kunjungan'],
          $value['pasien']
        ))
        ->execute();

      //Dokter Info
      $PegawaiInfo = $Pegawai->get_info($value['dokter']);
      $resep_dokter['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

      $ApotekerInfo = $Pegawai->get_info($value['apoteker']);
      $resep_dokter['response_data'][$key]['verifikator'] = $ApotekerInfo['response_data'][0];

      $resep_dokter['response_data'][$key]['created_at_parsed'] = date('d F Y, H:i', strtotime($value['created_at']));

      //Pasien Info
      $PasienInfo = $Pasien->get_pasien_detail('pasien', $value['pasien']);
      $resep_dokter['response_data'][$key]['pasien'] = $PasienInfo['response_data'][0];

      //Get Antrian Detail
      $AntrianInfo = $Antrian->get_antrian_detail('antrian', $value['antrian']);
      $resep_dokter['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

      //Departemen Info
      if ($AntrianInfo['response_data'][0]['departemen'] === __POLI_INAP__) {
        $AntrianInfo['response_data'][0]['departemen'] = array(
          'uid' => __POLI_INAP__,
          'nama' => 'Rawat Inap'
        );
      } else {
        $PoliInfo = $Poli->get_poli_info($AntrianInfo['response_data'][0]['departemen']);
        $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];
      }

      $resep_detail_dokter = self::$query->select('resep_detail', array(
        'id',
        'resep',
        'obat',
        'harga',
        'signa_qty',
        'signa_pakai',
        'qty',
        'satuan',
        'keterangan',
        'satuan_konsumsi',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'resep_detail.resep' => '= ?',
          'AND',
          'resep_detail.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();

      foreach ($resep_detail_dokter['response_data'] as $ResDKey => $ResDValue) {
        $InventoriInfo = $Inventori->get_item_detail($ResDValue['obat']);
        $resep_detail_dokter['response_data'][$ResDKey]['detail'] = $InventoriInfo['response_data'][0];

        //Batch Info
        $aturan_pakai = self::$query->select('terminologi_item', array(
          'id',
          'nama'
        ))
          ->where(array(
            'terminologi_item.id' => '= ?',
            'AND',
            'terminologi_item.deleted_at' => 'IS NULL'
          ), array(
            $ResDValue['aturan_pakai']
          ))
          ->execute();


        $resep_detail_dokter['response_data'][$ResDKey]['aturan_pakai'] = $aturan_pakai['response_data'][0];
      }

      $resep_dokter['response_data'][$key]['detail_dokter'] = $resep_detail_dokter['response_data'];



      $resep_verifikator = self::$query->select('resep_change_log', array(
        'item',
        'keterangan',
        'qty',
        'aturan_pakai',
        'signa_qty',
        'signa_pakai',
        'aturan_pakai',
        'verifikator',
        'alasan_ubah'
      ))
        ->where(array(
          'resep_change_log.resep' => '= ?',
          'AND',
          'resep_change_log.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($resep_verifikator['response_data'] as $ResKey => $ResValue) {
        $VerifikatorInfo = $Pegawai->get_info($ResValue['verifikator']);
        $resep_verifikator['response_data'][$ResKey]['verifikator'] = $VerifikatorInfo['response_data'][0];

        $InventoriInfo = $Inventori->get_item_detail($ResValue['item']);
        $resep_verifikator['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];

        // $batch_current = array();
        // Batch Info
        // $InventoriBatch = $Inventori->get_item_batch($ResValue['item']);

        // $total_sedia = 0;
        // foreach ($InventoriBatch['response_data'] as $bKey => $bValue)
        // {
        //     if($bValue['gudang']['uid'] === $UserData['data']->gudang) {
        //         array_push($batch_current, $bValue);
        //         $total_sedia += $bValue['stok_terkini'];
        //     }
        // }

        // $resep_verifikator['response_data'][$ResKey]['batch'] = $batch_current;
        // $resep_verifikator['response_data'][$ResKey]['sedia'] = $total_sedia;
        $InvoiceDetailCharged = self::$query->select('invoice_detail', array(
          'qty',
          'harga',
          'subtotal'
        ))
          ->where(array(
            'invoice_detail.invoice' => '= ?',
            'AND',
            'invoice_detail.item' => '= ?',
            'AND',
            'invoice_detail.item_type' => '= ?',
            'AND',
            'invoice_detail.pasien' => '= ?',
            'AND',
            // 'invoice_detail.status_bayar' => '= ?',
            // 'AND',
            'invoice_detail.document' => '= ?',
            'AND',
            'invoice_detail.keterangan' => '= ?',
          ), array(
            $Invoice_detail['response_data'][0]['uid'],
            $ResValue['item'],
            'master_inv',
            $value['pasien'],
            //'Y',
            'RESEP' . $value['kode'],
            'Biaya resep obat'
          ))
          ->execute();
        foreach ($InvoiceDetailCharged['response_data'] as $InvChargedKey => $InvChargedValue) {
          $totalAll += floatval($InvChargedValue['subtotal']);
        }
        $resep_verifikator['response_data'][$ResKey]['pay'] = $InvoiceDetailCharged['response_data'];
        $resep_verifikator['response_data'][$ResKey]['zonk'] = $InvoiceDetailCharged;

        $aturan_pakai = self::$query->select('terminologi_item', array(
          'id',
          'nama'
        ))
          ->where(array(
            'terminologi_item.id' => '= ?',
            'AND',
            'terminologi_item.deleted_at' => 'IS NULL'
          ), array(
            $ResValue['aturan_pakai']
          ))
          ->execute();


        $resep_verifikator['response_data'][$ResKey]['aturan_pakai'] = $aturan_pakai['response_data'][0];
      }

      $resep_dokter['response_data'][$key]['detail'] = $resep_verifikator['response_data'];



      //Racikan Item
      $racikan = self::$query->select('racikan', array(
        'uid',
        'asesmen',
        'kode',
        'total',
        'keterangan',
        'signa_qty',
        'signa_pakai',
        'qty',
        'keterangan',
        'satuan_konsumsi',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'racikan.asesmen' => '= ?',
          'AND',
          'racikan.deleted_at' => 'IS NULL'
        ), array(
          $value['asesmen']
        ))
        ->execute();
      foreach ($racikan['response_data'] as $RDKey => $RDValue) {
        $racikan_detail_dokter = self::$query->select('racikan_detail', array(
          'id',
          'asesmen',
          'obat',
          'kekuatan',
          'ratio',
          'pembulatan',
          'harga',
          'racikan',
          'takar_bulat',
          'takar_decimal',
          'penjamin',
          'created_at',
          'updated_at'
        ))
          ->where(array(
            'racikan_detail.deleted_at' => 'IS NULL',
            'AND',
            'racikan_detail.racikan' => '= ?'
          ), array(
            $RDValue['uid']
          ))
          ->execute();

        foreach ($racikan_detail_dokter['response_data'] as $RDIDKey => $RDIDValue) {
          $InventoriInfo = $Inventori->get_item_detail($RDIDValue['obat']);
          $racikan_detail_dokter['response_data'][$RDIDKey]['detail'] = $InventoriInfo['response_data'][0];
        }
        $racikan['response_data'][$RDKey]['detail_dokter'] = $racikan_detail_dokter['response_data'];

        $racikan_verifikator = self::$query->select('racikan_change_log', array(
          'jumlah',
          'signa_qty',
          'signa_pakai',
          'aturan_pakai',
          'keterangan',
          'alasan_ubah'
        ))
          ->where(array(
            'racikan_change_log.racikan' => '= ?',
            'AND',
            'racikan_change_log.deleted_at' => 'IS NULL'
          ), array(
            $RDValue['uid']
          ))
          ->execute();
        $racikan['response_data'][$RDKey]['change'] = $racikan_verifikator['response_data'];




        $racikan_detail = self::$query->select('racikan_detail_change_log', array(
          'id',
          'obat',
          'jumlah',
          'kekuatan',
          'created_at',
          'updated_at'
        ))
          ->where(array(
            'racikan_detail_change_log.deleted_at' => 'IS NULL',
            'AND',
            'racikan_detail_change_log.racikan' => '= ?'
          ), array(
            $RDValue['uid']
          ))
          ->execute();
        foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
          $InventoriInfo = $Inventori->get_item_detail($RDIValue['obat']);
          $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];

          // $InventoriBacthRacikan = $Inventori->get_item_batch($RDIValue['obat']);
          // $racikan_detail['response_data'][$RDIKey]['batch'] = $InventoriBacthRacikan['response_data'];

          // $total_sedia_racikan = 0;
          // foreach ($InventoriBacthRacikan['response_data'] as $bRKey => $bRValue)
          // {
          //     if($bRValue['gudang']['uid'] === $UserData['data']->gudang)
          //     {
          //         $total_sedia_racikan += floatval($bRValue['stok_terkini']);
          //     }
          // }
          // $racikan_detail['response_data'][$RDIKey]['sedia'] = $total_sedia_racikan;




          $InvoiceDetailCharged = self::$query->select('invoice_detail', array(
            'qty',
            'harga',
            'subtotal'
          ))
            ->where(array(
              'invoice_detail.invoice' => '= ?',
              'AND',
              'invoice_detail.item' => '= ?',
              'AND',
              'invoice_detail.item_type' => '= ?',
              'AND',
              'invoice_detail.pasien' => '= ?',
              'AND',
              // 'invoice_detail.status_bayar' => '= ?',
              // 'AND',
              'invoice_detail.document' => '= ?',
              'AND',
              'invoice_detail.keterangan' => '= ?',
            ), array(
              $Invoice_detail['response_data'][0]['uid'],
              $RDIValue['obat'],
              'master_inv',
              $value['pasien'],
              //'Y',
              'RACIKAN' . $value['kode'],
              'Biaya racikan obat'
            ))
            ->execute();
          foreach ($InvoiceDetailCharged['response_data'] as $InvChargedKey => $InvChargedValue) {
            $totalAll += floatval($InvChargedValue['subtotal']);
          }
          $racikan_detail['response_data'][$RDIKey]['pay'] = $InvoiceDetailCharged['response_data'];
        }
        $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
      }
      $resep_dokter['response_data'][$key]['racikan'] = $racikan['response_data'];
      $resep_dokter['response_data'][$key]['total_all'] = $totalAll;
      $resep_dokter['response_data'][$key]['terbilang'] = parent::terbilang($totalAll);
    }




    return $resep_dokter;
  }

  private function get_resep_serah_backend($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      if (isset($parameter['filter_poli'])) {
        if ($parameter['filter_poli'] === 'rajal') {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '((resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'OR',
            '(resep.status_resep' => '= ?))'
          );

          $paramValue = array('D', 'P', 'S');
        } else {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '((resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'OR',
            '(resep.status_resep' => '= ?))'
          );

          $paramValue = array('D', 'P', 'S');
        }
      }
    } else {
      if (isset($parameter['filter_poli'])) {
        if ($parameter['filter_poli'] === 'rajal') {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '((resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'OR',
            '(resep.status_resep' => '= ?))'
            //'resep.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
          );

          $paramValue = array('D', 'P', 'S');
        } else {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '((resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'OR',
            '(resep.status_resep' => '= ?))'
            //'resep.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
          );

          $paramValue = array('D', 'P', 'S');
        }
      }
    }


    if ($parameter['length'] < 0) {
      $data = self::$query->select('resep', array(
        'uid',
        'kunjungan',
        'antrian',
        'asesmen',
        'dokter',
        'pasien',
        'total',
        'status_resep',
        'created_at',
        'updated_at'
      ))
        ->order(array(
          'updated_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('resep', array(
        'uid',
        'kunjungan',
        'antrian',
        'asesmen',
        'dokter',
        'pasien',
        'total',
        'status_resep',
        'created_at',
        'updated_at'
      ))
        ->order(array(
          'updated_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->offset(intval($parameter['start']))
        ->limit(intval($parameter['length']))
        ->execute();
    }




    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;
    $Pegawai = new Pegawai(self::$pdo);
    $Antrian = new Antrian(self::$pdo);
    $Poli = new Poli(self::$pdo);
    $Inventori = new Inventori(self::$pdo);
    foreach ($data['response_data'] as $key => $value) {
      //Dokter Info

      $PegawaiInfo = $Pegawai->get_detail($value['dokter']);
      $data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

      //Get Antrian Detail

      $AntrianInfo = $Antrian->get_antrian_detail('antrian', $value['antrian']);

      //Departemen Info

      $PoliInfo = $Poli->get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
      $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];
      $data['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

      //Get resep detail
      $resep_detail = self::$query->select('resep_detail', array(
        'id',
        'resep',
        'obat',
        'harga',
        'signa_qty',
        'signa_pakai',
        'qty',
        'satuan',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'resep_detail.resep' => '= ?',
          'AND',
          'resep_detail.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($resep_detail['response_data'] as $ResKey => $ResValue) {
        //Batch Info

        /*$InventoriBatch = $Inventori->get_item_batch($ResValue['obat']);
                $resep_detail['response_data'][$ResKey]['batch'] = $InventoriBatch['response_data'];*/

        /*$InventoriInfo = $Inventori->get_item_detail($ResValue['obat']);
                $resep_detail['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];*/
      }
      $data['response_data'][$key]['detail'] = $resep_detail['response_data'];


      //Racikan Item
      /*$racikan = self::$query->select('racikan', array(
                'uid',
                'asesmen',
                //'resep',
                'kode',
                'total',
                'keterangan',
                'signa_qty',
                'signa_pakai',
                'qty',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'racikan.asesmen' => '= ?',
                    'AND',
                    'racikan.status' => '= ?',
                    'AND',
                    'racikan.deleted_at' => 'IS NULL'
                ), array(
                    $value['asesmen'],
                    'N'
                ))
                ->execute();
            foreach ($racikan['response_data'] as $RDKey => $RDValue) {
                $racikan_detail = self::$query->select('racikan_detail', array(
                    'id',
                    'asesmen',
                    //'resep',
                    'obat',
                    'ratio',
                    'pembulatan',
                    'harga',
                    'racikan',
                    'takar_bulat',
                    'takar_decimal',
                    'penjamin',
                    'created_at',
                    'updated_at'
                ))
                    ->where(array(
                        'racikan_detail.deleted_at' => 'IS NULL',
                        'AND',
                        'racikan_detail.racikan' => '= ?'
                    ), array(
                        //$value['uid'],
                        $RDValue['uid']
                    ))
                    ->execute();
                foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
                    $InventoriInfo = $Inventori->get_item_detail($RDIValue['obat']);

                    $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];
                }
                $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
            }
            $data['response_data'][$key]['racikan'] = $racikan['response_data'];*/
      $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    $itemTotal = self::$query->select('resep', array(
      'uid'
    ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($data['response_data']);
    $data['recordsFiltered'] = count($itemTotal['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);

    return $data;
  }


  private function resep_inap($parameter)
  {
    $Unit = new Inap(self::$pdo);
    $UnitDetail = $Unit->get_ns_detail($parameter['nurse_station'])['response_data'][0];
    if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'resep.deleted_at' => 'IS NULL',
        'AND',
        'resep.pasien' => '= ?',
        'AND',
        'resep.kunjungan' => '= ?',
        'AND',
        'antrian.departemen' => '= ?'
      );

      $paramValue = array($parameter['pasien'], $parameter['kunjungan']);
    } else {
      $paramData = array(
        'resep.deleted_at' => 'IS NULL',
        'AND',
        'resep.pasien' => '= ?',
        'AND',
        'resep.kunjungan' => '= ?',
        'AND',
        'antrian.departemen' => '= ?'
      );

      $paramValue = array($parameter['pasien'], $parameter['kunjungan'], __POLI_INAP__);
    }



    if ($parameter['length'] < 0) {
      $data = self::$query->select('resep', array(
        'uid',
        'kunjungan',
        'antrian',
        'asesmen',
        'dokter',
        'pasien',
        'total',
        'status_resep',
        'created_at',
        'updated_at'
      ))
        ->join('antrian', array(
          'departemen'
        ))
        ->on(array(
          array(
            'resep.antrian', '=', 'antrian.uid'
          )
        ))
        ->order(array(
          'created_at' => 'ASC'
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('resep', array(
        'uid',
        'kunjungan',
        'antrian',
        'asesmen',
        'dokter',
        'pasien',
        'total',
        'status_resep',
        'created_at',
        'updated_at'
      ))
        ->join('antrian', array(
          'departemen'
        ))
        ->on(array(
          array(
            'resep.antrian', '=', 'antrian.uid'
          )
        ))
        ->order(array(
          'created_at' => 'ASC'
        ))
        ->where($paramData, $paramValue)
        ->offset(intval($parameter['start']))
        ->limit(intval($parameter['length']))
        ->execute();
    }

    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;
    $Inventori = new Inventori(self::$pdo);
    $Antrian = new Antrian(self::$pdo);
    $Pegawai = new Pegawai(self::$pdo);

    foreach ($data['response_data'] as $key => $value) {
      $AntrianDetail = $Antrian->get_antrian_detail('antrian', $value['antrian']);
      $data['response_data'][$key]['antrian_detail'] = $AntrianDetail['response_data'][0];

      $PegawaiDetail = $Pegawai->get_detail($value['dokter']);
      $data['response_data'][$key]['dokter_detail'] = $PegawaiDetail['response_data'][0];

      //Get resep detail
      /*$resep_detail = self::$query->select('resep_detail', array(
                'id',
                'resep',
                'obat',
                'harga',
                'signa_qty',
                'signa_pakai',
                'qty',
                'satuan',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'resep_detail.resep' => '= ?',
                    'AND',
                    'resep_detail.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();*/

      $resep_detail = self::$query->select('resep_change_log', array(
        'resep',
        'item as obat',
        'signa_qty',
        'signa_pakai',
        'qty'
      ))
        ->where(array(
          'resep_change_log.resep' => '= ?',
          'AND',
          'resep_change_log.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($resep_detail['response_data'] as $ResKey => $ResValue) {
        //Batch Info
        // $InventoriBatch = $Inventori->get_item_batch($ResValue['obat']);
        // $resep_detail['response_data'][$ResKey]['batch'] = $InventoriBatch['response_data'];

        $InventoriInfo = $Inventori->get_item_info($ResValue['obat']);
        $resep_detail['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];

        //Check Ketersediaan Obat pada NS
        $NSInap = self::$query->select('rawat_inap_batch', array(
          'qty', 'status', 'mutasi'
        ))
          ->where(array(
            'rawat_inap_batch.gudang' => '= ?',
            'AND',
            'rawat_inap_batch.obat' => '= ?',
            'AND',
            'rawat_inap_batch.resep' => '= ?'
          ), array(
            $UnitDetail['uid_gudang'],
            $ResValue['obat'],
            $value['uid']
          ))
          ->execute();
        $resep_detail['response_data'][$ResKey]['stok_ns'] = $NSInap['response_data'];
      }
      $data['response_data'][$key]['detail'] = $resep_detail['response_data'];


      //Racikan Item
      $racikan = self::$query->select('racikan', array(
        'uid',
        'asesmen',
        'kode',
        'total',
        'keterangan',
        'signa_qty',
        'signa_pakai',
        'qty',
        'created_at',
        'updated_at'
      ))
        /*->join('racikan_change_log', array(
                    'jumlah as qty',
                    'signa_qty',
                    'signa_pakai',
                ))
                ->on(array(
                    array('racikan_change_log.racikan', '=', 'racikan.uid')
                ))*/
        ->where(array(
          'racikan.asesmen' => '= ?',
          'AND',
          'racikan.deleted_at' => 'IS NULL'
        ), array(
          $value['asesmen']
        ))
        ->execute();
      foreach ($racikan['response_data'] as $RDKey => $RDValue) {

        //Stok Racikan NS
        $history_racikan = self::$query->select('rawat_inap_riwayat_obat', array(
          'qty'
        ))
          ->where(array(
            'rawat_inap_riwayat_obat.obat' => '= ?',
            'AND',
            'rawat_inap_riwayat_obat.resep' => '= ?'
          ), array(
            $RDValue['kode'],
            $value['uid']
          ))
          ->execute();
        $racikan['response_data'][$RDKey]['ns_qty'] = $history_racikan['response_data'];
        $racikan_detail = self::$query->select('racikan_detail', array(
          'id',
          'asesmen',
          'obat',
          'ratio',
          'pembulatan',
          'harga',
          'racikan',
          'takar_bulat',
          'takar_decimal',
          'penjamin',
          'created_at',
          'updated_at'
        ))
          /*->join('racikan_detail_change_log', array(
                        'obat',
                        'obat',
                        'kekuatan',
                        'jumlah'
                    ))
                    ->on(array(
                        array('racikan_detail_change_log.racikan', '=', 'racikan_detail.racikan')
                    ))*/
          ->where(array(
            'racikan_detail.deleted_at' => 'IS NULL',
            'AND',
            'racikan_detail.racikan' => '= ?'
          ), array(
            $RDValue['uid']
          ))
          ->execute();
        foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
          $InventoriInfo = $Inventori->get_item_info($RDIValue['obat']);

          $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];
        }
        $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];



        // BillingApotek
        $racikan_change = self::$query->select('racikan_change_log', array(
          'jumlah', 'keterangan', 'signa_qty', 'signa_pakai', 'aturan_pakai', 'alasan_ubah'
        ))
          ->where(array(
            'racikan_change_log.racikan' => '= ?'
          ), array(
            $RDValue['uid']
          ))
          ->execute();
        foreach ($racikan_change['response_data'] as $RacApKey => $RacApValue) {
          $DetailApRac = self::$query->select('racikan_detail_change_log', array(
            'obat', 'kekuatan', 'jumlah'
          ))
            ->where(array(
              'racikan_detail_change_log.racikan' => '= ?'
            ), array(
              $RDValue['uid']
            ))
            ->execute();
          foreach ($DetailApRac['response_data'] as $RApoDIKey => $RApoDIValue) {
            $InventoriInfo = $Inventori->get_item_info($RApoDIValue['obat']);
            $DetailApRac['response_data'][$RApoDIKey]['detail'] = $InventoriInfo['response_data'][0];
          }
          $racikan_change['response_data'][$RacApKey]['detail'] = $DetailApRac['response_data'];
        }
        $racikan['response_data'][$RDKey]['racikan_apotek'] = $racikan_change['response_data'];
      }
      $data['response_data'][$key]['racikan'] = $racikan['response_data'];
      $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at'])) . '<br />' . date('H:i:s', strtotime($value['created_at']));
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    $itemTotal = self::$query->select('resep', array(
      'uid'
    ))
      ->join('antrian', array(
        'departemen'
      ))
      ->on(array(
        array(
          'resep.antrian', '=', 'antrian.uid'
        )
      ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($data['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);

    return $data;
  }


  private function resep_igd($parameter)
  {
    $Unit = new Inap(self::$pdo);
    $UnitDetail = $Unit->get_ns_detail($parameter['nurse_station'])['response_data'][0];
    if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'resep.deleted_at' => 'IS NULL',
        'AND',
        'resep.pasien' => '= ?',
        'AND',
        'resep.kunjungan' => '= ?',
      );

      $paramValue = array($parameter['pasien'], $parameter['kunjungan']);
    } else {
      $paramData = array(
        'resep.deleted_at' => 'IS NULL',
        'AND',
        'resep.pasien' => '= ?',
        'AND',
        'resep.kunjungan' => '= ?',
      );

      $paramValue = array($parameter['pasien'], $parameter['kunjungan']);
    }



    if ($parameter['length'] < 0) {
      $data = self::$query->select('resep', array(
        'uid',
        'kunjungan',
        'antrian',
        'asesmen',
        'dokter',
        'pasien',
        'total',
        'status_resep',
        'created_at',
        'updated_at'
      ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('resep', array(
        'uid',
        'kunjungan',
        'antrian',
        'asesmen',
        'dokter',
        'pasien',
        'total',
        'status_resep',
        'created_at',
        'updated_at'
      ))
        ->where($paramData, $paramValue)
        ->offset(intval($parameter['start']))
        ->limit(intval($parameter['length']))
        ->execute();
    }

    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;
    $Inventori = new Inventori(self::$pdo);
    $Antrian = new Antrian(self::$pdo);
    $Pegawai = new Pegawai(self::$pdo);

    foreach ($data['response_data'] as $key => $value) {
      $AntrianDetail = $Antrian->get_antrian_detail('antrian', $value['antrian']);
      $data['response_data'][$key]['antrian_detail'] = $AntrianDetail['response_data'][0];

      $PegawaiDetail = $Pegawai->get_detail($value['dokter']);
      $data['response_data'][$key]['dokter_detail'] = $PegawaiDetail['response_data'][0];

      //Get resep detail
      /*$resep_detail = self::$query->select('resep_detail', array(
                'id',
                'resep',
                'obat',
                'harga',
                'signa_qty',
                'signa_pakai',
                'qty',
                'satuan',
                'created_at',
                'updated_at'
            ))
                ->where(array(
                    'resep_detail.resep' => '= ?',
                    'AND',
                    'resep_detail.deleted_at' => 'IS NULL'
                ), array(
                    $value['uid']
                ))
                ->execute();*/
      $resep_detail = self::$query->select('resep_change_log', array(
        'resep',
        'item as obat',
        'signa_qty',
        'signa_pakai',
        'qty'
      ))
        ->where(array(
          'resep_change_log.resep' => '= ?',
          'AND',
          'resep_change_log.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($resep_detail['response_data'] as $ResKey => $ResValue) {
        //Batch Info
        $InventoriBatch = $Inventori->get_item_batch($ResValue['obat']);
        $resep_detail['response_data'][$ResKey]['batch'] = $InventoriBatch['response_data'];

        $InventoriInfo = $Inventori->get_item_detail($ResValue['obat']);
        $resep_detail['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];

        //Check Ketersediaan Obat pada NS
        $NSInap = self::$query->select('igd_batch', array(
          'qty', 'status'
        ))
          ->where(array(
            'igd_batch.gudang' => '= ?',
            'AND',
            'igd_batch.obat' => '= ?',
            'AND',
            'igd_batch.resep' => '= ?',
            'AND',
            'igd_batch.status' => '!= ?'
          ), array(
            $UnitDetail['uid_gudang'],
            $ResValue['obat'],
            $value['uid'],
            'N'
          ))
          ->execute();
        $resep_detail['response_data'][$ResKey]['stok_ns'] = $NSInap['response_data'];
      }
      $data['response_data'][$key]['detail'] = $resep_detail['response_data'];


      //Racikan Item
      $racikan = self::$query->select('racikan', array(
        'uid',
        'asesmen',
        //'resep',
        'kode',
        'total',
        'keterangan',
        'signa_qty',
        'signa_pakai',
        'qty',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'racikan.asesmen' => '= ?',
          'AND',
          'racikan.deleted_at' => 'IS NULL'
        ), array(
          $value['asesmen']
        ))
        ->execute();
      foreach ($racikan['response_data'] as $RDKey => $RDValue) {

        //Stok Racikan NS
        $history_racikan = self::$query->select('igd_riwayat_obat', array(
          'qty'
        ))
          ->where(array(
            'igd_riwayat_obat.obat' => '= ?',
            'AND',
            'igd_riwayat_obat.resep' => '= ?'
          ), array(
            $RDValue['kode'],
            $value['uid']
          ))
          ->execute();
        $racikan['response_data'][$RDKey]['ns_qty'] = $history_racikan['response_data'];
        $racikan_detail = self::$query->select('racikan_detail', array(
          'id',
          'asesmen',
          //'resep',
          'obat',
          'ratio',
          'pembulatan',
          'harga',
          'racikan',
          'takar_bulat',
          'takar_decimal',
          'penjamin',
          'created_at',
          'updated_at'
        ))
          ->where(array(
            'racikan_detail.deleted_at' => 'IS NULL',
            /*'AND',
                        'racikan_detail.resep' => '= ?',*/
            'AND',
            'racikan_detail.racikan' => '= ?'
          ), array(
            //$value['uid'],
            $RDValue['uid']
          ))
          ->execute();
        foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
          $InventoriInfo = $Inventori->get_item_detail($RDIValue['obat']);

          $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];
        }
        $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];

        // BillingApotek
        $racikan_change = self::$query->select('racikan_change_log', array(
          'jumlah', 'keterangan', 'signa_qty', 'signa_pakai', 'aturan_pakai', 'alasan_ubah'
        ))
          ->where(array(
            'racikan_change_log.racikan' => '= ?'
          ), array(
            $RDValue['uid']
          ))
          ->execute();
        foreach ($racikan_change['response_data'] as $RacApKey => $RacApValue) {
          $DetailApRac = self::$query->select('racikan_detail_change_log', array(
            'obat', 'kekuatan', 'jumlah'
          ))
            ->where(array(
              'racikan_detail_change_log.racikan' => '= ?'
            ), array(
              $RDValue['uid']
            ))
            ->execute();
          foreach ($DetailApRac['response_data'] as $RApoDIKey => $RApoDIValue) {
            $InventoriInfo = $Inventori->get_item_detail($RApoDIValue['obat']);
            $DetailApRac['response_data'][$RApoDIKey]['detail'] = $InventoriInfo['response_data'][0];
          }
          $racikan_change['response_data'][$RacApKey]['detail'] = $DetailApRac['response_data'];
        }
        $racikan['response_data'][$RDKey]['racikan_apotek'] = $racikan_change['response_data'];
      }
      $data['response_data'][$key]['racikan'] = $racikan['response_data'];
      $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    $itemTotal = self::$query->select('resep', array(
      'uid'
    ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($data['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);

    return $data;
  }

  private function extend_resep($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    //Check Invoice
    $Invoice = new Invoice(self::$pdo);
    $InvoiceCheck = self::$query->select('invoice', array(
      'uid'
    ))
      ->where(array(
        'invoice.kunjungan' => '= ?',
        'AND',
        'invoice.deleted_at' => 'IS NULL'
      ), array(
        $parameter['kunjungan']
      ))
      ->execute();

    if (count($InvoiceCheck['response_data']) > 0) {
      $TargetInvoice = $InvoiceCheck['response_data'][0]['uid'];
    } else {
      $InvMasterParam = array(
        'kunjungan' => $parameter['kunjungan'],
        'pasien' => $parameter['pasien'],
        'keterangan' => 'Tagihan tindakan perobatan'
      );
      $NewInvoice = $Invoice->create_invoice($InvMasterParam);
      $TargetInvoice = $NewInvoice['response_unique'];
    }


    $check = self::$query->select('resep', array(
      'uid',
      'kode'
    ))
      ->where(array(
        'resep.kunjungan' => '= ?',
        'AND',
        'resep.antrian' => '= ?',
        'AND',
        'resep.asesmen' => '= ?',
        'AND',
        'resep.dokter' => '= ?',
        'AND',
        'resep.pasien' => '= ?',
        'AND',
        '(resep.status_resep' => '= ?',
        'OR',
        'resep.status_resep' => '= ?)',
        'AND',
        'resep.deleted_at' => 'IS NULL'
      ), array(
        $parameter['kunjungan'],
        $parameter['antrian'],
        $parameter['asesmen'],
        $UserData['data']->uid,
        $parameter['pasien'],
        'N',
        'C'
      ))
      ->execute();

    if (count($check['response_data']) > 0) {
      $uid = $check['response_data'][0]['uid'];
      $Kode = $check['response_data'][0]['kode'];

      //Update resep master
      $resepUpdate = self::$query->update('resep', array(
        'alergi_obat' => (isset($parameter['editorAlergiObat']) && !is_null($parameter['editorAlergiObat']) && !empty($parameter['editorAlergiObat'])) ? $parameter['editorAlergiObat'] : '',
        'status_resep' => ($parameter['charge_invoice'] === 'Y') ? 'N' : 'C',
        'iterasi' => (isset($parameter['iterasi'])) ? intval($parameter['iterasi']) : 0,
        'alasan_tambahan' => $parameter['alasan'],
        'keterangan' => $parameter['keteranganResep'],
        'keterangan_racikan' => $parameter['keteranganRacikan']
      ))
        ->where(array(
          'resep.uid' => '= ?',
          'AND',
          'resep.deleted_at' => 'IS NULL'
        ), array(
          $uid
        ))
        ->execute();

      //Reset Resep Detail
      $resetResep = self::$query->update('resep_detail', array(
        'deleted_at' => parent::format_date()
      ))
        ->where(array(
          'resep_detail.resep' => '= ?'
        ), array(
          $uid
        ))
        ->execute();

      //Update Detail Resep
      $used_obat = array();
      $old_resep_detail = array();
      $detail_check = self::$query->select('resep_detail', array(
        'id',
        'obat'
      ))
        ->where(array(
          'resep_detail.resep' => '= ?'
        ), array(
          $uid
        ))
        ->execute();

      foreach ($detail_check['response_data'] as $key => $value) {
        if (!in_array($value['obat'], $used_obat)) {
          array_push($used_obat, $value['obat']);
          array_push($old_resep_detail, $value);
        }
      }

      $resepProcess = array();

      foreach ($parameter['resep'] as $key => $value) {
        //Prepare Data Obat
        $ObatDetail = new Inventori(self::$pdo);
        $ObatInfo = $ObatDetail->get_item_detail($value['obat'])['response_data'][0];

        if (in_array($value['obat'], $used_obat)) {
          $worker = self::$query->update('resep_detail', array(
            'signa_qty' => $value['signaKonsumsi'],
            'signa_pakai' => $value['signaTakar'],
            'iterasi' => (isset($value['iterasi'])) ? intval($value['iterasi']) : 0,
            'qty' => $value['signaHari'],
            'aturan_pakai' => intval($value['aturanPakai']),
            'keterangan' => $value['keteranganPerObat'],
            'satuan_konsumsi' => $value['satuanPemakaian'],
            'updated_at' => parent::format_date(),
            'deleted_at' => NULL
          ))
            ->where(array(
              'resep_detail.resep' => '= ?',
              'AND',
              'resep_detail.obat' => '= ?'
            ), array(
              $uid,
              $value['obat']
            ))
            ->execute();
        } else {
          $worker = self::$query->insert('resep_detail', array(
            'resep' => $uid,
            'obat' => $value['obat'],
            'harga' => 0,
            'iterasi' => (isset($value['iterasi'])) ? intval($value['iterasi']) : 0,
            'signa_qty' => $value['signaKonsumsi'],
            'signa_pakai' => $value['signaTakar'],
            'qty' => $value['signaHari'],
            'satuan' => $ObatInfo['satuan_terkecil'],
            'aturan_pakai' => intval($value['aturanPakai']),
            'keterangan' => $value['keteranganPerObat'],
            'satuan_konsumsi' => $value['satuanPemakaian'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
          ))
            ->execute();
        }
        array_push($resepProcess, $worker);
      }




      //Reset Racikan
      $racikReset = self::$query->update('racikan', array(
        'deleted_at' => parent::format_date()
      ))
        ->where(array(
          'racikan.asesmen' => '= ?'
        ), array(
          $parameter['asesmen']
        ))
        ->execute();


      //Filter #1
      $racikanOld = self::$query->select('racikan', array(
        'uid'
      ))
        ->where(array(
          'racikan.asesmen' => '= ?'
          /*,'AND',
                    'racikan.kode' => '= ?'*/
        ), array(
          $parameter['asesmen']
          //,$parameter['racikan'][$key]['nama']
        ))
        ->execute();

      $racikanError = array();

      foreach ($racikanOld['response_data'] as $key => $value) {
        $racikanUpdate = self::$query->update('racikan', array(
          'kode' => '[' . $Kode . ']' . $parameter['racikan'][$key]['nama'],
          'iterasi' => (isset($parameter['racikan'][$key]['iterasi'])) ? intval($parameter['racikan'][$key]['iterasi']) : 0,
          'aturan_pakai' => intval($parameter['racikan'][$key]['aturanPakai']),
          'keterangan' => $parameter['racikan'][$key]['keterangan'],
          'signa_qty' => $parameter['racikan'][$key]['signaKonsumsi'],
          'signa_pakai' => $parameter['racikan'][$key]['signaTakar'],
          'qty' => $parameter['racikan'][$key]['signaHari'],
          'deleted_at' => NULL
        ))
          ->where(array(
            'racikan.uid' => '= ?'
          ), array(
            $value['uid']
          ))
          ->execute();
        if ($racikanUpdate['response_result'] > 0) {
          //
        } else {
          //array_push($racikanError, $racikanUpdate);
        }



        //Reset Racikan Detail
        /*$resetRacikanDetail = self::$query->update('racikan_detail', array(
                    'deleted_at' => parent::format_date()
                ))
                    ->where(array(
                        'racikan_detail.racikan' => '= ?',
                        'AND',
                        'racikan_detail.asesmen' => '= ?'
                    ), array(
                        $value['uid'],
                        $MasterAsesmen
                    ))
                    ->execute();*/

        $resetRacikanDetail = self::$query->hard_delete('racikan_detail')
          ->where(array(
            /*'racikan_detail.resep' => '= ?',
                        'AND',*/
            'racikan_detail.racikan' => '= ?',
            'AND',
            'racikan_detail.asesmen' => '= ?'
          ), array(
            //$uid,
            $value['uid'],
            $parameter['asesmen']
          ))
          ->execute();

        //Old Racikan Detail
        $checkRacikanDetail = self::$query->select('racikan_detail', array(
          'id',
          'obat'
        ))
          ->where(array(
            /*'racikan_detail.resep' => '= ?',
                        'AND',*/
            'racikan_detail.racikan' => '= ?',
            'AND',
            'racikan_detail.asesmen' => '= ?'
          ), array(
            //$uid,
            $value['uid'],
            $parameter['asesmen']
          ))
          ->execute();

        $oldRacikanDetail = array();
        $usedRacikanDetail = array();
        foreach ($checkRacikanDetail['response_data'] as $RDKey => $RDValue) {
          if (!in_array($RDValue['obat'], $usedRacikanDetail)) {
            array_push($usedRacikanDetail, $RDValue['obat']);
            array_push($oldRacikanDetail, $RDValue);
          }
        }

        foreach ($parameter['racikan'][$key]['item'] as $RDIKey => $RDIValue) {
          if (in_array($RDIValue['obat'], $usedRacikanDetail)) {
            $racikanDetailWorker = self::$query->update('racikan_detail', array(
              'obat' => $RDIValue['obat'],
              'ratio' => floatval($RDIValue['takaran']),
              'kekuatan' => $RDIValue['kekuatan'],
              'penjamin' => $parameter['penjamin'],
              //'takar_bulat' => $RDIValue['takaranBulat'],
              //'takar_decimal' => $RDIValue['takaranDecimalText'],
              'pembulatan' => ceil($RDIValue['takaran']),
              'deleted_at' => NULL
            ))
              ->where(array(
                /*'racikan_detail.resep' => '= ?',
                                'AND',*/
                'racikan_detail.racikan' => '= ?',
                'AND',
                'racikan_detail.asesmen' => '= ?',
                'AND',
                'racikan_detail.obat' => '= ?'
              ), array(
                //$uid,
                $value['uid'],
                $parameter['asesmen'],
                $RDIValue['obat']
              ))
              ->execute();
          } else {
            $racikanDetailWorker = self::$query->insert('racikan_detail', array(
              'asesmen' => $parameter['asesmen'],
              //'resep' => $uid,
              'obat' => $RDIValue['obat'],
              'pembulatan' => ceil($RDIValue['takaran']),
              'kekuatan' => $RDIValue['kekuatan'],
              //'takar_bulat' => $RDIValue['takaranBulat'],
              //'takar_decimal' => $RDIValue['takaranDecimalText'],
              'harga' => 0,
              'created_at' => parent::format_date(),
              'updated_at' => parent::format_date(),
              'racikan' => $value['uid'],
              'ratio' => floatval($RDIValue['takaran'])
            ))
              ->execute();
          }
        }

        //array_push($racikanError, $racikanDetailWorker);

        //Unset processed data from parameter
        unset($parameter['racikan'][$key]);
      }

      //UnProcessed Racikan
      foreach ($parameter['racikan'] as $key => $value) {
        $newRacikanUID = parent::gen_uuid();
        $newRacikan = self::$query->insert('racikan', array(
          'uid' => $newRacikanUID,
          'asesmen' => $parameter['asesmen'],
          'kode' => '[' . $Kode . ']' . $value['nama'],
          'total' => 0,
          'signa_qty' => $value['signaKonsumsi'],
          'iterasi' => (isset($value['iterasi'])) ? intval($value['iterasi']) : 0,
          'keterangan' => $value['keterangan'],
          'signa_pakai' => $value['signaTakar'],
          'aturan_pakai' => intval($value['aturanPakai']),
          'qty' => $value['signaHari'],
          'created_at' => parent::format_date(),
          'updated_at' => parent::format_date()
        ))
          ->execute();

        if ($newRacikan['response_result'] > 0) {
          $racikanDetail = $parameter['racikan']['item'];
          foreach ($racikanDetail as $RDKey => $RDValue) {
            $detailRacikan = self::$query->insert('racikan_detail', array(
              'asesmen' => $parameter['asesmen'],
              'racikan' => $newRacikanUID,
              //'resep' => $uid,
              'obat' => $RDValue['obat'],
              'ratio' => floatval($RDValue['takaran']),
              'pembulatan' => ceil($RDValue['takaran']),
              'kekuatan' => $RDValue['kekuatan'],
              //'takar_bulat' => $RDIValue['takaranBulat'],
              //'takar_decimal' => $RDIValue['takaranDecimalText'],
              'harga' => 0,
              'penjamin' => '',
              'created_at' => parent::format_date(),
              'updated_at' => parent::format_date(),
            ))
              ->execute();
            array_push($racikanError, $detailRacikan);
          }
        } else {
          array_push($racikanError, $newRacikan);
        }
      }

      return array('resep' => $resepProcess, 'racikan' => $racikanError, 'response_unique' => $uid);
    } else { //Jika Resep baru


      if (count($parameter['resep']) > 0 || count($parameter['racikan']) > 0 || isset($parameter['isnew'])) {
        //New Resep
        $uid = parent::gen_uuid();

        $lastNumber = self::$query->select('resep', array(
          'uid'
        ))
          ->where(array(
            'EXTRACT(month FROM created_at)' => '= ?'
          ), array(
            intval(date('m'))
          ))
          ->execute();
        $Kode = 'RSP-' . date('Y/m') . '-' . str_pad(strval(count($lastNumber['response_data']) + 1), 4, '0', STR_PAD_LEFT);

        $newResep = self::$query->insert('resep', array(
          'uid' => $uid,
          'kode' => $Kode,
          'kunjungan' => $parameter['kunjungan'],
          'antrian' => $parameter['antrian'],
          'keterangan' => $parameter['keteranganResep'],
          'keterangan_racikan' => $parameter['keteranganRacikan'],
          'asesmen' => $parameter['asesmen'],
          'dokter' => $UserData['data']->uid,
          'pasien' => $parameter['pasien'],
          'alergi_obat' => (isset($parameter['editorAlergiObat']) && !is_null($parameter['editorAlergiObat']) && !empty($parameter['editorAlergiObat'])) ? $parameter['editorAlergiObat'] : '',
          'total' => 0,
          'iterasi' => (isset($parameter['iterasi'])) ? intval($parameter['iterasi']) : 0,
          'alasan_tambahan' => $parameter['alasan'],
          'status_resep' => ($parameter['charge_invoice'] === 'Y') ? 'N' : 'C',
          'created_at' => parent::format_date(),
          'updated_at' => parent::format_date()
        ))
          ->execute();

        if ($newResep['response_result'] > 0) {
          $resep_detail_error = array();
          //SetDetail
          foreach ($parameter['resep'] as $key => $value) {
            $ObatDetail = new Inventori(self::$pdo);
            $ObatInfo = $ObatDetail->get_item_detail($value['obat'])['response_data'][0];

            $newResepDetail = self::$query->insert('resep_detail', array(
              'resep' => $uid,
              'obat' => $value['obat'],
              'aturan_pakai' => intval($value['aturanPakai']),
              'iterasi' => (isset($value['iterasi'])) ? intval($value['iterasi']) : 0,
              'harga' => 0,
              'signa_qty' => $value['signaKonsumsi'],
              'signa_pakai' => $value['signaTakar'],
              'qty' => $value['signaHari'],
              'satuan' => $ObatInfo['satuan_terkecil'],
              'satuan_konsumsi' => $value['satuanPemakaian'],
              'created_at' => parent::format_date(),
              'updated_at' => parent::format_date(),
              'keterangan' => $value['keteranganPerObat']
            ))
              ->execute();
            array_push($resep_detail_error, $newResepDetail);
          }

          foreach ($parameter['racikan'] as $key => $value) {
            $uid_racikan = parent::gen_uuid();
            $newRacikan = self::$query->insert('racikan', array(
              'uid' => $uid_racikan,
              'asesmen' => $parameter['asesmen'],
              //'resep' => $uid,
              'kode' => '[' . $Kode . ']' . $value['nama'],
              'iterasi' => (isset($value['iterasi'])) ? intval($value['iterasi']) : 0,
              'signa_qty' => $value['signaKonsumsi'],
              'signa_pakai' => $value['signaTakar'],
              'keterangan' => $value['keterangan'],
              'aturan_pakai' => intval($value['aturanPakai']),
              'qty' => $value['signaHari'],
              'total' => 0,
              'created_at' => parent::format_date(),
              'updated_at' => parent::format_date()
            ))
              ->execute();

            if ($newRacikan['response_result'] > 0) {
              /*$newResepDetail = self::$pdo->insert('resep_detail', array(
                                'resep' => $uid,
                                'obat' => $uid_racikan,
                                'aturan_pakai' => $value['aturanPakai'],
                                'harga' => 0,
                                'signa_qty' => $value['signaKonsumsi'],
                                'signa_pakai' => $value['signaTakar'],
                                'qty' => $value['signaHari'],
                                'satuan' => '',
                                'created_at' => parent::format_date(),
                                'updated_at' => parent::format_date()
                            ))
                            ->execute();*/

              //Set Racikan Detail
              foreach ($value['item'] as $RIKey => $RIValue) {
                $newRacikanDetail = self::$query->insert('racikan_detail', array(
                  'asesmen' => $parameter['asesmen'],
                  //'resep' => $uid_racikan,
                  'obat' => $RIValue['obat'],
                  'ratio' => floatval($RIValue['takaran']),
                  'pembulatan' => ceil(floatval($RIValue['takaran'])),
                  'kekuatan' => $RIValue['kekuatan'],
                  //'takar_bulat' => $RIValue['takaranBulat'],
                  //'takar_decimal' => $RIValue['takaranDecimalText'],
                  'harga' => 0,
                  'racikan' => $uid_racikan,
                  'created_at' => parent::format_date(),
                  'updated_at' => parent::format_date()
                ))
                  ->execute();
              }
            }
          }
        }
        $newResep['response_unique'] = $uid;
        return $newResep;
      }
    }
  }

  private function aktifkan_resep($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $worker = self::$query->update('resep', array(
      'deleted_at' => NULL
    ))
      ->where(array(
        'resep.uid' => '= ?'
      ), array(
        $parameter['uid']
      ))
      ->execute();
    if ($worker['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'old_value',
          'new_value',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $parameter['uid'],
          $UserData['data']->uid,
          'resep_kajian',
          'U',
          'Aktifkan Kembali Resep',
          $parameter['alasan'],
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }

    return $worker;
  }

  private function batalkan_resep($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $worker = self::$query->update('resep', array(
      'deleted_at' => parent::format_date(),
      'status_resep' => 'C'
    ))
      ->where(array(
        'resep.uid' => '= ?'
      ), array(
        $parameter['uid']
      ))
      ->execute();
    if ($worker['response_result'] > 0) {
      //Catat Alasan
      $CancelationReason = self::$query->insert('cancelation_resep', array(
        'resep' => $parameter['uid'],
        'oleh' => $UserData['data']->uid,
        'alasan' => $parameter['alasan'],
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();
      if ($CancelationReason['response_result'] > 0) {
        $log = parent::log(array(
          'type' => 'activity',
          'column' => array(
            'unique_target',
            'user_uid',
            'table_name',
            'action',
            'logged_at',
            'status',
            'login_id'
          ),
          'value' => array(
            $parameter['uid'],
            $UserData['data']->uid,
            'resep',
            'D',
            parent::format_date(),
            'N',
            $UserData['data']->log_id
          ),
          'class' => __CLASS__
        ));
      }
    }

    return $worker;
  }

  private function get_resep_dokter($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'master_penjamin.deleted_at' => 'IS NULL',
        'AND',
        'resep.dokter' => '= ?',
        'AND',
        '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'OR',
        'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
      );
      $paramValue = array($UserData['data']->uid);
    } else {
      $paramData = array(
        'master_penjamin.deleted_at' => 'IS NULL',
        'AND',
        'resep.dokter' => '= ?'
      );
      $paramValue = array($UserData['data']->uid);
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('resep', array(
        'uid',
        'kunjungan',
        'antrian',
        'asesmen',
        'dokter',
        'pasien',
        'total',
        'status_resep',
        'created_at',
        'updated_at'
      ))
        ->join('pasien', array(
          'nama as nama_pasien',
          'no_rm'
        ))
        ->join('antrian', array(
          'departemen',
          'penjamin',
          'created_at as tanggal_antrian'
        ))
        ->join('master_penjamin', array(
          'nama as nama_penjamin'
        ))
        ->on(array(
          array('resep.pasien', '=', 'pasien.uid'),
          array('resep.antrian', '=', 'antrian.uid'),
          array('antrian.penjamin', '=', 'master_penjamin.uid')
        ))
        ->order(array(
          'resep.created_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('resep', array(
        'uid',
        'kunjungan',
        'antrian',
        'asesmen',
        'dokter',
        'pasien',
        'total',
        'status_resep',
        'created_at',
        'updated_at'
      ))
        ->join('pasien', array(
          'nama as nama_pasien',
          'no_rm'
        ))
        ->join('antrian', array(
          'departemen',
          'penjamin',
          'created_at as tanggal_antrian'
        ))
        ->join('master_penjamin', array(
          'nama as nama_penjamin'
        ))
        ->on(array(
          array('resep.pasien', '=', 'pasien.uid'),
          array('resep.antrian', '=', 'antrian.uid'),
          array('antrian.penjamin', '=', 'master_penjamin.uid')
        ))
        ->order(array(
          'resep.created_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->offset(intval($parameter['start']))
        ->limit(intval($parameter['length']))
        ->execute();
    }

    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;

    $Pegawai = new Pegawai(self::$pdo);
    $Pasien = new Pasien(self::$pdo);
    $Poli = new Poli(self::$pdo);
    $Penjamin = new Penjamin(self::$pdo);
    $dataBiasa = array();

    foreach ($data['response_data'] as $key => $value) {
      //Check Cancelation
      $Cancelation = self::$query->select('cancelation_resep', array(
        'alasan', 'oleh', 'created_at'
      ))
        ->where(array(
          'cancelation_resep.resep' => '= ?',
          'AND',
          'cancelation_resep.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($Cancelation['response_data'] as $CKey => $CValue) {
        $Cancelation['response_data'][$CKey]['created_at'] = date('d F Y', strtotime($CValue['created_at'])) . ' - ' . date('[H:i]', strtotime($CValue['created_at']));
        $Cancelation['response_data'][$CKey]['oleh'] = $Pegawai->get_info($CValue['oleh'])['response_data'][0];
      }

      $data['response_data'][$key]['cancelation'] = $Cancelation['response_data'];


      //Dokter Info
      $PegawaiInfo = $Pegawai->get_info($value['dokter']);
      $data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

      $PasienData = $Pasien->get_pasien_detail('pasien', $value['pasien']);
      $data['response_data'][$key]['pasien_info'] = $PasienData['response_data'][0];

      //Departemen Info
      if ($value['departemen'] === __POLI_INAP__) {
        $data['response_data'][$key]['departemen'] = array(
          'uid' => __POLI_INAP__,
          'nama' => 'Rawat Inap'
        );

        //NS Info
        $NS = self::$query->select('rawat_inap', array(
          'nurse_station'
        ))
          ->join('nurse_station', array(
            'kode as kode_ns', 'nama as nama_ns'
          ))
          ->on(array(
            array('rawat_inap.nurse_station', '=', 'nurse_station.uid')
          ))
          ->where(array(
            'rawat_inap.kunjungan' => '= ?',
            'AND',
            'rawat_inap.dokter' => '= ?',
            'AND',
            'rawat_inap.pasien' => '= ?'
          ), array(
            $value['kunjungan'],
            $value['dokter'],
            $value['pasien']
          ))
          ->execute();
        $data['response_data'][$key]['ns_detail'] = $NS['response_data'][0];
      } else {
        $PoliInfo = $Poli->get_poli_info($value['departemen']);
        $data['response_data'][$key]['departemen'] = (count($PoliInfo['response_data']) > 0) ? $PoliInfo['response_data'][0] : $value['departemen'];
      }

      $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
      $data['response_data'][$key]['penjamin'] = $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0];

      $current = strtotime(date('Y-m-d'));
      $date    = strtotime($value['tanggal_antrian']);

      $datediff = $date - $current;
      $difference = floor($datediff / (60 * 60 * 24));


      $data['response_data'][$key]['allow_edit'] = ($difference === 0);
      $data['response_data'][$key]['created_time'] = parent::humanTiming($date);
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    $itemTotal = self::$query->select('resep', array(
      'uid', 'pasien', 'antrian'
    ))
      ->join('pasien', array(
        'nama as nama_pasien',
        'no_rm'
      ))
      ->join('antrian', array(
        'departemen'
      ))
      ->on(array(
        array('resep.pasien', '=', 'pasien.uid'),
        array('resep.antrian', '=', 'antrian.uid')
      ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($data['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);

    return $data;
  }

  private function get_resep_backend_v3($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    if (isset($parameter['request_type'])) {
      if ($parameter['request_type'] === 'inap') {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '(resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'AND',
            'antrian.departemen' => '= ?',
            'AND',
            '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        } else {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '(resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'AND',
            'antrian.departemen' => '= ?',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        }

        $paramValue = array('K', 'L', __POLI_INAP__);
      } else if ($parameter['request_type'] === 'verifikasi') {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            'resep.status_resep' => '= ?',
            'AND',
            'antrian.departemen' => '!= ?',
            'AND',
            '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        } else {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            'resep.status_resep' => '= ?',
            'AND',
            'antrian.departemen' => '!= ?',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        }
        $paramValue = array('N', __POLI_IGD__);
      } else if ($parameter['request_type'] === 'batal') {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
          $paramData = array(
            'resep.deleted_at' => 'IS NOT NULL',
            'AND',
            '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        } else {
          $paramData = array(
            'resep.deleted_at' => 'IS NOT NULL',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        }

        $paramValue = array();
      } else if ($parameter['request_type'] === 'igd_lunas') {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '(resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'AND',
            'antrian.departemen' => '= ?',
            'AND',
            '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        } else {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '(resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'AND',
            'antrian.departemen' => '= ?',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        }

        $paramValue = array('D', 'P', __POLI_IGD__);
      } else if ($parameter['request_type'] === 'inap_lunas') {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '(resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'AND',
            'antrian.departemen' => '= ?',
            'AND',
            '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        } else {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '(resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'AND',
            'antrian.departemen' => '= ?',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        }

        $paramValue = array('D', 'P', __POLI_INAP__);
      } else if ($parameter['request_type'] === 'riwayat') {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
          if (isset($parameter['filter_departemen']) && $parameter['filter_departemen'] !== 'all') {
            $paramData = array(
              'resep.deleted_at' => 'IS NULL',
              'AND',
              'resep.status_resep' => '= ?',
              'AND',
              '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
              'OR',
              'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
              'AND',
              'pasien.deleted_at' => 'IS NULL',
              'AND',
              'antrian.departemen' => '= ?'
            );
          } else {
            $paramData = array(
              'resep.deleted_at' => 'IS NULL',
              'AND',
              'resep.status_resep' => '= ?',
              'AND',
              '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
              'OR',
              'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
              'AND',
              'pasien.deleted_at' => 'IS NULL'
            );
          }
        } else {
          if (isset($parameter['filter_departemen']) && $parameter['filter_departemen'] !== 'all') {
            $paramData = array(
              'resep.deleted_at' => 'IS NULL',
              'AND',
              'resep.status_resep' => '= ?',
              'AND',
              'pasien.deleted_at' => 'IS NULL',
              'AND',
              'antrian.departemen' => '= ?'
            );
          } else {
            $paramData = array(
              'resep.deleted_at' => 'IS NULL',
              'AND',
              'resep.status_resep' => '= ?',
              'AND',
              'pasien.deleted_at' => 'IS NULL'
            );
          }
        }

        if (isset($parameter['filter_departemen']) && $parameter['filter_departemen'] !== 'all') {
          $paramValue = array('S', $parameter['filter_departemen']);
        } else {
          $paramValue = array('S');
        }
      } else if ($parameter['request_type'] === 'igd') {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            'resep.status_resep' => '= ?',
            'AND',
            'antrian.departemen' => '= ?',
            'AND',
            '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        } else {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            'resep.status_resep' => '= ?',
            'AND',
            'antrian.departemen' => '= ?',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        }

        $paramValue = array('L', __POLI_IGD__);
      } else if ($parameter['request_type'] === 'lunas') {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            'resep.status_resep' => '= ?',
            'AND',
            '(NOT antrian.departemen' => '= ?',
            'AND',
            'NOT antrian.departemen' => '= ?)',
            'AND',
            '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        } else {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            'resep.status_resep' => '= ?',
            'AND',
            '(NOT antrian.departemen' => '= ?',
            'AND',
            'NOT antrian.departemen' => '= ?)',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        }

        $paramValue = array('L', __POLI_IGD__, __POLI_INAP__);
      } else if ($parameter['request_type'] === 'serah') {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '(resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'AND',
            '(NOT antrian.departemen' => '= ?',
            'AND',
            'NOT antrian.departemen' => '= ?)',
            'AND',
            'pasien.deleted_at' => 'IS NULL',
            'AND',
            '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'

          );
        } else {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '(resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'AND',
            '(NOT antrian.departemen' => '= ?',
            'AND',
            'NOT antrian.departemen' => '= ?)',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        }

        $paramValue = array('D', 'P', __POLI_IGD__, __POLI_INAP__);
      }
    } else {
      if (isset($parameter['history'])) {
        //Resep Poli Biasa
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
          $paramData = array(
            'pasien.deleted_at' => 'IS NULL',
            'AND',
            'pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'resep.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
          );
        } else {
          $paramData = array(
            'pasien.deleted_at' => 'IS NULL'
          );
        }

        $paramValue = array();
      } else {
        //Resep Poli Biasa
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '(NOT antrian.departemen' => '= ?',
            'AND',
            'NOT antrian.departemen' => '= ?)',
            'AND',
            '((resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'OR',
            '(resep.status_resep' => '= ?))',
            'AND',
            '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        } else {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '(NOT antrian.departemen' => '= ?',
            'AND',
            'NOT antrian.departemen' => '= ?)',
            'AND',
            '((resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'OR',
            '(resep.status_resep' => '= ?))',
            'AND',
            'pasien.deleted_at' => 'IS NULL'
          );
        }

        $paramValue = array(__POLI_IGD__, __POLI_INAP__, 'L', 'P', 'S');
      }
    }

    $dataIGD = self::$query->select('resep', array(
      'uid',
      'kunjungan',
      'antrian',
      'asesmen',
      'dokter',
      'pasien',
      'total',
      'status_resep',
      'waktu_panggil',
      'waktu_terima',
      'created_at',
      'updated_at'
    ))
      ->join('pasien', array(
        'nama as nama_pasien',
        'no_rm'
      ))
      ->join('antrian', array(
        'departemen',
        'penjamin'
      ))
      ->on(array(
        array('resep.pasien', '=', 'pasien.uid'),
        array('resep.antrian', '=', 'antrian.uid')
      ))
      ->order(array(
        'resep.created_at' => 'ASC'
      ))
      ->where(array(
        'resep.deleted_at' => 'IS NULL',
        'AND',
        'antrian.departemen' => '= ?',
        'AND',
        'resep.status_resep' => '= ?',
        'AND',
        '(pasien.nama' => 'ILIKE ' . '\'%' . ((isset($parameter['search']['value'])) ? $parameter['search']['value'] : '') . '%\'',
        'OR',
        'pasien.no_rm' => 'ILIKE ' . '\'%' . ((isset($parameter['search']['value'])) ? $parameter['search']['value'] : '') . '%\')'
      ), array(
        __POLI_IGD__, 'N'
      ))
      ->execute();


    if ($parameter['request_type'] === 'verifikasi') {
      if ($parameter['length'] < 0) {
        $dataMixed = self::$query->select('resep', array(
          'uid',
          'kunjungan',
          'antrian',
          'asesmen',
          'dokter',
          'pasien',
          'total',
          'status_resep',
          'waktu_panggil',
          'waktu_terima',
          'created_at',
          'updated_at'
        ))
          ->join('pasien', array(
            'nama as nama_pasien',
            'no_rm'
          ))
          ->join('antrian', array(
            'departemen',
            'penjamin'
          ))
          ->on(array(
            array('resep.pasien', '=', 'pasien.uid'),
            array('resep.antrian', '=', 'antrian.uid')
          ))
          ->order(array(
            'resep.created_at' => 'ASC'
          ))
          ->where($paramData, $paramValue)
          ->execute();
      } else {
        $dataMixed = self::$query->select('resep', array(
          'uid',
          'kunjungan',
          'antrian',
          'asesmen',
          'dokter',
          'pasien',
          'total',
          'status_resep',
          'waktu_panggil',
          'waktu_terima',
          'created_at',
          'updated_at'
        ))
          ->join('pasien', array(
            'nama as nama_pasien',
            'no_rm'
          ))
          ->join('antrian', array(
            'departemen',
            'penjamin'
          ))
          ->on(array(
            array('resep.pasien', '=', 'pasien.uid'),
            array('resep.antrian', '=', 'antrian.uid')
          ))
          ->order(array(
            'resep.created_at' => 'ASC'
          ))
          ->where($paramData, $paramValue)
          ->offset(intval($parameter['start']))
          ->limit(intval($parameter['length']))
          ->execute();
      }
      $data = array(
        // 'response_data' => array_merge($dataIGD['response_data'], array_splice($dataMixed['response_data'], 0, (count($dataMixed['response_data']) - count($dataIGD['response_data']))))
        // 'response_data' => $dataMixed['response_data']
        'response_data' => array_merge($dataIGD['response_data'], $dataMixed['response_data']),
        'response_igd' => $dataIGD
      );
    } else {
      if ($parameter['length'] < 0) {
        $data = self::$query->select('resep', array(
          'uid',
          'kunjungan',
          'antrian',
          'asesmen',
          'dokter',
          'pasien',
          'total',
          'status_resep',
          'waktu_panggil',
          'waktu_terima',
          'created_at',
          'updated_at'
        ))
          ->join('pasien', array(
            'nama as nama_pasien',
            'no_rm'
          ))
          ->join('antrian', array(
            'departemen',
            'penjamin'
          ))
          ->on(array(
            array('resep.pasien', '=', 'pasien.uid'),
            array('resep.antrian', '=', 'antrian.uid')
          ))
          ->order(array(
            'resep.created_at' => 'ASC'
          ))
          ->where($paramData, $paramValue)
          ->execute();
      } else {
        $data = self::$query->select('resep', array(
          'uid',
          'kunjungan',
          'antrian',
          'asesmen',
          'dokter',
          'pasien',
          'total',
          'status_resep',
          'waktu_panggil',
          'waktu_terima',
          'created_at',
          'updated_at'
        ))
          ->join('pasien', array(
            'nama as nama_pasien',
            'no_rm'
          ))
          ->join('antrian', array(
            'departemen',
            'penjamin'
          ))
          ->on(array(
            array('resep.pasien', '=', 'pasien.uid'),
            array('resep.antrian', '=', 'antrian.uid')
          ))
          ->order(array(
            'resep.created_at' => 'ASC'
          ))
          ->where($paramData, $paramValue)
          ->offset(intval($parameter['start']))
          ->limit(intval($parameter['length']))
          ->execute();
      }
    }


    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;

    $Pegawai = new Pegawai(self::$pdo);
    $Pasien = new Pasien(self::$pdo);
    $Poli = new Poli(self::$pdo);
    $Penjamin = new Penjamin(self::$pdo);
    $dataBiasa = array();

    foreach ($data['response_data'] as $key => $value) {
      //Check Cancelation
      $Cancelation = self::$query->select('cancelation_resep', array(
        'alasan', 'oleh', 'created_at'
      ))
        ->where(array(
          'cancelation_resep.resep' => '= ?',
          'AND',
          'cancelation_resep.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($Cancelation['response_data'] as $CKey => $CValue) {
        $Cancelation['response_data'][$CKey]['created_at'] = date('d F Y', strtotime($CValue['created_at'])) . ' - ' . date('[H:i]', strtotime($CValue['created_at']));
        $Cancelation['response_data'][$CKey]['oleh'] = $Pegawai->get_info($CValue['oleh'])['response_data'][0];
      }

      $data['response_data'][$key]['cancelation'] = $Cancelation['response_data'];


      //Dokter Info
      $PegawaiInfo = $Pegawai->get_info($value['dokter']);
      $data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

      $PasienData = $Pasien->get_pasien_info('pasien', $value['pasien']);
      $data['response_data'][$key]['pasien_info'] = $PasienData['response_data'][0];

      if ($value['departemen'] === __POLI_INAP__ || $value['departemen'] === __POLI_IGD__) {
        $start_date = new \DateTime($value['created_at']);
        $since_start = $start_date->diff(new \DateTime($value['waktu_terima']));
        $data['response_data'][$key]['response_time'] = (isset($value['waktu_terima']) && $value['waktu_terima'] !== '' && !empty($value['waktu_terima'])) ? str_pad($since_start->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($since_start->i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($since_start->s, 2, '0', STR_PAD_LEFT) : '-';
        $data['response_data'][$key]['response_to'] = $value['waktu_terima'];
      } else {
        $start_date = new \DateTime($value['created_at']);
        $since_start = $start_date->diff(new \DateTime($value['waktu_panggil']));
        $data['response_data'][$key]['response_time'] = (isset($value['waktu_panggil']) && $value['waktu_panggil'] !== '' && !empty($value['waktu_panggil'])) ? str_pad($since_start->h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($since_start->i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($since_start->s, 2, '0', STR_PAD_LEFT) : '-';
        $data['response_data'][$key]['response_to'] = $value['waktu_panggil'];
      }

      $minutes = $since_start->days * 24 * 60;
      $minutes += $since_start->h * 60;
      $minutes += $since_start->i;
      $data['response_data'][$key]['response_min'] = $minutes;


      //Departemen Info
      if ($value['departemen'] === __POLI_INAP__) {
        $data['response_data'][$key]['departemen'] = array(
          'uid' => __POLI_INAP__,
          'nama' => 'Rawat Inap'
        );

        //NS Info
        $NS = self::$query->select('rawat_inap', array(
          'nurse_station'
        ))
          ->join('nurse_station', array(
            'kode as kode_ns', 'nama as nama_ns'
          ))
          ->on(array(
            array('rawat_inap.nurse_station', '=', 'nurse_station.uid')
          ))
          ->where(array(
            'rawat_inap.kunjungan' => '= ?',
            'AND',
            // 'rawat_inap.dokter' => '= ?',
            // 'AND',
            'rawat_inap.pasien' => '= ?'
          ), array(
            $value['kunjungan'],
            //$value['dokter'],
            $value['pasien']
          ))
          ->execute();
        $data['response_data'][$key]['ns_response'] = $NS;
        $data['response_data'][$key]['ns_detail'] = $NS['response_data'][0];
      } else if ($value['departemen'] === __POLI_IGD__) {
        $data['response_data'][$key]['departemen'] = array(
          'uid' => __POLI_IGD__,
          'nama' => 'IGD'
        );

        //NS Info
        $NS = self::$query->select('rawat_inap', array(
          'nurse_station'
        ))
          ->join('nurse_station', array(
            'kode as kode_ns', 'nama as nama_ns'
          ))
          ->on(array(
            array('rawat_inap.nurse_station', '=', 'nurse_station.uid')
          ))
          ->where(array(
            'rawat_inap.kunjungan' => '= ?',
            'AND',
            // 'rawat_inap.dokter' => '= ?',
            // 'AND',
            'rawat_inap.pasien' => '= ?'
          ), array(
            $value['kunjungan'],
            //$value['dokter'],
            $value['pasien']
          ))
          ->execute();
        $data['response_data'][$key]['ns_response'] = $NS;
        $data['response_data'][$key]['ns_detail'] = $NS['response_data'][0];
      } else {
        $PoliInfo = $Poli->get_poli_info($value['departemen']);
        $data['response_data'][$key]['departemen'] = (count($PoliInfo['response_data']) > 0) ? $PoliInfo['response_data'][0] : $value['departemen'];
      }

      $data['response_data'][$key]['created_at_parsed'] = date('d F Y H:i:s', strtotime($value['created_at']));
      $data['response_data'][$key]['penjamin'] = $Penjamin->get_penjamin_detail($value['penjamin'])['response_data'][0];
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    $itemTotal = self::$query->select('resep', array(
      'uid', 'pasien', 'antrian'
    ))
      ->join('pasien', array(
        'nama as nama_pasien',
        'no_rm'
      ))
      ->join('antrian', array(
        'departemen'
      ))
      ->on(array(
        array('resep.pasien', '=', 'pasien.uid'),
        array('resep.antrian', '=', 'antrian.uid')
      ))
      ->where($paramData, $paramValue)
      ->execute();

    // $data['recordsTotal'] = ($parameter['request_type'] === 'verifikasi') ? (count($itemTotal['response_data']) + count($dataIGD['response_data'])) : count($itemTotal['response_data']);
    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($data['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);

    return $data;
  }

  private function get_resep_backend_v2($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (isset($parameter['request_type'])) {
      if ($parameter['request_type'] === 'inap') {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '((resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'OR',
            '(resep.status_resep' => '= ?))',
            'AND',
            '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
          );

          $paramValue = array('K', 'L', 'D');
        } else {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '((resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'OR',
            '(resep.status_resep' => '= ?))'
          );

          $paramValue = array('K', 'K', 'K');
        }
      } else {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '((resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'OR',
            '(resep.status_resep' => '= ?))',
            'AND',
            '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
          );

          $paramValue = array('D', 'P', 'S');
        } else {
          $paramData = array(
            'resep.deleted_at' => 'IS NULL',
            'AND',
            '((resep.status_resep' => '= ?',
            'OR',
            'resep.status_resep' => '= ?)',
            'OR',
            '(resep.status_resep' => '= ?))'
          );

          $paramValue = array('D', 'P', 'S');
        }
      }
    } else {
      if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
        $paramData = array(
          'resep.deleted_at' => 'IS NULL',
          'AND',
          'resep.status_resep' => '= ?',
          'AND',
          '(pasien.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
          'OR',
          'pasien.no_rm' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
        );

        $paramValue = array((isset($parameter['status']) ? $parameter['status'] : 'N'));
      } else {
        $paramData = array(
          'resep.deleted_at' => 'IS NULL',
          'AND',
          'resep.status_resep' => '= ?'
        );

        $paramValue = array((isset($parameter['status']) ? $parameter['status'] : 'N'));
      }
    }


    if (isset($parameter['request_type'])) {
      if ($parameter['length'] < 0) {
        $data = self::$query->select('resep', array(
          'uid',
          'kunjungan',
          'antrian',
          'asesmen',
          'dokter',
          'pasien',
          'total',
          'status_resep',
          'created_at',
          'updated_at'
        ))
          ->join('pasien', array(
            'nama as nama_pasien',
            'no_rm'
          ))
          ->on(array(
            array('resep.pasien', '=', 'pasien.uid')
          ))
          ->order(array(
            'resep.created_at' => 'ASC'
          ))
          ->where($paramData, $paramValue)
          ->execute();
      } else {
        $data = self::$query->select('resep', array(
          'uid',
          'kunjungan',
          'antrian',
          'asesmen',
          'dokter',
          'pasien',
          'total',
          'status_resep',
          'created_at',
          'updated_at'
        ))
          ->join('pasien', array(
            'nama as nama_pasien',
            'no_rm'
          ))
          ->on(array(
            array('resep.pasien', '=', 'pasien.uid')
          ))
          ->order(array(
            'resep.created_at' => 'ASC'
          ))
          ->where($paramData, $paramValue)
          /*->offset(intval($parameter['start']))
                    ->limit(intval($parameter['length']))*/
          ->execute();
      }
    } else {
      if ($parameter['length'] < 0) {
        $data = self::$query->select('resep', array(
          'uid',
          'kunjungan',
          'antrian',
          'asesmen',
          'dokter',
          'pasien',
          'total',
          'status_resep',
          'created_at',
          'updated_at'
        ))
          ->join('pasien', array(
            'nama as nama_pasien',
            'no_rm'
          ))
          ->on(array(
            array('resep.pasien', '=', 'pasien.uid')
          ))
          ->order(array(
            'resep.created_at' => 'ASC'
          ))
          ->where($paramData, $paramValue)
          ->execute();
      } else {
        $data = self::$query->select('resep', array(
          'uid',
          'kunjungan',
          'antrian',
          'asesmen',
          'dokter',
          'pasien',
          'total',
          'status_resep',
          'created_at',
          'updated_at'
        ))
          ->join('pasien', array(
            'nama as nama_pasien',
            'no_rm'
          ))
          ->on(array(
            array('resep.pasien', '=', 'pasien.uid')
          ))
          ->order(array(
            'resep.created_at' => 'ASC'
          ))
          ->where($paramData, $paramValue)
          /*->offset(intval($parameter['start']))
                    ->limit(intval($parameter['length']))*/
          ->execute();
      }
    }






    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;
    $Pegawai = new Pegawai(self::$pdo);
    $Pasien = new Pasien(self::$pdo);
    $Antrian = new Antrian(self::$pdo);
    $Poli = new Poli(self::$pdo);
    $Inventori = new Inventori(self::$pdo);

    $dataIGD = array();
    $dataBiasa = array();
    if (isset($parameter['request_type'])) {
      $dataIGDRaw = self::$query->select('resep', array(
        'uid',
        'kunjungan',
        'antrian',
        'asesmen',
        'dokter',
        'pasien',
        'total',
        'status_resep',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'resep.deleted_at' => 'IS NULL',
          'AND',
          '((resep.status_resep' => '= ?',
          'OR',
          'resep.status_resep' => '= ?)',
          'OR',
          '(resep.status_resep' => '= ?))'
        ), array('D', 'P', 'S'))
        ->execute();
    } else {
      $dataIGDRaw = self::$query->select('resep', array(
        'uid',
        'kunjungan',
        'antrian',
        'asesmen',
        'dokter',
        'pasien',
        'total',
        'status_resep',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'resep.deleted_at' => 'IS NULL',
          'AND',
          'resep.status_resep' => '= ?'
        ), array(
          (isset($parameter['status']) ? $parameter['status'] : 'N')
        ))
        ->execute();
    }

    foreach ($dataIGDRaw['response_data'] as $key => $value) {
      $PegawaiInfo = $Pegawai->get_detail($value['dokter']);
      $dataIGDRaw['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

      $PasienData = $Pasien->get_pasien_detail('pasien', $value['pasien']);
      $dataIGDRaw['response_data'][$key]['pasien_info'] = $PasienData['response_data'][0];


      $AntrianInfo = self::$query->select('antrian', array(
        'uid',
        'pasien',
        'kunjungan',
        'departemen',
        'penjamin',
        'dokter',
        'waktu_masuk',
        'waktu_keluar',
        'prioritas'
      ))
        ->join('master_penjamin', array(
          'nama as nama_penjamin'
        ))
        ->on(array(
          array('antrian.penjamin', '=', 'master_penjamin.uid')
        ))
        ->where(array(
          'antrian.uid' => '= ?'
        ), array(
          $value['antrian']
        ))
        ->execute();


      $PoliInfo = $Poli->get_poli_info($AntrianInfo['response_data'][0]['departemen']);
      $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];

      $dataIGDRaw['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];
      $dataIGDRaw['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
      $dataIGDRaw['response_data'][$key]['autonum'] = $autonum;
      $autonum++;


      if ($AntrianInfo['response_data'][0]['departemen']['uid'] === __POLI_IGD__) {
        array_push($dataIGD, $dataIGDRaw['response_data'][$key]);
      }
    }

    foreach ($data['response_data'] as $key => $value) {
      //Dokter Info
      $PegawaiInfo = $Pegawai->get_detail($value['dokter']);
      $data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

      //Get Antrian Detail
      //Todo : Disini yang buat lama
      //$AntrianInfo = $Antrian->get_antrian_detail('antrian', $value['antrian']);
      $AntrianInfo = self::$query->select('antrian', array(
        'uid',
        'pasien',
        'kunjungan',
        'departemen',
        'penjamin',
        'dokter',
        'waktu_masuk',
        'waktu_keluar',
        'prioritas'
      ))
        ->join('master_penjamin', array(
          'nama as nama_penjamin'
        ))
        ->on(array(
          array('antrian.penjamin', '=', 'master_penjamin.uid')
        ))
        ->where(array(
          'antrian.uid' => '= ?'
        ), array(
          $value['antrian']
        ))
        ->execute();

      $PasienData = $Pasien->get_pasien_detail('pasien', $value['pasien']);
      $data['response_data'][$key]['pasien_info'] = $PasienData['response_data'][0];

      //Departemen Info
      if ($AntrianInfo['response_data'][0]['departemen'] === __POLI_INAP__) {
        $AntrianInfo['response_data'][0]['departemen'] = array(
          'uid' => __POLI_INAP__,
          'nama' => 'Rawat Inap'
        );

        //NS Info
        $NS = self::$query->select('rawat_inap', array(
          'nurse_station'
        ))
          ->join('nurse_station', array(
            'kode as kode_ns', 'nama as nama_ns'
          ))
          ->on(array(
            array('rawat_inap.nurse_station', '=', 'nurse_station.uid')
          ))
          ->where(array(
            'rawat_inap.kunjungan' => '= ?',
            'AND',
            'rawat_inap.dokter' => '= ?',
            'AND',
            'rawat_inap.pasien' => '= ?'
          ), array(
            $AntrianInfo['response_data'][0]['kunjungan'],
            $value['dokter'],
            $value['pasien']
          ))
          ->execute();
        $AntrianInfo['response_data'][0]['ns_detail'] = $NS['response_data'][0];
      } else {
        $PoliInfo = $Poli->get_poli_info($AntrianInfo['response_data'][0]['departemen']);
        $AntrianInfo['response_data'][0]['departemen'] = (count($PoliInfo['response_data']) > 0) ? $PoliInfo['response_data'][0] : $AntrianInfo['response_data'][0]['departemen'];
      }

      $data['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];
      $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;

      if ($AntrianInfo['response_data'][0]['departemen']['uid'] === __POLI_IGD__) {
      } else {
        array_push($dataBiasa, $data['response_data'][$key]);
      }
    }

    $itemTotal = self::$query->select('resep', array(
      'uid'
    ))
      ->where($paramData, $paramValue)
      ->execute();
    // $dataResult = array_merge($dataIGD, $dataBiasa);
    /*if (intval($parameter['length']) < 0) {
            $data['response_data'] = $dataResult;
        } else {
            $data['response_data'] = array_splice($dataResult, intval($parameter['start']), intval($parameter['length']));
        }*/

    // $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsTotal'] = count($data);
    $data['recordsFiltered'] = count($data);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);

    return $data;
  }


  private function get_resep_backend($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (isset($parameter['request_type'])) {
      if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
        $paramData = array(
          'resep.deleted_at' => 'IS NULL',
          'AND',
          '((resep.status_resep' => '= ?',
          'OR',
          'resep.status_resep' => '= ?)',
          'OR',
          '(resep.status_resep' => '= ?))'
        );

        $paramValue = array('V', 'K', 'D');
      } else {
        $paramData = array(
          'resep.deleted_at' => 'IS NULL',
          'AND',
          '((resep.status_resep' => '= ?',
          'OR',
          'resep.status_resep' => '= ?)',
          'OR',
          '(resep.status_resep' => '= ?))'
        );

        $paramValue = array('V', 'K', 'D');
      }
    } else {
      if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
        $paramData = array(
          'resep.deleted_at' => 'IS NULL',
          'AND',
          'resep.status_resep' => '= ?'
        );

        $paramValue = array((isset($parameter['status']) ? $parameter['status'] : 'N'));
      } else {
        $paramData = array(
          'resep.deleted_at' => 'IS NULL',
          'AND',
          'resep.status_resep' => '= ?'
          //'resep.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
        );

        $paramValue = array((isset($parameter['status']) ? $parameter['status'] : 'N'));
      }
    }


    if (isset($parameter['request_type'])) {
      if ($parameter['length'] < 0) {
        $data = self::$query->select('resep', array(
          'uid',
          'kunjungan',
          'antrian',
          'asesmen',
          'dokter',
          'pasien',
          'total',
          'status_resep',
          'created_at',
          'updated_at'
        ))
          ->where($paramData, $paramValue)
          ->execute();
      } else {
        $data = self::$query->select('resep', array(
          'uid',
          'kunjungan',
          'antrian',
          'asesmen',
          'dokter',
          'pasien',
          'total',
          'status_resep',
          'created_at',
          'updated_at'
        ))
          ->where($paramData, $paramValue)
          ->offset(intval($parameter['start']))
          ->limit(intval($parameter['length']))
          ->execute();
      }
    } else {
      if ($parameter['length'] < 0) {
        $data = self::$query->select('resep', array(
          'uid',
          'kunjungan',
          'antrian',
          'asesmen',
          'dokter',
          'pasien',
          'total',
          'status_resep',
          'created_at',
          'updated_at'
        ))
          ->where($paramData, $paramValue)
          ->execute();
      } else {
        $data = self::$query->select('resep', array(
          'uid',
          'kunjungan',
          'antrian',
          'asesmen',
          'dokter',
          'pasien',
          'total',
          'status_resep',
          'created_at',
          'updated_at'
        ))
          ->where($paramData, $paramValue)
          ->offset(intval($parameter['start']))
          ->limit(intval($parameter['length']))
          ->execute();
      }
    }




    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;
    $Pegawai = new Pegawai(self::$pdo);
    $Antrian = new Antrian(self::$pdo);
    $Poli = new Poli(self::$pdo);
    $Inventori = new Inventori(self::$pdo);
    foreach ($data['response_data'] as $key => $value) {
      //Dokter Info
      $PegawaiInfo = $Pegawai->get_detail($value['dokter']);
      $data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

      //Get Antrian Detail
      $AntrianInfo = $Antrian->get_antrian_detail('antrian', $value['antrian']);

      //Departemen Info
      if ($AntrianInfo['response_data'][0]['departemen'] === __POLI_INAP__) {
        $AntrianInfo['response_data'][0]['departemen'] = array(
          'uid' => __POLI_INAP__,
          'nama' => 'Rawat Inap'
        );

        //NS Info
        $NS = self::$query->select('rawat_inap', array(
          'nurse_station'
        ))
          ->join('nurse_station', array(
            'kode as kode_ns', 'nama as nama_ns'
          ))
          ->on(array(
            array('rawat_inap.nurse_station', '=', 'nurse_station.uid')
          ))
          ->where(array(
            'rawat_inap.kunjungan' => '= ?',
            'AND',
            'rawat_inap.dokter' => '= ?',
            'AND',
            'rawat_inap.pasien' => '= ?'
          ), array(
            $AntrianInfo['response_data'][0]['kunjungan'],
            $value['dokter'],
            $value['pasien']
          ))
          ->execute();
        $AntrianInfo['response_data'][0]['ns_detail'] = $NS['response_data'][0];
      } else {
        $PoliInfo = $Poli->get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
        $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];
      }

      $data['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

      //Get resep detail
      $resep_detail = self::$query->select('resep_detail', array(
        'id',
        'resep',
        'obat',
        'harga',
        'signa_qty',
        'signa_pakai',
        'qty',
        'satuan',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'resep_detail.resep' => '= ?',
          'AND',
          'resep_detail.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($resep_detail['response_data'] as $ResKey => $ResValue) {
        //Batch Info
        $InventoriBatch = $Inventori->get_item_batch($ResValue['obat']);
        $resep_detail['response_data'][$ResKey]['batch'] = $InventoriBatch['response_data'];

        $InventoriInfo = $Inventori->get_item_detail($ResValue['obat']);
        $resep_detail['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];
      }
      $data['response_data'][$key]['detail'] = $resep_detail['response_data'];


      //Racikan Item
      $racikan = self::$query->select('racikan', array(
        'uid',
        'asesmen',
        //'resep',
        'kode',
        'total',
        'keterangan',
        'signa_qty',
        'signa_pakai',
        'qty',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'racikan.asesmen' => '= ?',
          'AND',
          'racikan.status' => '= ?',
          'AND',
          'racikan.deleted_at' => 'IS NULL'
        ), array(
          $value['asesmen'],
          'N'
        ))
        ->execute();
      foreach ($racikan['response_data'] as $RDKey => $RDValue) {
        $racikan_detail = self::$query->select('racikan_detail', array(
          'id',
          'asesmen',
          //'resep',
          'obat',
          'ratio',
          'pembulatan',
          'harga',
          'racikan',
          'takar_bulat',
          'takar_decimal',
          'penjamin',
          'created_at',
          'updated_at'
        ))
          ->where(array(
            'racikan_detail.deleted_at' => 'IS NULL',
            'AND',
            'racikan_detail.racikan' => '= ?'
          ), array(
            $RDValue['uid']
          ))
          ->execute();
        foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
          $InventoriInfo = $Inventori->get_item_detail($RDIValue['obat']);

          $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];
        }
        $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
      }
      $data['response_data'][$key]['racikan'] = $racikan['response_data'];
      $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    $itemTotal = self::$query->select('resep', array(
      'uid'
    ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($data['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);

    return $data;
  }

  private function get_resep($status = 'N')
  {
    $data = self::$query->select('resep', array(
      'uid',
      'kunjungan',
      'antrian',
      'asesmen',
      'dokter',
      'pasien',
      'status_resep',
      'total',
      'created_at',
      'updated_at'
    ))
      ->where(array(
        'resep.deleted_at' => 'IS NULL',
        'AND',
        'resep.status_resep' => '= ?'
      ), array(
        $status
      ))
      ->execute();
    $autonum = 1;
    foreach ($data['response_data'] as $key => $value) {
      //Dokter Info
      $Pegawai = new Pegawai(self::$pdo);
      $PegawaiInfo = $Pegawai->get_detail($value['dokter']);
      $data['response_data'][$key]['dokter'] = $PegawaiInfo['response_data'][0];

      //Get Antrian Detail
      $Antrian = new Antrian(self::$pdo);
      $AntrianInfo = $Antrian->get_antrian_detail('antrian', $value['antrian']);
      if (!isset($AntrianInfo['response_data'][0]['departemen'])) {
      }

      //Departemen Info
      $Poli = new Poli(self::$pdo);
      $PoliInfo = $Poli->get_poli_detail($AntrianInfo['response_data'][0]['departemen']);
      $AntrianInfo['response_data'][0]['departemen'] = $PoliInfo['response_data'][0];
      $data['response_data'][$key]['antrian'] = $AntrianInfo['response_data'][0];

      //Get resep detail
      $resep_detail = self::$query->select('resep_detail', array(
        'id',
        'resep',
        'obat',
        'harga',
        'signa_qty',
        'signa_pakai',
        'qty',
        'satuan',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'resep_detail.resep' => '= ?',
          'AND',
          'resep_detail.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($resep_detail['response_data'] as $ResKey => $ResValue) {
        $Inventori = new Inventori(self::$pdo);
        $InventoriInfo = $Inventori->get_item_detail($ResValue['obat']);
        $resep_detail['response_data'][$ResKey]['detail'] = $InventoriInfo['response_data'][0];
      }
      $data['response_data'][$key]['detail'] = $resep_detail['response_data'];


      //Racikan Item
      $racikan = self::$query->select('racikan', array(
        'uid',
        'asesmen',
        //'resep',
        'kode',
        'total',
        'keterangan',
        'signa_qty',
        'signa_pakai',
        'qty',
        'created_at',
        'updated_at'
      ))
        ->where(array(
          'racikan.asesmen' => '= ?',
          'AND',
          'racikan.status' => '= ?',
          'AND',
          'racikan.deleted_at' => 'IS NULL'
        ), array(
          $value['asesmen'],
          'N'
        ))
        ->execute();
      foreach ($racikan['response_data'] as $RDKey => $RDValue) {
        $racikan_detail = self::$query->select('racikan_detail', array(
          'id',
          'asesmen',
          //'resep',
          'obat',
          'ratio',
          'pembulatan',
          'harga',
          'racikan',
          'takar_bulat',
          'takar_decimal',
          'penjamin',
          'created_at',
          'updated_at'
        ))
          ->where(array(
            'racikan_detail.deleted_at' => 'IS NULL',
            /*'AND',
                        'racikan_detail.resep' => '= ?',*/
            'AND',
            'racikan_detail.racikan' => '= ?'
          ), array(
            //$value['uid'],
            $RDValue['uid']
          ))
          ->execute();
        foreach ($racikan_detail['response_data'] as $RDIKey => $RDIValue) {
          $Inventori = new Inventori(self::$pdo);
          $InventoriInfo = $Inventori->get_item_detail($RDIValue['obat']);

          $racikan_detail['response_data'][$RDIKey]['detail'] = $InventoriInfo['response_data'][0];
        }
        $racikan['response_data'][$RDKey]['detail'] = $racikan_detail['response_data'];
      }
      $data['response_data'][$key]['racikan'] = $racikan['response_data'];
      $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    return $data;
  }

  private function verifikasi_resep_2($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $resepChangedRecord = array();
    $racikanChangedRecord = array();
    $invoice_detail = array();

    $Invoice = new Invoice(self::$pdo);
    $Inventori = new Inventori(self::$pdo);
    $virtual = array();

    $InvoiceCheck = self::$query->select('invoice', array(
      'uid'
    ))
      ->where(array(
        'invoice.kunjungan' => '= ?',
        'AND',
        'invoice.deleted_at' => 'IS NULL'
      ), array(
        $parameter['kunjungan']
      ))
      ->order(array(
        'created_at' => 'DESC'
      ))
      ->execute();

    if (count($InvoiceCheck['response_data']) > 0) {
      $TargetInvoice = $InvoiceCheck['response_data'][0]['uid'];
    } else {
      $InvMasterParam['keterangan'] = '';
      $NewInvoice = $Invoice->create_invoice($InvMasterParam);
      $TargetInvoice = $NewInvoice['response_unique'];
    }

    //Resep Detail
    $ResepDetail = self::$query->select('resep', array(
      'uid', 'kode', 'asesmen'
    ))
      ->where(array(
        'resep.uid' => '= ?',
        'AND',
        'resep.deleted_at' => 'IS NULL'
      ), array(
        $parameter['uid']
      ))
      ->execute();

    foreach ($parameter['resep'] as $key => $value) {
      $alasan_resep = "";
      foreach ($parameter['alasan_resep'] as $AResKey => $AResValue) {
        if ($AResValue['obat'] === $value['obat']) {
          $alasan_resep = $AResValue['text'];
        }
      }

      //TODO : Simpan penggunaan batch. Dulu bentuk begini
      // $resepChange = self::$query->insert('resep_change_log', array(
      //     'resep' => $parameter['uid'],
      //     'verifikator' => $UserData['data']->uid,
      //     'item' => $value['obat'],
      //     'qty' => floatval($value['jumlah']),
      //     'aturan_pakai' => intval($value['aturan_pakai']),
      //     'signa_qty' => $value['signa_qty'],
      //     'signa_pakai' => $value['signa_pakai'],
      //     'keterangan' => (isset($value['keterangan'])) ? $value['keterangan'] : '',
      //     'alasan_ubah' => $value['alasan_ubah'],
      //     'created_at' => parent::format_date(),
      //     'updated_at' => parent::format_date(),
      // ))
      //     ->execute();
      // array_push($resepChangedRecord, $resepChange);

      //Check pengecasan biaya untuk resep dengan nomor yang digenerate
      $CheckInvoiceCharged = self::$query->select('invoice_detail', array(
        'id'
      ))
        ->where(
          array(
            'invoice_detail.invoice' => '= ?',
            'AND',
            'invoice_detail.item' => '= ?',
            'AND',
            'invoice_detail.item_type' => '= ?',
            'AND',
            'invoice_detail.pasien' => '= ?',
            'AND',
            'invoice_detail.penjamin' => '= ?',
            'AND',
            'invoice_detail.document' => '= ?'
          ),
          array(
            $TargetInvoice,
            $value['obat'],
            'master_inv',
            $parameter['pasien'],
            $parameter['penjamin'],
            'RESEP' . $ResepDetail['response_data'][0]['kode']
          )
        )
        ->execute();
      if (count($CheckInvoiceCharged['response_data']) === 0) {
        $AppendInvoice = $Invoice->append_invoice(array(
          'invoice' => $TargetInvoice,
          'item' => $value['obat'],
          'item_origin' => 'master_inv',
          'qty' => floatval($value['jumlah']),
          'harga' => floatval($value['harga']),
          'status_bayar' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'N' : 'Y',
          'subtotal' => floatval($value['harga']) * floatval($value['jumlah']),
          'discount' => 0,
          'discount_type' => 'N',
          'pasien' => $parameter['pasien'],
          'penjamin' => $parameter['penjamin'],
          'billing_group' => 'obat',
          'keterangan' => 'Biaya resep obat',
          'departemen' => $parameter['departemen'],
          'document' => 'RESEP' . $ResepDetail['response_data'][0]['kode']
        ));

        // if($resepChange['response_result'] > 0) {
        //     //Update Charged Harga
        //     $updateHargaResepDetail = self::$query->update('resep_detail', array(
        //         'harga' => floatval($value['harga'])
        //     ))
        //         ->where(array(
        //             'resep_detail.deleted_at' => 'IS NULL',
        //             'AND',
        //             'resep_detail.resep' => '= ?',
        //             'AND',
        //             'resep_detail.obat' => '= ?'
        //         ), array(
        //             $ResepDetail['response_data'][0]['uid'],
        //             $value['obat']
        //         ))
        //         ->execute();
        // }
        //Update Charged Harga
        $updateHargaResepDetail = self::$query->update('resep_detail', array(
          'harga' => floatval($value['harga'])
        ))
          ->where(array(
            'resep_detail.deleted_at' => 'IS NULL',
            'AND',
            'resep_detail.resep' => '= ?',
            'AND',
            'resep_detail.obat' => '= ?'
          ), array(
            $ResepDetail['response_data'][0]['uid'],
            $value['obat']
          ))
          ->execute();

        array_push($invoice_detail, $AppendInvoice);
      }

      $usedBatch = array();
      $InventoriBatch = $Inventori->get_item_batch($value['obat'], __GUDANG_APOTEK__);
      $AlternateBatch = $Inventori->get_item_batch($value['obat'], __GUDANG_UTAMA__);
      $kebutuhan = floatval($value['jumlah']);
      foreach ($InventoriBatch['response_data'] as $bKey => $bValue) {
        if ($bValue['gudang']['uid'] === __GUDANG_APOTEK__) {
          if ($kebutuhan >= $bValue['stok_terkini']) {
            if ($bValue['stok_terkini'] > 0) {
              array_push($usedBatch, array(
                'batch' => $bValue['batch'],
                'barang' => $bValue['barang'],
                'gudang' => $bValue['gudang']['uid'],
                'qty' => $bValue['stok_terkini']
              ));
              $kebutuhan -= $bValue['stok_terkini'];
            }
          } else {
            if ($kebutuhan > 0) {
              array_push($usedBatch, array(
                'batch' => $bValue['batch'],
                'barang' => $bValue['barang'],
                'gudang' => $bValue['gudang']['uid'],
                'qty' => $kebutuhan
              ));
              $kebutuhan = 0;
            }
          }
          /*if($bValue['gudang']['uid'] === $UserData['data']->gudang) {
    
                    }*/
        }
      }

      if ($kebutuhan > 0) {
        foreach ($AlternateBatch['response_data'] as $bKey => $bValue) {
          if ($bValue['gudang']['uid'] === __GUDANG_UTAMA__) {
            if ($kebutuhan >= $bValue['stok_terkini']) {
              if ($bValue['stok_terkini'] > 0) {
                array_push($usedBatch, array(
                  'batch' => $bValue['batch'],
                  'barang' => $bValue['barang'],
                  'gudang' => $bValue['gudang']['uid'],
                  'qty' => $bValue['stok_terkini']
                ));
                $kebutuhan -= $bValue['stok_terkini'];
              }
            } else {
              if ($kebutuhan > 0) {
                array_push($usedBatch, array(
                  'batch' => $bValue['batch'],
                  'barang' => $bValue['barang'],
                  'gudang' => $bValue['gudang']['uid'],
                  'qty' => $kebutuhan
                ));
                $kebutuhan = 0;
              }
            }
            /*if($bValue['gudang']['uid'] === $UserData['data']->gudang) {
        
                        }*/
          }
        }
      }

      foreach ($usedBatch as $BatchKey => $BatchValue) {

        $resepChange = self::$query->insert('resep_change_log', array(
          'resep' => $parameter['uid'],
          'verifikator' => $UserData['data']->uid,
          'item' => $value['obat'],
          'batch' => $BatchValue['batch'],
          'qty' => floatval($BatchValue['qty']),
          'aturan_pakai' => intval($value['aturan_pakai']),
          'signa_qty' => $value['signa_qty'],
          'signa_pakai' => $value['signa_pakai'],
          'keterangan' => (isset($value['keterangan'])) ? $value['keterangan'] : '',
          'alasan_ubah' => $value['alasan_ubah'],
          'created_at' => parent::format_date(),
          'updated_at' => parent::format_date(),
        ))
          ->execute();
        array_push($resepChangedRecord, $resepChange);

        if ($resepChange['response_result'] > 0) {
          //Potong Stok Virtual
          if ($UserData['data']->gudang === $BatchValue['gudang']) {
            $Virtual = $Inventori->virtual_stok(array(
              'transact_table' => 'resep',
              'transact_iden' => $parameter['uid'],
              'gudang_asal' => $UserData['data']->gudang,
              'barang' => $BatchValue['barang'],
              'batch' => $BatchValue['batch'],
              'qty' => $BatchValue['qty'],
              'remark' => 'Reserved stok resep'
            ));
          } else {
            $Virtual = $Inventori->virtual_stok(array(
              'transact_table' => 'resep',
              'transact_iden' => $parameter['uid'],
              'gudang_asal' => $BatchValue['gudang'],
              'gudang_tujuan' => $UserData['data']->gudang,
              'barang' => $BatchValue['barang'],
              'batch' => $BatchValue['batch'],
              'qty' => $BatchValue['qty'],
              'remark' => 'Reserved stok resep'
            ));
          }
          array_push($virtual, $Virtual);
        }
      }
    }

    foreach ($parameter['racikan'] as $key => $value) {
      $alasan_racikan = "";
      foreach ($parameter['alasan_racikan'] as $ARacKey => $ARacValue) {
        if ($ARacValue['racikan'] === $value['racikan_uid']) {
          $alasan_racikan = $ARacValue['text'];
        }
      }

      $racikanChange = self::$query->insert('racikan_change_log', array(
        'racikan' => $value['racikan_uid'],
        'jumlah' => floatval($value['jumlah']),
        'signa_qty' => $value['signa_qty'],
        'signa_pakai' => $value['signa_pakai'],
        'keterangan' => (isset($value['keterangan'])) ? $value['keterangan'] : '',
        'aturan_pakai' => floatval($value['aturan_pakai']),
        'alasan_ubah' => (isset($alasan_racikan)) ? $alasan_racikan : '',
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();

      foreach ($value['racikan_komposisi'] as $KKey => $KValue) {


        //Check pengecasan biaya untuk resep dengan nomor yang digenerate
        $CheckInvoiceCharged = self::$query->select('invoice_detail', array(
          'id'
        ))
          ->where(
            array(
              'invoice_detail.invoice' => '= ?',
              'AND',
              'invoice_detail.item' => '= ?',
              'AND',
              'invoice_detail.item_type' => '= ?',
              'AND',
              'invoice_detail.pasien' => '= ?',
              'AND',
              'invoice_detail.penjamin' => '= ?',
              'AND',
              'invoice_detail.document' => '= ?'
            ),
            array(
              $TargetInvoice,
              $KValue['obat'],
              'master_inv',
              $parameter['pasien'],
              $parameter['penjamin'],
              'RACIKAN' . $ResepDetail['response_data'][0]['kode']
            )
          )
          ->execute();
        if (count($CheckInvoiceCharged['response_data']) === 0) {
          $AppendInvoice = $Invoice->append_invoice(array(
            'invoice' => $TargetInvoice,
            'item' => $KValue['obat'],
            'item_origin' => 'master_inv',
            //'qty' => floatval($value['jumlah']),
            'qty' => floatval($KValue['jumlah']),
            'harga' => floatval($KValue['harga']),
            'status_bayar' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'N' : 'Y',
            'subtotal' => floatval($KValue['harga']) * floatval($KValue['jumlah']),
            'discount' => 0,
            'discount_type' => 'N',
            'pasien' => $parameter['pasien'],
            'billing_group' => 'obat',
            'penjamin' => $parameter['penjamin'],
            'keterangan' => 'Biaya racikan obat',
            'departemen' => $parameter['departemen'],
            'document' => 'RACIKAN' . $ResepDetail['response_data'][0]['kode']
          ));
          array_push($invoice_detail, $AppendInvoice);

          // if($racikanDetailChange['response_resul'] > 0) {
          //     $updateHargaRacikanDetail = self::$query->update('racikan_detail', array(
          //         'harga' => floatval($value['harga'])
          //     ))
          //         ->where(array(
          //             'racikan_detail.deleted_at' => 'IS NULL',
          //             'AND',
          //             'racikan_detail.asesmen' => '= ?',
          //             'AND',
          //             'racikan_detail.obat' => '= ?'
          //         ), array(
          //             $ResepDetail['response_data'][0]['asesmen'],
          //             $value['obat']
          //         ))
          //         ->execute();
          // }
          $updateHargaRacikanDetail = self::$query->update('racikan_detail', array(
            'harga' => floatval($value['harga'])
          ))
            ->where(array(
              'racikan_detail.deleted_at' => 'IS NULL',
              'AND',
              'racikan_detail.asesmen' => '= ?',
              'AND',
              'racikan_detail.obat' => '= ?'
            ), array(
              $ResepDetail['response_data'][0]['asesmen'],
              $value['obat']
            ))
            ->execute();
        }

        $usedBatch = array();
        $InventoriBatch = $Inventori->get_item_batch($KValue['obat'], __GUDANG_APOTEK__);
        $AlternatedBatch = $Inventori->get_item_batch($KValue['obat'], __GUDANG_UTAMA__);
        $kebutuhanRacikan = floatval($KValue['jumlah']);
        foreach ($InventoriBatch['response_data'] as $bKey => $bValue) {
          if ($bValue['gudang']['uid'] === __GUDANG_APOTEK__) {
            if ($kebutuhanRacikan >= $bValue['stok_terkini']) {
              if ($bValue['stok_terkini'] > 0) {
                array_push($usedBatch, array(
                  'batch' => $bValue['batch'],
                  'barang' => $bValue['barang'],
                  'gudang' => $bValue['gudang']['uid'],
                  'qty' => $bValue['stok_terkini']
                ));
                $kebutuhanRacikan -= $bValue['stok_terkini'];
              }
            } else {
              if ($kebutuhanRacikan > 0) {
                array_push($usedBatch, array(
                  'batch' => $bValue['batch'],
                  'barang' => $bValue['barang'],
                  'gudang' => $bValue['gudang']['uid'],
                  'qty' => $kebutuhanRacikan
                ));
                $kebutuhanRacikan = 0;
              }
            }
          }
        }

        if ($kebutuhanRacikan > 0) {
          foreach ($AlternatedBatch['response_data'] as $bKey => $bValue) {
            if ($bValue['gudang']['uid'] === __GUDANG_UTAMA__) {
              if ($kebutuhanRacikan >= $bValue['stok_terkini']) {
                if ($bValue['stok_terkini'] > 0) {
                  array_push($usedBatch, array(
                    'batch' => $bValue['batch'],
                    'barang' => $bValue['barang'],
                    'gudang' => $bValue['gudang']['uid'],
                    'qty' => $bValue['stok_terkini']
                  ));
                  $kebutuhanRacikan -= $bValue['stok_terkini'];
                }
              } else {
                if ($kebutuhanRacikan > 0) {
                  array_push($usedBatch, array(
                    'batch' => $bValue['batch'],
                    'barang' => $bValue['barang'],
                    'gudang' => $bValue['gudang']['uid'],
                    'qty' => $kebutuhanRacikan
                  ));
                  $kebutuhanRacikan = 0;
                }
              }
            }
          }
        }

        foreach ($usedBatch as $BatchKey => $BatchValue) {
          $racikanDetailChange = self::$query->insert('racikan_detail_change_log', array(
            'racikan' => $value['racikan_uid'],
            'obat' => $KValue['obat'],
            'jumlah' => floatval($BatchValue['qty']),
            'batch' => $BatchValue['batch'],
            'kekuatan' => $KValue['kekuatan'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
          ))
            ->execute();
          if ($racikanDetailChange['response_result'] > 0) {
            //Potong Stok Virtual
            if ($UserData['data']->gudang === $BatchValue['gudang']) {
              $Virtual = $Inventori->virtual_stok(array(
                'transact_table' => 'racikan',
                'transact_iden' => $value['racikan_uid'],
                'gudang_asal' => $UserData['data']->gudang,
                'barang' => $BatchValue['barang'],
                'batch' => $BatchValue['batch'],
                'qty' => $BatchValue['qty'],
                'remark' => 'Reserved stok racikan'
              ));
            } else {
              $Virtual = $Inventori->virtual_stok(array(
                'transact_table' => 'racikan',
                'transact_iden' => $value['racikan_uid'],
                'gudang_asal' => $BatchValue['gudang'],
                'gudang_tujuan' => $UserData['data']->gudang,
                'barang' => $BatchValue['barang'],
                'batch' => $BatchValue['batch'],
                'qty' => $BatchValue['qty'],
                'remark' => 'Reserved stok racikan'
              ));
            }
            array_push($virtual, $Virtual);
          }
        }
      }

      array_push($racikanChangedRecord, $racikanChange);
    }



    $kajian_data = array();

    $UpdateStatusResep = self::$query->update('resep', array(
      'apoteker' => $UserData['data']->uid,
      'status_resep' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? (($parameter['departemen'] === __POLI_IGD__) ? 'L' : 'K') : 'L',
      'alasan_ubah' => (isset($parameter['alasan_ubah']) && !empty($parameter['alasan_ubah']) && $parameter['alasan_ubah'] !== '') ? $parameter['alasan_ubah'] : ''
    ))
      ->where(array(
        'resep.uid' => '= ?',
        'AND',
        'resep.deleted_at' => 'IS NULL'
      ), array(
        $parameter['uid']
      ))
      ->execute();

    if ($UpdateStatusResep['response_result'] > 0) {
      //Simpan Kajian Resep
      foreach ($parameter['kajian'] as $KajKey => $KajValue) {
        $checkKajian = self::$query->select('resep_kajian', array(
          'id', 'nilai'
        ))
          ->where(array(
            'resep_kajian.parameter_kajian' => '= ?',
            'AND',
            'resep_kajian.resep' => '= ?',
            'AND',
            'resep_kajian.asesmen' => '= ?'
          ), array(
            $KajKey,
            $parameter['uid'],
            $parameter['asesmen']
          ))
          ->execute();
        if (count($checkKajian['response_data']) > 0) {
          $updateKajian = self::$query->update('resep_kajian', array(
            'nilai' => $KajValue,
            'updated_at' => parent::format_date()
          ))
            ->where(array(
              'resep_kajian.parameter_kajian' => '= ?',
              'AND',
              'resep_kajian.resep' => '= ?',
              'AND',
              'resep_kajian.asesmen' => '= ?'
            ), array(
              $KajKey,
              $parameter['uid'],
              $parameter['asesmen']
            ))
            ->execute();

          if ($updateKajian['response_result'] > 0) {
            $log = parent::log(array(
              'type' => 'activity',
              'column' => array(
                'unique_target',
                'user_uid',
                'table_name',
                'action',
                'old_value',
                'new_value',
                'logged_at',
                'status',
                'login_id'
              ),
              'value' => array(
                $checkKajian['response_data'][0]['id'],
                $UserData['data']->uid,
                'resep_kajian',
                'U',
                json_encode(array(
                  'parameter_kajian' => $KajKey,
                  'nilai' => $checkKajian['response_data'][0]['nilai']
                )),
                json_encode(array(
                  'parameter_kajian' => $KajKey,
                  'nilai' => $KajValue
                )),
                parent::format_date(),
                'N',
                $UserData['data']->log_id
              ),
              'class' => __CLASS__
            ));
          }
        } else {
          $updateKajian = self::$query->insert('resep_kajian', array(
            'resep' => $parameter['uid'],
            'asesmen' => $parameter['asesmen'],
            'parameter_kajian' => $KajKey,
            'nilai' => $KajValue,
            'petugas' => $UserData['data']->uid,
            'pasien' => $parameter['pasien'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
          ))
            ->returning('id')
            ->execute();

          if ($updateKajian['response_result'] > 0) {
            $log = parent::log(array(
              'type' => 'activity',
              'column' => array(
                'unique_target',
                'user_uid',
                'table_name',
                'action',
                'logged_at',
                'status',
                'login_id'
              ),
              'value' => array(
                $updateKajian['response_unique'],
                $UserData['data']->uid,
                'resep_kajian',
                'I',
                parent::format_date(),
                'N',
                $UserData['data']->log_id
              ),
              'class' => __CLASS__
            ));
          }
        }

        array_push($kajian_data, $updateKajian);
      }
    }

    //Update Antrian
    $AntrianNomor = self::$query->update('antrian_nomor', array(
      'status' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'A'
    ))
      ->where(array(
        'antrian_nomor.kunjungan' => '= ?',
        'AND',
        'antrian_nomor.pasien' => '= ?',
      ), array(
        $parameter['kunjungan'],
        $parameter['pasien']
      ))
      ->execute();

    return array(
      'resep' =>  $resepChangedRecord,
      'racikan' =>  $racikanChangedRecord,
      'antrian' => $AntrianNomor,
      'invoice' => $TargetInvoice,
      'invoice_detail' => $invoice_detail,
      'kajian' => $kajian_data,
      'virtual' => $virtual,
      'batch' => $usedBatch
    );
  }

  private function verifikasi_resep($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $Invoice = new Invoice(self::$pdo);

    $checkerObatBiasa = array(); //Buat check uid obat lama
    $obatBiasaMeta = array();    //Buat Meta Data Obat Lama

    //Tagihan Resep Biasa
    //Check tagihan terakhir kunjungan
    $InvMasterParam = $parameter;

    $InvoiceCheck = self::$query->select('invoice', array(
      'uid'
    ))
      ->where(array(
        'invoice.kunjungan' => '= ?',
        'AND',
        'invoice.deleted_at' => 'IS NULL'
      ), array(
        $InvMasterParam['kunjungan']
      ))
      ->order(array(
        'created_at' => 'DESC'
      ))
      ->execute();


    if (count($InvoiceCheck['response_data']) > 0) {
      $TargetInvoice = $InvoiceCheck['response_data'][0]['uid'];
    } else {
      $InvMasterParam['keterangan'] = '';
      $NewInvoice = $Invoice->create_invoice($InvMasterParam);
      $TargetInvoice = $NewInvoice['response_unique'];
    }
















    //===============================Proses
    //Reset Resep Detail
    $deleteAllResep = self::$query->update('resep_detail', array(
      'deleted_at' => parent::format_date()
    ))
      ->where(array(
        'resep_detail.resep' => '= ?'
      ), array(
        $parameter['resep']
      ))
      ->execute();


    $old_resep_detail = self::$query->select('resep_detail', array(
      'id',
      'resep',
      'obat',
      'harga',
      'signa_qty',
      'signa_pakai',
      'qty',
      'satuan',
      'status',
      'penjamin'
    ))
      ->where(array(
        'resep_detail.resep' => '= ?'
      ), array(
        $parameter['resep']
      ))
      ->execute();

    //Assign old data
    foreach ($old_resep_detail['response_data'] as $key => $value) {
      if (!in_array($value['obat'], $checkerObatBiasa)) {
        if ($value['obat'] != null) {
          array_push($checkerObatBiasa, $value['obat']);
          $obatBiasaMeta[$value['obat']]['id'] = $value['id'];
          $obatBiasaMeta[$value['obat']]['qty'] = $value['qty'];
          $obatBiasaMeta[$value['obat']]['signa_qty'] = $value['signa_qty'];
          $obatBiasaMeta[$value['obat']]['signa_pakai'] = $value['signa_pakai'];
        }
      }
    }

    /*
         * $obatBiasaMeta, uniqueUID = $checkerObatBiasa
         *
         * key = uid obat
         *
         * {
         *      id, qty, signa_qty, qty_pakai
         * }
         * */

    $obatChanged = array();
    $obatFix = array();
    $updateStatus = '';

    foreach ($parameter['detail'] as $key => $value) {
      //Cek apakah ada perubahan
      if (in_array($value['obat'], $checkerObatBiasa)) {
        //check signa dan jumlah
        if (
          floatval($value['signa_qty']) != floatval($obatBiasaMeta[$value['obat']]['signa_qty']) ||
          floatval($value['signa_pakai']) != floatval($obatBiasaMeta[$value['obat']]['signa_pakai']) ||
          floatval($value['jumlah']) != floatval($obatBiasaMeta[$value['obat']]['qty'])
        ) {
          if (!isset($obatChanged[$value['obat']])) {
            $obatChanged[$value['obat']] = array();
          }
          $obatChanged[$value['obat']]['qty'] = $value['jumlah'];
          $obatChanged[$value['obat']]['signa_qty'] = $value['signa_qty'];
          $obatChanged[$value['obat']]['signa_pakai'] = $value['signa_pakai'];
        } else {
          if (!isset($obatFix[$value['obat']])) {
            $obatFix[$value['obat']] = array();
          }
          $obatFix[$value['obat']]['qty'] = $value['jumlah'];
          $obatFix[$value['obat']]['signa_qty'] = $value['signa_qty'];
          $obatFix[$value['obat']]['signa_pakai'] = $value['signa_pakai'];
        }
        $updateResep = self::update_resep(array(
          'resep' => $parameter['resep'],
          'id' => $obatBiasaMeta[$value['obat']]['id'],
          'obat' => $value['obat'],
          'signa_qty' => $value['signa_qty'],
          'signa_pakai' => $value['signa_pakai'],
          'harga' => $value['harga_after_profit'],
          'qty' => $value['jumlah'],
          'deleted_at' => NULL
        ));
      } else {
        //Tetap catat yang lama
        /*if (!isset($obatFix[$value['obat']])) {
                    $obatFix[$value['obat']] = array();
                }

                $obatFix[$value['obat']]['qty'] = $value['jumlah'];
                $obatFix[$value['obat']]['signa_qty'] = $value['signa_qty'];
                $obatFix[$value['obat']]['signa_pakai'] = $value['signa_pakai'];*/

        //Uda pasti beda (ada tambahan), masukkan ke resep detail
        if (!isset($obatChanged[$value['obat']])) {
          $obatChanged[$value['obat']] = array();
        }

        $obatChanged[$value['obat']]['qty'] = $value['jumlah'];
        $obatChanged[$value['obat']]['signa_qty'] = $value['signa_qty'];
        $obatChanged[$value['obat']]['signa_pakai'] = $value['signa_pakai'];

        //Get Satuan
        $Inventori = new Inventori(self::$pdo);
        $InventoriDetail = $Inventori::get_item_detail($value['obat']);

        $worker = self::$query->insert('resep_detail', array(
          'resep' => $parameter['resep'],
          'obat' => $value['obat'],
          'harga' => floatval($value['harga_after_profit']),
          'signa_qty' => $value['signa_qty'],
          'signa_pakai' => $value['signa_pakai'],
          'qty' => $value['jumlah'],
          'satuan' => $InventoriDetail['response_data'][0]['satuan_terkecil'],
          'created_at' => parent::format_date(),
          'updated_at' => parent::format_date(),
          'aturan_pakai' => 0,
          'status' => 'K'
        ))
          ->execute();
      }


      //Jika ada tambah permintaan revisi pada dokter bersangkutan
      //Tidak mungkin menunggu dokter karena kadang dokter sudah pulang atau sudah tidak ditempat
      //Dapat menunggu proses verifikasi besok karena dokter sudah dikabari melalui telepon
      //Masukkan ke dalam tagihan langsung


      //Auto amprah jika batch dari gudang
      if ($value['batch'] == __GUDANG_APOTEK__) {
        //Update status resep detail menjadi proses
        $update_detail = self::$query->update('resep_detail', array(
          'status' => 'A'
        ))
          ->where(array(
            'resep_detail.id' => '= ?',
            'AND',
            'resep_detail.resep' => '= ?',
            'AND',
            'resep_detail.deleted_at' => 'IS NULL'
          ), array(
            $obatBiasaMeta[$value['obat']]['id'],
            $parameter['resep']
          ))
          ->execute();
      } else {
        //Update status resep detail menjadi proses
        $update_detail = self::$query->update('resep_detail', array(
          'status' => 'K'
        ))
          ->where(array(
            'resep_detail.id' => '= ?',
            'AND',
            'resep_detail.resep' => '= ?',
            'AND',
            'resep_detail.deleted_at' => 'IS NULL'
          ), array(
            $obatBiasaMeta[$value['obat']]['id'],
            $parameter['resep']
          ))
          ->execute();
      }

      //Assign invoice item
      //cek Pelunasan penjamin non umum. Status auto bayar jika non umum
      $invo_detail = $parameter;
      //Cek penjamin utama pasien
      if ($parameter['penjamin'] == __UIDPENJAMINUMUM__) {
        $invo_detail['invoice'] = $TargetInvoice;
        $invo_detail['item'] = $value['obat'];
        $invo_detail['item_origin'] = 'master_inv';
        $invo_detail['qty'] = $value['jumlah'];
        $invo_detail['harga'] = $value['harga_after_profit'];
        $invo_detail['status_bayar'] = 'N';
        $invo_detail['subtotal'] = $value['harga_after_profit'] * $value['jumlah'];
        $invo_detail['discount'] = 0;
        $invo_detail['discount_type'] = 'N';
        $invo_detail['keterangan'] = 'Biaya obat';
      } else {
        $invo_detail['invoice'] = $TargetInvoice;
        $invo_detail['item'] = $value['obat'];
        $invo_detail['item_origin'] = 'master_inv';
        $invo_detail['qty'] = $value['jumlah'];
        $invo_detail['harga'] = $value['harga_after_profit'];
        $invo_detail['status_bayar'] = 'Y';
        $invo_detail['subtotal'] = $value['harga_after_profit'] * $value['jumlah'];
        $invo_detail['billing_group'] = 'obat';
        $invo_detail['discount'] = 0;
        $invo_detail['discount_type'] = 'N';
        $invo_detail['keterangan'] = 'Biaya obat';
      }

      $AppendInvoice = $Invoice->append_invoice($invo_detail);
    } // End Loop Resep Biasa


    //Racikan manager

    $checkerObatRacikan = array(); //Buat check uid obat lama
    $racikanChange = array();
    $oldRacikan = array();

    foreach ($parameter['racikan'] as $key => $value) {
      $old_racikan = self::$query->select('racikan', array(
        'uid',
        'kode',
        'signa_qty',
        'signa_pakai',
        'qty'
      ))
        ->where(array(
          'racikan.uid' => '= ?',
          'AND',
          'racikan.asesmen' => '= ?',
          'AND',
          'racikan.status' => '= ?',
          'AND',
          'racikan.deleted_at' => 'IS NULL'
        ), array(
          $value['group_racikan'],
          $parameter['asesmen'],
          'N'
        ))
        ->execute();
      $old_racikan_data = $old_racikan['response_data'][0];

      $old_racikan_detail = self::$query->select('racikan_detail', array(
        'id',
        'asesmen',
        'obat',
        'pembulatan',
        'harga',
        'takar_bulat'
      ))
        ->where(array(
          'racikan_detail.deleted_at' => 'IS NULL',
          'AND',
          'racikan_detail.racikan' => '= ?',
          'AND',
          'racikan_detail.asesmen' => '= ?',
          'AND',
          'racikan_detail.obat' => '= ?'
        ), array(
          $value['group_racikan'],
          $parameter['asesmen'],
          $value['obat']
        ))
        ->execute();
      if (count($old_racikan_detail['response_data']) > 0) {
        $racikanCheck = $old_racikan_detail['response_data'][0];
        if (
          $value['total'] != $old_racikan_data['qty'] * $racikanCheck['pembulatan']
        ) {
          if (!isset($racikanChange[$value['group_racikan']])) {
            $racikanChange[$value['group_racikan']] = array();
          }

          if (!in_array($value['obat'], $racikanChange[$value['group_racikan']])) {
            array_push($racikanChange[$value['group_racikan']], array(
              'racikan' => $value['group_racikan'],
              'obat' => $value['obat'],
              'batch' => $value['batch'],
              'harga' => $value['harga'],
              'jumlah' => $value['jumlah']
            ));
          }
        } else {
          if (!isset($checkerObatRacikan[$value['group_racikan']])) {
            $checkerObatRacikan[$value['group_racikan']] = array();
          }

          if (!in_array($value['obat'], $checkerObatRacikan[$value['group_racikan']])) {
            array_push($checkerObatRacikan[$value['group_racikan']], array(
              'obat' => $value['obat'],
              'batch' => $value['batch'],
              'harga' => $value['harga'],
              'jumlah' => $value['jumlah']
            ));
          }
        }
      } else {
        if (!isset($racikanChange[$value['group_racikan']])) {
          $racikanChange[$value['group_racikan']] = array();
        }

        if (!in_array($value['obat'], $racikanChange[$value['group_racikan']])) {
          array_push($racikanChange[$value['group_racikan']], array(
            'racikan' => $value['group_racikan'],
            'obat' => $value['obat'],
            'batch' => $value['batch'],
            'harga' => $value['harga'],
            'jumlah' => $value['jumlah']
          ));
        }
      }

      $updateRacikan = self::update_racikan(array(
        'racikan' => $parameter['group_racikan'],
        'asesmen' => $parameter['asesmen'],
        'qty' => $value['jumlah'],
        'obat' => $value['obat'],
        'takar_bulat' => $value['bulat'],
        'takar_decimal' => $value['decimal'],
        'pembulatan' => $value['pembulatan'],
        'ratio' => $value['ratio'],
        'harga' => $value['harga'],
        'signa_qty' => $value['signa_qty'],
        'signa_pakai' => $value['signa_pakai']
      ));

      $old_racikan_data['response_data'][0]['detail'] = $old_racikan_detail['response_data'];

      //Tagihan Racikan
      $parameter['invoice'] = $TargetInvoice;
      $parameter['item'] = $value['obat'];
      $parameter['item_origin'] = 'master_inv';
      $parameter['qty'] = $value['jumlah'];
      $parameter['harga'] = $value['harga'];
      $parameter['status_bayar'] = 'N';
      $parameter['subtotal'] = $value['harga'] * $value['jumlah'];
      $parameter['billing_group'] = 'obat';
      $parameter['discount'] = 0;
      $parameter['discount_type'] = 'N';
      $parameter['keterangan'] = '';

      $AppendInvoice = $Invoice::append_invoice($parameter);
    }



    //TODO: Buka status tagihan untuk BPJS (apotek, lab, radio)

    //Update resep master menjadi kasir
    $Resep = self::$query->update('resep', array(
      'status_resep' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'L',
      'verifikator' => $UserData['data']->uid
    ))
      ->where(array(
        'resep.uid' => '= ?',
        'AND',
        'resep.deleted_at' => 'IS NULL'
      ), array(
        $parameter['resep']
      ))
      ->execute();
    $Racikan = self::$query->update('racikan', array(
      'status' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'L'
    ))
      ->where(array(
        'racikan.asesmen' => '= ?'
      ), array(
        $parameter['asesmen']
      ))
      ->execute();

    $resepChangedLog = array();
    $racikanChangedLog = array();

    //Save perubahan resep biasa
    foreach ($obatChanged as $key => $value) {
      $resepChangeSave = self::$query->insert('resep_change_log', array(
        'resep' => $parameter['resep'],
        'verifikator' => $UserData['data']->uid,
        'dokter' => $parameter['dokter'],
        'status' => 'N',
        'keterangan' => '',
        'item' => $key,
        'qty' => floatval($value['qty']),
        'signa_qty' => floatval($value['signa_qty']),
        'signa_pakai' => floatval($value['signa_pakai']),
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();
      array_push($resepChangedLog, $resepChangeSave);
    }

    foreach ($racikanChange as $key => $value) {
      foreach ($value as $KKey => $KValue) {
        $racikanChangeSave = self::$query->insert('racikan_change_log', array(
          'racikan' => $KValue['racikan'],
          'obat' => $KValue['obat'],
          'qty' => floatval($KValue['jumlah']),
          'status' => 'N',
          'keterangan' => ''
        ))
          ->execute();
        array_push($racikanChangedLog, $racikanChangeSave);
      }
    }

    $Resep['response_change_racikan'] = $racikanChange;
    $Resep['response_changed_resep'] = $obatChanged;
    $Resep['change_resep_result'] = $resepChangedLog;
    $Resep['change_racikan_result'] = $racikanChangedLog;
    $Resep['racikan_update'] = $Racikan;

    //Update status pembayaran pasien

    $AntrianNomor = self::$query->update('antrian_nomor', array(
      'status' => ($parameter['penjamin'] === __UIDPENJAMINUMUM__) ? 'K' : 'D'
    ))
      ->where(array(
        'antrian_nomor.kunjungan' => '= ?',
        'AND',
        'antrian_nomor.pasien' => '= ?',
      ), array(
        $parameter['kunjungan'],
        $parameter['pasien']
      ))
      ->execute();

    return $Resep;
  }

  private function update_resep($parameter)
  {
    $checkerResep = self::$query->select('resep_detail', array(
      'id'
    ))
      ->where(array(
        'resep_detail.id' => '= ?',
        'AND',
        'resep_detail.resep' => '= ?'
      ), array(
        $parameter['id'],
        $parameter['resep']
      ))
      ->execute();
    if (count($checkerResep['response_data']) > 0) {
      $worker = self::$query->update('resep_detail', array(
        'obat' => $parameter['obat'],
        'harga' => $parameter['harga'],
        'signa_qty' => $parameter['signa_qty'],
        'signa_pakai' => $parameter['signa_pakai'],
        'qty' => $parameter['qty'],
        'deleted_at' => NULL
      ))
        ->where(array(
          'resep_detail.id' => '= ?',
          'AND',
          'resep_detail.resep' => '= ?'
        ), array(
          $parameter['id'],
          $parameter['resep']
        ))
        ->execute();
    } else {
      //Get Satuan
      $Inventori = new Inventori(self::$pdo);
      $InventoriDetail = $Inventori::get_item_detail($parameter['obat']);

      $worker = self::$query->insert('resep_detail', array(
        'resep' => $parameter['resep'],
        'obat' => $parameter['obat'],
        'harga' => $parameter['harga'],
        'signa_qty' => $parameter['signa_qty'],
        'signa_pakai' => $parameter['signa_pakai'],
        'qty' => $parameter['qty'],
        'satuan' => $InventoriDetail['response_data'][0]['satuan_terkecil'],
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date(),
        'aturan_pakai' => '',
        'status' => 'K'
      ))
        ->execute();
    }
    return $worker;
  }

  private function update_racikan($parameter)
  {
    //Update Racikan
    /*$racikan = self::$query->update('racikan', array(
            'qty' => $parameter['qty']
        ))
        ->where(array(
            'racikan.uid' => '= ?',
            'AND',
            'racikan.asesmen' => '= ?',
            'AND',
            'racikan.deleted_at' => 'IS NULL'
        ), array(
            $parameter['racikan'],
            $parameter['asesmen']
        ))
        ->execute();

        if($racikan['response_result'] > 0) {
            $racikan_detail = self::$query->update('racikan_detail', array(
                //
            ))
            ->where(array(
                'racikan_detail.id' => '= ?'
            ), array())
            ->execute();
        }*/
    //Update Racikan Detail
  }
}
