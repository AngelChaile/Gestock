$(document).ready(function(){
  viewProcesar();
  $('.btnMenu').click(function(e) {
    e.preventDefault();
    if($('nav').hasClass('viewMenu')) {
      $('nav').removeClass('viewMenu');
    }else {
      $('nav').addClass('viewMenu');
    }
  });

  $('nav ul li').click(function() {
    $('nav ul li ul').slideUp();
    $(this).children('ul').slideToggle();
  });
// Modal Agregar
    $('.add_product').click(function(e) {
      e.preventDefault();
      var producto = $(this).attr('product');
      var action = 'infoProducto';
      $.ajax({
        url: 'modal.php',
        type: 'POST',
        async: true,
        data: {action:action,producto:producto},

        success: function(response) {
        if (response != 0) {
          var info = JSON.parse(response);
        //  $('#producto_id').val(info.codproducto);
        //  $('.nameProducto').html(info.descripcion);

          $('.bodyModal').html('<form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">'+
            '<h1>Agregar Producto</h1><br>'+
            '<h2 class="nameProducto">'+info.descripcion+'</h2>'+
            '<br>'+
            '<hr>'+
            '<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad del Producto" required><br>'+
            '<input type="number" name="precio" id="txtPrecio" placeholder="Precio del Producto" required>'+
            '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" required><br>'+
            '<input type="hidden" name="action" value="addProduct" required>'+
            '<div class="alert alertAddProduct"></div>'+
            '<button type="submit" class="btn_new">Agregar</button>'+
            '<a href="#" class="btn_ok closeModal" onclick="closeModal();">Cerrar</a>'+

          '</form>');
        }
        },
        error: function(error) {
          console.log(error);
        }
        });

      $('.modal').fadeIn();

    });
// modal Eliminar producto
$('.del_product').click(function(e) {
  e.preventDefault();
  var producto = $(this).attr('product');
  var action = 'infoProducto';
  $.ajax({
    url: 'modal.php',
    type: 'POST',
    async: true,
    data: {action:action,producto:producto},

    success: function(response) {
    if (response != 0) {
      var info = JSON.parse(response);
    //  $('#producto_id').val(info.codproducto);
    //  $('.nameProducto').html(info.descripcion);

      $('.bodyModal').html('<form action="" method="post" name="form_del_product" id="form_del_product" onsubmit="event.preventDefault(); delProduct();">'+
        '<h2 style="color: red; font-size: 18px;">¿Estás seguro de eliminar el Producto</h2>'+
        '<h2 class="nameProducto">'+info.descripcion+'</h2>'+
        '<hr>'+
        '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" required><br>'+
        '<input type="hidden" name="action" value="delProduct" required>'+
        '<div class="alert alertAddProduct"></div>'+
        '<input type="submit"  value="Aceptar" class="ok"><br>'+
        '<a href="#" style="text-align: center;" class="btn_cancelar" onclick="closeModal();">Cerrar</a>'+
      '</form>');
    }
    },
    error: function(error) {
      console.log('error');
    }
    });

  $('.modal').fadeIn();

});

$('#search_proveedor').change(function(e) {
  e.preventDefault();
  var sistema = getUrl();
  location.href = sistema+'buscar_productos.php?proveedor='+$(this).val();

});

// activa campos para registrar Cliente
/*$('.btn_new_cliente').click(function(e) {
  e.preventDefault();
  $('#nom_cliente').removeAttr('disabled');
  $('#tel_cliente').removeAttr('disabled');
  $('#dir_cliente').removeAttr('disabled');

  $('#div_registro_cliente').slideDown();

});*/

// buscar Cliente
$('#dni_cliente').keyup(function(e) {
  e.preventDefault();
  var cl = $(this).val();
  var action = 'buscarCliente';
  const recipientType = $('#recipient_type').val();
  $.ajax({
    url: 'ajax-router.php',
    type: "POST",
    async: true,
    data: {action:action,cliente:cl, recipientType: recipientType},
    success: function(response) {
      if (response == 0) {
        $('#idcliente').val('');
        $('#nom_cliente').val('');
        $('#tel_cliente').val('');
        $('#dir_cliente').val('');
        // mostar boton agregar
        $('.btn_new_cliente').slideDown();
      }else {
        var data = $.parseJSON(response);
        $('#idcliente').val(data.cuit_cuil);
        $('#nom_cliente').val(data.customer_name);
        $('#tel_cliente').val(data.tel_number);
        $('#dir_cliente').val((data.street !== undefined ? data.street : '') + ' ' + (data.address_number !== undefined ? data.address_number : ''));
        // ocultar boton Agregar
        $('.btn_new_cliente').slideUp();

        // Bloque campos
        $('#nom_cliente').attr('disabled','disabled');
        $('#tel_cliente').attr('disabled','disabled');
        $('#dir_cliente').attr('disabled','disabled');
        // ocultar boto Guardar
        $('#div_registro_cliente').slideUp();
      }
    },
    error: function(error) {

    }
  });

});

// crear cliente = Ventas
$('#form_new_cliente_venta').submit(function(e) {
  e.preventDefault();
  $.ajax({
    url: 'modal.php',
    type: "POST",
    async: true,
    data: $('#form_new_cliente_venta').serialize(),
    success: function(response) {
      if (response  != 0) {
        // Agregar id a input hidden
        $('#idcliente').val(response);
        //bloque campos
        $('#nom_cliente').attr('disabled','disabled');
        $('#tel_cliente').attr('disabled','disabled');
        $('#dir_cliente').attr('disabled','disabled');
        // ocultar boton Agregar
        $('.btn_new_cliente').slideUp();
        //ocultar boton Guardar
        $('#div_registro_cliente').slideDown();
      }
    },
    error: function(error) {
    }
  });
});

// buscar producto = Ventas
$('#txt_cod_producto').keyup(function(e) {
  e.preventDefault();

  if($('#ticket_type').val() == null) {
    //const sa = new sweetAlert('Debe seleccionar el Tipo de Movimiento a realizar');
    new Swal("", "Debe seleccionar el Tipo de Movimiento a realizar",
        undefined, "", "success", undefined, undefined,
        undefined, undefined, "Footer", true, true /*toast*/, "body", undefined,
        undefined/*ancho*/, undefined, undefined, undefined, 'center', false, undefined,
        undefined, false, true, false, true, true, true, false, true /*confirm BTN*/,
        false /* cancel BTN*/, 'Aceptar', 'Cancelar', undefined, undefined, undefined,
        '','','',true,false,true,true,false,false,false,'','','',false,false,true,
        undefined,
        undefined,false);
    return;
  }
  if($('#recipient_type').val() == null) {
    //const sa = new sweetAlert('Debe seleccionar el Tipo de Destinatario del Movimiento');
    new Swal("", "Debe seleccionar el Tipo de Destinatario del Movimiento",
        undefined, "", "success", undefined, undefined,
        undefined, undefined, "Footer", true, true /*toast*/, "body", undefined,
        undefined/*ancho*/, undefined, undefined, undefined, 'center', false, undefined,
        undefined, false, true, false, true, true, true, false, true /*confirm BTN*/,
        false /* cancel BTN*/, 'Aceptar', 'Cancelar', undefined, undefined, undefined,
        '','','',true,false,true,true,false,false,false,'','','',false,false,true,
        undefined,
        undefined,false);
    return;
  }

  var productos = $(this).val();
  if (productos == "") {
    $('#txt_descripcion').html('-');
    $('#txt_existencia').html('-');
    $('#txt_cant_producto').val('0');
    $('#txt_precio').html('0.00');
    $('#txt_precio_total').html('0.00');

    //Bloquear Cantidad
    $('#txt_cant_producto').attr('disabled', 'disabled');
    // Ocultar Boto Agregar
    $('#add_product_venta').slideUp();
  }
  var action = 'buscarProducto';
  if (productos != '') {
  $.ajax({
    url: 'ajax-router.php',
    type: "POST",
    async: true,
    data: {action:action,criteria:productos},
    success: function(response){
      if(response == 0) {
        $('#txt_descripcion').html('-');
        $('#txt_existencia').html('-');
        $('#txt_cant_producto').val('0');
        $('#txt_precio').html('0.00');
        $('#txt_precio_total').html('0.00');

        //Bloquear Cantidad
        $('#txt_cant_producto').attr('disabled','disabled');
        // Ocultar Boto Agregar
        $('#add_product_venta').slideUp();
      }else{
        var product = JSON.parse(response);

        $('#txt_descripcion').html(product[0].description);
        $('#txt_existencia').html(product[0].qty);
        $('#txt_cant_producto').val('1');
        $('#txt_precio').html(product[0].price);
        $('#txt_precio_total').html(product[0].price);
        $('#txt_id_producto').val(product[0].id_product);
        // Activar Cantidad
        $('#txt_cant_producto').removeAttr('disabled');
        //$('#txt_cod_producto').val(product[0].barcode);
        // Mostar boton Agregar
        $('#add_product_venta').slideDown();

      }
    },
    error: function(error) {
    }
  });

  $('#txt_descripcion').html('-');
  $('#txt_existencia').html('-');
  $('#txt_cant_producto').val('0');
  $('#txt_precio').html('0.00');
  $('#txt_precio_total').html('0.00');

  //Bloquear Cantidad
  $('#txt_cant_producto').attr('disabled','disabled');
  // Ocultar Boto Agregar
  $('#add_product_venta').slideUp();

  }
});

// calcular el Total
$('#txt_cant_producto').keyup(function(e) {
  e.preventDefault();
  var precio_total = $(this).val() * $('#txt_precio').html();
  var existencia = parseInt($('#txt_existencia').html());
  $('#txt_precio_total').html(precio_total);
  const ticketType = $("#ticket_type  option:selected").val();
  // Ocultat el boton Agregar si la cantidad es menor que 1

  if(ticketType == 1) {
    $('#add_product_venta').slideDown();
    return;
  }

  if (($(this).val() < 1 || isNaN($(this).val())) || ($(this).val() > existencia)){
    $('#add_product_venta').slideUp();
  }else {
    $('#add_product_venta').slideDown();
  }
});

// Agregar producto al detalle_venta
$('#add_product_venta').click(function(e) {
  e.preventDefault();
  if ($('#txt_cant_producto').val() > 0) {
    var codproducto = $('#txt_cod_producto').val();
    var idproducto = $('#txt_id_producto').val();
    var cantidad = $('#txt_cant_producto').val();
    const ticketType = $('#ticket_type').val();
    var action = 'agregarProductoATicket';

    $.ajax({
      url: 'ajax-router.php',
      type: 'POST',
      async: true,
      data: {action:action,producto:idproducto,cantidad:cantidad, ticketType:ticketType},
      success: function(response) {
        if (response !== 'Stock Insuficiente') {   //Ver Como se podría tratar
          drawItems(response);
        }else {
          console.log('No hay dato');
          //const sa = new sweetAlert(response);
          new Swal("", response,
              undefined, "", "success", undefined, undefined,
              undefined, undefined, "Footer", true, true /*toast*/, "body", undefined,
              undefined/*ancho*/, undefined, undefined, undefined, 'center', false, undefined,
              undefined, false, true, false, true, true, true, false, true /*confirm BTN*/,
              false /* cancel BTN*/, 'Aceptar', 'Cancelar', undefined, undefined, undefined,
              '','','',true,false,true,true,false,false,false,'','','',false,false,true,
              undefined,
              undefined,false);
        }
        viewProcesar();
      },
      error: function(error) {
        console.log("Ocurrió un error: ", error);
        //new sweetAlert(error.responseText);
        new Swal("", error.responseText,
            undefined, "", "success", undefined, undefined,
            undefined, undefined, "Footer", true, true /*toast*/, "body", undefined,
            undefined/*ancho*/, undefined, undefined, undefined, 'center', false, undefined,
            undefined, false, true, false, true, true, true, false, true /*confirm BTN*/,
            false /* cancel BTN*/, 'Aceptar', 'Cancelar', undefined, undefined, undefined,
            '','','',true,false,true,true,false,false,false,'','','',false,false,true,
            undefined,
            undefined,false);
      }
    });
  }
});

// anular venta
$('#btn_anular_venta').click(function(e) {
  e.preventDefault();
  //var rows = $('#detalle_venta tr').length;
  //if (rows > 0) {
    var action = 'anularVenta';
    $.ajax({
      url: 'ajax-router.php',
      type: 'POST',
      async: true,
      data: {action:action},
      success: function(response) {
        //FIXME: Manejar errores o mostrar un mensaje antes del reload
        if (response != 0) {
          //location.reload();
        }
        location.reload();
      },
      error: function(error) {

      }
    });
  //}
});
// facturar venta
$('#btn_facturar_venta').click(function(e) {
  e.preventDefault();
  var rows = $('#detalle_venta tr').length;
  var codcliente = $('#idcliente').val();

  if(codcliente === undefined || codcliente === '') {
    //const sa = new sweetAlert('Debe ingresar el ' + $('#recipient_type option:selected').text());
    new Swal("", "Debe ingresar el " + $('#recipient_type option:selected').text(),
        undefined, "", "success", undefined, undefined,
        undefined, undefined, "Footer", true, true /*toast*/, "body", undefined,
        undefined/*ancho*/, undefined, undefined, undefined, 'center', false, undefined,
        undefined, false, true, false, true, true, true, false, true /*confirm BTN*/,
        false /* cancel BTN*/, 'Aceptar', 'Cancelar', undefined, undefined, undefined,
        '','','',true,false,true,true,false,false,false,'','','',false,false,true,
        undefined,
        undefined,false);
    return;
  }

  if($('#ticket_type').val() == null) {
    //const sa = new sweetAlert('Debe seleccionar el Tipo de Movimiento a realizar');
    new Swal("", "Debe seleccionar el Tipo de Movimiento a realizar",
        undefined, "", "success", undefined, undefined,
        undefined, undefined, "Footer", true, true /*toast*/, "body", undefined,
        undefined/*ancho*/, undefined, undefined, undefined, 'center', false, undefined,
        undefined, false, true, false, true, true, true, false, true /*confirm BTN*/,
        false /* cancel BTN*/, 'Aceptar', 'Cancelar', undefined, undefined, undefined,
        '','','',true,false,true,true,false,false,false,'','','',false,false,true,
        undefined,
        undefined,false);
    return;
  }

  if($('#recipient_type').val() == null) {
    //const sa = new sweetAlert('Debe seleccionar el Tipo de Destinatario del Movimiento');
    new Swal("", "Debe seleccionar el Tipo de Destinatario del Movimiento",
        undefined, "", "success", undefined, undefined,
        undefined, undefined, "Footer", true, true /*toast*/, "body", undefined,
        undefined/*ancho*/, undefined, undefined, undefined, 'center', false, undefined,
        undefined, false, true, false, true, true, true, false, true /*confirm BTN*/,
        false /* cancel BTN*/, 'Aceptar', 'Cancelar', undefined, undefined, undefined,
        '','','',true,false,true,true,false,false,false,'','','',false,false,true,
        undefined,
        undefined,false);
    return;
  }

  console.log("CodCliente: ", codcliente);
  if (rows > 0 && codcliente !== undefined) {
    var action = 'procesarVenta';

    $.ajax({
      url: 'ajax-router.php',
      type: 'POST',
      async: true,
      data: {action:action,codcliente:codcliente},
      success: function(response) {
      if (response != 0) {
        var info = JSON.parse(response);
        //console.log(info);
        //generarPDF(info.codcliente,info.nofactura);

        //Pasar sólo los parámetros necesarios
        const popup = new Swal("", "Movimiento procesado correctamente con número: " + info.id_ticket,
            undefined, "", "success", undefined, undefined,
            undefined, undefined, "Footer", true, true /*toast*/, "body", undefined,
            undefined/*ancho*/, undefined, undefined, undefined, 'center', false, undefined,
            undefined, false, true, false, true, true, true, false, true /*confirm BTN*/,
            false /* cancel BTN*/, 'Aceptar', 'Cancelar', undefined, undefined, undefined,
            '','','',true,false,true,true,false,false,false,'','','',false,false,true,
            undefined,
            undefined,false);
        popup.then((result) => {
          if (result.isConfirmed) {
            location.reload();
          }
        })

      }else {
        console.log('no hay dato');
      }
      },
      error: function(error) {
        console.log("Ocurrió un error: " + action, error);
        //new sweetAlert(error.responseText);
        new Swal("", error.responseText,
            undefined, "", "success", undefined, undefined,
            undefined, undefined, "Footer", true, true /*toast*/, "body", undefined,
            undefined/*ancho*/, undefined, undefined, undefined, 'center', false, undefined,
            undefined, false, true, false, true, true, true, false, true /*confirm BTN*/,
            false /* cancel BTN*/, 'Aceptar', 'Cancelar', undefined, undefined, undefined,
            '','','',true,false,true,true,false,false,false,'','','',false,false,true,
            undefined,
            undefined,false);
      }
    });
  }
});

//Ver Factura
$('.view_factura').click(function(e) {
  e.preventDefault();

  var codCliente = $(this).attr('cl');
  var noFactura = $(this).attr('f');

  generarPDF(codCliente,noFactura);
});

// Cambiar contraseña
$('.newPass').keyup(function() {
  validaPass();
});

// cambiar contraseña
$('#frmChangePass').submit(function(e){
  e.preventDefault();
  var passActual = $('#actual').val();
  var passNuevo = $('#nueva').val();
  var passconfir = $('#confirmar').val();
  var action = "changePasword";
  if (passNuevo != passconfir) {
    $('.alertChangePass').html('<p style="color:red;">Las contraseñas no Coinciden</p>');
    $('.alertChangePass').slideDown();
    return false;
    }
  if (passNuevo.length < 5) {
  $('.alertChangePass').html('<p style="color:orangered;">Las contraseñas deben contener como mínimo 5 caracteres');
  $('.alertChangePass').slideDown();
  return false;
  }
  $.ajax({
    url: 'modal.php',
    type: 'POST',
    async: true,
    data: {action:action,passActual:passActual,passNuevo:passNuevo},
    success: function(response) {
      if (response != 'error') {
        var info = JSON.parse(response);
        if (info.cod == '00') {
          $('.alertChangePass').html('<p style="color:green;">'+info.msg+'</p>');
          $('#frmChangePass')[0].reset();
        }else {
          $('.alertChangePass').html('<p style="color:green;">'+info.msg+'</p>');
        }
        $('.alertChangePass').slideDown();
      }
    },
    error: function(error) {
    }
  });
});

$(".confirmar").submit(function(e) {
  e.preventDefault();
  Swal.fire({
    title: 'Esta seguro de eliminar?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'SI, Eliminar!'
  }).then((result) => {
    if (result.isConfirmed) {
      this.submit();
    }
  })
})


}); // fin ready

