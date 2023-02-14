
<script type="text/javascript">
  var prove = '<?php if(isset($_GET['proveedor'])){echo 1;}?>'
	$( document ).ready(function() {
		 provincias();
	});



  function buscar_numero_ci()
  {
       var ci_ruc = $('#ruc').val();
       if(ci_ruc=='' || ci_ruc=='.')
       {
        return false;
       }         
       $.ajax({
        url:   '../controlador/modalesC.php?buscar_cliente=true',          
        type:'post',
        dataType:'json',
        data:{search:ci_ruc},
        beforeSend: function () {
            $("#myModal_espera").modal('show');
        },
        success: function(response){
          console.log(response);
             limpiar();
             if(response.length>0)
             {
              // console.log(response[0]);
                $('#txt_id').val(response[0].value); // display the selected text
                $('#ruc').val(response[0].label); // display the selected text
                $('#nombrec').val(response[0].nombre); // save selected id to input
                $('#direccion').val(response[0].direccion); // save selected id to input
                $('#telefono').val(response[0].telefono); // save selected id to input
                $('#codigoc').val(response[0].codigo); // save selected id to input
                $('#email').val(response[0].email); // save selected id to input
                $('#nv').val(response[0].vivienda); // save selected id to input
                $('#grupo').val(response[0].grupo); // save selected id to input
                $('#naciona').val(response[0].nacionalidad); // save selected id to input
                $('#prov').val(response[0].provincia); // save selected id to input
                $('#ciu').val(response[0].ciudad); // save selected id to input
                $('#TD').val(response[0].TD); // save selected id to input
                if(response[0].FA==1){ $('#rbl_facturar').prop('checked',true); }else{ $('#rbl_facturar').prop('checked',false);}
             }else
             {
               $('#ruc').val(ci_ruc);
               codigo();
             }

            $("#myModal_espera").modal('hide');
        
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

  function limpiar()
  {
  	$('#txt_id').val(''); // display the selected text
    $('#ruc').val(''); // display the selected text
    $('#nombrec').val(''); // save selected id to input
    $('#direccion').val(''); // save selected id to input
    $('#telefono').val(''); // save selected id to input
    $('#codigoc').val(''); // save selected id to input
    $('#email').val(''); // save selected id to input
    $('#nv').val(''); // save selected id to input
    $('#grupo').val(''); // save selected id to input
    $('#naciona').val(''); // save selected id to input
    $('#prov').val(''); // save selected id to input
    $('#ciu').val(''); // save selected id to input
  }

  function codigo()
  {
  	 var ci = $('#ruc').val();
  	 if(ci!='')
  	 {
     $.ajax({
      url:   '../controlador/modalesC.php?codigo=true',      
      type:'post',
      dataType:'json',
      data:{ci:ci},
      success: function(response){     	
      	console.log(response);
      	$('#codigoc').val(response.Codigo);
      	$('#TD').val(response.Tipo);
        
      }
    });
   }else
   {
   	 limpiar();
   }

  }


	function buscar_cliente_nom()
	{
		var ci = $('#nombrec').val();
		var parametros = 
		{
			'nombre':ci,
		}
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/modalesC.php?buscar_cliente_nom=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response)
        {

        }
      }
    });
	}

	function guardar_cliente()
	{
    if(validar()==true)
    {
      swal.fire('Lene todos los campos','','info')
      return false;
    }
    var rbl = $('#rbl_facturar').prop('checked');
		 var datos = $('#form_cliente').serialize();
		  $.ajax({
       data:  datos+'&rbl='+rbl+'&cxp='+prove,
      url:   '../controlador/modalesC.php?guardar_cliente=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        var url = location.href;
        if(response ==1)
        {
        	if($('#txt_id').val()!='')
        	{
        		swal.fire('Registro guardado','','success');
        	}else
        	{
        		swal.fire('Registro guardado','','success');
        	}

        }else if(response==2)
        {
          swal.fire('Este CI / RUC ya esta registrado','','info');
        }
      }
    });
	}

function validar_sri()
  {
    var ci = $('#ruc').val();
     $.ajax({
    data: {ci,ci},
    url: '../controlador/modalesC.php?validar_sri=true',
    type: 'POST',
    dataType: 'json',
    success: function(response) {
      if(response.res=='1')
        {
          $('#datos_sri_cliente').modal('show');
          $('#tbl_sri').html(response.tbl);
        }else
        {
          Swal.fire('Ruc no encontrado en el SRI','','info')
        }

      }
    });

  }


  function validar()
  {

    $('#e_ruc').css('display','none');   
    $('#e_telefono').css('display','none');
    $('#e_nombrec').css('display','none');   
    $('#e_direccion').css('display','none');

    var vali = false;    
    if($('#ruc').val()=='')
    {
      $('#e_ruc').css('display','initial');
      vali = true;
    }
    if($('#telefono').val()=='')
    {
      $('#e_telefono').css('display','initial');
      vali = true;
    }
    if($('#nombrec').val()=='')
    {
      $('#e_nombrec').css('display','initial');
      vali = true;
    }
    if($('#direccion').val()=='')
    {
      $('#e_direccion').css('display','initial');
      vali = true;
    }
    if($('#email').val()=='')
    {
      $('#e_email').css('display','initial');
      vali = true;
    }

    return vali;

  }
