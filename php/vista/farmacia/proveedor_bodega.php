<script type="text/javascript">
$( document ).ready(function() {
   provincias();
   DLCxCxP();
	 DLGasto();
	 DLSubModulo();
	 lista_proveedores();
});
 function nombres(nombre)
  {
    $('#txt_nombre').val(nombre.ucwords());
  }

function lista_proveedores()
  {
  	var parametros = 
  	{
  		'query':$('#txt_query').val(),
  	}
    $.ajax({
      url: '../controlador/farmacia/proveedor_bodegaC.php?lista_clientes=true',
      type:'post',
      dataType:'json',
     data:{parametros:parametros},     
      success: function(response){
      	if(response!='')
      	{
        	$('#tbl_cliente').html(response);
        }
      // console.log(response);
    }
    });
  }

  function guardar_cliente()
  {
  	var parametros = 
  	{
  		'id':$('#txt_id').val(),
  		'nombre':$('#txt_nombre').val(),
  		'ci':$('#txt_ci').val(),
  		'telefono':$('#txt_telefono').val(),
  		'email':$('#txt_email').val(),
  		'direccion':$('#txt_direccion').val(),
  	}
    $.ajax({
      url: '../controlador/farmacia/proveedor_bodegaC.php?add_clientes=true',
      type:'post',
      dataType:'json',
     data:{parametros:parametros},     
      success: function(response){
      	if(response=='1')
      	{
      		if($('#txt_id').val()=='')
      		{
        	 Swal.fire('Registro guardado','','success');
        	}else
        	{
        	 Swal.fire('Registro editado','','success');        		
        	}
        	
        limpiar();
        }else if(response=='-2')
        {
        	Swal.fire('Numero de cedula ya registrada','','error');
        }else if(response=='-3')
        {
        	Swal.fire('Numero de cedula Incorrecta','','info');
        }
        lista_proveedores();
      // console.log(response);
    }
    });
  }

  function buscar_cliente(id)
  {  	
    $.ajax({
      url: '../controlador/farmacia/proveedor_bodegaC.php?buscar_edi=true',
      type:'post',
      dataType:'json',
     data:{id:id},     
      success: function(response){
      	// console.log(response);
      	$('#txt_id').val(response[0].ID);
      	$('#txt_codigo').val(response[0].Codigo);
  			$('#txt_nombre').val(response[0].Cliente);
  			$('#txt_ci').val(response[0].CI_RUC);
  			$('#txt_telefono').val(response[0].Telefono);
  			$('#txt_email').val(response[0].Email);
  			$('#txt_direccion').val(response[0].Direccion);
      	
    	}
    });
  }

  function limpiar()
  {
  	$('#txt_id').val('');
  	$('#txt_codigo').val('');
		$('#txt_nombre').val('');
		$('#txt_ci').val('');
		$('#txt_telefono').val('');
		$('#txt_email').val('');
		$('#txt_direccion').val('');
  }




  function eliminar(id)
  {
    Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
    	if (result.value) {
    		 eliminar_cliente(id);
    	}
    })
  }

  function eliminar_cliente(id)
  {  	
    $.ajax({
      url: '../controlador/farmacia/proveedor_bodegaC.php?delete=true',
      type:'post',
      dataType:'json',
     data:{id:id},     
      success: function(response){
      	if(response==1)
      	{
      		Swal.fire('Registro eliminado','','success');      		
        }else{ 
        	Swal.fire('Intente mas tarde','','error');
        }
        lista_proveedores();
      // console.log(response);
    }
    });
  }


