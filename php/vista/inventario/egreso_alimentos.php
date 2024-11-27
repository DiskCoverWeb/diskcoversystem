<?php date_default_timezone_set('America/Guayaquil'); ?> 
<script src="../../dist/js/qrCode.min.js"></script>
<script type="text/javascript">
	!function(e){var t=function(t,n){this.$element=e(t),this.type=this.$element.data("uploadtype")||(this.$element.find(".thumbnail").length>0?"image":"file"),this.$input=this.$element.find(":file");if(this.$input.length===0)return;this.name=this.$input.attr("name")||n.name,this.$hidden=this.$element.find('input[type=hidden][name="'+this.name+'"]'),this.$hidden.length===0&&(this.$hidden=e('<input type="hidden" />'),this.$element.prepend(this.$hidden)),this.$preview=this.$element.find(".fileupload-preview");var r=this.$preview.css("height");this.$preview.css("display")!="inline"&&r!="0px"&&r!="none"&&this.$preview.css("line-height",r),this.original={exists:this.$element.hasClass("fileupload-exists"),preview:this.$preview.html(),hiddenVal:this.$hidden.val()},this.$remove=this.$element.find('[data-dismiss="fileupload"]'),this.$element.find('[data-trigger="fileupload"]').on("click.fileupload",e.proxy(this.trigger,this)),this.listen()};t.prototype={listen:function(){this.$input.on("change.fileupload",e.proxy(this.change,this)),e(this.$input[0].form).on("reset.fileupload",e.proxy(this.reset,this)),this.$remove&&this.$remove.on("click.fileupload",e.proxy(this.clear,this))},change:function(e,t){if(t==="clear")return;var n=e.target.files!==undefined?e.target.files[0]:e.target.value?{name:e.target.value.replace(/^.+\\/,"")}:null;if(!n){this.clear();return}this.$hidden.val(""),this.$hidden.attr("name",""),this.$input.attr("name",this.name);if(this.type==="image"&&this.$preview.length>0&&(typeof n.type!="undefined"?n.type.match("image.*"):n.name.match(/\.(gif|png|jpe?g)$/i))&&typeof FileReader!="undefined"){var r=new FileReader,i=this.$preview,s=this.$element;r.onload=function(e){i.html('<img src="'+e.target.result+'" '+(i.css("max-height")!="none"?'style="max-height: '+i.css("max-height")+';"':"")+" />"),s.addClass("fileupload-exists").removeClass("fileupload-new")},r.readAsDataURL(n)}else this.$preview.text(n.name),this.$element.addClass("fileupload-exists").removeClass("fileupload-new")},clear:function(e){this.$hidden.val(""),this.$hidden.attr("name",this.name),this.$input.attr("name","");if(navigator.userAgent.match(/msie/i)){var t=this.$input.clone(!0);this.$input.after(t),this.$input.remove(),this.$input=t}else this.$input.val("");this.$preview.html(""),this.$element.addClass("fileupload-new").removeClass("fileupload-exists"),e&&(this.$input.trigger("change",["clear"]),e.preventDefault())},reset:function(e){this.clear(),this.$hidden.val(this.original.hiddenVal),this.$preview.html(this.original.preview),this.original.exists?this.$element.addClass("fileupload-exists").removeClass("fileupload-new"):this.$element.addClass("fileupload-new").removeClass("fileupload-exists")},trigger:function(e){this.$input.trigger("click"),e.preventDefault()}},e.fn.fileupload=function(n){return this.each(function(){var r=e(this),i=r.data("fileupload");i||r.data("fileupload",i=new t(this,n)),typeof n=="string"&&i[n]()})},e.fn.fileupload.Constructor=t,e(document).on("click.fileupload.data-api",'[data-provides="fileupload"]',function(t){var n=e(this);if(n.data("fileupload"))return;n.fileupload(n.data());var r=e(t.target).closest('[data-dismiss="fileupload"],[data-trigger="fileupload"]');r.length>0&&(r.trigger("click.fileupload"),t.preventDefault())})}(window.jQuery)
