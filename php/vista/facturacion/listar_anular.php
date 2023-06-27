<?php  //print_r( $_SESSION['SETEOS']);die();?>
<script type="text/javascript">
 $(document).ready(function(){
 		DCTipo();
  })

 function DCTipo()
 {
 	 // parametros = {
   //      'guia':$('#LblGuiaR_').val(),
   //      'serie':$('#DCSerieGR').val(),
   //      'auto':$('#LblAutGuiaRem_').val(),
   //  }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?DCTipo=true',
        // data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	llenarComboList(data,'DCTipo');    
        	Fun_DCSerie();        
        }
    });
 }
 function Fun_DCSerie()
 {
 	 parametros = {
        'tc':$('#DCTipo').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?DCSerie=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	llenarComboList(data,'DCSerie');  
        	fun_DCFact();          
        }
    });
 }

 function fun_DCFact()
 {
 	 parametros = {
        'tc':$('#DCTipo').val(),
        'serie':$('#DCSerie').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?DCFact=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	// console.log(data);
        	llenarComboList(data,'DCFact');            
        }
    });
 }
function detalle_factura()
{
	$('#myModal_espera').modal('show');
	parametros = {
        'tc':$('#DCTipo').val(),
        'serie':$('#DCSerie').val(),
        'factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
    }
    console.log(parametros);
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?detalle_factura=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {

        	$('#LabelFechaPe').val(formatoDate(data.FA.Fecha.date));  
        	$('#Label7').val(data.FA.Grupo);      
        	$('#LabelCodigo').val(data.FA.CodigoC);      	
        	$('#TxtAutorizacion').val(data.FA.Autorizacion)
        	$('#TxtClaveAcceso').val(data.FA.Clave_Acceso)

        	 $('#Label8').val(data.FA.Razon_Social+", CI/RUC: "+data.FA.CI_RUC+
        	 	        	 			"\n Dirección: "+data.FA.DireccionC+", Teléfono: "+data.FA.TelefonoC+
		                    		"\n Emails: "+data.FA.EmailC+"; "+data.FA.EmailR+
		                    		"\n Elaborado por: "+data.FA.Digitador +" ("+data.FA.Hora+")")

        	switch (data.FA.T) {
						  case 'A':
						    $('#LabelEstado').val('ANULADO');
						    break;
						  case 'P':						    
						  case 'N':
						    $('#LabelEstado').val('PENDIENTE');
						    break;
						  case 'C':
						    $('#LabelEstado').val('CANCELADA');
						    break;
						  default:
						    $('#LabelEstado').val('NO EXISTE');
						}

        	$('#LabelCliente').val(data.FA.Cliente)
        	$('#LabelVendedor').val(" Ejecutivo: "+data.FA.Ejecutivo_Venta)
        	$('#Label15').val(data.FA.Comercial)
        	$('#TxtObs').val(data.FA.Observacion)
        	$('#LabelTransp').val(data.FA.Nota)

        	$('#tbl_detalle').html(data.detalle);

        	
        	$('#LabelServicio').val(data.FA.Servicio)
        	$('#LabelConIVA').val(data.FA.Con_IVA)
        	$('#LabelSubTotal').val(data.FA.Sin_IVA)
        	$('#LabelSubTotalFA').val(0.00);
        	$('#LabelDesc').val(parseFloat(data.FA.Descuento)+parseFloat(data.FA.Descuento2))
        	$('#LabelIVA').val(data.FA.Total_IVA)
        	$('#LabelTotal').val(data.FA.Total_MN)
        	$('#LabelSaldoAct').val(data.FA.Saldo_MN)

        	$('#myModal_espera').modal('hide');


        console.log(data);         
        }
    });
}

function abonos_fac()
 {
 	$('#FrmTotalAsiento').css('display','none');
 	 parametros = {
        'TC':$('#DCTipo').val(),
        'Serie':$('#DCSerie').val(),
        'Factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?abonos_fac=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	$('#tbl_abonos').html(data);
        }
    });
 }

 function  guias()
 {

 	$('#FrmTotalAsiento').css('display','none');
 	 parametros = {
        'TC':$('#DCTipo').val(),
        'Serie':$('#DCSerie').val(),
        'Factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?guias=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	$('#tbl_guias').html(data);
        }
    });
 }

