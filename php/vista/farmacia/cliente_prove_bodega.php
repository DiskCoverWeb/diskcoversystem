<script type="text/javascript">
$( document ).ready(function() {
	 naciones();
   provincias();
   ciudad(17);
   cliente_proveedor();

 $('#ddl_cliente_proveedor').on('select2:select', function (e) {
      var data = e.params.data.data;


      $("#txt_id").val(data.ID);
      $("#txt_ci_ruc").val(data.CI_RUC);
      $("#txt_codigo").text(data.Codigo);
			$("#txt_fax").val(data.FAX);
			$("#txt_telefono").val(data.Telefono);
			$("#txt_telefono2").val(data.Telefono);
			$("#txt_celular").val(data.Celular);
			$("#txt_grupo").val(data.Grupo); 
			$("#txt_contactos" ).val(data.Contacto);
			$("#txt_descuento").val(data.Descuento);
			$("#txt_cliente").val(data.Cliente);

			$("#CTipoProv").val(data.Tipo_Pasaporte);
			$("#CParteR").val(data.Parte_Relacionada);
			$("#lbl_TD").text(data.TD);
			if(data.Sexo!='' && data.Sexo!='.')
			{
				$("#rbl_"+data.Sexo).attr('checked',true);
			}
			if(data.Especial==1)
			{
				$("#cbx_ContEsp").attr('checked',true);
			}else
			{				
				$("#cbx_ContEsp").attr('checked',false);
			}
			if(data.RISE==1)
			{
				$("#cbx_rise").attr('checked',true);
			}else
			{				
				$("#cbx_rise").attr('checked',false);
			}
			if(data.Asignar_Dr==1)
			{
				$("#cbx_dr").attr('checked',true);
			}else
			{				
				$("#cbx_dr").attr('checked',false);
			}

			$("#txt_direccion").val(data.Direccion);
			$("#txt_numero").val(data.DirNumero);
			$("#txt_email").val(data.Email);
			if(data.Pais!='' && data.Pais!='.')
			{
				$("#ddl_naciones").val(data.Pais);
			}
			if(data.Prov!='' && data.Prov!='.')
			{
				$("#prov").val(data.Prov);
			}
			if(data.Ciudad!='' && data.Ciudad!='.')
			{
				$("#ddl_ciudad").val(data.Ciudad);
			}
			$("#MBFecha").val( formatoDate(data.Fecha.date));
		 	$("#MBFechaN").val( formatoDate(data.Fecha_N.date));

		 	$("#txt_representante").val(data.Representante);
		 	if(data.Est_Civil!='.' && data.Est_Civil!='')
		 	{
				$("#ddl_estado_civil").val(data.Est_Civil);
			}
			$("#txt_no_dep").val(data.No_Dep);
			$("#txt_casilla").val(data.Casilla);
			$("#txt_comision").val(data.Porc_C);
			// $("#ddl_medidor").val(data.); //revisar
			$("#txt_Email2").val(data.Email2);
			$("#txt_afiliacion").val(data.Plan_Afiliado);
			$("#txt_actividad").val(data.Actividad);
			$("#txt_credito").val(data.Credito);
			$("#txt_profesion").val(data.Profesion);
			$("#txt_lugar_trabajo").val(data.Lugar_Trabajo);
			$("#txt_direccion_tra").val(data.DireccionT);
			$("#txt_califica").val(data.Casilla);
      console.log(data);
			historial_direcciones(data.Codigo);

      console.log(data);
    });

});

function naciones()
  {
   var option ="<option value=''>Seleccione pais</option>"; 
     $.ajax({
      url: '../controlador/educativo/detalle_estudianteC.php?naciones=true',
      type:'post',
      dataType:'json',
     // data:{usu:usu,pass:pass},
      beforeSend: function () {
                   $("#prov").html("<option value=''>OTRO</option>");
                   $("#ddl_ciudad").html("<option value=''>Seleccione provincia</option>");
             },
      success: function(response){
      response.forEach(function(data,index){
        option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
      });
       $('#ddl_naciones').html(option);
       $('#ddl_naciones').val('593');
       provincias('593');

      console.log(response);
    }
    });

  }

