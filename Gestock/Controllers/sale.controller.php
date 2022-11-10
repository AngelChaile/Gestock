<?php
require_once 'Models/product.php';
require_once 'Models/ticket.php';
require_once 'InvalidQuantityException.php';
require_once 'Models/ConfirmMovementException.php';

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

        return $ticketModel->ProcesarVenta($ticket);
    }
}
?>