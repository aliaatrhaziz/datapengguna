<?php
	session_start();
	if(!isset($_SESSION['user_login'])) header('Location: ../');

	include('../inc/dbConn.php');

	// |-----------------------------------------------------------------------
	// |   FUNCTION : Update / Insert table permohonan
	// |-----------------------------------------------------------------------

	if($_GET['dml'])
	{
		$id = $_POST['id'] ?: 'NULL';
		$sql = "INSERT INTO
					permohonan
				SET
					id = $id,
					id_pengguna = {$_SESSION['user_login']['id']},
					tarikh_keluar = STR_TO_DATE('{$_POST['keluar']}', '%Y-%m-%d'),
					tarikh_masuk = STR_TO_DATE('{$_POST['masuk']}', '%Y-%m-%d'),
					tujuan = '{$_POST['tujuan']}',
					tempat_tujuan = '{$_POST['tempat']}',
					status_permohonan = 'BARU',
					tarikh_permohonan = NOW()
				ON DUPLICATE KEY UPDATE
					tarikh_keluar = STR_TO_DATE('{$_POST['keluar']}', '%Y-%m-%d'),
					tarikh_masuk = STR_TO_DATE('{$_POST['masuk']}', '%Y-%m-%d'),
					tujuan = '{$_POST['tujuan']}',
					tempat_tujuan = '{$_POST['tempat']}',
					tarikh_permohonan = NOW()";
		$res = $db->query($sql);
		echo json_encode(["status" => $res]); die;
	}

	// |-----------------------------------------------------------------------
	// |   FUNCTION : If this true, means update process currently ongoing thus get the data
	// |-----------------------------------------------------------------------

	if($_GET['id'])
	{
		$sql = "SELECT
					*
				FROM 
					permohonan
				WHERE
					id = '{$_GET['id']}'";
		$res = $db->query($sql);
		$data = $res->fetch_all(MYSQLI_ASSOC)[0];
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
                    <h1 class="h3 mb-4 text-gray-800">Permohonan Baru Outing / Balik</h1>

                    <div class="card shadow mb-4">
                        <div class="card-body">
						<form onsubmit="event.preventDefault(); app.submit();">
							<input class="form-control" id="id" value="<?php echo $data['id'] ?>" hidden>
							<div class="form-group">
								<label for="tujuan">Tujuan</label>
								<select class="form-control" id="tujuan" required>
									<option value="" disabled selected>Sila Pilih</option>
									<option value="outing">Outing</option>
									<option value="balik">Balik</option>
								</select>
								<?php
									if($data['tujuan'])
									{
										echo <<<HTML
											<script>tujuan.value = '$data[tujuan]'</script>
										HTML;
									}
								?>
							</div>
							<div class="form-row">
								<div class="form-group col-6">
									<label for="keluar">Tarikh Keluar</label>
									<input type="date" class="form-control" id="keluar" min="<?php echo date("Y-m-d")?>" value="<?php echo $data['tarikh_keluar'] ?>"" required>
								</div>
								<div class="form-group col-6">
									<label for="masuk">Tarikh Masuk</label>
									<input type="date" class="form-control" id="masuk" min="<?php echo date("Y-m-d")?>" value="<?php echo $data['tarikh_masuk'] ?>" required>
								</div>
							</div>
							<div class="form-group">
								<label for="tempat">Tempat Tujuan</label>
								<textarea class="form-control" id="tempat" maxlength="255" required><?php echo $data['tempat_tujuan'] ?></textarea>
							</div>
							<div align="right">
								<button type="submit" class="btn btn-primary">
									<i class="fas fa-save"></i>
									Hantar Permohonan
								</button>
							</div>
						</form>
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

			submit: function (e) {
				$.post('form_baru.php?dml=1', {
					id: id.value,
					tujuan: tujuan.value,
					keluar: keluar.value,
					masuk: masuk.value,
					tempat: tempat.value
				}, function (res) {
					if(res.status)
					{
						Swal.fire({
							icon: 'success',
							title: 'Permohonan Dihantar',
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
		}
	</script>

</body>

</html>