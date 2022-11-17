
<script type="text/javascript">
  $( document ).ready(function() {
     provincia();

     cargar_clientes();
  });

  function provincia()
  {
     $.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?provincias=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response)
        {
          var op = '<option value="">Seleccione provincia</option>';
          $.each(response,function(i,item){
             op+= '<option value="'+item.Codigo+'">'+item.Descripcion_Rubro+'</option>';
          });
          $('#ddl_provincia').html(op);
          $("#ddl_provincia").val('17');
        }
      }
    });
  }
  function nombres(nombre)
  {
    $('#txt_nombre').val(nombre.ucwords());
  }

  function cargar_clientes()
  {
    var query = $('#txt_query').val();
    var rbl = $('input:radio[name=rbl_buscar]:checked').val();
    var pag =$('#txt_pag').val();
    var parametros = 
    {
      'query':query,
      'tipo':rbl,
      'codigo':'',
      'pag':pag,  // numero de registeros que se van a visualizar
      'fun':'cargar_clientes' // funcion que se va a a ejecutar en el paginando para recargar
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?pacientes=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log();
        // if(response)
        // {
          $('#tbl_pacientes').html(response.tr);
          $('#tbl_pag').html(response.pag);
        // }
      }
    });
  }

  function limpiar()
  {

    $('#txt_codigo').val('');
    $('#txt_nombre').val('');
    $('#txt_ruc').val('');
    // $('#ddl_provincia').val('');
    // $('#txt_localidad').val('');
    $('#txt_telefono').val('');
    $('#txt_tip').val('N');    
    $('#txt_id').val('');
    // $('#txt_email').val('');
    $('#btn_nu').html('<i class="fa fa-plus"></i> Nuevo cliente');
    // $('#txt_codigo').attr("readonly", false);
  }


  function nuevo_paciente()
  {
    if($('#txt_validado').val()==0)
    {
      // Swal.fire('Se esta validando la cedua','','info');
      return false;
    }
    var parametros = 
    {
       'cod':$('#txt_codigo').val(),
       'id':$('#txt_id').val(),
       'nom':$('#txt_nombre').val(),
       'ruc':$('#txt_ruc').val(),
       'pro':$('#ddl_provincia').val(),
       'loc':$('#txt_localidad').val(),
       'tel':$('#txt_telefono').val(),
       'ema':$('#txt_email').val(),
       'tip':$('#txt_tip').val(),
    }
    if($('#txt_codigo').val() =='' || $('#txt_nombre').val()=='' || $('#txt_ruc').val()=='' || $('#ddl_provincia').val()=='' || $('#txt_localidad').val()=='' || $('#txt_telefono').val()== '' || $('#txt_email').val()=='' || $('#txt_tip').val()=='')
    {

       Swal.fire('','Llene todo los campos.','info');
      return false;
    }
    if($('#txt_codigo').val() =='' || $('#txt_codigo').val()==0)
    {
       Swal.fire('','Numero de Historia invalido.','info');
      return false;
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?nuevo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response==1)
        {
          if(parametros.tip=='E')
          {
            cargar_clientes();
           limpiar();
           Swal.fire('Cliente Editado.','','success');
           
           }else
           {
            cargar_clientes();
            limpiar();
            Swal.fire('Nuevo Cliente Registrado.','','success');
            
           }          
        }else if(response==-2)
        {
           Swal.fire('Cedula incorrecta','','error');

        }else
        {
          Swal.fire('','Existio algun tipo de problema intente mas tarde.','error');
        }
      }
    });

  }

  function buscar_cod(tipo,campo)
  {

    // $('#txt_codigo').attr("readonly",true);
    $('#myModal_espera').modal('show');    
    $('#btn_nu').html('<i class="fa fa-pencil"></i> Editar cliente');
    $('#txt_tip').val('E');
    var query = $('#'+campo).val();
    var parametros;
    if(tipo=='N' || tipo=='N1')
    {
     parametros = 
      { 
        'query':query,
        'tipo':tipo,
        'codigo':'',
      }
    }else if(tipo=='R' || tipo=='R1' )
    {
       parametros = 
      { 
        'query':query,
        'tipo':tipo,
        'codigo':'',
      }
    }else if(tipo=='E')
    {
      parametros = 
      { 
        'query':'',
        'tipo':'',
        'codigo':campo,
      }

    }else
    {
      parametros = 
      { 
        'query':query,
        'tipo':tipo,
        'codigo':'',
      }
    }
    
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response !=-1)
        {
           $('#txt_codigo').val(response.matricula);
           $('#txt_nombre').val(response.nombre);
           $('#txt_ruc').val(response.ci);
           $('#ddl_provincia').val(response.prov);
           $('#txt_localidad').val(response.localidad);
           $('#txt_telefono').val(response.telefono);
           $('#txt_email').val(response.email);
           $('#txt_id').val(response.id);
           $('#txt_validado').val(1);
           if(response.matricula == 0 || response.matricula == '')
           {
                $('#txt_codigo').attr("readonly",false);
           }
           $(window).scrollTop(0);
          
        }else
        {
          var query = $('#'+campo).val();
          limpiar();
          $('#'+campo).val(query);
          Swal.fire('','No se a es encontrado registros.','info');
        }

       $('#myModal_espera').modal('hide');    
      }
    });
  }

  function eliminar(cli,ruc)
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
              $.ajax({
                data:  {cli:cli,ruc:ruc},
                url:   '../controlador/farmacia/pacienteC.php?eliminar=true',
                type:  'post',
                dataType: 'json',
                success:  function (response) 
                      {
                        if(response ==1)
                          {
                            Swal.fire('','Registro eliminado.','success');
                            cargar_clientes();
                          }else
                          {
                            Swal.fire('','Este usuario tiene Datos ligados.','error');
                          }
                      }
                });
        }
      });

  }

  function validar_num_historia()
  {
    var num = $('#txt_codigo').val();
    if(!num=='')
    {
       parametros = 
      { 
        'query':num,
        'tipo':'C1',
        'codigo':'',
      }
       $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?historial_existente=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response==-1)
        {
          Swal.fire('','El numero de Historia ya existe.','error');
          $('#txt_codigo').val('');
        }
      }
    });

    }
  }

  function validar_ci()
  {
     var num = $('#txt_ruc').val();
    
      $.ajax({
      data:  {num:num},
      url:   '../controlador/farmacia/pacienteC.php?validar_ci=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response == 2)
        {
           Swal.fire('Numero de cedula invalido.','','error');
            $('#txt_ruc').val('');
           return false;
        }
      }
    });
  }

  function paciente_existente()
  {
    var num = $('#txt_ruc').val();
    if(num.length<10)
    {
      Swal.fire('La cedula no tiene 10 caracteres','','info');
      return false;
    }

    if(!num=='')
    {
      validar_ci();
       parametros = 
      { 
        'query':num,
        'tipo':'R1',
        'codigo':'',
      }
       $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?paciente_existente=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response!=-1)
        {
          Swal.fire({
            title: 'Este CI ya esta registrado?',
            text: "Desea cargar sus datos!",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
            }).then((result) => {
              if (result.value) {
                $('#txt_codigo').val(response.matricula);
                $('#txt_nombre').val(response.nombre);
                $('#txt_ruc').val(response.ci);
                $('#ddl_provincia').val(response.prov);
                $('#txt_localidad').val(response.localidad);
                $('#txt_telefono').val(response.telefono);
                $('#txt_email').val(response.email);
                $('#txt_id').val(response.id);
                $('#txt_tip').val('E');
                if(response.matricula == 0 || response.matricula == '')
                {
                     $('#txt_codigo').attr("readonly",false);
                }

                }else
                {
                limpiar();
                $('#txt_ruc').val('');
                $('#txt_validado').val(0);
                }
            });
        }else
        {
           $('#txt_id').val('');
           $('#txt_codigo').val('');
           $('#txt_tip').val('N');
        }
        $('#txt_validado').val(1);
      }
    });

    }
  }

