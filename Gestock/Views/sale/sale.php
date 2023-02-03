<?php include_once "Controllers/movement.controller.php"; ?>
 
 <?php include_once "Controllers/sale.controller.php"; ?>

<h3 class="page-header">Nuevo Movimiento</h3>

<?php echo isset($_SESSION['alert']) ? $_SESSION['alert'] : ''; unset($_SESSION['alert']);
    //var_dump($ticket);
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <a href="?c=clients&a=Nuevo" class="btn btn-primary btn_new_cliente" <?php echo $ticket != null && $ticket->client!=null ? "hidden" : "visible" ?>>
                    <i class="fas fa-user-plus"></i> Nuevo Cliente</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="card-title text-center">Datos del Destinatario</p>
                    <form method="post" name="form_new_cliente_venta" id="form_new_cliente_venta">
                        <div class="row" style="padding-bottom: 20px;">
                            <div class="col-lg-3">
                                <select name="ticket_type" id="ticket_type" class="form-control">
                                    <option value="" selected disabled>Tipo de Movimiento...</option>
                                    <?php
                                    $result=(new SaleController)->TicketTypes();
                                    foreach ($result as $gender) {
                                        ?>
                                        <option value="<?php echo $gender['id_ticket_type']?>"
                                                <?php
                                                    if($ticket != null && $ticket->id_ticket_type != null
                                                        && $ticket->id_ticket_type==$gender['id_ticket_type']) {
                                                        echo 'selected';
                                                    }
                                                ?>
                                                ><?php echo $gender['tt_description']?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <select name="recipient_type" id="recipient_type" class="form-control">
                                    <option value="" selected disabled>Tipo destinatario ...</option>
                                    <?php
                                    $result=(new SaleController)->RecipientTypes();
                                    foreach ($result as $recipientType) {
                                        var_dump($ticket);
                                        ?>
                                        <option value="<?php echo $recipientType->id_recipient_type?>"
                                            <?php
                                            if($ticket != null && $ticket->id_recipient_type != null
                                                && $ticket->id_recipient_type==$recipientType->id_recipient_type) {
                                                echo 'selected';
                                            }
                                            ?>
                                        ><?php echo $recipientType->rt_description; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="action" value="addCliente">
                        <input type="hidden" id="idcliente" value="<?php echo ($ticket && $ticket->client) ? $ticket->client->Id : '' ?>" name="idcliente" required>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>cuit/cuil</label>
                                    <input type="number" name="dni_cliente" id="dni_cliente" class="form-control" value="<?php echo ($ticket && $ticket->client) ? $ticket->client->Cuil : '' ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" name="nom_cliente" id="nom_cliente" class="form-control" value="<?php echo ($ticket && $ticket->client) ? $ticket->client->Nombre : '' ?>" disabled required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="number" name="tel_cliente" id="tel_cliente" class="form-control" value="<?php echo ($ticket && $ticket->client) ? $ticket->client->Telefono : '' ?>" disabled required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Dirreción</label>
                                    <input type="text" name="dir_cliente" id="dir_cliente" class="form-control"
                                           value="<?php echo ($ticket != null && $ticket->client != null && $ticket->client->Direccion != null) ? $ticket->client->Direccion . ' ' . ($ticket->client->NumeroDireccion != null ? $ticket->client->NumeroDireccion : '') : '' ?>" disabled required>
                                </div>

                            </div>
                            <div id="div_registro_cliente" style="display: none;">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!--<h5 style="margin-top: 20px;" class="text-center">Datos Venta </h5>-->
            <div class="row" style="margin-top: 20px;">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> VENDEDOR: <span style="font-size: 16px; text-transform: uppercase; color: blue;"><?php echo $_SESSION['valido']->name ?></span></label>
                    </div>
                    <div id="div_nro_ticket" class="form-group" <?php echo $ticket && $ticket->id_ticket? 'visible' : 'hidden' ?>>
                        <label id="id_label_nro_ticket"><i class="fas fa-file"></i> Nro de Ticket: <span style="font-size: 16px; text-transform: uppercase; color: blue;"><?php echo $ticket ? $ticket->id_ticket : '' ?></span></label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label>Acciones</label>
                    <div id="acciones_venta" class="form-group">
                        <a href="#" class="btn btn-danger" id="btn_anular_venta">Anular</a>
                        <a href="#" class="btn btn-primary"  id="btn_facturar_venta"><i class="fas fa-save"></i> Generar Venta</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th width="100px">Código</th>
                        <th>Des.</th>
                        <th>Stock</th>
                        <th width="100px">Cantidad</th>
                        <th hidden class="textright">Precio</th>
                        <th hidden class="textright">Precio Total</th>
                        <th>Acciones</th>
                    </tr>
                    <tr>
                        <td hidden><input type="number" name="txt_id_producto" id="txt_id_producto"></td>
                        <td><input type="number" name="txt_cod_producto" id="txt_cod_producto"></td>
                        <td id="txt_descripcion">-</td>
                        <td id="txt_existencia">-</td>
                        <td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
                        <td hidden id="txt_precio" class="textright">0.00</td>
                        <td hidden id="txt_precio_total" class="txtright">0.00</td>
                        <td><a href="#" id="add_product_venta" class="btn btn-dark" style="display: none;">Agregar</a></td>
                    </tr>
                    <tr>
                        <th>Código</th>
                        <th colspan="2">Descripción</th>
                        <th>Cantidad</th>
                        <th hidden class="textright">Precio</th>
                        <th hidden class="textright">Precio Total</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody id="detalle_venta">
                    <!-- Contenido ajax -->
                    <?php

                    if($ticket) {
                            $sub_total = 0;
                            $total = 0;

                            foreach ($ticket->products as $data) {
                                $precioTotal = round($data['quantity'] * $data['cur_price'], 2);
                                $sub_total = round($sub_total + $precioTotal, 2);
                                $total = round($total + $precioTotal, 2);

                                $impuesto += round(($data['tax_value'] > 0 ? $sub_total / $data['tax_value'] : 1), 2);

                                echo "<tr>" .
                                    "<td>" . $data['barcode'] . "</td>" .
                                    "<td colspan=\"2\">" . $data['description'] . "</td>" .
                                    "<td class=\"textcenter\">" . $data['quantity'] . "</td>" .
                                    "<td hidden class=\"textright\">" . $data['cur_price'] . "</td>" .
                                    "<td hidden class=\"textright\">" . $precioTotal . "</td>" .
                                    "<td>" .
                                    "<a href=\"#\" class=\"btn btn-danger\" onclick=\"event.preventDefault(); delete_product(" . $data['id_product'] . ");\">" .
                                    "<i class=\"fas fa-trash-alt\"></i>" .
                                    "</a>" .
                                    "</td>" .
                                    "</tr>";
                            }

                            $impuesto = round($impuesto, 2);
                            $tl_sniva = round($sub_total - $impuesto, 2);
                            $total = round($tl_sniva + $impuesto, 2);
                        }
                    ?>

                    </tbody>

                    <tfoot id="detalle_totales" hidden>
                    <!-- Contenido ajax -->
                    <?php
                        if($ticket) {
                            echo '<tr>
                                <td colspan="5" class="textright text-black">Sub_Total</td>
                                <td class="textright">$ '.$tl_sniva.'</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="textright text-black">Impuestos</td>
                                <td class="textright">$ '. $impuesto.'</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="textright text-danger">Total</td>
                                <td class="textright text-danger">$ '.$total.'</td>
                            </tr>';
                        }
                        ?>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->




<!--<div class="">
    <div class="col margin-botton-20">
        <form action="?c=sale&a=BuscarProducto" method="post">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="search"><i class="fa fa-search"></i></span>
                </div>
                <input id="criteria" name="criteria" type="text" class="form-control" placeholder="Buscar producto"
                       aria-label="Buscar producto" aria-describedby="search"
                       value="<?php echo isset($_SESSION["current_filter"]) ? $_SESSION["current_filter"] : '' ; ?>">
            </div>
        </form>
    </div>
    <div class="col">
        <div class="row">
            <?php for($i=0; $i<sizeof($_SESSION["filtered_products"]); $i++) { ?>
            <div class="col-auto margin-botton-20">
                <div class="card card-width-14">
                    <div class="ribbon ribbon-top-right" <?php echo $i%2 == 0 ? "visible" : "hidden" ?> >
                        <span><?php echo "Agotado" ?></span>
                    </div>
                    <i class="fa
                        <?php if($i%2==0) { echo " fa-exclamation-triangle";} else { echo " fa-check-circle"; }
    if($i==1 || $i == 5) { echo " fa-lightbulb";}
    ?>"
                       data-toggle="tooltip" data-placement="right" title="<?php echo $i . " productos restantes" ?>"
                    ></i>
                    <img src="Assets/img/logo.jpeg" class="card-img-top" alt="...">
                    <div class="card-body text-center">
                        <h5 class="card-title" style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box;
                                                      -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical;"
                            data-toggle="tooltip" data-placement="right" title="<?php echo $_SESSION["filtered_products"][$i]->description; ?>">
                            <?php echo $_SESSION["filtered_products"][$i]->description; ?>
                        </h5>
                        <p class="card-text"><small class="text-muted"><?php echo $_SESSION["filtered_products"][$i]->brand; ?></small></p>
                        <p class="card-text">$ <?php echo $_SESSION["filtered_products"][$i]->price; ?></p>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="btn-group mr-1" role="group">
                                    <button type="button" class="btn btn-secondary" onclick="decrement('qty<?php echo "-".$i?>')">-</button>
                                    <input id="qty<?php echo "-".$i?>" name="qty<?php echo "-".$i?>" type="tel" value="0" class="form-control"
                                           style="border-radius: 0rem; width: 80%;padding: 0rem; text-align: center">
                                    <button type="button" class="btn btn-secondary" onclick="increment('qty<?php echo "-".$i?>')">+</button>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="btn-group mr-1" role="group">
                                    <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="right" title="Agregar al carrito">
                                        <i class="fa fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php  } ?>
        </div>
    </div>
    <div class="col text-center">
        <div class="btn-group mr-1" role="group">
            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="">
                <i class="fa fa-search"></i> Ver Más
            </button>
        </div>
    </div>

    <script type="text/javascript">
        function increment(id) {
            let qty = document.getElementById(id);

            if(isNaN(parseInt(qty.value))) {
                qty.value = "0";
            }

            qty.value = parseInt(qty.value) + 1;
        }

        function decrement(id) {
            let qty = document.getElementById(id);

            if(isNaN(parseInt(qty.value))) {
                qty.value = 0;
            }
            if(parseInt(qty.value) - 1 >= 0) {
                qty.value = parseInt(qty.value) - 1;
            }
        }
    </script>
</div>-->