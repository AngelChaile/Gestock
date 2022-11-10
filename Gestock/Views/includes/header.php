<?php 
	session_start();
	if (!isset($_SESSION['valido']))
	{
		header('Location: ?c=user&a=Login');
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

	<title><?php echo $_SESSION['title']; ?></title>
	<link rel="shortcut icon" href="Assets/img/logo.jpeg">

	<!-- Custom styles for this template-->
	<link href="Assets/css/styles.css" rel="stylesheet">
	<link href="Assets/css/sb-admin-2.min.css" rel="stylesheet">
	<link rel="stylesheet" href="Assets/css/dataTables.bootstrap4.min.css">

    <link href="Assets/css/ribbon.css" rel="stylesheet">
    <link href="Assets/css/sale.css" rel="stylesheet">
    <link href="Assets/css/fontawesome.min.css" rel="stylesheet">
    <script src="Assets/js/producto.js"></script>
    <!--<script>
        document.addEventListener('contextmenu', (e)=> {
            e.preventDefault();
        });

        document.addEventListener('keydown', (e)=> {
            if(e.keyCode === 123) {
                e.preventDefault();
                return false;
            }
        });
    </script>-->
</head>

<body id="page-top">
	
	<!-- Page Wrapper -->
	<div id="wrapper">
		<?php include_once 'main.php'; ?>
		<?php include_once "functions.php"; TiempoSession();?>
		<?php //include_once "Controllers/functions.php"; ?>
		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">
				<!-- Topbar -->
				<nav class="navbar navbar-expand navbar-light bg-primary text-white topbar mb-4 static-top shadow">

					<!-- Sidebar Toggle (Topbar) -->
					<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
						<i class="fa fa-bars"></i>
					</button>
					<div class="input-group">
						<h6>Sistema de Venta</h6>
						<p class="ml-auto"><strong>Buenos Aires, </strong><?php echo fechaArgentina(); ?></p>
					</div>

					<!-- Topbar Navbar -->
					<ul class="navbar-nav ml-auto">

						<div class="topbar-divider d-none d-sm-block"></div>

						<!-- Nav Item - User Information -->
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline small text-white"><?php echo $_SESSION['valido']->name ?></span>
							</a>
							<!-- Dropdown - User Information -->
							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
								<a class="dropdown-item" href="?c=user&a=PassView">
									<i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
									Configurar Contraseña
								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="?c=user&a=Logout">
									<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
									Salir
								</a>
							</div>
						</li>

					</ul>

				</nav>