function contabilizacion()
 {

 	 parametros = {
        'TC':$('#DCTipo').val(),
        'Serie':$('#DCSerie').val(),
        'Factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?contabilizacion=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	$('#tbl_conta').html(data.tbl);
        	$('#FrmTotalAsiento').css('display','initial');
        	$('#LblDiferencia').val(data.LblDiferencia)
        	$('#LabelDebe').val(data.LabelDebe)
        	$('#LabelHaber').val(data.LabelHaber)
        }
    });
 }

function resultado_sri()
 {

 	$('#FrmTotalAsiento').css('display','none');
 	 parametros = {
        'TC':$('#DCTipo').val(),
        'Serie':$('#DCSerie').val(),
        'Factura':$('#DCFact option:selected').text(),
        'Autorizacion':$('#DCFact').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/listar_anularC.php?resultado_sri=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
        	$('#tbl_resultados').html(data);
        }
    });
 }

</script>
<div class="row">
	<div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
		<div class="col-xs-2 col-md-2 col-sm-2">
			 <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
    		<img src="../../img/png/salire.png">
    	</a>
    	</div>
   </div>
</div>
<form id="form_nc">

<div class="row">
	<div class="col-sm-12">
		
		<div class="panel panel-primary" style="margin-bottom: 0px;">			
			<div class="panel-body">
				<div class="row"  style="padding:3px">
					<div class="col-md-2 col-sm-2 col-xs-2" style="padding-right: 0px;">   
             <div class="input-group">
               <div class="input-group-addon input-xs">
                 <b>Tipo de documento:</b>
               </div>
               <select class="form-control input-xs" id="DCTipo" name="DCTipo" onchange="Fun_DCSerie()" style="padding: 0px;">
        					<option value="">Seleccione</option>
				        </select>
             </div>
          </div>
          <div class="col-md-2 col-sm-1 col-xs-1">   
            <div class="input-group">
              <div class="input-group-addon input-xs">
                <b>Serie:</b>
              </div>
              <select class="form-control input-xs" id="DCSerie" name="DCSerie" onchange="fun_DCFact()">
				         	<option value="">Seleccione</option>
				      </select>
          	</div>
          </div>
          <div class="col-md-2 col-sm-2 col-xs-2" style="padding-right: 0px;">   
             <div class="input-group">
               <div class="input-group-addon input-xs">
                 <b>Secuencuial No:</b>
               </div>
                <select class="form-control input-xs" id="DCFact" name="DCFact" onchange="detalle_factura()" style="padding:2px">
				          	<option value="">Seleccione</option>
				        </select>
             </div>
          </div>
					<div class="col-sm-2">
						<input type="date" name="LabelFechaPe" id="LabelFechaPe" class="form-control input-xs" value="" readonly>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2" style="padding-right: 0px;">   
             <div class="input-group">
                <span class="input-group-btn" style="width: auto;">
                 <input type="text" name="Label7" id="Label7" class="form-control input-xs" value="">
								</span>
								<input type="text" name="LabelCodigo" id="LabelCodigo" class="form-control input-xs" value="0.00">              
             </div>
          </div>			
					<div class="col-sm-2">
						<input type="text" name="LabelEstado" id="LabelEstado" class="form-control input-xs" value="0.00">
					</div>
				</div>
				<div class="row" style="padding:3px">
					<div class="col-md-6 col-sm-6 col-xs-6">   
	          <div class="input-group">
	            <div class="input-group-addon input-xs">
	              <b>Clave de Acceso:</b>
	            </div>                         
							<input type="text" name="TxtClaveAcceso" id="TxtClaveAcceso" class="form-control input-xs" value=".">
            </div>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-6">   
            <div class="input-group">
              <div class="input-group-addon input-xs">
              	<b>autorizacion:</b>
              </div>                         
							<input type="text" name="TxtAutorizacion" id="TxtAutorizacion" class="form-control input-xs" value=".">
            </div>
          </div>
				</div>
				
				<div class="row" style="padding:3px">
					<div class="col-sm-2">   
            <div class="input-group">
              <div class="input-group-addon input-xs">
              	<b>Desde:</b>
              </div>                         
							<input type="text" name="TextFDesde" id="TextFDesde" class="form-control input-xs" value="0.00">
            </div>
          </div>
          <div class="col-sm-2">   
            <div class="input-group">
              <div class="input-group-addon input-xs">
              	<b>Hasta:</b>
              </div>                         
							<input type="text" name="TextFHasta" id="TextFHasta" class="form-control input-xs" value="0.00">
							<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-xs"><i class="fa fa-arrow-up"></i></button>
									<button type="button" class="btn btn-default btn-xs"><i class="fa fa-arrow-down"></i></button>
							</span>
            </div>
          </div>
           <div class="col-sm-2">   
           		<input type="date" name="MBFecha" id="MBFecha" class="form-control input-xs" value="">
          </div>
          <div class="col-sm-1" style="padding:0px">   
           		<label><input type="checkbox" name="CheqSoloCopia" id="CheqSoloCopia"> Imprimir solo copia</label>
          </div>
          <div class="col-sm-1" style="padding:0px">   
           		<label><input type="checkbox" name="CheqMatricula" id="CheqMatricula"> Sin deuda pendiente</label>
          </div>
          <div class="col-sm-2" style="padding:0px">   
           		<label><input type="checkbox" name="CheqSinCodigo" id="CheqSinCodigo"> Imprimir sin codigo de alumno</label>
          </div>         
          <div class="col-sm-1 text-right">   
          	<button class="btn btn-default btn-sm"> Actualizar Alumno</button>
          </div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="row">
								<div class="col-sm-12">   
			            <div class="input-group">
			              <div class="input-group-addon input-xs">
			              	<b>Cliente:</b>
			              </div>                         
										<input type="text" name="LabelCliente" id="LabelCliente" class="form-control input-xs" value="0.00">
			            </div>
			          </div>
			          <div class="col-sm-12">  
			            <div class="input-group">
			              <div class="input-group-addon input-xs">
			              	<b>No. de Bultos:</b>
			              </div>
			              <div class="row">
			              <div class="col-sm-3" style="padding-right: 0px;">
											<input type="text" class="form-control input-xs" id="LabelBultos" name="LabelBultos" placeholder=".">
										</div>
										<div class="col-sm-9" style="padding-left: 0px;">
												<input type="text" name="LabelVendedor" id="LabelVendedor" class="form-control input-xs" value="">
										</div>
										</div>
			            </div>
			          </div>
			          <div class="col-sm-12">   
			            <div class="input-group">
			              <div class="input-group-addon input-xs">
			              	<b>Entregado en:</b>
			              </div>                         
										<input type="text" name="Label15" id="Label15" class="form-control input-xs" value="0.00">
			            </div>
			          </div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-12">
								<textarea rows="4" style="resize:none;font-size: 11px;" class="form-control" readonly id="Label8" name="Label8"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">   
            <div class="input-group">
              <div class="input-group-addon input-xs">
              	<b>Observacion:</b>
              </div>                         
							<input type="text" name="TxtObs" id="TxtObs" class="form-control input-xs" value="">
            </div>
          </div>
          <div class="col-sm-12">   
            <div class="input-group">
              <div class="input-group-addon input-xs">
              	<b>Nota:</b>
              </div>                         
							<input type="text" name="LabelTransp" id="LabelTransp" class="form-control input-xs" value="">
            </div>
          </div>					
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
      <div class="panel panel-primary">
      	<div class="panel-body">
	          <ul class="nav nav-tabs">
	            <li class="active"><a href="#tab_detalle" data-toggle="tab" onclick="$('#FrmTotalAsiento').css('display','none');">DETALLE DE FACTURA</a></li>
	            <li><a href="#tab_abono" data-toggle="tab" onclick="abonos_fac()">ABONOS DE LA FACTURA</a></li>
	            <li><a href="#tab_guia" data-toggle="tab" onclick="guias()">GUIA DE REMISION</a></li>
	            <li><a href="#tab_conta" data-toggle="tab" onclick="contabilizacion()">CONTABILIZACION</a></li>
	            <li><a href="#tab_resultado" data-toggle="tab" onclick="resultado_sri()">RESULTADO SRI</a></li>
	          </ul>
	          <div class="tab-content">
	            <div class="tab-pane fade in active" id="tab_detalle"> 
	            	<div class="row" >
	            		<br>
	            		<div class="col-sm-12" id="tbl_detalle">
	            			
	            		</div>
	            		
	            	</div>
	            </div>
	            <div class="tab-pane fade" id="tab_abono">
	              <div class="row" >
	            		<br>
	            		<div class="col-sm-12" id="tbl_abonos">
	            			
	            		</div>
	            		
	            	</div>
	            </div>
	            <div class="tab-pane fade" id="tab_guia">
	            	<div class="row" >
	            		<br>
	            		<div class="col-sm-12" id="tbl_guias">
	            			
	            		</div>
	            		
	            	</div>
	            </div>
	            <div class="tab-pane fade" id="tab_conta">
	            	<div class="row" >
	            		<br>
	            		<div class="col-sm-12" id="tbl_conta">
	            			
	            		</div>	            		
	            	</div>
	            </div>
	            <div class="tab-pane fade" id="tab_resultado">
	              <div class="row" >
	            		<br>
	            		<div class="col-sm-12" id="tbl_resultados">
	            			
	            		</div>
	            		
	            	</div>
	            </div>
	      </div>
      </div>
    </div>
  </div>
