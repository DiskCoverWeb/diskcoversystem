<?php date_default_timezone_set('America/Guayaquil'); ?>
  <link rel="stylesheet" href="../../dist/css/arbol_bodegas/reset.min.css">
  <link rel="stylesheet" href="../../dist/css/arbol_bodegas/arbol_bodega.css">
  <script src="../../dist/js/arbol_bodegas/prefixfree.min.js"></script>
  <script src="../../dist/js/qrCode.min.js"></script>
<script type="text/javascript">
	var video;
	var canvasElement;
	var canvas;
	var scanning = false;
	var campo_qr = '';
  $(document).ready(function () {
	video = document.createElement("video");
	canvasElement = document.getElementById("qr-canvas");
	canvas = canvasElement.getContext("2d", { willReadFrequently: true });
  	cargar_bodegas()
  	cargar_paquetes()
  	pedidos();

  	 $('#txt_cod_lugar').keydown( function(e) { 
          var keyCode1 = e.keyCode || e.which; 
          if (keyCode1 == 13) { 
          	codigo = $('#txt_cod_lugar').val();
          	codigo = codigo.trim();
          	$('#txt_cod_lugar').val(codigo);
          	buscar_ruta();
          }
      });  
  


  
    $('#txt_codigo').on('select2:select', function (e) {
      var data = e.params.data.data;
	  setearCamposPedidos(data);
    });


  })

  function setearCamposPedidos(data){
	console.log(data);

    	$('#txt_id').val(data.ID); 
      $('#txt_fecha_exp').val(formatoDate(data.Fecha_Exp.date));
      $('#txt_fecha').val(formatoDate(data.Fecha.date));
      $('#txt_donante').val(data.Cliente);
      $('#txt_paquetes').val(data.Tipo_Empaque);

      var cantidad = parseFloat(data.Entrada).toFixed(2)
      $('#txt_cant').val(cantidad); // save selected id to input
      if(cantidad>=500)
      {
      	 $('#btn_alto_stock').css('display','initial');
      	 $('#txt_cant').css('color','green');
      	 $('#img_alto_stock').attr('src','../../img/gif/alto_stock_titi.gif');
      }else
      {

      	 $('#btn_alto_stock').css('display','none');
      	 $('#txt_cant').css('color','#000000');
      	 $('#img_alto_stock').attr('src','../../img/png/alto_stock.png');
      }

			var fecha1 = new Date();
      var fecha2 = new Date(formatoDate(data.Fecha_Exp.date));
			var diferenciaEnMilisegundos = fecha2 - fecha1;
			var diferenciaEnDias = ((diferenciaEnMilisegundos/ 1000)/86400);
			diferenciaEnDias = parseInt(diferenciaEnDias);

			console.log(diferenciaEnDias);
			if(diferenciaEnDias<0)
      {
      	 $('#btn_expired').css('display','initial');
      	 $('#txt_fecha_exp').css('color','red');
      	 $('#img_por_expirar').attr('src','../../img/gif/expired_titi2.gif');
      	 $('#btn_titulo').text('Expirado')
      	 $('#txt_fecha_exp').css('background','#ffff');
      }else if(diferenciaEnDias<=10 && diferenciaEnDias>0){
      	 $('#btn_expired').css('display','initial');
      	 $('#txt_fecha_exp').css('color','yellow');
      	 $('#img_por_expirar').attr('src','../../img/gif/expired_titi.gif');
      	 $('#btn_titulo').text('Por Expirar')
      	 $('#txt_fecha_exp').css('background','#a6a5a5');
      }else
      {
      	 $('#btn_expired').css('display','none');
      	 $('#txt_fecha_exp').css('color','#000000');
      	 $('#img_por_expirar').attr('src','../../img/png/expired.png');
      	 $('#txt_fecha_exp').css('background','#ffff');
      }
      
  	lineas_pedidos();
  }

  function pedidosPorQR(codigo){
		$.ajax({
			url:   '../controlador/inventario/almacenamiento_bodegaC.php?search_contabilizado=true&q='+codigo,          
			method: 'GET',
			dataType: 'json',
			success: (data) => {
				console.log(data);
        if(data.length > 0){
          let datos = data[0];
          // Crear una nueva opción con los 3 parámetros y asignarla al select2
          const nuevaOpcion = new Option('<div style="background:'+datos.fondo+'"><span style="color:'+datos.texto+';font-weight: bold;">' + datos.text + '</span></div>', datos.id, true, true);
  
          // Agregar el atributo `data` a la opción
          //$(nuevaOpcion).data('data', datos.data);
  
          // Añadir y seleccionar la nueva opción
          $('#txt_codigo').append(nuevaOpcion).trigger('change');//'select2:select'
          setearCamposPedidos(datos.data);
        }else{
          Swal.fire('No se encontró información para el codigo: '+codigo, '', 'error');
        }
			}
		});
	}

	function lugarPorQr(codigo){
		$('#txt_cod_lugar').val(codigo.trim());
		$('#txt_cod_lugar').trigger('blur');
	}

  function cargar_nombre_bodega(nombre,cod)
  {

  	$('#txt_bodega_title').text();
  	$('#txt_bodega_title').text(nombre);
  	$('#txt_cod_bodega').val(cod);
  	$('#txt_cod_lugar').val(cod);
  	if(cod!='.')
  	{
  		contenido_bodega();
  	}

  	// console.log(nombre)
  }

  function pedidos(){
 
  	$('#txt_codigo').select2({
    placeholder: 'Seleccione una beneficiario',
    ajax: {
      url: '../controlador/inventario/almacenamiento_bodegaC.php?search_contabilizado=true',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results: data.map(function (item) {
            return {
              id: item.id,
              text: '<div style="background:'+item.fondo+'"><span style="color:'+item.texto+';font-weight: bold;">' + item.text + '</span></div>',
              data : item.data,
            };
          })
        };
      },
      cache: true
    },
    escapeMarkup: function (markup) {
      return markup;
    }
  });
}

