<div class="container-fluid">
<div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray">Panel de Administración</h1>
    </div>
	<link href="../Assets/css/style.css" rel="stylesheet">
    <div class="row">
	<?php if ($_SESSION['valido']->id_rol == 1) { ?>
        <a class="col-xl-3 col-md-6 mb-4" href="?c=user&a=Mostrar">
            <div class="card border-left-primary shadow h-100 py-2 bg-warning my-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Usuarios</div>
                            <div class="h5 mb-0 font-weight-bold text-white"><?php $user = new Users(); echo $user->quantity('User'); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
		<?php }?>
        
        <a class="col-xl-3 col-md-6 mb-4" href="?c=clients&a=Mostrar">
            <div class="card border-left-success shadow h-100 py-2 bg-success my-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Clientes</div>
                            <div class="h5 mb-0 font-weight-bold text-white"><?php $user = new Users(); echo $user->quantity('customers'); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        

       <a class="col-xl-3 col-md-6 mb-4" href="?c=product&a=Mostrar">

            <div class="card border-left-info shadow h-100 py-2 bg-primary my-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Productos</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-white"><?php $user = new Users(); echo $user->quantity('products'); ?></div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        
        <a class="col-xl-3 col-md-6 mb-4" href="?c=sale&a=Mostrar">
            <div class="card border-left-warning bg-danger shadow h-100 py-2 my-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Entradas del día</div>
                            <div class="h5 mb-0 font-weight-bold text-white"><?php $user = new Users(); echo $user->quantityDay('movement', 1); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
		<a class="col-xl-3 col-md-6 mb-4" href="?c=sale&a=Mostrar">
            <div class="card border-left-warning bg-danger shadow h-100 py-2 my-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
							<div class="text-xs font-weight-bold text-white text-uppercase mb-1">Salidas del día</div>
                            <div class="h5 mb-0 font-weight-bold text-white"><?php $user = new Users(); echo $user->quantityDay('movement', 2); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
	</div>

	

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Configuración</h1>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-header bg-primary text-white">
					Información Personal
				</div>
				<div class="card-body">
					<div class="form-group">
						<label>Nombre: <strong><?php echo $_SESSION['valido']->name; ?></strong></label>
					</div>
					<div class="form-group">
						<label>Correo: <strong><?php echo $_SESSION['valido']->email; ?></strong></label>
					</div>
					<div class="form-group">
					<label>Rol: <strong><?php if ($_SESSION['valido']->id_rol == 1)
						{
							echo "Administrador";
						}else if ($_SESSION['valido']->id_rol == 2) {
							echo "Vendedor";
						};?></strong></label>
					</div>
					<div class="form-group">
						<label>Usuario: <strong><?php echo $_SESSION['valido']->user_name; ?></strong></label>
					</div>
				</div>
			</div>
		</div>
		<?php if ($_SESSION['valido']->id_rol == 1) { ?>
			<div class="col-lg-6">
				<div class="card">
						<div class="card-header bg-primary text-white">
							Datos de la Empresa
						</div>
						<form action="?c=user&a=EditInfoCompany" method="post">
							<div class="form-group">
								<label>Nombre:</label>
								<input type="text" name="txtNombre" class="form-control" value="<?php echo $_SESSION['infoCompany']->name; ?>" id="txtNombre" placeholder="Nombre de la Empresa" required class="form-control">
							</div>
							<div class="form-group">
								<label>Razon Social:</label>
								<input type="text" name="txtRSocial" class="form-control" value="<?php echo $_SESSION['infoCompany']->company_name; ?>" id="txtRSocial" placeholder="Razon Social de la Empresa">
							</div>
							<div class="form-group">
								<label>Cuit/Cuil:</label>
								<input type="text" name="txtCuit" class="form-control" value="<?php echo $_SESSION['infoCompany']->cuit; ?>" id="txtCuit" placeholder="Cuit de la empresa">
							</div>
							<div class="form-group">
								<label>Teléfono:</label>
								<input type="number" name="txtTelEmpresa" class="form-control" value="<?php echo $_SESSION['infoCompany']->tel_number; ?>" id="txtTelEmpresa" placeholder="teléfono de la Empresa" required>
							</div>
							<div class="form-group">
								<label>Correo Electrónico:</label>
								<input type="email" name="txtEmailEmpresa" class="form-control" value="<?php echo $_SESSION['infoCompany']->email; ?>" id="txtEmailEmpresa" placeholder="Correo de la Empresa" required>
							</div>
							<div class="form-group">
								<label>Dirección:</label>
								<input type="text" name="txtDirEmpresa" class="form-control" value="<?php echo $_SESSION['infoCompany']->address; ?>" id="txtDirEmpresa" placeholder="Direción de la Empresa" required>
							</div>
							<?php echo isset($_SESSION['alert']) ? $_SESSION['alert'] : ''; unset($_SESSION['alert']); ?>
							<div>
								<button type="submit" class="btn btn-primary btnChangePass"><i class="fas fa-save"></i> Guardar Datos</button>
							</div>

						</form>
					</div>
				</div>
			</div>
		<?php } else { ?>
		<div class="col-lg-6">
				<div class="card">
						<div class="card-header bg-primary text-white">
							Datos de la Empresa
						</div>
						<div>
							<div class="form-group">
								<strong>Nombre:</strong>
								<h6><?php echo $_SESSION['infoCompany']->name; ?></h6>
							</div>
							<div class="form-group">
								<strong>Razon Social:</strong>
								<h6><?php echo $_SESSION['infoCompany']->company_name; ?></h6>
							</div>
							<div class="form-group">
								<strong>Teléfono:</strong>
								<?php echo $_SESSION['infoCompany']->tel_number; ?>
							</div>
							<div class="form-group">
								<strong>Correo Electrónico:</strong>
								<h6><?php echo $_SESSION['infoCompany']->email; ?>
							</div>
							<div class="form-group">
								<strong>Dirección:</strong>
								<h6><?php echo $_SESSION['infoCompany']->address; ?>
							</div>
							
						</div>
				</div>
			</div>
		</div>
		<?php } ?>
		
	
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->