<?php
	session_start();
	if(!isset($_SESSION['user_login'])) header('Location: ../');

	include('../inc/dbConn.php');

	// |-----------------------------------------------------------------------
	// |   FUNCTION : Insert / Update Pelajar
	// |-----------------------------------------------------------------------

	if($_GET['dml'])
	{
		$sql = "UPDATE
					info_pengguna
				SET
					nama_pengguna = '{$_POST['nama_penuh']}',
					no_ndp = '{$_POST['no_ndp']}',
					email = '{$_POST['email']}',
					no_hp = '{$_POST['no_hp']}',
					jantina = '{$_POST['jantina']}'
				WHERE
					id = '{$_POST['id']}'";
		$res = $db->query($sql);

		if($res)
		{
			$_SESSION['user_login']['nama_pengguna'] = $_POST['nama_penuh'];
			$_SESSION['user_login']['no_ndp'] = $_POST['no_ndp'];
			$_SESSION['user_login']['email'] = $_POST['email'];
			$_SESSION['user_login']['no_hp'] = $_POST['no_hp'];
			$_SESSION['user_login']['jantina'] = $_POST['jantina'];
		}
		echo json_encode(["status" => $res]); die;
	}
	
	// |-----------------------------------------------------------------------
	// |   FUNCTION : Reset student password
	// |-----------------------------------------------------------------------

	if($_GET['reset_pd'])
	{
		if($_POST['c_pwd'] == $_SESSION['user_login']['password'])
		{
			$sql = "UPDATE
						info_pengguna
					SET
						password = '{$_POST['pwd']}'
					WHERE
						id = '{$_POST['id']}'";
			$res = $db->query($sql);
			if($res) $_SESSION['user_login']['password'] = $_POST['pwd'];
			echo json_encode(["status" => $res]); die;
		}
		else echo json_encode(["status" => "incorrect"]); die;
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
                    <h1 class="h3 mb-4 text-gray-800">Profil Diri</h1>

                    <div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">Info Diri</h6>
						</div>
                        <div class="card-body">
						<form onsubmit="event.preventDefault(); app.submit()">
							<div class="form-group">
								<label for="nama_penuh">Nama Penuh</label>
								<input class="form-control" id="nama_penuh" value="<?php echo $_SESSION['user_login']['nama_pengguna'] ?>">
							</div>
							<div class="form-group">
								<label for="no_ndp">Nombor Daftar Pelajar</label>
								<input class="form-control" oninput="app.number(this)" maxlength="10" id="no_ndp" value="<?php echo $_SESSION['user_login']['no_ndp'] ?>">
							</div>
							<div class="form-group">
								<label for="no_kp">No Kad Pengenalan</label>
								<input class="form-control" id="no_kp" oninput="app.number(this)" maxlength="12" value="<?php echo $_SESSION['user_login']['no_kp'] ?>" disabled>
							</div>
							<div class="form-group">
								<label for="email">e-Mail</label>
								<input type="email" class="form-control" id="email" value="<?php echo $_SESSION['user_login']['email'] ?>">
							</div>
							<div class="form-row">
								<div class="form-group col-6">
									<label for="no_hp">No Telefon</label>
									<input class="form-control" id="no_hp" oninput="app.number(this)" maxlength="12" value="<?php echo $_SESSION['user_login']['no_hp'] ?>">
								</div>
								<div class="form-group col-6">
									<label for="jantina">Jantina</label>
									<select class="form-control" id="jantina" required>
										<option value="lelaki">Lelaki</option>
										<option value="perempuan">Perempuan</option>
									</select>
									<script>
										jantina.value = '<?php echo $_SESSION['user_login']['jantina'] ?>'
									</script>
								</div>
							</div>
							<div align="right">
								<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Kemas kini</button>
							</div>
						</form>
                        </div>
                    </div>

					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">Ubah Kata Laluan</h6>
						</div>
                        <div class="card-body">
						<form onsubmit="event.preventDefault(); app.reset_pd()">
							<div class="form-group">
								<label for="pass">Kata Laluan Sekarang</label>
								<input type="password" class="form-control form-control-user" required id="pass">
							</div>
							<div class="form-row">
								<div class="form-group col-sm-6">
									<label for="pass1">Kata Laluan Baru</label>
									<input type="password" class="form-control form-control-user" required id="pass1">
								</div>
								<div class="form-group col-sm-6">
									<label for="pass2">Ulang Kata Laluan Baru</label>
									<input type="password" class="form-control form-control-user" required id="pass2">
								</div>
							</div>
							<div align="right">
								<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Kemas kini</button>
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

	number: function (elem) {
		elem.value = elem.value.replaceAll(/[^\d]/ig, '')
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
				$.post('profil.php?dml=1', {
					id: <?php echo $_SESSION['user_login']['id'] ?>,
					nama_penuh: nama_penuh.value,
					no_ndp: no_ndp.value,
					email: email.value,
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
								window.location.href = 'profil.php'
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

		if(pass1.value != pass2.value)
		{
			Swal.fire({
				icon: 'error',
				title: 'Kata laluan tidak sama',
			})
		}
		else
		{
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
					$.post('profil.php?reset_pd=1', {
						c_pwd: pass.value,
						pwd: pass1.value
					}, function (res) {

						if(res.status == 'incorrect')
						{
							Swal.fire({
								icon: 'error',
								title: 'Kata laluan sekarang salah!',
							})
						}
						else if(res.status == true)
						{
							Swal.fire({
								icon: 'success',
								title: 'Kata Laluan telah berjaya di setkan semula',
								showConfirmButton: true,
								allowOutsideClick: false,
								allowEscapeKey: false
							}).then((result) => {
								if (result.isConfirmed) {
									window.location.href = 'profil.php'
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