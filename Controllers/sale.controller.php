<?php
require_once 'Models/product.php';
require_once 'Models/ticket.php';
require_once 'InvalidQuantityException.php';
require_once 'Models/ConfirmMovementException.php';
require_once 'movement.controller.php';
require_once 'Models/movement.php';
require_once 'Assets/libreria/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

class SaleController {

    private $model;

    public function __CONSTRUCT(){
        session_start();
       $this->model = new Ticket();
    }

    
    public function Nuevo(){

        $ticket = new Ticket();
        $user = $_SESSION['valido'];
        $ticket = $ticket->BuscarTicketEnCurso($user);
        //var_dump($ticket);

        require_once 'Views/includes/header.php';
        require_once 'Views/sale/sale.php';
        require_once 'Views/includes/footer.php';
    }

    public function Mostrar(){
        $_SESSION['title'] = "Gestock-Movimientos";

        require_once 'Views/includes/header.php';
        require_once 'Views/sale/sales.php';
        require_once 'Views/includes/footer.php';
    }

    public function BuscarProducto() {
        $product = new Products();
        $criteria = $_POST["criteria"];
        $_SESSION["current_filter"] = $criteria;

        $products = $product->Buscar($criteria);

        $_SESSION["filtered_products"] = $products;

        $this->Nuevo();

    }

    public function crearTicket(): ?Ticket {
        $ticket = new Ticket();

        $user = $_SESSION['valido'];
        $cliente = $_POST['cliente'];
        $recipientType = $_POST['recipientType'];

        //var_dump($cliente);

        $ticket->user = new Users();
        $ticket->user->Id = $user->Id_user;
        $ticket->client = new Clients();
        $ticket->provider = new Provider();
        if($recipientType == 1) {
            $ticket->client->Id = $cliente->id_customer;
            $ticket->provider->Id = null;
        } else if($recipientType == 2) {
            $ticket->provider->Id = $cliente->id_customer;
            $ticket->client->Id = null;
        }

        $ticketEnCurso = $ticket->BuscarTicketEnCurso($user);
        if($ticketEnCurso) {
            if($ticket->client != null && $ticket->client->Id != null) {
                $ticket->agregarClienteATicket($ticket->client->Id, $ticket->provider->Id, $ticketEnCurso->id_ticket);
                $ticketEnCurso = $ticket->BuscarTicket($ticketEnCurso->id_ticket);
            }else if($ticket->provider != null && $ticket->provider->Id != null) {
                $ticket->agregarClienteATicket($ticket->client->Id, $ticket->provider->Id, $ticketEnCurso->id_ticket);
                $ticketEnCurso = $ticket->BuscarTicket($ticketEnCurso->id_ticket);
            }
            return $ticketEnCurso;
        }

        $ticket->Nuevo();
        return $ticket->BuscarTicketEnCurso($user);
    }

    public function EliminarClienteDeTicket($recipientType): ?Ticket {
        $ticket = new Ticket();

        $user = $_SESSION['valido'];

        $ticketEnCurso = $ticket->BuscarTicketEnCurso($user);
        if($ticketEnCurso) {
            $ticket->agregarClienteATicket(null, null, $ticketEnCurso->id_ticket);
            return $ticket->BuscarTicket($ticketEnCurso->id_ticket);
        }

        return null;
    }

    /**
     * @throws InvalidQuantityException
     */
    public function agregarProductoATicket($producto, $quantity, $ticketType): ?Ticket
    {
        $ticketModel = new Ticket();
        $productModel = new Products();

        $ticket = $this->crearTicket();
        $product = $productModel->BuscarPorId($producto);

        if($ticketType == 2 && $product->qty < $quantity) {
            throw new InvalidQuantityException("Stock Insuficiente", 50, new Exception(""));
        }

        return $ticketModel->agregarProductoATicket($ticket->id_ticket, $product, $quantity, $ticketType);
    }

    public function EliminarProductoDeTicket($id_product, $quantity): ?Ticket {
        $ticketModel = new Ticket();

        $ticket = $this->crearTicket();

        return $ticketModel->EliminarProductoDeTicket($ticket->id_ticket, $id_product, $quantity);
    }

    public function AnularTicket(): void
    {
        $user = $_SESSION['valido'];

        $ticket = new Ticket();
        $ticket->EliminarTicketEnCurso($user);
    }

    public function TicketTypes()
    {
        return $this->model->TicketTypes();
    }

    public function RecipientTypes()
    {
        return $this->model->RecipientTypes();
    }

    public function AgregarTipoTicketATicket($ticketTypeId) {
        $ticketModel = new Ticket();
        $productModel = new Products();
        $ticketEnCurso = $this->crearTicket();

        if ($ticketEnCurso->id_ticket_type != $ticketTypeId) {
            if($ticketEnCurso->products) {
                foreach ($ticketEnCurso->products as $product) {
                    $qty = $product["quantity"] * 2;
                    $ticketModel->ActualizarStock($qty, $product['id_product'], $ticketTypeId);
                }
            }
        }

        $ticketModel->AgregarTipoTicketATicket($ticketTypeId, $ticketEnCurso);

        return $ticketModel->BuscarTicket($ticketEnCurso->id_ticket);
    }