function provincias()
  {
   var option ="<option value=''>Seleccione provincia</option>"; 
     $.ajax({
      url: '../controlador/educativo/detalle_estudianteC.php?provincias=true',
      type:'post',
      dataType:'json',
     // data:{usu:usu,pass:pass},
      beforeSend: function () {
                   $("#select_ciudad").html("<option value=''>Seleccione provincia</option>");
             },
      success: function(response){
      response.forEach(function(data,index){
        option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
      });
       $('#prov').html(option);
      console.log(response);
    }
    });
  }

  function DLCxCxP(){
      $('#DLCxCxP').select2({
        placeholder: 'Seleccione una beneficiario',
        ajax: {
          url:   '../controlador/modalesC.php?DLCxCxP=true&SubCta='+$('#SubCta').val(),
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }


   function DLGasto(){
      $('#DLGasto').select2({
        placeholder: 'Seleccione una beneficiario',
        width:'100%',
        ajax: {
           url:   '../controlador/modalesC.php?DLGasto=true&SubCta='+$('#SubCta').val(),
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }


   function DLSubModulo(){
      $('#DLSubModulo').select2({
        placeholder: 'Seleccione una beneficiario',
         width:'100%',
        ajax: {
           url:   '../controlador/modalesC.php?DLSubModulo=true&SubCta='+$('#SubCta').val(),
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }


  function cargar_cuentas(tipo)
  {
  	if($('#txt_id').val()=='')
  	{
  		Swal.fire('Selecione un registro','','info');
  		return false;
  	}
  	$('#modal_cuentas').modal('show');

  	$('#txt_nombre_cuenta').val($('#txt_nombre').val());
  	$('#txt_ci_cuenta').val($('#txt_codigo').val());
  	if(tipo=='cxc')
  	{
  		$('#titulo').text('ASIGNACION DE CUENTAS POR COBRAR');
  		$('#SubCta').val('C');
  		$('#cbx_cuenta_g').prop('disabled',true);


  	}else
  	{

  		$('#cbx_cuenta_g').prop('disabled',false);
  		$('#titulo').text('ASIGNACION DE CUENTAS POR PAGAR');
  		$('#SubCta').val('P');
  	}
  	DLCxCxP();
	 DLGasto();
	 DLSubModulo();
  }


  function mostar_cuenta_Gastos()
  {
  	if($('#cbx_cuenta_g').prop('checked'))
  	{
  		$('#panel_cuenta_gasto').css('display','block');
  	}else
  	{
  		$('#panel_cuenta_gasto').css('display','none');
  	}

  }
	function mostar_porcentaje_retencion()
	{
		if($('#cbx_retencion').prop('checked'))
		{
			$('#panel_retencion').css('display','block');
		}else
		{
		$('#panel_retencion').css('display','none');
		}
	}

	function guardar_cuentas()
	{
		datos = $('#form_cuentas').serialize();
		$.ajax({
      url: '../controlador/farmacia/proveedor_bodegaC.php?guardar_cuentas=true',
      type:'post',
      dataType:'json',
     data:datos,     
      success: function(response){
        if(response==1)
        {
          cancelar();
          $('#modal_cuentas').modal('hide');
        }      
      }
    });
	}

  function cancelar()
  {
    $('#DLCxCxP').empty();
    $('#DLGasto').empty();
    $('#DLSubModulo').empty();
    $('#TxtCodRet').val('.');
    $('#TxtRetIVAB').val('.');
    $('#TxtRetIVAS').val('.');
    $('#txt_ci_cuenta').val('.');
    $('#Ttxt_nombre_cuenta').val('.');

    if($('#cbx_retencion').prop('checked'))
    {
     $('#cbx_retencion').click();
    }
     if($('#cbx_cuenta_g').prop('checked'))
    {
      $('#cbx_cuenta_g').click();
    }
    
  }

	
</script>
 <div class="row"><br>
    <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=Farmacia#" data-toggle="tooltip" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" onclick="cargar_cuentas('cxc')" data-toggle="tooltip" title="Asignar a Cuenta por Cobrar Contabilidad" >
            <img src="../../img/png/cxc.png">
          </button>           
        </div>
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" onclick="cargar_cuentas('cxp')" data-toggle="tooltip" title="Asignar a Cuenta por Pagar Contabilidad " >
            <img src="../../img/png/cxp.png">
          </button>         
        </div>
 <!-- <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-default" onclick="">
            <img src="../../img/png/articulos.png" >
          </a>
        </div>      -->
 </div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-primary">
      <div class="panel-heading text-center">
        <b>Proveedor Bodega</b>
      </div>
			<div class="panel-body">
				<div class="col-sm-5">
					<b>Nombre</b>

					<input type="hidden" name="txt_id" id="txt_id" class="form-control input-sm">
					<input type="hidden" name="txt_codigo" id="txt_codigo" class="form-control input-sm">
					<input type="" name="txt_nombre" id="txt_nombre" class="form-control input-sm" onkeyup="nombres(this.value)">
				</div>
				<div class="col-sm-2">
					<b>RUC / CI</b>
					<input type="" name="txt_ci" id="txt_ci" class="form-control input-sm">
				</div>
				<div class="col-sm-2">
					<b>Telefono</b>
					<input type="" name="txt_telefono" id="txt_telefono" class="form-control input-sm">
				</div>
				<!-- <div class="col-sm-2">
					<b>Movil</b>
					<input type="" name="" id="" class="form-control input-sm">
				</div> -->
				<div class="col-sm-3">
					<b>Email</b>
					<input type="" name="txt_email" id="txt_email" class="form-control input-sm">
				</div>
				<!-- <div class="col-sm-3">
					<b>Localidad</b>
					<input type="" name="" id="" class="form-control input-sm">
				</div>				
				<div class="col-sm-4">
					<b>Provincia</b>
					<select class="form-control input-sm" id="prov">
						<option value="">Seleccione provincia</option>
					</select>
				</div>
				<div class="col-sm-2">
					<b>Codigo Postal</b>
					<input type="" name="" id="" class="form-control input-sm">
				</div> -->
				<div class="col-sm-9">
					<b>Direccion</b>
					<input type="" name="txt_direccion" id="txt_direccion" class="form-control input-sm">
				</div>
				<!-- <div class="col-sm-3">
					<b>direccion Web</b>
					<input type="" name="" id="" class="form-control input-sm">
				</div>		 -->
				<div class="col-sm-3 text-right">
					<br>
					<button class="btn btn-primary btn-sm" onclick="guardar_cliente()"><i class="fa fa-save"></i> Guardar</button>
				</div>
        <div class="col-sm-6">
          <b>Buscar</b>
          <input type="text" name="txt_query" id="txt_query" class="form-control form-control-sm" onkeyup="lista_proveedores()" placeholder="Ingrese nombre">
        </div>		
			</div>
      	
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="box">
			<table class="table table-hover">
				<thead>
					<th>Nombre</th>
					<th>RUC / CI</th>
					<th>Telefono</th>
					<th>Email</th>
					<!-- <th>Provincia</th> -->
					<th>Direccion</th>
					<th></th>
				</thead>
				<tbody id="tbl_cliente">
					<tr><td colspan="6">Sin registros</td></tr>
				</tbody>
			</table>
			
		</div>
	</div>
</div>



  <div class="modal fade" id="modal_cuentas" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="titulo">ASIGNACION DE CUENTAS POR COBRAR</h5>
      </div>
        <div class="modal-body">
          <div class="row">
          	<form id="form_cuentas">
            <input type="hidden" name="SubCta" id="SubCta" value="">
        
            <div class="col-sm-10">
              <div class="row">
              	<div class="col-sm-4">
              		<input id="txt_ci_cuenta" name="txt_ci_cuenta" class="form-control form-control-sm" readonly style="background-color:black; color: yellow;" value="999999999999">
              	</div>
              	<div class="col-sm-8">
              		<input id="txt_nombre_cuenta" name="txt_nombre_cuenta" class="form-control form-control-sm" readonly style="background-color:black; color: yellow;" value="CONSUMIDOR FINAL">
              	</div>
              </div>
              <div class="row">
	              <div class="col-sm-12">
	              <b>Asignar a:</b>
	              	<select class="form-control form-control-sm" id="DLCxCxP" name="DLCxCxP" style="width: 100%;">
	              		<option value="">Seleccione Cuenta</option>
	              	</select>
	              </div>
	            </div>
	             <div class="row">
	            	<div class="col-sm-12">	            		
	              	<label><input type="checkbox" name="cbx_cuenta_g" id="cbx_cuenta_g" onclick="mostar_cuenta_Gastos()"> ASIGNAR A LA CUENTA DE GASTOS</label>
	            	</div>
	            </div>
	            <div class="row" id="panel_cuenta_gasto" style="display:none">	            	
	            	<div class="col-sm-6 nopadding">
	            		<select class="form-control form-control-sm col-sm-6" id="DLGasto" name="DLGasto">
	              		<option value="">Seleccione Cuenta</option>
	              	</select>
	            	</div>
	            	<div class="col-sm-6 nopadding">
	            		<select class="form-control form-control-sm col-sm-6" id="DLSubModulo" name="DLSubModulo">
	              		<option value="">Seleccione Cuenta</option>
	              	</select>
	            	</div>
	            </div>
	             <div class="row">	            	
	            	<div class="col-sm-12">	            		
	              	<label><input type="checkbox" name="cbx_retencion" id="cbx_retencion" onclick="mostar_porcentaje_retencion()"> PORCENTAJES DE RETENCION</label>
	            	</div>	  
	            </div>
	            <div class="row" id="panel_retencion" style="display:none">	
	            	<div class="col-sm-4">
	            		Codigo Retencion
	            		<input type="" name="TxtCodRet" id="TxtCodRet" class="form-control form-control-sm">
	            	</div>	            	
	            	<div class="col-sm-4">
	            		Retencion IVA Bienes
	            		<input type="" name="TxtRetIVAB" id="TxtRetIVAB" class="form-control form-control-sm">
	            	</div>	            	
	            	<div class="col-sm-4">
	            		Retencion IVA servicios
	            		<input type="" name="TxtRetIVAS" id="TxtRetIVAS" class="form-control form-control-sm">
	            	</div>
	            	           	
	            </div>
            </div>
          </form>
            <div class="col-sm-2">
              <div class="btn-group">
                <button class="btn btn-default btn-sm" onclick="guardar_cuentas()"><img src="../../img/png/grabar.png"><br>&nbsp;&nbsp;&nbsp;Aceptar&nbsp;&nbsp;&nbsp;</button> 
                <button class="btn btn-default"  data-dismiss="modal" onclick="cancelar()"> <img src="../../img/png/bloqueo.png" ><br> Cancelar</button>     
              </div>              
            </div>            
          </div>
        </div>
      </div>
    </div>
  </div>