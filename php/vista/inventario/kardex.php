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

#swal2-content{
  font-weight: 600;
    font-size: 12px;
}
</style>

<div class="container-fluid">
<div class="row mb-3">
  <div class="col">
    <a href="./inventario.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo" class="btn btn-default">
      <img src="../../img/png/salire.png">
    </a>
  </div>  
  <div class="col">
    <a href="#" id="Consultar" class="btn btn-default"  onclick="Consultar_Tipo_Kardex(true);" title="Consulta el kardex de un producto">
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
  <div class="col">
    <a href="#" id="Imprimir_Kardex"  class="btn btn-default" title="Descargar PDF Kardex de un Producto" onclick="generarPDF();">
      <img src="../../img/png/pdf.png">
    </a>                           
  </div>
  <div class="col">
    <a href="#" id="Excel"  class="btn btn-default" title="Descargar Excel" onclick="generarExcelKardex();">
      <img src="../../img/png/table_excel.png">
    </a>                           
  </div>
</div>

  <div class="row div_filtro">
    <form id="FormKardex">
      <div class="col-sm-6">
        <div class="row">
          <select class="form-control input-sm mb-1" tabindex="0" id="DCTInv" name="DCTInv" onchange="cambiarProducto();">
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
          <select class="form-control input-sm" tabindex="1" id="DCInv" name="DCInv" onchange="productoFinal();">
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
            <label><input id="CheqBod" name="CheqBod" tabindex="2" value="1" type="checkbox"><b>Bodega:</b></label>    
          </div>
          <div class="col-sm-9 padding-all" style="max-width: 330px;">
            <select class="form-control input-sm" tabindex="3" id="DCBodega" name="DCBodega">
              <option value=''>** Seleccionar Bodega**</option>
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
            <input type="date" name="MBoxFechaI" id="MBoxFechaI" tabindex="5" class="form-control input-sm"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);" onkeyup="validar_year_mayor(this.id)">
          </div>
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Hasta:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="date" name="MBoxFechaF" id="MBoxFechaF" tabindex="7" class="form-control input-sm"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);" onkeyup="validar_year_mayor(this.id)">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Código:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="text" class="form-control input-sm" tabindex="14" id="LabelCodigo" name="LabelCodigo" readonly>
          </div>
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Mínimo:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="text" class="form-control input-sm" tabindex="11" id="LabelMinimo" name="LabelMinimo" readonly>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Unidad:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="text" class="form-control input-sm" tabindex="13" id="LabelUnidad" name="LabelUnidad" readonly>
          </div>
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Existe:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="text" class="form-control input-sm" tabindex="10" id="LabelExitencia" name="LabelExitencia" readonly style="color:red">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Bodega:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="text" class="form-control input-sm" tabindex="12" id="LabelBodega" name="LabelBodega" value="0" readonly>
          </div>
          <div class="col-sm-2 padding-all" style="max-width:   80px;">
            <b>Máximo:</b>
          </div>
          <div class="col-sm-4 padding-all" style="max-width:   125px;">
            <input type="text" class="form-control input-sm" tabindex="9" id="LabelMaximo" name="LabelMaximo" readonly>
            <input type="hidden" id="heightDisponible" name="heightDisponible" value="100">    
            <input type="hidden" id="NombreProducto" name="NombreProducto">    
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="row">
    <div class="col-md-12" id="DGKardex"  tabindex="8">

    </div>

    <!-- Modal -->
    <div class="modal fade" id="FrmProductos" tabindex="-1" role="dialog" aria-labelledby="FrmProductosLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="FrmProductosLabel">| CAMBIO DE PRODUCTOS |</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="FormCambiarProducto">
              <div class="row mb-3">
                <div class="col-sm-12">
                  <input type="text" class="form-control input-sm" title="Producto anterior" tabindex="27" id="LblProducto" name="LblProducto" readonly>
                  <input type="hidden" id="ID_Reg" name="ID_Reg">
                  <input type="hidden" id="TC" name="TC">
                  <input type="hidden" id="Serie" name="Serie">
                  <input type="hidden" id="Factura" name="Factura">
                  <input type="hidden" id="CodigoInv" name="CodigoInv">
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <select class="form-control input-sm" tabindex="26" id="DCArt" name="DCArt">
                    <option value=''>** Seleccionar Nuevo**</option>
                  </select>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
              
            <button class="btn btn-success" id="Command1" title="Aceptar" onclick="AceptarCambio()">
              <img  src="../../img/png/grabar.png" width="25" height="30" tabindex="24">
            </button>
            <button class="btn btn-warning" id="Command3" title="Salir" data-dismiss="modal">
              <img  src="../../img/png/salire.png" width="25" height="30" tabindex="25">
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">
  $(document).ready(function()
  {
    asignarHeightPantalla($("#LabelBodega"), $("#heightDisponible"))
    document.title = "Diskcover | EXISTENCIA DE INVENTARIO";
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
    NombreProducto = producto[4];
    $("#LabelCodigo").val(LabelCodigo);
    $("#LabelUnidad").val(LabelUnidad);
    $("#LabelMinimo").val(formatearNumero(LabelMinimo));
    $("#LabelMaximo").val(formatearNumero(LabelMaximo));
    $("#LabelExitencia").val('0.00');
    $("#NombreProducto").val(NombreProducto);
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
    url = '../controlador/inventario/kardexC.php?generarPDF=true&'+$("#FormKardex").serialize();
    window.open(url, '_blank');
  }

  function generarExcelKardex(){ //revisada
    url = '../controlador/inventario/kardexC.php?generarExcelKardex=true&'+$("#FormKardex").serialize();
    window.open(url, '_blank');
  }

  function Imprime_Codigos_de_Barra(){
    alert(' no programado');
  }

  function Cambia_la_Serie(Producto, ID_Reg, TC, Serie, Factura, CodigoInv) {//revisada
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

  function Cambia_Codigo_de_Barra(Producto, ID_Reg, TC, Serie, Factura, CodigoInv) {
    Swal.fire({
      title: 'INGRESE EL CODIGO DE BARRAS DE ESTE PRODUCTO: '+Producto,
      showCancelButton: true,
      cancelButtonText: 'Cerrar',
      confirmButtonText: 'Actualizar',
      html:
        '<label for="CodigoB">INGRESO DE CODIGO DE BARRAS:</label>' +
        '<input type="tel" id="CodigoB" class="swal2-input" required>' +
        '<span id="error1" style="color: red;"></span><br>',
      focusConfirm: false,
      preConfirm: () => {
        const CodigoB = document.getElementById('CodigoB').value;
        if(CodigoB!="" && CodigoB!="."){
          return [CodigoB];
        }else{
          Swal.getPopup().querySelector('#error1').textContent = 'Debe ingresar una serie para actualizar';
          return false
        }
      }
    }).then((result) => {
      if (result.value) {
        const [CodigoB] = result.value;
        if(CodigoB!="" && CodigoB!="."){
          $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '../controlador/inventario/kardexC.php?CambiaCodigodeBarra=true',
            data: {'CodigoB' : CodigoB, 'ID_Reg':ID_Reg, 'TC':TC, 'Serie':Serie, 'Factura':Factura, 'CodigoInv':CodigoInv },
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

  function Cambiar_Articulo(Producto, ID_Reg, TC, Serie, Factura, CodigoInv) {
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: '../controlador/inventario/kardexC.php?ListarArticulos=true',
      beforeSend: function () {   
        $('#myModal_espera').modal('show');
      },    
      success: function(response)
      { 
        if(response.rps){
          $('#myModal_espera').modal('hide');
          $("#LblProducto").val(Producto) 
          $("#ID_Reg").val(ID_Reg) 
          $("#TC").val(TC) 
          $("#Serie").val(Serie) 
          $("#Factura").val(Factura) 
          $("#CodigoInv").val(CodigoInv) 
          $('#FrmProductos').modal('show');
          llenarComboList(response.DCArt,'DCArt')
          $("#DCArt").focus();
        }else{
          $('#myModal_espera').modal('hide');
          Swal.fire('¡Oops!', response.mensaje, 'warning')
        }
      },
      error: function () {
        $('#myModal_espera').modal('hide');
        alert("Ocurrio un error inesperado, por favor contacte a soporte.");
      }
    });
  }
  function AceptarCambio() {
    Swal.fire({
      title: 'PREGUNTA DE ACTUALIZACION',
      showCancelButton: true,
      cancelButtonText: 'Cerrar',
      confirmButtonText: 'Actualizar',
      html:
        '<label for="">Esta seguro de cambiar: '+$("#LblProducto").val()+'</label>' +
        '<label for="">por el Producto:'+$('#DCArt option:selected').text()+'</label>',
      focusConfirm: false,
    }).then((result) => {
      if (result.value) {
        $.ajax({
          type: 'POST',
          dataType: 'json',
          url: '../controlador/inventario/kardexC.php?ConfirmarCambiar_Articulo=true',
          data: $("#FormCambiarProducto").serialize(),
          beforeSend: function () {   
            $('#myModal_espera').modal('show');
          },    
          success: function(response)
          { 
            $('#myModal_espera').modal('hide'); 
            if(response.rps){
              $('#FrmProductos').modal('hide');
              Swal.fire('¡Bien!', response.mensaje, 'success')
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
    });
  }
</script>