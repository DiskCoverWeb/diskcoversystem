
<?php
$mod = '';
   if(isset($_GET['mod'])){
   	$mod = $_GET['mod'];
   }
    if(!isset($_SESSION)) 
	 		session_start();
	 	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) 
		{
			$uri = 'https://';
		}
		else
		{
			$uri = 'http://';
		}
		$uri .= $_SERVER['HTTP_HOST'];
		//echo "<script type='text/javascript'>window.location='".$uri."/store/php/view/login.php'</script>";
		$cartera_usu ='';
		$cartera_pass = '';
		if(isset($_SESSION['INGRESO']['CARTERA_USUARIO']))
		{
		 $cartera_usu = $_SESSION['INGRESO']['CARTERA_USUARIO'];
		 $cartera_pass = $_SESSION['INGRESO']['CARTERA_PASS'];
		}
	
?>
<div class="modal fade bd-example-modal-sm" id="modal_espera" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <img src="../../img/gif/proce.gif">
      <div class="text-center">
      	<h4 id="titulo"> Creando ficha de alumno</h4>          	
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

$(document).ready(function() {  

	var cartera_usu = '<?php echo $cartera_usu; ?>';
	var cartera_pas = '<?php echo $cartera_pass;?>';
	if(cartera_usu!='')
	{
		$('#txt_cod_banco').val(cartera_usu);
		$('#txt_clave').val(cartera_pas);

		$('#txt_cod_banco').attr('readonly',true);
		$('#txt_clave').attr('readonly',true);
	}
	var mod='<?php echo $mod; ?>'; 
	if(mod!='11')
	{
		$('#home_t').css('display','none');
		$('#menu2_t').css('display','none');
		$('#menu3_t').css('display','none');
		$('#home').css('display','none');
		$('#menu2').css('display','none');
		$('#menu3').css('display','none');
		$( "#menu4_t" ).addClass( "active" );
		$('#menu4').css('display','block');
		$( "#menu4" ).removeClass( "fade" );
	}


   provincias();
	$(".upload").on('click', function() {
    	var curso = '123647899';    	
		var nuevo = $('#txt_cod_banco').val(); 
		var pass = $('#txt_clave').val();
        var formData = new FormData(document.getElementById("img_ajax"));
        var files = $('#foto')[0].files[0];
        formData.append('file',files);
       // formData.append('curso',curso);
        $.ajax({
            url: '../controlador/educativo/detalle_estudianteC.php?cargar_imagen=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
		    dataType:'json',
		     beforeSend: function () {
                $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
             },
            success: function(response) {
            if(response=='ok')
            {
            	validar_estudiante(nuevo,pass,false);

            	$("#home_1").load(" #home_1");
            }
            else
            {
            	Swal.fire({
				 type: 'error',
				 title: 'No se pudo subir el archivo',
				 text: 'Asegurese que su archivo sea formato jpg, gif o png!'
             });
            	validar_estudiante(nuevo,pass,false);

            }                  
               
            }
        });
    });

    $("#b_file_2").on('click', function() {
		var nuevo = $('#txt_cod_banco').val(); 
        var formData = new FormData(document.getElementById("file_rep"));
        var files = $('#file')[0].files[0];
        formData.append('file',files);
       // formData.append('curso',curso);
        $.ajax({
            url: '../controlador/educativo/detalle_estudianteC.php?cargar_file=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
		   // dataType:'json',
            success: function(response) {
            	console.log(response);
            if(response == 1)
            {Swal.fire({
		  		 	//position: 'top-end',
                    type: 'success',
                    title: 'Archivo subido con exito!',
                    showConfirmButton: true
                    //timer: 2500
                 });
            }  
            else
            {
            	Swal.fire({
				 type: 'error',
				 title: 'No se pudo subir el archivo',
				 text: 'Asegurese que su archivo sea formato jpg, pdf, gif o png!'
             });
            	
                $('#modal_espera').modal('hide');
            }                
               
            }
        });
    });

        $("#b_file_3").on('click', function() {
		var nuevo = $('#txt_cod_banco').val(); 
        var formData = new FormData(document.getElementById("file_depo"));
        var files = $('#file_pago')[0].files[0];
        formData.append('file',files);
       // formData.append('curso',curso);
        $.ajax({
            url: '../controlador/educativo/detalle_estudianteC.php?cargar_pago=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
		   // dataType:'json',
            success: function(response) {
            	console.log(response);
            if(response == 1)
            {Swal.fire({
		  		 	//position: 'top-end',
                    type: 'success',
                    title: 'Archivo subido con exito!',
                    showConfirmButton: true
                    //timer: 2500
                 });
            }  
            else
            {
            	Swal.fire({
				 type: 'error',
				 title: 'No pudo subir el archivo',
				 text: 'Asegurese que su archivo sea formato jpg, pdf, gif o png!'
             });
            }                
               
            }
        });
    });


   lista_cursos();

 var input=  document.getElementById('txt_ci_r');
input.addEventListener('input',function(){
  if (this.value.length > 10) 
     this.value = this.value.slice(0,10); 
})
var input=  document.getElementById('txt_ci_m');
input.addEventListener('input',function(){
  if (this.value.length > 10) 
     this.value = this.value.slice(0,10); 
})
var input=  document.getElementById('txt_ci_p');
input.addEventListener('input',function(){
  if (this.value.length > 10) 
     this.value = this.value.slice(0,10); 
})
var input=  document.getElementById('txt_telefono_r');
input.addEventListener('input',function(){
  if (this.value.length > 10) 
     this.value = this.value.slice(0,10); 
})
var input=  document.getElementById('txt_celular_r');
input.addEventListener('input',function(){
  if (this.value.length > 10) 
     this.value = this.value.slice(0,10); 
})
var input=  document.getElementById('txt_cod_banco');
input.addEventListener('input',function(){
  if (this.value.length > 10) 
     this.value = this.value.slice(0,10); 
})
var input=  document.getElementById('txt_clave');
input.addEventListener('input',function(){
  if (this.value.length > 10) 
     this.value = this.value.slice(0,10); 
})

var input=  document.getElementById('txt_ocupacion_m');
input.addEventListener('input',function(){
  if (this.value.length > 40) 
     this.value = this.value.slice(0,40); 
})
var input=  document.getElementById('txt_ocupacion_p');
input.addEventListener('input',function(){
  if (this.value.length > 40) 
     this.value = this.value.slice(0,40); 
})
var input=  document.getElementById('txt_ocupacion_r');
input.addEventListener('input',function(){
  if (this.value.length > 40) 
     this.value = this.value.slice(0,40); 
})
var input=  document.getElementById('txt_profesion_m');
input.addEventListener('input',function(){
  if (this.value.length > 40) 
     this.value = this.value.slice(0,40); 
})
var input=  document.getElementById('txt_profesion_p');
input.addEventListener('input',function(){
  if (this.value.length > 40) 
     this.value = this.value.slice(0,40); 
})
var input=  document.getElementById('txt_profesion_r');
input.addEventListener('input',function(){
  if (this.value.length > 40) 
     this.value = this.value.slice(0,40); 
})

var input=  document.getElementById('txt_nombre_p');
input.addEventListener('input',function(){
  if (this.value.length > 35) 
     this.value = this.value.slice(0,35); 
})
var input=  document.getElementById('txt_nombre_m');
input.addEventListener('input',function(){
  if (this.value.length > 35) 
     this.value = this.value.slice(0,35); 
})
var input=  document.getElementById('txt_nombre_r');
input.addEventListener('input',function(){
  if (this.value.length > 35) 
     this.value = this.value.slice(0,35); 
})

if($('#id').val()== '')
{

	$('#imprimir_pdf').prop('disabled', true);
	$('#enavia_email').prop('disabled', true);

	$('#btn_guar_est').prop('disabled', true);
	$('#btn_guar_fami').prop('disabled', true);

	$('#btn_guar_repre').prop('disabled', true);
	$('#btn_guar_fin_rep').prop('disabled', true);

	$('#foto').prop('disabled', true);
	$('#btn_subir_foto').prop('disabled', true);

	$('#file_pago').prop('disabled', true);
	$('#b_file_3').prop('disabled', true);

	$('#b_file_2').prop('disabled', true);	
	$('#file').prop('disabled', true);

}
});

