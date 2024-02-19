<?php
require_once 'Models/user.php';
require_once 'Models/company.php';

class UserController{

    private $model;
    private $companyModel;

    public function __CONSTRUCT(){
        session_start();
       $this->model = new Users();
       $this->companyModel = new Company();
    }
  
    public function Index(){
        $_SESSION['title'] =  "Gestock - Home";
        require_once 'Views/includes/header.php';
        require_once 'Views/Home.php';
        require_once 'Views/includes/footer.php';
    }

    public function Login(){
        $_SESSION['title'] =  "Gestock - Ingresar";
        require_once 'Views/Login.php';
        require_once 'Views/includes/footer.php';
    }
    public function Mostrar(){
        $_SESSION['title'] =  "Gestock - Usuarios";
        require_once 'Views/includes/header.php';
        require_once 'Views/usuario/usuarios.php';
        require_once 'Views/includes/footer.php';
    }

    public function Crud()
    {
        $pvd = new Users();
        if(isset($_REQUEST['Id_user'])){
            $pvd = $this->model->Obtener($_REQUEST['Id_user']);
        }
        $_SESSION['title'] =  "Gestock - Editar Usuario";
        require_once 'Views/includes/header.php';
        require_once 'Views/usuario/usuario-editar.php';
        require_once 'Views/includes/footer.php';
    }

    
    public function Nuevo(){
        $pvd = new Users();
        $_SESSION['title'] =  "Gestock - Registrar Usuario";
        require_once 'Views/includes/header.php';
        require_once 'Views/usuario/usuario-nuevo.php';
        require_once 'Views/includes/footer.php';
    }

    public function PassView(){
        $_SESSION['title'] =  "Gestock - Editar Contraseña";
        require_once 'Views/includes/header.php';
        require_once 'Views/usuario/password-edit.php';
        require_once 'Views/includes/footer.php';
    }

    public function Logear(){
        $pvd = new Users();

        $_SESSION['infoCompany'] = $this->companyModel->Info();

        $pvd->Username = $_REQUEST['Username'];
        $pvd->Userpass = sha1($_REQUEST['Password']);
        $_SESSION['valido'] = $this->model->Validar($pvd);
        $condition = $_SESSION['valido']->estado;
        if ($_SESSION['valido'] == false)
        {
            $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
            Usuario o Contraseña incorrectos!
            </div>';
            header ('Location: ?');
        }
        else
        {
            if($condition != 1)
            {  
                $_SESSION['valido'] = false;
                $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                El usuario con el que desea ingresar se encuentra deshabilitado, contacte a su administrador!
                </div>';
                header ('Location: ?');
            }
            else
            {   
                header('Location: ?c=user&a=Index');
            }
        }
    }
    public function Logout(){

        unset($_SESSION['valido']);
        header('Location: ?');
    }
    
    public function Guardar(){
        $pvd = new Users();

        $_REQUEST['Username'] = $this->model->Sanitize($_REQUEST['Username']);
        $_REQUEST['UserPass'] = $this->model->Sanitize($_REQUEST['UserPass']);
        $_REQUEST['Name'] = $this->model->Sanitize($_REQUEST['Name']);
        $_REQUEST['Email'] = $this->model->Sanitize($_REQUEST['Email']);

        if (!empty($_REQUEST['Username']) &&
            !empty($_REQUEST['UserPass']) &&
            !empty($_REQUEST['Name']) &&
            !empty($_REQUEST['Email']))
        {
                $pvd->Username = $_REQUEST['Username'];
                $pvd->Userpass = sha1($_REQUEST['UserPass']);
                $pvd->Name = $_REQUEST['Name'];
                $pvd->Email = $_REQUEST['Email'];
                $pvd->Rol = $_REQUEST['Rol'];


                if($this->ExisteEmail())
                {
                    $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                    Ya se encuentra un usuario registrado con el email '.$pvd->Email.'
                    </div>';
                    header('Location: ?c=user&a=Nuevo');   
                }else if ($this->model->UserExist($pvd) != null)
                {
                    $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                    Ya se encuentra un usuario registrado con el nombre '.$pvd->Username.'
                    </div>';
                    header('Location: ?c=user&a=Nuevo');
                }else
                {
                    $this->model->Registrar($pvd);

                    $_SESSION['alert'] = '<div class="alert alert-success" role="alert">
                    Usuario registrado.
                    </div>';

                    header('Location: ?c=user&a=Mostrar');
                }
                
        }
            else
            {
                $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                 Por favor use caracteres válidos y recuerde que los campos no pueden estar vacíos.
                </div>';
                header('Location: ?c=user&a=Nuevo');
            }
    }

    public function ViewRol($selected = 1)
    {
        $respuesta = Users::mdlViewRol();
        foreach ($respuesta as $key => $value) {
            
			echo '<option value="'.$value['id_rol'].'" '.($value['id_rol'] == $selected ? 'selected' : '').'>'.$value['description'].'</option>';
		}
    }

