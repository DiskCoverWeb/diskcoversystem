<script type="text/javascript">
	$(document).ready(function () {
  		beneficiario();

  		 $('#beneficiario').on('select2:select', function (e) {
            var datos = e.params.data.data;//Datos beneficiario seleccionado
	        // $('#fechAten').val(datos.Fecha_Atencion);//Fecha de Atencion
	        $('#tipoEstado').val(datos.Estado);//Tipo de Estado
	        $('#tipoEntrega').val(datos.TipoEntega);//Tipo de Entrega
	        $('#horaEntrega').val(datos.Hora); //Hora de Entrega
	        $('#diaEntr').val(datos.Dia_Ent.toUpperCase());//Dia de Entrega
	        $('#frecuencia').val(datos.Frecuencia);//Frecuencia
	        $('#tipoBenef').val(datos.TipoBene);//Tipo de Beneficiario
	        $('#totalPersAten').val(datos.No_Soc);//Total, Personas Atendidas
	        $('#tipoPobl').val(datos.Area);//Tipo de Poblacion
	        $('#acciSoci').val(datos.AccionSocial);//Accion Social
	        $('#vuln').val(datos.vulnerabilidad);//Vulnerabilidad
	        $('#tipoAten').val(datos.TipoAtencion);//Tipo de Atencion
	        $('#CantGlobSugDist').val(datos.Salario);//Cantidad global sugerida a distribuir
	        $('#CantGlobDist').val(datos.Descuento);//Cantidad global a distribuir
	        $('#infoNutr').val(datos.InfoNutri);
	        cargarOrden();
  			cargar_asignacion();

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
  	 	if(codigo=='')
  	 	{
  	 		return false;
  	 	}
  	 	grupo = $('#ddlgrupoProducto').val();
		var parametros = {
		'codigo':codigo,
		'grupo':grupo,
		}
	 	$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/asignacion_pickingC.php?buscar_producto=true',
		     data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	if(data.validado_grupo==0)
		    	{
		    		Swal.fire("Codigo Ingresado no pertenece al grupo de produto","","error").then(function(){
		    			$('#txt_codigo').val("");

		    		});
		    	}else{

		    		data = data.producto[0];
			    	console.log(data);
			    	if(data!=undefined)
			    	{
				    	$('#txt_id').val(data.Codigo_Inv)
				    	$('#txt_ubicacion').val(data.ubicacion)
						$('#txt_donante').val(data.Cliente)
						// $('#txt_grupo').val(data.Producto)
						$('#txt_stock').val(data.Entrada)
						$('#txt_unidad').val(data.Unidad)
					}else
					{
						Swal.fire("Codigo de producto no encontrado","","info");
						limpiar_data();
					}
				}
		    }
		});
  	}


  	function limpiar_data()
  	{
  		$('#txt_id').val("")
    	$('#txt_ubicacion').val("")
		$('#txt_donante').val("")
		// $('#txt_grupo').val(da.Producto)
		$('#txt_stock').val("")
		$('#txt_unidad').val("")
  	}

  	function agregar_picking()
  	{
  		stock = $('#txt_stock').val();
  		cant =$('#cant').val();

  		if($('#beneficiario').val()=='' || $('#beneficiario').val()==null)
  		{
  			Swal.fire("Seleccione una Beneficiario valida","","info");
  			return false;
  		}
  		if($('#txt_id').val()=='' || $('#txt_id').val()== null || $('#txt_id').val()=='0')
  		{
  			Swal.fire("Seleccione una producto","","info");
  			return false;
  		}
  		if($('#cant').val()=='' || $('#cant').val()== null || $('#cant').val()=='0')
  		{
  			Swal.fire("Seleccione una cantidad valida","","info");
  			return false;
  		}
  		
  		if(parseFloat(cant)>parseFloat(stock))
  		{
  			Swal.fire("Cantidad Supera al stock","","info");
  			return false;
  		}


		var parametros = {
		'beneficiario':$('#beneficiario').val(),
		'CodigoInv':$('#txt_id').val(),
		'Cantidad':$('#cant').val(),
		'FechaAte':$('#fechAten').val(),
		'codigoProducto':$('#txt_codigo').val(),
		}
	 	$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/asignacion_pickingC.php?agregar_picking=true',
		     data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	if(data==1)
		    	{
		    		Swal.fire("Producto agregado","","success")
		    		cargar_asignacion();
		    	}else if(data==-2)
		    	{
		    		Swal.fire("El producto no se puede ingresar por que supera el total de Grupo","","error")
		    	}
		    	console.log(data);
		    }
		});
  	}

  	function cargar_asignacion()
  	{
  		var parametros = {
		'beneficiario':$('#beneficiario').val(),
		'FechaAte':$('#fechAten').val(),
		}
	 	$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/asignacion_pickingC.php?cargar_asignacion=true',
		     data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	$('#tbl_body').html(data.tabla);
		    	$('#txt_total_ing').val(data.total);
		    	console.log(data);
		    }
		});

  	}
  	function eliminarlinea(id)
  	{
  		Swal.fire({
	        title: 'Esta seguro?',
	        text: "Esta usted seguro de que quiere borrar este registro!",
	        type: 'warning',
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: 'Si!'
	       }).then((result) => {
	         if (result.value==true) {
	          Eliminar(id);
	         }
	       })
  	}

  	function Eliminar(id)
  	{ 		
  		var parametros = {
		'id':id,
		}
	 	$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/asignacion_pickingC.php?eliminarLinea=true',
		     data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	cargar_asignacion();		    	
		    }
		});
  	}
  	 function guardar() {
    	codigo = $('#beneficiario').val();
    	beneficiario = codigo.split('-');
        var parametros = {
            'beneficiario':beneficiario[0],
            'tipo':beneficiario[1],
            'fecha':$('#fechAten').val(),
        }
        $.ajax({
            url: '../controlador/inventario/asignacion_pickingC.php?GuardarPicking=true',
            type: 'POST',
            dataType: 'json',
            data: { parametros: parametros },
            success: function (data) {
            	Swal.fire("Picking Guardado","","success").then(function (){
            		location.reload();
            	})
            },
            error: function (error) {
                console.log(error);
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
				                            <input type="date" name="fechAten" id="fechAten" class="form-control input-xs" value="<?php echo date('Y-m-d');?>" readonly>
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
												<input type="text" class="form-control input-xs" id="txt_total_ing" name="txt_total_ing">
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
		                 <input type="hidden" id="txt_id" name="txt_id">
		            </div>
		        </div>
		        <div class="col-sm-5">
		        	<div class="row">
		        		<div class="col-sm-9">
		        			<b>Proveedor / Donante</b>
		        			<input type=""  class="form-control input-xs" placeholder="Proveedor / Donante" id="txt_donante" name="txt_donante">
		        		</div>
		        		<div class="col-sm-3">
		        			 <b>Stock:</b>
		        			<input type="" name="txt_stock" id="txt_stock" class="form-control input-xs" placeholder="0" readonly>
		        		</div>
		        	</div>
		        	<b>Ubicacion</b>
		        	<input type="" name="txt_ubicacion" id="txt_ubicacion" class="form-control input-xs" placeholder="Proveedor / Donante" readonly>		        	
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
		            <button class="btn btn-primary btn-sm" onclick="agregar_picking()">Ingreso</button>
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
                                <th width="10%"></th>
                                <th>FECHA ATENCION</th>
                                <th>FECHA PICKING</th>
                                <th>DESCRIPCION</th>
                                <th>CODIGO</th>
                                <th>USUARIO</th>
                                <th>CANTIDAD (KG)</th>
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