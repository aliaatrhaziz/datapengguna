<?php
	session_start();
	if(!isset($_SESSION['user_login'])) header('Location: ../');
	
	include('../inc/dbConn.php');

	if($_GET['dml'])
	{
		$sql = "UPDATE
					permohonan
				SET
					status_permohonan = '{$_POST['stat']}',
					tarikh_kemaskini_pentadbir = NOW()
				WHERE
					id = '{$_POST['id']}'";
		$res = $db->query($sql);
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
                    <h1 class="h3 mb-4 text-gray-800">Senarai Permohonan Outing (<?php echo $_GET['baru'] ? 'Baru' : 'Terdahulu' ?>)</h1>

					<div class="card shadow mb-4">
                        <div class="card-body" style="color: black">
                            <div class="table-responsive">									
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="color: black">
									<?php
										$cond = $_GET['baru'] ? '=' : '!=';
										$head = !$_GET['baru'] ? '<th style="width: 1%">Status Permohonan</th>' : '';
										$act_button = $_GET['baru'] 
											? '<i class="fas fa-edit"></i> Pinda Permohonan'
											: '<i class="fas fa-eye"></i> Lihat Permohonan';
									?>
                                    <thead>
                                        <tr>
                                            <th style="width: 1%">Tarikh Permohonan</th>
                                            <th>Pemohon</th>
                                            <th style="width: 1%">Tarikh Keluar</th>
                                            <th style="width: 1%">Tarikh Masuk</th>
											<?php echo $head ?>
                                            <th class="text-nowrap" style="width: 1%">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
										/* ========================================================================== Get datatable listing */
										$sql = "SELECT
													a.*,
													b.*,
													a.id 'apps_id',
													DATE_FORMAT(tarikh_keluar, '%d/%m/%Y') t_out,
													DATE_FORMAT(tarikh_masuk, '%d/%m/%Y') t_in,
													DATE_FORMAT(tarikh_permohonan, '%d/%m/%Y %H:%i') t_apply
												FROM 
													permohonan a,
													info_pengguna b
												WHERE
													a.id_pengguna = b.id
													AND status_permohonan $cond 'BARU'
													AND tujuan = 'outing'
												ORDER BY
													tarikh_permohonan DESC";

										$res = $db->query($sql);
										$data = $res->fetch_all(MYSQLI_ASSOC);
										$obj_data = [];

										foreach($data as $d)
										{
											$td = !$_GET['baru'] ? "<td>{$d['status_permohonan']}</td>" : '';
											// Sbb nk save resource kena request balik
											// so, better simpan data dalam array then tukar ke object jap lgi
											// tpi kena reconstruct array dlu supaya senang cari kat js nanti
											$obj_data['_' . $d['apps_id']] = $d;

											echo <<<HTML
											<tr>
												<td><!--$d[tarikh_permohonan]-->$d[t_apply]</td>
												<td>$d[nama_pengguna]</td>
												<td><!--$d[tarikh_keluar]-->$d[t_out]</td>
												<td><!--$d[tarikh_masuk]-->$d[t_in]</td>
												$td
												<td class="text-nowrap">
													<button type="button" onclick="app.action($d[apps_id])" class="btn btn-primary">
														$act_button
													</button>
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
					<h5 class="modal-title text-primary"><b>Maklumat Permohonan</b></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form onsubmit="event.preventDefault(); app.submit()">
					<div class="modal-body">
						<div class="form-group">
							<label for="nama_pengguna">Nama Penuh</label>
							<input class="form-control" id="nama_pengguna" readonly>
						</div>
						<div class="form-group">
							<label for="email">e-Mail</label>
							<input type="email" class="form-control" id="email" readonly>
						</div>
						<div class="form-row">
							<div class="form-group col-6">
								<label for="no_ndp">Nombor Daftar Pelajar</label>
								<input class="form-control" id="no_ndp" readonly>
							</div>
							<div class="form-group col-6">
								<label for="no_kp">No Kad Pengenalan</label>
								<input class="form-control" id="no_kp" readonly>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-6">
								<label for="no_hp">No Telefon</label>
								<input class="form-control" id="no_hp" readonly>
							</div>
							<div class="form-group col-6">
								<label for="jantina">Jantina</label>
								<input class="form-control text-capitalize" id="jantina" readonly>
							</div>
						</div>
						<hr>
						<div class="form-row">
							<div class="form-group col-6">
								<label for="keluar">Tarikh Keluar</label>
								<input class="form-control" id="t_out" readonly>
							</div>
							<div class="form-group col-6">
								<label for="masuk">Tarikh Masuk</label>
								<input class="form-control" id="t_in" readonly>
							</div>
						</div>
						<div class="form-group">
							<label for="tempat">Tempat Tujuan</label>
							<textarea class="form-control" id="tempat_tujuan" readonly></textarea>
						</div>
						<hr>
						<input id="apps_id" hidden>
						<div class="form-group">
							<label for="status_permohonan">Status Permohonan</label>
							<select class="form-control" id="status_permohonan" <?php echo $_GET['baru'] ? "required" : "disabled" ?>>
								<option value="" disabled selected>Sila Pilih</option>
								<?php
									if($_GET['baru'])
									{
										echo <<<HTML
											<option value="DITOLAK">Tolak Permohonan</option>
											<option value="LULUS">Luluskan Permohonan</option>
										HTML;
									}
									else
									{
										echo <<<HTML
											<option value="DITOLAK">Permohonan Ditolak</option>
											<option value="LULUS">Permohonan Diluluskan</option>
										HTML;
									}
								?>
							</select>
						</div>
					</div>
					<div class="modal-footer <?php echo !$_GET['baru'] ? "d-none" : "" ?>">
						<button type="button" data-dismiss="modal" class="btn btn-danger">Batal</button>
						<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Kemas kini</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php include('inc/ext_script.php') ?>

	<script>

		window.app = {

			dt_data: <?php echo json_encode($obj_data) ?>,

			action: function (id) {
				data = app.dt_data['_'+id]

				$.each(data, (i,v)=>$('#' + i).val(v))
				$(form_modal).modal('show')
			},

			submit: function () {
				swalWithBootstrapButtons.fire({
						title: `Anda pasti untuk ${$('#status_permohonan :selected').text().toLowerCase()} ini?`,
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
						$.post('list_outing.php?dml=1', {
							id: apps_id.value,
							stat: status_permohonan.value
						}, function (res) {
							if(res.status)
							{
								Swal.fire({
									icon: 'success',
									title: 'Permohonan Dikemaskini',
									showConfirmButton: true,
									allowOutsideClick: false,
									allowEscapeKey: false
								}).then((result) => {
									if (result.isConfirmed) {
										window.location.href = 'list_balik.php?baru=1'
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