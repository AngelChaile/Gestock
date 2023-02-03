<h1 class="page-header">Usuarios</h1>

<div class="container-fluid">
<div class="well well-sm text-right">
    <a class="btn btn-primary" href="?c=user&a=Nuevo">Nuevo Usuarios</a>
</div>
<br>
<?php echo isset($_SESSION['alert']) ? $_SESSION['alert'] : ''; unset($_SESSION['alert']); ?>
 <div class="table-responsive overflow-auto" style="height: 700px;">
    <table class="table table-hover table-striped">
    
    <thead class="thead-dark">
        <tr>
            <th style="width:120px;">Usuario</th>
            <th style="width:120px;">Nombre</th>
            <th style="width:120px;">Email</th>
            <th style="width:120px;">Rol</th>
            <th style="width:120px;">Estado</th>
            <th style="width:120px;">Acciones</th>
            
        </tr>
    </thead>
    </table>
<tbody>
 <div class="table-wrapper-scroll-y my-custom-scrollbar">
     <table class="table table-bordered table-striped mb-0">
     <?php foreach($this->model->Listar() as $r): ?>
        <tr>
            <td><?php echo $r->user_name; ?></td>
            <td><?php echo $r->name; ?></td>
            <td><?php echo $r->email; ?></td>
            <td><?php echo $r->description; ?></td>
            <td><?php echo $r->status; ?></td>

            
            <td>
                <a class="btn btn-warning" data-toggle="tooltip" title="Editar"href="?c=user&a=Crud&Id_user=<?php echo $r->Id_user; ?>"><i class="fas fa-edit" ></i></a>
            </td>
           
            <td>  
                <a href="?c=user&a=Eliminar&Id_user=<?php echo $r->Id_user; ?>" class="btn btn-secondary" data-toggle="tooltip" title="Cambiar Estado" ><i class="fas fa-trash" ></i></a>
            
            <?php if($r->id_rol !=1 ){ ?>
            </td>
            <td>
            
                <a class="btn btn-danger"data-toggle="tooltip" title="Reestablecer contraseña" onclick="javascript:return confirm('La contraseña de usuario se reestablecerá a 1234 ¿Desea continuar?');" href="?c=user&a=ResetPass&Id_user=<?php echo $r->Id_user; ?>"><i class="fas fa-key" ></i></a>
            </td><?php } ?>
       </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>