function lineas_pedidos()
{
	var parametros = {
		'num_ped':$('#txt_codigo').val(),
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?lineas_pedido=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	$('#lista_pedido').html(data);
	    }
	});

  
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
function cargar_paquetes()
{
	
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?cargar_empaques=true',
	     // data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	var op = '<option value="">Seleccione empaque</option>';
	    	data.forEach(function(item,i){
	    		 op+='<option value="'+item.ID+'">'+item.Proceso+'</option>';
	    	})

	    	$('#txt_paquetes').html(op);	    	
	    }
	});

  
}

function asignar_bodega()
{

	 id = '';
	 $('.rbl_pedido').each(function() {
	    const checkbox = $(this);
	    const isChecked = checkbox.prop('checked'); 
	    if (isChecked) {
	        id+= checkbox.val()+',';
	    }
	});

	 bodega = $('#txt_cod_bodega').val();
	 paquete = $('#txt_paquetes').val();

	if(bodega=='.' || bodega =='')
	{
		Swal.fire('Seleccione una bodega','','info');
		return false;
	}

	// if(paquete=='.' || paquete =='')
	// {
	// 	Swal.fire('Seleccione Paquete','','info');
	// 	return false;
	// }
	if(id=='')
	{
		Swal.fire('Seleccione un pedido','','info');
		return false;
	}
	// $('#myModal_espera').modal('show');

	var parametros = {
		'id':id,
		'bodegas':bodega,
	}
	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?asignar_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {

				$('#myModal_espera').modal('hide');
				Swal.fire('Asignado a bodega','','success');
	    	lineas_pedidos()   	
	    	contenido_bodega();
	    	productos_asignados();
	    }
	});
	
}

