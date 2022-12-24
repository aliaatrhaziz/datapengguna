<?php

include('inc/dbConn.php');

// |-----------------------------------------------------------------------
// |   FUNCTION : Login Student
// |-----------------------------------------------------------------------

if($_GET['login_stud'])
{
	$res = $db->query("SELECT * FROM info_pengguna WHERE email = '{$_POST['email']}' AND password = '{$_POST['password']}'");
	$c_res = $res->fetch_all(MYSQLI_ASSOC)[0];

	if(count($c_res))
	{
		$db->query("UPDATE info_pengguna SET logmasuk_terakhir = NOW() WHERE id = {$c_res['id']}");
		session_start();
		$_SESSION['user_login'] = $c_res;
		echo json_encode(["status" => "ok"]);
	}
	else echo json_encode(["status" => "ko"]);
	
	die;
}

// |-----------------------------------------------------------------------
// |   FUNCTION : Login Pentadbir
// |-----------------------------------------------------------------------

if($_GET['login_admin'])
{
	if($_GET['pwd'] == "abc123")
	{
		session_start();
		$_SESSION['user_login'] = [
			"id" => "admin",
			"nama_pengguna" => "Sistem Pentadbir"
		];
		echo json_encode(["status" => true]);
	}
	else echo json_encode(["status" => false]);
	
	die;
}

// |-----------------------------------------------------------------------
// |   FUNCTION : User logout so clearkan data
// |-----------------------------------------------------------------------

if($_GET['logout'])
{
	session_start();
	$_SESSION['user_login'] = [];
	session_destroy();
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

    <title>Sistem Keluar Masuk Pelajar ILP K. Langat</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">
											Sistem Keluar Masuk Pelajar<br>
											Institut Latihan Perindustrian Kuala Langat
										</h1>
                                    </div>
                                    <form onsubmit="app.stud_login(); return false;" class="user">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" id="email" required placeholder="Alamat e-Mail...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="password" required placeholder="Kata Laluan">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Log Masuk</button>
										<button type="button" class="btn btn-secondary btn-user btn-block" onclick="app.admin_login()">Log Masuk sebagai Pentadbir</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Daftar Akaun!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
		$(document).ready(function() {
			// kalau daftar success.. popup mesej
			<?php
				if($_GET['registed'])
				{
					echo <<<HTML
						Swal.fire({
							icon: 'success',
							title: 'Pendaftaran Berjaya',
							showConfirmButton: false,
							timer: 2000
						})
					HTML;
				}
			?>
		});


		window.app = {

			stud_login: function () {
				$.post('index.php?login_stud=1', {
					email: email.value,
					password: password.value
				}, function (res) {

					if(res.status == 'ok')
					{
						window.location.href = 'pelajar'
					}
					else
					{
						Swal.fire({
							icon: 'error',
							title: 'Kombinasi e-Mail dan Kata Laluan tidak wujud!',
						})
					}
				}, 'json')
			},

			admin_login: function () {
				Swal.fire({
					title: 'Sila masukkan kata laluan pentadbir',
					input: 'password',
					showCancelButton: true,
					cancelButtonText: 'Batal',
					confirmButtonText: 'Log Masuk',
					showLoaderOnConfirm: true,
					preConfirm: (pwd) => {
						return fetch(`index.php?login_admin=1&pwd=${pwd}`)
						.then(res => res.json())
						.then(res => {
							if(res.status) window.location.href = 'pentadbir'
							else Swal.showValidationMessage(`Kata laluan tidak tepat`)
						})
					},
					allowOutsideClick: () => !Swal.isLoading()
					})
			}
		}
	</script>

</body>

</html>