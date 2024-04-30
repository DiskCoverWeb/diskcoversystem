<?php date_default_timezone_set('America/Guayaquil'); ?> 
<script type="text/javascript">
  $(document).ready(function () {
  	lista_egreso_checking();
  	areas();  
  	motivo_egreso()	
  })

  function modal_mensaje(orden)
  {
	$('#myModal_notificar_usuario').modal('show');
	$('#txt_codigo').val(orden);
  }

  function notificar(usuario = false)
 {
 		var mensaje = $('#txt_notificar').val();
 		var para_proceso = 4;
 		if(usuario=='usuario')
 		{
 			mensaje = $('#txt_texto').val();
 			para_proceso = 4;
 		}
   
    var parametros = {
        'notificar':mensaje,
        'asunto':'De Checking egreso',
        'pedido':$('#txt_codigo').val(),
        'de_proceso':5,
        'pa_proceso':para_proceso,
    }

     $.ajax({
      data:  {parametros,parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?notificar_egresos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
        	
          	Swal.fire("","Notificacion enviada","success").then(function(){
          	$('#myModal_notificar_usuario').modal('hide'); 
          	$('#txt_texto').val('');   
          	$('#txt_codigo_usu').val('') 
          });
        }
        console.log(response);
        
      }, 
      error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');           
      }
    });
 }


  function modal_motivo(orden)
  {
  	cargar_motivo_lista(orden);
  	$('#myModal_motivo').modal('show');
  }

  function cargar_motivo_lista(orden)
	{		
		var parametros = {
			'orden':orden
		}
	 	$.ajax({
		    type: "POST",
	       	url:   '../controlador/inventario/egreso_alimentosC.php?cargar_motivo_lista=true',
		    data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	$('#txt_motivo_lista').html(data);	
		    }
		});
	}
  
  function lista_egreso_checking()
	{		
	 	$.ajax({
		    type: "POST",
	       	url:   '../controlador/inventario/egreso_alimentosC.php?lista_egreso_checking=true',
		    // data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	$('#tbl_asignados').html(data);	
		    }
		});
	}

	 function areas(){
	  $('#ddl_areas').select2({
	    placeholder: 'Seleccione una beneficiario',
	    // width:'90%',
	    ajax: {
	      url:   '../controlador/inventario/egreso_alimentosC.php?areas=true',          
	      dataType: 'json',
	      delay: 250,
	      processResults: function (data) {
	        // console.log(data);
	        return {
	          results: data
	        };
	      },
	      cache: true
	    }
	  });
	}

	function motivo_egreso(){
	  $('#ddl_motivo').select2({
	    placeholder: 'Seleccione una beneficiario',
	    // width:'90%',
	    ajax: {
	      url:   '../controlador/inventario/egreso_alimentosC.php?motivos=true',          
	      dataType: 'json',
	      delay: 250,
	      processResults: function (data) {
	        // console.log(data);
	        return {
	          results: data
	        };
	      },
	      cache: true
	    }
	  });
	}

	function mostra_doc(documento)
	{
		$('#modal_documento').modal('show');
		$('#img_documento').attr('src','../comprobantes/sustentos/empresa_<?php echo $_SESSION['INGRESO']['item']; ?>/'+documento)
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
		<!-- <div class="col-xs-2 col-md-2 col-sm-2">
			<button class="btn btn-default" title="Guardar" onclick="guardar()">
				<img src="../../img/png/mostrar.png">
			</button>
		</div>    	 -->
		<!-- <div class="col-xs-2 col-md-2 col-sm-2">
			<button class="btn btn-default" title="Guardar" onclick="guardar()">
				<img src="../../img/png/file_crono.png" style="width:32px;height:32px">
			</button>
		</div>   -->
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
							<select class="form-control" id="ddl_areas" name="ddl_areas">
					           	<option>Seleccione</option>
					        </select>
						</div>				        
				    </div>
				    <div class="col-sm-3">
						<div class="input-group">
							<div class="input-group-btn" style="padding-right:5px">
								<button type="button" class="btn btn-default btn-sm">
									<img src="../../img/png/transporte_caja.png" style="width: 60px;height: 60px;">
								</button>
							</div>
							<br>
		            		<b>Motivo de egreso</b>								
							<select class="form-control" id="ddl_motivo" name="ddl_motivo">
					           	<option value="">Seleccione</option>
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
				<!-- <div class="row">
					<br>
					<div class="col-sm-12 text-right">
						<button class="btn btn-primary btn-sm"><b>Borrar</b></button>
						<button class="btn btn-primary btn-sm"><b>Agregar</b></button>
					</div>
				</div> -->
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




<div id="myModal_notificar_usuario" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Notificacion</h4>
            </div>
            <div class="modal-body" style="background: antiquewhite;">
              <input type="hidden" name="txt_codigo" id="txt_codigo">
                <textarea class="form-control form-control-sm" rows="3" id="txt_texto" name="txt_texto" placeholder="Detalle de notificacion"></textarea>
            </div>
             <div class="modal-footer">             	
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="notificar('usuario')">Notificar</button>
            </div>
        </div>
    </div>
  </div>
 <div id="modal_documento" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Foto Evidencia</h4>
            </div>
            <div class="modal-body" style="background: antiquewhite;">
            	<div class="row">
            		<div class="col-sm-12 text-center">
            			<img src="" id="img_documento" name="img_documento" width="50%">
            		</div>
            	</div>
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
            			<tbody id="txt_motivo_lista">
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