function desasignar_bodega()
{
	 id = '';
	 $('.rbl_pedido_des').each(function() {
	    const checkbox = $(this);
	    const isChecked = checkbox.prop('checked'); 
	    if (isChecked) {
	        id+= checkbox.val()+',';
	    }
	});

	 bodega = $('#txt_cod_bodega').val();

	if(bodega=='.' || bodega =='')
	{
		Swal.fire('Seleccione una bodega','','info');
		return false;
	}
	if(id=='')
	{
		Swal.fire('Seleccione un pedido','','info');
		return false;
	}

	var parametros = {
		'id':id,
		'bodegas':bodega,
	}
	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?desasignar_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	lineas_pedidos()   	
	    	contenido_bodega();	    	
	    	productos_asignados();
	    }
	});
	
}

function contenido_bodega()
{
	var parametros = {
		'num_ped':$('#txt_codigo').val(),
		'bodega':$('#txt_cod_bodega').val(),
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?contenido_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	$('#arbol_bodegas li span.label-success').removeClass('label-success');
	    	id = $('#txt_cod_bodega').val();
	    	id = id.replaceAll('.','_');
	    	$('#contenido_bodega').html(data);
	    	$('#c_'+id).addClass('label-success');	
	    	productos_asignados();
	    }
	});

}

function productos_asignados()
{
	var parametros = {
		'bodegas':$('#txt_cod_lugar').val(),
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?productos_asignados=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	$('#tbl_asignados').html(data);
	    }
	});

}

function  eliminar_bodega(id)
{
	var parametros = {
		'id':id,
	}
	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?eliminar_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	lineas_pedidos()   	 	
	    	productos_asignados();
	    	$('#contenido_bodega').html('');
	    	$('#txt_cod_bodega').val('.');
	    	$('#txt_bodega_title').text('Ruta: ');
	    }
	});

}

function cargar_info(codigo)
{
	var parametros = {
		'codigo':codigo,
	}
	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?cargar_info=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	$('#pnl_contenido').html(data)
	    }
	});

}

function abrir_modal_bodegas()
{
	 $('#myModal_arbol_bodegas').modal('show');
}

async function buscar_ruta()
{  
	// if($('#txt_cod_bodega').val()!='' && $('#txt_cod_bodega').val()!='.' ){cargar_bodegas();}

	 codigo = $('#txt_cod_lugar').val();
	 codigo = codigo.trim();
	  $('#txt_cod_lugar').val(codigo);
	 // pasos = codigo.split('.');	 
	 // let ruta = '';
	 // let bodega = '';
	 // for (var i=0 ; i <= pasos.length ; i++) {
	 // 		bodega+=pasos[i]+'_';
	// 		let pasos2 = bodega.substring(0 ,bodega.length-1);
	// 		$('#c'+pasos2).prop('checked',false);
   //  	$('#c_'+pasos2).click();
	// 		await sleep(3000);
	// 		console.log('espera');
	 // }
	// await pasos.forEach(function(item,i){
	// 		bodega+=item+'_';
	// 		let pasos2 = bodega.substring(0 ,bodega.length-1);
  //   	$('#c_'+pasos2).click();
	// 		await sleep(7000);
	// 		console.log('espera');
	//  })
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
		    	$('#txt_cod_bodega').val(codigo);
		    	$('#txt_cod_lugar').val(codigo);
		    	productos_asignados();
		    }
		});
}

function escanear_qr(campo){
	/*if(campo == 'lugar' && $('#txt_codigo').val() == ''){
		Swal.fire('Seleccione un codigo de ingreso', '', 'error');
		return;
	}*/
	$('#modal_qr_escaner').modal('show');
	navigator.mediaDevices
	.getUserMedia({ video: { facingMode: "environment" } })
	.then(function (stream) {
	$('#qrescaner_carga').hide();
		scanning = true;
		campo_qr = campo;
		//document.getElementById("btn-scan-qr").hidden = true;
		canvasElement.hidden = false;
		video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
		video.srcObject = stream;
		video.play();
		tick();
		scan();
	});
}

