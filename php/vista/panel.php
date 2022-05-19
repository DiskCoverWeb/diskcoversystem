<?php 
include('../headers/header_panel.php');
$empresa = '';
if (isset($_SESSION['INGRESO']['empresa'])) { $empresa = $_SESSION['INGRESO']['empresa'];}
?>
<script>
  $(document).ready(function(){
    var empresa = '<?php echo $empresa; ?>';
    if(empresa!='')
    {
      Swal.fire('Sesion anterior encontrada','','info').then(function(){ location.href = 'modulos.php'; });
    }else
    {
      listado_empresas();
    }

  })
  </script>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
        Empresas asociadas a este usuario
        </h1>
        <!-- <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#">Layout</a></li>
          <li class="active">Top Navigation</li>
        </ol> -->
      </section>

      <!-- Main content -->
      <section class="content" id="contenido">
   	  
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <?php include('../headers/footer_panel.php');?>
 