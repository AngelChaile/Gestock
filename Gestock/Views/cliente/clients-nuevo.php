<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Registrar Cliente</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 m-auto">
            <form id="frm-cliente" action="?c=clients&a=Guardar" method="post" autocomplete="off">
                <?php echo isset($alert) ? $alert : ''; ?>
                <div class="form-group">
                    <label for="cuil">Cuit/Cuil</label>
                    <input type="number" class="form-control" value="<?php echo $pvd->Cuil; ?>" placeholder="Ingrese cuit/cuil" name="Cuil" id="cuil">
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre y Apellido</label>
                    <input type="text" class="form-control" value="<?php echo $pvd->Nombre; ?>" placeholder="Ingrese su nombre y apellido" name="Nombre" id="nombre">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" value="<?php echo $pvd->Email; ?>" placeholder="Ingrese su email" name="Email" id="email">
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="number" class="form-control" value="<?php echo $pvd->Telefono; ?>" placeholder="Ingrese su teléfono" name="Telefono" id="telefono">
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" class="form-control" value="<?php echo $pvd->Direccion; ?>" placeholder="Ingrese su direccion" name="Direccion" id="direccion">
                </div>
                <div class="form-group">
                    <label for="numerodireccion">Número De Dirección</label>
                    <input type="number" class="form-control" value="<?php echo $pvd->NumeroDireccion; ?>" placeholder="Ingrese el número de dirección" name="NumeroDireccion" id="numerodireccion">
                </div>
                <input type="submit" value="Guardar Cliente" class="btn btn-warning">
                <a href="?c=clients&a=Mostrar" class="btn btn-info">Regresar</a>
            </form>
            <script>
                $(document).ready(function()
                {
                    $("#frm-cliente").submit(function(){
                        return $(this).validate();
                    });
                })
            </script>
        </div>
    </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->