function lista_cursos()
{	 var option ="<option value=''>Seleccione curso</option>"; 
	$.ajax({
		url: '../controlador/educativo/detalle_estudianteC.php?cursos=true',
		type:'post',
		dataType:'json',
		//data:{categoria,categoria},
		success: function(response){
			response[0].forEach(function(data,index){
				option+="<option value='"+data.Curso+"'>"+data.Descripcion+"</option>"
			});
		$('#select_cursos').html(option);
			console.log(response);
		}
	})

}

	function pasar_tab(tab)
	{
		if(tab!='')
		{
		 $('a[href="#'+tab+'"]').click();
	    }
	} 

	function abrir_modal()
	{
		
		$('#mymodal').modal('show');
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
		   $('#select_provincias').html(option);
			console.log(response);
		}
	  });

	}
	function ciudad(idpro)
	{
		console.log(idpro);
		var option ="<option value=''>Seleccione ciudad</option>"; 
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
		   $('#select_ciudad').html(option);
			console.log(response);
		}
	  });
	 } 

	}

	 function cliente_proveedor(cli)
	 {
	 	var banco = $('#txt_cod_banco').val();
		var pass = $('#txt_clave').val();
		  $.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?cliente_proveedor=true',
		  type:'post',
		  dataType:'json',
		  data:{cli:cli},
		  success: function(response){
		  	console.log(response);
			if(response== 'si')
			{
				Swal.fire({
				 type: 'info',
				 title: 'Cliente paso A FACTURAR',
				 //text: 'the quantity entered is outside the order range!'
                 });
				validar_estudiante(banco,pass,false);
			}else
			{

				validar_estudiante(banco,pass,false);
			}
		}
	  });

	 }
	function existente_clave(ci,cla)
	{
		var banco = $('#txt_cod_banco').val();
		var pass = $('#txt_clave').val();
		  $.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?existente_clave=true',
		  type:'post',
		  dataType:'json',
		  data:{ci:ci,cla:cla},
		  success: function(response){
		  	console.log(response);
			if(response.length == null || response.length == '' || response.length === 0)
			{
				Swal.fire({
				 type: 'error',
				 title: 'El usuario esta registrado pero su Clave incorrecta',
				 //text: 'the quantity entered is outside the order range!'
          });
			}else
			{

				cliente_proveedor(ci);
			}
		}
	  });
	

	}

	function existente_usu(usu)
	{
		var pass = $('#txt_clave').val();
		
		  $.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?existente=true',
		  type:'post',
		  dataType:'json',
		  data:{usu:usu},
		  success: function(response){
			if(response.length == null || response.length == '')
			{
				abrir_modal();
			}else
			{
				existente_clave(usu,pass);
			}
			$('#myModal_espera').modal('hide');
		}
	  });
	

	}


	function login_data()
	{
		var banco = $('#txt_cod_banco').val();
		var pass = $('#txt_clave').val();
		if(banco != '' &&  pass !='')
		{
			existente_usu(banco);
		}else
		{
			Swal.fire({
				 type: 'error',
				 title: 'Campos vacios',
				 //text: 'the quantity entered is outside the order range!'
          });
		}
	}
	function validar_estudiante(usu,pass,nuevo)
	{

	   $.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?validar_estudiante=true',
		  type:'post',
		  dataType:'json',
		  data:{usu:usu,pass:pass,nuevo:nuevo}, 
		  beforeSend: function () {

		  	  $('#modal_espera').modal('hide');
		  	  $('#myModal_espera').modal('show');
		  	       provincias();
                  limpiar();
             },
		  success: function(response){		  	

			if(response!='' && response !='-2')
			{
			//$('#contenido_tab').css('display','block');

			$("#id").val(response[0]['ID']);			
			$("#select_cursos option[value='"+response[0]['Grupo']+"']").attr("selected",true);
			if(response[0]['Archivo_Foto'] !='.' &&  response[0]['Archivo_Foto']!='')
			{
			 $("#foto_alumno").attr('src','../img/img_estudiantes/'+response[0]['Archivo_Foto']+'?ver='+Math.floor(Date.now()));
			}else{				
			$("#foto_alumno").attr('src','../../img/jpg/sinimagen.jpg');
		    }
			$("#txt_cedula").val(response[0]['CI_RUC']);
			$("#nom").val(response[0]['CI_RUC']);
			$("#nom_1").val(response[0]['CI_RUC']);
			$("#codigo_foto").val(response[0]['CI_RUC']);
			$("#codigo_cli").val(response[0]['Codigo']);
			facturas_emi(response[0]['Codigo']);

			
			
			$("#codigo").val(response[0]['CI_RUC']);
			$("#txt_nombre").val(response[0]['Cliente']);
			$("#txt_curso_cod").val(response[0]['Direccion']);
			if(response[0]['Sexo']== 'M')
			{
				$("#cbx_sexo_M").prop("checked", true);
			}else
			{				
				$("#cbx_sexo_F").prop("checked", true);
			}
			$("#email_estudiante").val(response[0]['Email']);
			$("#procedencia").val(response[0]['Procedencia']);
			$("#matricula").val(response[0]['Matricula']);
			//$("#").val(response[0]['']);
			//$("#").val(response[0]['']);
			$("#representante").val(response[0]['Representante_Alumno']);
			if(response[0]['Nacionalidad'] =='.' || response[0]['Nacionalidad'] =='')
			{
			  $("#txt_nacionalidad").val('ECUATORIANA');
			}else{
			 $("#txt_nacionalidad").val(response[0]['Nacionalidad']);
		    }
			$("#txt_curso").val(response[0]['Grupo']);
			if(response[0]['Prov'] !='.' && response[0]['Prov'] !='' )
			{
			 $("#select_provincias option[value="+response[0]['Prov']+"]").attr("selected",true);
		    }
		    
			$("#txt_seccion").val(response[0]['Seccion']);
			$('#curso_detalle').val(response[0]['Curso_Superior']);
			$('#fecha_n').val(response[0]['Fecha_N']);
			$('#observaciones').val(response[0]['Observaciones']);

			//datos de padres de familia 
			$("#txt_nombre_p").val(response[0]['Nombre_Padre']);
			$("#txt_ci_p").val(response[0]['CI_P']);
			$("#txt_nacionalidad_p").val(response[0]['Nacionalidad_P']);
			$("#txt_trabajo_p").val(response[0]['Lugar_Trabajo_P']);
			$("#txt_telefono_p").val(response[0]['Telefono_Trabajo_P']);
			$("#txt_celular_p").val(response[0]['Celular_P']);
			$("#txt_profesion_p").val(response[0]['Profesion_P']);
			$("#txt_ocupacion_p").val(response[0]['Ocupacion_P']);
			$("#txt_email_p").val(response[0]['Email_P']);

			//datos de madre de familia 
			$("#txt_nombre_m").val(response[0]['Nombre_Madre']);
			$("#txt_ci_m").val(response[0]['CI_M']);
			$("#txt_nacionalidad_m").val(response[0]['Nacionalidad_M']);
			$("#txt_trabajo_m").val(response[0]['Lugar_Trabajo_M']);
			$("#txt_telefono_m").val(response[0]['Telefono_Trabajo_M']);
			$("#txt_celular_m").val(response[0]['Celular_M']);
			$("#txt_profesion_m").val(response[0]['Profesion_M']);
			$("#txt_ocupacion_m").val(response[0]['Ocupacion_M']);
			$("#txt_email_m").val(response[0]['Email_M']);


			//datos de representante de familia 
			$("#txt_nombre_r").val(response[0]['Representante_Alumno']);
			$("#txt_ci_r").val(response[0]['CI_R']);
			$("#txt_profesion_r").val(response[0]['Profesion_R']);
			$("#txt_ocupacion_r").val(response[0]['Ocupacion_R']);
			$("#txt_telefono_r").val(response[0]['Telefono_R']);
			$("#txt_celular_r").val(response[0]['Telefono_RS']);
			$("#txt_trabajo_r").val(response[0]['Lugar_Trabajo_R']);
			$("#txt_email_fac_r").val(response[0]['Email2']);
			$("#txt_email_r").val(response[0]['Email_R']);

			$('#txt_matricula_num').val(response[0]['Matricula_No']);
			$('#txt_tomo').val(response[0]['Folio_No']);
			if(response[0]['Ciudad'] != '.' &&  response[0]['Ciudad'] != '')
		    {
		      $("#select_ciudad").html("<option value='' selected>"+response[0]['Ciudad']+"</option>");
		    // console.log(response[0]['Ciudad']+'ciudad');	
		    }

			$("#txt_dir_est").val(response[0]['DireccionT']);
			//$("#email_estudiante").val(response[0]['Email2']);
			numero_patricula();
			$('#imprimir_pdf').prop('disabled', false);
			$('#enavia_email').prop('disabled', false);

			$('#btn_guar_est').prop('disabled', false);
			$('#btn_guar_fami').prop('disabled', false);

			$('#btn_guar_repre').prop('disabled', false);
			$('#btn_guar_fin_rep').prop('disabled', false);

			$('#foto').prop('disabled', false);
			$('#btn_subir_foto').prop('disabled', false);

			$('#file_pago').prop('disabled', false);
			$('#b_file_3').prop('disabled', false);

			$('#b_file_2').prop('disabled', false);	
			$('#file').prop('disabled', false);

		    //$('#modal_espera').modal('hide');
		    $('#myModal_espera').modal('hide');
		       console.log(response);
            }else if(response == '-2')
            {
		        $('#myModal_espera').modal('hide');
		        var cartera = '<?php echo $cartera_usu; ?>'
		        if(cartera=='')
		        {
            		$('#nueva_matricula').modal('show');
            	}
            	// alert('el usuario no tiene registrado un matricula');
            }else
            {
            	abrir_modal();
		        $('#myModal_espera').modal('hide');
		     //  $('#modal_espera').modal('hide');
            }
		  


		//	response.forEach(function(data,index){
		//		option+="<option value='"+data.Curso+"'>"+data.Descripcion+"</option>"
		//	});
		//$('#cursos').html(option);
			console.log(response);
		}
	  })

	}

	function facturas_emi(codigo)
	{		
	   $.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?fac_emi=true',
		  type:'post',
		 dataType:'json',
		  data:{codigo:codigo},		 
		  success: function(response){
		  	if(response != null)
		  	{
		  	$('#factura_emi').html(response);			
			//console.log(response);

		    }else
		    {
		    	alert('pasa algo');
		    }
		  	
		}
	  })	

	}
 	function Ver_factura(id,serie,ci,per,auto)
	{		 
		var url = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+id+'&ser='+serie+'&ci='+ci+'&per='+per+'&auto='+auto;		
		window.open(url,'_blank');
	}

	function limpiar()
	{
		    $("#id").val('');			
			$("#select_cursos option[value='']").attr("selected",true);			
			$("#foto_alumno").attr('src','../../img/jpg/sinimagen.jpg');
			
			$("#txt_cedula").val('');
			$("#codigo_foto").val('');
			
			
			$("#codigo").val('');
			$("#txt_nombre").val('');
			$("#txt_curso_cod").val('');
			$("#cbx_sexo_M").prop("checked", true);
			
			$("#email_estudiante").val('');
			$("#procedencia").val('');
			$("#matricula").val('');
			//$("#").val(response[0]['']);
			//$("#").val(response[0]['']);
			$("#representante").val('');
			$("#txt_nacionalidad").val('');
			$("#txt_curso").val('');
			//$("#select_provincias").val('');

			$("#select_ciudad").html("<option value=''> Seleccione ciudad</option>");
			$("#txt_seccion").val('');
			$('#curso_detalle').val('');
			$('#fecha_n').val('');
			$('#observaciones').val('');

			//datos de padres de familia 
			$("#txt_nombre_p").val('');
			$("#txt_ci_p").val('');
			$("#txt_nacionalidad_p").val('');
			$("#txt_trabajo_p").val('');
			$("#txt_telefono_p").val('');
			$("#txt_celular_p").val('');
			$("#txt_profesion_p").val('');
			$("#txt_email_p").val('');
			$("#txt_ocupacion_p").val('')

			//datos de madre de familia 
			$("#txt_nombre_m").val('');
			$("#txt_ci_m").val('');
			$("#txt_nacionalidad_m").val('');
			$("#txt_trabajo_m").val('');
			$("#txt_telefono_m").val('');
			$("#txt_celular_m").val('');
			$("#txt_profesion_m").val('');
			$("#txt_email_m").val('');
			$("#txt_ocupacion_m").val()


			//datos de representante de familia 
			$("#txt_nombre_r").val('');
			$("#txt_ci_r").val('');
			$("#txt_profesion_r").val('');
			$("#txt_ocupacion_r").val('');
			$("#txt_telefono_r").val('');
			$("#txt_celular_r").val('');
			$("#txt_trabajo_r").val('');
			$("#txt_email_fac_r").val('');
			$("#txt_email_r").val('');
			$('txt_matricula_num').val('');
			$('#txt_tomo').val('');
			$('#txt_dir_est').val('');
			$('#email_estudiante').val('');
			$('#file_rep').val('');			
			$('#file_pago').val('');
			$('#foto').val('');
			$('#cod_checks').val('');
			$('#ver_fac').css('display','none');
			$('#file_rep').css('display','none');
	}

	function nuevo_registro()
	{
		$('#mymodal').modal('hide');

		$('#modal_espera').modal('show');
		var nuevo = $('#txt_cod_banco').val();
		var clave = $('#txt_clave').val();
		var parametro = 
		{
			'codigo':nuevo,
			'clave':clave,
		} 
		if(nuevo != '')
		{
	   $.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?nuevo=true',
		  type:'post',
		  dataType:'json',
		  data:{parametro:parametro},		 
		  success: function(response){
		  	if(response=='ok')
		  	{
		       $('#modal_espera').modal('hide');
		       $('#select_cursos').val('');
		  		validar_estudiante(nuevo,clave,true);
		  	}else if(response=='ci')
            {
            	Swal.fire({
				 type: 'error',
				 title: 'Cedula ingresada incorrecta',
				 text: 'Asegurese que su numero sea correcto!'
             });
            	$('#modal_espera').modal('hide');

            }
			
			console.log(response);
		}
	  });
	 }

	}

	function actualizar_est(tab){
		//var tab1 = tab;
		if($('#select_cursos').val() !='')
		{	
		pasar_tab(tab);
		var parametro = {
			'codigo':$("#txt_cedula").val(),
			'cedula':$("#txt_cedula").val(),
			'codigo':$("#codigo").val(),
			'nombre':$("#txt_nombre").val(),
			'select_curso':$("#select_cursos").val(),			
			'M':$("#cbx_sexo_M").is(':checked'),		
			'F':$("#cbx_sexo_F").is(':checked'),
		    'email':$("#email_estudiante").val(),
			'procedencia':$("#procedencia").val(),
			'matricula':$("#matricula").val(),
		    'nacionalidad':$("#txt_nacionalidad").val(),
			//'id':$("#txt_curso").val(),
			'provincia':$("#select_provincias").val(),
			'ciudad':$('#select_ciudad option:selected').html(),
			'seccion':$("#txt_seccion").val(),
			'fechan':$('#fecha_n').val(),
			'nom_curso':$('#select_cursos option:selected').html(),
			'procedencia':$('#procedencia').val(),
			'observacion':$('#observaciones').val(),
			'matricula_n':$('#txt_matricula_num').val(),
			'tomo':$('#txt_tomo').val(),
			'dir_est':$('#txt_dir_est').val(),
			'fecha_m':$('#txt_fecha_m').val(),
		}
		$.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?update_alumno=true',
		  type:'post',
		  dataType:'json',
		  data:{parametro:parametro},		 
		  success: function(response){
		  		if(response ==1)
		  	{
		  		Swal.fire({
		  		 	//position: 'top-end',
                    type: 'success',
                    title: 'Datos de Estudiante actualizados!',
                    showConfirmButton: true
                    //timer: 2500
                 });

		  		if(tab=='')
		  		{
		  		actualizar_familia(tab);
		  	    }
		  	}else
		  	{
		  		Swal.fire({
				 type: 'error',
				 title: 'No se pudo guardar los datos',
				 //text: 'the quantity entered is outside the order range!'
                });
                $('#modal_espera').modal('hide');
		  	}
		  	

		}
	  });
	}else
	{
			Swal.fire({
				 type: 'error',
				 title: 'Debe escoger y guardar curso para matricularse',
				 //text: 'the quantity entered is outside the order range!'
          });

	}
	}

	function actualizar_familia(tab){

		 pasar_tab(tab);
		var parametro = {			
			//datos de padres de familia
			'codigo':$("#txt_cedula").val(), 
			'nombre_p':$("#txt_nombre_p").val(),
			'ci_p':$("#txt_ci_p").val(),
			'nacionalidad_p':$("#txt_nacionalidad_p").val(),
			'trabajo_p':$("#txt_trabajo_p").val(),
			'telefono_p':$("#txt_telefono_p").val(),
			'celular_p':$("#txt_celular_p").val(),
			'profesion_p':$("#txt_profesion_p").val(),
			'email_p':$("#txt_email_p").val(),
			'ocupacion_p':$("#txt_ocupacion_p").val(),

			//datos de madre de familia 
			'nombre_m':$("#txt_nombre_m").val(),
			'ci_m':$("#txt_ci_m").val(),
			'nacionalidad_m':$("#txt_nacionalidad_m").val(),
			'trabajo_m':$("#txt_trabajo_m").val(),
			'telefono_m':$("#txt_telefono_m").val(),
			'celular_m':$("#txt_celular_m").val(),
			'profesion_m':$("#txt_profesion_m").val(),
			'ocupacion_m':$("#txt_ocupacion_m").val(),
			'email_m':$("#txt_email_m").val(),

		}
		$.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?update_fami=true',
		  type:'post',
		 //dataType:'json',
		  data:{parametro:parametro},		 
		  success: function(response){
		  	if(response ==1)
		  	{
		  		Swal.fire({
		  		 	//position: 'top-end',
                    type: 'success',
                    title: 'Datos de Familia actualizados!',
                    showConfirmButton: true
                    //timer: 2500
                 });

		  		if(tab=='')
		  		{
		  		 actualizar_representante();
		  	    }
		  	}else
		  	{
		  		Swal.fire({
				 type: 'error',
				 title: 'No pudo guardar los datos',
				 //text: 'the quantity entered is outside the order range!'
          });
		  	}
		  	
		}
	  });
	}
	function actualizar_representante(){
		var parametro = {			
			//datos de padres de familia
			'codigo':$("#txt_cedula").val(), 
			'nombre_r':$("#txt_nombre_r").val(),
			'ci_r':$("#txt_ci_r").val(),
			'ocupacion_r':$("#txt_ocupacion_r").val(),
			'trabajo_r':$("#txt_trabajo_r").val(),
			'telefono_r':$("#txt_telefono_r").val(),
			'celular_r':$("#txt_celular_r").val(),
			'profesion_r':$("#txt_profesion_r").val(),
			'email_r':$("#txt_email_r").val(),
			'email_fac_r':$("#txt_email_fac_r").val(),
		}
		$.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?update_repre=true',
		  type:'post',
		 // dataType:'json',
		  data:{parametro:parametro},		 
		  success: function(response){
		  	if(response ==1)
		  	{
		  		if($('#file').val() != '')
		  		 {
		  		 	$('#b_file_2').click();
		  		 }
		  		 if($('#file_pago').val() != '')
		  		 {
		  		 	$('#b_file_3').click();
		  		 }
	
		  		 Swal.fire({
		  		 	//position: 'top-end',
                    type: 'success',
                    title: 'Datos de Representante actualizados!',
                    showConfirmButton: true
                    //timer: 2500
              });
		  		 
		  		  generar_archivos();
		  		 
		  	}else
		  	{
		  		Swal.fire({
				 type: 'error',
				 title: 'No se pudo guardar los datos',
				 //text: 'the quantity entered is outside the order range!'
               });

                $('#modal_espera').modal('hide');
		  	}
		}
	  });
	}


	function documentos()
	{
		archivos(false);
		enviar_email();
	}

	function madre_repre()
	{
		    $('#file_rep').css('display','none');
		    $("#txt_nombre_r").val($("#txt_nombre_m").val());
			$("#txt_ci_r").val($("#txt_ci_m").val());
			$("#txt_profesion_r").val($("#txt_profesion_m").val());
			$("#txt_ocupacion_r").val($("#txt_ocupacion_m").val());
			$("#txt_telefono_r").val($("#txt_telefono_m").val());
			$("#txt_celular_r").val($("#txt_celular_m").val());
			$("#txt_trabajo_r").val($("#txt_trabajo_m").val());
			if($("#txt_email_fac_r").val() =='')
			{
			  $("#txt_email_fac_r").val($("#txt_email_m").val()); 
		    }else
		    {
		    	if($("#txt_email_fac_r").val() == $("#txt_email_p").val())
		    	{
		    	   $("#txt_email_fac_r").val($("#txt_email_m").val()); 
		    	}
		    }
			$("#txt_email_r").val($("#txt_email_m").val());
	}
	function ver_archivo()
	{
		$('#file_rep').css('display','block');
	}
	function ocultar_archivo()
	{
		$('#file_rep').css('display','none');
		$('#file').val('');
	}
	function limpiar_repre()
	{

		    $('#file_rep').css('display','block');
		    $("#txt_nombre_r").val('');
			$("#txt_ci_r").val('');
			$("#txt_profesion_r").val('');
			$("#txt_ocupacion_r").val('');
			$("#txt_telefono_r").val('');
			$("#txt_celular_r").val('');
			$("#txt_trabajo_r").val('');
			$("#txt_email_fac_r").val('');
			$("#txt_email_r").val('');
	}
	function padre_repre()
	{

		    $('#file_rep').css('display','none');
		    $("#txt_nombre_r").val($("#txt_nombre_p").val());
			$("#txt_ci_r").val($("#txt_ci_p").val());
			$("#txt_profesion_r").val($("#txt_profesion_p").val());
			$("#txt_ocupacion_r").val($("#txt_ocupacion_p").val());
			$("#txt_telefono_r").val($("#txt_telefono_p").val());
			$("#txt_celular_r").val($("#txt_celular_p").val());
			$("#txt_trabajo_r").val($("#txt_trabajo_p").val());
			if($("#txt_email_fac_r").val() =='')
			{
			  $("#txt_email_fac_r").val($("#txt_email_p").val()); 
		    }else
		    {
		    	if($("#txt_email_fac_r").val() == $("#txt_email_m").val())
		    	{
		    	   $("#txt_email_fac_r").val($("#txt_email_p").val()); 
		    	}
		    }

			$("#txt_email_r").val($("#txt_email_p").val());
	}


	function archivos(email)
	{
        var banco = $('#txt_cod_banco').val();
		var pass = $('#txt_clave').val();
		var url = '../controlador/educativo/detalle_estudianteC.php?generar_archivos=true&usu='+$("#txt_cod_banco").val()+'&pass='+$("#txt_clave").val()+'&nuevo_usu=false&email='+email;
		window.open(url)
		var url1 = '../controlador/educativo/detalle_estudianteC.php?generar_archivos2=true&usu='+$("#txt_cod_banco").val()+'&pass='+$("#txt_clave").val()+'&nuevo_usu=false&email='+email;
		window.open(url1)
		var url2 = '../controlador/educativo/detalle_estudianteC.php?generar_archivos3=true&usu='+$("#txt_cod_banco").val()+'&pass='+$("#txt_clave").val()+'&nuevo_usu=false&email='+email;
		window.open(url2)
		validar_estudiante(banco,pass,false);

	}

	function generar_archivos()
	{

	   $.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?generar_archivos=true&usu='+$("#txt_cod_banco").val()+'&pass='+$("#txt_clave").val()+'&nuevo_usu=false&email=true',
		  type:'post',
		  dataType:'json',
		 // data:{usu:usu,pass:pass,nuevo:nuevo},
		  success: function(response){	
		  console.log(response);
			if(response == null || response == '')
			{
				 generar_archivos2();	  	
		    }

		}
	  });

	}
	function generar_archivos2()
	{	

	   $.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?generar_archivos2=true&usu='+$("#txt_cod_banco").val()+'&pass='+$("#txt_clave").val()+'&nuevo_usu=false&email=true',
		  type:'post',
		  dataType:'json',
		  //data:{usu:usu,pass:pass,nuevo_usu:nuevo},
		  success: function(response){		  	
			console.log(response);
			if(response ==null || response == '')
			{
		  	  generar_archivos3();
		    }
		}
	  });

	}
	function generar_archivos3()
	{

	   $.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?generar_archivos3=true&usu='+$("#txt_cod_banco").val()+'&pass='+$("#txt_clave").val()+'&nuevo_usu=false&email=true',
		  type:'post',
		  dataType:'json',
		 // data:{usu:usu,pass:pass,nuevo_usu:nuevo},
		  success: function(response){
		  if(response == null || response ==''){	
		  	var op = $('#txt_op').val();
		  	if(op == 'env')
		  	{
		  		enviar_email();
		  		$('#txt_op').val('');
		  	}else if(op == 'im_d')
		  	{
		  	  archivos(false);
		  	  $('#txt_op').val('');
		  	}else if(op=='fin_g')
		  	{
		  		archivos(false);
		  		enviar_email();
		  		$('#txt_op').val('');
		  	}
		  }
			
		}
	  });

	}


	function procesos(op)
	{
		event.preventDefault();
		$('#txt_op').val(op);
		if($('#select_cursos').val()!='')
		{
		Swal.fire({
			title: 'Desea guardar los cambios realizados?',
			//text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Guardar'
		}).then((result) => {
		if (result.value) {
			actualizar_est('');	
		   $('#titulo').html('Descargando documentos');	  
		   
		}
	  })
	  }else
	  {
	  	Swal.fire({
				 type: 'error',
				 title: 'Debe escoger y guardar curso para matricularse',
				 //text: 'the quantity entered is outside the order range!'
          });
	  }		
	}

	function numero_patricula()
	{


         var cod =  $('#select_cursos').val();
         var detalle =$('#select_cursos option:selected').html();
          //$('#curso_detalle').val(detalle);
          $('#txt_curso_cod').val(detalle);
          $('#txt_curso').val(cod);
          //lista_cursos();

          $.ajax({
		url: '../controlador/educativo/detalle_estudianteC.php?cursos=true',
		type:'post',
		dataType:'json',
		//data:{categoria,categoria},
		success: function(response){
			 response.forEach(function(name,index){
			 	if(name.Curso == cod)
			 	{	
			     $('#curso_detalle').val(name.Curso_Superior);
			     $('#txt_seccion').val(name.Seccion);		
			 	//console.log(name.Curso_Superior);
			 	//console.log(name.Seccion);
			 	}
			 });
		}
	})


		var curso = $('#txt_curso').val();
		var cur1 =curso.substr(0,-1);
	   $.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?num_matricula=true',
		  type:'post',
		  dataType:'json',
		  data:{curso:curso},
		  success: function(response){		  	
			console.log(response);
			if(response!=null )
			{
				$('#txt_matricula_num').val(response);
				$('#txt_tomo').val(response);
				$('#txt_pag').val(response.substr(0,3));
		  		console.log(response);
		    }
		}
	  });

	}
	function validar_gardado_email()
	{
		if($('#select_cursos').val()!='')
		    {
		Swal.fire({
			title: 'Desea guardar los cambios realizados?',
			//text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Guardar'
		}).then((result) => {
		if (result.value) {
			console.log(result.value+'--');
			

			      if($('#file').val() != '')
		  		     {
		  		 	  $('#b_file_2').click();
		  		     }
		  		  if($('#file_pago').val() != '')
		  		    {
		  		 	 $('#b_file_3').click();
		  		     }
			      actualizar_est();
			      actualizar_familia();			      
			      actualizar_representante();
			      generar_archivos();
		          $('#titulo').html('Guardando y enviando correos');       

	       

         }
	
	  })
	}else
	    {
	       	Swal.fire({
			 type: 'error',
			 title: 'Debe escoger y guardar curso para matricularse',
			 //text: 'the quantity entered is outside the order range!'
                });
       }

	}

	function validar_gardado_pdf()
	{
		if($('#select_cursos').val()!='')
		{
		Swal.fire({
			title: 'Desea guardar los cambios realizados?',
			//text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Guardar'
		}).then((result) => {
		if (result.value) {
			actualizar_est();
			actualizar_familia();						
			actualizar_representante();
			generar_archivos();
			archivos(false);	
		   $('#titulo').html('Descargando documentos');	  
		   
		}
	  })
	  }else
	  {
	  	Swal.fire({
				 type: 'error',
				 title: 'Debe escoger y guardar curso para matricularse',
				 //text: 'the quantity entered is outside the order range!'
          });
	  }
	}

	function enviar_email()
	{
		 //$('#modal_espera').modal('show');
		// $('#titulo').html('Enviando correos');
		var usu = $("#txt_cod_banco").val();
		var pass=$("#txt_clave").val();
		var nuevo_usu=false

	   $.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?enviar_email_=true',
		  type:'post',
		  dataType:'json',
		  data:{usu:usu,pass:pass,nuevo_usu:nuevo_usu},
		  success: function(response){		  	
			console.log(response);
			if(response!=null )
			{				
		  		if(response == true)
		  		{

		  		$('#modal_espera').modal('hide');
		  			Swal.fire({
		  		 	//position: 'top-end',
                    type: 'success',
                    title: 'Documentos Enviados a su correo!',
                    showConfirmButton: true
                    //timer: 2500
                 });
		  		}
		  		console.log(response);
		    }
		}
	  });

	}
	function validar_repre()
	{
		var ci_r = $('#txt_ci_r').val();
		var ci_p = $('#txt_ci_p').val();
		var ci_m = $('#txt_ci_m').val();
		if(ci_r != ci_p && ci_r != ci_m)
		{
			$('#file_rep').css('display','block');
		}
		else
		{
			$('#file_rep').css('display','none');			
		}
	}

	function generar_excel()
	{
		
	  var cod = $('#codigo_cli').val();
	   var url = '../controlador/educativo/detalle_estudianteC.php?imprimir_excel=true&codigo='+cod;
	   window.open(url);

	}
	function generar_pdf()
	{

		var cod = $('#codigo_cli').val();
		var url = '../controlador/educativo/detalle_estudianteC.php?imprimir_pdf=true&codigo='+cod;
		window.open(url);
		

	}

	// function ver_factura()
	// {
	// 	 var cod = $('#cod_checks').val();
	// 	if(cod != '')
	// 	{
	// 	 var cods = cod.substring(1).split('/');
	// 	// console.log(cods.length);	 

	// 	 for (var i = 1; i <cods.length+1; i++){
	// 	 	let values = cods[i-1].split('-');
	// 	   // console.log('dsdsd');			 
	// 	   var url = '../controlador/educativo/detalle_estudianteC.php?ver_fac=true&codigo='+values[1]+'&ser='+values[0]+'&ci='+$('#txt_cod_banco').val();		
	// 	    window.open(url,'_blank');
	// 	 }
		
	//     }else
	//     {
	//     	Swal.fire({
	// 			 type: 'error',
	// 			 title: 'Seleccione por lo menos una factura',
	// 			 //text: 'the quantity entered is outside the order range!'
 //               });
	//     }
		
	// }

	function validarc($id,$ta)
	{
		if($("#"+$id+"").is(':checked'))
		{
			var cadena = $id.replace(/-/, "");
			var id = cadena.split('_');
		    $('#ver_fac').css('display','block');
		    let tmp =  $('#cod_checks').val();
		    $('#cod_checks').val(tmp+'/'+id[1].slice(0,-2));
		     console.log(id[1]);
		  
		}else
		{
		  let tmp =  $('#cod_checks').val();
		  var cadena = $id.replace(/-/, "");
		  var id = cadena.split('_');
		  let nuevo = tmp.replace("/"+id[1].slice(0,-2),'');
		  $('#cod_checks').val(nuevo);
		  if(nuevo == '')
		  {

			$('#ver_fac').css('display','none');
		  }
		   
		 // $('#ver_fac').css('display','none');
		}	
	}


	function registro_matricula()
	{
		var usuario = $('#txt_cod_banco').val();
		 $.ajax({
		  url: '../controlador/educativo/detalle_estudianteC.php?nueva_matricula=true',
		  type:'post',
		  dataType:'json',
		  data:{usuario:usuario},
		  success: function(response){
		  if(response=='' || response == null)
		  {
		  	$('#nueva_matricula').modal('hide');
		  	login_data();
		  }	  	
			
		}
	  });

	}

