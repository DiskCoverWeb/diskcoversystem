<?php date_default_timezone_set('America/Guayaquil'); ?> 
<script type="text/javascript">
  $(document).ready(function () {
  	validar_ingreso();
  	areas();  
  	motivo_egreso()	
  	lista_egreso();
  })


  function validar_ingreso()
  {
  	$.ajax({
		    type: "POST",
	       	url:   '../controlador/inventario/egreso_alimentosC.php?listar_egresos=true',
		    // data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {

		    	$('#tbl_asignados').html(data);	 
		    	if(!data=='')
		    	{ 
	    		Swal.fire({
	                 title: 'Datos entontrados?',
	                 text: "Se encontraron datos sin guardar desea cargarlos?",
	                 type: 'warning',
	                 showCancelButton: true,
	                 confirmButtonColor: '#3085d6',
	                 cancelButtonColor: '#d33',
	                 confirmButtonText: 'Si!'
	               }).then((result) => {
	                 if (result.value!=true) {

	                  	
	                 	eliminar_egreso_all();
	                 }
	               })
		    	} 	
		    }
		});


  }


  function eliminar_egreso(id)
  {
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/egreso_alimentosC.php?eliminar_egreso=true',
	     data:{id:id},
       dataType:'json',
	    success: function(data)
	    {
	    	lista_egreso();
	    }
	});
  }


  function eliminar_egreso_all()
  {
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/egreso_alimentosC.php?eliminar_egreso_all=true',
	     // data:{id:id},
       dataType:'json',
	    success: function(data)
	    {
	    	lista_egreso();
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

	function buscar_producto(codigo)
	{
		var parametros = {
		'codigo':$('#txt_cod_producto').val(),
		}
	 	$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/egreso_alimentosC.php?buscar_producto=true',
		     data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	data = data[0];

		    	$('#txt_id').val(data.ID)
		    	$('#txt_cod_producto').val(data.Codigo_Barra)
				$('#txt_donante').val(data.Cliente)
				$('#txt_grupo').val(data.Producto)
				$('#txt_stock').val(data.Entrada)
				$('#txt_unidad').val(data.Unidad)
		    }
		});
	}

	function add_egreso()
	{
		var parametros = 
		{
			'codigo':$('#txt_cod_producto').val(),
			'id':$('#txt_id').val(),
			'donante':$('#txt_donante').val(),
			'grupo':$('#txt_grupo').val(),
			'stock':$('#txt_stock').val(),
			'unidad':$('#txt_unidad').val(),
			'cantidad':$('#txt_cantidad').val(),
			'fecha':$('#txt_fecha').val(),
			'area':$('#ddl_areas').val(),
			'motivo':$('#ddl_motivo').val(),
			'detalle':$('#txt_detalle').val(),
		}
	 	$.ajax({
		    type: "POST",
	       	url:   '../controlador/inventario/egreso_alimentosC.php?add_egresos=true',
		    data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	if(data==1)
		    	{
		    		Swal.fire("Ingresado","","success");
		    		lista_egreso();
		    	}		    	
		    }
		});

	}
	function lista_egreso()
	{		
	 	$.ajax({
		    type: "POST",
	       	url:   '../controlador/inventario/egreso_alimentosC.php?listar_egresos=true',
		    // data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	$('#tbl_asignados').html(data);	
		    }
		});
	}

  	function guardar()
	{		
		var parametros = {

		}
	 	$.ajax({
		    type: "POST",
	       	url:   '../controlador/inventario/egreso_alimentosC.php?guardar_egreso=true',
		    // data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	if(data==1)
		    	{
		    		Swal.fire('Guardado','','success').then(function(){
		    			location.reload();
		    		})
		    	}
		    }
		});
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
			<button class="btn btn-default" title="" onclick="">
				<img src="../../img/png/mostrar.png">
			</button>
		</div>    	
		<div class="col-xs-2 col-md-2 col-sm-2">
			<button class="btn btn-default" title="Historial" onclick="">
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
			<form id="form_datos">			
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
					<div class="col-sm-3">
						<div class="input-group">
							<div class="input-group-btn" style="padding-right:5px">
								<button type="button" class="btn btn-default btn-sm">
									<img src="../../img/png/area_egreso.png" style="width: 60px;height: 60px;">
								</button>
							</div>
							<br>
							<b>Area de egreso:</b>
							<select class="form-control" id="ddl_areas" name="ddl_areas">
					           	<option value="">Seleccione</option>
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
					<div class="col-sm-3">
						<div class="input-group">
							<div class="input-group-btn" style="padding-right:5px">
								<button type="button" class="btn btn-default btn-sm">
									<img src="../../img/png/detalle_egreso.png" style="width: 60px;height: 60px;">
								</button>
							</div>
							<br>
							<b>Detalle de egreso:</b>
	             			<input type="" class="form-control input-xs" id="txt_detalle" name="txt_detalle">	
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
						<b>Codigo productos</b>
						<input type="" class="form-control input-sm" id="txt_cod_producto" style="font-size: 17px;" name="txt_cod_producto" onblur="buscar_producto()">			
						<input type="hidden" id="txt_id" name="">								
					</div>	
					<div class="col-sm-3">
						<b>Proveedor / Donante</b>
						<input type="" class="form-control input-sm" id="txt_donante" name="txt_donante" readonly>	
								
					</div>														
					<div class="col-sm-3">
						<b>Grupo de producto</b>
						<input type="" class="form-control input-sm" id="txt_grupo" name="txt_grupo">	
								
					</div>	
					<div class="col-sm-1">
						<b>Stock</b>
						<input type="" class="form-control input-sm" id="txt_stock" style="font-size: 20px;" name="txt_stock" readonly>	
								
					</div>	
					<div class="col-sm-1">
						<b>Unidad</b>
						<input type="" class="form-control input-sm" id="txt_unidad" name="txt_unidad" readonly>	
					</div>	
					<div class="col-sm-1">
						<b>Cantidad</b>
								<input type="" class="form-control input-sm" id="txt_cantidad" style="font-size: 17px;" name="txt_cantidad">									
					</div>	
				</div>
				<div class="row">
					<br>
					<div class="col-sm-12 text-right">
						<button class="btn btn-primary btn-sm"><b>Borrar</b></button>
						<button type="button" class="btn btn-primary btn-sm" onclick="add_egreso()"><b>Agregar</b></button>
					</div>
				</div>			
			</form>
				<hr>
				<div class="row">		
					<div class="col-sm-12">
						<table class="table-sm table-hover table">
							<thead>
								<th><b>Item</b></th>
								<th><b>Fecha de Egreso</b></th>
								<th><b>Producto</b></th>
								<th><b>Cantidad</b></th>
								<th></th>
							</thead>
							<tbody id="tbl_asignados">
								<tr>
									<td colspan="5">Productos asignados</td>
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



<div id="myModal_arbol_bodegas" class="modal fade myModalNuevoCliente" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Seleccion manual de bodegas</h4>
            </div>
            <div class="modal-body" id="contenido_prov" style="background: antiquewhite;">
            		<ul class="tree_bod" id="arbol_bodegas">
								</ul>               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div> 
        </div>
    </div>
  </div>

 <script src="../../dist/js/arbol_bodegas/arbol_bodega.js"></script>
