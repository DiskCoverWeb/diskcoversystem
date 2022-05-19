<?php 
$prv ='.';
$ben = '.';
$fec = date('Y-m-d');
if(isset($_GET['prv']))
{
	$prv = $_GET['prv'];
}
if(isset($_GET['ben']))
{
	$ben = $_GET['ben'];
} 
if(isset($_GET['fec']))
   $fec = $_GET['fec'];
?>
<script type="text/javascript">
</script>
<script src="../../lib/dist/js/FVentas.js"></script>
<script type="text/javascript">
  $(document).ready(function()
  {    
    $('#ChRetB').focus();    	
    $('#myModal_espera').modal('show');
  	  var fecha = '<?php echo $fec;?>';
      var ben = '<?php echo $ben;?>';
  	  eliminar_air();
    // Ult_fact_Prove('<?php echo $prv; ?>');
    DCBenef_LostFocus(ben,'','');
    // ddl_DCSustento(fecha);
    ddl_DCPorcenIceV(fecha);
    ddl_DCPorcenIvaV(fecha);   
    ddl_DCConceptoRet(fecha);
    cargar_grilla();
    
  });
  </script>
<div class="row">
          <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-8">
                    <div class="box box-info">
                        <div class="box-header" style="padding:0px">
                            <h3 class="box-title">RETENCIONES DE IVA POR </h3>
                            <input type="hidden" name="txt_fecha" id="txt_fecha" value="<?php echo $fec; ?>">
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="radio-inline" onclick="habilitar_bienes()"><input type="checkbox" name="ChRetB" id="ChRetB"> Bienes</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm" id="DCRetIBienes">
                                        <option>Seleccione Tipo Retencion</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="radio-inline" onclick="habilitar_servicios()"><input type="checkbox" name="ChRetS" id="ChRetS">Servicios</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm" id="DCRetISer">
                                        <option>Seleccione Tipo Retencion</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-center">
                  <button class="btn btn-default"> <img src="../../img/png/grabar.png"  onclick="grabacion();"><br> Guardar</button>
                  <button class="btn btn-default"  data-dismiss="modal" onclick="limpiar_retencaion()"> <img src="../../img/png/bloqueo.png" ><br> Cancelar</button>
                </div>            
            </div>
            <div class="row">
              <div class="col-sm-8">
                <b>PROVEEDOR / BENEFICIARIO</b>
                <select class="form-control input-sm" id="DCProveedor">
                  <option value="">No seleccionado</option>
                </select>
              </div>
              <div class="col-sm-1"><br>
                <input type="text" class="form-control input-sm" name="" id="LblTD" style="color: red" readonly="">
              </div>
              <div class="col-sm-3"><br>                
                <input type="text" class="form-control input-sm" name="" id="LblNumIdent" readonly="">
              </div>
            </div>
          </div>
          <div class="col-sm-12">
            <ul class="nav nav-tabs">
               <li class="nav-item active">
                 <a class="nav-link" data-toggle="tab" href="#home">COMPROBANTE DE VENTA: FORMULARIO 104</a>
               </li>
               <li class="nav-item">
                 <a class="nav-link" data-toggle="tab" href="#menu1">INSERTAR CONCEPTO AIR</a>
               </li>               
             </ul>
               <!-- Tab panes -->
             <div class="tab-content">
               <div class="tab-pane modal-body active" id="home">
                   <div class="row box-info box">
                   	<div class="row">
                   		<div class="col-sm-10">
                   			<b>INGRESE LOS DATOS DE LA FACTURA, NOTA DE VENTA, ETC</b>
                   		</div>
                   		<div class="col-sm-2">
                   			<b></b>
                   		</div>
                   	</div>
                   	<div class="row">
                   	   <div class="col-sm-6">
                   	   	<b>Tipo de comprobante</b>
                   	   	    <select class="form-control input-sm" id="DCTipoComprobanteV" name="DCTipoComprobanteV">
                   	   	    	<option value="0">Documentos Autorizados en Ventas excepto ND y NC</option>
                   	   	    </select>                   	   	
                   	   </div>
                   	   <div class="col-sm-3">
                   	   	    <b>Numero</b>
                   	   	    <input type="text" name="TxtNumComprobante" id="TxtNumComprobante" class="form-control input-sm" value="000001">                   	   	    
                   	   </div>  
                   	   <div class="col-sm-3">
                   	   	   	<button class="btn btn-default text-center" onclick="cambiar_air()"><i class="fa fa-arrow-right"></i><br>AIR</button>                   	   	
                   	   </div>                     	
                    </div>
                    <div class="row">
                    	<div class="col-sm-9">
                   	   	<b>Forma de pago</b>
                   	   	    <select class="form-control input-sm" name="DCTipoPago" id="DCTipoPago">
                   	   	    	<option value="">Seleccione</option>
                   	   	    </select>                   	   	
                   	   </div>                    	
                    </div>
                    <div class="row">
                    	<div class="col-sm-4">
                    	     <b>SERIE Y COMPROBANTE</b>
                    	     <div class="row">
                    	 	    <div class="col-sm-4">
                    	 		    <input type="text" name="TxtNumSerieUno" id="TxtNumSerieUno" class="form-control input-sm" value="001">   
                    	 	    </div>
                    	 	    <div class="col-sm-4" style="padding-left: 0px;">
                    	 		    <input type="text" name="TxtNumSerieDos" id="TxtNumSerieDos" class="form-control input-sm" value="001"> 
                    	 	    </div>
                    	 	    <div class="col-sm-4" style="padding-left: 0px;">
                    	 		    <input type="text" name="TxtNumSerietres" id="TxtNumSerietres" class="form-control input-sm" value="000001">  
                    	 	    </div>
                    	     </div>
                    	</div>
                    	<div class="col-sm-2" style="padding-left: 0px; padding-right:5px">
                    		<b>Emision</b>
                    		<input type="date" name="MBFechaEmiV" id="MBFechaEmiV" class="form-control input-sm" value="<?php echo date('Y-m-d')?>">
                    	</div>
                    	<div class="col-sm-2" style="padding-left: 0px; padding-right:5px">
                    		<b>Caducidad</b>
                    		<input type="date" name="MBFechaRegistroV" id="MBFechaRegistroV" class="form-control input-sm" value="<?php echo date('Y-m-d')?>">                    		
                    	</div>
                    	<div class="col-sm-1" style="padding-left: 0px;  padding-right:5px">
                    		<b>Tarifa 0 %</b>
                    		<input type="text" name="TxtBaseImpV" id="TxtBaseImpV" class="form-control input-sm" value="0">                      		
                    	</div>
                    	<div class="col-sm-1" style="padding-left: 0px;  padding-right:2px">
                    		<b>Tarifa 12 %</b>
                    		<input type="text" name="TxtBaseImpGravV" id="TxtBaseImpGravV" class="form-control input-sm" value="0">                      		
                    	</div>
                    	<div class="col-sm-2">                    		
                    		<b>Valores ICE</b>
                    		<input type="text" name="TxtBaseImpoIceV" id="TxtBaseImpoIceV" class="form-control input-sm" value="0">                      		
                    	</div>                    	
                    </div>
                  </div>
                  <div class="row">
                  	<div class="col-sm-6">
                  		<div class="row box-info box">
                  			<div class="col-sm-6">
                  				<b>I.V.A. Presuntivo</b><br>
                  				<label class="form-check-label"><input type="radio" name="rbl_presuntivo" id="" value="S"> SI</label>
                  				<label class="form-check-label"><input type="radio" name="rbl_presuntivo" id="" value="N" checked=""> NO</label>
                  			</div>
                  			<div class="col-sm-6">
                  				<b>Retencion Presuntivo</b><br>
                  				<label class="form-check-label"><input type="radio" name="rbl_ret_presuntivo" id="" value="S"> SI</label>
                  				<label class="form-check-label"><input type="radio" name="rbl_ret_presuntivo" id="" value="N" checked=""> NO</label>
                  			</div>
                  		</div>
                  		<div class="row">
                  			<div class="col-sm-6 form-group" style="padding-left: 0px;">
                  				<label class="col-sm-4 control-label">I.V.A</label>
                  				<div class="col-sm-8">
                  					<select class="form-control input-sm" id="DCPorcenIvaV" name="DCPorcenIvaV" onchange="calcular_iva()">
                  						<option value="0">0</option>
                  					</select>
                  				</div>
                  				<label class="col-sm-4 control-label">No.Autori</label>
                  				<div class="col-sm-8">
                  					<select class="form-control input-sm" name="DCPorcenIceV" id="DCPorcenIceV"  onchange="calcular_ice()">
                  						<option value="0">0</option>
                  					</select>
                  				</div>
                  				
                  			</div>
                  			<div class="col-sm-6 form-group" style="padding-left: 0px;">
                  				<label class="col-sm-5 control-label" style="padding-left: 0px;padding-right: 0px">VALOR IVA</label>
                  				<div class="col-sm-7">
                  					<input type="text" name="TxtMontoIvaV" id="TxtMontoIvaV" class="form-control input-sm"
                  					value="0">
                  				</div>
                  				<label class="col-sm-5 control-label" style="padding-left: 0px;padding-right: 0px">VALOR ICE</label>
                  				<div class="col-sm-7">
                  					<input type="text" name="TxtMontoIceV" id="TxtMontoIceV" class="form-control input-sm" 
                  					value="0">
                  				</div>
                  				
                  			</div>
                  		</div>
                  		
                  	</div>
                  	<div class="col-sm-6">
                  		<div class="col-sm-12">
                            <div class="box box-warning">
                                <div class="box-header" style="padding:0px">
                                    <h3 class="box-title">RETENCION DEL IVA POR BIENES Y/O SERVICIOS</h3>
                                </div>
                                <div class="box-body">
                                     <div class="row">
                                         <div class="col-sm-4"><br>
                                            <b>Monto</b>
                                        </div>
                                        <div class="col-sm-4">
                                            <b>BIENES</b>
                                            <input type="text" name="TxtIvaBienMonIva" class="form-control input-sm" id="TxtIvaBienMonIva" readonly="" value="0">
                                        </div>                            
                                        <div class="col-sm-4">
                                            <b>SERVICIOS</b>
                                           <input type="text" name="TxtIvaSerMonIva" class="form-control input-sm" id="TxtIvaSerMonIva" readonly="" value="0">
                                        </div>       
                                    </div>
                                    <div class="row">
                                         <div class="col-sm-4">
                                            <b>Porcentaje</b>
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="form-control input-sm" id="DCPorcenRetenIvaBien" disabled="" onchange="calcular_retencion_porc_bienes()">
                                                <option value="0">0</option>
                                            </select>
                                        </div>                            
                                        <div class="col-sm-4">
                                          <select class="form-control input-sm" id="DCPorcenRetenIvaServ" disabled="" onchange="calcular_retencion_porc_serv()">
                                                <option value="0">0</option>
                                            </select>
                                        </div>       
                                    </div>
                                    <div class="row">
                                         <div class="col-sm-4">
                                         	<b>Valor RET</b>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="" class="form-control input-sm" id="TxtIvaBienValRet" value="0" readonly="">
                                        </div>                            
                                        <div class="col-sm-4">
                                           <input type="text" name="" class="form-control input-sm" id="TxtIvaSerValRet" value="0" readonly="">
                                        </div>       
                                    </div>
                                </div>
                            </div>
                        </div>    
                  	</div>                  	
                  </div>
               </div>
               <div class="tab-pane modal-body fade" style="padding-top: 2px" id="menu1">
                  <div class="row">
                        <div class="col-sm-12">
                            <div class="box box-info" style="margin-bottom: 2px;">
                                <div class="box-header" style="padding:0px">
                                    <h3 class="box-title"><b>INGRESE LOS DATOS DE LA RETENCION_________________FORMULARIO 103</b></h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                          <label class="radio-inline" onclick="mostra_select()" id="lbl_rbl"><input type="checkbox" name="ChRetF" id="ChRetF"> Retencion en la fuente</label>
                                        </div>
                                        <div class="col-sm-8">
                                          <select class="form-control input-sm" id="DCRetFuente" style="display: none;" onchange="$('#DCRetFuente').css('border','1px solid #d2d6de');">
                                            <option value=""> Seleccione Tipo de retencion</option>
                                          </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-sm-2">
                                        Serie
                                        <div class="row">
                                          <div class="col-sm-6"style="padding-left: 0px;padding-right: 0px;"><input type="text" class="form-control input-sm" name="TxtNumUnoComRet" id="TxtNumUnoComRet" onkeyup="solo_3_numeros(this.id)" placeholder="001" onblur="autocompletar_serie_num(this.id)"></div>
                                          <div class="col-sm-6"style="padding-left: 0px;padding-right: 0px;"><input type="text" class="form-control input-sm" name="TxtNumDosComRet" id="TxtNumDosComRet" onkeyup="solo_3_numeros(this.id)" placeholder="001" onblur="autocompletar_serie_num(this.id)"></div>
                                        </div>
                                      </div>
                                      <div class="col-sm-2">
                                        Numero
                                        <input type="text" class="form-control input-sm" name="TxtNumTresComRet" id="TxtNumTresComRet" onblur="validar_num_retencion()" onkeyup="solo_9_numeros(this.id)" placeholder="000000001">
                                      </div>
                                      <div class="col-sm-4">
                                        Autorizacion
                                        <input type="text" name="" class="form-control input-sm" id="TxtNumUnoAutComRet" >
                                      </div>
                                      <div class="col-sm-4">
                                        <div class="row">
                                          <div class="col-sm-4"><br>
                                            SUMATORIA
                                          </div>
                                          <div class="col-sm-8"><br>
                                            <input type="text" name="" class="form-control input-sm" id="TxtSumatoria">
                                          </div>
                                        </div>                                      
                                      </div>                          
                                    </div>
                                    <div class="row">
                                      <div class="col-sm-7">
                                        <b>CODIGO DE RETENCION</b>
                                        <select class="form-control input-sm" id="DCConceptoRet" name="DCConceptoRet" onchange="calcular_porc_ret()">
                                          <option value="">Seleccione Codigo de retencion</option>
                                        </select>                                        
                                      </div>
                                      <div class="col-sm-2">
                                        <b>BASE IMP</b>
                                        <input type="text" class="form-control input-sm" name="TxtBimpConA" id="TxtBimpConA">
                                      </div>
                                       <div class="col-sm-1" style="padding-left: 0px;padding-right: 0px">
                                        <b>PORC</b>
                                        <input type="text" class="form-control input-sm" name="TxtPorRetConA" id="TxtPorRetConA" onblur="insertar_grid()" readonly="">
                                      </div>
                                       <div class="col-sm-2">
                                        <b>VALOR RET</b>
                                        <input type="text" class="form-control input-sm" name="TxtValConA" id="TxtValConA" readonly="">
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>            
                  </div>
                  <div class="row">
                    <div class="table-responsive" id="tbl_retencion">
                      <!-- <table class="table table-striped table-hover">
                        <thead>
                          <th>CodRet</th>
                          <th>Detalle</th>
                          <th>BaseImp</th>
                          <th>Porcentaje</th>
                          <th>ValRet</th>
                          <th>EstabRetencion</th>
                          <th>PtoEmiRetencion</th>
                          <th>SecRetencion</th>
                          <th>AutoRetencion</th>
                          <th>FechaEmiRet</th>
                          <th>Cta_Retencion</th>
                          <th>EstabFactura</th>
                          <th>PuntoEmiFactura</th>
                          <th>Factura_No</th>
                          <th>IdProv</th>
                          <th>Item</th>
                          <th>codigoU</th>
                          <th>A_No</th>
                          <th>T_No</th>
                          <th>Tipo_Trans</th>
                        </thead>
                        <tbody id="tbl_retencion">
                          
                        </tbody>
                        
                      </table> -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 text-right">
                      <b>Total Retencion</b>
                      <input type="text" class="input-sm" name="">
                    </div>
                  </div>        
               </div>
               
             </div>
            
          </div>
</div>
     