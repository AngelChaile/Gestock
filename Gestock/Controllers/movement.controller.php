<?php
session_start();

require_once 'Models/movement.php';

class MovementController {

    private $model;

    public function __CONSTRUCT(){
        $this->model = new Movement();
    }

    public function Listar() {
        return $this->model->Listar();
    }

    public function Detail() {
        $_SESSION['title'] = "Gestock-Movimiento";

        $movement = $this->model->FindById($_REQUEST["movement"]);

        if(!$movement) return;

        //require_once 'Views/includes/header.php';
        require_once 'Views/sale/sale-detail.php';
        require_once 'Views/includes/footer.php';
    }

    public function FormPDF(){
        //$_SESSION['Errormessege'] = $pdf_enviar;
        $movement = $this->model->FindById($_REQUEST["movement"]);
        if(!$movement) return;

        require_once 'Views/includes/header.php';
        require_once 'Views/sale/form-pdf.php';
        require_once 'Views/includes/footer.php';
    }

    public function EnviarPDF(){
        $this->model->EnviarOne();
    }
}