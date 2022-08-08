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
        	// console.log(response);
        if(response.respuesta == 1)
        {
        	

        	$('#txt_cartera').val('1');
        	// $('#form_cartera').css('display','block');
        	// $('#form_login').css('display','none');
        	$('#correo').val(response.cartera_usu);
					$('#contra').val(response.cartera_pass);
        	$('#alerta').css('display','block');
        	$('#alerta').html(response.Nombre+'<br> <u>Cartera de Cliente</u>');
        	$('#res').val(response.entidad);

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
        }        
      }
    });

  }

   function validar_usuario()
  { 

		 var usuario = $("#correo").val();
		 var entidad = $("#res").val();
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
        	Swal.fire('Este usuario no esta registrado O no pertenece a la entidad!','No se a encontrado al usuario.','error');

        }else if(response.respuesta==-2)
        {      	
        	Swal.fire('Este usuario se encuentra bloqueado!','Usuario bloqueado.','error');
        }     
      }
    });

  }

  function Ingresar()
  { 

		 var usuario = $("#txt_usuario").val();
		 var entidad = $("#res").val();
		 var cartera = $("#txt_cartera").val();
		 var ci_empresa = $("#entidad").val();
		 if(entidad =='')
		 {
		 		Swal.fire('No se a verificado la entidad Asegurese de colocar una entidad valida','Se volvera a verificar la empresa','info').then(function(){ $('#entidad').focus()});
		 	return false;
		 }
		 if(entidad=='')
		 {
		 	Swal.fire('Llene todo los campos','Asegurese de colocar una entidad, usuario y password validos','info')
		 	return false;
		 }
		 
		 var parametros = 
		 {
		 	 'usuario':usuario,
		 	 'entidad':entidad,
		 	 'empresa':ci_empresa,		 	
		 }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/login_controller.php?recuperar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) { 
        	console.log(response);
        if(response.respuesta==-1)
        {
        	Swal.fire('Usuario invalidos!','No se pudo recuperar.','error');
        }else if(response.respuesta==-2)
        {        	
        	Swal.fire('Clave o usuario de cartera invalidos!','No se pudo acceder.','error');
        }
        else
        {      	
        	var ema = response.email;
       		if(ema!='' && ema !='.')
       		{
       			"intimundosa@hotmail.com"
       			var ini = ema.substring(0,4);
       			var divi = ema.split('@');
       			var num_car =  divi[0].substring(4).length;
       			// num_car = num_car
       			var medio = '';
       			for (var i = 0; i < num_car; i++) {
       				medio+='*';       				
       			}
       			var fin = divi[1];
       		}
       			// console.log(ini+medio+fin);

       		 // $('#lbl_email').text(ini+medio+'@'+fin);


        	// console.log(response); return false;
        	Swal.fire('Sus credenciales han sido enviadas al email <br>'+ini+medio+'@'+fin,'','success').then(function(){
        		location.href = 'login.php';
        	});
        }     
      }
    });

  }	
	  </script>
	
</head>
<body>
	<div id="Contenedor" style="background:#af373766;">
		<div class="Icon">
			<img src="../../img/jpg/logo.jpg" class="img-circle" alt="User Image" style="width: 20%; height:20%;">
		</div>
		<div class="ContentForm">
		   <form action="../controlador/login_controller.php" method="post" name="FormEntrar">
		   	<div id="alerta" class="alert alert-success visible" align="center" style="display:none;"></div>
		   	<div class="input-group input-group-lg" >
		   		<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-home"></i></span>
		   		<!-- 1792164710001 -->
		   		<input type="text" class="form-control" name="entidad" placeholder="Entidad a la que perteneces" id="entidad" onblur="validar_entidad()">
		   		<input type="hidden" name="res" id="res">
		   	</div><br>
				
			<div id="form_cartera">
				<div class="input-group input-group-lg" >
		         <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-envelope"></i></span>
		          <input type="text" class="form-control" name="txt_usuario" placeholder="Email / Usuario" id="txt_usuario">
		        </div>  <br>    
			</div>
        <br>
         <button type="button" id="IngresoLog" name="submitlog"  class="btn btn-lg btn-primary btn-block btn-signin" onclick="Ingresar();">Recuperar contraseña</button>
         <br>
       </form>
      </div>
    </div>
</body>

</html>