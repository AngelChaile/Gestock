<h1 class="page-header">Proveedores</h1>

<div class="container-fluid">
<div class="well well-sm text-right">
    <a class="btn btn-primary" href="?c=provider&a=Nuevo">Nuevo Proveedor</a>
</div>
<br>
   <?php echo isset($_SESSION['alert']) ? $_SESSION['alert'] : ''; unset($_SESSION['alert']); ?>
        <table class="table table-hover table-striped mb-0">
           <thead class="thead-dark">
             <tr>
                <th style="width:180px;">Nombre/Razón Social</th>
                <th style="width:120px;">Cuit/Cuil</th>
                <th style="width:120px;">Teléfono</th>
                <th style="width:120px;">Calle</th>
                <th style="width:120px;">Número</th>
                 <th style="width:120px;">Acciones</th>
              </tr>
            </thead>
        </table>
<tbody>
    <div class="table-wrapper-scroll-y my-custom-scrollbar">
         <table class="table table-bordered table-striped mb-0">
         <?php foreach($this->model->Listar() as $r): ?>
        <tr>
            <td><?php echo $r->company_name; ?></td>
            <td><?php echo $r->cuit_cuil; ?></td>
            <td><?php echo $r->tel_number; ?></td>
            <td><?php echo $r->street; ?></td>
            <td><?php echo $r->number; ?></td>
            <td><?php echo $r->estado; ?></td>
            <td>
                <a class="btn btn-primary" data-toggle="tooltip" title="Editar" href="?c=provider&a=Crud&id_provider=<?php echo $r->id_provider; ?>"><i class="fas fa-edit" ></i></a>
            </td> 
            <td>   
                <a href="?c=provider&a=Eliminar&id_provider=<?php echo $r->id_provider; ?>" class="btn btn-warning" data-toggle="tooltip" title="Cambiar Estado" ><i class="fas fa-trash" ></i></a>
            </td>
            <!--<td>
                <a href="?c=provider&a=Eliminar&id_provider=<?php echo $r->id_provider; ?>">Eliminar</a>
            </td>-->
        </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
         