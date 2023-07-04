<?php $MascaraCodigoK = (isset($_SESSION['INGRESO']['Formato_Inventario']))?$_SESSION['INGRESO']['Formato_Inventario']:MascaraCodigoK;
?>
<div class="row mb-3">          
	<div class="col-xs-12">
			<div class="col">
				<a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" data-toggle="tooltip" title="Salir de modulo" class="btn btn-default">
					<img src="../../img/png/salire.png">
				</a>
			</div>
			<div class="col">
				<a href="#" class="btn btn-default" id='imprimir_pdf'  data-toggle="tooltip"title="Descargar PDF">
					<img src="../../img/png/pdf.png">
				</a>
			</div>
			<div class="col">
				<a href="#"  class="btn btn-default"  data-toggle="tooltip"title="Descargar excel" id='imprimir_excel'>
					<img src="../../img/png/table_excel.png">
				</a>
			</div>
			<div class="col">
				<button title="Consultar"  data-toggle="tooltip" class="btn btn-default" onclick="ListarCatalogoInventarioJS();">
					<img src="../../img/png/consultar.png" >
				</button>
			</div>
	</div>
</div>	

<div class="row mb-3 div_filtro">
	<form id="FormCatalogoCtas">
		<div class="col-xs-8 col-lg-5">
			<div class="row">
				<div class="col-xs-6">
					<b>Cuenta inicial:</b>
					<br>
					<input type="text" name="MBoxCtaI" id="MBoxCtaI" class="form-control input-xs" placeholder="<?php 
					echo $MascaraCodigoK ?>">
				</div>
				<div class="col-xs-6">
					<b> Cuenta final:</b>
					<br>
					<input type="text" name="MBoxCtaF" id="MBoxCtaF" class="form-control input-xs" placeholder="<?php 
					echo $MascaraCodigoK ?>"> 
				</div>       	
			</div>             	
		</div>
		<div class="col-xs-4">
			<div class="row">
				<div class="col-sm-12">
					<br>
					<label class="radio-inline"><input type="checkbox" name="CheqPM" id="CheqPM" value="1" onchange=""><b>Solo Productos de Movimiento</b></label> 
					<input type="hidden" id="heightDisponible" name="heightDisponible" value="100">            			
				</div>             		          		
			</div>
		</div>
	</form>
</div>

<div class="row">
	<div class="col-sm-12" id="tablaProductoCatalogo">
				<table id="tablaProductoCatalogo">
		     	<thead><tr><th class="text-left" style="width:40px">TC</th><th class="text-left" style="width:200px">Codigo_Inv</th><th class="text-left" style="width:300px">Producto</th><th class="text-left" style="width:64px">PVP</th><th class="text-left" style="width:200px">Codigo_Barra</th><th class="text-left" style="width:144px">Cta_Inventario</th><th class="text-left" style="width:136px">Unidad</th><th class="text-right" style="width:136px">Cantidad</th><th class="text-left" style="width:144px">Cta_Costo_Venta</th><th class="text-left" style="width:144px">Cta_Ventas</th><th class="text-left" style="width:144px">Cta_Ventas_0</th><th class="text-left" style="width:144px">Cta_Ventas_Ant</th><th class="text-left" style="width:144px">Cta_Venta_Anticipada</th><th class="text-left" style="width:24px">IVA</th><th class="text-left" style="width:24px">INV</th><th class="text-left" style="width:320px">Codigo_IESS</th><th class="text-left" style="width:136px">Codigo_RES</th><th class="text-left" style="width:240px">Marca</th><th class="text-left" style="width:200px">Reg_Sanitario</th><th class="text-left" style="width:300px">Ayuda</th></tr></thead>
		    </table>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function()
  {

	 	$('#MBoxCtaI').keyup(function(e){ 
			if(e.keyCode != 46 && e.keyCode !=8)
			{
				validar_cuenta_inv(this);
			}
		})

	 	$('#MBoxCtaF').keyup(function(e){ 
			if(e.keyCode != 46 && e.keyCode !=8)
			{
				validar_cuenta_inv(this);
			}
		})

  	asignarHeightPantalla($(".div_filtro"), $("#heightDisponible"))
		$('#imprimir_excel').click(function(){
      var url = '../controlador/inventario/CatalogoC.php?ExcelListarCatalogoInventario=true&'+$("#FormCatalogoCtas").serialize();
      window.open(url, '_blank');
    });

    $('#imprimir_pdf').click(function(){
      var url = '../controlador/inventario/CatalogoC.php?PdfRListarCatalogoInventario=true&'+$("#FormCatalogoCtas").serialize();
      window.open(url, '_blank');
    });
  });

	function ListarCatalogoInventarioJS(){
		$('#myModal_espera').modal('show');
		$.ajax({
      type: "POST",                 
      url: '../controlador/inventario/CatalogoC.php?ListarCatalogoInventario=true',
      dataType:'json', 
      data: $("#FormCatalogoCtas").serialize(),
      success: function(data)             
      {
      	$('#tablaProductoCatalogo').html(data.DGQuery);
        $('#myModal_espera').modal('hide');      
      },
      error: function () {
        $('#myModal_espera').modal('hide');
        alert("Ocurrio un error inesperado, por favor contacte a soporte.");
      }
    });
	}


</script>