//funciones para levantar las funiones de encendido de la camara
function tick() {
	canvasElement.height = video.videoHeight;
	canvasElement.width = video.videoWidth;
	//canvasElement.width = canvasElement.height + (video.videoWidth - video.videoHeight);
	canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

	scanning && requestAnimationFrame(tick);
}

function scan() {
	try {
		qrcode.decode();
	} catch (e) {
		setTimeout(scan, 300);
	}
}

const cerrarCamara = () => {
	video.srcObject.getTracks().forEach((track) => {
		track.stop();
	});
	canvasElement.hidden = true;
$('#qrescaner_carga').show();
	$('#modal_qr_escaner').modal('hide');
};

//callback cuando termina de leer el codigo QR
qrcode.callback = (respuesta) => {
	if (respuesta) {
		//console.log(respuesta);
		//Swal.fire(respuesta)

		if(campo_qr == 'ingreso'){
			pedidosPorQR(respuesta);
		}else if(campo_qr == 'lugar'){
			lugarPorQr(respuesta);
		}
		//activarSonido();
		//encenderCamara();    
		cerrarCamara();    
	}
};
  
 
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
					<div class="col-sm-2">					
							<b>Fecha de Ingreso:</b>
							<input type="hidden" name="txt_id" id="txt_id">
		          <input type="date" class="form-control input-xs" id="txt_fecha" name="txt_fecha" readonly>		
		      </div>						
					<div class="col-sm-3">
			       	<b>Codigo de Ingreso:</b>
					<input type="hidden" class="form-control input-xs" id="txt_codigo_p" name="txt_codigo_p" readonly>
					<div class="input-group">
						<select class="form-control input-xs" id="txt_codigo" name="txt_codigo">
							<option value="">Seleccione</option>
						</select>
						<span class="input-group-btn">
							<button type="button" class="btn btn-primary btn-flat btn-xs" title="Escanear QR" onclick="escanear_qr('ingreso')">
								<i class="fa fa-qrcode" aria-hidden="true"></i>
							</button>
						</span>    
					</div>
			    </div>
					<div class="col-sm-3">
	            <b>PROVEEDOR / DONANTE</b>								
								<input type="" class="form-control input-xs" id="txt_donante" name="txt_donante" readonly>
					</div>
					<div class="col-sm-2 text-right">
						 	<b>CANTIDAD:</b>
             	<input type="" class="form-control input-xs" id="txt_cant" name="txt_cant" readonly>	
					</div>
					<div class="col-sm-2 text-right">
						 	<b>FECHA EXPIRACION:</b>
             	<input type="date" class="form-control input-xs" id="txt_fecha_exp" name="txt_fecha_exp" readonly>	
					</div>
					<!-- <div class="col-sm-12 text-right">
						<div class="row">
							<div class="col-sm-10"></div>
								<div class="col-sm-2">
										<b>ALIMENTO RECIBIDO:</b>
										<select class=" form-control input-xs form-select" id="ddl_alimento" name="ddl_alimento" disabled>
		               		<option value="">Seleccione Alimento</option>
		               	</select>										
								</div>
						</div>								
						
					</div> -->
				</div>
				<div class="row">
					<div class="col-sm-5">
						<b>Tipo de Empaque</b>
						<select class="form-control input-xs" id="txt_paquetes" name="txt_paquetes" disabled>
							<option value="">Seleccione Empaque</option>
						</select>
					</div>
					<div class="col-sm-7 text-right" id="pnl_alertas">
						<button class="btn btn-default" type="button" id="btn_alto_stock" style="display:none;">
							<img id="img_alto_stock"  src="../../img/gif/alto_stock_titi.gif" style="width:48px">
							<br>
							Alto Stock
						</button>
						<button class="btn btn-default" type="button" id="btn_expired" style="display:none;">
							<b id="btn_titulo">Por Expirar</b><br>
							<img id="img_por_expirar" src="../../img/gif/expired_titi.gif" style="width:48px">
							
						</button>
					</div>
				</div>
				<hr>
				<div class="row">					
					<div class="col-sm-12">
						<div class="row">							
							<div class="col-md-5">

								<div class="box box-primary direct-chat direct-chat-primary">	
										<div class="box-header">
													<h3 class="box-title">Articulos de pedido</h3>
										</div>									
										<div class="box-body">
												<div class="direct-chat-messages">	
													<ul class="list-group list-group-flush" id="lista_pedido"></ul>											
												</div>
										</div>
								</div>
						</div>
							
							<div class="col-sm-7">
								<!-- <br>
								<button class="btn btn-primary" type="button" onclick="desasignar_bodega()"><i class="fa fa-arrow-left"></i></button>	
 -->
								<div class="row">
									<div class="col-sm-9">
										<b>Codigo de lugar</b>
										<div class="input-group input-group-sm">
												<input type="" class="form-control input-xs" id="txt_cod_lugar" style="font-size: 20px;" name="txt_cod_lugar" onblur="buscar_ruta();productos_asignados()">	
												<span class="input-group-btn">
														<button type="button" class="btn btn-info btn-flat" onclick="abrir_modal_bodegas()"><i class="fa fa-sitemap"></i></button>
												</span>
												<span class="input-group-btn">
													<button type="button" class="btn btn-primary btn-flat btn-xs" title="Escanear QR" onclick="escanear_qr('lugar')">
														<i class="fa fa-qrcode" aria-hidden="true"></i>
													</button>
												</span>
										</div>
									</div>
									<div class="col-sm-3 text-right">
										<br>
											<button class="btn btn-primary btn-sm" type="button" onclick="asignar_bodega()"><i class="fa fa-map-marker"></i>  Asignar</button>											
									</div>
									
								</div>							


								<div class="box box-success">
										<div class="box-header">
											<h3 class="box-title" id="txt_bodega_title">Ruta: </h3>
											<input type="hidden" class="form-control input-xs" id="txt_cod_bodega" name="txt_cod_bodega" readonly>
										</div>
										<!-- <div class="box-body">
											<ul class="nav nav-pills nav-stacked" id="contenido_bodega"></ul>						
										</div> -->
									</div>	

								<div class="row">
										<div class="col-sm-12">
											Contenido de bodega
											<table class="table-sm table-hover table">
												<thead>
													<th><b>Producto</b></th>
													<th><b>Stock</b></th>
													<th><b>Ruta</b></th>
													<th></th>
												</thead>
												<tbody id="tbl_asignados">
													<tr>
														<td colspan="3">Productos asignados</td>
													</tr>
												</tbody>
											</table>
										</div>					
								</div>

							</div>
						</div>
						
						
					</div>
				</div>
				
			</div>
			</form>
		</div>	
	</div>
</div>

<div id="modal_qr_escaner" class="modal fade"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <button type="button" class="close" onclick="cerrarCamara()">&times;</button>
              <h4 class="modal-title">Escanear QR</h4>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
            <div id="qrescaner_carga">
              <div style="height: 100%;width: 100%;display:flex;justify-content:center;align-items:center;"><img src="../../img/gif/loader4.1.gif" width="20%"></div>
            </div>
		  	    <canvas hidden="" id="qr-canvas" class="img-fluid" style="height: 100%;width: 100%;"></canvas>
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-danger" onclick="cerrarCamara()">Cerrar</button>
          </div>
      </div>
  </div>
</div>

<div id="myModal_arbol_bodegas" class="modal fade myModalNuevoCliente" role="dialog"  data-keyboard="false" data-backdrop="static">
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
                <button type="button" class="btn btn-default" onclick="cargar_bodegas();" data-dismiss="modal">OK</button>
            </div> 
        </div>
    </div>
  </div>

 <script src="../../dist/js/arbol_bodegas/arbol_bodega.js"></script>
