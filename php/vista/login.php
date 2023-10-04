
<!DOCTYPE html>
<html>
<head>
    <title>DiskCover System login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
	  <!-- Font Awesome -->
	  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
	  <!-- Ionicons -->
	  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
	  <!-- Theme style -->
	  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
	  <!-- iCheck -->
	  <link rel="stylesheet" href="../../plugins/iCheck/square/blue.css">
	  <link rel="stylesheet" type="text/css" href="../../dist/css/estilologin.css">
	  <link rel="shortcut icon" href="../../img/jpg/logo.jpg" />
	  
	  <link rel="stylesheet" href="../../dist/css/sweetalert.css">
	  <script src="../../dist/js/sweetalert2.js"></script>
	  <link rel="stylesheet" href="../../dist/css/sweetalert2.min.css">
	  <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
	  <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	  <link href="https://cdn.jsdelivr.net/npm/simple-line-icons@2.4.1/css/simple-line-icons.css" rel="stylesheet" type="text/css" />
	  <script type="text/javascript">
	  $(document).ready(function(){

	  });

	function validar_entidad()
  { 
		 var entidad = $("#entidad").val();

     $.ajax({
      data:  {'entidad':entidad},
      url:   '../controlador/login_controller.php?Cartera_Entidad=true&pantalla='+screen.height,
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) { 
         if(response==-2)
         {
	         	$('#alerta').css('display','none');        	
	        	$('#res').val('');
	        	Swal.fire('Error Entidad!','"La entidad que ingresaste no tiene el formato correcto.','error');
         }
        if(response.length==1)
        {
        	$('#alerta').css('display','block');
        	$('#txt_item').val(response[0].Item);
        	if(response[0].Nombre == response[0].Razon_Social)
					{
        		$('#alerta').html(response[0].Nombre);
        	}else
        	{
        		$('#alerta').html(response[0].Razon_Social+'<br>'+response[0].Nombre);   
        		$('#alerta').css('font-size','10px');        		
        	}
        	$('#img_logo').attr('src',response[0].Logo);
        	$('#img_logo').css('width','35%');
        	$('#img_logo').css('border-radius','5px');
        	$('#res').val(response[0].entidad);
        	$('#txt_cartera').val('0');


        }else if(response.length>1)
        {
        	 $('#lbl_ruc').text(entidad);
        	 tr = '';
        	 response.forEach(function(item,i){
        	 	  tr+='<tr><td><img style="width:100%" src="'+item.Logo+'"></td><td><button class="btn btn-block btn-default" onclick="seleccionar_empresa(\''+item.Nombre+'\',\''+item.Razon_Social+'\',\''+item.Logo+'\',\''+item.entidad+'\',\''+item.Item+'\')">'+item.Razon_Social+'<br>'+item.Nombre+'</button></td></tr>';
        	 })

        	 	// console.log(item)
        	 $('#tbl_empresas').html(tr);
        	 $('#mis_empresas').modal('show');
        }else
        {
        		$('#alerta').css('display','none');    
        		$('#res').val('');    	
        		Swal.fire('Error Entidad!','No se a encontrado la entidad.','error');
        }
/*
        if(response.respuesta == 1)
        {
        	$('#alerta').css('display','block');
        	if(response.Nombre == response.Razon_Social)
					{
        		$('#alerta').html(response.Nombre);
        	}else
        	{
        		$('#alerta').html(response.Razon_Social+'<br>'+response.Nombre);   
        		$('#alerta').css('font-size','10px');        		
        	}
        	$('#img_logo').attr('src',response.Logo);
        	$('#img_logo').css('width','35%');
        	$('#img_logo').css('border-radius','5px');
        	$('#res').val(response.entidad);
        	$('#txt_cartera').val('0');
        
        }else if(response.respuesta==-1)
        {
        	$('#alerta').css('display','none');    
        	$('#res').val('');    	
        	Swal.fire('Error Entidad!','No se a encontrado la entidad.','error');

        }else
        { 
        	$('#alerta').css('display','none');        	
        	$('#res').val('');
        	Swal.fire('Error Entidad!','"La entidad que ingresaste no tiene el formato correcto.','error');
        }        */
      }
    });

  }


  function seleccionar_empresa(Nombre,Razon_Social,Logo,entidad,item)
  {
  	  $('#mis_empresas').modal('hide');

  	 	$('#txt_item').val(item);
  	 	$('#alerta').css('display','block');
	  	if(Nombre == Razon_Social)
			{
	  		$('#alerta').html(Nombre);
	  	}else
	  	{
	  		$('#alerta').html(Razon_Social+'<br>'+Nombre);   
	  		$('#alerta').css('font-size','10px');        		
	  	}
	  	$('#img_logo').attr('src',Logo);
	  	$('#img_logo').css('width','35%');
	  	$('#img_logo').css('border-radius','5px');
	  	$('#res').val(entidad);
	  	$('#txt_cartera').val('0');
	  	$('#correo').focus();
	  	var parametros = 
	  	{
	  		'empresa':Nombre,
	  		'item_cartera':item,
	  	}

	  	 $.ajax({
	      data:  {parametros:parametros},
	      url:   '../controlador/login_controller.php?setear_empresa=true',
	      type:  'post',
	      dataType: 'json',
	      /*beforeSend: function () {   
	           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
	         $('#tabla_').html(spiner);
	      },*/
	        success:  function (response) { 
	        
	      }
	    });




  }
   function validar_usuario()
  { 

		 var usuario = $("#correo").val();
		 var entidad = $("#res").val();		 
		 var item = $("#txt_item").val();

		 if(usuario =='')
		 {
		 	//Swal.fire('USUARIO','Ingrese un usuario valido','info')
		 	return false;
		 }
		 if(entidad=='')
		 {
		 	return false;
		 }
		 var parametros = 
		 {
		 	 'usuario':usuario,
		 	 'entidad':entidad,
		 	 'item':item,
		 }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/login_controller.php?Usuario=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) { 
        	// console.log(response);
        if(response.respuesta == -1)
        {
        	// Swal.fire({
         //     title: 'Este usuario no esta registrado como usuario del sistema!',
         //     text: "Desea revisar en cartera de clientes!",
         //     type: 'warning',
         //     showCancelButton: true,
         //     confirmButtonColor: '#3085d6',
         //     cancelButtonColor: '#d33',
         //     confirmButtonText: 'Si!'
         //   }).then((result) => {
         //     if (result.value==true) {
             	$('#txt_cartera').val('1');
             	$('#correo_cartera').val(response.cartera_usu);
							$('#contra_cartera').val(response.cartera_pass);
       //       }else
       //       {
       //       	$('#txt_cartera').val('0');
       //       	$('#correo_cartera').val('');
							// $('#contra_cartera').val('');
       //       }
       //     })

        	// Swal.fire('Este usuario no esta registrado O no pertenece a la entidad!','No se a encontrado al usuario.','info');
        }else if(response.respuesta==-2)
        {      	
        	$('#txt_cartera').val('0');
        	Swal.fire('Este usuario se encuentra bloqueado!','Usuario bloqueado.','error');
        }else if(response.respuesta==1)
        {
        	$('#txt_cartera').val('0');
        }     
      }
    });

  }

  function Ingresar_vali()
  {
  	if($('#txt_cartera').val()==1)
  	{
  		 Swal.fire({
           title: 'Esta seguro?',
           text: "Esta apunto de entrar a la cartera de clientes!",
           type: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Si!'
         }).then((result) => {
           if (result.value==true) {
            Ingresar();
           }
         })

  	}else
  	{
  		Ingresar();
  	}
  }
  

  function Ingresar()
  { 

		 var usuario = $("#correo").val();
		 var entidad = $("#res").val();
		 var item = $("#txt_item").val();
		 var pass = $("#contra").val();		 
		 var cartera = $("#txt_cartera").val();
		 var cartera_usu = $("#correo_cartera").val();
		 var cartera_pass = $("#contra_cartera").val();
		 var ci_empresa = $("#entidad").val();
		 if(entidad =='')
		 {
		 		Swal.fire('No se a verificado la entidad Asegurese de colocar una entidad valida','Se volvera a verificar la empresa','info').then(function(){ $('#entidad').focus()});
		 	return false;
		 }
		 if(pass=='' || entidad=='')
		 {
		 	Swal.fire('Llene todo los campos','Asegurese de colocar una entidad, usuario y password validos','info')
		 	return false;
		 }

		 var localIp;
		 var pc = new RTCPeerConnection();
      pc.createDataChannel('');
      pc.createOffer(function(sdp) {
          pc.setLocalDescription(sdp);
      }, function() {});
      pc.onicecandidate = function(event) {
        if (event.candidate) {
            var ipRegex = /(\d+\.\d+\.\d+\.\d+)/;
            let a = ipRegex.exec(event.candidate.candidate);
            if(a){
            	localIp = a[1];
            }
				}
			}

			

		 var parametros = 
		 {
		 	 'usuario':usuario,
		 	 'entidad':entidad,
		 	 'item':item,
		 	 'empresa':ci_empresa,
		 	 'pass':pass,
		 	 'cartera':cartera,
		 	 'cartera_usu':cartera_usu,
		 	 'cartera_pass':cartera_pass,
		 	 'localIp':localIp
		 }

     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/login_controller.php?Ingresar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) { 
        if(response==-1)
        {
        	Swal.fire('Clave o usuario invalidos!','No se pudo acceder.','error');
        }else if(response==-2)
        {        	
        	Swal.fire('Clave o usuario de cartera invalidos!','No se pudo acceder.','error');
        }
        else
        {     	
        	// console.log(response); return false;
        	window.location.href = response;
        }     
      }
    });

  }
  	
	  </script>
	
