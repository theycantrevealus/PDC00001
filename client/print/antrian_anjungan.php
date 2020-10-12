<?php
	require '../../config.php';
?>
<style type="text/css">
	@media print {
		@page {
			size: 9.2cm 4.9cm ;
			margin :0;
			padding:0;
		}
	}
</style>
<div>
	<center>
		<img src="<?php echo __HOSTNAME__; ?>/template/assets/images/logo-text-black.png" width="100" height="90" />
		<h5>SELAMAT DATANG DI RSUD PETALA BUMI</h5>
		<H6>NOMOR ANTRIAN ANDA</H6>
		<h1 style="font-size: 40pt; font-family: Courier; margin: 0; padding: 0"><?php echo $_POST['antrian']; ?></h1>
		<H6>TERIMA KASIH<br />ANDA TELAH MENUNGGU</H6>
		<h6>Semoga Lekas Sembuh</h6>
		<h6><?php echo date('d F Y'); ?></h6>
	</center>
</div>