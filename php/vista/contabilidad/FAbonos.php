<?php 

?>
<script type="text/javascript"></script>
<script src="../../dist/js/FAbonos.js"></script>
<script type="text/javascript">
  $(document).ready(function()
  {
       
  });
 
  </script>
  <div class="row">
  	<div class="col-sm-10">
  		<form id="form_abonos">
  			

			  <div class="row">
				  	<div class="col-sm-4 col-xs-4" style="padding:0px">
							<b class="col-sm-7 col-xs-8 control-label" style="font-size: 11.5px;padding-right: 0px;"><input type="checkbox" name="CheqRecibo" id="CheqRecibo" checked> INGRESO CAJA No.</b>
							<div class="col-sm-5 col-xs-4" style="padding:0px">
								<input type="text" name="TxtRecibo" id="TxtRecibo" class="form-control input-sm" value="0000000">
							</div>
						</div>
						<div class="col-sm-4 col-xs-3" style="padding:0px">
								<b class="col-sm-6 col-xs-7">COTIZACION</b>
								<div class="col-sm-6 col-xs-5" style="padding:0px">
									<input type="text" name="LabelDolares" id="LabelDolares" class="form-control input-sm text-right" value="0.00">
								</div>
						</div>
						<div class="col-sm-4 col-xs-5" style="padding:0px">
								<b class="col-sm-6 col-xs-6 control-label" style="padding: 0px">Fecha del abono</b>
								<div class="col-sm-6 col-xs-6" style="padding: 0px">
									<input type="date" name="MBFecha" id="MBFecha" class="form-control input-sm" value="<?php echo date('Y-m-d');?>">
								</div>
						</div>			  	
			  </div>
			  <div class="row">
				  	<div class="col-sm-3 col-xs-4">
							<b class="col-sm-5 col-xs-8 control-label" style="padding:0px">Tipo de Documento.</b>
							<div class="col-sm-7 col-xs-4" style="padding:0px">
								<select class="form-control input-sm" id="DCTipo" name="DCTipo" onchange="DCSerie()">
									<option value="FA">FA</option>
								</select>
							</div>
						</div>
						<div class="col-sm-3 col-xs-3" style="padding:0px">
							<label class="col-sm-6 col-xs-6 control-label"> Serie.</label>
							<div class="col-sm-6 col-xs-6" style="padding:0px">
								<select class="form-control input-sm" id="DCSerie" name="DCSerie" onchange="DCFactura_()">
									<option value="001001">001001</option>
								</select>
							</div>
						</div>
						<div class="col-sm-3 col-xs-3">
							<b class="col-sm-3 col-xs-3" style="padding:0px" id="Label2" name="Label2">No.</b>
							<div class="col-sm-9 col-xs-9" style="padding:0px">
								<select class="form-control input-sm" id="DCFactura" name="DCFactura" onchange="DCAutorizacionF();DCFactura1()">
									<option value="00000000">000000000</option>
								</select>
							</div>
						</div>
						<div class="col-sm-3 col-xs-2" style="padding:0px">
							<b class="col-sm-4 col-xs-4" style="padding:0px">Saldo</b>
							<div class="col-sm-8 col-xs-8" style="padding:0px">
								<input type="text" name="LabelSaldo" id="LabelSaldo" class="form-control input-sm text-right" value="0.00">
							</div>
						</div>  	
			  </div>



			  <div class="row">
			  	<div class="col-sm-12 col-xs-12" style="padding:0px">
						<b class="col-sm-2 col-xs-2">Autorizacion.</b>
						<div class="col-sm-10 col-xs-10">
							<select class="form-control input-sm" id="DCAutorizacion" name="DCAutorizacion">
								<option value="00000000">000000000</option>
							</select>
						</div>
					</div>
			  </div>

			  <div class="row">
				  	<div class="col-sm-8 col-xs-8">
							<input type="text" name="LblCliente" id="LblCliente" class="form-control input-sm" placeholder="Cliente">
							<input type="hidden" name="CodigoC" id="CodigoC" class="form-control input-sm" placeholder="Cliente">
							<input type="hidden" name="CI_RUC" id="CI_RUC">
						</div>
						<div class="col-sm-4 col-xs-4">		
							<input type="text" name="LblGrupo" id="LblGrupo" class="form-control input-sm" placeholder="Grupo No">
						</div>
			  </div>


			  <div class="row">
			  	<div class="col-sm-2 col-xs-2">
				  		<b>Serie Retencion</b>
									<input type="text" name="TxtSerieRet" id="TxtSerieRet" class="form-control input-sm" placeholder="001" value="001001">
						</div>
					<div class="col-sm-2 col-xs-2">
				  		<b>Retencion No</b>
							<input type="text" name="TextCompRet" id="TextCompRet" class="form-control input-sm text-right" placeholder="00000000" value="99999999">	
					</div>
					<div class="col-sm-8 col-xs-8">	
						<b>Autorizacion	</b>
						<input type="text" name="TxtAutoRet" id="TxtAutoRet" class="form-control input-sm" placeholder="Grupo No" value="000000000">
					</div>
			  </div>


			 <div class="row">
			  	<div class="col-sm-8 col-xs-7">
					<div class="row">
						<div class="col-sm-10 col-xs-10">
							<b>RETENCION DEL I.V.A. EN BIENES</b><br>
								<input type="hidden" name="DCRetIBienesNom" id="DCRetIBienesNom">
							<select class="form-control input-sm" id="DCRetIBienes" name="DCRetIBienes" onchange="$('#DCRetIBienesNom').val($('#DCRetIBienes option:selected').text())">
								<option value="">Retencion en bienes</option>
							</select>
						</div>
						<div class="col-sm-2 col-xs-2" style="padding:0px"><b>%</b>
							<select class="form-control input-sm" id="CBienes" name="CBienes">
								<option value="0">0</option>
								<option value="10">10</option>
								<option value="30">30</option>
								<option value="100">100</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-xs-5">	<br>
					<b class="col-sm-7 col-xs-6" style="padding:0px">VALOR RETENIDO.</b>
					<div class="col-sm-5 col-xs-6">
						<input type="text" name="TextRetIVAB" id="TextRetIVAB" class="form-control input-sm text-right" placeholder="0.00" value="0.00" onblur="Calculo_Saldo()">
					</div>
				</div>
			  </div>
			  <div class="row">
			  	<div class="col-sm-8 col-xs-7">
					<div class="row">
						<div class="col-sm-10 col-xs-10" >
							<b>RETENCION DEL I.V.A. EN SERVICIO </b>
								<input type="hidden" name="DCRetISerNom" id="DCRetISerNom">
							<select class="form-control input-sm" id="DCRetISer" name="DCRetISer" onchange="$('#DCRetISerNom').val($('#DCRetISer option:selected').text())">
								<option value="">Retencion en servicios</option>
							</select>
						</div>
						<div class="col-sm-2 col-xs-2" style="padding:0px"><b>%</b>
							<select class="form-control input-sm" id="CServicio" name="CServicio">
								<option value="0">0</option>
								<option value="20">20</option>
								<option value="70">70</option>
								<option value="100">100</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-xs-5">	<br>
					<b class="col-sm-7 col-xs-6" style="padding:0px">VALOR RETENIDO.</b>
					<div class="col-sm-5 col-xs-6">
						<input type="text" name="TextRetIVAS" id="TextRetIVAS" class="form-control input-sm text-right" placeholder="0.00" onblur="Calculo_Saldo()" value="0.00">
					</div>
				</div>
			  </div>
			  <div class="row">
			  	<div class="col-sm-8 col-xs-7">
					<div class="row">
						<div class="col-sm-7 col-xs-7" style="padding-right:0px">
							<b>RETENCION EN LA FUENTE</b>
							<select class="form-control input-sm" id="DCRetFuente" name="DCRetFuente">
								<option value="">Retencion en la fuente</option>
							</select>
						</div>
						<div class="col-sm-3 col-xs-3" style="padding:0px"><b>CODIGO </b> 
							<select class="form-control input-sm" id="DCCodRet" name="DCCodRet">
								<option value="">Codigo</option>
							</select>
						</div>
						<div class="col-sm-2 col-xs-2" style="padding:0px">
							<b>%</b>
							<input type="text" name="TextPorc" id="TextPorc" class="form-control input-sm" placeholder="000">				
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-xs-5">	<br>
					<b class="col-sm-7 col-xs-6" style="padding: 0px;">VALOR RETENIDO.</b>
					<div class="col-sm-5 col-xs-6">
						<input type="text" name="TextRet" id="TextRet" class="form-control input-sm text-right" placeholder="00000000" onblur="Calculo_Saldo()" value="0.00">
					</div>
				</div>
			  </div>

			  <div class="row">
			  	<div class="col-sm-9 col-xs-8">
					<div class="row">
						<div class="col-sm-6 col-xs-5" style="padding-right: 0px;">
							 <b>CUENTA DEL BANCO </b>							           
							 <input type="hidden" name="DCBancoNom" id="DCBancoNom">
							<select class="form-control input-sm" id="DCBanco" name="DCBanco" onchange="$('#DCBancoNom').val($('#DCBanco option:selected').text())">        					
								<option value="">Cuenta Banco</option>
							</select>
						</div>
						<div class="col-sm-3 col-xs-3"><b>CHEQUE </b>
							<input type="text" name="TextCheqNo" id="TextCheqNo" class="form-control input-sm" placeholder="00000000">		
						</div>
						<div class="col-sm-3 col-xs-4" style="padding:0px"><b>NOMBRE DE BANCO</b>
							<input type="text" name="TextBanco" id="TextBanco" class="form-control input-sm" placeholder="00000000">				
						</div>
					</div>
				</div>
				<div class="col-sm-3 col-xs-4">	<br>
					<b class="col-sm-6  col-xs-6">VALOR.</b>
					<div class="col-sm-6 col-xs-6 " style="padding-left: 0px;">
						<input type="text" name="TextCheque" id="TextCheque" class="form-control input-sm text-right" placeholder="0.00" onblur="Calculo_Saldo()" value="0.00">
					</div>
				</div>
			  </div>
			  <div class="row">
			  	<div class="col-sm-9 col-xs-8">
					<div class="row">
						<div class="col-sm-6 col-xs-5" style="padding-right: 0px;">
							 <b>TARJETA DE CREDITO</b>    
							 <input type="hidden" name="DCTarjetaNom" id="DCTarjetaNom">            
							<select class="form-control input-sm" id="DCTarjeta" name="DCTarjeta" onchange="$('#DCTarjetaNom').val($('#DCTarjeta option:selected').text())">        					
								<option value="">Tarjeta credito</option>
							</select>
						</div>
						<div class="col-sm-2 col-xs-3"><b>BAUCHER </b>
							<input type="text" name="TextBaucher" id="TextBaucher" class="form-control input-sm" placeholder="00000000">		
						</div>
						<div class="col-sm-4 col-xs-4" style="padding:0px"><b>INTERES DE LA TARJETA</b>
							<input type="text" name="TextInteres" id="TextInteres" class="form-control input-sm text-right" placeholder="00000000" value="0" onblur="TextInteres();TextRecibido();">				
						</div>
					</div>
				</div>
				<div class="col-sm-3 col-xs-4">	<br>
					<b class="col-sm-6 col-xs-6">VALOR.</b>
					<div class="col-sm-6 col-xs-6 " style="padding-left: 0px;">
						<input type="text" name="TextTotalBaucher" id="TextTotalBaucher" class="form-control input-sm text-right" placeholder="00000000" onblur="Calculo_Saldo()" value="0.00">
					</div>
				</div>
			</div>
			<div class="row">
			  	<div class="col-sm-6 col-xs-6">
					<div class="row">
						<div class="col-sm-12 col-xs-12" >
							<textarea placeholder="Observacion" rows="2" style="resize: none;" class="form-control input-sm"></textarea>
							 <textarea placeholder="Nota" rows="2" style="resize: none;" class="form-control input-sm"></textarea>
							 <b>Vendedor</b>
							 <select class="form-control input-sm" id="DCVendedor" name="DCVendedor">
							 	<option value="">Vendedor</option>
							 </select>
							
						</div>		 
					</div>
				</div>
				<div class="col-sm-6 col-xs-6">
					<div class="row">
						<label class="col-sm-6 col-xs-6 control-label">Caja MN.</label>
						<div class="col-sm-6 col-xs-6">
							<input type="text" name="TextCajaMN" id="TextCajaMN" class="form-control input-sm text-right" placeholder="00000000" value="0.00">
						</div>			
					</div>
					<div class="row">
						<label class="col-sm-6 col-xs-6 control-label">Caja ME.</label>
						<div class="col-sm-6 col-xs-6">
							<input type="text" name="TextCajaME" id="TextCajaME" class="form-control input-sm text-right" placeholder="00000000" value="0.00">
						</div>			
					</div>
					<div class="row">
						<label class="col-sm-6 col-xs-6 control-label">SALDO ACTUAL.</label>
						<div class="col-sm-6 col-xs-6">
							<input type="text" name="LabelPend" style="color:red;" id="LabelPend" class="form-control input-sm text-right" placeholder="00000000" value="0.00">
						</div>			
					</div>
					<div class="row">
						<label class="col-sm-6 col-xs-6 control-label">VALOR RECIBIDO.</label>
						<div class="col-sm-6 col-xs-6">
							<input type="text" name="TextRecibido" id="TextRecibido" class="form-control input-sm text-right" placeholder="00000000" value="0.00">
						</div>			
					</div>
					<div class="row">
						<label class="col-sm-6  col-xs-6 control-label">CAMBIO A ENTREGAR.</label>
						<div class="col-sm-6 col-xs-6 ">
							<input type="text" name="LabelCambio" style="color:red;" id="LabelCambio" class="form-control input-sm text-right" placeholder="00000000" value="0.00">
						</div>			
					</div>
					
				</div>
			  </div>
			  <input type="hidden" name="Cta_Cobrar" id="Cta_Cobrar">
  		</form>
  	</div>
  	<div class="col-sm-2">
  		<button class="btn btn-default" id="btn_g" onclick="guardar_abonos();"> <img src="../../img/png/grabar.png"><br>&nbsp;Guardar&nbsp;</button>
       <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> --><br> <br>
     <button class="btn btn-default" onclick="cerrar_modal()"> <img src="../../img/png/bloqueo.png" ><br> Cancelar</button>  		
  	</div>  	
  </div>
  <script type="text/javascript">
  	
  </script>