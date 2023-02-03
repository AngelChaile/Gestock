<?php  
  if ($pvd->id_rol == 1) $admin = 'selected'; 
  if ($pvd->id_rol == 2) $vendor = 'selected';
  if ($pvd->estado == 1) $active = 'selected'; 
  if ($pvd->estado == 2) $inactive = 'selected';
?>
<div class="container-fluid">
<h2>Editar Usuario</h2>
  <div class="row">
    <div class="col-lg-6 m-auto">
      
      <form action="?c=user&a=Editar" method="post">
        <input type="hidden" name="id" value="<?php echo $pvd->Id_user; ?>">
        <div class="form-group">
          <label for="nombre">Nombre de Usuario</label>
          <input type="text" placeholder="Ingrese nombre de usuario" class="form-control" name="Username" id="nombre" value="<?php echo $pvd->user_name; ?>">

          <div class="form-group">
          <label for="nombre">Nombre y Apellido</label>
          <input type="text" placeholder="Ingrese su nombre y apellido" class="form-control" name="Name" id="nombre" value="<?php echo $pvd->name; ?>">

        </div>
        <div class="form-group">
          <label for="correo">Email</label>
          <input type="email" placeholder="Ingrese su email" class="form-control" name="Email" id="correo" value="<?php echo $pvd->email; ?>">


        </div>
        <div class="form-group">
          <label for="rol">Rol</label>
          <select name="Rol" id="rol" class="form-control" value="<?php echo $pvd->id_rol ?>">
          <?php
                        (new UserController()) -> ViewRol($pvd->id_rol);       
          ?>
          </select>
          
        </div>
        <button type="submit" class="btn btn-warning"><i class="fas fa-user-edit"></i> Editar Usuario</button>
        <a href="?c=user&a=Mostrar" class="btn btn-info">Regresar</a>
      </form>
    </div>
  </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->