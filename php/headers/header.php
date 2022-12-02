<?php  @session_start(); 
include("../db/chequear_seguridad.php"); 
include("../controlador/panel.php");
include("../funciones/funciones.php");
include('../controlador/contabilidad/contabilidad_controller.php');

       $f =date('Y-m-d');
       // print_r($_SESSION);die();
       if(isset($_SESSION['INGRESO']['Fecha']))
       {
          $f =$_SESSION['INGRESO']['Fecha'];
       }
      $date1 = new DateTime(date('Y-m-d'));
      $date2 = new DateTime($f);
      $diff = date_diff($date1, $date2)->format('%R%a días');
      // $interval = date_diff($date1, $date2);
      // echo $interval->format('%R%a días');
      $color='white';
      $estado = 'Infefinido';
      if($diff> 241)
      {
        $color = 'success';
        $estado = 'Licencia activa';

      }else if($diff >= 121 and  $diff <= 240)
      {

        $estado = 'Licencia activa';
        $color = 'success';
      }else if($diff >= 1 and $diff<=120)
      {

        $estado = 'Casi por renovar';
        $color = 'warning';
      }else if($diff <= 0 and isset($_SESSION['INGRESO']['item']))
      {
        $estado = 'licencia vencida';
        $color='danger';
      }

       $f1 =date('Y-m-d');
       if(isset($_SESSION['INGRESO']['Fecha_ce']))
       {
          $f1 =$_SESSION['INGRESO']['Fecha_ce'];
       }
      $date11 = new DateTime(date('Y-m-d'));
      $date21 = new DateTime($f1);
      $diff1 = date_diff($date11, $date21)->format('%R%a días');
      $color1='white';
      $estado1 = 'Infefinido';
      if($diff1 > 241)
      {
        $color1 = 'success';
        $estado1 = 'Comp-Elec. activo';

      }else if($diff1 >= 121 and  $diff1 <= 240)
      {

        $estado1 = 'Comp-Elec. activo';
        $color1 = 'success';
      }else if($diff1 >= 1 and $diff1<=120)
      {

        $estado1 = 'Comp-Elec. por renovar';
        $color1 = 'warning';
      }else if($diff1 <= 0 and isset($_SESSION['INGRESO']['item']))
      {
        $estado1 = 'Comp-Elec. vencida';
        $color1='danger';
      }
    $modulo_header = '';
    if(isset($_GET['mod']))
    {
      $cod = $_GET['mod'];
      $detalle_modulo = datos_modulo($cod);
      $modulo_header = $detalle_modulo[0]['aplicacion'];
      // print_r($detalle_modulo);
    }

    $cuentas = SeteosCtas();
    
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Diskcover System | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">

  <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">
  
  <link rel="shortcut icon" href="../../img/jpg/logo.jpg" />
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="../../dist/css/style_acordeon.css">
  <link rel="stylesheet" href="../../dist/css/jquery-ui.css">
  <link rel="stylesheet" href="../../dist/css/sweetalert2.min.css">
  <link rel="stylesheet" href="../../dist/css/creados.css">
  <link rel="stylesheet" href="../../dist/css/email.css" type="text/css">

  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
  <script src="../../bower_components/select2/dist/js/select2.js"></script>
  <script src="../../dist/js/jquery-ui.js"></script>
  <script src="../../dist/js/sweetalert2.js"></script>
  <script src="../../dist/js/js_globales.js"></script>

  <script type="text/javascript">

     $(document).ready(function () {
      var cuentas = '<?php echo $cuentas; ?>';
      if(cuentas!='')
      {
        // console.log(cuentas);
       Swal.fire(cuentas,'Faltan cetear cuentas','info');
      }

     })
     


        // document.onclick = actualizar_base_actual;
        // document.onchange = actualizar_base_actual;
        // document.onblur = actualizar_base_actual;
        // document.onkeyup = actualizar_base_actual;



    // setInterval(actualizar_base_actual, 1000);
    pantalla_medidas();
  var formato = "<?php if(isset($_SESSION['INGRESO']['Formato_Cuentas'])){echo $_SESSION['INGRESO']['Formato_Cuentas'];}?>";
  var formato_inv = "<?php if(isset($_SESSION['INGRESO']['Formato_Inventario'])){echo $_SESSION['INGRESO']['Formato_Inventario'];}?>";
  function addCliente(){
    var src ="../vista/modales.php?FCliente=true";
     $('#FCliente').attr('src',src);     
    $("#myModal").modal("show");
  }

  // esta fyunciuon esta en js_globales
  // function mayusculas(campo)
  // {
  //    var dato = $('#'+campo).val();
  //    str = dato.toLowerCase();
  //   $('#'+campo).val(str);
  // }


  function actualizar_base_actual()
  {
    $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/login_controller.php?base_actual_=true',
      type:  'post',
      dataType: 'json',     
        success:  function (response) { 
          console.log(response);
          $("#base_actual").text(response);
      }
    });

  }
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

  function cambiar_empresa()
  {      
     $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/panel.php?salir_empresa=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          console.log(response);
        if(response == 1)
        {
          location.href = 'panel.php';          
        }     
      }
    });
  }

  function pantalla_medidas()
  {      
     $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/panel.php?pantalla=true&width='+screen.width+'&height='+screen.height,
      type:  'post',
      dataType: 'json',
        success:  function (response) {             
      }
    });
  }


    function formatoDate(date)
    {
      var formattedDate = new Date(date); 
      var d = formattedDate.getDate(); 
      var m = formattedDate.getMonth(); 
      m += 1; // javascript months are 0-11
      if(m<10)
      {
        m = '0'+m;
      } 
      if(d<10)
      {
        d = '0'+d;
      } 
      var y = formattedDate.getFullYear(); 
      var Fecha = y + "-" + m + "-" + d;
      console.log(Fecha);
      return Fecha;
    }

  </script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">
        <img src="../../img/logotipos/diskcover_web.gif" title="Diskcover Systema