</script>
  <div class="row"><br>
    <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=28&acc=pacientes&acc1=Visualizar%20paciente&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_pdf" title="Pacientes">
            <img src="../../img/png/pacientes.png">
          </a>           
        </div>
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=28&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_excel" title="Descargos">
            <img src="../../img/png/descargos.png">
          </a>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=28&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-default" onclick="">
            <img src="../../img/png/articulos.png" >
          </a>
        </div>     
 </div>
</div>
<div class="row">
  <div class="col-sm-12"><br>
     <div class="panel panel-primary">
      <div class="panel-heading text-center"><b>BUSCAR CLIENTES</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-3">
            <b>Num. Historia Clinica :</b>
            <input type="hidden" class="form-control" id="txt_tip" value="N">
            <input type="hidden" class="form-control" id="txt_id">   
            <div class="input-group">
                <input type="text" class="form-control input-sm" id="txt_codigo" onblur="validar_num_historia()" autocomplete="off">                
                <span class="input-group-addon" title="Buscar" onclick="buscar_cod('C1','txt_codigo')"><i class="fa fa-search"></i></span>
                <!-- <span class="input-group-addon" title="Buscar"><i class="fa fa-search"></i></span> -->
            </div>            
          </div>
          <div class="col-sm-5">
             <b>Nombre</b>
              <input type="text" name="txt_nombre" id="txt_nombre" class="form-control input-sm" onblur="nombres(this.value)" autocomplete="off">            
          </div>
          <div class="col-sm-4">
             <b>RUC / CI:</b>
            <input type="text" name="txt_ruc" id="txt_ruc" class="form-control input-sm" onblur="paciente_existente()" onkeyup="num_caracteres('txt_ruc',10)" autocomplete="off">            
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3">
            <b>Provincia:</b>
            <select class="form-control input-sm" id="ddl_provincia">
              <option value="0">Seleccione una provincia</option>
            </select>       
          </div>
          <div class="col-sm-3">
             <b>Localidad:</b>
            <input type="text" name="txt_localidad" id="txt_localidad" class="form-control input-sm" value="QUITO" autocomplete="off">
          </div>
          <div class="col-sm-3">
            <b>Teléfono:</b>
            <input type="text" name="txt_telefono" id="txt_telefono" class="form-control input-sm" autocomplete="off">            
          </div>
          <div class="col-sm-3">
            <b>Email:</b>
            <input type="text" name="txt_email" id="txt_email" class="form-control input-sm" value="comprobantes@clinicasantabarbara.com.ec" autocomplete="off">            
          </div>
          
        </div> 
      </div>
    </div>
  </div>
  <div class="col-sm-12">
     <div class="col-sm-6">
        <input type="text" name="" placeholder="Buscar" class="form-control" onkeyup="cargar_clientes()" id="txt_query">
        <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_nombre" checked="" value="N"><b> Nombre</b></label>
        <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_codigo" value="C"><b> Num. Historia Clinica </b></label>
        <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_ruc" value="R"><b> RUC / CI</b></label>
       
            
     </div> 
     <div class="col-sm-6">
        <div class="modal-footer">
          <input type="hidden" name="txt_validado" id="txt_validado" value="0">
        <button type="button" class="btn btn-primary" id="btn_nu" onclick="nuevo_paciente()"><i class="fa fa-user-plus"></i> Nuevo cliente</button>
        <button type="button" class="btn btn-default" onclick=" limpiar()"><i class="fa fa-paint-brush"></i> Limpiar</button>
        <!-- <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Imprimirr</button> -->
      </div>
     </div>             
  </div>
  <div class="col-sm-12">
  	<div class="col-sm-10">
        <!-- N de articulos encontrados: 000 -->
      </div>
      <input type="hidden" name="" id="txt_pag" value="0">
      <div class="col-sm-12 text-right" id="tbl_pag">
        <!-- mostrados: 1-50 -->
      </div>  	
  </div>
  
  <div class="col-sm-12" >
  	<div class="table-responsive">      
  		<table class="table table-hover">
  			<thead>
  				<th>ITEM</th>
  				<th>NUM HISTORIA</th>
  				<th>NOMBRE</th>
  				<th>RUC</th>
  				<th>TELEFONO</th>
  				<th></th>
  			</thead>
  			<tbody id="tbl_pacientes">
  				<tr>
  					<td></td>
  					<td></td>
  					<td></td>
  					<td></td>
  					<td></td>
  					<td>
  						<button class="btn btn-sm"><span class="glyphicon glyphicon-pencil"></span></button>
  						<button class="btn btn-sm"><span class="glyphicon glyphicon-search"></span></button>
  						<button class="btn btn-sm"><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>
  			</tbody>
  		</table>
  	</div>
  </div>
