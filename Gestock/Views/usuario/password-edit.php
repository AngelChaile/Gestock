<!-- Begin Page Content -->
<div class="container-fluid">
<h2>Editar Contraseña</h2>
  <div class="row">
    <div class="col-lg-6 m-auto">
      <form action="?c=user&a=PasswordGuardar" method="post">
        <?php echo isset($_SESSION['alert']) ? $_SESSION['alert'] : ''; unset($_SESSION['alert']); ?>
		<input type="hidden" name="id" value="<?php echo $_SESSION['valido']->Id_user; ?>">
        <div class="form-group">
          <label for="nombre">Contraseña Actual</label>
          <input type="password" placeholder="Ingrese contraseña actual" class="form-control" name="Actual" value="<?php echo $pvd->Actual; ?>">

          <div class="form-group">
          <label for="nombre">Contraseña Nueva</label>
          <input type="password" placeholder="Ingrese la nueva contraseña" class="form-control" name="Nueva" value="<?php echo $pvd->Nueva; ?>">

        </div>
        <div class="form-group">
          <label for="correo">Confirmar</label>
          <input type="password" placeholder="Ingrese otra vez la nueva contraseña" class="form-control" name="Confirmar" value="<?php echo $pvd->Confirmar; ?>">
	<br>
        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Contraseña</button>
      </form>
    </div>
  </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->