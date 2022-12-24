<?php
	session_start();
	if(!isset($_SESSION['user_login'])) header('Location: ../');
	
	include('../inc/dbConn.php');

	// |-----------------------------------------------------------------------
	// |   FUNCTION : Polling data within times interval
	// |-----------------------------------------------------------------------

	if($_GET['poll_data'])
	{
		$sql = "SELECT
					COUNT(id) 'C'
				FROM
					permohonan
				WHERE
					tarikh_kemaskini_pentadbir >= (
						SELECT 
							logmasuk_terakhir 
						FROM
							info_pengguna
						WHERE
							id = ".$_SESSION['user_login']['id'].")";
		$res = $db->query($sql);
		$data = $res->fetch_all(MYSQLI_ASSOC);

		echo json_encode($data[0]['C']); die;
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Halaman Utama</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-md-12 mb-4" onclick="app.goto('profil.php')">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Profil Diri</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-md-12 mb-4" onclick="app.goto('form_baru.php')">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Permohonan Baru Outing / Balik</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-md-12 mb-4" onclick="app.goto('form_his.php')">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-1 font-weight-bold text-gray-800">Sejarah Permohonan Outing / Balik</div>
                                            <div class="h6 mb-0 font-weight-light text-gray-800"><span id="poll">0</span> kemas kini dari pentadbir</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-history fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
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
		$(document).ready(function() {
			setInterval(function(){
				$.post('index.php?poll_data=1', function (res) {
					poll.innerHTML = res
				}, 'json')
			}, 11000) // 1 saat = 1000, jangan buat kurang dari 2 saat.. hang nanti
		})

		window.app = {

			goto: function (link) {
				window.location.href = link
			}
		}
	</script>

</body>

</html>