    public function Editar(){
        $pvd = new Users();

        $_REQUEST['Username'] = $this->model->Sanitize($_REQUEST['Username']);
        $_REQUEST['Name'] = $this->model->Sanitize($_REQUEST['Name']);
        $_REQUEST['Email'] = $this->model->Sanitize($_REQUEST['Email']);

        if (!empty($_REQUEST['Username']) &&
            !empty($_REQUEST['Name']) &&
            !empty($_REQUEST['Email']))
            {
                $pvd->Id = $_REQUEST['id'];
                $pvd->Username = $_REQUEST['Username'];
                $pvd->Name = $_REQUEST['Name'];
                $pvd->Email = $_REQUEST['Email'];
                $pvd->Rol = $_REQUEST['Rol'];

                $Exist = $this->model->ValidateAddUser($pvd);
                $Exist2 = $this->model->UserExist($pvd);
                
                if($pvd->Id == $Exist->Id_user || $Exist->Id_user == null)
                {
                    if ($pvd->Id == $Exist2->Id_user || $Exist2->Id_user == null)
                    {
                        $this->model->Actualizar($pvd);
                        $_SESSION['alert'] = '<div class="alert alert-success" role="alert">
                        Los datos se actualizaron correctamente
                        </div>';
                        if ($_SESSION['valido']->Id_user == $pvd->Id)
                        {
                            header('Location: ?c=user&a=Logout');
                        }
                        else
                        {
                            header('Location: ?c=user&a=Mostrar');
                        }
                    } 
                    else
                    {
                        $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                        Ya se encuentra un usuario registrado con el nombre '.$pvd->Username.
                        '</div>';
                        header('Location: ?c=user&a=Crud&Id_user='.$pvd->Id);
                    }
                }
                else
                {
                    $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                    Ya se encuentra un usuario registrado con el email '.$pvd->Email.
                    '</div>';
                    header('Location: ?c=user&a=Crud&Id_user='.$pvd->Id);
                }
            }
            else
            {
                $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                 Por favor use caracteres válidos y recuerde que los campos no pueden estar vacíos.
                </div>';
                header('Location: ?c=user&a=Crud&Id_user='.$pvd->Id);
            }
    }

    public function ResetPass(){
        $this->model->PassReset($_REQUEST['Id_user']);

         $_SESSION['alert'] = '<div class="alert alert-success" role="alert">
            La contraseña se restableció correctamente
        </div>';
        header('Location: ?c=user&a=Mostrar');
    }

    public function ExisteEmail(){
        $pvd = new Users();
        $pvd->Email = $_REQUEST['Email'];

        return $this->model->ExisteEmail($pvd);
    
    }

    public function PasswordGuardar()
    {
        $pvd = new Users();

        $pvd->Id = $_REQUEST['id'];
        $pvd->Actual = $_REQUEST['Actual'];
        $pvd->Nueva = $_REQUEST['Nueva'];
        $pvd->Confirmar = $_REQUEST['Confirmar'];

        if ($pvd->Actual == "" || $pvd->Nueva == "" || $pvd->Confirmar == "")
        {
            $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
            No puede haber campos vacíos!!
            </div>';
            header ("Location: ?c=user&a=PassView");
        }else
        {
            $pvd->Actual = sha1($_REQUEST['Actual']);
            $pvd->Nueva = sha1($_REQUEST['Nueva']);
            $pvd->Confirmar = sha1($_REQUEST['Confirmar']);
            
            if ($pvd->Actual != $_SESSION['valido']->user_pass)
            {
                $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                La contraseña actual no es la correcta
                </div>';
                header ("Location: ?c=user&a=PassView");
            } else if ($pvd->Nueva != $pvd->Confirmar)
            {
                $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                Las contraseñas no coinciden
                </div>';
                header ("Location: ?c=user&a=PassView");
            } else
            {
                $this->model->EditPass($pvd);
                $_SESSION['alert'] = '<div class="alert alert-success" role="alert">
                La contraseña se actualizó correctamente
                </div>';
                header ("Location: ?c=user&a=PassView");
            }
        }   
    }

    public function EditInfoCompany(){
        $pvd = new Company();
        $stm = new Company();

        $pvd->Name = $_REQUEST['txtNombre'];
        $pvd->CompanyName = $_REQUEST['txtRSocial'];
        $pvd->Cuit = $_REQUEST['txtCuit'];
        $pvd->TelNumber = $_REQUEST['txtTelEmpresa'];
        $pvd->Email = $_REQUEST['txtEmailEmpresa'];
        $pvd->Address = $_REQUEST['txtDirEmpresa'];

        $stm->Actualizar($pvd);
        if (isset($stm))
        {
            $_SESSION['alert'] = '<div class="alert alert-success" role="alert">
            Datos Actualizados
        </div>';
        }
        else
        {
            $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
            No se pudieron Actualizar los datos
        </div>';
        }
        header ("Location: ?c=user&a=Index");
    }

    //Método que actualiza el estado activo/inactivo con el id_user
    public function Eliminar(){
        $Id_user = $_REQUEST['Id_user'];
        $newStatus = 1;
        if($this->model->Obtener($Id_user)->estado == 1) {
            $newStatus = 2;
        }
        $this->model->Eliminar($Id_user, $newStatus);
        header('Location: ?c=user&a=Mostrar');
    }
}
?>