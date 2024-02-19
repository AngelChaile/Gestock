<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card-header bg-primary text-white">
                Carga de Proveedor
            </div>
            <div class="card">
                <form action="?c=provider&a=Guardar" autocomplete="off" method="post" class="card-body p-2">
                    <?php echo isset($_SESSION['alert']) ? $_SESSION['alert'] : ''; unset($_SESSION['alert']); ?>
                    <div class="form-group">
                        <label for="nombre">NOMBRE / RAZÓN SOCIAL</label>
                        <input type="text" value="<?php echo $pvd->Nombre; ?>" placeholder="Ingrese su nombre o razón social" name="NameProvider" id="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="contacto">CUIT / CUIL</label>
                        <input type="number" value="<?php echo $pvd->Cuil; ?>" placeholder="Ingrese su CUIT o CUIL" name="Cuil" id="contacto" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">TELÉFONO</label>
                        <input type="number" value="<?php echo $pvd->Telefono; ?>" placeholder="Ingrese teléfono" name="Telefono" id="telefono" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion">CALLE</label>
                        <input type="text" value="<?php echo $pvd->Calle; ?>" placeholder="Ingrese su calle" name="Calle" id="direcion" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion">NÚMERO</label>
                        <input type="number" value="<?php echo $pvd->Numero; ?>" placeholder="Ingrese su número" name="Numero" id="direcion" class="form-control" required>
                    </div>
                    <input type="submit" value="Guardar Proveedor" class="btn btn-warning">
                    <a href="?c=provider&a=Mostrar" class="btn btn-info">Regresar</a>
                </form>
            </div>
        </div>
    </div>
</div>