<?php  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION);die();//print_r($_SESSION['INGRESO']);die();

 //verificacion titulo accion
  $_SESSION['INGRESO']['ti']='';
  if(isset($_GET['ti'])) { $_SESSION['INGRESO']['ti']=$_GET['ti']; } else{ unset( $_SESSION['INGRESO']['ti']); $_SESSION['INGRESO']['ti']='ADMINISTRAR EMPRESA';}

?>
<script type="text/javascript">
  $(document).ready(function () {
  	

  });

  function mostrarEmpresa()
  {
    //$(".loader1").show();
    var x = document.getElementById('mostraE');
    var x1 = document.getElementById('mostraEm');
    
    if (x.style.display === 'none') 
    {
      x.style.display = 'block';
      $("#mostraEm").show();
      $("#mostraEm1").show();
    } 
    else 
    {
      x.style.display = 'none';
      //$("#mostraEm").hide();
      $("#mostraEm1").hide();
    }
  }

  function consultar_datos()
    {
      let desde= document.getElementById('desde');
      let hasta= document.getElementById('hasta');
      ///alert(desde.value+' '+hasta.value);
      var parametros =
      {
        'desde':desde.value,
        'hasta':hasta.value,
        'repor':'',     
      }
      $titulo = 'Mayor de '+$('#DCCtas option:selected').html(),
      $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/contabilidad_controller.php?consultar=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () {   
          //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'      
           // $('#tabla_').html(spiner);
           $('#myModal_espera').modal('show');
        },
          success:  function (response) {

            var tr ='';
            response.forEach(function(item,i)
            {
              tr+='<tr><td>'+item.tipo+'</td><td>'+item.Item+'</td><td>'+item.Empresa+'</td><td>'+item.Fecha+'</td><td>'+item.enero+'</td></tr>';
            })
                $('#tbl_vencimiento').html(tr);
            $('#myModal_espera').modal('hide');
          
        
        }
      });
    }

  function reporte()
  {
    let desde= $('#desde').val();
    let hasta= $('#hasta').val();
    var tit = 'ADMINISTRAR EMPRESA';
    var url = ' ../controlador/contabilidad/contabilidad_controller.php?consultar_reporte=true&desde='+desde+'&hasta='+hasta+'&repor=2';
    window.open(url,'_blank');
  }
</script>

<div class="row">
  <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12">
     <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
            <img src="../../img/png/salire.png">
          </a>
      </div>
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" title="Mostrar Vencimiento" onclick='mostrarEmpresa();'><img src="../../img/png/reporte_1.png"></button>
      </div>
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" title="Mostrar Vencimiento" onclick="reporte()"><img src="../../img/png/table_excel.png"></button>
      </div>
      
       <!-- <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="#" class="btn btn-default" title="Asignar reserva" onclick="Autorizar_Factura_Actual2();" target="_blank" ><img src="../../img/png/archivero2.png"></a>
      </div> -->
  </div>
</div>
<div class="row">
  <div class="col-sm-2">
      <b>Desde: </b>
      <input type="date" class="form-control input-sm" id="desde" value="<?php echo date("Y-m-d"); ?>">      
  </div> 
  <div class="col-sm-2">
    <b>Hasta: </b>
    <input type="date" id="hasta"  class="form-control input-sm"  value="<?php echo date("Y-m-d");?>" onblur="consultar_datos();">      
  </div>    
</div>
  <br>
  <div class="row">
    <div id='mostraE'>
      <div class="col-sm-12">
        <div class="table-responsive" style="height:300px">
          <table class="table" style="width: 98%;">
            <thead>
                <th>Tipo</th>
                <th>Item</th>
                <th>Empresa</th>
                <th>Fecha</th>
                <th>Enero</th>
            </thead>
            <tbody id="tbl_vencimiento">
              
            </tbody>
          </table>
        </div>
      </div>             
    </div>
  </div>
	
	
</div>