</script>			

			<div class="box box-info">

            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" id="form_cliente">
              <div class="box-body">
				<div class="row">
					<div class="col-xs-4 col-sm-3 ">
					  <label for="ruc" class="control-label" id="resultado"><span style="color: red;">*</span>RUC/CI</label>
						<input type="hidden" class="form-control" id="txt_id" name="txt_id" placeholder="ruc" autocomplete="off">
						<input type="text" class="form-control input-sm" id="ruc" name="ruc" placeholder="RUC/CI" autocomplete="off" onblur="buscar_numero_ci();/*codigo()*/" style="z-index: 1;">
							<span class="help-block" id='e_ruc' style='display:none;color: red;'>Debe ingresar RUC/CI</span>
						
					</div>
          <div class="col-xs-2 col-sm-1" style="padding:0px"><br>
            <!-- <iframe src="https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/existePorNumeroRuc?numeroRuc=1722214507001&output=embed"></iframe> -->
            <button type="button" class="btn btn-sm" onclick="validar_sri()">
              <img src="../../img/png/SRI.jpg" style="width: 60%">
            </button>
            
          </div>
					<div class="col-xs-3 col-sm-3 ">
					 <label for="telefono" class="col-sm-1 control-label"><span style="color: red;">*</span>Telefono</label>
						<input type="text" class="form-control input-sm" id="telefono" name="telefono" placeholder="Telefono" autocomplete="off">
							<span class="help-block" id='e_telefono' style='display:none;color: red;'>Debe ingresar Telefono</span>
					</div>
					<div class="col-xs-3 col-sm-3 ">
					 <label for="codigoc" class="control-label"><span style="color: red;">*</span>Codigo</label>
						<input type="hidden" id='buscar' name='buscar'  value='' />            
            <input type="text" id='TD' name='TD'  value='' readonly  style="width:30px" />
						<input type="text" class="form-control input-sm" id="codigoc" name="codigoc" placeholder="Codigo" readonly="">
						<span class="help-block" id='e_codigoc' style='display:none;color: red;'>debe agregar Codigo</span>						
					</div>
        </div>
        <div class="row">
          <div class="col-xs-9 col-sm-11 col-lg-10">
            <label for="nombrec" class="control-label"><span style="color: red;">*</span>Apellidos y Nombres</label>
            <input type="text" class="form-control input-sm" id="nombrec" name="nombrec" placeholder="Razon social" onkeyup="buscar_cliente_nom();mayusculas('nombrec',this.value) " onblur="mayusculas('nombrec',this.value)">
              <span class="help-block" id='e_nombrec' style='display:none;color: red;'>Debe ingresar nombre</span>
           </div>
          <div class="col-xs-3 col-sm-1 col-lg-2">
            <br>
            <label> </label><input type="checkbox" name="rbl_facturar" id="rbl_facturar" checked> Para Facturar</div>
          </div>
        </div>
        <div class="row">
					<div class="col-xs-8">
					  <label for="direccion" class="control-label"><span style="color: red;">*</span>Direccion</label>
						<input type="text" class="form-control input-sm" id="direccion" name="direccion" placeholder="Direccion" tabindex="0" onkeyup="mayusculas('direccion',this.value)" onblur="mayusculas('direccion',this.value)">
							<span class="help-block" id='e_direccion' style='display:none;color: red;'>debe agregar Direccion</span>
					</div>
          <div class="col-xs-4">
            <label for="email" class="control-label"><span style="color: red;">*</span>Email Principal</label>
            <input type="email" class="form-control input-sm" id="email" name="email" placeholder="Email" tabindex="0" onblur="validador_correo('email')">            
              <span class="help-block" id='e_email' style='display:none;color: red;'> debe agregar un email</span>
          </div>
        </div>
				
				<div class="row">
				  <div class="col-xs-5">
				    <label for="nv" class="control-label">Ubicacion geografica</label>
				    <input type="text" class="form-control input-sm" id="nv" name="nv" placeholder="Numero vivienda"  tabindex="0" onkeyup="mayusculas('nv',this.value)" onblur="mayusculas('nv',this.value)">
				  </div>
				  <div class="col-xs-2">
				    <label for="grupo" class="control-label">Grupo</label>
						<input type="text" class="form-control input-sm" id="grupo" name="grupo" placeholder="Grupo" 
						tabindex="0">
					</div>
					<div class="col-xs-5">
					  <label for="naciona" class="col-sm-1 control-label">Nacionalidad</label>
						<input type="text" class="form-control" id="naciona" name="naciona" placeholder="Nacionalidad" 
						tabindex="0">
					</div>
        </div>
				<div class="row">
				  <div class="col-xs-6">
				    <label for="prov" class="control-label">Provincia</label>
				    <select class="form-control input-sm" id="prov" name="prov">
				    	<option>Seleccione una provincia</option>
				    </select>
				  </div>
				  <div class="col-xs-6">
				    <label for="ciu" class="control-label">Ciudad</label>
						<input type="text" class="form-control input-sm" id="ciu" name="ciu" placeholder="Ciudad" 
						tabindex="0">
					</div>
        </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
              	<button type="button" onclick="guardar_cliente()" class="btn btn-primary">Guardar</button>
				      </div>
              <!-- /.box-footer -->
            </form>
          </div>          

  <div class="modal fade" id="datos_sri_cliente" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <div class="row">
            <div class="col-sm-6 col-xs-6">
                <h5 class="modal-title" id="titulo_clave">Datos de cliente desde SRI</h5>              </div>
            <div class="col-sm-6 col-xs-6 text-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
          </div>      
      </div>
        <div class="modal-body text-center">
          <div class="col-sm-12">
            <div id="tbl_sri" class="text-left">
              
            </div>                      
          </div>
        </div>
         <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

 