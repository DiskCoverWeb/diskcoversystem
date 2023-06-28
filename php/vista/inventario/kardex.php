<?php
include "../controlador/inventario/kardexC.php";
$kardex = new kardexC();
?>

<style type="text/css">
.col{
  display: inline-block;
}
.padding-all{
  padding: 2px !important;
}
</style>

<div class="container-fluid">
<div class="row mb-3">
  <div class="col">
    <a href="./inventario.php?mod=inventario#" title="Salir de modulo" class="btn btn-default">
      <img src="../../img/png/salire.png">
    </a>
  </div>  
  <div class="col">
    <a href="#" id="Consultar" class="btn btn-default" onclick="Consultar_Tipo_Kardex(true);" title="Consulta el kardex de un producto">
      <img src="../../img/png/archivo1.png">
    </a>
  </div>
  <div class="col">
    <a href="#" id="Kardex_Total"  class="btn btn-default" onclick="Consultar_Tipo_Kardex(false);" title="Presenta el kardex de todos los productos">
      <img src="../../img/png/archivo2.png">
    </a>
  </div>
  <div class="col">
    <a href="#" id="Kardex" title="Presenta el Resumen de Codigos de Barra" onclick="consulta_kardex();" class="btn btn-default" >
      <img src="../../img/png/archivo3.png" >
    </a>
  </div>
  <<!-- div class="col">
    <a href="#" id="Imprimir_Kardex"  class="btn btn-default" title="Descargar PDF Kardex de un Producto" onclick="generarPDF();">
      <img src="../../img/png/pdf.png">
    </a>                           
  </div> 
  <div class="col">
    <a href="#" id="Excel"  class="btn btn-default" title="Descargar Excel" onclick="generarExcel();">
      <img src="../../img/png/table_excel.png">
    </a>                           
  </div> -->
</div>

  <div class="row">
    <form id="FormKardex">
      <div class="col-sm-6">
        <div class="row">
          <select class="form-control input-sm mb-1" id="DCTInv" name="DCTInv" onchange="cambiarProducto();">
            <option value=''>** Seleccionar **</option>
            <?php
            $productosI = $kardex->ListarProductos('I','');
            foreach ($productosI as $value) {
              echo "<option value='".$value['LabelCodigo']."'>".$value['nombre']."</option>";
            }
            ?>
          </select>
        </div>
        <div class="row">
          <select class="form-control input-sm" id="DCInv" name="DCInv" onchange="productoFinal();">
            <option value=''>** Seleccionar **</option>
            <?php
            $productosI = $kardex->ListarProductos('P','');
            foreach ($productosI as $value) {
              echo "<option value='".$value['LabelCodigo']."'>".$value['nombre']."</option>";
            }
            ?>
          </select>
        </div>
      </div>

      <div class="col-sm-5">
        <div class="row">
          <div class="col-sm-3 padding-all" style="max-width:   80px;">
            <label><input id="CheqBod" name="CheqBod" value="1" type="checkbox"><b>Bodega:</b></label>           
          </div>
          <div class="col-sm-9 padding-all" style="max-width: 330px;">
            <select class="form-control input-sm" id="DCBodega" name="DCBodega">
              <option value=''>** Seleccionar **</option>
              <?php
              $bodegas = $kardex->bodegas();
              foreach ($bodegas as $value) {
                echo "<option value='".$value['LabelCodigo']."'>".$value['nombre']."</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Desde:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="date" name="MBoxFechaI" id="MBoxFechaI" class="form-control input-sm"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);" onkeyup="validar_year_mayor(this.id)">
          </div>
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Hasta:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="date" name="MBoxFechaF" id="MBoxFechaF"  class="form-control input-sm"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);" onkeyup="validar_year_mayor(this.id)">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Código:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="text" class="form-control input-sm" id="LabelCodigo" name="LabelCodigo" readonly>
          </div>
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Mínimo:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="text" class="form-control input-sm" id="LabelMinimo" name="LabelMinimo" readonly>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Unidad:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="text" class="form-control input-sm" id="LabelUnidad" name="LabelUnidad" readonly>
          </div>
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Existe:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="text" class="form-control input-sm" id="LabelExitencia" name="LabelExitencia" readonly style="color:red">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Bodega:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="text" class="form-control input-sm" id="LabelBodega" name="LabelBodega" value="0" readonly>
          </div>
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Máximo:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="text" class="form-control input-sm" id="LabelMaximo" name="LabelMaximo" readonly>
          </div>
        </div>
      </div>
    </form>
  </div>


  <br>
  <div class="row">
    <div class="col-md-12" id="DGKardex">

    </div>

    <!-- Modal -->
    <div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="miModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="miModalLabel">| CAMBIO DE PRODUCTOS |</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Contenido del modal -->
            <div id="modalContenido"></div>
          </div>
        </div>
      </div>
    </div>


  </div>
</div>

