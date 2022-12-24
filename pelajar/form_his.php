<?php
	session_start();
	if(!isset($_SESSION['user_login'])) header('Location: ../');
	
	include('../inc/dbConn.php');

	// |-----------------------------------------------------------------------
	// |   FUNCTION : Delete permohonan
	// |-----------------------------------------------------------------------

	if($_GET['delete'])
	{
		$res = $db->query("DELETE FROM permohonan WHERE id = '{$_POST['id']}'");
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
                    <h1 class="h3 mb-4 text-gray-800">Sejarah Permohonan Outing / Balik</h1>

					<div class="card shadow mb-4">
                        <div class="card-body" style="color: black">
                            <div class="table-responsive">									
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="color: black">
                                    <thead>
                                        <tr>
                                            <th style="width: 1%">Tarikh Permohonan</th>
                                            <th style="width: 1%">Tarikh Keluar</th>
                                            <th style="width: 1%">Tarikh Masuk</th>
                                            <th style="width: 1%">Tujuan</th>
                                            <th>Tempat Tujuan</th>
                                            <th style="width: 1%">Status Permohonan</th>
                                            <th style="width: 1%">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
										/* ========================================================================== Get datatable listing */
										$sql = "SELECT
													*,
													DATE_FORMAT(tarikh_keluar, '%d/%m/%Y') t_out,
													DATE_FORMAT(tarikh_masuk, '%d/%m/%Y') t_in,
													DATE_FORMAT(tarikh_permohonan, '%d/%m/%Y %H:%i') t_apply
												FROM 
													permohonan
												WHERE
													id_pengguna = '{$_SESSION['user_login']['id']}'
												ORDER BY
													tarikh_permohonan DESC";
										$res = $db->query($sql);
										$data = $res->fetch_all(MYSQLI_ASSOC);

										foreach($data as $d)
										{
											// utk disabled button
											$d_edit = $d['status_permohonan'] != 'BARU' ? 'disabled' : '';
											$d_delete = $d['status_permohonan'] != 'BARU' ? 'disabled' : '';

											echo <<<HTML
											<tr>
												<td><!--$d[tarikh_permohonan]-->$d[t_apply]</td>
												<td><!--$d[tarikh_keluar]-->$d[t_out]</td>
												<td><!--$d[tarikh_masuk]-->$d[t_in]</td>
												<td class="text-capitalize">$d[tujuan]</td>
												<td>$d[tempat_tujuan]</td>
												<td>$d[status_permohonan]</td>
												<td>
													<div class="btn-group" role="group">
														<button type="button" onclick="app.edit($d[id], '$d_edit')" data-toggle="tooltip" title="Pinda Permohonan" class="btn btn-primary $d_edit">
															<i class="fas fa-edit"></i>
														</button>
														<button type="button" onclick="app.del($d[id], '$d_edit')" data-toggle="tooltip" title="Batalkan Permohonan" type="button" class="btn btn-danger">
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

	<?php include('inc/ext_script.php') ?>

	<script>
		window.app = {

			edit: function (id, disabled) {
				if(!disabled)
				{
					window.location.href = "form_baru.php?id=" + id
				}
			},

			del: function (id, disabled) {
				if(!disabled)
				{
					swalWithBootstrapButtons.fire({
						title: 'Anda pasti untuk batalkan permohonan ini?',
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
							$.post('form_his.php?delete=1', {id}, function (res) {
								if(res.status)
								{
									Swal.fire({
										icon: 'success',
										title: 'Permohonan Dibatalkan',
										showConfirmButton: true,
										allowOutsideClick: false,
										allowEscapeKey: false
									}).then((result) => {
										if (result.isConfirmed) {
											window.location.href = 'form_his.php'
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
		}
	</script>	

</body>

</html>