</script>
<style type="text/css">


.clearfix{*zoom:1;}.clearfix:before,.clearfix:after{display:table;content:"";line-height:0;}
.clearfix:after{clear:both;}
.hide-text{font:0/0 a;color:transparent;text-shadow:none;background-color:transparent;border:0;}
.input-block-level{display:block;width:100%;min-height:30px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
.btn-file{overflow:hidden;position:relative;vertical-align:middle;}.btn-file>input{position:absolute;top:0;right:0;margin:0;opacity:0;filter:alpha(opacity=0);transform:translate(-300px, 0) scale(4);font-size:23px;direction:ltr;cursor:pointer;}
.fileupload{margin-bottom:9px;}.fileupload .uneditable-input{display:inline-block;margin-bottom:0px;vertical-align:middle;cursor:text;}
.fileupload .thumbnail{overflow:hidden;display:inline-block;margin-bottom:5px;vertical-align:middle;text-align:center;}.fileupload .thumbnail>img{display:inline-block;vertical-align:middle;max-height:100%;}
.fileupload .btn{vertical-align:middle;}
.fileupload-exists .fileupload-new,.fileupload-new .fileupload-exists{display:none;}
.fileupload-inline .fileupload-controls{display:inline;}
.fileupload-new .input-append .btn-file{-webkit-border-radius:0 3px 3px 0;-moz-border-radius:0 3px 3px 0;border-radius:0 3px 3px 0;}
.thumbnail-borderless .thumbnail{border:none;padding:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;}
.fileupload-new.thumbnail-borderless .thumbnail{border:1px solid #ddd;}
.control-group.warning .fileupload .uneditable-input{color:#a47e3c;border-color:#a47e3c;}
.control-group.warning .fileupload .fileupload-preview{color:#a47e3c;}
.control-group.warning .fileupload .thumbnail{border-color:#a47e3c;}
.control-group.error .fileupload .uneditable-input{color:#b94a48;border-color:#b94a48;}
.control-group.error .fileupload .fileupload-preview{color:#b94a48;}
.control-group.error .fileupload .thumbnail{border-color:#b94a48;}
.control-group.success .fileupload .uneditable-input{color:#468847;border-color:#468847;}
.control-group.success .fileupload .fileupload-preview{color:#468847;}
.control-group.success .fileupload .thumbnail{border-color:#468847;}

</style>
<script type="text/javascript">
	var video;
	var canvasElement;
	var canvas;
	var scanning = false;
  $(document).ready(function () {
	video = document.createElement("video");
    canvasElement = document.getElementById("qr-canvas");
    canvas = canvasElement.getContext("2d", { willReadFrequently: true });
  	validar_ingreso();
  	areas();  
  	motivo_egreso()	
  	lista_egreso();
  	notificaciones();


  })

  function notificaciones()
  {
  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?listar_notificaciones=true',
		      // data:datos,
	        dataType:'json',
		    success: function(data)
		    {		    	    	
		    	if(data.length>0)
		    	{
		    		var mensajes = '';
		    		var cantidad  = 0;
		    		 $('#pnl_notificacion').css('display','block');
		    		 data.forEach(function(item,i){
		    		 	mensajes+='<li>'+
											'<a href="#" data-toggle="modal" onclick="mostrar_notificacion(\''+item.Texto_Memo+'\',\''+item.ID+'\',\''+item.Pedido+'\')">'+
												'<h4 style="margin:0px">'+
													item.Asunto+
													'<small>'+formatoDate(item.Fecha.date)+' <i class="fa fa-calendar-o"></i></small>'+
												'</h4>'+
												'<p>'+item.Texto_Memo.substring(0,15)+'...</p>'+
											'</a>'+
										'</li>';
										cantidad = cantidad+1;
		    		 })

		    		 $('#pnl_mensajes').html(mensajes);
		    		 $('#cant_mensajes').text(cantidad);
		    	}else
		    	{

		    		 $('#pnl_notificacion').css('display','none');
		    	}
		    	console.log(data);
		    }
		});  	

  }

  function mostrar_notificacion(text,id,pedido)
  {

  	cargar_notificacion(id);
  	$('#myModal_notificar').modal('show');
  	$('#txt_mensaje').html(text);  	
  	$('#txt_id_noti').val(id); 	
  	$('#txt_cod_pedido').val(pedido);
  }

  function cambiar_estado()
  {
  	respuesta = $('#txt_respuesta').val();
  	if(respuesta=='' || respuesta=='.')
  	{
  		 Swal.fire("Ingrese una respuesta","",'info');
  		 return false;
  	}
  	parametros = 
  	{
  		'noti':$("#txt_id_noti").val(),
  		'respuesta':respuesta,
  		'pedido':$('#txt_cod_pedido').val(),
  	}
  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?cambiar_estado=true',
		      data:{parametros:parametros},
	        dataType:'json',
		    success: function(data)
		    {		    
		    	$('#myModal_notificar').modal('hide');
		    	$('#txt_respuesta').val('');
		    	notificaciones();
		    }
		});  	

  }

  function solucionado()
  {
   
    parametros = 
    {
      'noti':$("#txt_id_noti").val(),
    }
    $.ajax({
        type: "POST",
          url:   '../controlador/inventario/alimentos_recibidosC.php?cambiar_estado_solucionado=true',
          data:{parametros:parametros},
          dataType:'json',
        success: function(data)
        {       
          $('#myModal_notificar').modal('hide');
          notificaciones();
        }
    });   

  }


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
	function area_egreso_modal(){
		
	 	$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/egreso_alimentosC.php?areas=true',          
		     // data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	var option = '';
		    	 data.forEach(function(item,i){
	         	img = 'simple';
		    	 	if(item.data.Picture!='.'){	 		img = item.data.Picture; 	 	}
	          option+= '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">'+
	                      '<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/'+img+'.png" onclick="cambiar_area(\''+item.id+'\',\''+item.text+'\')"  style="width: 60px;height: 60px;"></button><br>'+
	                      '<b style="white-space: nowrap;">'+item.text+'</b>'+
	                    '</div>';
	        })
	        // $('#txt_paquetes').html(op); 
	        $('#pnl_opciones').html(option);      
		    }
		});
	}

	function cambiar_area(id,text)
	{
		$('#ddl_areas').append($('<option>',{value:  id, text: text,selected: true }));
		$('#myModal_opciones').modal('hide');
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

	function motivo_egreso_modal(){
		
	 	$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/egreso_alimentosC.php?motivos=true',          
		     // data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	 var option = '';
		    	 data.forEach(function(item,i){
	         	img = 'simple';
		    	 	if(item.data.Picture!='.'){	 		img = item.data.Picture; 	 	}
	          option+= '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">'+
	                      '<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/'+img+'.png" onclick="cambiar_motivo(\''+item.id+'\',\''+item.text+'\')"  style="width: 60px;height: 60px;"></button><br>'+
	                      '<b>'+item.text+'</b>'+
	                    '</div>';
	        })
	        // $('#txt_paquetes').html(op); 
	        $('#pnl_opciones').html(option);       	 
		    }
		});
	}

	function cambiar_motivo(id,text)
	{
		$('#ddl_motivo').append($('<option>',{value:  id, text: text,selected: true }));
		$('#myModal_opciones').modal('hide');
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

	function productoPorQR(codigo){
		$('#txt_cod_producto').val(codigo).trigger('blur');
	}

	function add_egreso()
	{
		var stock = $('#txt_stock').val(); 
		var cant = $('#txt_cantidad').val(); 
		console.log(cant);
		console.log(stock);
		if(parseFloat(stock) < parseFloat(cant))
		{
			Swal.fire("La catidad supera al Stock","","info")
			return false;
		}else if(parseFloat(cant)<=0  || cant=='' )
		{
			Swal.fire("La catidad invalida","","info")
			return false;
		}
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
		var archivo = $('#file_doc')[0].files[0];
    
	    // Verificar si se seleccionó un archivo
	    if (!archivo) {

	    	Swal.fire("Seleccione un archivo","","info");
	      // alert('Por favor, seleccione un archivo.');
	      return false;
	    }
	    if($('#txt_detalle').val()=='')
	    {
	    	Swal.fire("","Ingrese un detalle de egreso","info");
	    	return false;
	    }
	    if($('#ddl_areas').val()=='')
	    {
	    	Swal.fire("","Seleccione un Area de egreso","info");
	    	return false;
	    }
	    if($('#ddl_motivo').val()=='')
	    {
	    	Swal.fire("","Seleccione un motivo de egreso","info");
	    	return false;
	    }

	    // Crear un objeto FormData
	    var formData = new FormData();
	    formData.append('archivo', archivo);
        
		console.log(formData);
	 	$.ajax({
		    type: "POST",
	       	url:   '../controlador/inventario/egreso_alimentosC.php?guardar_egreso=true',
		    // data:{parametros:parametros},
		    type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
		    success: function(data)
		    {
		    	if(data==1)
		    	{
		    		Swal.fire('Guardado','','success').then(function(){
		    			location.reload();
		    		})
		    	}else if(data==-2)
		    	{
		    		Swal.fire('formato de evidencia incorrecto','asegurese de que sea una imagen','error');
		    	}
		    }
		});
	}

	function abrir_modal(op)
	{
		if(op=='M')
		{
			$('#lbl_titulo_modal').text('MOTIVO DE EGRESO');
		 	motivo_egreso_modal()
		}else
		{
			$('#lbl_titulo_modal').text('AREA DE EGRESO');
		 	area_egreso_modal();
		}
		$('#myModal_opciones').modal('show');
	}

	function lista_egreso_checking()
	{		
		var parametros = {
			'desde':$('#txt_desde').val(),
			'hasta':$('#txt_hasta').val()
		}
	 	$.ajax({
		    type: "POST",
	       	url:   '../controlador/inventario/egreso_alimentosC.php?lista_egreso_checking_reportados=true',
		    data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	$('#tbl_asignados_check').html(data);	
		    }
		});
	}  	

	function myModal_historial()
	{
		lista_egreso_checking();
		$('#myModal_historial').modal('show');
	}

	function escanear_qr(){
		//$('#qrescaner_carga').show();
		$('#modal_qr_escaner').modal('show');
		navigator.mediaDevices
		.getUserMedia({ video: { facingMode: "environment" } })
		.then(function (stream) {
			$('#qrescaner_carga').hide();
			scanning = true;
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
			productoPorQR(respuesta);
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
		<!-- <div class="col-xs-2 col-md-2 col-sm-2">
			<button class="btn btn-default" title="" onclick="">
				<img src="../../img/png/mostrar.png">
			</button>
		</div>    	 -->
		<div class="col-xs-2 col-md-2 col-sm-2">
			<button class="btn btn-default" title="Historial" onclick="myModal_historial()">
				<img src="../../img/png/file_crono.png" style="width:32px;height:32px">
			</button>
		</div>
		<!--<div class="col-xs-2 col-md-2 col-sm-2">
          <button class="btn btn-default" title="Escanear QR" onclick="escanear_qr()">
            <img src="../../img/png/escanear_qr.png">
          </button>
        </div>-->
		<div class="col-xs-2 col-md-2 col-sm-2" style="display:none;" id="pnl_notificacion">
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">

						<li class="dropdown messages-menu">
							<button class="btn btn-danger dropdown-toggle" title="Guardar"  data-toggle="dropdown" aria-expanded="false">
									<img src="../../img/png/alarma.png" style="width:32px;height: 32px;">
							</button>  	
							<ul class="dropdown-menu">
								<li class="header">tienes <b id="cant_mensajes">0</b> mensajes</li>
								<li>
									<ul class="menu" id="pnl_mensajes">
										
									</ul>
								</li>
							</ul>
						</li>
					</ul>
			</div>
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
								<button type="button" class="btn btn-default btn-sm" onclick="abrir_modal('A')">
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
								<button type="button" class="btn btn-default btn-sm" onclick="abrir_modal('M')">
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
							<textarea id="txt_detalle" name="txt_detalle"  class="form-control input-xs"></textarea>
	          </div>
					</div>
					<div class="col-sm-3">						
						<form enctype="multipart/form-data" id="form_img" method="post" style="width: inherit;">
							 <div class="fileupload fileupload-new" data-provides="fileupload">
							    <span class="btn btn-default btn-file">
							    <img src="../../img/png/clip.png" style="width: 60px;height: 60px;">
							    <span class="fileupload-new">Archivo Adjunto</span>
							    <span class="fileupload-exists">Archivo Adjunto</span> 
							    	<input type="file" id="file_doc" name="file_doc" />
							    </span> <br>
							    <span class="fileupload-preview"></span>
							    <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
							  </div>
						</form>
					</div>					
				</div>
				<div class="row">
					<div class="col-sm-3">
						<b>Codigo productos</b>
						<div class="input-group input-group-sm">
							<input type="" class="form-control input-sm" id="txt_cod_producto" style="font-size: 17px;" name="txt_cod_producto" onblur="buscar_producto()">			
							<span class="input-group-btn">
								<button type="button" class="btn btn-primary btn-flat btn-sm" title="Escanear QR" onclick="escanear_qr()">
									<i class="fa fa-qrcode" aria-hidden="true"></i> Escanear QR
								</button>
							</span>    
						</div>
						
						<input type="hidden" id="txt_id" name="">								
					</div>	
					<div class="col-sm-3">
						<b>Proveedor / Donante</b>
						<input type="" class="form-control input-sm" id="txt_donante" name="txt_donante" readonly>	
								
					</div>														
					<div class="col-sm-3">
						<b>Grupo de producto</b>
						<input type="" class="form-control input-sm" id="txt_grupo" name="txt_grupo" readonly>	
								
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


<!-- Modal -->
<div id="myModal_opciones" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="lbl_titulo_modal"></h4>
      </div>
      <div class="modal-body">
        	<div class="row text-center" id="pnl_opciones">
          </div> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
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

  <div id="myModal_historial" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-lg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="lbl_titulo_modal"></h4>
      </div>
      <div class="modal-body">
      		<div class="row">
	      		<div class="col-sm-3">
	      			<b>Desde</b>
	        		<input type="date" name="txt_desde" id="txt_desde" value="<?php echo date('Y-m-d'); ?>">
	        	</div>	
	        	<div class="col-sm-3">
	        		<b>Hasta</b>
        			<input type="date" name="txt_hasta" id="txt_hasta" value="<?php echo date('Y-m-d'); ?>">
        		</div>
        		<div class="col-sm-6 text-right">
        			<br>
        			<button class="btn btn-primary btn-sm" onclick="lista_egreso_checking();">Buscar</button>
        		</div>		
      		</div>
        	<div class="row">
        		
						<div class="col-sm-12">
							<div class="table-responsive">
								<table class="table-sm table-hover table">
									<thead>
										<th><b>Item</b></th>
										<th><b>Fecha de Egreso</b></th>
										<th><b>Usuario</b></th>
										<th><b>Area Egreso</b></th>
										<th><b>Detalle Egreso</b></th>
										<th><b>Motivo</b></th>
										<th><b>Observacion</b></th>
										<th></th>
									</thead>
									<tbody id="tbl_asignados_check">
										
									</tbody>
								</table>
							</div>	
						</div>
					</div>
				
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

 <script src="../../dist/js/arbol_bodegas/arbol_bodega.js"></script>
