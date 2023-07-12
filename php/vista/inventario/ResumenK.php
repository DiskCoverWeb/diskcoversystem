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
hr{
  margin: 3px 0px;
}
input[type="checkbox"], input[type="radio"]{
  margin-right: 3px;
  margin-left: 5px;
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
    <a href="#" id="Imprimir"  class="btn btn-default" title="Imprime Resultado" onclick="Imprimir_ResumenK()">
      <img src="../../img/png/pdf.png">
    </a>                           
  </div>
  <div class="col">
    <a href="#" id="Stock" class="btn btn-default"  onclick="ConsultarStock(true)" title="Resumen de Existecia">
      <img src="../../img/png/archivo1.png">
    </a>
  </div>
  <div class="col">
    <a href="#" id="Stock_1"  class="btn btn-default" onclick="ConsultarStock(false)" title="Resumen de Existencia Agrupado">
      <img src="../../img/png/archivo2.png">
    </a>
  </div>
  <div class="col">
    <a href="#" id="Lote" title="Resumen de Existencia por Lotes" onclick="" class="btn btn-default" >
      <img src="../../img/png/archivo3.png" >
    </a>
  </div>
  <div class="col">
    <a href="#" id="Barras" title="Resumen en Codigos de Barra" onclick="" class="btn btn-default" >
      <img src="../../img/png/archivo3.png" >
    </a>
  </div>
  <div class="col">
    <a href="#" id="Excel"  class="btn btn-default" title="Enviar a Excel el resultado" onclick="">
      <img src="../../img/png/table_excel.png">
    </a>                           
  </div>
</div>

  <div class="row div_filtro">
    <form id="FormResumenK">
      <div class="row">
        <div class="form-group col margin-b-1">
          <label for="inputEmail3" class="col control-label">Fecha Inicial</label>
          <div class="col">
            <input tabindex="2" type="date" name="MBoxFechaI" id="MBoxFechaI" class="form-control input-xs validateDate mw115" value="<?php echo date('Y-m-d'); ?>">
          </div>
        </div>
        <div class="form-group col margin-b-1">
          <label for="inputEmail3" class="col control-label">Fecha Final</label>
          <div class="col">
            <input type="date" tabindex="3" name="MBoxFechaF" id="MBoxFechaF" class="form-control input-xs validateDate mw115" value="<?php echo date('Y-m-d'); ?>">
          </div>
        </div>
        <div class="form-group col margin-b-1">
          <div class="col" style="max-width:80px;">
            <label><input id="CheqMonto" name="CheqMonto" tabindex="" value="1" type="checkbox"><b>Monto</b></label>   
          </div>
          <div class="col padding-all" style="max-width: 330px;">
            <input type="tel" tabindex="" name="TxtMonto" id="TxtMonto" class="form-control input-xs mw115" placeholder="0.00">
          </div>
        </div>
        <div class="form-group col margin-b-1">
          <div class="col " style="max-width:210px;">
            <label><input id="CheqExist" name="CheqExist" tabindex="" value="1" type="checkbox"><b>Listar Catalogo Completo</b></label>   
          </div>
        </div><hr>
      </div>

      
      <div class="row">
        <div class="col-sm-3 padding-all" style="max-width: 140px;">
          <label><input id="CheqBod" name="CheqBod" tabindex="2" value="1" type="checkbox"><b>BODEGA</b></label>   
        </div>
        <div class="col padding-all" style="max-width: 330px;">
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
        <div class="col padding-all" style="max-width: 160px;">
          <label><input id="CheqGrupo" name="CheqGrupo" tabindex="2" value="1" type="checkbox"><b>TIPO GRUPO</b></label>   
        </div>
        <div class="col padding-all" >
          <select class="form-control input-sm" tabindex="3" id="DCTInv" name="DCTInv" onchange="Listar_X_Producto()">
            <option value=''>** Seleccionar Grupo**</option>
            <?php
            
            ?>
          </select>
        </div>
      <hr>
      </div>

      <div class="row">
        <div class="col-sm-3 padding-all" style="max-width: 140px;">
          <label><input id="CheqProducto" name="CheqProducto" tabindex="2" value="1" type="checkbox"><b>PRODUCTO</b></label>   
        </div>
        <div class="col padding-all" >
          <label><input id="OpcProducto" name="ProductoPor" checked tabindex="" value="1" type="radio"><b>Producto</b></label>   
          <label><input id="OpcBarra" name="ProductoPor" tabindex="" value="1" type="radio"><b>Codigo Barra</b></label>   
          <label><input id="OpcMarca" name="ProductoPor" tabindex="" value="1" type="radio"><b>Marca</b></label>   
          <label><input id="OpcLote" name="ProductoPor" tabindex="" value="1" type="radio"><b>Lote</b></label>   
        </div>
        <div class="col padding-all" >
          <select class="form-control input-sm" tabindex="" id="DCTipoBusqueda" name="DCTipoBusqueda">
            <option value=''>** Seleccionar**</option>
            <?php
            
            ?>
          </select>
        </div>
      <hr>
      </div>

      <div class="row">
        <div class="col-sm-3 padding-all" style="max-width: 140px;">
          <label><input id="CheqCtaInv" name="CheqCtaInv" tabindex="2" value="1" type="checkbox"><b>TIPO DE CTA.</b></label>   
        </div>
        <div class="col padding-all" >
          <label><input id="OpcInv" name="TipoCuentaDe" checked tabindex="" value="1" type="radio"><b>Inventario</b></label>   
          <label><input id="OpcCosto" name="TipoCuentaDe" tabindex="" value="1" type="radio"><b>Costo</b></label>   
        </div>
        <div class="col padding-all" >
          <select class="form-control input-sm" tabindex="" id="DCCtaInv" name="DCCtaInv">
            <option value=''>** Seleccionar Cuenta**</option>
            <?php
            
            ?>
          </select>
        </div>
      <hr>
      </div>

      <div class="row">
        <div class="col-sm-3 padding-all" style="max-width: 140px;">
          <label><input id="CheqSubMod" name="CheqSubMod" tabindex="2" value="1" type="checkbox"><b>POR SUBMODULO</b></label>   
        </div>
        <div class="col padding-all" >
          <label><input id="OpcGasto" name="SuModeloDe" checked tabindex="" value="1" type="radio"><b>Centro de Costo</b></label>   
          <label><input id="OpcCxP" name="SuModeloDe" tabindex="" value="1" type="radio"><b>CxP/Proveedores</b></label>   
        </div>
        <div class="col padding-all" >
          <select class="form-control input-sm" tabindex="" id="DCSubModulo" name="DCSubModulo">
            <option value=''>** Seleccionar Modulo**</option>
            <?php
            
            ?>
          </select>
        </div>
      <hr>
      </div>

      <input type="hidden" id="heightDisponible" name="heightDisponible" value="100"> 
    </form>
  </div>

  <div class="row">
    <div class="col-md-12" id="DGQuery"  tabindex="15">
      <div  style="min-height:100px">
      <table>
        <thead><tr><th class="text-left" style="width:40px">TC</th><th class="text-left" style="width:200px">Codigo_Inv</th><th class="text-left" style="width:300px">Producto</th><th class="text-left" style="width:136px">Unidad</th><th class="text-left" style="width:64px">Stock_Anterior</th><th class="text-left" style="width:64px">Entradas</th><th class="text-left" style="width:64px">Salidas</th><th class="text-left" style="width:64px">Stock_Actual</th><th class="text-left" style="width:64px">Costo_Unit</th><th class="text-right" style="width:112px">Total</th><th class="text-right" style="width:136px">Diferencias</th><th class="text-left" style="width:0px">Bodega</th><th class="text-left" style="width:400px">Ubicacion</th></tr></thead>
      </table>
        
      </div>
    </div>
  </div>
  <div class="row">
        <div class="form-group col margin-b-1">
          <label for="inputEmail3" class="col control-label">Stock Total</label>
          <div class="col">
            <input type="tel" name="LabelStock" id="LabelStock" class="form-control input-xs mw115" >
          </div>
        </div>
        <div class="form-group col margin-b-1">
          <label for="inputEmail3" class="col control-label">Valor Total</label>
          <div class="col">
            <input type="tel" name="LabelTot" id="LabelTot" class="form-control input-xs mw115">
          </div>
        </div>
    
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function()
  {
    asignarHeightPantalla($("#DCSubModulo"), $("#heightDisponible"))
    document.title = "Diskcover | RESUMEN DE EXISTENCIAS";
    Listar_X_Producto()
  });

  function ConsultarStock(StockSuperior) {
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",                 
      url: '../controlador/inventario/ResumenKC.php?ConsultarStock=true&StockSuperior='+StockSuperior,
      dataType: 'json',
      data: $("#FormResumenK").serialize(), 
      success: function(data)             
      {
        if (data.error) {
          Swal.fire({
            type: 'warning',
            title: '',
            text: data.mensaje
          });
        }else{
          $('#DGQuery').html(data.DGQuery);   
          $('#LabelTot').val(data.LabelTot); 
        }   
        $('#myModal_espera').modal('hide');     
      }
    });
  }
  function Imprimir_ResumenK() {
    url = '../controlador/inventario/ResumenKC.php?Imprimir_ResumenK=true&'+$("#FormResumenK").serialize();
    window.open(url, '_blank');
  }

  function Listar_X_Producto() {
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: '../controlador/inventario/ResumenKC.php?Listar_Por_Producto=true',
      data: $("#FormResumenK").serialize(), 
      beforeSend: function () {   
        $('#myModal_espera').modal('show');
      },    
      success: function(response)
      { 
        if(response.rps){
          $('#myModal_espera').modal('hide');
          llenarComboList(response.DCTipoBusqueda,'DCTipoBusqueda')
          $("#DCTipoBusqueda").focus();
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
</script>