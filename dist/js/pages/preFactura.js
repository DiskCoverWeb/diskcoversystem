$(document).ready(function () {
  $("#btnSalirModuloPF").on("click", function () {
    document.getElementById('FInsPreFacturas').reset();
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

function OpenModalPreFactura(selects){
  $('.myModalNuevoCliente').modal('hide');
  $('#myModalPreFactura').modal('show');
  
  $.ajax({
    type: "POST",                 
    url: '../controlador/facturacion/facturar_pensionC.php?CatalogoProductosByPeriodo=true',
    dataType:'json', 
    success: function(productos)
    {
      for (var i = 1; i <= selects; i++) {
        $('#PFselectProducto'+i).select2({
          placeholder: 'Seleccione un producto',
          data : productos
        });
      }
                 
    }
  });
}

function GuardarPreFactura(selects) {
  //TODO 
  codigoCliente = $('#codigoCliente').val();
  $('#myModal_espera').modal('show');

  $.ajax({
    type: "POST",                 
    url: '../controlador/facturacion/facturar_pensionC.php?GuardarInsPreFacturas=true',
    data: $("#FInsPreFacturas").serialize(),
    beforeSend: function () {   
        $('#myModal_espera').modal('show');
    },    
    success: function(productos)
    {
      console.log(productos)
      alert('aun no se esta procesando la data')
      $('#myModal_espera').modal('hide');          
    },
    error: function () {
      $('#myModal_espera').modal('hide');
      alert("Ocurrio un error inesperado, por favor contacte a soporte.");
    }
  });
}

function EliminarPreFactura(selects) {
  //TODO 
  codigoCliente = $('#codigoCliente').val();
}