<script type="text/javascript">
  $(document).ready(function()
  {
    document.title = "Diskcover | EXISTENCIA DE INVENTARIO";
    // cambiarProducto();
    // productoFinal();
  });

  function cambiarProducto(){
    codigoProducto = $("#DCTInv").val();
    producto = codigoProducto.split("/");
    $.ajax({
      type: "POST",                 
      url: '../controlador/inventario/kardexC.php?cambiarProducto=true',
      data: {'codigoProducto' : producto[0] }, 
      success: function(data)             
      {
        if (data) {
          datos = JSON.parse(data);
          llenarComboList(datos,'DCInv')
        }else{
          console.log("No tiene datos");
        }        
      }
    });
  }

  function productoFinal(){
    codigoProducto = $("#DCInv").val();
    producto = codigoProducto.split("/");
    LabelCodigo = producto[0];
    LabelMinimo = producto[1];
    LabelMaximo = producto[2];
    LabelUnidad = producto[3];
    $("#LabelCodigo").val(LabelCodigo);
    $("#LabelUnidad").val(LabelUnidad);
    $("#LabelMinimo").val(formatearNumero(LabelMinimo));
    $("#LabelMaximo").val(formatearNumero(LabelMaximo));
    $("#LabelExitencia").val('0.00');
  }

  function Consultar_Tipo_Kardex(EsKardexIndividual){//revisada
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",                 
      url: '../controlador/inventario/kardexC.php?Consultar_Tipo_Kardex=true&EsKardexIndividual='+EsKardexIndividual,
      dataType: 'json',
      data: $("#FormKardex").serialize(),
      success: function(data)             
      {
        if (data.error) {
          Swal.fire({
            type: 'warning',
            title: '',
            text: data.mensaje
          });
        }else{
          document.title = "Diskcover | " + (EsKardexIndividual ? "EXISTENCIA DE INVENTARIO" : "EXISTENCIA DE TODOS LOS INVENTARIOS");

          $('#DGKardex').html(data.DGKardex);   
          $('#LabelExitencia').val(data.LabelExitencia); 
        }  
        $('#myModal_espera').modal('hide');      
      }
    });
  }

  function consulta_kardex(){ //revisado
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",                 
      url: '../controlador/inventario/kardexC.php?consulta_kardex=true',
      dataType: 'json',
      data: $("#FormKardex").serialize(), 
      success: function(data)             
      {
        if (data.error) {
          Swal.fire({
            type: 'warning',
            title: '',
            text: data.mensaje
          });
        }else{
          $('#DGKardex').html(data.DGKardex);   
          $('#LabelExitencia').val(data.LabelExitencia); 
        }   
        $('#myModal_espera').modal('hide');     
      }
    });
  }

  function generarPDF(){
    MBoxFechaI = $("#MBoxFechaI").val();
    MBoxFechaF = $("#MBoxFechaF").val();
    DCInv = $("#LabelCodigo").val();
    DCBodega = $("#DCBodega").val();
    url = '../controlador/inventario/kardexC.php?generarPDF=true&MBoxFechaI='+MBoxFechaI+'&MBoxFechaF='+MBoxFechaF+'&LabelCodigo='+DCInv;
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

  function Imprime_Codigos_de_Barra(){
    alert(' no programado');
  }

  function Cambia_la_Serie(Producto, ID_Reg, TC, Serie, Factura, CodigoInv) {
    Swal.fire({
      title: 'INGRESE LA SERIE DE ESTE PRODUCTO: '+Producto,
      showCancelButton: true,
      cancelButtonText: 'Cerrar',
      confirmButtonText: 'Actualizar',
      html:
        '<label for="CodigoP">INGRESO DE SERIE:</label>' +
        '<input type="tel" id="CodigoP" class="swal2-input" required>' +
        '<span id="error1" style="color: red;"></span><br>',
      focusConfirm: false,
      preConfirm: () => {
        const CodigoP = document.getElementById('CodigoP').value;
        if(CodigoP!="" && CodigoP!="."){
          return [CodigoP];
        }else{
          Swal.getPopup().querySelector('#error1').textContent = 'Debe ingresar una serie para actualizar';
          return false
        }
      }
    }).then((result) => {
      if (result.value) {
        const [CodigoP] = result.value;
        if(CodigoP!="" && CodigoP!="."){
          $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '../controlador/inventario/kardexC.php?ActualizarSerie=true',
            data: {'CodigoP' : CodigoP, 'ID_Reg':ID_Reg, 'TC':TC, 'Serie':Serie, 'Factura':Factura, 'CodigoInv':CodigoInv },
            beforeSend: function () {   
              $('#myModal_espera').modal('show');
            },    
            success: function(response)
            { 
              if(response.rps){
                Swal.fire('¡Bien!', response.mensaje, 'success')
              }else{
                Swal.fire('¡Oops!', response.mensaje, 'warning')
              }
              $('#myModal_espera').modal('hide');        
            },
            error: function () {
              $('#myModal_espera').modal('hide');
              alert("Ocurrio un error inesperado, por favor contacte a soporte.");
            }
          });
        }
      }
    });
  }

</script>