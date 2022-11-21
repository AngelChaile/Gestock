<?php
session_start();
if (!isset($_SESSION['valido']))
{
	header('Location: ?c=user&a=Login');
}

ob_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimientos</title>

    <link rel="shortcut icon" href="Assets/img/logo.jpeg">

	<!-- Custom styles for this template-->
	<link href="Assets/css/styles.css" rel="stylesheet">
	<link href="Assets/css/sb-admin-2.min.css" rel="stylesheet">
	<link rel="stylesheet" href="Assets/css/dataTables.bootstrap4.min.css">

    <link href="Assets/css/ribbon.css" rel="stylesheet">
    <link href="Assets/css/sale.css" rel="stylesheet">
    <link href="Assets/css/fontawesome.min.css" rel="stylesheet">
    <script src="Assets/js/producto.js"></script>

    <style>
    body {
        overflow-x: hidden;
        font-family: 'Roboto Slab', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
    }
    #titulo{
        text-align: center;
    }
    .signature{
        font-family: 'Kaushan Script', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
        color: #0099D5;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid transparent;
        background: #0099D5;
        color: #fff;
    }

    .div-3{
        display: flex;
        justify-content: space-between;
        padding-bottom: 16px;
    }
    .div-1{
        /*background: yellow;*/
    }
    .div-2{
       /* background: red;*/
    }

    .font-weight-lighter{
        font-weight: 300;
        background: #0099D5;
        color: #fff;
    }
    .inv{
        background: #E6E4E7;
    }
    .my-3 {
        margin-bottom: 2rem !important;
        margin-top: 1rem !important;
    }
    .my-5 {
        margin-bottom: 3rem !important;
        margin-top: 3rem !important;
    }
    .py-5 {
        padding-bottom: 3rem !important;
        padding-top: 3rem !important;
    }
    .px-3 {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
   /* .border-bottom{
        border-bottom: 2px solid #000 !important;
        /*width: 35%;*/
       /* padding: 0px 0px 5px 0px;
    }*/
    .cantidad{
        text-align:center;
    }
    .text-muted{
        position: absolute;
        bottom: 0;
        right: 50%;
        margin-right: -100px;
    }
    </style>

</head>
<bodys>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 m-auto">
            <div class="row">
                <!--<img class="img-thumbnail" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/Practicas_Examen_Final/Assets/img/logo.jpeg">-->
                <h1 id="titulo">Gestock</h1>                  
                
                <div>
                    <h1 class="font-weight-lighter py-1 px-3">Movimiento</h1>
                </div>
       

            <div class="row my-3">
                <div class="col-lg-4">
                    <p class="mb-0">Destinatario</p>
                    <h5 class="mb-0"><b><?php echo ($movement->customer_name ?? ($movement->company_name ?? ''));?></b></h5>
                    <p class="mb-0"><?php echo ($movement->cu_cuit_cuil ?? ($movement->prv_cuit_cuil ?? ''));?></p>
                    <p class="mb-0">
                        <?php echo ($movement->cu_street ?? ($movement->prv_street ?? '')) . " " .
                            ($movement->cu_address_number ?? ($movement->prv_number ?? ''));?>
                    </p>
                    <p class="mb-0"><?php echo ($movement->cu_tel_number ?? ($movement->prv_tel_number ?? ''));?></p>
                    <p class="mb-0"><?php echo ($movement->email ?? ($movement->p_email ?? ''));?></p>
                </div>
            </div>
        <div class="div-3">
                <div class="div-1">
                    <table>
                        <tbody>
                        <tr>
                            <td>Número</td>
                            <td class="px-3">:</td>
                            <td class="text-left"><?php echo $movement->ticket_number;?></td>
                        </tr>
                        <tr>
                            <td>Fecha</td>
                            <td class="px-3">:</td>
                            <td><?php
                                try {
                                    $date = new DateTime($movement->date);
                                    echo $date->format('d-m-Y');
                                } catch (Exception $e) {
                                    echo "";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Hora</td>
                            <td class="px-3">:</td>
                            <td>
                                <?php
                                try {
                                    $date = new DateTime($movement->date);
                                    echo $date->format('H:i:s');
                                } catch (Exception $e) {
                                    echo "";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Tipo</td>
                            <td class="px-3">:</td>
                            <td><?php echo $movement->m_description;?></td>
                        </tr>
                        <tr>
                            <td>Atendido por</td>
                            <td class="px-3">:</td>
                            <td><?php echo $movement->user_name;?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="div-2">
                    <table>
                        <tbody>
                            <tr>
                                <td>Razón Social</td>
                                <td class="px-3">:</td>
                                <td><?php echo $_SESSION['infoCompany']->company_name; ?></td>
                            </tr>
                            <tr>
                                <td>CUIT</td>
                                <td class="px-3">:</td>
                                <td><?php echo $_SESSION['infoCompany']->cuit; ?></td>
                            </tr>
                            <tr>
                                <td>Dirección</td>
                                <td class="px-3">:</td>
                                <td><?php echo $_SESSION['infoCompany']->address; ?></td>
                            </tr>
                            <tr>
                                <td>Teléfono</td>
                                <td class="px-3">:</td>
                                <td><?php echo $_SESSION['infoCompany']->tel_number; ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td class="px-3">:</td>
                                <td><?php echo $_SESSION['infoCompany']->email; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>

            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Nro</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Código</th>
                            <th scope="col">Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $index = 0;
                        $products = (new Movement())->FindMovementProductsById($movement->id_movement);
                        foreach ($products as $prd) { ;
                            $index += 1;?>
                        <tr>
                            <td style="background: #3989c6; color: white !important;"><?php echo $index ?></td>
                            <td>
                                <b><?php echo $prd->brand; ?></b>
                                <p>
                                    <?php echo $prd->description; ?>
                                </p>
                            </td>
                            <td><?php echo $prd->barcode; ?></td>
                            <td class="cantidad"><?php echo $prd->quantity; ?></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
        </div>

    </div>
  </div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<br><br>
    <div class="text-muted">Copyright &copy; Alumnos isft177</div>

</body>
</html>

<?php
$html=ob_get_clean();
//echo $html;

require_once 'Assets/libreria/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('letter');
//$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream("archivo_.pdf", array("Attachment" => false));
?>
