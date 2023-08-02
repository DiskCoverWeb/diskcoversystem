<?php
$mostrar_medidor = false;
  switch ($_SESSION['INGRESO']['modulo_']) {
    case '07': //AGUA POTABLE
      $mostrar_medidor =  true;
      break;    
    default:
      
      break;
  }

?>
<script type="text/javascript">
  var prove = '<?php if(isset($_GET['proveedor'])){echo 1;}?>'
	$( document ).ready(function() {
		 provincias();

     $("#CMedidor").on('change',function() {
       if($("#CMedidor").val()!="." && $("#CMedidor").val()!=""){
        $("#DeleteMedidor").removeClass("no-visible")
       }else{
        $("#DeleteMedidor").addClass("no-visible")
       }
     })
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
                if(response[0].provincia=='' || response[0].provincia=='.')
                {
                  $('#prov').append('<option value=".">Seleccione</option>'); // save selected id to input                  
                }
                $('#ciu').val(response[0].ciudad); // save selected id to input
                $('#TD').val(response[0].TD); // save selected id to input
                if(response[0].FA==1){ $('#rbl_facturar').prop('checked',true); }else{ $('#rbl_facturar').prop('checked',false);}
                MostrarOcultarBtnAddMedidor()
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
    $('#CMedidor').empty();
    MostrarOcultarBtnAddMedidor()
  }

  function codigo()
  {
    $("#myModal_espera").modal('show');
  	 var ci = $('#ruc').val();
  	 if(ci!='')
  	 {
     $.ajax({
      url:   '../controlador/modalesC.php?codigo=true',      
      type:'post',
      dataType:'json',
      data:{ci:ci},
     beforeSend: function () {
          // $("#myModal_espera").modal('show');
      },
      success: function(response){     	
      	console.log(response);
      	$('#codigoc').val(response.Codigo_RUC_CI);
      	$('#TD').val(response.Tipo_Beneficiario);
        $("#myModal_espera").modal('hide');
        MostrarOcultarBtnAddMedidor()

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
      swal.fire('Llene todos los campos','','info')
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
        }else if(response==3)
        {
           swal.fire('El Nombre ya esta registrado','','info');
        }
      }
    });
	}

  function validar_sri()
  {
    var ci = $('#ruc').val();
    if(ci!='')
    {
      url = 'https://srienlinea.sri.gob.ec/facturacion-internet/consultas/publico/ruc-datos2.jspa?accion=siguiente&ruc='+ci
      window.open(url, "_blank");
    }else
    {
       Swal.fire('Coloque un numero de CI / RUC','','info')
    }
    // var ci = $('#ruc').val();
    //  $.ajax({
    // data: {ci,ci},
    // url: '../controlador/modalesC.php?validar_sri=true',
    // type: 'POST',
    // dataType: 'json',
    // success: function(response) {
    //   if(response.res=='1')
    //     {
    //       $('#datos_sri_cliente').modal('show');
    //       $('#tbl_sri').html(response.tbl);
    //     }else
    //     {
    //       Swal.fire('Ruc no encontrado en el SRI','','info')
    //     }

    //   }
    // });

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

  function AddMedidor(){
    let CodigoC =$("#codigoc").val();

    if (CodigoC!="" && CodigoC!=".") {
      Swal.fire({
        title: 'Ingresar Nuevo Medidor:',
        showCancelButton: true,
        cancelButtonText: 'Cerrar',
        confirmButtonText: 'Guardar',
        html:
          '<label for="CMedidorNew">Numero de Medidor</label>' +
          '<input type="tel" id="CMedidorNew" class="swal2-input" required>' +
          '<span id="error1" style="color: red;"></span><br>' +
          '<label for="LecturaInicial">Lectura Anterior</label>' +
          '<input type="tel" id="LecturaInicial" class="swal2-input inputNumero">'+
          '<span id="error2" style="color: red;"></span><br>' ,
        focusConfirm: false,
        preConfirm: () => {
          const CMedidorNew = document.getElementById('CMedidorNew').value;
          const LecturaInicial = document.getElementById('LecturaInicial').value;

          if($.isNumeric(CMedidorNew)){
            if($.isNumeric(LecturaInicial) || LecturaInicial==""){
              return [CMedidorNew, LecturaInicial];
            }else{
              Swal.getPopup().querySelector('#error2').textContent = 'Debe ingresar un valor numérico';
              return false
            }
          }else{
            Swal.getPopup().querySelector('#error1').textContent = 'Debe ingresar un valor numérico';
            return false
          }
        }
      }).then((result) => {
        if (result.value) {
          const [CMedidorNew, LecturaInicial] = result.value;
          if($.isNumeric(CMedidorNew)){
            $.ajax({
              type: 'POST',
              dataType: 'json',
              url: '../controlador/modalesC.php?AddMedidor=true',
              data: {'Cuenta_No' : CMedidorNew , 'TxtCodigo' : CodigoC, 'LecturaInicial' : LecturaInicial},
              beforeSend: function () {   
                  $('#myModal_espera').modal('show');
              },    
              success: function(response)
              {
                $('#myModal_espera').modal('hide');  
                if(response.rps){
                  Swal.fire('¡Bien!', response.mensaje, 'success')
                  ListarMedidores(CodigoC)
                }else{
                  Swal.fire('¡Oops!', response.mensaje, 'warning')
                }        
              },
              error: function () {
                $('#myModal_espera').modal('hide');
                alert("Ocurrio un error inesperado, por favor contacte a soporte.");
              }
            });
          }
        }
      });
    }else{
      swal.fire('No se ha definido un Codigo de usuario, ingrese un RUC/CI para obtener el codigo.','','warning')
    }
  }

  function DeleteMedidor(){
    let idMedidor = $("#CMedidor").val();
    let TxtApellidosS =$("#nombrec").val();
    let CodigoC =$("#codigoc").val();

    if(idMedidor!="." && idMedidor !=""){
      Swal.fire({
          title: `Esta seguro que desea Eliminar\nEl Medidor No. ${idMedidor} \nDe ${TxtApellidosS}`,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          cancelButtonText: 'No.',
          confirmButtonText: 'Si, Eliminar'
        }).then((result) => {
          if (result.value==true) {
            $.ajax({
              type: 'POST',
              dataType: 'json',
              url: '../controlador/modalesC.php?DeleteMedidor=true',
              data: {'Cuenta_No' : idMedidor , 'TxtCodigo' : CodigoC},
              beforeSend: function () {   
                  $('#myModal_espera').modal('show');
              },    
              success: function(response)
              {
                $('#myModal_espera').modal('hide');  
                if(response.rps){
                  Swal.fire('¡Bien!', response.mensaje, 'success')
                }else{
                  Swal.fire('¡Oops!', response.mensaje, 'warning')
                }  
                ListarMedidores(CodigoC)      
              },
              error: function () {
                $('#myModal_espera').modal('hide');
                alert("Ocurrio un error inesperado, por favor contacte a soporte.");
              }
            });
          }
        })
    }else{
      swal.fire('Debe seleccionar el medidor que desea eliminar','','warning')
    }
  }

  function ListarMedidores(codigo)
  {
    if(codigo!="" && codigo!="."){
      $.ajax({
        url:   '../controlador/modalesC.php?ListarMedidores=true',      
        type:'POST',
        dataType:'json',
        data:{'codigo':codigo},
        success: function(response){
          // construye las opciones del select dinámicamente
          var select = $('#CMedidor');
          select.empty(); // limpia las opciones existentes
          $.each(response, function (i, opcion) {
              if(opcion.Cuenta_No=="."){
                select.append($('<option>', {
                    value: '.',
                    text: 'NINGUNO'
                }));
              }else{
                select.append($('<option>', {
                  value: opcion.Cuenta_No,
                  text: opcion.Cuenta_No
                }));
              }
          });
          $('#CMedidor').change()
        }
      });
    }

  }

  function MostrarOcultarBtnAddMedidor() {
    if($('#codigoc').val()!="" && $('#codigoc').val()!="."){
      $("#AddMedidor").removeClass("no-visible")
      ListarMedidores($('#codigoc').val())
     }else{
      $("#AddMedidor").addClass("no-visible")
     }
  }
</script>		

<style type="text/css">
  .visible{
  visibility: visible;
}

.no-visible{
  visibility: hidden;
}
</style>	

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
        <?php if ($mostrar_medidor): ?>
          <div class="row">
            <div class="col-xs-6 col-sm-4">
                <label for="CMedidor" class="control-label">Medidor No.</label>
                <div class="input-group contenedor_item_center">
                  <select class="form-control input-sm" id="CMedidor" name="CMedidor">
                    <option value="<?php echo G_NINGUNO ?>">NINGUNO</option>
                  </select>
                    <a class="btn btn-sm btn-success no-visible" id="AddMedidor" title="Agregar Medidor" onclick="AddMedidor()"><i class="fa fa-plus"></i></a>
                    <a class="btn btn-sm btn-danger no-visible" id="DeleteMedidor" title="Eliminar Medidor" onclick="DeleteMedidor()"><i class="fa fa-trash-o"></i></a>
                  
                </div>
            </div>
          </div>
        <?php endif ?>
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

 