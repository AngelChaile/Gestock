<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Registrar Usuario</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 m-auto">
        <form id="frm-usuario" action="?c=user&a=Guardar" method="post" autocomplete="off">
        <?php echo isset($_SESSION['alert']) ? $_SESSION['alert'] : ''; unset($_SESSION['alert']); ?>
                <div class="form-group">
                    <label for="nombre">Nombre de Usuario</label>
                    <input type="text" value="<?php echo $pvd->Username; ?>" class="form-control" placeholder="Ingrese Usuario" name="Username" id="nombre" required>
                </div>
                <div class="form-group">
                    <label for="clave">Contraseña</label>
                    <input type="text" value="<?php echo $pvd->Userpass; ?>" class="form-control" placeholder="Ingrese una contraseña" name="UserPass" id="clave" required>
                </div>
                <div class="form-group">
                    <label for="usuario">Nombre y Apellido</label>
                    <input type="text" value="<?php echo $pvd->Name; ?>" class="form-control" placeholder="Ingrese su nombre y apellido" name="Name" id="usuario" required>
                </div>
                <div class="form-group">
                    <label for="correo">Email</label>
                    <input type="email" value="<?php echo $pvd->Email; ?>" class="form-control" placeholder="Ingrese su correo eléctronico" name="Email" id="correo" required>
                </div>
                <div class="form-group">
                    <label>Rol</label>
                    <select name="Rol" id="ol" class="form-control" value="<?php echo $pvd->Rol; ?>">
                    <?php
                        (new UserController()) -> ViewRol();       
                    ?>
                    </select></div>
                    <input type="submit" value="Guardar Usuario" class="btn btn-warning">
                <a href="?c=user&a=Mostrar" class="btn btn-primary">Regresar</a>
            </form>
        </div>
    </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
