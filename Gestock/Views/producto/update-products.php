<!-- Begin Page Content -->
<div class="container-fluid">
<h2>Editar Producto</h2>
  <div class="row">
    <div class="col-lg-6 m-auto">
      <form action="?c=product&a=Editar" method="post">
        <?php echo isset($alert) ? $alert : ''; ?>
        <input type="hidden" name="id" value="<?php echo $pvd->id_product; ?>">
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text"  class="form-control" name="Nombre" id="nombre" value="<?php echo $pvd->description; ?>" >
 <div class="form-group">
          <label for="precio">Precio</label>
          <input type="text" class="form-control" name="precio" id="precio" value="<?php echo $pvd->price; ?>" >

        </div>
        <div class="form-group">
          <label for="cantidad">Cantidad</label>
          <input type="text" class="form-control" name="Cantidad" id="cantidad" value="<?php echo $pvd->qty; ?>" >
        </div>

        <div class="form-group">
          <label>CATEGORIA</label>
          <select name="categoria" id="categoria" class="form-control" value="<?php echo $pvd->id_category; ?>">
           <?php
              (new ProductController()) -> ViewCategory();       
           ?>
        </select></div>

        <div class="form-group">
          <label>IVA</label>
              <select name="iva" id="iva" class="form-control" value="<?php echo $pvd->iva; ?>">
                <?php
                        (new ProductController()) -> ViewTax();       
                  ?>
              </select>
        </div>        



        <div class="form-group">
          <label for="marca">Marca</label>
          <input type="text" class="form-control" name="Marca" id="marca"  value="<?php echo $pvd->brand; ?>">
        </div>
        
        <div class="form-group">
          <label for="codbarra">Código de barra</label>
          <input type="text" class="form-control" name="codbarra" id="codbarra"  value="<?php echo $pvd->barcode; ?>">
        </div>
     
        <button type="submit" class="btn btn-warning"><i class="fas fa-user-edit"></i> Editar Producto</button>
        <a href="?c=product&a=Mostrar" class="btn btn-info">Regresar</a>
      </form>
    </div>
  </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