function provincias(pais)
  {
   var option ="<option value=''>Seleccione Provincia</option>"; 
     $.ajax({
      url: '../controlador/educativo/detalle_estudianteC.php?provincias=true',
      type:'post',
      dataType:'json',
     data:{pais:pais},
      beforeSend: function () {
                   $("#ddl_ciudad").html("<option value=''>Seleccione provincia</option>");
             },
      success: function(response){
      response.forEach(function(data,index){
        option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
      });
       $('#prov').html(option);       
       $('#prov').val(17);
      console.log(response);
    }
    });

  }

  function ciudad(idpro)
	{
		// console.log(idpro);
		var option ="<option value=''>seleccione ciudad</option>"; 
		//var idpro = $('#select_provincias').val();
		if(idpro !='')
		{
	   $.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?ciudad=true',
		  type:'post',
		  dataType:'json',
		  data:{idpro:idpro},
		  success: function(response){
			response.forEach(function(data,index){
				option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
			});
		   $('#ddl_ciudad').html(option);
		   $('#ddl_ciudad').val(21701);
			console.log(response);
		}
	  });
	 } 

	}

   function cliente_proveedor(){
      $('#ddl_cliente_proveedor').select2({
        placeholder: 'Seleccione una paciente',
        ajax: {
          url:   '../controlador/farmacia/proveedor_bodegaC.php?cliente_proveedor=true',
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

  function historial_direcciones(codigo)
  {
  		
	   $.ajax({
		  url: '../controlador/farmacia/proveedor_bodegaC.php?historial_direcciones=true',
		  type:'post',
		  dataType:'json',
		  data:{txtcodigo:codigo},
		  success: function(response){		  	
			$("#txt_historial_dir").html(response);
			// $("#txt_productos_rela").val(data.);
		}
	  });

  }
  function nuevo_cliente()
  {
  	$('#txt_cliente').attr('readonly',false);
  	$('#ddl_cliente_proveedor').attr('disabled',true);
  	$('#ddl_cliente_proveedor').empty();
  	$('#txt_ci_ruc').val('.');
  	$('#txt_ci_ruc').select();
  	limpiar();
  }

  function modificar_cliente()
  {
  	$('#txt_cliente').attr('readonly',false);
  	$('#ddl_cliente_proveedor').attr('disabled',false);
  	$('#ddl_cliente_proveedor').empty();
  	$('#ddl_cliente_proveedor').focus();
  	// limpiar();
  }

  function limpiar()
  {

  	  $("#txt_id").val('');
      $("#txt_ci_ruc").val('');
      $("#txt_codigo").text('');
			$("#txt_fax").val('');
			$("#txt_telefono").val('');
			$("#txt_telefono2").val('');
			$("#txt_celular").val('');
			$("#txt_grupo").val(''); 
			$("#txt_contactos" ).val('');
			$("#txt_descuento").val(0);
			$("#txt_cliente").val('');

			$("#lbl_TD").text('');
			$("#cbx_ContEsp").attr('checked',true);					
			$("#cbx_ContEsp").attr('checked',false);
			$("#cbx_rise").attr('checked',false);						
			$("#cbx_dr").attr('checked',false);
			

			$("#txt_direccion").val('');
			$("#txt_numero").val('');
			$("#txt_email").val('');
		

		 	$("#txt_representante").val('');		 
			$("#txt_no_dep").val('');
			$("#txt_casilla").val('');
			$("#txt_comision").val('');
			// $("#ddl_medidor").val(data.); //revisar
			$("#txt_Email2").val('');
			$("#txt_afiliacion").val('');
			$("#txt_actividad").val('');
			$("#txt_credito").val('');
			$("#txt_profesion").val('');
			$("#txt_lugar_trabajo").val('');
			$("#txt_direccion_tra").val('');
			$("#txt_califica").val('');
			$("#txt_historial_dir").val('');

  }

  function guardar_datos()
  {

  	if($("#txt_ci_ruc").val()=='' ||
		$("#txt_telefono").val()=='' ||
		$("#txt_fax").val()=='' ||
		$("#txt_cliente").val()=='' ||
		$("#txt_direccion").val()=='' ||
		$("#txt_numero").val()=='' ||
		$("#txt_email").val()=='' ||
		$("#ddl_naciones").val()=='' ||
		$("#prov").val()=='' ||
		$("#ddl_ciudad").val()=='')
  	{
  		Swal.fire('Llene los campos en rojo','','info');
  		return false;
  	}

  	var td = $('#lbl_TD').text();
  	var codigo = $('#txt_codigo').text();

  	var datos = $('#form_datos').serialize();
  	var datos = datos+'&TD='+td+'&txt_codigo='+codigo;

  	console.log(datos);
  	$.ajax({
		  url: '../controlador/farmacia/proveedor_bodegaC.php?guardar_datos=true',
		  type:'post',
		  dataType:'json',
		  data:datos,
		  success: function(response){
		  	if(response==1)
		  	{
		  		Swal.fire('Datos Guardados','','success').then(function(){
		  			$('#txt_cliente').attr('readonly',true);
		  		});
		  		console.log(response);
		  	}else if(response==-2)
		  	{		  		
		  		Swal.fire('C.I / R.U.C Existente','','info').then(function(){
		  			$('#txt_cliente').attr('readonly',true);
		  			$('#ddl_cliente_proveedor').attr('disabled',false);
		  			$('#ddl_cliente_proveedor').focus();

		  		});
		  	}
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


  function cargar_cuentas(tipo)
  {
  	if($('#txt_id').val()=='')
  	{
  		Swal.fire('Selecione un registro','','info');
  		return false;
  	}
  	$('#modal_cuentas').modal('show');

  	$('#txt_nombre_cuenta').val($('#txt_cliente').val());
  	$('#txt_ci_cuenta').val($('#txt_codigo').text());
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

  function codigo()
  {
  	 var ci = $('#txt_ci_ruc').val();
  	 if(ci!='')
  	 {
     $.ajax({
      url:   '../controlador/modalesC.php?codigo=true',      
      type:'post',
      dataType:'json',
      data:{ci:ci},
      success: function(response){     	
      	console.log(response);
      	$('#txt_codigo').text(response.Codigo);
      	$('#lbl_TD').text(response.Tipo);
        
      }
    });
   }else
   {
   	 limpiar();
   }
 }


 function eliminar_cliente()
 {
 	var id = $('#txt_id').val();
 		if(id=='')
 		{
 			Swal.fire('Sleccione un cliente','','error');
 			return false;
 		}
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
    		 eliminar(id);
    	}
    })
 }
 function eliminar(id)
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
        // lista_proveedores();
      // console.log(response);
    }
    });
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


</script>
 <div class="row">
    <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=28" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>      
		    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
			      <button  title="Nuevo Registro" data-toggle="tooltip" class="btn btn-default" onclick="nuevo_cliente();">
			        <img src="../../img/png/mostrar.png" >
			      </button>
		    </div>
		    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
			      <button  title="Modificar Registro" data-toggle="tooltip" class="btn btn-default" onclick="modificar_cliente();">
			        <img src="../../img/png/edit_file.png" >
			      </button>
		    </div>
		    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
			      <button  title="Eliminar Registro" data-toggle="tooltip" class="btn btn-default" onclick="eliminar_cliente()">
			        <img src="../../img/png/delete_file.png" >
			      </button>
		    </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
		      <button title="Guardar y generar Comprobante"  class="btn btn-default" onclick="guardar_datos();">
		        <img src="../../img/png/grabar.png" >
		      </button>
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
 </div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-primary">
			<form id="form_datos">
			<div class="panel-body">
				<div class="col-sm-6" id="panel_ddl">
					<b>Cliente / proveedor</b>
					<input type="hidden" name="txt_id" id="txt_id" value="">
					<select class="form-control input-sm" id="ddl_cliente_proveedor" name="ddl_cliente_proveedor">
						<option value="">Seleccione provincia</option>
					</select>
					<br>
					<b class="text-danger">* SON ITEMS OBLIGATORIOS</b>
				</div>
				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-4">
						<b class="text-danger">*C.I. / R.U.C [<span id="lbl_TD"></span>]</b>
						<input type="" name="txt_ci_ruc" id="txt_ci_ruc" class="form-control input-sm" onblur="codigo();">
					</div>	
					<div class="col-sm-4">
						<b class="text-danger">*TELEFONO</b>
						<input type="" name="txt_telefono" id="txt_telefono" class="form-control input-sm">
					</div>	
					<div class="col-sm-4">
						<b class="text-danger">*FAX</b>
						<input type="" name="txt_fax" id="txt_fax" class="form-control input-sm">
					</div>	
					<div class="col-sm-4">
						<b>TELEFONO</b>
						<input type="" name="txt_telefono2" id="txt_telefono2" class="form-control input-sm">
					</div>	
					<div class="col-sm-4">
						<b>CELULAR</b>
						<input type="" name="txt_celular" id="txt_celular" class="form-control input-sm">
					</div>	
					<div class="col-sm-4">
						<b class="text-danger">*GRUPO #</b>
						<input type="" name="txt_grupo" id="txt_grupo" class="form-control input-sm">
					</div>
					<div class="col-sm-9">
						<b>CONTACTOS</b>
						<input type="" name="txt_contactos" id="txt_contactos" class="form-control input-sm">
					</div>	
					<div class="col-sm-3">
						<b>Descuento</b>
						<input type="" name="txt_descuento" id="txt_descuento" class="form-control input-sm" value="0">
					</div>		
						
					</div>
					
				</div>			
				<div class="col-sm-4">
					<b class="text-danger">*APELLIDOS / NOMBRES</b>
					<div class="pull-right"  name="txt_codigo" id="txt_codigo" ></div>
					<input type="" name="txt_cliente" id="txt_cliente" class="form-control form-control-sm" readonly onblur="$('#txt_cliente').val(this.value.ucwords());">
					<!-- <input type="" name="txt_codigo" id="txt_codigo" class="form-control form-control-sm" readonly> -->
				</div>
				<div class="col-sm-3">
					<b class="text-danger">*TIPO PROV. Y PARTE RELAC.</b>
					<div class="row">
						<div class="col-sm-9">
							<select class="form-control input-sm" id="CTipoProv" name="CTipoProv">
								<option value="00">OTRO</option>
								<option value="01">PERSONA NATURAL</option>
								<option value="02">SOCIEDAD</option>							
							</select>
						</div>
						<div class="col-sm-3" style="padding: 0px;">
							<select class="form-control input-sm" id="CParteR" name="CParteR">
								<option value="SI">SI</option>
								<option value="NO" selected>NO</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-2" style="padding-top: 10px;">	
						<label class="text-danger" style="margin:0px"><input type="radio" name="rbl_sexo" id="rbl_M" value="M" checked> Masculino</label>
						<label class="text-danger" style="margin:0px"><input type="radio" name="rbl_sexo" id="rbl_F" value="F" class=""> Femenino</label>
				</div>
				<div class="col-sm-1" style="padding: 10px 0px 0px 0px;"> 
						<label class="text-danger"  style="margin:0px"><input type="checkbox" name="cbx_ContEsp" id="cbx_ContEsp" class=""> Cont.Esp.</label><br>
						<label class="text-danger"  style="margin:0px"><input type="checkbox" name="cbx_rise" id="cbx_rise" class=""> RISE</label>
				</div>
				<div class="col-sm-1"><br>					
						<label class="text-danger"><input type="checkbox" name="cbx_dr" id="cbx_dr" class=""> Dr.</label>
				</div>
				<div class="col-sm-4">
					<b class="text-danger">*DIRECCION</b>
					<input type="" name="txt_direccion" class="form-control input-sm" id="txt_direccion">
				</div>
				<div class="col-sm-4">
					<b class="text-danger">* NUMERO</b>
					<input type="" name="txt_numero" class="form-control input-sm" id="txt_numero">
				</div>
				<div class="col-sm-4">
					<b class="text-danger">*EMAIL (CORREO ELECTRONICO)</b>
					<input type="" name="txt_email" class="form-control input-sm" id="txt_email">
				</div>
				<div class="col-sm-3">
					<b class="text-danger">* NACIONALIDAD</b>
					<select class="form-control input-sm" id="ddl_naciones" name="ddl_naciones" onchange="provincias(this.value)">
						<option>Seleccione</option>
					</select>
				</div>
				<div class="col-sm-3">
					<b class="text-danger">* PROVINCIA</b>
					<select class="form-control input-sm" id="prov" name="prov" onchange="ciudad(this.value)">
						<option>Seleccione</option>
					</select>
				</div>
				<div class="col-sm-2">
					<b class="text-danger">* CIUDAD</b>
					<select class="form-control input-sm" id="ddl_ciudad" name="ddl_ciudad">
						<option value="">Seleccione</option>
					</select>
				</div>
				<div class="col-sm-2">
					<b>FECHA APE.</b>					
					<input type="date" name="MBFecha" class="form-control input-sm" id="MBFecha">
				</div>
				<div class="col-sm-2">
					<b>FECHA NAC.</b>					
					<input type="date" name="MBFechaN" class="form-control input-sm" id="MBFechaN">
				</div>
				<div class="col-sm-4">
					<b>REPRESENTANTE</b>					
					<input type="" name="txt_representante" id="txt_representante" class="form-control input-sm">
				</div>
				<div class="col-sm-2">
					<b>EST. CIVIL</b>
					<select class="form-control input-sm" id="ddl_estado_civil" name="ddl_estado_civil">
						<option value="C">Casado</option>
					  <option value="D">Divorciado</option>
					  <option value="S" selected>Soltero</option>
					  <option value="V">Viudo</option>
					  <option value="O">Otro</option>
					</select>
				</div>
				<div class="col-sm-1">
					<b>No. DEP</b>					
					<input type="" name="txt_no_dep" id="txt_no_dep" class="form-control input-sm">
				</div>
				<div class="col-sm-2">
					<b>CASILLA POS.</b>					
					<input type="" name="txt_casilla" id="txt_casilla" class="form-control input-sm">
				</div>
				<div class="col-sm-1">
					<b>COMISION</b>					
					<input type="" name="txt_comision" id="txt_comision" class="form-control input-sm">
				</div>
				<div class="col-sm-2">
					<b>Medidor No.</b>					
					<select class="form-control input-sm" id="ddl_medidor" name="ddl_medidor">
						<option>Seleccione</option>
					</select>
				</div>
				<div class="col-sm-4">
					<b>EMAIL2 (CORREO ELECTRONICO 2)</b>					
					<input type="" name="txt_Email2" id="txt_Email2" class="form-control input-sm">
				</div>
				<div class="col-sm-3">
					<b>PLAN AFILIACION</b>					
					<input type="" name="txt_afiliacion" id="txt_afiliacion" class="form-control input-sm">
				</div>
				<div class="col-sm-3">
					<b>ACTIVIDAD (CTE/AHO)</b>					
					<input type="" name="txt_actividad" id="txt_actividad" class="form-control input-sm">
				</div>
				<div class="col-sm-2">
					<b>CREDITOS</b>					
					<input type="" name="txt_credito" id="txt_credito" class="form-control input-sm">
				</div>
				<div class="col-sm-3">
					<b>PROFESION</b>					
					<input type="" name="txt_profesion" id="txt_profesion" class="form-control input-sm">
				</div>
				<div class="col-sm-3">
					<b>LUGAR TRABAJO</b>					
					<input type="" name="txt_lugar_trabajo" id="txt_lugar_trabajo" class="form-control input-sm">
				</div>
				<div class="col-sm-4">
					<b>DIRECCION DEL TRABAJO</b>					
					<input type="" name="txt_direccion_tra" id="txt_direccion_tra" class="form-control input-sm">
				</div>
				<div class="col-sm-2">
					<b>CALIFICA</b>					
					<input type="" name="txt_califica" id="txt_califica" class="form-control input-sm">
				</div>
				<div class="col-sm-6">
					<b>HISTORIAL DE DIRECCIONES</b>	
					<div name="txt_historial_dir" id="txt_historial_dir"></div>				
					</div>
				<div class="col-sm-6">
					<b>PRODUCTOS RELACIONADOS</b>					
					<textarea  name="txt_productos_rela" id="txt_productos_rela" class="form-control input-sm" rows="3"></textarea>
				</div>

			</div>
			</form>			
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
