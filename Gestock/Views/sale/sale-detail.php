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

	<!-- Custom styles for this template-->
	<link href="Assets/css/styles.css" rel="stylesheet">
	<link href="Assets/css/sb-admin-2.min.css" rel="stylesheet">
	<link rel="stylesheet" href="Assets/css/dataTables.bootstrap4.min.css">

    <link href="Assets/css/sale.css" rel="stylesheet">
    <link href="Assets/css/fontawesome.min.css" rel="stylesheet">
    <script src="Assets/js/producto.js"></script>

    <style>
        body {
            font-family: 'Roboto Slab', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
        }
        #titulo{
            text-align: center;
        }
        h2{
            text-align: center;
            background: gray;
            color: #fff;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid transparent;
            background: #0099D5;
            color: #fff;
        }
        .font-weight-lighter{
            font-weight: 300;
            background: #0099D5;
            color: #fff;
        }
        .px-3 {
            padding-left: 3px !important;
            padding-right: 20px !important;
        }
        .cantidad{
            text-align:center;
        }
        .text-muted{
            position: absolute;
            bottom: 0;
            right: 50%;
            margin-right: -100px;
        }
        #lista1 li {
             display:inline;
             padding-left:1px;
             padding-right:18%;
        }  
        #email{
            margin-left: 2.4%;
        }
        #lista2 li {
             display:inline;
             padding-left:1px;
             padding-right:35%;
        }  
        #lista3 li {
             display:inline;
             padding-left:1px;
             padding-right:25%;
        }  
        #lista4 li {
             display:inline;
             padding-left:1px;
             padding-right:19%;
        }  
        #lista5 li {
             display:inline;
             padding-left:1px;
             padding-right:24%;
        }  
        #lista6 li {
             display:inline;
             padding-left:1px;
             padding-right:26%;
        }  
        #nro{
            background: #3989c6;
            color: white !important;
            text-align: center;
        }
        .celda{
            padding-left:10px;
            padding-right:25px;
        }
    </style>
</head>
<bodys>
    <h1 id="titulo">Gestock</h1>                  
    <h1 class="font-weight-lighter px-3">Movimiento</h1>
<br/>
    <ul id="lista1">
        <li>
            <b>Destinatario:</b> <?php echo ($movement->customer_name ?? ($movement->company_name ?? ''));?>
        </li>
    </ul>

    <ul id="lista1">
        <li>
           <b>Cuil:</b> <?php echo ($movement->cu_cuit_cuil ?? ($movement->prv_cuit_cuil ?? ''));?>
        </li>
        <li>
           <b>Domicilio:</b> <?php echo ($movement->cu_street ?? ($movement->prv_street ?? '')) . " " .
                           ($movement->cu_address_number ?? ($movement->prv_number ?? ''));?>
        </li>
    </ul>

    <ul id="lista1">
        <li>
           <b>Tel: </b><?php echo ($movement->cu_tel_number ?? ($movement->prv_tel_number ?? ''));?>
        </li>
        <li id="email">
           <b>Email: </b><?php echo ($movement->email ?? ($movement->p_email ?? ''));?>
        </li>
    </ul>
<br>
<hr>
<h2>Datos de la Empresa</h2>
    <ul id="lista2">
        <li>
           Número:<span class="px-3"><?php echo $movement->ticket_number;?></span>
        </li>
        <li>
           Fecha: <span class="px-3"><?php
                   try {
                       $date = new DateTime($movement->date);
                       echo $date->format('d-m-Y');
                   } catch (Exception $e) {
                       echo "";
                   }
                   ?></span>
        </li>
    </ul>

    <ul id="lista3">
        <li>
           Hora: <span class="px-3"><?php
                    try {
                        $date = new DateTime($movement->date);
                        echo $date->format('H:i:s');
                    } catch (Exception $e) {
                        echo "";
                    }
                    ?></span>
        </li>
        <li>
           Tipo: <span class="px-3"><?php echo $movement->m_description;?></span>
        </li>
    </ul>

    <ul id="lista4">
        <li>
           Atendido por: <span class="px-3"><?php echo $movement->user_name;?></span>
        </li>  
        <li>
           Razón Social: <span><?php echo $_SESSION['infoCompany']->company_name; ?></span>
        </li>
    </ul>

    <ul id="lista5">
        <li>
            Cuit: <?php echo $_SESSION['infoCompany']->cuit; ?>
        </li>
        <li>
            Dirección: <?php echo $_SESSION['infoCompany']->address; ?>
        </li>
    </ul>

    <ul id="lista6">
        <li>
            Tel:<span> <?php echo $_SESSION['infoCompany']->tel_number; ?></span>
        </li>
        <li>
            Email:<span> <?php echo $_SESSION['infoCompany']->email; ?></span>
        </li>
    </ul>
    <br>
    <table border=1 class="table table-striped">
        <thead>
        <tr>
            <th class="celda">Nro</th>
            <th class="celda">Descripción</th>
            <th class="celda">Código</th>
            <th class="celda">Cantidad</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $index = 0;
        $products = (new Movement())->FindMovementProductsById($movement->id_movement);
        foreach ($products as $prd) { ;
            $index += 1;?>
        <tr>
            <td id="nro"><?php echo $index ?></td>
            <td class="fila" class="celda">
                <b><?php echo $prd->brand; ?></b>
                <p>
                    <?php echo $prd->description; ?>
                </p>
            </td>
            <td class="celda"><br><?php echo $prd->barcode; ?></td>
            <td class="cantidad"><br><?php echo $prd->quantity; ?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
    <br><br>
    <div class="text-muted">Copyright &copy; Alumnos isft177</div>

</body>
</html>

<?php
//Guardar contenido html en variable
$html=ob_get_clean();


require_once 'Assets/libreria/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();

//opciones para permitir que obtenga el html css y mostrar en una lista
$options = $dompdf->getOptions();                   //recuperar opcion
$options->set(array('isRemoteEnabled' => true));    //activar opción
$dompdf->setOptions($options);                      //pasarlo al objeto dompdf

//cargamos el contenido html
$dompdf->loadHtml($html);

$dompdf->setPaper('letter');
$dompdf->render();

// Enviamos el fichero PDF al navegador.
    $dompdf->stream("movimiento_$movement->ticket_number.pdf", array("Attachment" => true));
?>
