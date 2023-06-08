<div class="row" id='submenu'>
   <div class="col-xs-12">
     <div class="box" style='margin-bottom: 5px;'>
      <div class="box-header">
        <a class="btn btn-default" title="Salir del modulo" href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>">
          <i ><img src="../../img/png/salire.png" class="user-image" alt="User Image"
          style='font-size:20px; display:block; height:100%; width:100%;'></i> 
        </a>

        <a class="btn btn-default" title="Imprimir resultados" id='imprimir_pdf'>
          <i ><img src="../../img/png/pdf.png" class="user-image" alt="User Image"
          style='font-size:20px; display:block; height:100%; width:100%;'></i> 
        </a>
        
        <a id='imprimir_excel' class="btn btn-default" title="Exportar Excel" href="#">
          <i ><img src="../../img/png/table_excel.png" class="user-image" alt="User Image"
          style='font-size:20px; display:block; height:100%; width:100%;'></i> 
        </a>
      </div>
      <div class="box-body">
        <form id="FormReporteConsumoAgua">
          <div class="row">
            <div class="col-xs-4 col-md-3 col-lg-3">
              <b>Cliente</b> <br>
              <select class="form-control input-sm" id="cliente" name="cliente" tabindex="1">
                <option value="">Seleccione un cliente</option>
              </select>
              <input type="hidden" name="codigoCliente" id="codigoCliente">
            </div>
            <div class="col-xs-4 col-md-3 col-lg-1" style="min-width: 145px;max-width: 160px">
              <b>Medidor</b> <br>
              <select class="form-control input-xs" id="CMedidorFiltro" name="CMedidorFiltro" tabindex="2">  
                <option value="<?php echo G_NINGUNO ?>">Medidores</option>
              </select>
            </div>
            <div class="col-xs-4 col-md-3 col-lg-2" style="max-width:150px">
              <b>Tipo</b> <br>
              <select class="form-control input-xs" id="Tipo" name="Tipo" tabindex="2" 
              onchange="document.getElementById('div_serie').style.display = (this.value === '2') ? 'block' : 'none';">  
                <option value="1">Prefacturas</option>
                <option value="2">Facturado</option>
              </select>
            </div>
            <div class="col-xs-4 col-md-3 col-lg-1 " id="div_serie" style="min-width: 95px;max-width: 150px;display:none">
              <b>Serie</b> <br>
               <input type="tel" class="form-control pull-right input-xs" id="serie" name="serie" >
            </div>
            <div class="col-xs-4 col-md-3 col-lg-2">
              <b>Desde:</b>
               <input type="date" class="form-control pull-right input-xs" id="desde" name="fechai" value='<?php echo date('Y-m-d') ?>' onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)" tabindex="3">           
            </div>
            <div class="col-xs-4 col-md-3 col-lg-2">
              <b>Hasta:</b>
              <input type="date" class="form-control pull-right input-xs" id="hasta" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)" name="fechaf" value='<?php echo date('Y-m-d') ?>' tabindex="4">             
            </div>
            <div class="col-xs-4 col-md-3 col-lg-1">
              <b><br></b>
              <button type="button" class="btn btn-xs btn-info" onclick="cargarTablaReporteConsumo()" tabindex="5">Buscar
              </button>            
            </div>
          </div>
        </form>
      </div>
     </div>
   </div>
  </div>
  <div class="row">
    <div class="table-responsive">
      <div class="col-sm-12" id="tablaReporteConsumo">
        
      </div>
    </div>
  </div>
<script type="text/javascript">
  $(document).ready(function()
  {
    autocomplete_cliente();
    cargarTablaReporteConsumo();

    $('#imprimir_excel').click(function(){
      var url = '../controlador/facturacion/facturar_pensionC.php?ExcelReporteConsumo=true&'+$("#FormReporteConsumoAgua").serialize();
      window.open(url, '_blank');
    });

    $('#imprimir_pdf').click(function(){
      var url = '../controlador/facturacion/facturar_pensionC.php?PdfReporteConsumo=true&'+$("#FormReporteConsumoAgua").serialize();
      window.open(url, '_blank');
    });

    $('#cliente').on('select2:select', function (e) {
      var data = e.params.data.data;
      $('#codigoCliente').val(data.codigo);
      ListarMedidoresHeader($("#CMedidorFiltro"),data.codigo, true)
    });

  });

  function cargarTablaReporteConsumo()
  {
    $('#myModal_espera').modal('show');
    $.ajax({
      url:   '../controlador/facturacion/facturar_pensionC.php?TablaReporteConsumo=true',
      data: $("#FormReporteConsumoAgua").serialize(),
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        $('#tablaReporteConsumo').html(response);
        $('#myModal_espera').modal('hide');
      }
    });
  }

  function autocomplete_cliente(){
    $('#cliente').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url:   '../controlador/facturacion/facturar_pensionC.php?cliente=true&all=true',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }
</script>