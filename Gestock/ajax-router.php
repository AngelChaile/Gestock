<?php
include 'Models/clients.php';
include 'Models/database.php';

include 'Controllers/clients.controller.php';
include 'Controllers/product.controller.php';
include 'Controllers/sale.controller.php';
include 'Controllers/provider.controller.php';

//permitimos el post a este controller para aprovechar el ajax
// Buscar Cliente
if (!empty($_POST)) {
    switch ($_POST['action']) {
        case 'tipoTicket' :
            AgregarTipoTicketATicket();
            exit;
        case 'destinatarioTicket' :
            AgregarDestinatarioATicket();
            exit;
        case 'buscarCliente' :
            BuscarCliente();
            exit;
        case 'buscarProducto':
            BuscarProducto();
            exit;
        case 'agregarProductoATicket':
            AgregarProductoATicket();
            exit;
        case 'eliminarProductoDeTicket':
            EliminarProductoDeTicket();
            exit;
        case 'anularVenta':
            AnularVenta();
            exit;
        case 'procesarVenta':
            ProcesarVenta();
            exit;
    }
}

function BuscarCliente() {
    if (!empty($_POST['cliente'])) {
        $cuit_cuil = $_POST['cliente'];
        $recipientType = $_POST['recipientType'];

        $controller = null;
        if($recipientType == 1) {
            $controller = new ClientsController();
        } else if($recipientType == 2) {
            $controller = new ProviderController();
        }
        $data = $controller->BuscarPorCUITCUIL($cuit_cuil);

        if(!$data) {
            $controller = new SaleController();
            $controller->EliminarClienteDeTicket($recipientType);
        }

        $_POST['cliente'] = $data;

        $controller = new SaleController();
        $controller->crearTicket();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        $recipientType = $_POST['recipientType'];
        $controller = new SaleController();
        $ticket = $controller->EliminarClienteDeTicket($recipientType);

        $response = json_encode($ticket, JSON_UNESCAPED_UNICODE);
        if($ticket == null) {
            $response = 'Error';
        }

        echo $response;
    }
}

function BuscarProducto() {
    if (!empty($_POST['criteria'])) {
        $criterio = $_POST['criteria'];

        $controller = new ProductController();
        $controller->BuscarProducto($criterio);
        $data = $_SESSION["filtered_products"];

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

function AgregarProductoATicket() {
    // agregar producto a detalle temporal
    if (empty($_POST['producto']) || empty($_POST['cantidad'])){
        echo 'error';
    }else {

        $controller = new SaleController();
        try {
            $ticket = $controller->agregarProductoATicket($_POST['producto'], $_POST['cantidad'], $_POST['ticketType']);
        }catch (InvalidQuantityException $e) {
            http_response_code(412); //Precondition failed
            echo $e->getMessage();
            exit;
        }
        echo json_encode($ticket, JSON_UNESCAPED_UNICODE);

    }
    exit;
}

function EliminarProductoDeTicket() {
    // agregar producto a detalle temporal
    if (empty($_POST['id_producto'])){
        echo 'error';
    }else {

        $controller = new SaleController();
        $ticket = $controller->EliminarProductoDeTicket($_POST['id_producto'], $_POST['cantidad']);

        echo json_encode($ticket, JSON_UNESCAPED_UNICODE);

    }
    exit;
}

function AnularVenta() {
    $controller = new SaleController();
    $controller->AnularTicket();
}

function AgregarTipoTicketATicket() {
    // agregar producto a detalle temporal
    if (empty($_POST['ticketType'])){
        echo 'error';
    }else {
        $controller = new SaleController();
        $ticket = $controller->AgregarTipoTicketATicket($_POST['ticketType']);

        echo json_encode($ticket, JSON_UNESCAPED_UNICODE);

    }
    exit;
}

function AgregarDestinatarioATicket() {
    // agregar producto a detalle temporal
    if (empty($_POST['recipientType'])){
        echo 'error';
    }else {
        $controller = new SaleController();
        $ticket = $controller->AgregarDestinatarioATicket($_POST['recipientType']);

        echo json_encode($ticket, JSON_UNESCAPED_UNICODE);

    }
    exit;
}

function ProcesarVenta() {
    try {
        $controller = new SaleController();
        $ticket = $controller->ProcesarVenta();
        echo json_encode($ticket, JSON_UNESCAPED_UNICODE);
    }catch (ConfirmMovementException $e) {
        http_response_code(500); //Precondition failed
        echo $e->getMessage();
        exit;
    }
}