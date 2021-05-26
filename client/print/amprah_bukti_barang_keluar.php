<?php
	require '../../config.php';
?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link type="text/css" href="<?php echo __HOSTNAME__; ?>/template/assets/css/custom.css" rel="stylesheet">
<style type="text/css">
	@media print {
		@page {
			size: 21cm 29.7cm;
			margin : 0;
			padding: 0cm;
		}
	}
	body {
		background: #fff !important;
	}
	hr {
		border: none;
		border-bottom: solid 2.5px #ccc;
	}
	table thead tr th {
		text-align: center;
		padding: 5px !important;
		vertical-align: middle !important;
	}
</style>
<div style="padding: 1cm">
	<table style="width: 100%; text-align: left;">
		<tr>
			<td style="width: 10%;">
				<img style="" src="<?php echo __HOSTNAME__; ?>/template/assets/images/logo-text-black.png" width="150" height="150" />
			</td>
			<td style="padding: 10px 80px;">
				<div style="padding: 1px 60px; border-left: solid 5px #ccc;">
					<b style="font-size: 16pt;font-family: Montserrat !important;">PEMERINTAH PROVINSI RIAU<br />RUMAH SAKIT UMUM DAERAH PETALA BUMI</b>
					<br />
					<b style="color: #808080; font-family: Montserrat !important;">Jln. Dokter Soetomo No. 65 Telp.(0761) 23024. Pekanbaru</b>
				</div>
			</td>
		</tr>
	</table>
	<hr />
	<center style="padding-bottom: 20px">
		<h3>Surat Bukti Barang Keluar Gudang</h3>
	</center>
	<div class="row">
		<div class="col-6">
			<table class="table form-mode">
				<tr>
					<td class="wrap_content">Kode Amprah</td>
					<td class="wrap_content">:</td>
					<td>
						<b id="verif_kode"><?php echo $_POST['kode']; ?></b>
					</td>
				</tr>
				<tr>
					<td class="wrap_content">Nama Pengamprah</td>
					<td class="wrap_content">:</td>
					<td id="verif_nama"></td>
				</tr>
			</table>
		</div>
		<div class="col-6">
			<table class="table form-mode">
				<tr>
					<td class="wrap_content">Unit Pengamprah</td>
					<td class="wrap_content">:</td>
					<td id="verif_unit"></td>
				</tr>
				<tr>
					<td class="wrap_content">Tanggal Amprah</td>
					<td class="wrap_content">:</td>
					<td id="verif_tanggal"></td>
				</tr>
			</table>
		</div>
	</div>
	<table class="table table-bordered largeDataType">
		<thead class="thead-dark">
			<tr>
				<th rowspan="2" class="wrap_content">No</th>
				<th rowspan="2">Item</th>
				<th rowspan="2">Satuan</th>
				<th rowspan="2">Diminta</th>
				<th colspan="3">Disetujui</th>
			</tr>
			<tr>
				<th>Batch</th>
				<th>Tgl. Kadaluarsa</th>
				<th>Jumlah</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($_POST['detail'] as $key => $value) {
			?>
			<tr>
				<td><?php echo $value['autonum']; ?></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>
</div>