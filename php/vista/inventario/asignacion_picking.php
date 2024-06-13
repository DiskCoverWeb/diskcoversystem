<script type="text/javascript">
	$(document).ready(function () {
  		beneficiario();

  		 $('#beneficiario').on('select2:select', function (e) {
            var datos = e.params.data.data;//Datos beneficiario seleccionado
	        $('#fechAten').val(datos.Fecha_Atencion);//Fecha de Atencion
	        $('#tipoEstado').val(datos.Estado);//Tipo de Estado
	        $('#tipoEntrega').val(datos.TipoEntega);//Tipo de Entrega
	        $('#horaEntrega').val(datos.Hora); //Hora de Entrega
	        $('#diaEntr').val(datos.Dia_Ent.toUpperCase());//Dia de Entrega
	        $('#frecuencia').val(datos.Frecuencia);//Frecuencia
	        $('#tipoBenef').val(datos.TipoBene);//Tipo de Beneficiario
	        $('#totalPersAten').val(datos.No_Soc);//Total, Personas Atendidas
	        $('#tipoPobl').val(datos.Area);//Tipo de Poblacion
	        $('#acciSoci').val(datos.AccionSocial);//Accion Social
	        $('#vuln').val(datos.vulneravilidad);//Vulnerabilidad
	        $('#tipoAten').val(datos.TipoAtencion);//Tipo de Atencion
	        $('#CantGlobSugDist').val(datos.Salario);//Cantidad global sugerida a distribuir
	        $('#CantGlobDist').val(datos.Descuento);//Cantidad global a distribuir
	        $('#infoNutr').val(datos.InfoNutri);
	        cargarOrden();
        });

  	})

  	 function beneficiario() {
  	 	$('#beneficiario').select2({
        placeholder: 'Seleccione una beneficiario',
        // width:'90%',
        ajax: {
           url: '../controlador/inventario/asignacion_pickingC.php?Beneficiario=true',
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

    function cargarOrden() {
    	codigo = $('#beneficiario').val();
    	beneficiario = codigo.split('-');
        var param = {
            'beneficiario':beneficiario[0],
            'tipo':beneficiario[1],
        }
        $.ajax({
            url: '../controlador/inventario/asignacion_pickingC.php?cargarOrden=true',
            type: 'POST',
            dataType: 'json',
            data: { param: param },
            success: function (data) {
            	$('#pnl_detalle').html(data.detalle);
            	$('#ddlgrupoProducto').html(data.ddl);
            	$('#txt_total').val(data.total);
                $('#CantGlobDist').val(data.cantidad);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }


  	function ver_detalle()
  	{
  		cargarOrden();
  		$('#modalDetalleCantidad').modal('show');
  	}

  	function validar_codigo()
  	{
  	 	codigo = $('#txt_codigo').val();
  	 	grupo = $('#ddlgrupoProducto').val();
		var parametros = {
		'codigo':codigo,
		}
	 	$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/egreso_alimentosC.php?buscar_producto=true',
		     data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	data = data[0];
		    	console.log(data);
		    	// if(grupo==data[])
		    	$('#txt_id').val(data.ID)
		    	$('#txt_cod_producto').val(data.Codigo_Barra)
				$('#txt_donante').val(data.Cliente)
				$('#txt_grupo').val(data.Producto)
				$('#txt_stock').val(data.Entrada)
				$('#txt_unidad').val(data.Unidad)
		    }
		});
	

  	}
</script>
<div class="row mb-2">
    <div class="col-lg-4 col-sm-10 col-md-6 col-xs-12">
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
            print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-default">
                <img src="../../img/png/salire.png">
            </a>
        </div>
         <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button title="Guardar" class="btn btn-default" onclick="guardar()">
                <img src="../../img/png/grabar.png">
            </button>
        </div>
    </div>
       
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="box" style="background: #ccff99;">
			<div class="box-body">
				 <div class="col-sm-12">
				        <div class="row mb-2">            
				            <div class="col-sm-5">
				                <div class="row">                   
				                     <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b>Beneficiario/ Usuario:</b>
				                            </div>
				                             <select name="beneficiario" id="beneficiario" class="form-control input-xs"></select>
				                        </div>
				                    </div>
				                </div>
				            </div>
				             <div class="col-sm-3">
				                <div class="row">                   
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">  
				                                <b> Estado</b>
				                            </div>
				                        <input type="tipoEstado" name="tipoEstado" id="tipoEstado" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				            </div>
				            <div class="col-sm-3">
				                <div class="row">
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">  
				                                <b> Tipo de Entrega</b>
				                            </div>
				                        <input type="text" name="tipoEntrega" id="tipoEntrega" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				            </div>

				            <div class="col-sm-3">
				                <div class="row">                   
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">                   
				                                <i class="fa fa-calendar"></i>
				                                <b>Fecha de Atención:</b>
				                            </div>
				                            <input type="date" name="fechAten" id="fechAten" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				            </div>
				            <div class="col-sm-3">
				                <div class="row">                    
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b>Día de Entrega</b>
				                            </div>
				                            <input type="text" name="diaEntr" id="diaEntr" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				            </div>
				            <div class="col-sm-3">
				                <div class="row">                    
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">  
				                                <b><i class="fa fa-clock-o"></i> Hora de Entrega</b>
				                            </div>
				                        <input type="time" name="horaEntrega" id="horaEntrega" class="form-control input-xs">
				                        </div>
				                    </div>
				                </div>
				            </div>
				            <div class="col-sm-3">
				                <div class="row">
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">  
				                                <b>Frecuencia</b>
				                            </div>
				                        <input type="text" name="frecuencia" id="frecuencia" class="form-control input-xs">
				                        </div>
				                    </div>
				                </div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-4">
				                <div class="row">
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b> Tipo de Beneficiario:</b>
				                            </div>
				                        <input type="text" name="tipoBenef" id="tipoBenef" class="form-control input-xs" readonly>
				                        <!-- <span class="input-group-btn">
				                            <button type="button" class="">
				                                <img id="img_tipoBene"  src="../../img/png/cantidad_global.png" style="width: 20px;" />
				                            </button>
				                        </span> -->
				                        </div>
				                    </div>
				                </div>
				                <div class="row">                    
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b>Total, Personas Atendidas:</b>
				                            </div>
				                            <input type="text" name="totalPersAten" id="totalPersAten" class="form-control input-xs" readonly>
				                           <!--  <span class="input-group-btn">
				                            <button type="button" class="" onclick="llenarCamposPoblacion()">
				                                <img id="img_tipoBene"  src="../../img/png/Personas_atendidas.png" style="width: 32px;" />
				                            </button>
				                        </span> -->
				                        </div>
				                    </div>
				                </div>
				                <!-- <div class="row">                   
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b>Tipo de Población:</b>
				                            </div>
				                            <input type="text" name="tipoPobl" id="tipoPobl" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div> -->
				                <div class="row">                    
				                     <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b>  Acción Social:</b>
				                            </div>
				                            <input type="text" name="acciSoci" id="acciSoci" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				                <div class="row">                   
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b> Vulnerabalidad:</b>
				                            </div>
				                            <input type="text" name="vuln" id="vuln" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				                <div class="row">                    
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b> Tipo de Atención:</b>
				                            </div>
				                            <input type="text" name="tipoAten" id="tipoAten" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				            </div>
				            <div class="col-sm-4">
				                <div class="row">				                	
				                   <div class="col-sm-12 ">				                   
							               <div class="input-group input-group-sm">
							               		<div class="input-group-addon input-xs">
						                            <b>CANTIDAD:</b>
						                        </div>

												<input type="text" class="form-control input-xs" id="txt_total">
							               		<div class="input-group-addon input-xs">
						                            <b>Dif:</b>
						                        </div>
												<input type="text" class="form-control input-xs">
												<span class="input-group-btn">
													<button type="button" class="btn btn-info btn-flat btn-sm" onclick="ver_detalle()"><i class="fa fa-eye"></i> Ver detalle</button>
												</span>
											</div>
									</div>					                   
				                </div>
				               
				                <div class="row">
				                	 <div class="col-sm-12 ">
					                	 <div class="input-group input-group-sm">
						               		<div class="input-group-addon input-xs">
					                            <b>Información Nutricional</b>
					                        </div>
					                           <textarea name="infoNutr" id="infoNutr" rows="4" class="form-control input-xs">
					                        </textarea>
										</div>	
									</div>			                   
				                </div>
				                  <div class="row">
				                	 <div class="col-sm-12 ">
					                	 <div class="input-group input-group-sm">
						               		<div class="input-group-addon input-xs">
					                            <b>Comentario de asignacion</b>
					                        </div>
					                           <textarea name="infoNutr" id="infoNutr" rows="4" class="form-control input-xs">
					                        </textarea>
										</div>	
									</div>			                   
				                </div>
				            </div>
				            <div class="col-sm-4">
								<div class="row">	
					              <div class="col-sm-12">
					                  <b>Responsable de asignacion</b><br> 
					                  <div class="input-group">
					                      <input type="text" name="txt_responsable" id="txt_responsable" value="" class="form-control input-xs" readonly>
					                      <span class="input-group-btn">
					                        <button type="button" class="btn btn-warning btn-flat btn-xs" onclick="nueva_notificacion()"><i class="fa  fa-envelope"></i></button>
					                      </span>
					                  </div>
					              </div>	
					            </div>  	
		            			<hr style="margin: 5px 0 5px 0;">          
					            <div class="row"> 					
									<div class="col-sm-12">
										<div class="row text-center">
											<div class="col-sm-6">
												<label style="color:green" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" checked  value="V" > <img src="../../img/png/smile.png"><br> Conforme</label>											
											</div>
											<div class="col-sm-6">
												<label style="color:red" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" value="R">  <img src="../../img/png/sad.png"><br> Inconforme </label>											
											</div>
		                  
										</div>
									</div>
					            </div>            
					            <div class="row"> 
									<div class="col-sm-12" id="pnl_comentario">
					                  <div class="input-group">
					                      <textarea class="form-control input-sm" rows="1" style="font-size:16px" id="txt_comentario2" name="txt_comentario2" placeholder="COMENTARIO DE PICKING"></textarea>
					                      <span class="input-group-btn">
					                        <button type="button" class="btn btn-primary btn-sm" onclick="comentar()"><i class="fa fa-save"></i></button>   
					                      </span>
					                  </div>
									</div>
								</div>
         
				            </div>
				        </div>
				    </div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">		
		<div class="box">
			<div class="box-body">
				<div class="col-sm-5">
		            <div class="input-group">
		                <span class="input-group-btn" style="padding-right: 10px;">
		                     <button type="button" class="btn btn-default" onclick="show_producto();"><img
		                    src="../../img/png/Grupo_producto.png" /> <br> <b>Grupo producto</b></button>
		                </span>
		                <b>Grupo producto:</b>
		                 <select name="ddlgrupoProducto" id="ddlgrupoProducto" class="form-control input-xs" onchange="buscar_producto(this.value)"></select>
		                 <br>
		                 <b>Codigo</b>
		                 <input type="" name="txt_codigo" id="txt_codigo" class="form-control input-xs" placeholder="Codigo de producto" onblur="validar_codigo()">
		            </div>
		        </div>
		        <div class="col-sm-5">
		        	<div class="row">
		        		<div class="col-sm-9">
		        			<b>Proveedor / Donante</b>
		        			<input type="" name="" class="form-control input-xs" placeholder="Proveedor / Donante">
		        		</div>
		        		<div class="col-sm-3">
		        			 <b>Stock:</b>
		        			<input type="" name="" class="form-control input-xs" placeholder="0">
		        		</div>
		        	</div>
		        	<b>Ubicacion</b>
		        	<input type="" name="" class="form-control input-xs" placeholder="Proveedor / Donante">		        	
		        </div>
		        <div class="col-sm-2">
		            <b>Fecha expiracions</b>
		            <input type="date" name="stock" id="stock" class="form-control input-xs" readonly>
		        </div>
		        <div class="col-sm-3">            
		            <div class="input-group">
		                <span class="input-group-btn" style="padding-right: 10px;">
		                     <button type="button" style="width: initial;" class="btn btn-default" onclick="show_cantidad()"
		                    id="btn_cantidad">
		                    <img src="../../img/png/kilo.png" style="width: 42px;height: 42px;" />
		                </button>
		                </span>
		                <b>Cantidad</b>
		                  <input type="number" name="cant" id="cant" class="form-control input-xs">
		            </div>
		        </div>
		        <div class="col-sm-9 text-right">
		            <button class="btn btn-primary btn-sm">Ingreso</button>
		            <button class="btn btn-primary btn-sm">Borrar</button>
		        </div>
				
			</div>
			<div class="col-sm-12">
				<hr style="margin:0px">
			</div>
			<div class="box-body">
				<div class="col-sm-12">
					<table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>FECHA ATENCION</th>
                                <th>FECHA PICKING</th>
                                <th>DESCRIPCION</th>
                                <th>CODIGO</th>
                                <th>USUARIO</th>
                                <th>CANTIDAD (KG)</th>
                                <th>ELIMINAR</th>
                            </tr>
                        </thead>
                        <tbody id="tbl_body"></tbody>
                    </table>
				</div>
			</div>
		</div>

	</div>	
</div>


<div id="modalDetalleCantidad" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ver detalle</h4>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 300px;"  id="pnl_detalle">
                 
				               
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-success" id="btnGuardarGrupo">Aceptar</button> -->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>