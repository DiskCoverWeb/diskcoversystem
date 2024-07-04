 <script type="text/javascript">
 	  $( document ).ready(function() {
        tipo_proveedor();

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
                $('#txt_email2').val(ui.item.email2); // save selected id to input
                $('#txt_actividad').val(ui.item.Actividad); // save selected id to input
                $('#txt_ejec').val(ui.item.Cod_Ejec); // save selected id to input
                cargar_sucursales();
                return false;
            },
            focus: function(event, ui){
                 $('#txt_nombre_prove').val(ui.item.label); // display the selected text
                
                return false;
            },
        });

        $( "#txt_ruc" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                     url:   '../controlador/farmacia/articulosC.php?search_ruc=true',           
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
                $('#txt_nombre_prove').val(ui.item.Nombre); // display the selected text
                $('#txt_ruc').val(ui.item.label); // save selected id to input
                $('#txt_direccion').val(ui.item.dir); // save selected id to input
                $('#txt_telefono').val(ui.item.tel); // save selected id to input
                $('#txt_email').val(ui.item.email); // save selected id to input
                $('#txt_email2').val(ui.item.email2); // save selected id to input
                $('#txt_actividad').val(ui.item.Actividad); // save selected id to input
                $('#txt_ejec').val(ui.item.Cod_Ejec); // save selected id to input
                cargar_sucursales();
                return false;
            },
            focus: function(event, ui){
                 $('#txt_ruc').val(ui.item.label); // display the selected text
                
                return false;
            },
        });

      //autocmpretar abreviado

      $( "#txt_ejec" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                     url:   '../controlador/farmacia/articulosC.php?searchAbre=true',           
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
              // console.log(ui.item);
                // $('#txt_id_prove').val(ui.item.value); // display the selected text
                // $('#txt_ejec').val(ui.item.label); // display the selected text
                // $('#txt_ruc').val(ui.item.CI); // save selected id to input
                // $('#txt_direccion').val(ui.item.dir); // save selected id to input
                // $('#txt_telefono').val(ui.item.tel); // save selected id to input
                // $('#txt_email').val(ui.item.email); // save selected id to input
                // $('#txt_actividad').val(ui.item.Actividad); // save selected id to input
                // $('#txt_ejec').val(ui.item.Cod_Ejec); // save selected id to input
                return false;
            },
            focus: function(event, ui){
                 $('#txt_ejec').val(ui.item.label); // display the selected text
                
                return false;
            },
        });



  });

 function mostrar_ingreso_sucursal()
 {
    if($('#txt_id_prove').val()=='')
    {
        Swal.fire('Seleccione un proveedor','','info');
        return false;
    }
    $('#pnl_sucursal').css('display','block');
 }

 function cargar_sucursales()
 {
    if($('#txt_id_prove').val()=='')
    {
        Swal.fire('Seleccione un proveedor','','info');
        return  false;
    }

    var parametros = {
        'ruc':$('#txt_ruc').val(),
    }
     $.ajax({
      data:  {parametros,parametros},
      url:   '../controlador/modalesC.php?sucursales=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        op = '';
        var sucursal = 0;
        response.forEach(function(item,i){
            sucursal = 1;
            op+="<tr><td>"+item.Direccion+"</td><td>"+item.TP+"</td><td><button class='btn btn-danger btn-xs' type='button' onclick='eliminar_sucursal(\""+item.ID+"\")'><i class='fa fa-trash'></i></button></td></tr>";
        })

        if(sucursal==1)
        {
            $('#pnl_sucursal').css('display','block');
        }else{            
            $('#pnl_sucursal').css('display','none');
        }

        $('#tbl_sucursales').html(op);
        console.log(response);
        
      }, 
      error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');           
      }
    });

 }

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
       $('#txt_email2').val('');
       $('#txt_actividad').val('');
       $('#txt_ejec').val('');
     }
   }

  function guardar_proveedor()
   {
     abre = $('#txt_ejec').val();
     console.log(abre);
     if(abre.length >5 || abre=='.' || abre=='' || abre.length <2)
     {
         Swal.fire('Abreviatura incorrecta ','Asegurese de colocar una abreviatura mayor a 2 digitos y menor o igual 5 digitos y diferente de punto (.)','info')
        return false;
     }
     
     $('#myModal_espera').modal('show');
     var datos =  $("#form_nuevo_proveedor").serialize();
     datos = datos+'&actividad='+$('#txt_actividad option:selected').text()
     $.ajax({
      data:  datos,
      url:   '../controlador/farmacia/articulosC.php?proveedor_nuevo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
         $('#myModal_espera').modal('hide');
        // console.log(response);
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
      }, 
        error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');
            // $('#lbl_mensaje').text(xhr.statusText);
            // alert(xhr.statusText);
            // alert(textStatus);
            // alert(error);
        }
    });

     // console.log(datos);
   }

    function tipo_proveedor()
    {
     
     $.ajax({
      // data:  datos,
      url:   '../controlador/modalesC.php?tipo_proveedor=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
         var op = '<option value="">Seleccione</option>';
        response.forEach(function(item,i){
            op+="<option value="+item.ID+">"+item.Proceso+"</option>";
        })

        $('#txt_actividad').html(op);
        // console.log(response);
        
      }, 
      error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');           
      }
    });

     // console.log(datos);
   }

  function cerrar()
  {
     //window.parent.postMessage('closeModal', '*');;
     location.href = window.location.pathname + '?mod=03';
  }
  function validar_abrev()
  {
        var ab = $('#txt_ejec').val();
        var id = $('#txt_id_prove').val();
        var parametros = {
            'abre':ab,
            'id':id,
        }
    if(ab!='')
    {
         $.ajax({
          data:  {parametros,parametros},
          url:   '../controlador/farmacia/articulosC.php?validar_abre=true',
          type:  'post',
          dataType: 'json',
          success:  function (response) { 
            if(response==1)
            {
                Swal.fire('Esta abreviatura ya esta en uso','Coloque otra abreviacion','info').then(function(){
                    $('#txt_ejec').val('');
                });
            }
           
          }, 
            error: function(xhr, textStatus, error){
            $('#myModal_espera').modal('hide');
                // $('#lbl_mensaje').text(xhr.statusText);
                // alert(xhr.statusText);
                // alert(textStatus);
                // alert(error);
            }
        });
     }

  }
  
  function validar_sri()
  {
    var ci = $('#txt_ruc').val();
    if(ci!='')
    {
      url = 'https://srienlinea.sri.gob.ec/facturacion-internet/consultas/publico/ruc-datos2.jspa?accion=siguiente&ruc='+ci
      window.open(url, "_blank");
    }else
    {
       Swal.fire('Coloque un numero de CI / RUC','','info')
    }    
  }

  function add_sucursal()
  {
    var parametros = {
        'ruc':$('#txt_ruc').val(),
        'direccion':$('#txt_sucursal_dir').val(),
        'tp':$('#txt_cod_sucursal').val(),
    }
     $.ajax({
      data:  {parametros,parametros},
      url:   '../controlador/modalesC.php?add_sucursal=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
       if(response==1)
       {
        $('#txt_sucursal_dir').val('');
        cargar_sucursales();
       }        
      }, 
      error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');           
      }
    });

  }

  function eliminar_sucursal(id)
  {
    var parametros = {
        'id':id,
    }
     $.ajax({
      data:  {parametros,parametros},
      url:   '../controlador/modalesC.php?delete_sucursal=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
       if(response==1)
       {
        cargar_sucursales();
       }        
      }, 
      error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');           
      }
    });
  }



 </script>
 <!-- <div class="box box-info"> -->
	 <form id="form_nuevo_proveedor">
        <div class="panel">
            <div class="panel-body" style="background-color:antiquewhite;">
                <div class="row">
                    <div class="col-sm-4 col-xs-4">
                        <b>CI / RUC</b>
                        <input type="text" id="txt_ruc" name="txt_ruc"  style="z-index:auto" class="form-control input-sm">              
                      </div>
                      <div class="col-xs-2 col-sm-1">
                        <br>
                        <button type="button" class="btn btn-sm" onclick="validar_sri()">
                          <img src="../../img/png/SRI.jpg" style="width: 100%">
                        </button>                    
                      </div>        
                      <div class="col-xs-6 col-sm-7">
                        <b>Nombre de proveedor</b>
                        <input type="hidden" id="txt_id_prove" name="txt_id_prove" class="form-control input-sm">  
                        <div class="input-group">
                             <input type="text"  style="z-index:auto"  id="txt_nombre_prove" name="txt_nombre_prove" class="form-control input-sm" onkeyup="limpiar_t();mayusculasevent(this)" onblur="nombres(this.value)">
                             <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat btn-sm" title="Sucursales" onclick="mostrar_ingreso_sucursal()"><i class="fa fa-building-o"></i>&nbsp;<i class="fa fa-plus"></i></button>
                            </span>
                        </div>
                       
                      </div>                      
                </div>
                <div class="row">
                    <div class="col-sm-3 col-xs-6">
                        <b>Abreviado del donante</b>
                        <input type="" style="z-index:auto" name="txt_ejec" id="txt_ejec" class="form-control input-sm" onkeyup="mayusculasevent(this)" onblur="validar_abrev()">
                    </div>
                    <div class="col-sm-9 col-xs-6">
                        <b>Tipo de proveedor / Donante</b>
                        <select class="form-control input-xs" id="txt_actividad" name="txt_actividad">
                            <option value="">Seleccione</option>
                        </select>
                        <!-- <input type="" name="txt_actividad" id="txt_actividad" class="form-control input-sm form-select"  onkeyup="mayusculasevent(this)"> -->
                    </div>          
                </div>
                <div class="row">
                  <div class="col-sm-12 col-xs-12">
                    <b>Direccion</b>
                    <input type="text" id="txt_direccion" name="txt_direccion" class="form-control input-xs"  onkeyup="mayusculasevent(this)">  
                  </div>        
                </div>
                <div class="row">
                  <div class="col-sm-8 col-xs-8">
                    <b>Email</b>
                    <input type="text" id="txt_email" name="txt_email" class="form-control input-sm">  
                  </div> 
                  <div class="col-sm-4 col-xs-4">
                    <b>Telefono</b>
                    <input type="txt_telefono" id="txt_telefono" name="txt_telefono" class="form-control input-sm" >              
                  </div> 
                </div>
                <div class="row">
                  <div class="col-sm-8 col-xs-8">
                    <b>Email 2</b>
                    <input type="text" id="txt_email2" name="txt_email2" class="form-control input-sm">  
                  </div> 
                  <div class="col-sm-4 col-xs-4">
                    <b>Tipo Prov. Y Parte Relac.</b> 
                    <div class="row">
                        <div class="col-sm-9">
                            <select class="form-control input-xs" id="CTipoProv" name="CTipoProv">
                                <option value="">Seleccione</option>
                                <option value="00">OTROS</option>
                                <option value="01">PERSONA NATURAL</option>
                                <option value="02">SOCIENDAD</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control input-xs" id="CParteR" name="CParteR">
                                <option value="NO">NO</option>
                                <option value="SI">SI</option>
                            </select>
                        </div>
                    </div>         
                  </div> 
                </div>                
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <br>
                        <button type="button" class="btn btn-sm btn-primary" onclick="guardar_proveedor()">Guardar</button>
                        <button type="button" class="btn btn-sm btn-default" onclick="cerrar()">Cerrar</button>
                    </div>
                </div>
                <div class="row" id="pnl_sucursal" style="display:none">
                    <div class="col-sm-12">                        
                    <hr>
                        <h3>Sucursales</h3>
                    </div>
                     <div class=" col-xs-3 col-sm-4 col-md-3">
                        <b>Codigo Sucursal</b>
                        <input type="" name="txt_cod_sucursal" id="txt_cod_sucursal" class="form-control input-sm">
                    </div>
                    <div class=" col-xs-6 col-sm-4 col-md-7">
                        <b>Direccion</b>
                        <input type="" name="txt_sucursal_dir" id="txt_sucursal_dir" class="form-control input-sm">
                    </div>
                   
                    <div class=" col-xs-3 col-sm-2 col-md-2 text-right">
                        <br>
                        <button type="button"  class="btn btn-primary" onclick="add_sucursal()">Guardar sucursal</button> 
                    </div> 
                    <div class="col-sm-12 col-xs-12">

                        <table class="table table-hover text-sm">
                            <thead>
                                <th>Direccion</th>
                                <th>TP</th>
                                <th></th>
                            </thead>
                            <tbody id="tbl_sucursales">
                                
                            </tbody>
                        </table>
                    </div>                 
                </div>


            </div>
        </div>
	</form>
	
<!-- </div> -->
