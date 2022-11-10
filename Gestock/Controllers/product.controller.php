<?php
require_once 'Models/product.php';
class ProductController{

    private $model;
    public function __CONSTRUCT(){
        session_start();
       $this->model = new Products();
    }

    public function Mostrar(){
        $_SESSION['title'] = "Gestock-Productos";
        require_once 'Views/includes/header.php';
        require_once 'Views/producto/productos.php';
        require_once 'Views/includes/footer.php';
    }

    public function Nuevo(){

        require_once 'Views/includes/header.php';
        require_once 'Views/producto/products-nuevo.php';
        require_once 'Views/includes/footer.php';
    }


    public function Crud(){
        $pvd = new Products();

        //Se obtienen los datos del cliente a editar.
        if(isset($_REQUEST['id_product'])){
            $pvd = $this->model->Obtener($_REQUEST['id_product']);
        }


        require_once 'Views/includes/header.php';
        require_once 'Views/producto/update-products.php';
        require_once 'Views/includes/footer.php';
  }

    public function Guardar(){
        $pvd = new Products();

        $_REQUEST['producto'] = $this->model->Sanitize($_REQUEST['producto']);
        $_REQUEST['precio'] = $this->model->Sanitize($_REQUEST['precio']);
        $_REQUEST['cantidad'] = $this->model->Sanitize($_REQUEST['cantidad']);
        $_REQUEST['categoria'] = $this->model->Sanitize($_REQUEST['categoria']);
        $_REQUEST['iva'] = $this->model->Sanitize($_REQUEST['iva']);
        $_REQUEST['marca'] = $this->model->Sanitize($_REQUEST['marca']);
        $_REQUEST['codigo'] = $this->model->Sanitize($_REQUEST['codigo']);

        if (!empty($_REQUEST['producto']) &&
            !empty($_REQUEST['precio']) &&
            !empty($_REQUEST['cantidad']) &&
            !empty($_REQUEST['categoria']) &&
            !empty($_REQUEST['iva']) &&
            !empty($_REQUEST['marca']) &&
            !empty($_REQUEST['codigo']))
            {

                $pvd->ProductName = $_REQUEST['producto'];
                $pvd->Price = $_REQUEST['precio'];
                $pvd->Qty = $_REQUEST['cantidad'];
                $pvd->Category = $_REQUEST['categoria'];
                $pvd->Tax = $_REQUEST['iva'];
                $pvd->Brand = $_REQUEST['marca'];
                $pvd->Barcode = $_REQUEST['codigo'];

            $Exist = $this->model->ValidateAddProduct($pvd);
            if ($Exist)
            {
            $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
            Ya se encuentra registrado un producto con el codigo de barras '.$pvd->Barcode.'
            </div>';
            header('Location: ?c=product&a=Nuevo');
            }
            else
            {
            $this->model->Registrar($pvd);
            $_SESSION['alert'] = '<div class="alert alert-succes" role="alert">
            Producto cargado correctamente
            </div>';
            header('Location: ?c=product&a=Mostrar');
            }
        } else
        {
            $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
            Por favor use caracteres válidos y recuerde que los campos no pueden estar vacíos.
            </div>';
            header('Location: ?c=product&a=Nuevo');
        }
    }


    
    public function ViewCategory($selected = "?")
    {
        $respuesta = Products::mdlViewCategory();
        foreach ($respuesta as $key => $value) {
            
            echo '<option value="'.$value['id_category'].'" '.($value['id_category'] == $selected ? 'selected' : '').'>'.$value['description'].'</option>';
		}
    }
    
    public function ViewTax($selected = "?")
    {
        $respuesta = Products::mdlViewTax();
        foreach ($respuesta as $key => $value) {
            
            echo '<option value="'.$value['id_tax'].'" '.($value['id_tax'] == $selected ? 'selected' : '').'>'.$value['description'].'</option>';
        }
    }
    
    public function Editar()
    {
        $pvd = new Products();

        $_REQUEST['Nombre'] = $this->model->Sanitize($_REQUEST['Nombre']);
        $_REQUEST['precio'] = $this->model->Sanitize($_REQUEST['precio']);
        $_REQUEST['Cantidad'] = $this->model->Sanitize($_REQUEST['Cantidad']);
        $_REQUEST['categoria'] = $this->model->Sanitize($_REQUEST['categoria']);
        $_REQUEST['iva'] = $this->model->Sanitize($_REQUEST['iva']);
        $_REQUEST['Marca'] = $this->model->Sanitize($_REQUEST['Marca']);
        $_REQUEST['codbarra'] = $this->model->Sanitize($_REQUEST['codbarra']);
        

        
        
        if 
        (!empty($_REQUEST['Nombre']) &&
        !empty($_REQUEST['precio']) &&
        !empty($_REQUEST['Cantidad']) &&
        !empty($_REQUEST['categoria']) &&
        !empty($_REQUEST['iva']) &&
        !empty($_REQUEST['Marca']) &&
        !empty($_REQUEST['codbarra']))

        {
            $pvd->Id = $_REQUEST['id'];
            $pvd->Nombre = $_REQUEST['Nombre'];
            $pvd->Price = $_REQUEST['precio'];
            $pvd->Qty = $_REQUEST['Cantidad'];
            $pvd->Category = $_REQUEST['categoria'];
            $pvd->Tax = $_REQUEST['iva'];
            $pvd->Brand = $_REQUEST['Marca'];
            $pvd->Barcode = $_REQUEST['codbarra'];

            $ProductExist = $this->model->ValidateAddProduct($pvd);

           
            if($ProductExist->id_product == $pvd->Id || $ProductExist->id_product == null)
            {
                    $this->model->Actualizar($pvd);
                    $_SESSION['alert'] = '<div class="alert alert-success" role="alert">
                    Los datos se actualizaron correctamente
                    </div>';
                    header('Location: ?c=product&a=Mostrar');
            }else
                {
                    $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
                    Ya se encuentra registrado un producto con el codigo '.$pvd->Barcode.'
                    </div>';
                    header('Location: ?c=product&a=Crud&id_product='.$pvd->Id);
                }
            
        }else
        {
            $_SESSION['alert'] = '<div class="alert alert-danger" role="alert">
            Por favor use caracteres válidos y recuerde que los campos no pueden estar vacíos.
            </div>';
            header('Location: ?c=product&a=Crud&id_product='.$pvd->Id);
        }
    }

    public function BuscarProducto($criteria) {
        $_SESSION["current_filter"] = $criteria;

        $_SESSION["filtered_products"] = $this->model->Buscar($criteria);
    }


    //Método que actualiza el estado activo/inactivo con el id_product
    public function Eliminar(){
        $id_product = $_REQUEST['id_product'];
        $newStatus = 1;
        if($this->model->Obtener($id_product)->id_state == 1) {
            $newStatus = 2;
        }
        $this->model->Eliminar($id_product, $newStatus);
        header('Location: ?c=product&a=Mostrar');
    }
}
?>