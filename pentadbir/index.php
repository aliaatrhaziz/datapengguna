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
					(SELECT COUNT(id) FROM permohonan WHERE tujuan = 'balik' AND status_permohonan = 'BARU') A,
					(SELECT COUNT(id) FROM permohonan WHERE tujuan = 'outing' AND status_permohonan = 'BARU') B";
		$res = $db->query($sql);
		$data = $res->fetch_all(MYSQLI_ASSOC);

		echo json_encode($data[0]); die;
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

                        <div class="col-md-12 mb-4" onclick="app.goto('list_balik.php?baru=1')">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-1 font-weight-bold text-gray-800">Permohonan Balik (Baru)</div>
                                            <div class="h6 mb-0 font-weight-light text-gray-800"><span id="poll_balik">0</span> Permohonan Baru</div>
                                        </div>
                                        <div class="col-auto">
											<i class="fas fa-home fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4" onclick="app.goto('list_outing.php?baru=1')">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-1 font-weight-bold text-gray-800">Permohonan Outing (Baru)</div>
                                            <div class="h6 mb-0 font-weight-light text-gray-800"><span id="poll_outing">0</span> Permohonan Baru</div>
                                        </div>
                                        <div class="col-auto">
											<i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

						<div class="col-md-12 mb-4" onclick="app.goto('list_stud.php')">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Senarai Nama Pelajar</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
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
					poll_balik.innerHTML = res.A
					poll_outing.innerHTML = res.B
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