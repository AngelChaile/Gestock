<?php include_once "Controllers/movement.controller.php";

$controller = new MovementController();
$resultado = $controller->Listar();

?>
<h3 class="page-header">Movimientos</h3>

<div class="container-fluid">
    <div class="well well-sm text-right">
        <a class="btn btn-primary" href="?c=sale&a=Nuevo">Nuevo Movimiento</a>
    </div>
    <br/>

    <table id="dtHorizontalVerticalExample" class="table table-hover table-striped" style="width:100%">
        <thead class="thead-dark">
            <tr>
                <th class="col-lg-1">Número</th>
                <th class="col-lg-2">Usuario</th>
                <th class="col-lg-2">Destinatario</th>
                <th class="col-lg-1">Tipo Movimiento</th>
                <th class="col-lg-1">Cantidad</th>
                <th class="col-lg-3">Fecha</th>
                <th class="col-lg-1"></th>
                <th class="col-lg-1"></th>
            </tr>
        </thead>
        <tbody>
                    <?php foreach($resultado as $r) { ?>
                        <tr>
                            <td><?php echo $r->ticket_number; ?></td>
                            <td><?php echo $r->user_name; ?></td>
                            <td><?php
                                echo ($r->customer_name ?? ($r->company_name ?? ''));
                                ?>
                            </td>
                            <td><?php echo $r->m_description; ?></td>
                            <td><?php echo $r->product_quantity; ?></td>
                            <td><?php
                                try {
                                    $date = new DateTime($r->date);
                                    echo $date->format('d-m-Y H:i:s');
                                } catch (Exception $e) {
                                    echo "";
                                }
                                 ?>
                            </td>
                            <td>
                                <a href="?c=movement&a=detail&movement=<?php echo $r->id_movement; ?>"
                                   class="btn btn-success" data-toggle="tooltip" title="Detalle">
                                   <p>PDF</p>
                                   <i style="font-size:24px" class="fa">&#xf1c1;</i>
                                </a>
                            </td>
                            <td>
                            <a href="?c=movement&a=FormPDF&movement=<?php echo $r->id_movement; ?>"
                                   class="btn btn-success" data-toggle="tooltip" title="Detalle">
                                   <p>enviar</p>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
        </tbody>
    </table>
</div>