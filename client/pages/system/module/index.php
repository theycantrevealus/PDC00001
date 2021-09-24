<div class="container-fluid page__heading-container">
	<div class="page__heading d-flex align-items-center">
		<div class="flex">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item"><a href="<?php echo __HOSTNAME__; ?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Module</li>
				</ol>
			</nav>
			<h4 class="m-0">Module Manager</h4>
		</div>
	</div>
</div>


<div class="container-fluid page__container">
	<div class="row card-group-row">
		<div class="col-lg-4 col-md-4 card-group-row__col">
			<div class="card card-body">
				<button id="btn_tambah_modul" class="btn btn-info ml-3"><i class="fa fa-plus"></i> Module Parent</button>
				<hr />
				<div id="module"></div>
			</div>
		</div>
		<div class="col-lg-8 col-md-8 card-group-row__col">
			<div class="card card-body">

				<h5>
					<i class="fa fa-cubes"></i> Methods Data
					<small class="pull-right">
						<a href="#" id="reload-methods">
							<i class="fa fa-sync"></i> Refresh Methods
						</a>
					</small>
				</h5>
				<table class="table table-bordered largeDataType" id="methods-loader">
					<thead class="thead-dark">
						<tr>
							<th style="width: 20px;"><i class="fa fa-hashtag"></i></th>
							<th>Methods</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>




<div class="panel-simulator">
	<ul class='custom-menu'>
		<li data-action="add_pos">
			<div>
				<b>
					<i class="fa fa-plus"></i>
				</b>
				<span>Add Child</span>
			</div>
		</li>
		<li data-action="edit_pos">
			<div>
				<b>
					<i class="fa fa-edit"></i>
				</b>
				<span>Edit</span>
			</div>
		</li>
		<li data-action="delete_pos">
			<div>
				<i class="fa fa-trash"></i>
				<span>Delete</span>
			</div>
		</li>
	</ul>
</div>