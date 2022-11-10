<!-- Begin Page Content -->
<div class="container-fluid">
<h2>Editar Cliente</h2>
  <div class="row">
    <div class="col-lg-6 m-auto">
      <form action="?c=clients&a=Editar" method="post">
        <?php echo isset($alert) ? $alert : ''; ?>
        <input type="hidden" name="id" value="<?php echo $pvd->id_customer; ?>">
        <div class="form-group">
          <label for="nombresocial">Nombre y Apellido</label>
          <input type="text"  class="form-control" name="Nombre" id="nombresocial" value="<?php echo $pvd->customer_name; ?>" >

          <div class="form-group">
          <label for="telefono">Email</label>
          <input type="email" class="form-control" name="Email" id="email" value="<?php echo $pvd->email; ?>" >

        </div>
        <div class="form-group">
          <label for="calle">Teléfono</label>
          <input type="number" class="form-control" name="Telefono" id="calle" value="<?php echo $pvd->tel_number; ?>" >
        </div>

        <div class="form-group">
          <label for="numero">Dirección</label>
          <input type="text" class="form-control" name="Direccion" id="numero" value="<?php echo $pvd->street; ?>" >
        </div>

        <div class="form-group">
          <label for="numero">Número</label>
          <input type="number" class="form-control" name="NumeroDireccion" id="numero"  value="<?php echo $pvd->address_number; ?>">
        </div>
     
        <button type="submit" class="btn btn-warning"><i class="fas fa-user-edit"></i> Editar Cliente</button>
        <a href="?c=clients&a=Mostrar" class="btn btn-info">Regresar</a>
      </form>
    </div>
  </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->