</div>
<div class="row" id="FrmTotalAsiento" style="display:none;">	
	<div class="col-sm-6">
		<div class="row">
				<div class="col-sm-6">   
          <div class="input-group">
            <div class="input-group-addon input-xs">
            	<b>Diferencias:</b>
            </div>                         
						<input type="text" name="LblDiferencia" id="LblDiferencia" class="form-control input-xs" value="0.00">
          </div>
        </div>
        <div class="col-sm-6">  
          <div class="input-group">
            <div class="input-group-addon input-xs">
            	<b>TOTALES:</b>
            </div>
            <div class="row">
            <div class="col-sm-6" style="padding-right: 0px;">
							<input type="text" class="form-control input-xs" id="LabelDebe" name="LabelDebe" placeholder="0.00">
						</div>
						<div class="col-sm-6" style="padding-left: 0px;">
								<input type="text" name="LabelHaber" id="LabelHaber" class="form-control input-xs" value="0.00">
						</div>
						</div>
          </div>
        </div>			         
		</div>
	</div>
</div>
<div class="row">	
	<div class="col-sm-2"  style="padding-right: 1px">
     	<label>Subtotal sin iva</label>
      <input type="text" name="LabelSubTotal" id="LabelSubTotal" class="form-control input-xs">
	</div>
	<div class="col-sm-2"  style="padding:1px">
      <label>Subtotal con iva</label>
      <input type="text" name="LabelConIVA" id="LabelConIVA" class="form-control input-xs"> 
	</div>
	<div class="col-sm-1"  style="padding:1px">
      <label>Descuento</label>
      <input type="text" name="LabelDesc" id="LabelDesc" class="form-control input-xs">
  </div>
	<div class="col-sm-1"  style="padding:1px">
     <label> Subtotal</label>
     <input type="text" name="LabelSubTotalFA" id="LabelSubTotalFA" class="form-control input-xs">
 	</div>
	<div class="col-sm-1"  style="padding:1px">
    <label> I.V.A</label>
    <input type="text" name="LabelIVA" id="LabelIVA" class="form-control input-xs">
	</div>
	<div class="col-sm-2"  style="padding:1px">
	    <label>Subtotal Servicios</label>
      <input type="text" name="LabelServicio" id="LabelServicio" class="form-control input-xs">
  </div>
  <div class="col-sm-1"  style="padding:1px">
	    <label>Total Factura</label>
      <input type="text" name="LabelTotal" id="LabelTotal" class="form-control input-xs">
  </div>
  <div class="col-sm-1"  style="padding:1px">
	    <label>Saldo actual</label>
      <input type="text" name="LabelSaldoAct" id="LabelSaldoAct" class="form-control input-xs">
  </div>
</div>

</form>