    public function AgregarDestinatarioATicket($recipientTypeId) {
        $ticketModel = new Ticket();

        $ticketEnCurso = $this->crearTicket();

        $ticketModel->AgregarDestinatarioATicket($recipientTypeId, $ticketEnCurso->id_ticket);

        return $ticketModel->BuscarTicket($ticketEnCurso->id_ticket);
    }

    /**
     * @throws ConfirmMovementException
     */
    public function ProcesarVenta() {
        $ticketModel = new Ticket();

        $user = $_SESSION['valido'];

        $ticket = $ticketModel->BuscarTicketEnCurso($user);

        $ticketOK = $ticketModel->ProcesarVenta($ticket);

        $this->EnviarEmail($ticket->movementId);

        return $ticketOK;
    }

    private function EnviarEmail($movementId) {
        $movementController = new MovementController();
        $movement = $movementController->DetailWithoutRedirect($movementId);
        
        $dompdf = new Dompdf();

        //opciones para permitir que obtenga el html css y mostrar en una lista
        $options = $dompdf->getOptions();                   //recuperar opcion
        $options->set(array('isRemoteEnabled' => true));    //activar opción
        $dompdf->setOptions($options);                      //pasarlo al objeto dompdf

        //cargamos el contenido html
        $dompdf->loadHtml($this->GetHtml($movement));

        $dompdf->setPaper('letter');
        $dompdf->render();

        $name = $movement->customer_name ?? ($movement->company_name ?? '');
        $email = $movement->email ?? ($movement->p_email ?? '');
        $comentario = "Esto es un comentario";
        Movement::EnviarOne($name, $email, $comentario, $dompdf->output());

    }

