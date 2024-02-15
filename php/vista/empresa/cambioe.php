<?php  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION);die();//print_r($_SESSION['INGRESO']);die();?>
<script type="text/javascript">
  $(document).ready(function () 
  {
  	ddl_estados();
  	 ddl_naciones();
  	$('#ciudad').select2();
  	autocmpletar_entidad(); 

  	 $('#entidad').on('select2:select', function (e) {
  	 	// console.log(e);
      var data = e.params.data.data;
      $('#lbl_ruc').html(data.RUC_CI_NIC);
      if(data.ID_Empresa.length<3 && data.ID_Empresa.length>=2)
      {
      	var item = '0'+data.ID_Empresa;
      }else if(data.ID_Empresa.length<2)
      {
      	var item = '00'+data.ID_Empresa
      }
      $('#lbl_enti').html(item);
     
      // console.log(data);
    });	

  	 $('#file_firma').on('change', function() {

  	 	dato = this.files[0].name;
  	 	$('#TxtEXTP12').val(dato);

  	 })


  	 // $('#empresas').on('select2:select', function (e) {
  	 // 	  var data = e.params.data.data;
  	 // 	  console.log(data);
  	 // })


});

  function ddl_estados()
  {
     $.ajax({
      url: '../controlador/empresa/cambioeC.php?ddl_estados=true',
      type:'post',
      dataType:'json',
     // data:{:},     
      success: function(response){

      	$('#Estado').html(response);
     
      // console.log(response);
    }
    });

  }


  function cargar_imgs()
  {
  	$.ajax({
      url: '../controlador/empresa/cambioeC.php?cargar_imgs=true',
      type:'post',
      dataType:'json',
     // data:{:},     
      success: function(response){

      $('#ddl_img').html(response);
      // console.log(response);
    }
    });
  }

  function ddl_naciones()
  {
     $.ajax({
      url: '../controlador/empresa/cambioeC.php?ddl_nacionalidades=true',
      type:'post',
      dataType:'json',
     // data:{:},     
      success: function(response){      
      	// console.log(response);
      	var opNaciones = '<option value="">Seleccione Pais</option>';
      	response.forEach(function(item,i){
      		opNaciones+='<option value="'+item.Codigo+'">'+item.Descripcion_Rubro+'</option>';
      	})
      	$('#ddl_naciones').html(opNaciones);
     
   	  }
    });

  }


  function subir_img()
  {
     var fileInput = $('#file_img').get(0).files[0];    
      if(fileInput=='')
      {
        Swal.fire('','Seleccione una imagen','warning');
        return false;
      }
      $('#myModal_espera').modal('show');
      var formData = new FormData(document.getElementById("form_empresa"));
         $.ajax({
            url: '../controlador/empresa/cambioeC.php?cargar_imagen=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
            success: function(response) {
               if(response==-1)
               {
                 Swal.fire(
                  '',
                  'Algo extraño a pasado intente mas tarde.',
                  'error')

               }else if(response ==-2)
               {
                  Swal.fire(
                  '',
                  'Asegurese que el archivo subido sea una imagen.',
                  'error')
               }else if(response==-3)
               {
               	 Swal.fire(
                  'El nombre del logo es muy extenso',
                  '',
                  'error');

               }else
               {
               	datos_empresa();
               } 
               $('#myModal_espera').modal('hide');
            }
        });

  }

function subir_firma()
  {
     var fileInput = $('#file_firma').get(0).files[0];    
      if(fileInput=='')
      {
        Swal.fire('','Seleccione una imagen','warning');
        return false;
      }
      // $('#myModal_espera').modal('show');
      var formData = new FormData(document.getElementById("form_empresa"));
         $.ajax({
            url: '../controlador/empresa/cambioeC.php?cargar_firma=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
            success: function(response) {

      			// $('#myModal_espera').modal('hide');
               if(response==-1)
               {
                 Swal.fire(
                  '',
                  'Algo extraño a pasado intente mas tarde.',
                  'error')

               }else if(response ==-2)
               {
                  Swal.fire(
                  '',
                  'Asegurese que el archivo subido sea certificado (.p12) valido')
               }
            }
        });

  }

  // async function cargar_tb2()
  // {
  // 	$('#myModal_espera').modal('show');
// 	  	await datos_empresa();
// 	  	setTimeout(setear_Tab2, 2000);	  
  // 	$('#myModal_espera').modal('hide');	
  // }
  // async function cargar_tb3()
  // {
  // 	$('#myModal_espera').modal('show');
// 	  	await datos_empresa();
// 	  	setTimeout(setear_Tab3, 2000);	  
  // 	$('#myModal_espera').modal('hide');	
  // }
  // function setear_Tab2()
  // {
  // 		$(".active").removeClass("active");
// 	    $('.nav-tabs li').find('a[href="#tab_2"]').parent('li').addClass('active'); 
// 	    $('#tab_2').addClass("active");
  // }
  // function setear_Tab3()
  // {
  // 		$(".active").removeClass("active");
// 	    $('.nav-tabs li').find('a[href="#tab_3"]').parent('li').addClass('active'); 
// 	    $('#tab_3').addClass("active");
  // }

  function provincias(pais,callback)
  {
   var option ="<option value=''>Seleccione Provincia</option>"; 
     $.ajax({
      url: '../controlador/empresa/cambioeC.php?provincias=true',
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
         if (callback && typeof callback === 'function') {
	        callback();
	      }
      // console.log(response);
    }
    });
  }


  function subdireccion()
{
    var txtsubdi = $('#TxtSubdir').val();
    $.ajax
    ({
        data:  {txtsubdi:txtsubdi},
        url:   '../controlador/empresa/crear_empresaC.php?subdireccion=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) 
        { 
            if(response == null)
            {
                Swal.fire('Este directorio ya existe seleccione otro','','error');
                $('#TxtSubdir').val('');
            }
            else{
                // console.log(response);
            $('#TxtSubdir').val(response);
            }
        }
    });
}

function MostrarUsuClave() 
{
    if($('#AsigUsuClave').prop('checked'))
    {
        $('#TxtUsuario').css('display','block');
        $('#lblUsuario').css('display','block');
        $('#TxtClave').css('display','block');
        $('#lblClave').css('display','block');
        TraerUsuClave();
    }else
    {
        $('#TxtUsuario').css('display','none');
        $('#lblUsuario').css('display','none');
        $('#TxtClave').css('display','none');
        $('#lblClave').css('display','none');
    }
}
function TraerUsuClave()
{
    var form = $('#TxtCI').val();
        $.ajax({
            data:{form:form},//son los datos que se van a enviar por $_POST
            url: '../controlador/empresa/crear_empresaC.php?traer_usuario=true',//los datos hacia donde se van a enviar el envio por url es por GET
            type:'post',//envio por post
            dataType:'json',
            success: function(response){
                // console.log(response);
                $('#TxtUsuario').val(response[0]['Usuario']);
                $('#TxtClave').val(response[0]['Clave']);
            }
        });
}

