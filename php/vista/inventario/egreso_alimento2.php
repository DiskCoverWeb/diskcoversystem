<?php date_default_timezone_set('America/Guayaquil'); ?> 
<script type="text/javascript">
  $(document).ready(function () {
  	
  })

  function modal_mensaje()
  {
	$('#myModal_mensaje').modal('show');
  }
  function modal_motivo()
  {
  	$('#myModal_motivo').modal('show');
  }
  	
   
</script>

 <div class="row">
    <div class="col-lg-4 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
			<button class="btn btn-default" title="Guardar" onclick="guardar()">
				<img src="../../img/png/grabar.png">
			</button>
		</div>
		<div class="col-xs-2 col-md-2 col-sm-2">
			<button class="btn btn-default" title="Guardar" onclick="guardar()">
				<img src="../../img/png/mostrar.png">
			</button>
		</div>    	
		<div class="col-xs-2 col-md-2 col-sm-2">
			<button class="btn btn-default" title="Guardar" onclick="guardar()">
				<img src="../../img/png/file_crono.png" style="width:32px;height:32px">
			</button>
		</div>  
  </div>
  
</div>
<div class="row">
	<div class="col-sm-12">		
		<div class="box">
			<form id="form_correos">
			<div class="box-body" style="background: antiquewhite;">					
				<div class="row">	

					<!-- <div class="col-sm-9"></div> -->
					<div class="col-sm-3 col-md-3">
						<div class="form-group">
								<label class="col-sm-6 control-label" style="padding-left: 0px;">Fecha de Egreso</label>
								<div class="col-sm-6" style="padding: 0px;">
									<input type="date" class="form-control input-xs" id="txt_fecha" name="txt_fecha" value="<?php echo date('Y-m-d'); ?>" readonly>		
								</div>
						</div>		
					</div>
				</div>
				<div class="row">								
					<div class="col-sm-5">
						<div class="input-group">
							<div class="input-group-btn" style="padding-right:5px">
								<button type="button" class="btn btn-default btn-sm">
									<img src="../../img/png/area_egreso.png" style="width: 60px;height: 60px;">
								</button>
							</div>
							<br>
							<b>Area de egreso:</b>
							<select class="form-control" id="txt_codigo" name="txt_codigo">
					           	<option>Seleccione</option>
					        </select>
						</div>				        
				    </div>
				<!--	<div class="col-sm-3">
						<div class="input-group">
							<div class="input-group-btn" style="padding-right:5px">
								<button type="button" class="btn btn-default btn-sm">
									<img src="../../img/png/transporte_caja.png" style="width: 60px;height: 60px;">
								</button>
							</div>
							<br>
		            		<b>Motivo de egreso</b>								
							<input type="" class="form-control input-xs" id="txt_donante" name="txt_donante" readonly>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<div class="input-group-btn" style="padding-right:5px">
								<button type="button" class="btn btn-default btn-sm">
									<img src="../../img/png/detalle_egreso.png" style="width: 60px;height: 60px;">
								</button>
							</div>
							<br>
							<b>Detalle de egreso:</b>
	             			<input type="" class="form-control input-xs" id="txt_cant" name="txt_cant" readonly>	
	             		</div>
					</div>
					<div class="col-sm-3">
						<button type="button" class="btn btn-default btn-sm">
							<img src="../../img/png/clip.png" style="width: 60px;height: 60px;">
							<b>Archivo adjunto</b>
						</button>
					</div>					
				</div>
				<div class="row">
					<div class="col-sm-3">
						<b>Codigo de lugar</b>
						<input type="" class="form-control input-sm" id="txt_cod_lugar" style="font-size: 20px;" name="txt_cod_lugar" onblur="buscar_ruta()">								
					</div>	
					<div class="col-sm-3">
						<b>Proveedor</b>
						<input type="" class="form-control input-sm" id="txt_cod_lugar" style="font-size: 20px;" name="txt_cod_lugar" readonly>	
								
					</div>														
					<div class="col-sm-3">
						<b>Grupo de producto</b>
						<input type="" class="form-control input-sm" id="txt_cod_lugar" style="font-size: 20px;" name="txt_cod_lugar" onblur="buscar_ruta()">	
								
					</div>	
					<div class="col-sm-1">
						<b>Stock</b>
						<input type="" class="form-control input-sm" id="txt_cod_lugar" style="font-size: 20px;" name="txt_cod_lugar" readonly>	
								
					</div>	
					<div class="col-sm-1">
						<b>Unidad</b>
						<input type="" class="form-control input-sm" id="txt_cod_lugar" style="font-size: 20px;" name="txt_cod_lugar" readonly>	
					</div>	
					<div class="col-sm-1">
						<b>Cantidad</b>
								<input type="" class="form-control input-sm" id="txt_cod_lugar" style="font-size: 20px;" name="txt_cod_lugar" onblur="buscar_ruta()">									
					</div>	-->
				</div>
				<div class="row">
					<br>
					<div class="col-sm-12 text-right">
						<button class="btn btn-primary btn-sm"><b>Borrar</b></button>
						<button class="btn btn-primary btn-sm"><b>Agregar</b></button>
					</div>
				</div>
				<hr>
				<div class="row">		
					<div class="table-responsive">
						<table class="table-sm table-hover table">
							<thead>
								<th><b>Item</b></th>
								<th><b>Fecha de Egreso</b></th>
								<th><b>Usuario</b></th>
								<th><b>Motivo</b></th>
								<th><b>Detalle Egreso</b></th>
								<th><b>Archivo adjunto</b></th>
								<th><b>SubModulo gastos</b></th>
								<th><b>Para Contabilizar</b></th>
							</thead>
							<tbody id="tbl_asignados">
								<tr>
									<td>1</td>
									<td>
										2023-10-12
									</td>
									<td>
										<div class="input-group input-group-sm">
											DIEGO C
											<span class="input-group-btn">
											<button type="button" class="btn btn-default btn-sm" onclick="modal_mensaje()">
												<img src="../../img/png/user.png" style="width:20px">
											</button>
											</span>
										</div>
									</td>
									<td>
										<div class="input-group input-group-sm">
											Refrigerio
											<span class="input-group-btn">
											<button type="button" class="btn btn-default btn-sm" onclick="modal_motivo()">
												<img src="../../img/png/transporte_caja.png" style="width:20px">
											</button>
											</span>
										</div>
									</td>
									<td>REFRIGERIO VOLUNTARIO LUNES</td>
									<td>
										<button type="button" class="btn btn-default btn-sm" onclick="$('#file_doc').click()">
											<img src="../../img/png/clip.png" style="width:20px">
										</button>
										<input type="file" id="file_doc" name="" style="display: none;">
									</td>
									<td>
										<select class="form-control input-sm">
											<option value="">Seleccione modulo</option>
										</select>
									</td>
									<td>
										<input type="radio" name="">
									</td>
								</tr>
							</tbody>
						</table>
					</div>	
				</div>
				
			</div>
			</form>
		</div>	
	</div>
