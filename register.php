<?php

include('inc/dbConn.php');

// |-----------------------------------------------------------------------
// |   FUNCTION : Register Student
// |-----------------------------------------------------------------------

if($_GET['dml'])
{
	$sql = "INSERT INTO
				info_pengguna
			SET
				nama_pengguna = '{$_POST['name']}',
				no_ndp = '{$_POST['ndp']}',
				no_kp = '{$_POST['kp']}',
				email = '{$_POST['email']}',
				password = '{$_POST['pass']}',
				jantina = '{$_POST['gender']}',
				no_hp = '{$_POST['hp']}',
				tarikh_daftar = NOW()";
	try {
		$res = $db->query($sql);
	}catch (Exception $e){
		$error = $e->getMessage();
	}
	
	echo json_encode(["status" => $res ?: $error]); die;
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

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form onsubmit="app.submit(); return false;" class="user">
                                <div class="form-group">
                                    <input class="form-control form-control-user" required id="nama_penuh" placeholder="Nama Penuh" >
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-user"required  id="no_ndp" oninput="app.number(this)" maxlength="10" placeholder="Nombor Daftar Pelajar">
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-user" required id="no_kp" oninput="app.number(this)" maxlength="12" placeholder="No Kad Pengenalan">
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-user" required id="email" placeholder="Alamat e-Mail">
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-user" required id="no_hp" oninput="app.number(this)" maxlength="12" placeholder="No Telefon">
                                </div>
                                <div class="form-group">
                                    <select class="form-control" id="jantina" required style="border-radius: 10rem;">
										<option value="" disabled selected>Jantina</option>
										<option value="lelaki">Lelaki</option>
										<option value="perempuan">Perempuan</option>
									</select>
                                </div>					
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" required id="pass1" placeholder="Kata Laluan">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" required id="pass2" placeholder="Ulang Kata Laluan">
                                    </div>
                                </div>
								<button type="submit" class="btn btn-primary btn-user btn-block">Register Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
	
	<script>
		window.app = {

			number: function (elem) {
				elem.value = elem.value.replaceAll(/[^\d]/ig, '')
			},

			submit: function () {

				if(pass1.value != pass2.value)
				{
					Swal.fire({
						icon: 'error',
						title: 'Kata laluan tidak sama',
					})
				}
				else
				{
					$.post('register.php?dml=1', {
						name: nama_penuh.value,
						ndp: no_ndp.value,
						kp: no_kp.value,
						email: email.value,
						hp: no_hp.value,
						gender: jantina.value,
						pass: pass1.value
					}, function (res) {

						if(res.status == true)
						{
							window.location.href = 'index.php?registed=1'
						}
						else
						{
							Swal.fire({
								icon: 'error',
								title: 'No kad pengenalan dah wujud didalam sistem',
							})
						}
					}, 'json')
				}
			}
		}
	</script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>