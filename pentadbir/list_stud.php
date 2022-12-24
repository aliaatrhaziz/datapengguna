<?php
	session_start();
	if(!isset($_SESSION['user_login'])) header('Location: ../');
	
	include('../inc/dbConn.php');

	// |-----------------------------------------------------------------------
	// |   FUNCTION : Reset student password
	// |-----------------------------------------------------------------------

	if($_GET['reset_pd'])
	{
		$sql = "UPDATE
					info_pengguna
				SET
					password = '{$_POST['pwd']}'
				WHERE
					id = '{$_POST['id']}'";
		$res = $db->query($sql);
		echo json_encode(["status" => $res]); die;
	}

	// |-----------------------------------------------------------------------
	// |   FUNCTION : Insert / Update Pelajar
	// |-----------------------------------------------------------------------

	if($_GET['dml'])
	{
		$sql = "UPDATE
					info_pengguna
				SET
					nama_pengguna = '{$_POST['nama_pengguna']}',
					no_ndp = '{$_POST['no_ndp']}',
					no_hp = '{$_POST['no_hp']}',
					jantina = '{$_POST['jantina']}'
				WHERE
					id = '{$_POST['id']}'";
		$res = $db->query($sql);
		echo json_encode(["status" => $res]); die;
	}

	// |-----------------------------------------------------------------------
	// |   FUNCTION : Delete data Pelajar
	// |-----------------------------------------------------------------------

	if($_GET['del'])
	{
		$res = $db->query("DELETE FROM info_pengguna WHERE id = '{$_POST['id']}'");
		echo json_encode(["status" => $res]); die;
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

	<?php include('inc/head.php') ?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
		<?php include('inc/sidebar.php') ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include('inc/topbar.php') ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Senarai Nama Pelajar</h1>

					<div class="card shadow mb-4">
                        <div class="card-body" style="color: black">
                            <div class="table-responsive">									
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="color: black">
                                    <thead>
                                        <tr>
                                            <th>Nama Pelajar</th>
                                            <th style="width: 1%">No NDP</th>
                                            <th style="width: 1%">No Kad Pengenalan</th>
                                            <th class="text-nowrap" style="width: 1%">e-Mail</th>
                                            <th style="width: 1%">No Telefon</th>
                                            <th style="width: 1%">Jumlah Permohonan</th>
                                            <th style="width: 1%">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
										/* ========================================================================== Get datatable listing */
										$sql = "SELECT
													*,
													DATE_FORMAT(tarikh_daftar, '%d/%m/%Y %H:%i') t_regis,
													DATE_FORMAT(logmasuk_terakhir, '%d/%m/%Y %H:%i') t_lastlogin,
													(SELECT COUNT(id) FROM permohonan WHERE id_pengguna = info_pengguna.id) 'bil_app'
												FROM 
													info_pengguna";

										$res = $db->query($sql);
										$data = $res->fetch_all(MYSQLI_ASSOC);

										foreach($data as $d)
										{
											// Sbb nk save resource kena request balik
											// so, better simpan data dalam array then tukar ke object jap lgi
											// tpi kena reconstruct array dlu supaya senang cari kat js nanti
											$obj_data['_' . $d['id']] = $d;

											echo <<<HTML
											<tr>
												<td>$d[nama_pengguna]</td>
												<td>$d[no_ndp]</td>
												<td>$d[no_kp]</td>
												<td>$d[email]</td>
												<td>$d[no_hp]</td>
												<td>$d[bil_app]</td>
												<td class="text-nowrap">
													<div class="btn-group" role="group">
														<button type="button" onclick="app.edit($d[id])" data-toggle="tooltip" title="Pinda Maklumat Pelajar" class="btn btn-primary">
															<i class="fas fa-edit"></i>
														</button>
														<button type="button" onclick="app.del($d[id])" data-toggle="tooltip" title="Padam Data Pelajar" type="button" class="btn btn-danger">
															<i class="fas fa-trash-alt"></i>
														</button>
													</div>
												</td>
											</tr>
											HTML;
										}
										?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

	<!-- /* ========================================================================== Modal untuk edit application */ -->
	<div class="modal fade" tabindex="-1" role="dialog" id="form_modal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-primary"><b>Maklumat Pelajar</b></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form onsubmit="event.preventDefault(); app.submit()">
						<input id="id" hidden>
						<div class="form-group">
							<label for="nama_pengguna">Nama Penuh</label>
							<input class="form-control" id="nama_pengguna" required>
						</div>
						<div class="form-group">
							<label for="email">e-Mail</label>
							<input type="email" class="form-control" id="email" readonly>
						</div>
						<div class="form-row">
							<div class="form-group col-6">
								<label for="no_ndp">Nombor Daftar Pelajar</label>
								<input oninput="app.number(this)" maxlength="10" class="form-control" id="no_ndp" required>
							</div>
							<div class="form-group col-6">
								<label for="no_kp">No Kad Pengenalan</label>
								<input oninput="app.number(this)" maxlength="12" class="form-control" id="no_kp" readonly>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-6">
								<label for="no_hp">No Telefon</label>
								<input oninput="app.number(this)" maxlength="12" class="form-control" id="no_hp" required>
							</div>
							<div class="form-group col-6">
								<label for="jantina">Jantina</label>
								<select class="form-control" id="jantina" required>
									<option value="lelaki">Lelaki</option>
									<option value="perempuan">Perempuan</option>
								</select>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-4">
								<label for="bil_app">Jumlah Permohonan</label>
								<input class="form-control" id="bil_app" readonly>
							</div>
							<div class="form-group col-4">
								<label for="t_regis">Tarikh Daftar</label>
								<input class="form-control" id="t_regis" readonly>
							</div>
							<div class="form-group col-4">
								<label for="t_lastlogin">Tarikh Terakhir Log Masuk</label>
								<input class="form-control" id="t_lastlogin" readonly>
							</div>
						</div>
						<div align="right">
							<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Kemas kini</button>
						</div>
					</form>
					<hr>
					<form onsubmit="event.preventDefault(); app.reset_pd()">
						<div class="form-group">
							<label for="t_lastlogin">Set Semula Kata Laluan</label>
							<div class="input-group">
								<input type="password" class="form-control" id="pwd" required>
								<div class="input-group-append">
									<button type="submit" class="btn btn-danger" type="button">Set Semula</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php include('inc/ext_script.php') ?>

	<script>

		window.app = {

			dt_data: <?php echo json_encode($obj_data) ?>,

			number: function (elem) {
				elem.value = elem.value.replaceAll(/[^\d]/ig, '')
			},

			edit: function (id) {
				data = app.dt_data['_'+id]

				$.each(data, (i,v)=>$('#' + i).val(v))
				$(form_modal).modal('show')
			},

			submit: function () {
				swalWithBootstrapButtons.fire({
						title: `Anda pasti untuk kemas kini maklumat pelajar ini?`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: 'Ya, Saya Pasti',
						cancelButtonText: 'Tidak',
						reverseButtons: true,
						allowOutsideClick: false,
						allowEscapeKey: false
					}).then((result) => {

					if(result.isConfirmed)
					{
						$.post('list_stud.php?dml=1', {
							id: id.value,
							nama_pengguna: nama_pengguna.value,
							no_ndp: no_ndp.value,
							no_hp: no_hp.value,
							jantina: jantina.value
						}, function (res) {
							if(res.status)
							{
								Swal.fire({
									icon: 'success',
									title: 'Maklumat Pelajar Dikemaskini',
									showConfirmButton: true,
									allowOutsideClick: false,
									allowEscapeKey: false
								}).then((result) => {
									if (result.isConfirmed) {
										window.location.href = 'list_stud.php'
									}
								})
							}
							else
							{
								Swal.fire({
									icon: 'error',
									title: 'Terdapat ralat di sistem',
								})
							}
						}, 'json')
					}
				})
			},

			reset_pd: function () {
				swalWithBootstrapButtons.fire({
						title: `Anda pasti untuk set semula kata laluan untuk pelajar ini?`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: 'Ya, Saya Pasti',
						cancelButtonText: 'Tidak',
						reverseButtons: true,
						allowOutsideClick: false,
						allowEscapeKey: false
					}).then((result) => {

					if(result.isConfirmed)
					{
						$.post('list_stud.php?reset_pd=1', {
							id: id.value,
							pwd: pwd.value
						}, function (res) {
							if(res.status)
							{
								Swal.fire({
									icon: 'success',
									title: 'Kata Laluan telah berjaya di setkan semula',
									showConfirmButton: true,
									allowOutsideClick: false,
									allowEscapeKey: false
								}).then((result) => {
									if (result.isConfirmed) {
										pwd.value = ''
									}
								})
							}
							else
							{
								Swal.fire({
									icon: 'error',
									title: 'Terdapat ralat di sistem',
								})
							}
						}, 'json')
					}
				})
			},

			del: function (pid) {
				swalWithBootstrapButtons.fire({
						title: `Anda pasti padam data pelajar ini?`,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: 'Ya, Saya Pasti',
						cancelButtonText: 'Tidak',
						reverseButtons: true,
						allowOutsideClick: false,
						allowEscapeKey: false
					}).then((result) => {

					if(result.isConfirmed)
					{
						$.post('list_stud.php?del=1', {
							id: pid
						}, function (res) {
							if(res.status)
							{
								Swal.fire({
									icon: 'success',
									title: 'Maklumat Pelajar Dipadam',
									showConfirmButton: true,
									allowOutsideClick: false,
									allowEscapeKey: false
								}).then((result) => {
									if (result.isConfirmed) {
										window.location.href = 'list_stud.php'
									}
								})
							}
							else
							{
								Swal.fire({
									icon: 'error',
									title: 'Terdapat ralat di sistem',
								})
							}
						}, 'json')
					}
				})
			}
		}
	</script>	

</body>

</html>