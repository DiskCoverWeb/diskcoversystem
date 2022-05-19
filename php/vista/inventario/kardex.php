<?php
  include "../controlador/inventario/kardexC.php";
  $kardex = new kardexC();
?>
<script type="text/javascript">
  $(document).ready(function()
  {
    cambiarProducto();
    productoFinal();
    funcionInicio();
  });

  function cambiarProducto(){
    codigoProducto = $("#productoI").val();
    producto = codigoProducto.split("/");
    $.ajax({
      type: "POST",                 
      url: '../controlador/inventario/kardexC.php?cambiarProducto=true',
      data: {'codigoProducto' : producto[0] }, 
      success: function(data)             
      {
        if (data) {
          datos = JSON.parse(data);
          llenarComboList(datos,'productoP')
        }else{
          console.log("No tiene datos");
        }        
      }
    });
  }

  function funcionInicio(){
    $("#myModal").modal("show");
    $.ajax({
      type: "POST",                 
      url: '../controlador/inventario/kardexC.php?funcion=true',
      success: function(data)             
      {
        datos = JSON.parse(data);
        $("#myModal").modal("hide");
      }
    });
  }

  function productoFinal(){
    codigoProducto = $("#productoP").val();
    producto = codigoProducto.split("/");
    codigo = producto[0];
    minimo = producto[1];
    maximo = producto[2];
    unidad = producto[3];
    $("#codigo").val(codigo);
    $("#minimo").val(parseFloat(minimo).toFixed(2));
    $("#maximo").val(parseFloat(maximo).toFixed(2));
    $("#unidad").val(unidad);
    $("#existe").val('0.00');
  }

  function consulta_kardex_producto(){
    desde = $("#desde").val();
    hasta = $("#hasta").val();
    productoI = $("#productoI").val();
    productoI = productoI.split("/");
    producto = $("#codigo").val();
    bodega = $("#bodega").val();
    $.ajax({
      type: "POST",                 
      url: '../controlador/inventario/kardexC.php?consulta_kardex_producto=true',
      dataType: 'json',
      data: {
              'desde' : desde,
              'hasta' : hasta,
              'productoP' : codigo,
              'productoI' : productoI[0],
              'bodega' : bodega 
            },

      success: function(data)             
      {
        console.log(data);
        if (data) {
          $('#myTable1').html(data);   
        }else{
          console.log("No tiene datos");
        }        
      }
    });
  }

  function consulta_kardex(){
    desde = $("#desde").val();
    hasta = $("#hasta").val();
    productoI = $("#productoI").val();
    productoI = productoI.split("/");
    producto = $("#codigo").val();
    bodega = $("#bodega").val();
    cbBodega = $("#cbBodega").val();
    $.ajax({
      type: "POST",                 
      url: '../controlador/inventario/kardexC.php?consulta_kardex=true',
      dataType: 'json',
      data: {
              'desde' : desde,
              'hasta' : hasta,
              'productoP' : codigo,
              'productoI' : productoI[0],
              'bodega' : bodega,
              'cbBodega' : cbBodega, 
            }, 
      success: function(data)             
      {
        if (data) {
          $('#myTable1').html(data);   
        }else{
          console.log("No tiene datos");
        }        
      }
    });
  }

  function kardex_total(){
    desde = $("#desde").val();
    hasta = $("#hasta").val();
    productoI = $("#productoI").val();
    productoI = productoI.split("/");
    producto = $("#codigo").val();
    bodega = $("#bodega").val();
    cbBodega = $("#cbBodega").val();
    $.ajax({
      type: "POST",                 
      url: '../controlador/inventario/kardexC.php?kardex_total=true',
      dataType : 'json',
      data: {
              'desde' : desde,
              'hasta' : hasta,
              'productoP' : codigo,
              'productoI' : productoI[0],
              'bodega' : bodega,
              'cbBodega' : cbBodega, 
            }, 
      success: function(data)             
      {
        if (data) {
          $('#myTable1').html(data);   
        }else{
          console.log("No tiene datos");
        }
      }
    });
  }

  function generarPDF(){
    desde = $("#desde").val();
    hasta = $("#hasta").val();
    productoP = $("#codigo").val();
    bodega = $("#bodega").val();
    url = '../controlador/inventario/kardexC.php?generarPDF=true&desde='+desde+'&hasta='+hasta+'&codigo='+productoP;
    window.open(url, '_blank');
  }

  function generarExcel(){
    console.log("entra");
    var titulo = Array.prototype.slice.call(document.getElementById("myTable").getElementsByTagName("th"));
    array_titulo = [];
    cont_titulo = 0;
    for(var i in titulo){
      array_titulo[cont_titulo] = titulo[i].innerHTML;
      cont_titulo ++;
    }
    array_datos = [];
    array_aux = [];
    cont_datos = 0;
    cont_aux = 0;
    var cells = Array.prototype.slice.call(document.getElementById("myTable").getElementsByTagName("td"));
    for(var j in cells){
      array_datos[cont_aux] = cells[j].innerHTML;
      cont_aux++;
      if (cont_titulo == cont_aux) {
        array_aux[cont_datos] = array_datos;
        cont_aux = 0;
        cont_datos++;
        array_datos = [];
      } 
    }
    url = '../controlador/inventario/kardexC.php?generarExcel=true&array_titulo='+array_titulo+'&array_datos='+array_aux;
    window.open(url, '_blank');
  }

  </script>
   <div class="container">
    <div class="row">
      <div class="col-lg-4 col-sm-4 col-md-8 col-xs-12 col-md-offset-1">
        <div class="col-xs-2 col-md-2 col-sm-2">
          <a href="./inventario.php?mod=inventario#" title="Salir de modulo" class="btn btn-default">
            <img src="../../img/png/salire.png">
          </a>
        </div>  
        <div class="col-xs-2 col-md-2 col-sm-2">
          <a href="#" id="imprimir_pdf" class="btn btn-default" onclick="consulta_kardex_producto();" title="Consulta el kardex de un producto">
            <img src="../../img/png/archivo1.png">
          </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
          <a href="#" id="imprimir_excel"  class="btn btn-default" onclick="kardex_total();" title="Presenta el kardex de todos los productos">
            <img src="../../img/png/archivo2.png">
          </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
          <a href="#" title="Presenta el resumen de codigos de barra" onclick="consulta_kardex();" class="btn btn-default" >
            <img src="../../img/png/archivo3.png" >
          </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
          <a href="#" id="imprimir_excel"  class="btn btn-default" title="Descargar PDF" onclick="generarPDF();">
            <img src="../../img/png/archivo4.png">
          </a>                           
        </div> 
        <div class="col-xs-2 col-md-2 col-sm-2">
          <a href="#" id="imprimir_excel"  class="btn btn-default" title="Descargar PDF" onclick="generarExcel();">
            <img src="../../img/png/table_excel.png">
          </a>                           
        </div>  
      </div>
      
    </div>
  <div class="row">
    <div class="col-sm-4 col-md-offset-1">
      <select class="form-control input-sm" id="productoI" onchange="cambiarProducto();">
        <?php
          $productosI = $kardex->productos('I','');
          foreach ($productosI as $value) {
            echo "<option value='".$value['codigo']."'>".$value['nombre']."</option>";
          }
        ?>
      </select>
    </div>
    <div class="col-sm-3">
      <label><input id="cbBodega" type="checkbox"><b>Bodega:</b></label>           
    </div>
    <div class="col-sm-3">
      <select class="form-control input-sm" id="bodega">
        <?php
          $bodegas = $kardex->bodegas();
          foreach ($bodegas as $value) {
            echo "<option value='".$value['codigo']."'>".$value['nombre']."</option>";
          }
        ?>
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4 col-md-offset-1">
      <select class="form-control input-sm" id="productoP" onchange="productoFinal();">
        <?php
          $productosI = $kardex->productos('P','');
          foreach ($productosI as $value) {
            echo "<option value='".$value['codigo']."'>".$value['nombre']."</option>";
          }
        ?>
      </select>
    </div>
    <div class="col-sm-1">
      <b>Desde:</b>
    </div>
    <div class="col-sm-2">
      <input type="date" name="desde" id="desde" class="form-control input-sm"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);fecha_fin()" onkeyup="validar_year_mayor(this.id)">
    </div>
    <div class="col-sm-1">
      <b>Hasta:</b>
    </div>
    <div class="col-sm-2">
      <input type="date" name="hasta" id="hasta"  class="form-control input-sm"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);" onkeyup="validar_year_mayor(this.id)">
    </div>
  </div>
  <div class="row">
    <div class="col-sm-1 col-md-offset-5">
      <b>Código:</b>
    </div>
    <div class="col-sm-2">
      <input type="text" class="form-control input-sm" id="codigo" readonly>
    </div>
    <div class="col-sm-1">
      <b>Mínimo:</b>
    </div>
    <div class="col-sm-2">
      <input type="text" class="form-control input-sm" id="minimo" readonly>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-1 col-md-offset-5">
      <b>Unidad:</b>
    </div>
    <div class="col-sm-2">
      <input type="text" class="form-control input-sm" id="unidad" readonly>
    </div>
    <div class="col-sm-1">
      <b>Existe:</b>
    </div>
    <div class="col-sm-2">
      <input type="text" class="form-control input-sm" id="existe" readonly>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-1 col-md-offset-5">
      <b>Bodega:</b>
    </div>
    <div class="col-sm-2">
      <input type="text" class="form-control input-sm" value="0" readonly>
    </div>
    <div class="col-sm-1">
      <b>Máximo:</b>
    </div>
    <div class="col-sm-2">
      <input type="text" class="form-control input-sm" id="maximo" readonly>
    </div>
  </div>
    <!--seccion de panel-->
    <br>
    <div class="row">
      <div class="table-responsive" id="myTable1">
                     
      </div>
    </div>
  </div>