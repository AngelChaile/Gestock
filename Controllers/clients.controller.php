<?php
session_start();

require_once 'Models/clients.php';

class ClientsController{

    private $model;


    public function __CONSTRUCT(){
        session_start();
       $this->model = new Clients();
    }


    public function Index(){
        require_once 'View/includes/header.php';
        require_once 'View/Home.php';
        require_once 'View/includes/footer.php';
    }

    public function Login(){
        require_once 'View/includes/header.php';
        require_once 'View/Login.php';
        require_once 'View/includes/footer.php';
    }
    public function Mostrar(){
        require_once 'Views/includes/header.php';
        require_once 'Views/cliente/clients.php';
        require_once 'Views/includes/footer.php';
    }




    public function Crud(){
        $pvd = new Clients();

        //Se obtienen los datos del cliente a editar.
        if(isset($_REQUEST['id_customer'])){
            $pvd = $this->model->Obtener($_REQUEST['id_customer']);
        }


        require_once 'Views/includes/header.php';
        require_once 'Views/cliente/update-clients.php';
        require_once 'Views/includes/footer.php';
  }

    //Llamado a la vista votante-nuevo
    public function Nuevo(){

        //Llamado de las vistas.
        require_once 'Views/includes/header.php';
        require_once 'Views/cliente/clients-nuevo.php';
        require_once 'Views/includes/footer.php';
    }

    public function Guardar(){
        $pvd = new Clients();

        $_REQUEST['Cuil'] = $this->model->Sanitize($_REQUEST['Cuil']);
        $_REQUEST['Nombre'] = $this->model->Sanitize($_REQUEST['Nombre']);
        $_REQUEST['Email'] = $this->model->Sanitize($_REQUEST['Email']);
        $_REQUEST['Telefono'] = $this->model->Sanitize($_REQUEST['Telefono']);
        $_REQUEST['Direccion'] = $this->model->Sanitize($_REQUEST['Direccion']);
        $_REQUEST['NumeroDireccion'] = $this->model->Sanitize($_REQUEST['NumeroDireccion']);

        if (!empty($_REQUEST['Cuil']) &&
            !empty($_REQUEST['Nombre']) &&
            !empty($_REQUEST['Email']) &&
            !empty($_REQUEST['Telefono']) &&
            !empty($_REQUEST['Direccion']) &&
            !empty($_REQUEST['NumeroDireccion']))
            {
                $pvd->Cuil = $_REQUEST['Cuil'];
                $pvd->Nombre = $_REQUEST['Nombre'];
                $pvd->Email = $_REQUEST['Email'];
                $pvd->Telefono = $_REQUEST['Telefono'];
                $pvd->Direccion = $_REQUEST['Direccion'];
                $pvd->NumeroDireccion = $_REQUEST['NumeroDireccion'];

                $ClientExist = $this->model->ValidateAddClient($pvd);
                $EmailExist = $this->model->EmailExist($pvd);

                if ($ClientExist != null)
                {
                    $_SESSION['alert'] = '
                    <div class="alert alert-danger" role="alert">
                    Ya se encuentra registrado un Cliente con el Cuil/Cuit '.$pvd->Cuil.'
                    </div>';
                    header('Location: ?c=clients&a=Nuevo');
                }else if($EmailExist != null)
                {
                    $_SESSION['alert'] = '
                    <div class="alert alert-danger" role="alert">
                    Ya se encuentra registrado un Cliente con el email '.$pvd->Email.'
                    </div>';
                    header('Location: ?c=clients&a=Nuevo');
                }else
                {
                    $this->model->Registrar($pvd);
                    $_SESSION['alert'] = '
                    <div class="alert alert-success" role="alert">
                    Los datos se registraron correctamente!
                    </div>';
                    header('Location: ?c=clients&a=Mostrar');
                }   
            }else
            {
                $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                Por favor use caracteres válidos y recuerde que los campos no pueden estar vacíos.
                </div>';
                header('Location: ?c=clients&a=Nuevo');
            }
        
    }


    public function Editar()
    {
        $pvd = new Clients();

        $_REQUEST['Cuil'] = $this->model->Sanitize($_REQUEST['Cuil']);
        $_REQUEST['Nombre'] = $this->model->Sanitize($_REQUEST['Nombre']);
        $_REQUEST['Email'] = $this->model->Sanitize($_REQUEST['Email']);
        $_REQUEST['Telefono'] = $this->model->Sanitize($_REQUEST['Telefono']);
        $_REQUEST['Direccion'] = $this->model->Sanitize($_REQUEST['Direccion']);
        $_REQUEST['NumeroDireccion'] = $this->model->Sanitize($_REQUEST['NumeroDireccion']);
        
        
        if 
        (!empty($_REQUEST['Cuil']) &&
        !empty($_REQUEST['Nombre']) &&
        !empty($_REQUEST['Email']) &&
        !empty($_REQUEST['Telefono']) &&
        !empty($_REQUEST['Direccion']) &&
        !empty($_REQUEST['NumeroDireccion']))
        {
            $pvd->Id = $_REQUEST['id'];
            $pvd->Cuil = $_REQUEST['Cuil'];
            $pvd->Nombre = $_REQUEST['Nombre'];
            $pvd->Email = $_REQUEST['Email'];
            $pvd->Telefono = $_REQUEST['Telefono'];
            $pvd->Direccion = $_REQUEST['Direccion'];
            $pvd->NumeroDireccion = $_REQUEST['NumeroDireccion'];

            $ClientExist = $this->model->ValidateAddClient($pvd);
            $EmailExist = $this->model->EmailExist($pvd);

            if($ClientExist->id_customer == $pvd->Id || $ClientExist->id_customer == null)
            {
                if($EmailExist->id_customer == $pvd->Id || $EmailExist->id_customer == null)
                {
                    $this->model->Actualizar($pvd);
                    $_SESSION['alert'] = '<div class="alert alert-success" role="alert">
                    Los datos se actualizaron correctamente
                    </div>';
                    header('Location: ?c=clients&a=Mostrar');
                }else
                {
                    $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                    Ya se encuentra registrado un cliente con el email '.$pvd->Email.'
                    </div>';
                    header('Location: ?c=clients&a=Crud&id_customer='.$pvd->Id);
                }
            }else
            {
                $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                Ya se encuentra regitrado un cliente con el Cuit/Cuil '.$pvd->Cuil.'
                </div>';
                header('Location: ?c=clients&a=Crud&id_customer='.$pvd->Id);
            }
        }else
        {
            $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
            Por favor use caracteres válidos y recuerde que los campos no pueden estar vacíos.
            </div>';
            header('Location: ?c=clients&a=Crud&id_customer='.$pvd->Id);
        }
    }

    //Método que actualiza el estado activo/inactivo con el id_cliente.
    public function Eliminar(){
        $id_customer = $_REQUEST['id_customer'];
        $newStatus = 1;
        if($this->model->Obtener($id_customer)->id_state == 1) {
            $newStatus = 2;
        }
        $this->model->Eliminar($id_customer, $newStatus);
        header('Location: ?c=clients&a=Mostrar');
    }

    public function BuscarPorCUITCUIL($number) {
        $cliente = $this->model->BuscarPorCUITCUIL($number);

        $client = new Clients();
        $client->Id = $cliente->id_customer;
        $client->Cuil = $cliente->cuit_cuil;
        $client->Nombre = $cliente->customer_name;
        $client->Email = $cliente->email;
        $client->Telefono = $cliente->tel_number;
        $client->Direccion = $cliente->street;
        $client->NumeroDireccion = $cliente->address_number;

        return $cliente;
    }
}
?>