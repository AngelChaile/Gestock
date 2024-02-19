<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card-header bg-primary text-white">
                Carga de Producto
            </div>
            <div class="card">
                <form action="?c=product&a=Guardar" autocomplete="off" method="post" class="card-body p-2">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="nombre">NOMBRE DEL PRODUCTO</label>
                        <input type="text" placeholder="Ingrese su nombre " value="<?php echo $pvd->description; ?>" name="producto" id="nombre" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="precio">PRECIO</label>
                        <input type="number" placeholder="Ingrese precio" value="<?php echo $pvd->price; ?>" name="precio" id="precio" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">CANTIDAD</label>
                        <input type="number" placeholder="Ingrese cantidad de productoS"  value="<?php echo $pvd->qty; ?>" name="cantidad" id="cantidad" class="form-control" required>
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
                    </select></div>

                    <div class="form-group">
                        <label for="marca">MARCA</label>
                        <input type="text" placeholder="Ingrese marca" value="<?php echo $pvd->brand; ?>" name="marca" id="marca" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="codbarra">CÓDIGO DE BARRA</label>
                        <input type="number" placeholder="Ingrese código de barra" value="<?php echo $pvd->barcode; ?>" name="codigo" id="codigo" class="form-control" required>
                    </div>
                    <input type="submit" value="Guardar Producto" class="btn btn-primary">
                    <a href="?c=product&a=Mostrar" class="btn btn-info">Regresar</a>
            </form>
            </div>
        </div>
    </div>
</div>
