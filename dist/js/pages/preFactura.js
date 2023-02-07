$(document).ready(function () {

});

function OpenModalPreFactura(){
    //TODO 
    codigoCliente = $('#codigoCliente').val();
    $('.myModalNuevoCliente').modal('hide');
    $('#myModalPreFactura').modal('show');
    
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?CatalogoProductosByPeriodo=true',
      dataType:'json', 
      success: function(data)
      {
        if (data) {
          datos = data;
          clave = 0;
          $("#cuerpoHistoria").empty();
          for (var indice in datos) {
            var tr = `<tr>
            </tr>`;
            $("#cuerpoHistoria").append(tr);
            clave++;
          }
        }else{
          console.log("No tiene datos");
        }            
      }
    });
  }
