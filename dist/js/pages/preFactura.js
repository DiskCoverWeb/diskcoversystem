$(document).ready(function () {

});

function OpenModalPreFactura(){
	alert("llamado a OpenModalPreFactura");
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
              <td><input style="border:0px;background:bottom" size="1" type ="text" id="TD`+clave+`" value ="`+datos[indice].TD+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="7" type ="text" id="Fecha`+clave+`" value ="`+datos[indice].Fecha+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="6" type ="text" id="Serie`+clave+`" value ="`+datos[indice].Serie+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="6" type ="text" id="Factura`+clave+`" value ="`+datos[indice].Factura+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="70" type ="text" id="Detalle`+clave+`" value ="`+datos[indice].Detalle+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="2" class="text-right" type ="text" id="Anio`+clave+`" value ="`+datos[indice].Anio+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="10" type ="text" id="Mes`+clave+`" value ="`+datos[indice].Mes+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="6" class="text-right" size="10px" type ="text" id="Total`+clave+`" value ="`+parseFloat(datos[indice].Total).toFixed(2)+`" disabled/></td>
              <td><input style="border:0px;background:bottom" size="6" class="text-right" type ="text" id="Abonos`+clave+`" value ="`+parseFloat(datos[indice].Abonos).toFixed(2)+`" disabled/></td>
              <td><input size="2" class="text-right" style="border:0px;background:bottom"  type ="text" id="Mes_No`+clave+`" value ="`+datos[indice].Mes_No+`" disabled/></td>
              <td><input size="2" class="text-right" style="border:0px;background:bottom"  type ="text" id="No`+clave+`" value ="`+datos[indice].No+`" disabled/></td>
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
