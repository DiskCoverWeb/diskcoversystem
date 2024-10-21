<?php
/**
 * Autor: JAVIER FARINANGO.
 * Mail:  
 * web:   www.diskcoversystem.com
 */
?>
<script type="text/javascript">
	
   // function generarArchivos()
   // {

// 	      	$('#myModal_espera').modal('show');
// 	    var parametros = 
// 	    {
	      
// 	    }
// 	     $.ajax({
// 	       data:  {parametros:parametros},
// 	      url:   '../controlador/migracion/migrar_datosC.php?generarArchivos=true',
// 	      type:  'post',
// 	      dataType: 'json',
// 	      success:  function (response) {  
// 	      	$('#myModal_espera').modal('hide');
// 	      	Swal.fire("","Geraro lod archivos","success")
	       
// 	      },error: function (error) {		      
// 	      	$('#myModal_espera').modal('hide');
// 		    },
// 	    });
   // }
   var columnasEsquema = [];
	var baseDatos = '';
	$(document).ready(() => {
		//$('#cont-subir').hide();
		consultarBasesDatos();
	});

	function consultarBasesDatos(){
		$('#SelectDatabase').select2({
			placeholder: 'Seleccione la Base de Datos',
			ajax: {
				url: '../controlador/migracion/migrar_datosC.php?ConsultarBasesDatos=true',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						query: params.term,
					}
				},
				processResults: function (data) {
					return {
						results: data
					};
				},
				cache: true
			}
		});
		
		$('#SelectDatabase').on('select2:select', (e) => {
			baseDatos = e.params.data.text;
			//console.log(baseDatos);
			$('#generar_esquemas').removeAttr('disabled');
			$('#generar_tablas').removeAttr('disabled');
			$('#generar_archivos').removeAttr('disabled');
		});
	}

	function generarEsquemas()
   	{
   		url = '../controlador/migracion/migrar_datosC.php?generarEsquemas=true&basedatos='+baseDatos;
   		window.open(url,"_blank");
   }

	function generarArchivos()
   	{
   		url = '../controlador/migracion/migrar_datosC.php?generarArchivos=true&basedatos='+baseDatos;
   		window.open(url,"_blank");
   }

   function generarSP()
   {
   		url = '../controlador/migracion/migrar_datosC.php?generarSP=true';
   		window.open(url,"_blank");
   }
   // function generarSP2()
   // {

   // 	$('#myModal_espera').modal('show');
// 	    var parametros = 
// 	    {
	      
// 	    }
// 	     $.ajax({
// 	       data:  {parametros:parametros},
// 	      url:   '../controlador/migracion/migrar_datosC.php?generarSP=true',
// 	      type:  'post',
// 	      dataType: 'json',
// 	      success:  function (response) { 
// 	      	$('#myModal_espera').modal('hide');
// 	      	if(response==1)
// 	      	{
// 	      		Swal.fire("","Archivos creados","success");
// 	      	}else
// 	      	{
// 	      		Swal.fire("","Uno o varios archivos no se pudieron generar","error");
// 	      	}	       
// 	      },error: function (error) {		      
// 	      	$('#myModal_espera').modal('hide');
// 		    },
// 	    });
   // }
</script>

<div class="content">
	<div class="row" style="margin-bottom:15px;">
        <div class="form-group">
            <div class="col-sm-12">
                <label for="SelectDatabase">Seleccionar base de datos:</label>
                <div class="row">
                    <div class="col-sm-12">
                        <select class="form-control input-xs" name="SelectDatabase" id="SelectDatabase"></select>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="row">
		<div class="col-sm-12">
			<button id="generar_esquemas" onclick="generarEsquemas()" disabled>Generar esquemas</button>
		</div>
		<div class="col-sm-12">
			<button id="generar_tablas" onclick="generarArchivos()" disabled>Generar tablas</button>
		</div>
		<div class="col-sm-12">
			<button id="generar_archivos" onclick="generarSP()" disabled>Generar archivos</button>
		</div>
	</div>
</div>
