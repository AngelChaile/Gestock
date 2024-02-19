<h1 class="page-header">Clientes</h1>
<div class="container-fluid">
<div class="well well-sm text-right">
    <a class="btn btn-primary" href="?c=clients&a=Nuevo">Nuevo Cliente</a>
</div>
<br>
     <table class="table table-hover table-striped mb-0">
       <thead class="thead-dark">
        <tr>
            <th style="width:120px;">Cuil</th>
            <th style="width:120px;">Nombre</th>
            <th style="width:120px;">Email</th>
            <th style="width:120px;">Teléfono</th>
            <th style="width:120px;">Dirección</th>
            <th style="width:120px;">Número</th>
            <th style="width:100px;">Acciones</th>
        </tr>
    </thead>
</table>
<tbody>
<div class="table-wrapper-scroll-y my-custom-scrollbar">
  <table class="table table-bordered table-striped mb-0">
      <?php foreach($this->model->Listar() as $r): ?>
          <tr>
            <td><?php echo $r-> cuit_cuil; ?></td>
            <td><?php echo $r->customer_name; ?></td>
            <td><?php echo $r->email; ?></td>
            <td><?php echo $r->tel_number; ?></td>
            <td><?php echo $r->street; ?></td>
            <td><?php echo $r->address_number; ?></td>
            <td><?php echo $r->estado; ?></td>
            <td>
                <a class="btn btn-primary"data-toggle="tooltip" title="Editar" href="?c=clients&a=Crud&id_customer=<?php echo $r->id_customer; ?>"><i class=" fas fa-edit"></i></a>
            </td>
           <!-- <td>
                <a href="?c=clients&a=Eliminar&id_customer=<?php echo $r->id_customer; ?>">Eliminar</a>
            </td>-->
            <td>
                 <a href="?c=clients&a=Eliminar&id_customer=<?php echo $r->id_customer; ?>" class="btn btn-warning" data-toggle="tooltip" title="Cambiar Estado" ><i class=" fas fa-trash"></i></a>
            </td>
           </tr>
       <?php endforeach; ?>
     </tbody>
    </table>
   </div>  
</div>