</script>

<!--<h2>Balance de Comprobacion/Situación/General</h2>-->
    <div class="row">
    	
    	<div class="col-sm-3">
      		   <b>CODIGO BANCO / CEDULA</b>
      		   <input type="text" name="txt_cod_banco" id="txt_cod_banco" class="form-control input-sm"  placeholder="Cedula de identidad">  
        </div>
        <div class="col-sm-2">
      		   <b>Clave alumno </b>
      		   <input type="text" name="txt_clave" id="txt_clave" class="form-control input-sm" placeholder="*******"  /> 
      		   <input type="text" name="id" id="id" hidden="" />
      		   <input type="text" name="codigo_cli" id="codigo_cli" hidden="" />
        </div>
        <div class="col-sm-2">
        	<br>
        	<button onclick="login_data();" class=" btn btn-primary btn-sm" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar</button>
        </div>
    	
    </div>
    <br>



<ul class="nav nav-tabs">
    <li class="active" id="home_t"><a data-toggle="tab" href="#home">Matricula</a></li>
    <li id="menu2_t"><a data-toggle="tab" href="#menu2">Familiares</a></li>
    <li id="menu3_t"><a data-toggle="tab" href="#menu3">Representante</a></li>
    <li id="menu4_t"><a data-toggle="tab" href="#menu4">Facturas emitidas</a></li>
    <div class="text-right">
	     <input type="" name="txt_op" id="txt_op" hidden=""><!-- 
	  	 <button type="button" onclick="abrir_modal()">modal</button> -->

	  	 <button type="button" class="btn btn-default  btn-sm" id='imprimir_pdf' onclick="procesos('im_d')"><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Imprimir documentos</button>
	  	 <button type="button" class="btn btn-default  btn-sm" id='enavia_email' onclick="procesos('env')"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>  Enviar email</button>
	</div>
