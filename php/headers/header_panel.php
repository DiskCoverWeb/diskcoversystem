<?php  @session_start(); 
include("../db/chequear_seguridad.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Top Navigation</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
  <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
  <!-- <link rel="stylesheet" href="../../dist/css/sweetalert.css"> -->
  <script src="../../dist/js/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../dist/css/sweetalert2.min.css">
  <script src="../../dist/js/js_globales.js?<?php echo date('y') ?>"></script>

  <script type="text/javascript">
    function validador_correo(imput)
    {
        var campo = $('#'+imput).val();   
        var emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
        //Se muestra un texto a modo de ejemplo, luego va a ser un icono
        if (emailRegex.test(campo)) {
          // alert("válido");
          return true;

        } else {
          Swal.fire('Email incorrecto','','info');
          console.log(campo);
          return false;
        }
    }
    function logout()
  { 
     
     $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/login_controller.php?logout=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) { 
          console.log(response);
        if(response == 1)
        {
          location.href = 'login.php';          
        }     
      }
    });

  }
  </script>

  <script type="text/javascript">
    function listado_empresas()
    {
      $("#myModal_espera").modal("show");     
      $.ajax({
        // data:  {parametros:parametros},
        url:   '../vista/select_empresa.php?consultar=true',
        type:  'post',
        dataType: 'json',     
          success:  function (response) {  
          // console.log(response);      
           $('#contenido').html(response); 
           $('#myModal_espera').modal('hide');
        }
      });
    }

    function empresa_seleccionada()
    {
        $('#myModal_espera').modal('show');
        var value =  $('#sempresa').val(); 
         arra = value.split('-');
        text =$('#sempresa option:selected').html();
        var parametros = 
        {
          'ID':value,
          'EMPRESA':text,
          'ITEM':arra[1],
        }
      // window.location="panel.php?mos="+value+"&mos1="+text+"&mos3="+arregloDeSubCadenas[1]+"";
      $.ajax({
        data:  {parametros:parametros},
        url:   '../vista/select_empresa.php?cargar=true',
        type:  'post',
        dataType: 'json',     
          success:  function (response) {  
          location.href = 'modulos.php';
        }
      });
    }
  </script>



</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">   
        <img src="../../img/logotipos/diskcover_web.gif"  class="navbar-brand" style="padding: 2px;" title="Diskcover Systema
DIRECCION: Atacames N23-226 y Av. La Gasca
EMAIL: prisma_net@hotmail.com
diskcove@msn.com
info@diskcoversystem.com
TELEFONO: (+593)989105300 - 999654196 - 986524396">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

         <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">          
          <!-- <li>
            <a href="#" class="" data-toggle="dropdown" style="padding: 12px 0px 12px 0px;" >
              <img src="../../img/png/salire.png" class="fa" alt="User Image" style="width:75%" title="Salir de empresa">
            </a>
          </li> -->
          <li>
            <a href="#" class="btn " data-toggle="dropdown" style="padding: 12px 0px 12px 0px;" onclick="logout()">
              <!-- <i class="fa fa-calendar"></i> -->
              <img src="../../img/png/salirs.png" class="fa" alt="User Image" style="width:75%" title="Salir de sistema">
            </a>
              <!-- <i class="fa fa-calendar"></i> -->
            
          </li>

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
              <span class="hidden-xs"><?php echo $_SESSION['INGRESO']['Nombre']; ?></span>
            </a>
           <!--  <ul class="dropdown-menu">
              <li class="user-header">
                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2012</small>
                </p>
              </li>
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="#" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul> -->
          </li>
        </ul>
      </div>
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>