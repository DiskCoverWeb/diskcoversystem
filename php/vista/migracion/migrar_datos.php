<?php
/**
 * Autor: JAVIER FARINANGO.
 * Mail:  
 * web:   www.diskcoversystem.com
 */
?>
<script type="text/javascript">
	
   function generarArchivos()
   {

	      	$('#myModal_espera').modal('show');
	    var parametros = 
	    {
	      
	    }
	     $.ajax({
	       data:  {parametros:parametros},
	      url:   '../controlador/migracion/migrar_datosC.php?generarArchivos=true',
	      type:  'post',
	      dataType: 'json',
	      success:  function (response) {  
	      	$('#myModal_espera').modal('hide');
	      	Swal.fire("","Geraro lod archivos","success")
	       
	      },error: function (error) {		      
	      	$('#myModal_espera').modal('hide');
		    },
	    });
   }
   function generarSP()
   {
   	$('#myModal_espera').modal('show');
	    var parametros = 
	    {
	      
	    }
	     $.ajax({
	       data:  {parametros:parametros},
	      url:   '../controlador/migracion/migrar_datosC.php?generarSP=true',
	      type:  'post',
	      dataType: 'json',
	      success:  function (response) { 
	      	$('#myModal_espera').modal('hide');
	      	if(response==1)
	      	{
	      		Swal.fire("","Archivos creados","success");
	      	}else
	      	{
	      		Swal.fire("","Uno o varios archivos no se pudieron generar","error");
	      	}	       
	      },error: function (error) {		      
	      	$('#myModal_espera').modal('hide');
		    },
	    });
   }
</script>

<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<button onclick="generarArchivos()">Generar tablas</button>
		</div>
		<div class="col-sm-12">
			<button onclick="generarSP()">Generar archivos</button>
		</div>
	</div>
</div>
