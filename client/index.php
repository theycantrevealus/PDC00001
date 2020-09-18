<?php
	require '../config.php';
	define('__PAGES__', explode('/', $_GET['pondokcoder_simrs']));
?>

<script type="text/javascript">
	var __HOSTNAME__ = <?php echo json_encode(__HOSTNAME__); ?>;
	var __HOSTAPI__ = <?php echo json_encode(__HOSTAPI__); ?>;
	var __PAGES__ = <?php echo json_encode(__PAGES__); ?>;
	var __HOST__ = <?php echo json_encode(__HOST__); ?>;
	var __ME__ = <?php echo json_encode($_SESSION['uid']); ?>;
</script>

<?php


	if(isset($_SESSION['token'])) {
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