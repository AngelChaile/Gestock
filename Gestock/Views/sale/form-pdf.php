<div class="container-fluid">
<h2>ENVIAR PDF</h2>
  <div class="row">
    <div class="col-lg-6 m-auto">
      <form name="formulario" id="formulario" action="?c=movement&a=EnviarPDF" method="post" enctype="multipart/form-data">
        <!--<?php /*echo isset($alert) ? $alert : '';*/ ?>
        <input type="hidden" name="id" value="<?php /*echo $pvd->id_movement; */?>"-->
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text"  class="form-control" name="nombre" value="<?php echo $movement->customer_name; ?>">
          <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" name="email" value="<?php echo $movement->email; ?>" >
        </div>

        <div class="form-group">
          
          <div>
            <span>PDF</span>
          </div>

          <div>
            <input type="file" name="my_file" id="my_file" class="form-control">
          </div> 

        </div>

     
        <button type="submit" class="btn btn-warning">Enviar PDF</button>
        <a href="?c=sale&a=Mostrar" class="btn btn-info">Regresar</a>
      </form>
    </div>

  </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
