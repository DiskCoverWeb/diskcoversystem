<?php date_default_timezone_set('America/Guayaquil'); ?>
  <link rel="stylesheet" href="../../dist/css/arbol_bodegas/reset.min.css">
  <link rel="stylesheet" href="../../dist/css/arbol_bodegas/arbol_bodega.css">
  <script src="../../dist/js/arbol_bodegas/prefixfree.min.js"></script>
 
<script type="text/javascript">
  $(document).ready(function () {
			// cargar_bodegas();
			// cargar_bodegas2();
           	lista_stock_ubicado();
  	 $('#txt_bodega').keydown( function(e) { 
          var keyCode1 = e.keyCode || e.which; 
          if (keyCode1 == 13) { 
          	 codigo = $('#txt_bodega').val();
          	 codigo = codigo.trim();
          	 $('#txt_bodega').val(codigo);
           	lista_stock_ubicado();
          }
      });  
  	  $('#txt_cod_barras').keydown( function(e) { 
          var keyCode1 = e.keyCode || e.which; 
          if (keyCode1 == 13) { 
          	codigo = $('#txt_cod_barras').val();
          	codigo = codigo.trim();
          	$('#txt_cod_barras').val(codigo);

           	lista_stock_ubicado();
          }
      });  
  })

function abrir_modal_bodegas(op='')
{
	if(op=='')
	{
	 $('#arbol_bodegas2').html('');

	 console.log('1');
	 cargar_bodegas();
	 $('#myModal_arbol_bodegas').modal('show');
	}else
	{		

	 $('#arbol_bodegas').html('');
	 console.log('2');
	 cargar_bodegas2();
	 $('#myModal_arbol_bodegas2').modal('show');
	}
}

function cargar_bodegas(nivel=1,padre='')
{
	var parametros = {
		'nivel':nivel,
		'padre':padre,
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?lista_bodegas_arbol=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	// console.log(data);
	    	if(nivel==1)
	    	{
	    	 $('#arbol_bodegas').html(data);
	    	}else
	    	{
	    		 $('#h'+padre).html(data);
	    	}
	    }
	});  
}
function cargar_nombre_bodega(nombre,cod)
{

	$('#txt_bodega_title').text();
	$('#txt_bodega_title').text(nombre);
	$('#txt_bodega').val(cod);
	if(cod!='.')
	{
		contenido_bodega();
	}
}

function contenido_bodega()
{
	var parametros = {
		'num_ped':'',//$('#txt_codigo').val(),
		'bodega':$('#txt_bodega').val(),
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?contenido_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	$('#arbol_bodegas li span.label-success').removeClass('label-success');
	    	id = $('#txt_bodega').val();
	    	// console.log(id);
	    	id = id.replaceAll('.','_');
	    	$('#contenido_bodega').html(data);
	    	$('#c_'+id).addClass('label-success');	
	    	// productos_asignados();
	    }
	});
}

function cargar_bodegas2(nivel=1,padre='')
{
	var parametros = {
		'nivel':nivel,
		'padre':padre,
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?lista_bodegas_arbol2=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	// console.log(data);
	    	if(nivel==1)
	    	{
	    	 $('#arbol_bodegas2').html(data);
	    	}else
	    	{
	    		 $('#h'+padre).html(data);
	    	}
	    }
	});  
}
function cargar_nombre_bodega2(nombre,cod)
{

	$('#txt_bodega_title2').text();
	$('#txt_bodega_title2').text(nombre);
	$('#txt_cod_lugar').val(cod);
	if(cod!='.')
	{
		contenido_bodega2();
	}
}

function contenido_bodega2()
{
	var parametros = {
		'num_ped':'',//$('#txt_codigo').val(),
		'bodega':$('#txt_cod_lugar').val(),
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?contenido_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	$('#arbol_bodegas2 li span.label-success').removeClass('label-success');
	    	id = $('#txt_cod_lugar').val();
	    	// console.log(id);
	    	id = id.replaceAll('.','_');
	    	$('#contenido_bodega2').html(data);
	    	$('#c_'+id).addClass('label-success');	
	    	// productos_asignados();
	    }
	});
}

function lista_stock_ubicado()
{ 	
		var parametros = {
			'bodegas':$('#txt_bodega').val(),
			'cod_articulo':$('#txt_cod_barras').val(),
		}
	 	$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/reubicarC.php?lista_stock_ubicado=true',
		     data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	$('#tbl_asignados').html(data);
		    }
		}); 
}

