$(document).ready(function () {
  $("#btnSalirModuloPF").on("click", function () {
    document.getElementById('FInsPreFacturas').reset();
    for (var i = 1; i <= cantidadProductoPreFacturar; i++) {
      MostrarOcultarContenedorDataPFCheck("PFcheckProducto"+i, i)
    }
  })

  //inicio Mostar ContenedorDataPFCheck
  for (var i = 1; i <= cantidadProductoPreFacturar; i++) {
    MostrarOcultarContenedorDataPFCheck("PFcheckProducto"+i, i)
  }

  $('.PFcheckProducto').on('change', function (e) {
    MostrarOcultarContenedorDataPFCheck(e.target.id, $(this).data().indice)
  })
  // fin  Mostar ContenedorDataPFCheck

});

function MostrarOcultarContenedorDataPFCheck(id, i) {
  if($("#"+id).prop('checked')){
    $('.ContenedorDataPFCheck'+i).show()
  }else{
    $('.ContenedorDataPFCheck'+i).hide()
  }
}

function OpenModalPreFactura(cantidadProductos){
  $('.myModalNuevoCliente').modal('hide');
  $('#myModalPreFactura').modal('show');
  
  $.ajax({
    type: "POST",                 
    url: '../controlador/facturacion/facturar_pensionC.php?CatalogoProductosByPeriodo=true',
    dataType:'json', 
    success: function(productos)
    {
      for (var i = 1; i <= cantidadProductos; i++) {
        $('#PFselectProducto'+i).select2({
          placeholder: 'Seleccione un producto',
          data : productos
        });
      }
                 
    }
  });
}

function GuardarPreFactura() {
  $('#myModal_espera').modal('show');

  $.ajax({
    type: "POST",                 
    url: '../controlador/facturacion/facturar_pensionC.php?GuardarInsPreFacturas=true',
    data: $("#FInsPreFacturas").serialize(),
    dataType:'json', 
    beforeSend: function () {   
        $('#myModal_espera').modal('show');
    },    
    success: function(response)
    {
      $('#myModal_espera').modal('hide');  
      if(response.rps){
        if(response.mensaje_extra){
          Swal.fire(response.mensaje, response.mensaje_extra, 'success')
        }else{
          Swal.fire('¡Bien!', response.mensaje, 'success')
        }
        $('#myModalPreFactura').modal('hide');
      }else{
        Swal.fire('¡Oops!', response.mensaje, 'warning')
      }        
    },
    error: function () {
      $('#myModal_espera').modal('hide');
      alert("Ocurrio un error inesperado, por favor contacte a soporte.");
    }
  });
}

function EliminarPreFactura() {
  Swal.fire({
    title: '¿ESTA SEGURO DE ELIMINAR LA PREFACTURACION DE ESTE CLIENTE?',
    text: "¡NO PODRA REVERSAR ESTE PROCESO!",
    type: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Eliminar'
  }).then((result) => {
    if (result.value) {
      $('#myModal_espera').modal('show');

      $.ajax({
        type: "POST",                 
        url: '../controlador/facturacion/facturar_pensionC.php?EliminarInsPreFacturas=true',
        data: $("#FInsPreFacturas").serialize(),
        dataType:'json', 
        beforeSend: function () {   
            $('#myModal_espera').modal('show');
        },    
        success: function(response)
        {
          $('#myModal_espera').modal('hide');  
          if(response.rps){
            if(response.mensaje_extra){
              Swal.fire(response.mensaje, response.mensaje_extra, 'success')
            }else{
              Swal.fire('¡Bien!', response.mensaje, 'success')
            }
            $('#myModalPreFactura').modal('hide');
          }else{
            Swal.fire('¡Oops!', response.mensaje, 'warning')
          }        
        },
        error: function () {
          $('#myModal_espera').modal('hide');
          alert("Ocurrio un error inesperado, por favor contacte a soporte.");
        }
      });

    }
  })
}