</head>
<body>
	<div id="Contenedor" style="background:rgba(201, 223, 241,0.4);">
		<div class="Icon">
			<img src="../../img/jpg/logo.jpg" class="img-circle" alt="User Image" style="width: 20%; height:20%;" id="img_logo">
		</div>
		<div class="ContentForm">
		   <form action="../controlador/login_controller.php" method="post" name="FormEntrar">
		   	<div id="alerta" class="alert alert-success visible" align="center" style="display:none;"></div>
		   	<input type="hidden" name="txt_cartera" id="txt_cartera" value="0">
		   	<div class="input-group input-group-lg" >
		   		<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-home"></i></span>
		   		<!-- 1792164710001 -->
		   		<input type="text" class="form-control" name="entidad" placeholder="Entidad a la que perteneces" id="entidad" onblur="validar_entidad()">
		   		<input type="hidden" name="res" id="res">
		   		<input type="hidden" name="txt_item" id="txt_item">
				</div><br>
				<div id="form_login">
					<div class="input-group input-group-lg" >
	        	<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-envelope"></i></span>
	          <input type="text" class="form-control" name="correo" placeholder="Correo Electrónico/Usuario" id="correo" onblur="validar_usuario()">                 
	        </div>  <br>        
	         <div class="input-group input-group-lg" >
	            <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
	              <input type="password" name="contra" id="contra" class="form-control" placeholder="******" aria-describedby="sizing-addon1" required autocomplete="new-password">
	         </div>					
				</div>
				<div id="form_cartera" style="display:none">
					<div class="input-group input-group-lg" >
	        	<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-envelope"></i></span>
	          <input type="text" class="form-control" name="correo_cartera" placeholder="Correo Electrónico/Usuario" id="correo_cartera" onblur="validar_usuario()">                 
	        </div>  <br>        
	         <div class="input-group input-group-lg" >
	            <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
	              <input type="password" name="contra_cartera" id="contra_cartera" class="form-control" placeholder="******" aria-describedby="sizing-addon1" required autocomplete="new-password">
	         </div>							
				</div>
				
        <br>
         <button type="button" id="IngresoLog" name="submitlog"  class="btn btn-lg btn-primary btn-block btn-signin" onclick="/*Ingresar();*/Ingresar_vali()">Entrar</button>
				 <!-- <input type="submit" name="submitlog" id='enviar' value="Entrar" class="btn btn-lg btn-primary btn-block btn-signin" id="IngresoLog" /> -->
         <div class="opcioncontra">
				 	<!-- <a href="login_cartera.php">Cartera de Clientes</a> -->
				 	<!-- <br> -->
         	<a href="recuperar.php">Olvidaste tu contraseña?</a>
         </div>
       </form>
      </div>
    </div>

     <div id="mis_empresas" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="border-radius: 10px;">
      <div class="modal-dialog modal-dialog-centered modal-md">
          <div class="modal-content" style=" background: rgba(201, 223, 241,0.7); border-radius: 10px;">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Empreas asociadas a <label id="lbl_ruc"></label></h4>
              </div>
              <div class="modal-body" style="height:300px; overflow-y:scroll;">
              	<table class="table table-hover">
              		<thead>
              			<th style="width: 35%;"></th>
              			<th>Empresa</th>
              		</thead>
              		<tbody id="tbl_empresas">
              		</tbody>
              	</table>

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" onclick="location.reload()">Cerrar</button>
              </div>
          </div>

      </div>
  </div>

</body>

</html>