function cambiar_bodegas(id)
{
	$('#myModal_cambiar_bodegas').modal('show');
	$('#txt_id_inv').val(id);
}


async function buscar_ruta()
{  

	 codigo = $('#txt_cod_lugar').val();
	 codigo = codigo.trim();
	 $('#txt_cod_lugar').val(codigo);
	 var parametros = {
			'codigo':codigo,
		}
		$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/almacenamiento_bodegaC.php?cargar_lugar=true',
		     data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	$('#txt_bodega_title').text('Ruta:'+data);
		    	// $('#txt_cod_bodega').val(codigo);

		    }
		});
}

function Guardar_bodega(id)
{
	 codigo = $('#txt_cod_lugar').val();
	 codigo = codigo.trim();
	 $('#txt_cod_lugar').val(codigo);
	 var parametros = {
			'codigo':codigo,
			'id':$('#txt_id_inv').val(),
		}
		$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/reubicarC.php?cambiar_bodega=true',
		     data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	$('#myModal_cambiar_bodegas').modal('hide');
		    	$('#txt_bodega_title2').text('Ruta:');
		    	$('#txt_cod_lugar').val('')
		    	$('#txt_id_inv').val('')
		    	lista_stock_ubicado();
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
  </div>
  
</div>
<div class="row">
	<div class="col-sm-12">		
		<div class="box">
			<form id="form_correos">
			<div class="box-body" style="background: antiquewhite;">					
				<div class="row">			
					<div class="col-sm-4">
						<b>Buscar Bodega</b>
						
						<div class="input-group input-group-sm">
							<input type="" name="" class="form-control input-xs" id="txt_bodega" name="txt_bodega" placeholder="Buscar Bodega" onblur="lista_stock_ubicado()">
							<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat" onclick="abrir_modal_bodegas()"><i class="fa fa-sitemap"></i></button>
							</span>
						</div>
					</div>
					<div class="col-sm-4">
						<b>Buscar Articulo</b>
						<input type="" name="" class="form-control input-xs" id="txt_cod_barras" name="txt_cod_barras" placeholder="Buscar" onblur="lista_stock_ubicado()">
					</div>
					<div class="col-sm-4 text-right">
						<br>
						<button type="button" class="btn btn-primary btn-sm" onclick="lista_stock_ubicado()"><i class="fa fa-search"></i> Buscar</button>
					</div>
				</div>
				<div class="row">
					<br>
						<div class="col-sm-12">
							<b>Contenido De bodega</b>
							<h3 class="box-title" id="txt_bodega_title">Ruta: </h3>
							<table class="table-sm table-hover table">
								<thead>
									<th>Codigo</th>
									<th><b>Producto</b></th>
									<th>Codigo bodega</th>
									<th><b>Ruta</b></th>
									<th></th>
								</thead>
								<tbody id="tbl_asignados">
									
								</tbody>
							</table>
						</div>					
				</div>
			</div>
			</form>
		</div>	
	</div>
</div>



<div id="myModal_cambiar_bodegas" class="modal fade myModalNuevoCliente" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Seleccion manual de bodegas</h4>
            </div>
            <div class="modal-body" id="contenido_prov" style="background: antiquewhite;">
        		<div class="row">
        			<div class="col-sm-12">
        				<div class="input-group input-group-sm">
							<input type="hidden" name="txt_id_inv" id="txt_id_inv">
        					<input type="text" id="txt_cod_lugar" name="txt_cod_lugar" class="form-control input-sm" placeholder="Nueva ruta" onblur="buscar_ruta()">        				
							<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat" onclick="abrir_modal_bodegas(2)"><i class="fa fa-sitemap"></i></button>
							</span>
						</div>						
        				<label id="txt_bodega_title2">Ruta:</label>

        			</div>
        		</div>             
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="Guardar_bodega()">Guardar</button>
            </div> 
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
                <button type="button" class="btn btn-default" onclick="cargar_bodegas();lista_stock_ubicado();" data-dismiss="modal">Cerrar</button>
            </div> 
        </div>
    </div>
  </div>

  <div id="myModal_arbol_bodegas2" class="modal fade myModalNuevoCliente" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Seleccion manual de bodegas</h4>
            </div>
            <div class="modal-body" id="contenido_prov" style="background: antiquewhite;">
            		<ul class="tree_bod" id="arbol_bodegas2">
								</ul>               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="cargar_bodegas();lista_stock_ubicado();" data-dismiss="modal">Cerrar</button>
            </div> 
        </div>
    </div>
  </div>

 <script src="../../dist/js/arbol_bodegas/arbol_bodega.js"></script>

