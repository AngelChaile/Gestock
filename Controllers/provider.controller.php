<?php
require_once 'Models/provider.php';
class ProviderController{

    private $model;

    public function __CONSTRUCT(){
        
        session_start();
       $this->model = new Provider();
    }

    public function Mostrar(){
        $_SESSION['title'] = "Gestock-Proveedor";
        require_once 'Views/includes/header.php';
        require_once 'Views/proveedor/providers.php';
        require_once 'Views/includes/footer.php';
    }

    public function Crud()
    {
        $pvd = new Provider();
        if(isset($_REQUEST['id_provider'])){
            $pvd = $this->model->Obtener($_REQUEST['id_provider']);
        }

        require_once 'Views/includes/header.php';
        require_once 'Views/proveedor/proveedor-editar.php';
        require_once 'Views/includes/footer.php';
    }

    public function Nuevo(){

        require_once 'Views/includes/header.php';
        require_once 'Views/proveedor/formProvider.php';
        require_once 'Views/includes/footer.php';
    }

    public function Guardar()
    {
        $pvd = new Provider();

        $_REQUEST['NameProvider'] = $this->model->Sanitize($_REQUEST['NameProvider']);
        $_REQUEST['Cuil'] = $this->model->Sanitize($_REQUEST['Cuil']);
        $_REQUEST['Telefono'] = $this->model->Sanitize($_REQUEST['Telefono']);
        $_REQUEST['Calle'] = $this->model->Sanitize($_REQUEST['Calle']);
        $_REQUEST['Numero'] = $this->model->Sanitize($_REQUEST['Numero']);
        $_REQUEST['Email'] = $this->model->Sanitize($_REQUEST['Email']);

        if (
            !empty($_REQUEST['NameProvider']) &&
            !empty($_REQUEST['Cuil']) &&
            !empty($_REQUEST['Telefono']) &&
            !empty($_REQUEST['Calle']) &&
            !empty($_REQUEST['Numero']) &&
            !empty($_REQUEST['Email'])
        ) {
            $pvd->Nombre = $_REQUEST['NameProvider'];
            $pvd->Cuil = $_REQUEST['Cuil'];
            $pvd->Telefono = $_REQUEST['Telefono'];
            $pvd->Calle = $_REQUEST['Calle'];
            $pvd->Numero = $_REQUEST['Numero'];
            $pvd->Email = $_REQUEST['Email'];

            $ProviderExist = $this->model->ValidateAddProvider($pvd);
            $EmailExist = $this->model->EmailExist($pvd);

            if ($ProviderExist != null) 
            {
                $_SESSION['alert'] = '
                <div class="alert alert-danger" role="alert">
                    Ya se encuentra registrado un proveedor con el Cuil/Cuit '.$pvd->Cuil.'
                </div>';
                header('Location: ?c=provider&a=Nuevo');
            } else if ($EmailExist != null) 
            {
                $_SESSION['alert'] = '
                <div class="alert alert-danger" role="alert">
                Ya se encuentra registrado un proveedor con el email '.$pvd->Email.'
                </div>';
                header('Location: ?c=provider&a=Nuevo');
            } else
            {
                $this->model->Registrar($pvd);
                $_SESSION['alert'] = '<div class="alert alert-success" role="alert">
                El nuevo proveedor se ingresó correctamente
                </div>';
                header('Location: ?c=provider&a=Mostrar');
            }
        } else {
            $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
            Por favor use caracteres válidos y recuerde que los campos no pueden estar vacios.
            </div>';
            header('Location: ?c=provider&a=Nuevo');
        }
    }

    

    public function Editar()
    {
        $pvd = new Provider();

        $_REQUEST['NameProvider'] = $this->model->Sanitize($_REQUEST['NameProvider']);
        $_REQUEST['Cuil'] = $this->model->Sanitize($_REQUEST['Cuil']);
        $_REQUEST['Telefono'] = $this->model->Sanitize($_REQUEST['Telefono']);
        $_REQUEST['Calle'] = $this->model->Sanitize($_REQUEST['Calle']);
        $_REQUEST['Numero'] = $this->model->Sanitize($_REQUEST['Numero']);
        $_REQUEST['Email'] = $this->model->Sanitize($_REQUEST['Email']);

        //var_dump($_REQUEST['Numero'],$_REQUEST['Calle'],$_REQUEST['Telefono'],$_REQUEST['Cuil'],$_REQUEST['NameProvider']);exit;

        if 
        (!empty($_REQUEST['NameProvider']) &&
        !empty($_REQUEST['Cuil']) &&
        !empty($_REQUEST['Telefono']) &&
        !empty($_REQUEST['Calle']) &&
        !empty($_REQUEST['Numero']) &&
        !empty($_REQUEST['Email']))
        {
            $pvd->Id = $_REQUEST['id'];
            $pvd->Nombre = $_REQUEST['NameProvider'];
            $pvd->Cuil = $_REQUEST['Cuil'];
            $pvd->Telefono = $_REQUEST['Telefono'];
            $pvd->Calle = $_REQUEST['Calle'];
            $pvd->Numero = $_REQUEST['Numero'];
            $pvd->Email = $_REQUEST['Email'];

            $ProviderExist = $this->model->ValidateAddProvider($pvd);
            $EmailExist = $this->model->EmailExist($pvd);

            //var_dump($EmailExist->id_provider,$pvd->Id);exit;

            if($ProviderExist->id_provider == $pvd->Id || $ProviderExist->id_provider == null)
            {
                if ($EmailExist->id_provider == $pvd->Id || $EmailExist->id_provider == null)
                {
                    $this->model->Actualizar($pvd);
                    $_SESSION['alert'] = '<div class="alert alert-success" role="alert">
                    Los datos se actualizaron correctamente
                    </div>';
                    header('Location: ?c=provider&a=Mostrar');
                }else
                {
                    $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                    Ya se encuentra registrado un proveedor con el email '.$pvd->Email.'
                    </div>';
                    header('Location: ?c=provider&a=Crud&id_provider='.$pvd->Id);
                } 
            }
            else
            {
                $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                Ya se encuentra regitrado un proveedor con el  Cuil/Cuit '.$pvd->Cuil.'
                </div>';
                header('Location: ?c=provider&a=Crud&id_provider='.$pvd->Id);
            }
        }else
        {
            $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
            Por favor use caracteres válidos y recuerde que los campos no pueden estar vacíos.
            </div>';
            header('Location: ?c=provider&a=Crud&id_provider='.$pvd->Id);
        }
        
    }


    //Método que actualiza el estado activo/inactivo con el id_provider
    public function Eliminar(){
        $id_provider = $_REQUEST['id_provider'];
        $newStatus = 1;
        if($this->model->Obtener($id_provider)->id_state == 1) {
            $newStatus = 2;
        }
        $this->model->Eliminar($id_provider, $newStatus);
        header('Location: ?c=provider&a=Mostrar');
    }
    public function BuscarPorCUITCUIL($number) {
        return $this->model->BuscarPorCUITCUIL($number);
    }
}
?>