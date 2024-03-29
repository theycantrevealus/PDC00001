<div class="container-fluid page__heading-container">
  <div class="page__heading d-flex align-items-center">
    <div class="flex">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Pasien</li>
        </ol>
      </nav>
      <h4 class="m-0">Data Pasien</h4>
    </div>
  </div>
</div>

<?php
$yesterday = new DateTime(date('Y-m-d')); // For today/now, don't pass an arg.
$yesterday->modify("-1 day");

$tomorrow = new DateTime(date('Y-m-d'));
$tomorrow->modify("+1 day");
?>

<div class="container-fluid page__container">
  <div class="card">
    <div class="card-header card-header-large bg-white d-flex align-items-center">
      <h5 class="card-header__title flex m-0">CPPT</h5>
    </div>
    <div class="card-body tab-content">
      <div class="tab-pane active show fade" id="resep-biasa">
        <div class="row">
          <div class="col-md-12">


            <div class="card card-form d-flex flex-column flex-sm-row">
              <div class="card-body-form-group flex">
                <div class="row">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="filter_date">Dari - Sampai</label>
                      <input id="filter_date" type="text" class="form-control" placeholder="Filter Tanggal" data-toggle="flatpickr" data-flatpickr-mode="range" value="<?php echo /* $yesterday->format("Y-m-d")*/ '2018-01-01'; ?> to <?php echo $tomorrow->format("Y-m-d"); ?>" />
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <br /><br />
                    <nav aria-label="CPPT Pagination" id="cppt_pagination" class="paginate_selection">
                      <ul class="pagination"></ul>
                    </nav>
                  </div>
                  <div class="col-lg-2">
                    <br /><br />
                    <button class="btn btn-info pull-right" id="btnTambahAsesmen">
                      <i class="fa fa-plus"></i> Tambah Asesmen
                    </button>
                  </div>
                </div>
              </div>
              <button class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary">refresh</i></button>
            </div>


            <div id="cppt_pagination">

            </div>
            <div class="card">
              <div id="cppt_loader" class="card-body">
                <div class="no-data-panel">
                  <div class="row">
                    <div class="col-lg-6">
                      <div style="padding: 50px;">
                        <h1 class="text-muted">Tidak ada Data Ditemukan</h1>
                        <p style="padding: 10px;">Silahkan ubah filter pencarian untuk mendapatkan data lain.</p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <img style="width: 100%;" alt="no-data" src="<?php echo __HOSTNAME__; ?>/template/assets/images/illustration/undraw_startled_8p0r.png" />
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <div class="mt-4">
              <ul class="pagination justify-content-center" id="pagin">

              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>