function autocompletarCempresa(){
        $('#ListaCopiaEmpresa').select2({
        placeholder: 'Seleccionar copia empresa',
        ajax: {
            url: '../controlador/empresa/crear_empresaC.php?Copiarempresas=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

  function ciudad_l(idpro,callback)
	{
		// console.log(idpro);
		var option ="<option value=''>Seleccione Ciudad</option>"; 
		if(idpro !='')
		{
		   $.ajax({
			  url: '../controlador/empresa/cambioeC.php?ciudad2=true',
			  type:'post',
			  dataType:'json',
			  data:{idpro:idpro},
			  success: function(response){
				response.forEach(function(data,index){
					option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
				});
	            $('#ddl_ciudad').html(option);
	             if (callback && typeof callback === 'function') {
			        callback();
			      }
			}
		  });
		 } 

	}


 function autocmpletar_entidad()
 {
	$('#entidad').select2({
	  placeholder: 'Seleccione una Entidad',
	  ajax: {
	    url: '../controlador/empresa/niveles_seguriC.php?entidades=true',
	    dataType: 'json',
	    delay: 250,
	    processResults: function (data) {
	      return {
	        results: data
	      };
	    },
	    cache: true
	  }
	});
 }

  function buscar_ciudad()
  {
  	var parametros = 
  	{
  		'entidad':$('#entidad').val(),
  	}
  	$.ajax({
		type: "POST",
		 url: '../controlador/empresa/cambioeC.php?ciudad=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			llenarComboList(data,'ciudad');
		}
	});
  }

 function buscar_empresas()
 {
 	var ciu = $('#ciudad').val();
 	var ent = $('#entidad').val();
	$('#empresas').select2({
	  placeholder: 'Seleccione una Empresa',
	  ajax: {
	    url: '../controlador/empresa/cambioeC.php?empresas=true&ciu='+ciu+'&ent='+ent,
	    dataType: 'json',
	    delay: 250,
	    processResults: function (data) {
	      return {
	        results: data
	      };
	    },
	    cache: true
	  }
	});
 }

 
function cambiarEmpresa()
{
	// $('#myModal_espera').modal('show');
	var parametros = $('#form_empresa').serialize();
	var parametros = parametros+'&ciu='+$('#ddl_ciudad option:selected').text();
	$.ajax({
		type: "POST",
		 url: '../controlador/empresa/cambioeC.php?editar_datos_empresa=true',
		data:parametros,
		dataType:'json',
		success: function(data)
		{	
			$('#myModal_espera').modal('hide');

			if($('#file_firma').val()!='')
			{
				subir_firma();
			}
			if(data==1)
			{
				Swal.fire('Empresa modificada con exito ','','success').then(function(){
					 datos_empresa();
				});

			}else
			{
				Swal.fire('Intente mas tarde '+data,'','error');
			}

		}
	});
}

function mmasivo()
{
	var parametros = $('#form_empresa').serialize();
	$.ajax({
		type: "POST",
		 url: '../controlador/empresa/cambioeC.php?mensaje_masivo=true',
		data: parametros,
		dataType:'json',
		success: function(data)
		{
			if(data==1)
			{
				Swal.fire('Mensaje modificado en las entidades con exito ','','success');
			}else
			{
				Swal.fire('Intente mas tarde','','error');
			}
			
		}
	});
}
function mgrupo()
{
	var parametros = $('#form_empresa').serialize();
	$.ajax({
		type: "POST",
		 url: '../controlador/empresa/cambioeC.php?mensaje_grupo=true',
		data: parametros,
		dataType:'json',
		success: function(data)
		{
			if(data==1)
			{
				Swal.fire('Mensaje modificado en las entidades con exito ','','success');
			}else
			{
				Swal.fire('Intente mas tarde','','error');
			}
			
		}
	});

}
function mindividual()
{
	var parametros = $('#form_empresa').serialize();
	$.ajax({
		type: "POST",
		 url: '../controlador/empresa/cambioeC.php?mensaje_indi=true',
		data: parametros,
		dataType:'json',
		success: function(data)
		{
			if(data==1)
			{
				Swal.fire('Mensaje a entidad modificado con exito ','','success');
			}else
			{
				Swal.fire('Intente mas tarde','','error');
			}
			
		}
	});
}

function cambiarEmpresaMa()
{
	$('#myModal_espera').modal('show');
	var parametros = $('#form_empresa').serialize();
	$.ajax({
		type: "POST",
		 url: '../controlador/empresa/cambioeC.php?guardar_masivo=true',
		data: parametros,
		dataType:'json',

		success: function(data)
		{
			if(data==1)
			{
				Swal.fire('Entidad modificada con exito.','','success');
			}else
			{
				Swal.fire('Intente mas tarde','','error');
			}	

			$('#myModal_espera').modal('hide');		
		}
	});
}
function mostrarEmpresa()
{
	$('#reporte_excel').css('display','initial');
	$('#form_empresa').css('display','none');
	$('#form_vencimiento').css('display','initial');
}
function cerrarEmpresa()
{
	$('#reporte_excel').css('display','none');
	$('#form_empresa').css('display','initial');
	$('#form_vencimiento').css('display','none');
}

function consultar_datos(reporte = null)
{
	let desde= $('#desde').val();
	let hasta= $('#hasta').val();
	///alert(desde.value+' '+hasta.value);
	var parametros =
	{
		'desde':desde,
		'hasta':hasta,
		'repor': reporte,			
	}
	$.ajax({
		data:  {parametros:parametros},
		url:   '../controlador/contabilidad/contabilidad_controller.php?consultar=true',
		type:  'post',
		dataType: 'json',
		beforeSend: function () {	
			 $('#myModal_espera').modal('show');
		},
		success:  function (response) {
				// console.log(response);
		var tr ='';
		response.forEach(function(item,i)
		{
			tr+='<tr><td>'+item.tipo+'</td><td>'+item.Item+'</td><td>'+item.Empresa+'</td><td>'+item.Fecha+'</td><td>'+item.enero+'</td></tr>';
		})
				$('#tbl_vencimiento').html(tr);
				$('#myModal_espera').modal('hide');
		}
	});
	//document.getElementById('desde').value=desde;
   // document.getElementById('hasta').value=hasta;
}

function reporte()
{
	let desde= $('#desde').val();
	let hasta= $('#hasta').val();
	var tit = 'Reporte de vencimiento';
	var url = ' ../controlador/contabilidad/contabilidad_controller.php?consultar_reporte=true&desde='+desde+'&hasta='+hasta+'&repor=2';
	window.open(url,'_blank');
}

function asignar_clave()
{
	if($('#entidad').val()==''){Swal.fire('Seleccione una entidad','','info');return false;}
	// if($('#ciudad').val()==''){Swal.fire('Seleccione una Ciudad','','info');return false;}
	if($('#empresas').val()==''){Swal.fire('Seleccione una empresa','','info');return false;}
	var parametros = $('#form_empresa').serialize();
	$.ajax({
		type: "POST",
		 url: '../controlador/empresa/cambioeC.php?asignar_clave=true',
		data: parametros,
		dataType:'json',
		beforeSend: function () {	
			 $('#myModal_espera').modal('show');
		},
		success: function(data)
		{

			$('#myModal_espera').modal('hide');
			if(data==1)
			{
				Swal.fire('Credenciales de comprobantes electronicos Asignados.','','success');
			}else
			{
				Swal.fire('Intente mas tarde','','error');
			}		

		}
	});
}

function AmbientePrueba()
{
    $('#TxtWebSRIre').val('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl');
    $('#TxtWebSRIau').val('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl');
}
function AmbienteProduccion()
{
    $('#TxtWebSRIre').val('https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl');
    $('#TxtWebSRIau').val('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl');
}

function cargar_img()
{
	var img  = $('#ddl_img').val();
	// console.log(img)
	$('#img_logo').prop('src','../../img/logotipos/'+img)
}


async function datos_empresa()
  {
  	$('#myModal_espera').modal('show');
  	var sms = !!document.getElementById("Mensaje");
  	if(sms==false)
  	{
  		sms='';
  	}else
  	{
  		sms = $('#Mensaje').val();
  	}
  	var parametros = 
  	{
  		'empresas':$('#empresas').val(),
  		'sms':sms,
  	}
  	$.ajax({
		type: "POST",
		 url: '../controlador/empresa/cambioeC.php?datos_empresa=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			console.log(data);
			empresa = data.empresa1[0];
			empresa2 = '';
			contribuyente = '';
			if(data.empresa2.length>0)
			{
				empresa2 = data.empresa2[0];
			}
			if(data.tipoContribuyente.length>0)
			{
				contribuyente = data.tipoContribuyente[0];
			}
			

			console.log(data.empresa2);
			console.log(contribuyente);
			 limpiar_tabs();
			$('#datos_empresa').html(data.datos);
			$('#ci_ruc').val(data.ci);

			// -------------------tab1--------------------

			$('#Estado').val(empresa.Estado);
			$('#FechaR').val(empresa.Fecha);
			$('#FechaCE').val(empresa.Fecha_CE);
			$('#FechaDB').val(empresa.Fecha_DB);
			$('#FechaP12').val(empresa.Fecha_P12);
			$('#ci_ruc').val(empresa.RUC_CI_NIC);

			$('#Servidor').val(empresa.IP_VPN_RUTA);
			$('#Base').val(empresa.Base_Datos);
			$('#Usuario').val(empresa.Usuario_DB);
			$('#Clave').val(empresa.Contrasena_DB);
			$('#Motor').val(empresa.Tipo_Base);
			$('#Puerto').val(empresa.Puerto);
			$('#Plan').val(empresa.Tipo_Plan);
			$('#Mensaje').val(empresa.Mensaje);

			//----------------fin tab 1---------------------

			$('#myModal_espera').modal('hide');

			if (empresa2 == '') 
			{						
				console.log('fola')
				$('#txt_sqlserver').val(0);
				Swal.fire('Esta empresa no tiene una configuracion SQL server','','warning');
				$('#li_tab1').addClass('active');
				$('#tab_1').addClass('active');	

				$('#li_tab2').css('display','none');
				$('#tab_2').removeClass('active');	
				$('#li_tab3').css('display','none');
				$('#tab_3').removeClass('active');
				$('#li_tab4').css('display','none');
				$('#tab_4').removeClass('active');
				return false

			}else
			{

				$('#txt_sqlserver').val(1);
				$('#li_tab2').css('display','initial');
				$('#li_tab2').removeClass('active');	
				$('#li_tab3').css('display','initial');
				$('#li_tab3').removeClass('active');	
				$('#li_tab4').css('display','initial');
				$('#li_tab4').removeClass('active');	
			}


			//----------------- tab 2 ----------------------
			$('#TxtEmpresa').val(empresa2.Empresa);
			$('#lbl_item').text(empresa2.Item);
			$('#TxtRazonSocial').val(empresa2.Razon_Social);
			$('#TxtNomComercial').val(empresa2.Nombre_Comercial);
			$('#TxtRuc').val(empresa2.RUC);
			$('#ddl_obli').val(empresa2.Obligado_Conta);
			$('#TxtRepresentanteLegal').val(empresa2.Gerente);
			$('#TxtCI').val(empresa2.CI_Representante);

			$('#ddl_naciones').val(empresa2.CPais);			
			provincias(empresa2.CPais,function () {
				$('#prov').val(empresa2.CProv);
			});

			var numero = parseFloat(empresa2.Ciudad);
			if (!isNaN(numero)) {
			   ciudad_l(empresa2.CProv,function(){
					$('#ddl_ciudad').val(empresa2.Ciudad);
				})
			} else {		
				ciudad_l(empresa2.CProv,function(){
					$('#ddl_ciudad').val(21701);
				})	    
			}
			
			$('#TxtDirMatriz').val(empresa2.Direccion);
			$('#TxtEsta').val(empresa2.Establecimientos);
			$('#TxtTelefono').val(empresa2.Telefono1);
			$('#TxtTelefono2').val(empresa2.Telefono2);
			$('#TxtFax').val(empresa2.FAX);
			$('#TxtMoneda').val('USD');
			$('#TxtNPatro').val(empresa2.No_Patronal);
			$('#TxtCodBanco').val(empresa2.CodBanco);
			$('#TxtTipoCar').val(empresa2.Tipo_Carga_Banco);
			$('#TxtAbrevi').val(empresa2.Abreviatura);
			$('#TxtEmailEmpre').val(empresa2.Email);
			$('#TxtEmailConta').val(empresa2.Email_Contabilidad);
			$('#TxtEmailRespa').val(empresa2.Email_Respaldos);
			$('#TxtSegDes1').val(empresa2.Seguro);
			$('#TxtSegDes2').val(empresa2.Seguro2);
			$('#TxtSubdir').val(empresa2.SubDir);
			$('#TxtNombConta').val(empresa2.Contador);
			$('#TxtRucConta').val(empresa2.RUC_Contador);
			//------------- fin tab 2 ------------------

			//---------------- tab 3 -------------------
			cargar_imgs();			
			autocompletarCempresa();
			$('#ASDAS').prop('checked', false);
			$('#MFNV').prop('checked', false);
			$('#MPVP').prop('checked', false);
			$('#IRCF').prop('checked', false);
			$('#IMR').prop('checked', false);
			$('#IRIP').prop('checked', false);
			$('#PDAC').prop('checked', false);
			$('#RIAC').prop('checked', false);

			if(empresa2.Det_SubMod==1){
				$('#ASDAS').prop('checked', true);
			}
			if(empresa2.Mod_Fact==1){
				$('#MFNV').prop('checked', true);
			}
			if(empresa2.Mod_PVP==1){
				$('#MPVP').prop('checked', true);
			}
			if(empresa2.Imp_Recibo_Caja==1){
				$('#IRCF').prop('checked', true);
			}			
			if(empresa2.Medio_Rol==1){
				$('#IMR').prop('checked', true);
			}
			if(empresa2.Rol_2_Pagina==1){
				$('#IRIP').prop('checked', true);
			}
			if(empresa2.Det_Comp==1){
				$('#PDAC').prop('checked', true);
			}
			if(empresa2.Registrar_IVA==1){
				$('#RIAC').prop('checked', true);
			}

			if(empresa2.Logo_Tipo_url!='' && empresa2.Logo_Tipo_url!='.')
			{
				$('#img_logo').prop('src',empresa2.Logo_Tipo_url);
			}
			$('#img_foto_name').text(empresa2.Logo_Tipo);
			if(empresa2.Num_CD==1){ $('#DM').prop('checked', true); }else{ $('#DS').prop('checked', true); }

            if(empresa2.Num_CI==1){ $('#IM').prop('checked', true); }else{ $('#IS').prop('checked', true); }

            if(empresa2.Num_CE==1){ $('#EM').prop('checked', true); }else{ $('#ES').prop('checked', true); }

            if(empresa2.Num_ND==1){ $('#NDM').prop('checked', true); }else{ $('#NDS').prop('checked', true); }

            if(empresa2.Num_NC==1){ $('#NCM').prop('checked', true); }else{ $('#NCS').prop('checked', true); }

			$('#TxtServidorSMTP').val(empresa2.smtp_Servidor);

			 if(empresa2.smtp_UseAuntentificacion==1){ $('#Autenti').prop('checked', true); }else{ $('#Autenti').prop('checked', false);}

            if(empresa2.smtp_SSL==1){ $('#SSL').prop('checked', true); }else{  $('#SSL').prop('checked', false); }
            if(empresa2.smtp_Secure==1){ $('#Secure').prop('checked', true); }else{ $('#Secure').prop('checked', false); }
			$('#TxtPuerto').val(empresa2.smtp_Puerto);
			$('#TxtPVP').val(empresa2.Dec_PVP);
			$('#TxtCOSTOS').val(empresa2.Dec_Costo);
			$('#TxtIVA').val(empresa2.Dec_IVA);
			$('#TxtCantidad').val(empresa2.Dec_Cant);

			// console.log(contribuyente)

			if(contribuyente!='')
			{
				$('#TxtRucTipocontribuyente').val(contribuyente.RUC)
		 	 	$('#TxtZonaTipocontribuyente').val(contribuyente.Zona)
		 	 	$('#TxtAgentetipoContribuyente').val(contribuyente.Agente_Retencion);
		 	 }

	 	 	$('#rbl_ContEs').prop('checked',false)
		 	$('#rbl_rimpeE').prop('checked',false)
		 	$('#rbl_rimpeP').prop('checked',false)
		 	$('#rbl_regGen').prop('checked',false)
		 	$('#rbl_rise').prop('checked',false)
		 	$('#rbl_micro2020').prop('checked',false)
		 	$('#rbl_micro2021').prop('checked',false)

	 	 	if(contribuyente.Contribuyente_Especial==1){
	 	 		$('#rbl_ContEs').prop('checked',true)
	 	 	}

	 	 	if(contribuyente.RIMPE_E==1){
		 		$('#rbl_rimpeE').prop('checked',true)
		 	}

		 	if(contribuyente.RIMPE_P==1){
		 	$('#rbl_rimpeP').prop('checked',true)
		 	}

		 	if(contribuyente.Regimen_General==1){
		 	$('#rbl_regGen').prop('checked',true)
		 	}

		 	if(contribuyente.RISE==1){
		 	$('#rbl_rise').prop('checked',true)
		 	}

		 	if(contribuyente.Micro_2020==1){
		 	$('#rbl_micro2020').prop('checked',true)
		 	}

		 	if(contribuyente.Micro_2021==1){
		 	$('#rbl_micro2021').prop('checked',true)
		 	}

		 



			//---------------------------------fin tab3---------------------

			//-----------------------------tab4-----------------------------

			// console.log(empresa2.Ambiente)			
			if(empresa2.Ambiente=='1')
			{
				$('#optionsRadios1').prop('checked', true);
			}else
			{
				$('#optionsRadios2').prop('checked', true);	
				// console.log('prioduc')			
			}
			$('#TxtContriEspecial').val(empresa2.Codigo_Contribuyente_Especial);
			$('#TxtWebSRIre').val(empresa2.Web_SRI_Recepcion);
			$('#TxtWebSRIau').val(empresa2.Web_SRI_Autorizado);
			$('#TxtEXTP12').val(empresa2.Ruta_Certificado);
			$('#TxtContraExtP12').val(empresa2.Clave_Certificado);
			$('#TxtEmailGE').val(empresa2.Email_Conexion);
			$('#TxtContraEmailGE').val(empresa2.Email_Contraseña);
			$('#TxtEmaiElect').val(empresa2.Email_Conexion_CE);
			$('#TxtContraEmaiElect').val(empresa2.Email_Contraseña_CE);
			if(empresa2.Email_CE_Copia==1 && empresa2.Email_Procesos!=''){	$('#rbl_copia').prop('checked', true); }
			$('#TxtCopiaEmai').val(empresa2.Email_Procesos);
			$('#TxtRUCOpe').val(empresa2.RUC_Operadora);
			$('#txtLeyendaDocumen').val(empresa2.LeyendaFA);
			$('#txtLeyendaImpresora').val(empresa2.LeyendaFAT);
			

		},error: function (jqXHR, textStatus, errorThrown) {
           $('#myModal_espera').modal('hide');
          }

	});
  }


  function limpiar_tabs()
  {
		$('#TxtEmpresa').val('.');
		$('#lbl_item').text('');
		$('#TxtRazonSocial').val('.');
		$('#TxtNomComercial').val('.');
		$('#TxtRuc').val('.');
		$('#ddl_obli').val('');
		$('#TxtRepresentanteLegal').val('.');
		$('#TxtCI').val('.');

		$('#ddl_naciones').val('');			
		$('#prov').val('');
		$('#ddl_ciudad').val('');
		
		$('#TxtDirMatriz').val('.');
		$('#TxtEsta').val('.');
		$('#TxtTelefono').val('.');
		$('#TxtTelefono2').val('.');
		$('#TxtFax').val('.');
		$('#TxtMoneda').val('USD');
		$('#TxtNPatro').val('.');
		$('#TxtCodBanco').val('.');
		$('#TxtTipoCar').val('.');
		$('#TxtAbrevi').val('.');
		$('#TxtEmailEmpre').val('.');
		$('#TxtEmailConta').val('.');
		$('#TxtEmailRespa').val('.');
		$('#TxtSegDes1').val('.');
		$('#TxtSegDes2').val('.');
		$('#TxtSubdir').val('.');
		$('#TxtNombConta').val('.');
		$('#TxtRucConta').val('.');
		//------------- fin tab 2 ------------------

		//---------------- tab 3 -------------------
		$('#ASDAS').prop('checked', false);
		$('#MFNV').prop('checked', false);
		$('#MPVP').prop('checked', false);
		$('#IRCF').prop('checked', false);
		$('#IMR').prop('checked', false);
		$('#IRIP').prop('checked', false);
		$('#PDAC').prop('checked', false);
		$('#RIAC').prop('checked', false);
		$('#img_logo').prop('src','../../img/logotipos/'+empresa2.Logo_Tipo+'.png');

		
		$('#DM').prop('checked', false); 
		$('#DS').prop('checked', false);
        $('#IM').prop('checked', false); 
        $('#IS').prop('checked', false);
        $('#EM').prop('checked', false); 
        $('#ES').prop('checked', false);
        $('#NDM').prop('checked', false );
        $('#NDS').prop('checked', false);
        $('#NCM').prop('checked', false );
        $('#NCS').prop('checked', false);

		$('#TxtServidorSMTP').val('.');
		$('#Autenti').prop('checked', false);

        $('#SSL').prop('checked', false);
        $('#Secure').prop('checked', false); 
		$('#TxtPuerto').val('.');
		$('#TxtPVP').val('.');
		$('#TxtCOSTOS').val('.');
		$('#TxtIVA').val('.');
		$('#TxtCantidad').val('.');
		//---------------------------------fin tab3---------------------

		//-----------------------------tab4-----------------------------
		
		$('#optionsRadios1').prop('checked', true);
		
		$('#TxtContriEspecial').val('.');
		$('#TxtWebSRIre').val('.');
		$('#TxtWebSRIau').val('.');
		$('#TxtEXTP12').val('.');
		$('#TxtContraExtP12').val('.');
		$('#TxtEmailGE').val('.');
		$('#TxtContraEmailGE').val('.');
		$('#TxtEmaiElect').val('.');
		$('#TxtContraEmaiElect').val('.');
		$('#rbl_copia').prop('checked', true); 
		$('#TxtCopiaEmai').val('.');
		$('#TxtRUCOpe').val('.');
		$('#txtLeyendaDocumen').val('.');
		$('#txtLeyendaImpresora').val('.');
		$('#file_firma').val('');
  }


  function guardarTipoContribuyente()
  {
  	
	 var parametros = 
	 {
	 	 'ruc':$('#TxtRucTipocontribuyente').val(),
	 	 'zona':$('#TxtZonaTipocontribuyente').val(),
	 	 'agente':$('#TxtAgentetipoContribuyente').val(),
	 	 'op1': $('#rbl_ContEs').prop('checked'),
		 'op2': $('#rbl_rimpeE').prop('checked'),
		 'op3': $('#rbl_rimpeP').prop('checked'),
		 'op4': $('#rbl_regGen').prop('checked'),
		 'op5': $('#rbl_rise').prop('checked'),
		 'op6': $('#rbl_micro2020').prop('checked'),
		 'op7': $('#rbl_micro2021').prop('checked'),
	 }
	 $.ajax({
      url: '../controlador/empresa/cambioeC.php?guardarTipoContribuyente=true',
      type:'post',
      dataType:'json',
     data:{parametros:parametros},     
      success: function(response){
      	if(response==1)
      	{
      		Swal.fire('Guardado','','success')
      	}

      // console.log(response);
    }
    });
  }


</script>

  <div class="row">
    <div class="col-lg-8 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Mensaje masivo todas las empresas" onclick='mmasivo();'><img src="../../img/png/masivo.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Mensaje masivo a grupo seleccionado" onclick='mgrupo();'><img src="../../img/png/email_grupo.png" ></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Mensaje solo a empresa" onclick='mindividual();'><img src="../../img/png/mensajei.png" ></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Guardar" onclick="cambiarEmpresa();"><img src="../../img/png/grabar.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Guardar Masivo: Fechas de renovaciones" onclick='cambiarEmpresaMa();'><img src="../../img/png/guardarmasivo.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Mostrar Vencimiento" onclick='mostrarEmpresa();'><img src="../../img/png/reporte_1.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1" >
             <button type="button" class="btn btn-default" title="Asignar credenciales de comprobanmtes electronicos" onclick='asignar_clave();'><img src="../../img/png/credencial_cliente.png"></button>
        </div>
         <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1" id="reporte_excel" style="display:none">
            <a href="#" class="btn btn-default" title="Asignar reserva" id="reporte_exc" onclick="reporte()"><img img src="../../img/png/table_excel.png"></a>
        </div>
         
 </div>
</div>
	<div class="row" id="form_vencimiento" style="display:none;">
		<br>
		<div class="col-sm-1">
			<br>
			<button class="btn btn-default btn-sm" type="button" onclick="cerrarEmpresa()"><i class="fa fa-close"></i> Cerrar</button>
		</div>
		<div class="col-sm-2">
			<b>Desde:</b>
			<input type="date" class="form-control input-sm" id="desde" value="<?php echo date("Y-m-d");?>"  onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
		</div>
		<div class="col-sm-2">
			<b>Hasta</b>
			<input type="date" id="hasta"  class="form-control input-sm"  value="<?php echo date("Y-m-d");?>"  onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);consultar_datos();">
		</div><br>
		<div class="col-sm-12">
			<div class="table-responsive">
				<table class="" id="tbl_style" style="width: 98%;">
					<thead>
						<tr>
							<td>Tipo</td>
							<td>Item</td>
							<td>Empresa</td>
							<td>Fecha</td>
							<td>Enero</td>
						</tr>
					</thead>
					<tbody id="tbl_vencimiento">
						
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<form id="form_empresa">
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<label>Entidad: </label><i id="lbl_ruc"></i>-<i id="lbl_enti"></i>
				<select class="form-control fomr" name="entidad" id='entidad' onChange="buscar_ciudad();">
					<option value=''>Seleccione Entidad</option>
				</select>
			</div>			
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<label for="Entidad">Ciudad</label>
				<select class="form-control input-sm" name="ciudad" id='ciudad' onchange="buscar_empresas()">
					<option value=''>Seleccione ciudad</option>
				</select>
			</div>			
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<label for="Entidad">Empresa</label>
				<select class="form-control input-xs" name="empresas" id='empresas' onchange="datos_empresa()">
					<option value=''>Seleccione Empresa</option>
				</select>
			</div>			
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<label for="Entidad">CI / RUC </label>
				<input type="text" class="form-control input-xs" name="ci_ruc" id="ci_ruc" readonly>
				<input type="hidden" name="txt_sqlserver" id="txt_sqlserver" value="0">
			</div>			
		</div>
	</div>

	<div class="row" id="datos_empresa">
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">					
					<li id="li_tab1" class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Configuracion Principal</a></li>
					<li id="li_tab2" class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Datos Principales</a></li>
					<li id="li_tab3" class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Procesos Generales</a></li>
					<li id="li_tab4" class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">Comprobantes Electrónicos</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
		        		<div class="row">
			        		<div class="col-md-4">
								<div class="form-group">
								    <label for="Estado">Estado</label>
								    <select class="form-control input-sm" name="Estado" id="Estado" >
										<option value=''>Estado</option>
									    <option value="0">Seleccione Estado</option>
									    <!-- $op.= $this->estados(); -->
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
								  <label for="FechaR">Renovación</label>
								   
								  <input type="date" class="form-control input-sm" id="FechaR" name="FechaR" placeholder="FechaR" 
								  value='' onKeyPress="return soloNumeros(event)"  maxlength="10" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
								  <label for="Fecha">Comp. Electronico</label>								   
								  <input type="date" class="form-control input-sm" id="FechaCE" name="FechaCE" placeholder="Fecha" 
								  value="" onKeyPress="return soloNumeros(event)" maxlength="10" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
								</div>
							</div>							
							<div class="col-md-2">
								<div class="form-group">
								  <label for="Fecha_DB">BD</label>
								  <input type="date" class="form-control input-sm" id="FechaDB" name="FechaDB" value="">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
								  <label for="Fecha_P12">Fecha P12</label>
								  <input type="date" class="form-control input-sm" id="FechaP12" name="FechaP12" value="">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
								  <label for="Servidor">Servidor</label>
								  <input type="text" class="form-control input-sm" id="Servidor" name="Servidor" placeholder="Servidor" value="">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
								  <label for="Base">Base</label>
								  <input type="text" class="form-control input-sm" id="Base" name="Base" placeholder="Base" value="">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
								  <label for="Usuario">Usuario</label>								   
								  <input type="text" class="form-control input-sm" id="Usuario" name="Usuario" placeholder="Usuario" value="">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
								  <label for="Clave">Clave</label>
								  <input type="text" class="form-control input-sm" id="Clave" name="Clave" placeholder="Clave" value="">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
								  <label for="Motor">Motor BD</label>
								  <input type="text" class="form-control input-sm" id="Motor" name="Motor" placeholder="Motor" value="">
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
								  <label for="Puerto">Puerto</label>
								   
								  <input type="text" class="form-control input-sm" id="Puerto" name="Puerto" placeholder="Puerto" value="">
								</div>
							</div>				
							<div class="col-md-2">
								<div class="form-group">
								  <label for="Plan">Plan</label>
								   
								  <input type="text" class="form-control input-sm" id="Plan" name="Plan" placeholder="Plan" value="">
								</div>
							</div>
						
							<div class="col-md-12">
								<div class="form-group">
								  <label for="Mensaje">Mensaje</label>
								  <input type="text" class="form-control input-sm" id="Mensaje" name="Mensaje" placeholder="Mensaje" value="">
								</div>
							</div>	        
		        		</div>
		        	</div>

					<div class="tab-pane " id="tab_2">
						<div class="row">
		                    <div class="col-sm-2">
		                        <label>EMPRESA:</label>
		                        <label id="lbl_item" name=></label>
		                    </div>
		                    <div class="col-sm-10">                                
		                        <input type="text" name="TxtEmpresa" id="TxtEmpresa" class="form-control input-xs" value="">
		                    </div>
		                    <div class="col-sm-2">
		                        <label hidden="hidden">ITEM:</label>
		                    </div>
		                    <div class="col-sm-10" style="display:none;">                                
		                        <input type="text" name="TxtItem" id="TxtItem" class="form-control input-xs" value="">
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="col-sm-2">
		                        <label>RAZON SOCIAL:</label>
		                    </div>
		                    <div class="col-sm-10">
		                        <input type="text" name="TxtRazonSocial" id="TxtRazonSocial" class="form-control input-xs" value="">
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="col-sm-2">
		                        <label>NOMBRE COMERCIAL:</label>
		                    </div>
		                    <div class="col-sm-10">
		                        <input type="text" name="TxtNomComercial" id="TxtNomComercial" class="form-control input-xs" value="">
		                    </div>
		                </div>                
		                <div class="row">
		                    <div class="col-sm-2">
		                        <label>RUC:</label>
		                        <input type="text" name="TxtRuc" id="TxtRuc" class="form-control input-xs" value="" onkeyup="num_caracteres('TxtRuc',13)" autocomplete="off">
		                    </div>
		                    <div class="col-sm-2">
		                        <label>OBLIG</label>
		                        <select class="form-control input-xs" id="ddl_obli" name="ddl_obli">
		                            <option value="">Seleccione</option>
		                            <option value="SI">SI</option>
		                            <option value="NO">NO</option>
		                        </select>
		                    </div>
		                    <div class="col-sm-6">
		                        <label>REPRESENTANTE LEGAL:</label>
		                        <input type="text" name="TxtRepresentanteLegal" id="TxtRepresentanteLegal" class="form-control input-xs" value="">
		                    </div>
		                    <div class="col-sm-2">
		                        <label>C.I/PASAPORTE</label>
		                        <input type="text" name="TxtCI" id="TxtCI" class="form-control input-xs" value="" onkeyup="num_caracteres('TxtCI',10)" autocomplete="off">
		                    </div>
		                </div>                    
		                <div class="row">
		                    <div class="col-sm-4">
		                        <label>NACIONALIDAD</label>
		                        <select class="form-control input-xs" id="ddl_naciones" name="ddl_naciones" onchange="provincias(this.value)">
		                            <option value="">Seleccione</option>
		                        </select>
		                    </div>
		                    <div class="col-sm-4">
		                        <label>PROVINCIA</label>
		                        <select class="form-control input-xs"  id="prov" name="prov" onchange="ciudad_l(this.value)">
		                            <option value="">Seleccione una provincia</option>		                           
		                        </select>
		                    </div>
		                    <div class="col-sm-4">
		                        <label>CIUDAD</label>
		                        <select class="form-control input-xs" id="ddl_ciudad" name="ddl_ciudad">
		                            <option value="">Seleccione una ciudad</option>		                            
		                        </select>
		                    </div>                        
		                </div>                    
		                <div class="row">
		                    <div class="col-sm-10">
		                        <label>DIRECCION MATRIZ:</label>
		                        <input type="text" name="TxtDirMatriz" id="TxtDirMatriz" class="form-control input-xs" value="">
		                    </div>
		                    <div class="col-sm-2">
		                        <label>ESTA.</label>
		                        <input type="text" name="TxtEsta" id="TxtEsta" class="form-control input-xs" value="">
		                    </div>                        
		                </div>
		                <div class="row">
		                    <div class="col-sm-2">
		                        <label>TELEFONO:</label>
		                        <input type="text" name="TxtTelefono" id="TxtTelefono" class="form-control input-xs" value="" onkeyup="num_caracteres(this.id,10)" onblur="num_caracteres(this.id,10)">
		                    </div>
		                    <div class="col-sm-2">
		                        <label>TELEFONO 2:</label>
		                        <input type="text" name="TxtTelefono2" id="TxtTelefono2" class="form-control input-xs" value="" onkeyup="num_caracteres(this.id,10)" onblur="num_caracteres(this.id,10)">
		                    </div>
		                    <div class="col-sm-1">
		                        <label>FAX:</label>
		                        <input type="text" name="TxtFax" id="TxtFax" class="form-control input-xs" value="">
		                    </div>
		                    <div class="col-sm-1">
		                        <label>MONEDA</label>
		                        <input type="text" name="TxtMoneda" id="TxtMoneda" class="form-control input-xs" value="USD">
		                    </div>
		                    <div class="col-sm-2">
		                        <label>NO. PATRONAL:</label>
		                        <input type="text" name="TxtNPatro" id="TxtNPatro" class="form-control input-xs" value="">
		                    </div>
		                    <div class="col-sm-1">
		                        <label>COD.BANCO</label>
		                        <input type="text" name="TxtCodBanco" id="TxtCodBanco" class="form-control input-xs" value="">
		                    </div>
		                    <div class="col-sm-1" style="padding: 0 0 0 8px;">
		                        <label>TIPO CAR.</label>
		                        <input type="text" name="TxtTipoCar" id="TxtTipoCar" class="form-control input-xs" value="">
		                    </div>
		                    <div class="col-sm-2">
		                        <label>ABREVIATURA</label>
		                        <input type="text" name="TxtAbrevi" id="TxtAbrevi" class="form-control input-xs" value="">
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="col-sm-12">
		                        <label>EMAIL DE LA EMPRESA:</label>
		                        <input type="text" name="TxtEmailEmpre" id="TxtEmailEmpre" class="form-control input-xs" value="">
		                    </div>                        
		                </div>
		                <div class="row">
		                    <div class="col-sm-12">
		                        <label>EMAIL DE CONTABILIDAD:</label>
		                        <input type="text" name="TxtEmailConta" id="TxtEmailConta" class="form-control input-xs" value="">
		                    </div>                        
		                </div>
		                <div class="row">
		                    <div class="col-sm-6">
		                        <label>EMAIL DE RESPALDO:</label>
		                        <input type="text" name="TxtEmailRespa" id="TxtEmailRespa" class="form-control input-xs" value="">
		                    </div>
		                    <div class="col-sm-4 text-center">
		                        <label>SEGURO DESGRAVAMEN %</label>
		                        <div class="form-group">
		                            <div class="col-sm-6">
		                                <input type="text" name="TxtSegDes1" id="TxtSegDes1" class="form-control input-xs" value="">
		                            </div>
		                            <div class="col-sm-6">
		                                <input type="text" name="TxtSegDes2" id="TxtSegDes2" class="form-control input-xs" value="">
		                            </div>
		                        </div>                        
		                    </div>
		                    <div class="col-sm-2">
		                        <label>SUBDIR:</label>
		                        <input type="text" name="TxtSubdir" id="TxtSubdir" class="form-control input-xs" value="" onblur="subdireccion()" onkeyup="mayusculas('TxtSubdir',this.value);">
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="col-sm-10">
		                        <label>NOMBRE DEL CONTADOR</label>
		                        <input type="text" name="TxtNombConta" id="TxtNombConta" class="form-control input-xs" value="">
		                    </div>
		                    <div class="col-sm-2">
		                        <label>RUC CONTADOR:</label>
		                        <input type="text" name="TxtRucConta" id="TxtRucConta" class="form-control input-xs" value="" onkeyup="num_caracteres('TxtRucConta',13)" autocomplete="off">
		                    </div>
		                </div>
					</div>

					<div class="tab-pane" id="tab_3">


						<div class="row">
		                    <div class="col-md-4" style="background-color:#ffe0c0">                                   
		                    <!-- setesos -->
		                        <label>|Seteos Generales|</label>
		                        <div class="checkbox">
		                            <label><input type="checkbox" name="ASDAS" id="ASDAS">Agrupar Saldos Detalle Auxiliar de Submodulos</label>
		                        </div>
		                        <div class="checkbox">
		                            <label><input type="checkbox" name="MFNV" id="MFNV">Modificar Facturas o Notas de Venta</label>
		                        </div>
		                        <div class="checkbox">
		                            <label><input type="checkbox" name="MPVP" id="MPVP">Modificar Precio de Venta al Público</label>
		                        </div>
		                        <div class="checkbox">
		                            <label><input type="checkbox" name="IRCF" id="IRCF">Imprimir Recibo de Caja en Facturación</label>
		                        </div>
		                        <div class="checkbox">
		                            <label><input type="checkbox" name="IMR" id="IMR">Imprimir Medio Rol</label>
		                        </div>
		                        <div class="checkbox">
		                            <label><input type="checkbox" name="IRIP" id="IRIP">Imprimir dos Roles Individuales por pagina</label>
		                        </div>
		                        <div class="checkbox">
		                            <label><input type="checkbox" name="PDAC" id="PDAC" >Procesar Detalle Auxiliar de Comprobantes</label>
		                        </div>
		                        <div class="checkbox">
		                            <label><input type="checkbox" name="RIAC" id="RIAC">Registrar el IVA en el Asiento Contable</label>
		                        </div>
		                        <div class="checkbox">
		                            <label><input type="checkbox" name="FCMS" id="FCMS">Funciona como Matriz de Sucursales</label>
		                        </div>
		                    </div>
		                    <div class="col-md-4">                                        
		                        <label>LOGO TIPO </label>
		                        <!-- llenar con contenuido de la carpeta logotipos -->

		                        <input type="text" name="TxtXXXX" id="TxtXXXX" class="form-control input-xs" value="XXXXXXXXXX">
		                        <div class="form-group" rows="11">                                        
		                            <select class="form-control" onchange="cargar_img()" id="ddl_img" name="ddl_img" row="11" multiple></select>                                                
		                        </div>
		                        <div class="row">
		                        	<div class="col-sm-10">
		                        	    <input type="file" id="file_img" name="file_img" />
		                        	</div>
		                        	<div class="col-sm-2">
		                        	    <button type="button" class="btn btn-primary btn-sm" id="subir_imagen" onclick="subir_img()" >Cargar</button>                        	
		                        	</div>
		                        </div>
		                        
		                    </div>
		                    <div class="col-md-4">                                        
		                        <div class="box-body">
		                        <img src="../../img/logotipos/sin_img.jpg" id="img_logo" name="img_logo" style="width:316px;height:158px; border:1px solid"/>
		                        <p><b>Nombre: </b><span id="img_foto_name"></span></p>   
		                        </div>                                     
		                    </div>
		                </div>
		                <div class="row">
		                    <div class="col-md-4">
		                        <label>|Numeración de Comprobantes|</label>
		                        <div class="row">
		                            <div class="col-sm-6">
		                                <label><input type="radio" name="dm1" id="DM" value="1"  onclick="DiariosM()">Diarios por meses</label>
		                            </div>
		                            <div class="col-sm-6">
		                                <label><input type="radio" name="dm1" id="DS" value="0" onclick="DiariosS()">Diarios secuenciales</label>                                
		                            </div>
		                        </div>
		                        <div class="row">
		                            <div class="col-sm-6">
		                                <label><input type="radio" name="dm2" id="IM"  value="1" onclick="IngresosM()">Ingresos por meses</label>
		                            </div>
		                            <div class="col-sm-6">
		                                <label><input type="radio" name="dm2" id="IS"  value="0" onclick="IngresosS()">Ingresos secuenciales</label>
		                            </div>
		                        </div>
		                        <div class="row">
		                            <div class="col-sm-6">
		                                <label><input type="radio" name="dm3" id="EM"  value="1" onclick="EgresosM()">Egresos por meses</label>
		                            </div>
		                            <div class="col-sm-6">
		                                <label> <input type="radio" name="dm3" id="ES"  value="0" onclick="EgresosS()">Egresos secuenciales</label>
		                            </div>
		                        </div>
		                        <div class="row">
		                            <div class="col-sm-6">
		                                <label><input type="radio" name="dm4" id="NDM" value="1" onclick="NDPM()">N/D por meses</label>
		                            </div>
		                            <div class="col-sm-6">
		                                <label><input type="radio" name="dm4" id="NDS" value="0" onclick="NDPS()">N/D secuenciales</label>
		                            </div>
		                        </div>
		                        <div class="row">
		                            <div class="col-sm-6">
		                                <label><input type="radio" name="dm5" id="NCM" value="1" onclick="NCPM()">N/C por meses</label>
		                            </div>
		                            <div class="col-sm-6">
		                                <label><input type="radio" name="dm5" id="NCS"  value="0" onclick="NCPS()">N/C secuenciales</label>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="col-sm-8" style="background-color:#ffffc0">                        
		                        <div class="row">
		                            <div class="col-sm-12">
		                                <b>|Servidor de Correos|</b>
		                            </div>
		                        </div>
		                        <div class="row">
		                            <div class="col-sm-10" style="background-color:#ffffc0">
		                                <b>Servidor SMTP</b>
		                                <input type="text" name="TxtServidorSMTP" id="TxtServidorSMTP" class="form-control input-xs" value="">
		                            </div>
		                            <!-- <div class="col-sm-2" style="background-color:#ffffc0">
		                                <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
		                                <button type="button" class="btn btn-default" title="Grabar Empresa" onclick="()">
		                                    <img src="../../img/png/grabar.png">
		                                </button>
		                            </div>
		                            </div> -->
		                        </div>
		                        <div class="row" style="background-color:#ffffc0">
		                            <div class="col-sm-3">
		                                <input type="checkbox" name="Autenti" id="Autenti">Autentificación
		                            </div>
		                            <div class="col-sm-2">
		                                <input type="checkbox" name="SSL" id="SSL">SSL
		                            </div>
		                            <div class="col-sm-2">
		                                <input type="checkbox" name="Secure" id="Secure">SECURE
		                            </div>
		                            <div class="col-sm-1">
		                                PUERTO
		                            </div>
		                            <div class="col-sm-2">
		                                <input type="text" name="TxtPuerto" id="TxtPuerto" class="form-control input-xs">                                
		                            </div>
		                        </div>
		                        <div class="row">
		                            <div class="col-sm-8">
		                                <input type="checkbox" name="AsigUsuClave" id="AsigUsuClave" onclick="MostrarUsuClave()">ASIGNA USUARIO Y CLAVE DEL REPRESENTANTE LEGAL
		                            </div>
		                            <div class="col-sm-2">
		                                <label id="lblUsuario" style="display:none" >USUARIO</label>
		                            </div>
		                            <div class="col-sm-2">
		                                <input type="text" name="TxtUsuario" id="TxtUsuario" class="form-control input-xs" value="USUARIO"  style="display:none" > 
		                            </div>
		                        </div>
		                        <div class="row">
		                            <div class="col-sm-8">
		                                <input type="checkbox"  name="CopSeEmp" id="CopSeEmp" onclick="MostrarEmpresaCopia()">COPIAR SETEOS DE OTRA EMPRESA
		                            </div>
		                            <div class="col-sm-2">
		                                <label id="lblClave"  style="display:none" >CLAVE</label>
		                            </div>
		                            <div class="col-sm-2">
		                                <input type="text" name="TxtClave" id="TxtClave" class="form-control input-xs" style="display:none" >
		                            </div>
		                        </div>
		                        <div class="row">
		                            <div class="col-sm-12">
		                                <select class="form-control input-xs" id="ListaCopiaEmpresa" name="ListaCopiaEmpresa"width="100%" >
		                                    <option value="">Empresa</option>
		                                </select>
		                            </div>
		                        </div>
		                        
		                    </div>
		            </div>
		            <div class="row">
		            	<div class="col-sm-4" style="background-color:#c0ffc0">
		            		<div class="row">
		                        <label>|Cantidad de Decimales en|</label>
		                    </div>   
		                <div class="row">
		                    <div class="col-md-3" style="background-color:#c0ffc0">
		                        P.V.P
		                        <input type="text" name="TxtPVP" id="TxtPVP" class="form-control input-xs" value="">
		                    </div>
		                    <div class="col-md-3" style="background-color:#c0ffc0">
		                        COSTOS
		                        <input type="text" name="TxtCOSTOS" id="TxtCOSTOS" class="form-control input-xs" value="">
		                    </div>
		                    <div class="col-md-3" style="background-color:#c0ffc0">
		                        I.V.A
		                        <input type="text" name="TxtIVA" id="TxtIVA" class="form-control input-xs" value="">
		                    </div>
		                    <div class="col-md-3" style="background-color:#c0ffc0">
		                        CANTIDAD
		                        <input type="text" name="TxtCantidad" id="TxtCantidad" class="form-control input-xs" value="">
		                    </div>
		                </div>
		            		
		            	</div>
		            	<div class="col-sm-8" style="background-color:#c0ecff">
		            			<div class="row" >
		                            <div class="col-sm-12" >
		                                <b>|Tipo Contibuyente|</b>
		                            </div>
		                        </div>
		                        
		                        <div class="row">
		                        	<div class="col-sm-4">
		                                <b>RUC</b>
		                                <input type="text" name="TxtRucTipocontribuyente" id="TxtRucTipocontribuyente" class="form-control input-xs" value="" readonly>
		                            </div>
		                            <div class="col-sm-3">
		                                <b>Zona</b>
		                                <input type="text" name="TxtZonaTipocontribuyente" id="TxtZonaTipocontribuyente" class="form-control input-xs" value="">
		                            </div>
		                            <div class="col-sm-4">
		                                <b>Agente de retencion</b>
		                                <input type="text" name="TxtAgentetipoContribuyente" id="TxtAgentetipoContribuyente" class="form-control input-xs" value="">
		                            </div>
		                            <div class="col-sm-2">
		                               <!--  <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
			                                <button type="button" class="btn btn-default" title="Grabar Empresa" onclick="guardarTipoContribuyente()">
			                                    <img src="../../img/png/grabar.png">
			                                </button>
		                            	</div> -->
		                            </div>
		                        </div>
		                        <div class="row">
		                        	<label class="col-sm-4">
		                                <input type="checkbox" name="rbl_ContEs" id="rbl_ContEs">Contribuyente Especial
		                           </label>
		                            <label class="col-sm-2">
		                                <input type="checkbox" name="rbl_rimpeE" id="rbl_rimpeE">RIMPE E
		                            </label>
		                            <label class="col-sm-2">
		                                <input type="checkbox" name="rbl_rimpeP" id="rbl_rimpeP">RIMPE P
		                           </label>
		                            <label class="col-sm-3">
		                                <input type="checkbox" name="rbl_regGen" id="rbl_regGen">Regimen General
		                            </label>
		                            <label class="col-sm-2">
		                                <input type="checkbox" name="rbl_rise" id="rbl_rise">RISE
		                            </label>
		                            <label class="col-sm-2">
		                                <input type="checkbox" name="rbl_micro2020" id="rbl_micro2020">Micro 2020
		                           </label>
		                            <label class="col-sm-2">
		                                <input type="checkbox" name="rbl_micro2021" id="rbl_micro2021">Micro 2021
		                            </label>
		                        </div>
		            	</div>
		            	
		            </div>                
		                

					</div>

					<div class="tab-pane" id="tab_4">
						<div class="row">
							<div class="col-sm-3"><label>WEBSERVICE SRI RECEPCION</label></div>
		               <div class="col-sm-2">                                    
			                   <label><input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" onclick="AmbientePrueba()">
			                    Ambiente de Prueba</label>
			                </div>
			                <div class="col-sm-3">
			                    <label><input type="radio" name="optionsRadios" id="optionsRadios2" value="option2" onclick="AmbienteProduccion()">
			                    Ambiente de Producción</label>
			                </div>
		            	<div class="col-sm-2">Contribuyente Especial</div>
		                <div class="col-sm-2">
		                    <input type="text" name="TxtContriEspecial" id="TxtContriEspecial" class="form-control input-xs" value="">
		                </div>
		                 <div class="col-sm-12">
		                    <input type="text" name="TxtWebSRIre" id="TxtWebSRIre" class="form-control input-xs" value="">
		                </div>
		                <div class="col-sm-12">
		                    <label>WEBSERVICE SRI AUTORIZACIÓN</label>
		                        <input type="text" name="TxtWebSRIau" id="TxtWebSRIau" class="form-control input-xs" value="">
		                </div>           
		                <div class="col-sm-10">
		                    <label>CERTIFICADO FIRMA ELECTRONICA (DEBE SER EN FORMATO DE EXTENSION P12)</label>
		                    <div class="input-group">

		                    	<input type="text" name="TxtEXTP12" id="TxtEXTP12" class="form-control input-sm" value="" >
		                    	<span class="input-group-addon input-xs">
		                    		<input type="file"  id="file_firma" data-placeholder="Elegir imágen..." name="file_firma" />
		                    	</span>
		                    	<!-- <span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat btn-sm" onclick="subir_firma()">Subir firma</button>
								</span> -->
		                    </div>
		                </div>
		                <div class="col-sm-2">
		                    <label>CONTRASEÑA:</label>
		                    <input type="text" name="TxtContraExtP12" id="TxtContraExtP12" class="form-control input-xs" value="">
		                </div>
		                <div class="col-sm-10">
		                    <label>EMAIL PARA PROCESOS GENERALES:</label>
		                    <input type="text" name="TxtEmailGE" id="TxtEmailGE" class="form-control input-xs" value="">
		                </div>
		                <div class="col-sm-2">
		                    <label>CONTRASEÑA:</label>
		                    <input type="text" name="TxtContraEmailGE" id="TxtContraEmailGE" class="form-control input-xs" value="">
		                </div>
		                <div class="col-sm-10">
		                    <label>EMAIL PARA DOCUMENTOS ELECTRONICOS:</label>
		                    <input type="text" name="TxtEmaiElect" id="TxtEmaiElect" class="form-control input-xs" value="">
		                </div>
		                <div class="col-sm-2">
		                    <label>CONTRASEÑA:</label>
		                    <input type="text" name="TxtContraEmaiElect" id="TxtContraEmaiElect" class="form-control input-xs" value="">
		                </div>
		                <div class="col-sm-10">
		                <label><input type="checkbox" id="rbl_copia" name="rbl_copia">Enviar Copia de Email</label>';
		                <input type="text" name="TxtCopiaEmai" id="TxtCopiaEmai" class="form-control input-xs" value="">
		                </div>
		                <div class="col-sm-2">
		                    <label>RUC Operadora</label>
		                    <input type="text" name="TxtRUCOpe" id="TxtRUCOpe" class="form-control input-xs" value="">
		                </div>
		                <div class="col-sm-12">                            
		                        <label>LEYENDA AL FINAL DE LOS DOCUMENTOS ELECTRONICOS</label>
		                        <textarea name="txtLeyendaDocumen" id="txtLeyendaDocumen"class="form-control" rows="2" resize="none"></textarea>                            
		                </div>
		                <div class="col-sm-12">
		                    <label>LEYENDA AL FINAL DE LA IMPRESION EN LA IMPRESORA DE PUNTO DE VENTA DE DOCUMENTOS ELECTRÓNICOS</label><br>                            
		                    <textarea name="txtLeyendaImpresora" id="txtLeyendaImpresora"class="form-control" rows="2" resize="none"></textarea>
		                </div>
						</div>
					</div>

				</div>

			</div>
		</div>		
	</div>

</form>	