</ul>
  <div class="tab-content" style="background-color:#E7F5FF;">
    <div id="home" class="tab-pane fade in active">
    	<div class="row">
    		<div class="col-sm-9">
    			<h3>MATRICULA</h3>
    		</div>
    		<div class="col-sm-3 text-right">
    			<br>
    			<button type="button" id="btn_guar_est" class="btn btn-success btn-sm" onclick="actualizar_est('menu2')"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>  Guardar y continuar</button>    			
    		</div>
    		
    	</div>    
    	
    <div class="row" class="home_1">
      	<div class="col-sm-9">
      		<div class="col-sm-12">
      			<select class="form-control input-sm" id="select_cursos" onchange="numero_patricula()">
      			     <option value=""><b>Seleccione</option>      			
      		     </select>       			
      		</div>
      		<div class="col-sm-9"> 
      		   <b>APELLIDOS Y NOMBRES</b>
      		   <input type="text" name="" class="form-control input-sm" id="txt_nombre">    		
      	    </div>      	    
         	<div class="col-sm-3">
         		<br>
      		    <label><input type="radio" name="cbx_sexo" id="cbx_sexo_M" checked=""> MASCULINO</label> 
      		    <br>
      		    <label><input type="radio" name="cbx_sexo" id="cbx_sexo_F"> FEMENINO</label> 
      	    </div>
      		<div class="col-sm-8">
      	       <b>NIVEL DE ESTUDIO</b>
      		  <input type="" name="" class="form-control input-sm" id="curso_detalle" disabled="">
      	    </div>
      	    <div class="col-sm-4">
      		   <input type="" name="" value="" id="txt_curso" disabled="">
      		   <input type="" name="" value="" id="txt_seccion" disabled="">
      	    </div>
      	    <div class="row">
      	    	<br>
	      	    <div class="col-sm-12">
	      		    <div class="col-sm-3">
	      			    <b>CURSO</b>
	      			    <input type="text" name="" class="form-control input-sm" id="txt_curso_cod" size=''>      			
	      		    </div> 
	      		    <div class="col-sm-3">
	      			    <b>FECHA MATRICULA.</b>
	      			    <input type="date" name="" class="form-control input-sm" id="txt_fecha_m"  value="<?php echo date("Y-m-d");?>"> 
	      		    </div>
		      		<div class="col-sm-3">
		      			<b>TOMO No.</b>
		      			<input type="numeber" name="" class="form-control input-sm" id="txt_tomo" readonly="">      			
		      		</div> 
		      		<div class="col-sm-3">
		      			<b>PAG No.</b>
		      			<input type="text" name="" class="form-control input-sm" id="txt_pag" disabled="">      			
		      		</div> 
	      		</div>      		
	      	</div>      		
      	</div>
      	<div class="col-sm-3 text-center" style="border: 1px solid;">
      		<form enctype="multipart/form-data" id="img_ajax" method="post">
      			<img src="../../img/jpg/sinimagen.jpg" height="140px" weight="100px" id="foto_alumno">
      		    <br> 
      		    <input type="file" name="" class="btn-sm" id="foto" name="foto" width="100px">
      		    <input type="" name="codigo_foto" id="codigo_foto" hidden="">
      		    <input type="" name="codigo" id="codigo" hidden="">
      		    <button type="button" class="upload btn btn-primary  btn-sm" id="btn_subir_foto"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span> Subir imagen</button>       			
      		</form>      		
      	</div>      	
     </div>
    <div class="row">   
      	
      	<div class="col-sm-12">
      		<div class="col-sm-4">
      			<b>FECHA NACIMIENTO</b>
      			<input type="date" name="" class="form-control input-sm" id="fecha_n">      			
      		</div> 
      		<div class="col-sm-4">
      			<b>MATRICULA No</b>
      			<input type="text" name="" class="form-control input-sm" placeholder="" id="txt_matricula_num" readonly="">      			
      		</div>
      		<div class="col-sm-4">
      			<b>CEDULA DE IDENTIDAD</b>
      			<input type="numeber" name="" class="form-control input-sm" id="txt_cedula">      			
      		</div>       		       		
      	</div>

      	<div class="col-sm-12">
      		<div class="col-sm-4">
      			<b>NACIONALIDAD</b>
      			<input type="text" name="" class="form-control input-sm" id="txt_nacionalidad">      			
      		</div> 
      		<div class="col-sm-4">
      			<b>PROVINCIA</b>
      			<select class="form-control input-sm" id="select_provincias" onchange="ciudad(this.value)">
      				<option value="">seleccione provincia</option>
      			</select>   			
      		</div>
      		<div class="col-sm-4">
      			<b>CIUDAD</b>
      			<select class="form-control input-sm" id="select_ciudad">
      				<option value="">seleccione ciudad</option>
      			</select>       			
      		</div>       		       		
      	</div>
      	<div class="col-sm-12">
      		<div class="col-sm-8">
      			<b>DIRECCION</b>
      		    <input type="text" name="" class="form-control input-sm" id="txt_dir_est">      			
      		</div>
      		<div class="col-sm-4">
      			<b>PROCEDENCIA</b>
      		    <input type="text" name="" class="form-control input-sm" id="procedencia">      			
      		</div>
      	</div>
      	<div class="col-sm-12">
      		<!--
      		<div class="col-sm-3">
      			<br>
      		    <input type="text" name="" class="form-control input-sm"> 
      		</div>-->
      	</div>
      	<div class="col-sm-12">
      		<div class="col-sm-12">
      			<b>CORREO DE ESTUDIANTE</b>
      		    <input type="text" name="" class="form-control input-sm" id="email_estudiante">      			
      		</div>
      	</div>
      	<div class="col-sm-12">
      		<div class="col-sm-12">
      			<b>OBSERVACIONES</b>
      		    <textarea class="form-control input-sm" id="observaciones" onblur="pasar_tab('menu2')">
      		    	
      		    </textarea>     			
      		</div>
      	</div>
      	<div class="col-sm-12">
      		<div class="col-sm-9" style="text-align: right;">
      			<b>DEUDA PENDIENTE</b>
      		</div>
      		<div class="col-sm-3">      			
      			<input type="text" name="" class="form-control input-sm" disabled="">     			
      		</div>
      	</div>      	          	
    </div>
      <br>
    </div>
    <div id="menu2" class="tab-pane fade">
    	<div class="row">
    		<div class="col-sm-9">
    			<h3>FAMILIARES</h3>    			
    		</div>
    		<div class="col-sm-3 text-right"><br>
    			<button type="button" class="btn btn-success btn-sm" id="btn_guar_fami" onclick="actualizar_familia('menu3')"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>  Guardar y continuar</button>    			
    		</div>
    	</div>    	
      <div class="row">
      	<div class="col-sm-12">
      		<div class="col-sm-6">
      			<div class="panel panel-default">
      			   <div class="panel-heading" style="background-color: #c7e5f9">Datos de padre</div>
      			   <div class="panel-body" style="background-color:#E7F5FF;">
      			   	 <b>NOMBRE</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_nombre_p">
      			   	 <b>CEDULA</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_ci_p" max="10">
      			   	 <b>NACIONALIDAD</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_nacionalidad_p">
      			   	 <b>PROFESION</b>
      			   	 <input type="text" name="txt_profesion_p" class="form-control input-sm" id="txt_profesion_p">
      			   	 <b>OCUPACION</b>
      			   	 <input type="text" name="txt_ocupacion_p" class="form-control input-sm" id="txt_ocupacion_p">
      			   	 <b>LUGAR DE TRABAJO</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_trabajo_p">
      			   	 <b>TELEFONO DE TRABAJO</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_telefono_p">
      			   	 <b>TELEFONO CELULAR</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_celular_p">
      			   	 <b>EMAIL</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_email_p">
      			   </div>
      			</div>
      		</div>
      		<div class="col-sm-6">
      			<div class="panel panel-default">
      			   <div class="panel-heading" style="background-color: #c7e5f9">Datos de madre</div>
      			   <div class="panel-body" style="background-color:#E7F5FF;">
      			   	 <b>NOMBRE</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_nombre_m">
      			   	 <b>CEDULA</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_ci_m" max="10">
      			   	 <b>NACIONALIDAD</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_nacionalidad_m">
      			   	 <b>PROFESION</b>
      			   	 <input type="text" name="txt_profesion_m" class="form-control input-sm" id="txt_profesion_m">
      			   	 <b>OCUPACION</b>
      			   	 <input type="text" name="txt_ocupacion_m" class="form-control input-sm" id="txt_ocupacion_m">      			   	 
      			   	 <b>LUGAR DE TRABAJO</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_trabajo_m">
      			   	 <b>TELEFONO DE TRABAJO</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_telefono_m">
      			   	 <b>TELEFONO CELULAR</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_celular_m">
      			   	 <b>EMAIL</b>
      			   	 <input type="text" name="" class="form-control input-sm" id="txt_email_m">
      			   </div>
      			</div>
      		</div>      		
      	</div>      	
      </div>
       <div class="row text-right">
      <br><br>
    </div>
    </div>
    <div id="menu3" class="tab-pane fade">
    	<div class="row">
    		<div class="col-sm-3">
    			<h3>REPRESENTANTE</h3>
    		</div>
    		<div class="col-sm-6"><br>
    			<form enctype="multipart/form-data" id="file_depo" method="post">
    			<div class="row">
    				<div class="col-sm-6">
    					  <input  class="btn btn-succes btn-sm"  type="file" id="file_pago"/> 
      		       <input  type="text"  class="form-control-file" id="nom_1" name="nom_1" hidden="" />
    				</div>    			
    				<div class="col-sm-6">
    					 <button type="button" class="btn btn-primary btn-sm" id="b_file_3"><span class="fa fa-fw fa-money" aria-hidden="true"></span> Subir pago</button>     					
    				</div>   	
    			</div>
    		</form>    			
    		</div>
    		<div class="col-sm-3"><br>
    			<button type="button" class="btn btn-success btn-sm" id="btn_guar_repre" onclick="actualizar_representante()"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>  Guardar</button>
    			<button type="button" class="btn btn-success btn-sm" id="btn_guar_fin_rep" onclick="procesos('fin_g')"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>  Guardar y Finalizar</button>    			
    		</div>
    	</div>           
      <div class="row">
      	<div class="col-sm-12">
      	   <div class="col-sm-3">
      		   <b>REPRESENTANTE</b>
      	   </div>
      	   <div class="col-sm-9">
      	   	   <div class="col-sm-4 text-right">
      	   	   	  <button class="btn btn-primary btn-sm" onclick="padre_repre()" title="Traer datos padre"><span class="fa fa-fw fa-male" aria-hidden="true"></span> Padre</button>
      		      <button class="btn btn-danger btn-sm" onclick="madre_repre()" title="Traer datos madre"><span class="fa fa-fw fa-female" aria-hidden="true"></span> Madre</button>
      		       <!--<button class="btn btn-default btn-sm" onclick="limpiar_repre()" title="Traer datos madre"><span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span> Otro</button>-->      		       
      		   </div>
      		   <div class="col-sm-1">
      		       	  <div class="dropdown">
      		       	  	<button class="btn btn-default btn-sm" type="button" data-toggle="dropdown">Otro <span class="caret"></span></button>
      		       	  	 <ul class="dropdown-menu">
      		       	  	 	<li><a href="#" onclick="limpiar_repre();">Limpiar Representante</a></li>
      		       	  	 	<li><a href="#" onclick="ver_archivo()">Subir Archivo</a></li>
      		       	  	 </ul>
      		       	  </div>
      		       	</div>

      		    <form enctype="multipart/form-data" id="file_rep" method="post"  style="display: none;" class="col-sm-7">
      		    <div class="col-sm-7"> 
      		    	<input  class="btn btn-succes btn-sm"  type="file" id="file"/> 
      		        <input  type="text"  class="form-control-file" id="nom" name="nom" hidden="" />      		      
      		     </div>
      		     <div class="col-sm-5">    
      		       <div class="btn-group" role="group" aria-label="Basic example">  
      		       <button type="button" class="btn btn-primary btn-xs" id="b_file_2">Subir Archivo</button>
      			   <button type="button" class="btn btn-danger btn-xs" onclick="ocultar_archivo()">Cancelar</button>       		   
      		       	
      		       </div>  		     	
      			  </div><br><br>
      		        <h6><i>Subir poder notarizado, o presentarlo en  secretaria cuando nos reintegremos a labores presenciales.</i></h6>
      		   </form> 
      	   </div>
         </div>
         <br><br>
      	<div class="col-sm-12">
      		<div class="col-sm-3">
      			<b>CEDULA DE IDENTIDAD </b>    			
      		</div>
      		<div class="col-sm-6">
      			<input type="text" name="" class="form-control input-sm" id="txt_ci_r" onblur="validar_repre()" max="10">
      			<br>      			
      		</div>      		
      	</div>
      	<div class="col-sm-12">
      		<div class="col-sm-3">
      			<b>APELLIDOS Y NOMBRES </b>    			
      		</div>
      		<div class="col-sm-6">
      			<input type="text" name="" class="form-control input-sm" id="txt_nombre_r"> 
      			<br>     			
      		</div>      		
      	</div>      	
      	 <div class="col-sm-12">
      		 <div class="col-sm-3">
      			 <b>PROFESION</b>
      		 </div>
      		 <div class="col-sm-6">
      			 <input type="text" name="" class="form-control input-sm" id="txt_profesion_r">
      			 <br>
      		 </div>
      	 </div>
      	 <div class="col-sm-12">
      		 <div class="col-sm-3">
      			 <b>OCUPACION</b>
      		 </div>
      		 <div class="col-sm-6">
      			 <input type="text" name="" class="form-control input-sm" id="txt_ocupacion_r">
      			 <br>
      		 </div>
      	 </div>
      	 <div class="col-sm-12">
      		 <div class="col-sm-3">
      			 <b>TELEFONO</b>
      		 </div>
      		 <div class="col-sm-2">
      			 <input type="text" name="" class="form-control input-sm" id="txt_telefono_r" max="10">
      			 <br>
      		 </div>
      		 <div class="col-sm-1">
      			 <b>CELULAR</b>
      		</div>
      		<div class="col-sm-3">
      		  <input type="text" name="" class="form-control input-sm" id="txt_celular_r" max="10">
      		  <br>
      	    </div>
        </div>
      	<div class="col-sm-12">
      		<div class="col-sm-3">
      			<b>LUGAR DE TRABAJO</b>     			
      		</div>
      		<div class="col-sm-6">
      			<input type="text" name="" class="form-control input-sm" id="txt_trabajo_r"> 
      			<br>     			
      		</div>      		
      	</div> 
      	<div class="col-sm-12">
      		<div class="col-sm-3">
      			<b>CORREO FACTURA</b>     			
      		</div>
      		<div class="col-sm-6">
      			<input type="text" name="" class="form-control input-sm" id="txt_email_fac_r"> 
      			<br>     			
      		</div>      		
      	</div>
      	<div class="col-sm-12">
      		<div class="col-sm-3">
      			<b>CORREO ELECTRONICO</b>
      		</div>
      		<div class="col-sm-6">
      			<input type="text" name="" class="form-control input-sm" id="txt_email_r">
      			<br>
      		</div>
      	</div>      			   	  	
      </div>  	
   </div>
   <div id="menu4" class="tab-pane fade">
   	<div class="row">
   		<div class="col-sm-9"><h3>Facturas Emitidas</h3></div>
   		<div class="col-sm-3">
   			<div class="btn-group">
   				<br>
   				<button class="btn btn-sm btn-primary" onclick="generar_pdf()"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> PDF</button>
   				<button class="btn btn-sm btn-primary" onclick="generar_excel()"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Excel</button>
   				<!-- <button class="btn btn-sm btn-default" onclick="ver_factura()" id="ver_fac" style="display: none;"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver factura</button> -->
   			</div>  
   		</div>
   	</div>
    	<div class="row">
    		
    		<input type="" name="cod_checks" id="cod_checks" hidden="">
    		<div class="col-sm-12 table-responsive" id="factura_emi">   			
    			
    		</div>    		
    	</div>	
      </div>
 </div>	
</div>

<!-- Modal -->
<div class="modal fade" id="mymodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> El estudiante no esta registrado o su clave o usuario estan mal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">       
        Desea Registrarlo.. ?
        <div class="spinner-border"></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="nuevo_registro()">Registrar nuevo Estudiante</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="nueva_matricula" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> El estudiante no tiene una matricula anterior</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">       
        Desea Crear una nueva matricula?
        <div class="spinner-border"></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="registro_matricula()">Crear matricula</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>