    private function GetHtml($movement) {
        return '<!DOCTYPE html>' .
            '<html lang="en">' .
            '<head>' .
            '    <meta charset="UTF-8">' .
            '    <meta http-equiv="X-UA-Compatible" content="IE=edge">' .
            '    <meta name="viewport" content="width=device-width, initial-scale=1.0">' .
            '    <title>Movimientos</title>' .
            '    <link href="Assets/css/styles.css" rel="stylesheet">' .
            '    <link href="Assets/css/sb-admin-2.min.css" rel="stylesheet">' .
            '    <link rel="stylesheet" href="Assets/css/dataTables.bootstrap4.min.css">' .
            '    <link href="Assets/css/sale.css" rel="stylesheet">' .
            '    <link href="Assets/css/fontawesome.min.css" rel="stylesheet">' .
            '    <script src="Assets/js/producto.js"></script>' .
            '    <style>' .
            '        body {' .
            '            font-family: \'Roboto Slab\', -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\', \'Noto Color Emoji\';' .
            '        }' .
            '        #titulo{' .
            '            text-align: center;' .
            '        }' .
            '        h2{' .
            '            text-align: center;' .
            '            background: gray;' .
            '            color: #fff;' .
            '        }' .
            '        .table thead th {' .
            '            vertical-align: bottom;' .
            '            border-bottom: 2px solid transparent;' .
            '            background: #0099D5;' .
            '            color: #fff;' .
            '        }' .
            '        .font-weight-lighter{' .
            '            font-weight: 300;' .
            '            background: #0099D5;' .
            '            color: #fff;' .
            '        }' .
            '        .px-3 {' .
            '            padding-left: 3px !important;' .
            '            padding-right: 20px !important;' .
            '        }' .
            '        .cantidad{' .
            '            text-align:center;' .
            '        }' .
            '        .text-muted{' .
            '            position: absolute;' .
            '            bottom: 0;' .
            '            right: 50%;' .
            '            margin-right: -100px;' .
            '        }' .
            '        #lista1 li {' .
            '            display:inline;' .
            '            padding-left:1px;' .
            '            padding-right:18%;' .
            '        }  ' .
            '        #email{' .
            '            margin-left: 2.4%;' .
            '        }' .
            '        #lista2 li {' .
            '            display:inline;' .
            '            padding-left:1px;' .
            '            padding-right:30%;' .
            '        }  ' .
            '        #lista3 li {' .
            '            display:inline;' .
            '            padding-left:1px;' .
            '            padding-right:25%;' .
            '        }  ' .
            '        #lista4 li {' .
            '            display:inline;' .
            '            padding-left:1px;' .
            '            padding-right:19%;' .
            '        }  ' .
            '        #lista5 li {' .
            '            display:inline;' .
            '            padding-left:1px;' .
            '            padding-right:24%;' .
            '        }  ' .
            '        #lista6 li {' .
            '            display:inline;' .
            '            padding-left:1px;' .
            '            padding-right:26%;' .
            '        }  ' .
            '        #nro{' .
            '            background: #3989c6;' .
            '            color: white !important;' .
            '            text-align: center;' .
            '        }' .
            '        .celda{' .
            '            padding-left:10px;' .
            '            padding-right:25px;' .
            '        }' .
            '    </style>' .
            '</head>' .
            '<bodys>' .
            '    <h1 id="titulo">Gestock</h1>                  ' .
            '    <h1 class="font-weight-lighter px-3">Movimiento</h1>' .
            '<br/>' .
            '    <ul id="lista1">' .
            '        <li>' .
            '            <b>Destinatario:</b> ' . ($movement->customer_name ?? ($movement->company_name ?? '')) .
            '        </li>' .
            '    </ul>' .
            '    <ul id="lista1">' .
            '        <li>' .
            '        <b>Cuil:</b> '. ($movement->cu_cuit_cuil ?? ($movement->prv_cuit_cuil ?? '')) .
            '        </li>' .
            '        <li>' .
            '        <b>Domicilio:</b> ' . ($movement->cu_street ?? ($movement->prv_street ?? '')) . " " .
                                    ($movement->cu_address_number ?? ($movement->prv_number ?? '')) .
            '        </li>' .
            '    </ul>' .
            '    <ul id="lista1">' .
            '        <li>' .
            '        <b>Tel: </b> ' . ($movement->cu_tel_number ?? ($movement->prv_tel_number ?? '')) .
            '        </li>' .
            '        <li id="email">' .
            '        <b>Email: </b> ' . ($movement->email ?? ($movement->p_email ?? '')) .
            '        </li>' .
            '    </ul>' .
            '<br>' .
            '<hr>' .
            '<h2>Datos de la Empresa</h2>' .
            '    <ul id="lista2">' .
            '        <li>' .
            '        Número:<span class="px-3"> ' . $movement->ticket_number . '</span>' .
            '        </li>' .
            '        <li>' .
            '        Fecha: <span class="px-3">' . $this->GetDateWithFormatDMY($movement->date) . '</span>' .
            '        </li>' .
            '    </ul>' .
            '    <ul id="lista3">' .
            '        <li>' .
            '        Hora: <span class="px-3">' . $this->ChangeTimeFormat($movement->date) .'</span>' .
            '        </li>' .
            '        <li>' .
            '        Tipo: <span class="px-3">' . $movement->m_description . '</span>' .
            '        </li>' .
            '    </ul>' .
            '    <ul id="lista4">' .
            '        <li>' .
            '        Atendido por: <span class="px-3">' . $movement->user_name . '</span>' .
            '        </li>  ' .
            '        <li>' .
            '        Razón Social: <span>' . $_SESSION['infoCompany']->company_name. '</span>' .
            '        </li>' .
            '    </ul>' .
            '    <ul id="lista5">' .
            '        <li>' .
            '            Cuit: ' . $_SESSION['infoCompany']->cuit .
            '        </li>' .
            '        <li>' .
            '            Dirección: ' . $_SESSION['infoCompany']->address .
            '        </li>' .
            '    </ul>' .
            '    <ul id="lista6">' .
            '        <li>' .
            '            Tel:<span> ' . $_SESSION['infoCompany']->tel_number . '</span>' .
            '        </li>' .
            '        <li>' .
            '            Email:<span> ' . $_SESSION['infoCompany']->email . '</span>' .
            '        </li>' .
            '    </ul>' .
            '    <br>' .
            '    <table border=1 class="table table-striped">' .
            '        <thead>' .
            '        <tr>' .
            '            <th class="celda">Nro</th>' .
            '            <th class="celda">Descripción</th>' .
            '            <th class="celda">Código</th>' .
            '            <th class="celda">Cantidad</th>' .
            '        </tr>' .
            '        </thead>' .
            '        <tbody>' .
                $this->RenderBody($movement) .
            '        </tbody>' .
            '    </table>' .
            '    <br><br>' .
            '    <div class="text-muted">Copyright &copy; Alumnos isft177</div>' .
            '</body>' .
            '</html>';
    }

    private function GetDateWithFormatDMY($date) {
        try {
            $date = new DateTime($date);
            return $date->format('d-m-Y');
        } catch (Exception $e) {
            return "";
        }
              
    }

    private function RenderBody($movement) {
        $body = "";
        $index = 0;
        $products = (new Movement())->FindMovementProductsById($movement->id_movement);
        foreach ($products as $prd) {
            $index .= 1;
        $body .= '<tr>' .
            '<td id="nro">' . $index . '</td>' .
            '<td class="fila" class="celda">' .
            '    <b>'. $prd->brand .'</b>' .
            '    <p>' .
                    $prd->description .
            '    </p>' .
            '</td>' .
            '<td class="celda"><br>'. $prd->barcode .'</td>' .
            '<td class="cantidad"><br>' . $prd->quantity . '</td>' .
            '</tr>';
        }

        return $body;
    }

    private function ChangeTimeFormat($date){
        try {
                $date = new DateTime($date);
                return $date->format('H:i:s');
            } catch (Exception $e) {
                return "";
            }
    }
    
}
?>