</div>



<div id="myModal_mensaje" class="modal fade myModalNuevoCliente" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Mensaje</h4>
            </div>
            <div class="modal-body"  style="background: antiquewhite;">
            	<textarea class="form-control" rows="3"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div> 
        </div>
    </div>
</div>

<div id="myModal_motivo" class="modal fade myModalNuevoCliente" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Motivo</h4>
            </div>
            <div class="modal-body" style="background: antiquewhite;">
            	<div class="col-sm-12">
            		<table class="table">
            			<thead>
            				<th>Item</th>
            				<th>Donante</th>
            				<th>Producto</th>
            				<th>Stock</th>
            				<th>Cant Final(kg)</th>
            				<th>Precio / Costo</th>
            				<th>Total</th>
            				<th>Contabilizar</th>
            			</thead>
            			<tbody>
            				<tr>
            					<td>1</td>
            					<td>Corporacion la favororita</td>
            					<td>Lacteos</td>
            					<td></td>
            					<td>10</td>
            					<td>0.14</td>
            					<td>1.40</td>
            					<td>
            						<input type="radio" name="">
            					</td>
            				</tr>
            			</tbody>
            		</table>
            	</div>           
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div> 
        </div>
    </div>
</div>

 <script src="../../dist/js/arbol_bodegas/arbol_bodega.js"></script>
