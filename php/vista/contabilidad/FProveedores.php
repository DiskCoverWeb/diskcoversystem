 <script type="text/javascript">
 	  $( document ).ready(function() {

 	  $( "#txt_nombre_prove" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                     url:   '../controlador/farmacia/articulosC.php?search=true',           
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                      console.log(data);
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
              console.log(ui.item);
                $('#txt_id_prove').val(ui.item.value); // display the selected text
                $('#txt_nombre_prove').val(ui.item.label); // display the selected text
                $('#txt_ruc').val(ui.item.CI); // save selected id to input
                $('#txt_direccion').val(ui.item.dir); // save selected id to input
                $('#txt_telefono').val(ui.item.tel); // save selected id to input
                $('#txt_email').val(ui.item.email); // save selected id to input
                $('#txt_actividad').val(ui.item.Actividad); // save selected id to input
                $('#txt_ejec').val(ui.item.Cod_Ejec); // save selected id to input
                return false;
            },
            focus: function(event, ui){
                 $('#txt_nombre_prove').val(ui.item.label); // display the selected text
                
                return false;
            },
        });



  });
	function nombres(nombre)
	{
	   $('#txt_nombre_prove').val(nombre.ucwords());
	}
function limpiar_t()
   {
     var nom = $('#txt_nombre_prove').val();
     if(nom=='')
     {
       $('#txt_id_prove').val(''); // display the selected text
       $('#txt_nombre_prove').val(''); // display the selected text
       $('#txt_ruc').val(''); // save selected id to input
       $('#txt_direccion').val(''); // save selected id to input
       $('#txt_telefono').val(''); // save selected id to input
       $('#txt_email').val('');
       $('#txt_actividad').val('');
       $('#txt_ejec').val('');
     }
   }

  function guardar_proveedor()
   {
     var datos =  $("#form_nuevo_proveedor").serialize();
     $.ajax({
      data:  datos,
      url:   '../controlador/farmacia/articulosC.php?proveedor_nuevo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response==1)
        {
           $('#txt_nombre_prove').val('');  
          limpiar_t();        
          Swal.fire('Proveedores Guardado.','','success').then(function(){
          	cerrar();
          }); 
        }else if(response==-2)
        {
          Swal.fire('El numero de Cedula o ruc ingresado ya esta en uso.','','info');  
        }
      }
    });

     // console.log(datos);
   }

  function cerrar()
  {
     window.parent.postMessage('closeModal', '*');
  }

 </script>
 <!-- <div class="box box-info"> -->
	 <form id="form_nuevo_proveedor">
	    <div class="row">
	      <div class="col-sm-8 col-xs-8">
	        <b>Nombre de proveedor</b>
	        <input type="hidden" id="txt_id_prove" name="txt_id_prove" class="form-control input-sm">  
	        <input type="text" id="txt_nombre_prove" name="txt_nombre_prove" class="form-control input-sm" onkeyup="limpiar_t()" onblur="nombres(this.value)">  
	      </div> 
	      <div class="col-sm-4 col-xs-4">
	        <b>CI / RUC</b>
	        <input type="text" id="txt_ruc" name="txt_ruc" class="form-control input-sm">              
	      </div>           
	    </div>
	    <div class="row">
	    	<div class="col-sm-3 col-xs-3">
	    		<b>Codigo</b>
	    		<input type="" name="txt_ejec" id="txt_ejec" class="form-control input-sm">
	    	</div>
	    	<div class="col-sm-9 col-xs-9">
	    		<b>Tipo de proveedor</b>
	    		<input type="" name="txt_actividad" id="txt_actividad" class="form-control input-sm">
	    	</div>	    	
	    </div>
	    <div class="row">
	      <div class="col-sm-12 col-xs-12">
	        <b>Direccion</b>
	        <input type="text" id="txt_direccion" name="txt_direccion" class="form-control input-sm">  
	      </div>        
	    </div>
	    <div class="row">
	      <div class="col-sm-8 col-xs-8">
	        <b>Email</b>
	        <input type="text" id="txt_email" name="txt_email" class="form-control input-sm">  
	      </div> 
	      <div class="col-sm-4 col-xs-4">
	        <b>Telefono</b>
	        <input type="txt_telefono" id="txt_telefono" name="txt_telefono" class="form-control input-sm">              
	      </div> 
	    </div>
	    <div class="row">
			<div class="col-sm-12 text-right">
				<br>
				<button type="button" class="btn btn-primary" onclick="guardar_proveedor()">Guardar</button>
				<button type="button" class="btn btn-default" onclick="cerrar()">Cerrar</button>
			</div>
		</div>
	</form>
	
<!-- </div> -->