function validaPass() {
  var passNuevo = $('#nueva').val();
  var confirmarPass = $('#confirmar').val();
  if (passNuevo != confirmarPass) {
    $('.alertChangePass').html('<p style="color:red;">Las contraseñas no Coinciden</p>');
    $('.alertChangePass').slideDown();
    return false;
  }
if (passNuevo.length < 5) {
  $('.alertChangePass').html('<p style="color:orangered;">Las contraseñas deben contener como mínimo 5 caracteres');
  $('.alertChangePass').slideDown();
  return false;
}

$('.alertChangePass').html('<p style="color:blue;">Las contraseñas Coinciden.</p>');
$('.alertChangePass').slideDown();
}
function generarPDF(cliente,factura) {
  url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;
  window.open(url, '_blank');
}
function delete_product(id_producto) {
  var action = 'eliminarProductoDeTicket';
  var id_detalle = id_producto;

  console.log("el id_producto es: ", id_detalle);

  $.ajax({
    url: 'ajax-router.php',
    type: "POST",
    async: true,
    data: {action:action,id_producto:id_detalle},
    success: function(response) {
      if (response != 0) {
        /*var info = JSON.parse(response);
        $('#detalle_venta').html(info.detalle);
        $('#detalle_totales').html(info.totales);
        $('#txt_cod_producto').val('');
        $('#txt_descripcion').html('-');
        $('#txt_existencia').html('-');
        $('#txt_cant_producto').val('0');
        $('#txt_precio').html('0.00');
        $('#txt_precio_total').html('0.00');

        // Bloquear cantidad
        $('#txt_cant_producto').attr('disabled','disabled');

        // Ocultar boton agregar
        $('#add_product_venta').slideUp();*/

          drawItems(response);
      }else {
        $('#detalle_venta').html('');
        $('#detalle_totales').html('');
      }
      viewProcesar();
    },
    error: function(error) {
      
    }
  });
}

