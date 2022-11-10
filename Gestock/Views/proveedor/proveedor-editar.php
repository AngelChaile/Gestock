<!-- Begin Page Content -->
<div class="container-fluid">
<h2>Editar Proveedor</h2>
  <div class="row">
    <div class="col-lg-6 m-auto">
      <form action="?c=provider&a=Editar" method="post">
        <input type="hidden" name="id" value="<?php echo $pvd->id_provider; ?>">
        <div class="form-group">
          <label for="nombre">Nombre de Proveedor</label>
          <input type="text" placeholder="Ingrese nombre de Proveedor" class="form-control" name="Username" id="nombre" value="<?php echo $pvd->company_name; ?>">

        <div class="form-group">
          <label for="cuil">Cuil</label>
          <input type="text" placeholder="Ingrese su cuil" class="form-control" name="Cuil" id="cuil" value="<?php echo $pvd->cuit_cuil; ?>">
        </div>

        <div class="form-group">
          <label for="telefono">Teléfono</label>
          <input type="text" placeholder="Ingrese su teléfono" class="form-control" name="Telefono" id="telefono" value="<?php echo $pvd->tel_number; ?>">
        </div>

        <div class="form-group">
          <label for="calle">Ingrese dirección</label>
          <input type="text" placeholder="Ingrese su domicilio" class="form-control" name="Calle" id="calle" value="<?php echo $pvd->street; ?>">
        </div>

        <div class="form-group">
          <label for="calle">Número</label>
          <input type="text" placeholder="Ingrese su número" class="form-control" name="Numero" id="numero" value="<?php echo $pvd->number; ?>">
        </div>

        <button type="submit" class="btn btn-warning"><i class="fas fa-user-edit"></i> Editar Proveedor</button>
        <a href="?c=provider&a=Mostrar" class="btn btn-info">Regresar</a>
      </form>
    </div>
  </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
