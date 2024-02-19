<h1 class="page-header">Producto</h1>

<div class="container-fluid">
    <div class="well well-sm text-right">
        <a class="btn btn-primary" href="?c=product&a=Nuevo">Nuevo Producto</a>
    </div>
    <br>
        <table class="table table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="col-lg-1">Producto</th>
                    <th class="col-lg-5">Nombre</th>
                    <th class="col-lg-1">Cantidad</th>
                    <th class="col-lg-1">Acciones</th>
                </tr>
            </thead>
        </table>
    <tbody>
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-bordered table-striped mb-0">
               <?php foreach($this->model->Listar() as $r): ?>
                  <tr>
                    <td><?php echo $r->id_product; ?></td>
                    <td><?php echo $r->description; ?></td>
                    <td><?php echo $r->qty; ?></td>
                    <td>
                        <a class="btn btn-primary" data-toggle="tooltip" title="Editar" href="?c=product&a=Crud&id_product=<?php echo $r->id_product; ?>"><i class="fas fa-edit" ></i></a>
                        
                        <a href="?c=product&a=Eliminar&id_product=<?php echo $r->id_product; ?>" class="btn btn-warning"data-toggle="tooltip" title="Cambiar Estado" ><i class="fas fa-trash" ></i></a>
                    </td>
                 </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>