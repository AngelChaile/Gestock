
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">


    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="?c=user&a=Index">
        <div class="sidebar-brand-icon rotate-n-15">
            <img src="Assets/img/logo.jpeg" class="img-thumbnail">
        </div>
        <div class="sidebar-brand-text mx-4">Gestock</div>
    </a>


    <hr class="sidebar-divider my-0">


    <hr class="sidebar-divider">


    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Ventas Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSale" aria-expanded="true" aria-controls="collapseSale">
            <i class="fas fa-fw fa-cog"></i>
            <span>Movimientos</span>
        </a>
        <div id="collapseSale" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="?c=sale&a=Nuevo">Nuevo Movimiento</a>
                <a class="collapse-item" href="?c=sale&a=Mostrar">Movimientos</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Productos Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Productos</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!--<a class="collapse-item" href="?c=product&a=Nuevo">Nuevo Producto</a>-->
                <a class="collapse-item" href="?c=product&a=Mostrar">Productos</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Clientes Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClientes" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-users"></i>
            <span>Clientes</span>
        </a>
        <div id="collapseClientes" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="?c=clients&a=Nuevo">Nuevo Cliente</a>
                <a class="collapse-item" href="?c=clients&a=Mostrar">Clientes</a>
            </div>
        </div>
    </li>
    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProveedor" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-hospital"></i>
            <span>Proveedores</span>
        </a>
        <div id="collapseProveedor" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="?c=provider&a=Nuevo">Nuevo Proveedor</a>
                <a class="collapse-item" href="?c=provider&a=Mostrar">Proveedores</a>
            </div>
        </div>
    </li>
    <?php if ($_SESSION['valido']->id_rol == 1) { ?>
        <!-- Nav Item - Usuarios Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsuarios" aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-user"></i>
                <span>Usuarios</span>
            </a>
            <div id="collapseUsuarios" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="?c=user&a=Nuevo">Nuevo Usuario</a>
                    <a class="collapse-item" href="?c=user&a=Mostrar">Usuarios</a>
                </div>
            </div>
        </li>
    <?php } ?>

</ul>