DIRECCION: Atacames N23-226 y Av. La Gasca
EMAIL: prisma_net@hotmail.com
diskcove@msn.com
info@diskcoversystem.com
TELEFONO: (+593)989105300 - 999654196 - 986524396">
      </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>


      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-calendar"></i>
              Comp-Elec
              <span class="label label-<?php echo $color1;?>"><?php echo $estado1;?></span>
            </a>
            <ul class="dropdown-menu">
             <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <img src="../../img/png/calendario.png" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Fecha de comprobante:
                      </h4>
                      <p><?php if(isset($_SESSION['INGRESO']['Fecha_ce'])){ $originalDate = $_SESSION['INGRESO']['Fecha_ce']; $newDate = date("Y-m-d", strtotime($originalDate)); echo $newDate;}else{ echo date('Y-m-d');}?></p>
                      <p>DIAS RESTANTES: <?php echo $diff1 ?></p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-calendar"></i>
              Licencia
              <span class="label label-<?php echo $color;?>"><?php echo $estado;?></span>
            </a>
            <ul class="dropdown-menu">
             <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <img src="../../img/png/calendario.png" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Fecha de licencia:
                      </h4>
                      <p><?php if(isset($_SESSION['INGRESO']['Fecha'])){ $originalDate = $_SESSION['INGRESO']['Fecha']; $newDate = date("Y-m-d", strtotime($originalDate)); echo $newDate;}else{ echo date('Y-m-d');}?></p>
                      <p>DIAS RESTANTES: <?php echo $diff ?></p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <li>
            <a href="#" class="btn" data-toggle="dropdown" style="padding: 12px 0px 12px 0px;" onclick="cambiar_empresa()">
              <!-- <i class="fa fa-calendar"></i> -->
              <img src="../../img/png/salire.png" class="fa" alt="User Image" style="width:75%" title="Salir de empresa">
            </a>
          </li>
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
          </li>
        </ul>
      </div>

      <?php if(isset($_GET['mod'])){ ?>
      <div class="navbar-custom-menu" style="float:left;font-size: 11px;">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu" >
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-dashboard"></i>
              <b><?php 
              //verificacion titulo accion
                  // $acction1 = '';
                  if(isset($_GET['acc1'])) 
                  {
                    unset($_SESSION['INGRESO']['accion1']);
                    $_SESSION['INGRESO']['accion1']=ucwords($_GET['acc1']);
                    // $acction1 = ucwords($_GET['acc1']);
                  }
                  else
                  {
                   $acction1 = '';
                    unset($_SESSION['INGRESO']['accion1']);
                  } 

              echo $modulo_header;?> </b><?php echo ' > '; if(isset($_SESSION['INGRESO']['accion1'])){ echo $_SESSION['INGRESO']['accion1']; } ?>
            </a>            
          </li>         
        </ul>
      </div>
    <?php } ?>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel" title="<?php echo $_SESSION['INGRESO']['Razon_Social']; ?>&#xA;R.U.C.:<?php echo $_SESSION['INGRESO']['RUC'];?>&#xA;Representante: <?php echo $_SESSION['INGRESO']['Gerente']; ?>&#xA;Direccion: <?php echo $_SESSION['INGRESO']['Direccion'];?>&#xA;Telefono:<?php echo $_SESSION['INGRESO']['Telefono1'].' / '.$_SESSION['INGRESO']['FAX']; ?>&#xA;Email:<?php echo $_SESSION['INGRESO']['Email'];?>&#xA;Item: <?php echo $_SESSION['INGRESO']['item'];?>">

        <div class="main-header text-center">
          <span class="logo-lg">    
        <!-- <div class="pull-left image"> -->
          <!-- //----------------logo de empresa---------------- -->
          <?php
          $url = '../../img/logotipos/diskcover_web.gif"';          
          if(isset($_SESSION['INGRESO']['Logo_Tipo']))
           {
            $tipo_img = array('jpg','gif','png','jpeg');
              foreach ($tipo_img as $key => $value) {
                if(file_exists( dirname(__DIR__,2). '/img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.'.$value))
                {                   
                  $url='../../img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.'.$value;
                  break;
                }
              }
           }
          ?>
          <!-- ----------------------- fin de logo --------------------- -->
          <!-- <img src="<?php echo $url; ?>" alt="User Image"> -->
        <!-- </div> -->
            <img src="<?php echo $url; ?>" width="50%">         
          </span>
            <p class="text-gray" style="margin:0px" id="base_actual"></p> 
            <p class="text-gray" style="margin:0px"><?php echo $_SESSION['INGRESO']['Nombre_Comercial'];?></p> 
            <p class="text-gray" style="margin:0px">RUC: <?php echo $_SESSION['INGRESO']['RUC'];?></p> 
            <p class="text-gray" style="margin:0px">Item: <?php echo $_SESSION['INGRESO']['item'];?></p> 
        </div>
        
        <!-- <div class="pull-left info">
          <p>RUC: <?php echo $_SESSION['INGRESO']['RUC'];?></p>            
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div> -->
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">       
            <?php 
            if(!isset($_SESSION['INGRESO']['modulo_']) || $_SESSION['INGRESO']['modulo_']=="")
            {
              // print_r($_SESSION['INGRESO']['modulo_']);die();
            }else{
              if(isset($_GET['mod']))
               {
                  if(isset($_GET['acc'])) 
                  {
                    $_SESSION['INGRESO']['accion']=$_GET['acc'];
                  }
                  else
                  {
                    unset( $_SESSION['INGRESO']['accion']);
                  }

        echo '<li class=" header"> Menu '.$modulo_header.' </li>';  
          $paginas = 
          $menu = select_menu_mysql();
          $accesos_pag = pagina_acceso_hijos($_SESSION['INGRESO']['CodigoU'],$_SESSION['INGRESO']['IDEntidad'],$_SESSION['INGRESO']['item']);
          if(count($menu)==0)
          {
             echo  '<li><a href="../vista/modulos.php" class="active treeview">
                 <i class="fa fa-th"></i> <span>Salir a modulos</span>
               </a></li>';
          }
          if(count($accesos_pag)>0)
          {
          $m='';
          foreach ($menu as $key => $value) {
            if(count(explode('.',$value['codMenu']))==2)
            {
              if(count(pagina_acceso_hijos($_SESSION['INGRESO']['CodigoU'],$_SESSION['INGRESO']['IDEntidad'],$_SESSION['INGRESO']['item'],$value['codMenu']))>0)
              {
              $item = strtolower($value['descripcionMenu']);
              $m.= '<li class="treeview">';
                $m.='<a class="nav-link dropdown-toggle" id="'.$item.'">'.
                  $value['descripcionMenu'].' <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span> </a>';
                  $m.='<ul class="treeview-menu" aria-labelledby="'.$item.'">';
                    $m.='<li class="dropdown-submenu">';
                      $nivel = select_nivel_menu_mysql($value['codMenu']);
                      foreach ($nivel as $key2 => $value2) 
                      {
                        if (count(explode(".",$value2['codMenu'])) == 3) 
                        {
                            if(count(pagina_acceso($value2['codMenu'],$_SESSION['INGRESO']['CodigoU'],$_SESSION['INGRESO']['IDEntidad'],$_SESSION['INGRESO']['item']))>0)
                              {                                                              
                                $ico = '';
                                $acceso = '';
                                if ($value2['accesoRapido'] != ".")
                                {
                                  $ico = '<small class="label pull-right bg-yellow">('.$value2['accesoRapido'].')</small>';
                                  $acceso = 'Acceso Rapido ('.$value2['accesoRapido'].')' ;
                                }
                                $m.='<li title="'.$acceso.'"><a href="'.$value2['rutaProceso'].'">'.$value2['descripcionMenu'].$ico.'</a></li>';
                              }
                        }                        
                      }
                  $m.='</li>';
                $m.='</ul>';
              $m.='</li>';
              } 
            }
          }
           $m.='<li><a href="../vista/modulos.php" class="active treeview">
                 <i class="fa fa-th"></i> <span>Salir a modulos</span>
               </a></li>';
          echo $m;
          }else{ 
          foreach ($menu as $item_menu) {
            if (count(explode(".",$item_menu['codMenu'])) == 2) {
              $item = strtolower($item_menu['descripcionMenu']);
              echo '<li class="treeview">';
                echo '<a class="nav-link dropdown-toggle" id="'.$item.'">'.
                  $item_menu['descripcionMenu'].' <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span> </a>';
                echo '<ul class="treeview-menu" aria-labelledby="'.$item.'">';
                  echo '<li class="dropdown-submenu">';
                    $nivel = select_nivel_menu_mysql($item_menu['codMenu']);

                    // print_r($nivel);die();
                    foreach ($nivel as $item_nivel) {
                      if (count(explode(".",$item_nivel['codMenu'])) == 3) {
                        $subnivel = select_nivel_menu_mysql($item_nivel['codMenu']);
                        if ($subnivel) {
                          echo '<li class="dropdown-submenu">';
                          echo '
                          <a href="#" id="'.strtolower($item_nivel['descripcionMenu']).'" >'.$item_nivel['descripcionMenu'].'
                              <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                          </a>';
                          echo '<ul class="dropdown-menu" aria-labelledby="de_operacion">';
                          foreach ($subnivel as $item_subnivel) {
                            $subnivel1 = select_nivel_menu_mysql($item_subnivel['codMenu']);
                            if ($subnivel1 && count(explode(".",$item_subnivel['codMenu'])) == 4) {
                              echo '<li class="dropdown-submenu">';
                              echo '<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="'.strtolower($item_subnivel['descripcionMenu']).'" role="button" aria-haspopup="true" aria-expanded="false">'.$item_subnivel['descripcionMenu'].'&nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></a>';
                              echo '<ul class="dropdown-menu" aria-labelledby="de_operacion">';
                              foreach ($subnivel1 as $item_subnivel1) {
                                echo '<li class="dropdown-item  col-6 col-md"><a href="'.$item_subnivel1['rutaProceso'].'">'.$item_subnivel1['descripcionMenu'];
                                if ($item_subnivel1['accesoRapido'] != ".") {
                                  echo '<span class="pull-right-container"><i class="fa fa-angle-left">('.$item_subnivel1['accesoRapido'].')</i></span>';
                                }
                                echo '</a></li>';
                              }
                              echo '</ul></li>';
                            } else if(count(explode(".",$item_subnivel['codMenu'])) == 4) {
                              echo '<li class="dropdown-item  col-6 col-md"><a href="'.$item_subnivel['rutaProceso'].'">'.$item_subnivel['descripcionMenu'];
                              if ($item_subnivel['accesoRapido'] != ".") {
                                // echo '<i  style="float: right;" align="right">('.$item_subnivel['accesoRapido'].')</i>';
                                 echo '<span class="pull-right-container"><i class="fa fa-angle-left">('.$item_subnivel['accesoRapido'].')</i></span>';
                              }
                            }
                            
                            echo '</a></li>';
                          }
                          echo '</ul></li>';
                        } else {
                          $ico = '';
                          $acceso = '';
                           if ($item_nivel['accesoRapido'] != ".") {
                             $ico = '<small class="label pull-right bg-yellow">('.$item_nivel['accesoRapido'].')</small>';
                             $acceso = 'Acceso Rapido ('.$item_nivel['accesoRapido'].')' ;
                          }
                          echo '<li title="'.$acceso.'"><a href="'.$item_nivel['rutaProceso'].'">'.$item_nivel['descripcionMenu'].$ico;
                          echo '</a></li>';
                        }
                      }
                    }
                  echo '</li>';
                echo '</ul>';
              echo '</li>';              
            }
          }
           echo  '<li><a href="../vista/modulos.php" class="active treeview">
                 <i class="fa fa-th"></i> <span>Salir a modulos</span>
               </a></li>';
        }
       
}

            ?>
             <!--  <li class="active treeview">
                <a href="../vista/modulos.php">
                  <i class="fa fa-th"></i> <span>Volver a modulos</span>
                </a>
              </li>
 -->
              <!-- <li class="active treeview">
                <a href="#">
                  <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li class="active"><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
                  <li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
                </ul>
              </li> -->

               <!-- <li class="treeview">
                <a href="#">
                  <i class="fa fa-files-o"></i>
                  <span>Layout Options</span>
                  <span class="pull-right-container">
                    <span class="label label-primary pull-right">4</span>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
                  <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
                  <li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
                  <li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
                </ul>
              </li>
              <li>
                <a href="pages/widgets.html">
                  <i class="fa fa-th"></i> <span>Widgets</span>
                  <span class="pull-right-container">
                    <small class="label pull-right bg-green">new</small>
                  </span>
                </a>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-pie-chart"></i>
                  <span>Charts</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
                  <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
                  <li><a href="pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
                  <li><a href="pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-laptop"></i>
                  <span>UI Elements</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
                  <li><a href="pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
                  <li><a href="pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
                  <li><a href="pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
                  <li><a href="pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
                  <li><a href="pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-edit"></i> <span>Forms</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
                  <li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
                  <li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-table"></i> <span>Tables</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
                  <li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
                </ul>
              </li>
              <li>
                <a href="pages/calendar.html">
                  <i class="fa fa-calendar"></i> <span>Calendar</span>
                  <span class="pull-right-container">
                    <small class="label pull-right bg-red">3</small>
                    <small class="label pull-right bg-blue">17</small>
                  </span>
                </a>
              </li>
              <li>
                <a href="pages/mailbox/mailbox.html">
                  <i class="fa fa-envelope"></i> <span>Mailbox</span>
                  <span class="pull-right-container">
                    <small class="label pull-right bg-yellow">12</small>
                    <small class="label pull-right bg-green">16</small>
                    <small class="label pull-right bg-red">5</small>
                  </span>
                </a>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-folder"></i> <span>Examples</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
                  <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>
                  <li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
                  <li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
                  <li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
                  <li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
                  <li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
                  <li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
                  <li><a href="pages/examples/pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>
                </ul>
              </li>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-share"></i> <span>Multilevel</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                  <li class="treeview">
                    <a href="#"><i class="fa fa-circle-o"></i> Level One
                      <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                      <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i> Level Two
                          <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                        </a>
                        <ul class="treeview-menu">
                          <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                          <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                </ul>
              </li> -->
            <?php } ?>
            </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