// mostrar/ ocultar boton Procesar
function viewProcesar() {
  if ($('#detalle_venta tr').length > 0){
    $('#btn_facturar_venta').show()
    //attr('visible', 'visible') //show();
    //$('#btn_anular_venta').show();
  }else {
    $('#btn_facturar_venta').hide();
    //$('#btn_anular_venta').hide();
  }
}

function searchForDetalle(id) {
  var action = 'searchForDetalle';
  var user = id;
  $.ajax({
    url: 'modal.php',
    type: "POST",
    async: true,
    data: {action:action,user:user},
    success: function(response) {
      if (response == 0) {
        console.log('');
      }else {
        var info = JSON.parse(response);
        $('#detalle_venta').html(info.detalle);
        $('#detalle_totales').html(info.totales);
      }
      viewProcesar();
    },
    error: function(error) {

    }
  });
}

function getUrl() {
  var loc = window.location;
  var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/')+ 1);
  return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}
// funcion para agregar producto
function sendDataProduct() {
  $('.alertAddProduct').html('');
  $.ajax({
    url: 'modal.php',
    type: 'POST',
    async: true,
    data: $('#form_add_product').serialize(),
    success: function(response) {
      if (producto == 'error') {
        $('.alertAddProduct').html('<p style="color : red;">Error al agregar producto.</p>');

      }else {
        var info = JSON.parse(response);
        $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
        $('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
        $('#txtCantidad').val('');
        $('#txtPrecio').val('');
        $('.alertAddProduct').html('<p>Producto Agregado Corectamente.</p>');

      }
    },
    error: function(error) {
      console.log(error);

    }
  });

}
// funcion para elimar producto
function delProduct() {
  var pr = $('#producto_id').val();
  $('.alertAddProduct').html('');
  $.ajax({
    url: 'modal.php',
    type: 'POST',
    async: true,
    data: $('#form_del_product').serialize(),
    success: function(response) {

      if (response == 'error') {
        $('.alertAddProduct').html('<p style="color : red;">Error al eliminar producto.</p>');

      }else {

        $('.row'+pr).remove();
        $('#form_del_product .ok').remove();
        $('.alertAddProduct').html('<p>Producto Eliminado Corectamente.</p>');

      }
    },
    error: function(error) {
      console.log(error);

    }
  });

}

function drawItems(response) {
  var info = JSON.parse(response);

  function round(a, b) {
    return parseFloat(parseFloat(a).toFixed(2));
  }
  var detalleVenta = '';
  var detalleTotales = '';
  var $sub_total = 0;
  var $total = 0;
  var $impuesto = 0;
  var $precioTotal=0;

  $('#div_nro_ticket').removeAttr('hidden')
  $('#id_label_nro_ticket').html('<i class="fas fa-file"></i> Nro de Ticket: <span style="font-size: 16px; text-transform: uppercase; color: blue;">' + info.id_ticket +'</span>');

  for (const $data of info.products) {
    $precioTotal = round($data['quantity'] * $data['cur_price'], 2);
    $sub_total = round($sub_total + $precioTotal, 2);
    $total = round($total + $precioTotal, 2);

    $impuesto += round(($data['tax_value'] > 0 ? $sub_total / $data['tax_value'] : 1), 2);

    detalleVenta += "<tr>" +
        "<td> " + $data.barcode + "</td>" +
        "<td colspan=\"2\">" + $data.description + "</td>" +
        "<td class=\"textcenter\">" + $data.quantity + "</td>" +
        "<td hidden class=\"textright\">" + $data.cur_price + "</td>" +
        "<td hidden class=\"textright\">" + $precioTotal + "</td>" +
        "<td>" +
        "<a href=\"#\" class=\"btn btn-danger\" onclick=\"event.preventDefault(); delete_product(" + $data['id_product'] + ");\" disabled>" +
        "<i class=\"fas fa-trash-alt\"></i>" +
        "</a>" +
        "</td>" +
        "</tr>";
  }

  $impuesto = round($impuesto, 2);
  var $tl_sniva = round($sub_total - $impuesto, 2);
  var $total = round($tl_sniva + $impuesto, 2);

  detalleTotales += "<tr><td colspan=\"5\" class=\"textright text-black\">Sub_Total</td>" +
      "<td class=\"textright\">$ " + $tl_sniva + "</td>" +
      "</tr><tr><td colspan=\"5\" class=\"textright text-black\">Impuestos</td>" +
      "<td class=\"textright\">$ " + $impuesto + "</td>" +
      "</tr><tr><td colspan=\"5\" class=\"textright text-danger\">Total</td>" +
      "<td class=\"textright text-danger\">$ " + $total + "</td></tr>";

  $('#detalle_venta').html(detalleVenta);
  $('#detalle_totales').html(detalleTotales);
  $('#txt_cod_producto').val('');
  $('#txt_id_producto').val('');
  $('#txt_descripcion').html('-');
  $('#txt_existencia').html('-');
  $('#txt_cant_producto').val('0');
  $('#txt_precio').html('0.00');
  $('#txt_precio_total').html('0.00');

  // Bloquear cantidad
  $('#txt_cant_producto').attr('disabled','disabled');

  // Ocultar boton agregar
  $('#add_product_venta').slideUp();

  //Datos del cliente
  $('#idcliente').val(info.client.Id);
  $('#dni_cliente').val(info.client.Cuil);
  $('#nom_cliente').val(info.client.Nombre);
  $('#tel_cliente').val(info.client.Telefono);
  $('#dir_cliente').val((info != null && info.client != null && info.client.Direccion != null) ? info.client.Direccion + ' ' + (info.client.NumeroDireccion != null ? info.client.NumeroDireccion : '') : '');
}

// Agregar tipo de ticket
$('#recipient_type').change(function(e) {
  e.preventDefault();
  const recipientType = $('#recipient_type').val();

  if ((recipientType > 0)) {
    const action = 'destinatarioTicket';

    $.ajax({
      url: 'ajax-router.php',
      type: "POST",
      async: true,
      data: {action:action, recipientType: recipientType},
      success: function(response) {
        if (response !== 'error') {
          drawItems(response);
        }else {
          $('#detalle_venta').html('');
          $('#detalle_totales').html('');
        }
        viewProcesar();
      },
      error: function(error) {

      }
    });
  }2
});

// Agregar tipo de destinatario para el ticket
$('#ticket_type').change(function(e) {
  e.preventDefault();
  const ticketType = $('#ticket_type').val();

  if ((ticketType > 0)) {
    const action = 'tipoTicket';

    $.ajax({
      url: 'ajax-router.php',
      type: "POST",
      async: true,
      data: {action:action, ticketType: ticketType},
      success: function(response) {
        if (response !== 'error') {
          drawItems(response);
        }else {
          $('#detalle_venta').html('');
          $('#detalle_totales').html('');
        }
        viewProcesar();
      },
      error: function(error) {

      }
    });
  }
});

$(document).ready(function () {
  $('#dtHorizontalVerticalExample').DataTable({
    "scrollX": false,
    "scrollY": 380,
    "language": {
      "paginate": {
        "previous": "Anterior",
        "next": "Siguiente",
      },
      "search": "",
      "searchPlaceholder": "Buscar",
      "lengthMenu": "Mostrar _MENU_ registros",
      "infoFiltered": " - filtrados de _MAX_ registros",
      "info": "Pagina _PAGE_ de _PAGES_",
      "infoEmpty": "Sin datos a mostrar",
      "zeroRecords": "Sin resultados",
    },
    "responsive": true,
  });
  $('.dataTables_length').addClass('bs-select');
});