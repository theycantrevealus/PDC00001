<?php
	require '../config.php';
	define('__PAGES__', explode('/', $_GET['pondokcoder_simrs']));
    $day=new DateTime('last day of this month');
?>

<script type="text/javascript">
    var __PC_CUSTOMER__ = <?php echo json_encode(__PC_CUSTOMER__); ?>;
	var __SYNC__ = <?php echo json_encode(__SYNC__); ?>;
	var __SYNC_PORT__ = <?php echo json_encode(__SYNC_PORT__); ?>;
	var __HOSTNAME__ = <?php echo json_encode(__HOSTNAME__); ?>;
	var __HOSTAPI__ = <?php echo json_encode(__HOSTAPI__); ?>;
	var __PAGES__ = <?php echo json_encode(__PAGES__); ?>;
	var __HOST__ = <?php echo json_encode(__HOST__); ?>;

	var __ME__ = <?php echo json_encode($_SESSION['uid']); ?>;
	var __PROFILE_PIC__ = <?php echo json_encode($_SESSION['profile_pic']); ?>;
	var __MY_NAME__ = <?php echo json_encode($_SESSION['nama']); ?>;
	var __UNIT__ = <?php echo json_encode($_SESSION['unit']); ?>;

	var __UIDPENJAMINUMUM__ = <?php echo json_encode(__UIDPENJAMINUMUM__); ?>;
	var __UIDPENJAMINBPJS__ = <?php echo json_encode(__UIDPENJAMINBPJS__); ?>;
	var __GUDANG_APOTEK__ = <?php echo json_encode(__GUDANG_APOTEK__); ?>;
	var __GUDANG_UTAMA__ = <?php echo json_encode(__GUDANG_UTAMA__); ?>;


    var __UIDADMIN__ = <?php echo json_encode(__UIDADMIN__); ?>;
    var __UIDFISIOTERAPI__ = <?php echo json_encode(__UIDFISIOTERAPI__); ?>;
    var __UIDPETUGASLAB__ = <?php echo json_encode(__UIDPETUGASLAB__); ?>;
    var __POLI_GIGI__ = <?php echo json_encode(__POLI_GIGI__); ?>;
    var __POLI_MATA__ = <?php echo json_encode(__POLI_MATA__); ?>;
    var __POLI_INAP__ = <?php echo json_encode(__POLI_INAP__); ?>;

	var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	var __TODAY__  = mm + ' ' + monthNames[today.getMonth()] + ' ' + yyyy;
	//Kelas
	var __UID_KELAS_GENERAL_RJ__ = <?php echo json_encode(__UID_KELAS_GENERAL_RJ__); ?>;
</script>

<?php


	if(
		isset($_SESSION['token']) ||
		__PAGES__[0] == 'anjungan' ||
		__PAGES__[0] == 'display'
	) {
		$params = parse_ini_file('../api/app/database.ini');
		$conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
			$params['host'],
			$params['port'],
			$params['database'],
			$params['user'],
			$params['password']);
		$pdo = new \PDO($conStr);
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		require 'builder.php';
	} else {
		require 'pages/system/login.php';	
	}

?>