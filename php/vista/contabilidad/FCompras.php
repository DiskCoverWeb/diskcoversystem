<?php
date_default_timezone_set('America/Guayaquil');  
$prv ='.';
$ben = '.';
$fec = date('Y-m-d');
$tipo2 = ''; if(isset($_GET['tipo']) && $_GET['tipo']!=''){$tipo2 = 1;}
if(isset($_GET['prv']))
{
	$prv = $_GET['prv'];
}
if(isset($_GET['ben']))
{
	$ben = $_GET['ben'];
} 
if(isset($_GET['fec']))
   $fec = $_GET['fec'];
?>
<style type="text/css">
    button:focus {
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
 }
  input[type=text]:focus {
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
 }
 input[type=date]:focus {
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
 }
 input[type=radio]:focus {
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
 }
 input[type=checkbox]:focus {
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
 }
 select :active {
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
}
.input-xs:focus
{
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
}

</style>
<script type="text/javascript">
    var tipo2 = '<?php echo $tipo2; ?>'
</script>

<script src="../../dist/js/kardex_ing.js"></script>
<script type="text/javascript">
  $(document).ready(function()
  {
    $('#ChRetB').focus();    
    $('#myModal_espera').modal('show');
    familias();
    contracuenta();
    Trans_Kardex();
    bodega();
    marca();
    var ben = '<?php echo $ben;?>';
    var fecha = '<?php echo $fec;?>';
    codigo_beneficiario('<?php echo $prv; ?>');
    DCBenef_LostFocus(ben,'','');
    ddl_DCSustento(fecha);
    ddl_DCConceptoRet(fecha);
    ddl_DCPorcenIce(fecha);
    ddl_DCPorcenIva(fecha);
    $('#MBFechaEmi').val(fecha);
    $('#MBFechaRegis').val(fecha);
    $('#MBFechaCad').val(fecha);
console.log(tipo2)
    if(tipo2!='')
    {
        autocoplet_bene();
        Tipo_De_Comprobante_No();
    }
    
  });

     function autocoplet_bene(){
      $('#DCProveedor').select2({
        placeholder: 'Seleccione una beneficiario',
        width:'90%',
        ajax: {
          url:   '../controlador/contabilidad/incomC.php?beneficiario_p=true',
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

   function familias()
  {
      $('#ddl_familia').select2({
        placeholder: 'Seleccione una Familia',
        ajax: {
           url:   '../controlador/inventario/registro_esC.php?familias=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
           /// console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });

  }
   function producto_famili(familia)
  { 
    var fami = $('#ddl_familia').val();
    $('#ddl_producto').select2({
        placeholder: 'Seleccione producto',
        ajax: {
           url:   '../controlador/inventario/registro_esC.php?producto=true&fami='+fami,
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
function contracuenta()
  { 
    $('#DCCtaObra').select2({
        placeholder: 'Seleccione Contracuenta',
        ajax: {
           url:   '../controlador/inventario/registro_esC.php?contracuenta=true',
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

  function codigo_beneficiario(ruc)
  { 
    var parametros =
    {
        'ruc':ruc,
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/registro_esC.php?leercodigo=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            if (response.length !=0) 
            {
                // console.log(response)
                Ult_fact_Prove(response);
            }
         
      }
    });  

  }

  function leercuenta()
  { 
     $('#DCBenef').val('').trigger('change');
    var parametros =
    {
        'cuenta':$('#DCCtaObra').val(),
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/registro_esC.php?leercuenta=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            if (response.length !=0) 
            {
                $('#Codigo').val(response.Codigo);
                $('#Cuenta').val(response.Cuenta);
                $('#SubCta').val(response.SubCta);
                $('#Moneda_US').val(response.Moneda_US);
                $('#TipoCta').val(response.TipoCta);
                $('#TipoPago').val(response.TipoPago);
                ListarProveedorUsuario();

            }
         
      }
    });  

  }

   function Trans_Kardex()
  { 
    $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/registro_esC.php?Trans_Kardex=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        if (response.length !=0) 
        {
            console.log(response);
        }         
      }
    });  

  }

     function bodega()
  { 
    var option = '<option value="">Seleccione bodega</option>';
    $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/registro_esC.php?bodega=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        if (response.length !=0) 
        {
        $.each(response,function(i,item){
          //  console.log(item);
             option+='<option value="'+item.CodMar+'">'+item.Marca+'</option';
           });
           $('#DCBodega').html(option); 
        }         
      }
    });  

  }


   function marca()
  { 
    var option = '<option value="">Seleccione marca</option>';
    $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/registro_esC.php?marca=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        if (response.length !=0) 
        {
           $.each(response,function(i,item){
           // console.log(item);
             option+='<option value="'+item.CodMar+'">'+item.Marca+'</option';
           });
           $('#DCMarca').html(option); 
        }         
      }
    });  

  }



   function ListarProveedorUsuario()
  { 
    var cta = $('#SubCta').val();
    var contra = $('#DCCtaObra').val();
    $('#DCBenef').select2({
        placeholder: 'Seleccione Cliente',
        ajax: {
           url:   '../controlador/inventario/registro_esC.php?ListarProveedorUsuario=true&cta='+cta+'&contra='+contra,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
          //  console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   

  }

  function guardar()
  {
  	var tipo = $('input:radio[name=rbl_]:checked').val();
  }


  function modal_retencion()
  {
  	if($('#rbl_retencion').prop('checked'))
  	{
  		$('#myModal').modal('show');
  	}
  }

  function detalle_articulo()
  {
    var arti = $('#ddl_producto').val();
    var fami = $('#ddl_familia').val();
    var nom_ar = $('select[name="ddl_producto"] option:selected').text();
    var parametros = 
    {
        'arti':arti,
        'nom':nom_ar,
        'fami':fami,
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/registro_esC.php?detalle_articulos=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        if (response.length !=0) 
        {
            $('#labelProductro').val(response.producto);
            $('#LabelUnidad').val(response.unidad);
            $('#LabelCodigo').val(response.codigo);
            $('#TxtRegSanitario').val(response.registrosani);
            if(response.si_no==0){
                 $('#Sin').prop('checked',true);
            }else
            {
                $('#con').prop('checked',true);
            }
        // console.log(response);
        }         
      }
    });  

  }
  function tipo_ingreso()
  {
    if($('#ingreso').prop('checked'))
    {
        // alert('ingreso');
        $('#DCCtaObra').attr('disabled',false);
        $('#DCBenef').attr('disabled',false);
        $('#cbx_contra_cta').attr('disabled',false);
        $('#cbx_contra_cta').attr('checked',true);
    }else
    {
        $('#DCCtaObra').attr('disabled',true);
        $('#DCBenef').attr('disabled',true);
        $('#cbx_contra_cta').attr('disabled',true);
        $('#cbx_contra_cta').attr('checked',false);
        // alert('egreso');
    }

  }
  function limpiar_retencaion()
  {
    $('#modal_cuenta').modal('hide');
     window.parent.postMessage('closeModal', '*');
  }


  function pdf_retencion()
  {

    var src ="../controlador/modalesC.php?pdf_retencion=true";
    window.open(src,'_blank');

  }


  function datos_pro()
  {
    var ddl = $('#DCProveedor').val();
    var ddl = ddl.split('-');
    $('#txtemail').val(ddl[1])
    $('#LblTD').val(ddl[2])
    $('#LblNumIdent').val(ddl[3])
    var nombre = $('#DCProveedor option:selected').text()
    $('#DCProveedor').empty()
    $('#DCProveedor').append($('<option>',{value:ddl[4], text:nombre,selected: true }));
  }


  function addCliente(){
    $("#myModal").modal("show");
    var src ="../vista/modales.php?FCliente=true&proveedor=true";
     $('#FCliente').attr('src',src).show();
  }
  function usar_cliente(nombre,ruc,codigo,email,td='N')
  {
      $('#txtemail').val(email);
      $('#LblNumIdent').val(ruc);
      $('#codigoCliente').val(codigo);
      $('#LblTD').val(td);
      // $('#DCCliente').append('<option value="' +codi+ ' ">' + datos[indice].text + '</option>');
      $('#DCProveedor').append($('<option>',{value:codigo, text:nombre,selected: true }));
      $('#myModal').modal('hide');
  }


  function eliminar_linea_Retencion(A_no,Codret)
  {

     var parametros = 
    {
        'a_no':A_no,
        'cod':Codret,
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/registro_esC.php?eliminar_air=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        if (response.length !=0) 
        {
           cargar_grilla();
        }         
      }
    });  


  }

  </script>
<div class="row">
    <?php if($tipo2!=''){?>
        <div class="col-sm-12 text-center">
          <h4  style="float: top;padding: 5px 10px 5px 10px;vertical-align:top; margin-top: 1px; margin-bottom: 1px;" id='num_com'>
            Comprobante de Diario No. 0000-00000000
          </h4>
        </div>
    <?php }?>
          <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-8" style="margin-bottom: auto;">
                    <div class="box box-info" style="margin-bottom: auto;">
                        <div class="box-header" style="padding:0px">
                            <h3 class="box-title">Retencion de IVA por</h3>
                        </div>
                        <div class="box-body" style="padding-top: 0px;">
                            <div class="row">
                                <div class="col-sm-3">
                                  <input type="hidden" name="txt_opc_mult" id="txt_opc_mult" value="<?php if(isset($_GET['opc_mult'])){ echo $_GET['opc_mult']; }?>">
                                    <label class="radio-inline" onclick="habilitar_bienes()"><input type="checkbox" name="ChRetB" id="ChRetB"> Bienes</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="" id="DCRetIBienes" style="display:none; width: 100%;">
                                        <option>Seleccione Tipo Retencion</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="radio-inline" onclick="habilitar_servicios()"><input type="checkbox" name="ChRetS" id="ChRetS">Servicios</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="" id="DCRetISer" style="display: none; width: 100%;">
                                        <option>Seleccione Tipo Retencion</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-center">
                  <button class="btn btn-default" id="btn_g"> <img src="../../img/png/grabar.png"  onclick="validar_formulario();"><br> Guardar</button>
                  
                  <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> -->
                  <button class="btn btn-default"  data-dismiss="modal" onclick="limpiar_retencaion();"> <img src="../../img/png/bloqueo.png" ><br> Cancelar</button>

                  <!-- <button class="btn btn-default" onclick="pdf_retencion();"> <img src="../../img/png/bloqueo.png" ><br> pdf</button> -->

                </div>            
            </div>
            <div class="row">
              <div class="col-sm-8">
                <b>PROVEEDOR</b>
                <?php if($tipo2!=''){ ?>
                    <!-- <div class="input-group"> -->
                      <select class="form-control input-xs" id="DCProveedor" onchange="datos_pro()">
                        <option value="">No seleccionado</option>
                      </select>
                    <button type="button" class="btn btn-success btn-xs btn-flat" onclick="addCliente()" title="Nuevo cliente">
                      <span class="fa fa-user-plus"></span>
                    </button>   
                    <!-- </div> -->
                <?php }else{ ?>
                     <select class="form-control input-xs" id="DCProveedor">
                          <option value="">No seleccionado</option>
                        </select>
                <?php } ?>
              </div>
              <div class="col-sm-1"><br>
                <input type="text" class="form-control input-xs" name="" id="LblTD" style="color: red" readonly="">
              </div>
              <div class="col-sm-3"><br>                
                <input type="text" class="form-control input-xs" name="" id="LblNumIdent" readonly="">
                <input type="hidden" class="form-control input-xs" name="" id="txtemail" readonly="">
              </div>
            </div>
          </div><br>
          <div class="col-sm-12">
            <ul class="nav nav-tabs">
               <li class="nav-item active">
                 <a class="nav-link" data-toggle="tab" href="#home">Comprobante de compra: FORMULARIO 104</a>
               </li>
               <li class="nav-item">
                 <a class="nav-link" data-toggle="tab" href="#menu1">Conceptos AIR</a>
               </li>
               <li class="nav-item">
                 <a class="nav-link" data-toggle="tab" href="#menu2">Partidos politicos</a>
               </li>
             </ul>
               <!-- Tab panes -->
             <div class="tab-content">
               <div class="tab-pane modal-body active" id="home" style="padding-top: 0px;">
                   <div class="row">
                            <div class="col-sm-10">
                                <div class="row">
                                     <div class="col-sm-3">
                                        <b>Devolucion del IVA:</b>
                                     </div>
                                     <div class="col-sm-19">
                                        <label class="radio-inline"><input type="radio" name="cbx_iva" id="iva_si" value="S"> SI</label>
                                        <label class="radio-inline"><input type="radio" name="cbx_iva" id="iva_no" value="N" checked=""> NO</label>
                                    </div>                                    
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <b>Tipo de sustento Tributario</b>
                                        <select class="" style="width:100%" id="DCSustento" onchange="ddl_DCTipoComprobante('<?php echo $fec?>');ddl_DCDctoModif();" onblur="cambiar_fecha_sus();ddl_DCTipoComprobante('<?php echo $fec?>') ">
                                            <option value="">seleccione sustento </option>
                                        </select>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <br>
                                <button class="btn btn-default text-center" onclick="cambiar_air()" id="btn_air"><i class="fa fa-arrow-right"></i><br>AIR</button>
                            </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="box box-info" style="margin-bottom: auto;">
                                <div class="box-header" style="padding:0px">
                                    <h3 class="box-title"><b>INGRESE LOS DATOS DE LA FACTURA, NOTA DE VENTA, ETC</b></h3>
                                </div>
                                <div class="box-body" style="padding-top:0px">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <b>Tipo de comprobate</b>
                                            <select class="form-control input-xs" id="DCTipoComprobante" onchange="mostrar_panel()" onblur="selec_tipo_comp()">
                                                <option value="1">Factura</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <b>Serie</b>
                                            <div class="row">
                                                <div class="col-sm-6" style="padding: 0px">
                                                    <input type="text" name="" class="form-control input-xs" id="TxtNumSerieUno" placeholder="001" onblur="autocompletar_serie_num_fac(this.id)" onkeyup=" solo_3_numeros(this.id)"  autocomplete="off">
                                                </div>
                                                <div class="col-sm-6" style="padding: 0px">
                                                    <input type="text" name="" class="form-control input-xs" id="TxtNumSerieDos" placeholder="001" onblur="autocompletar_serie_num_fac(this.id)" onkeyup=" solo_3_numeros(this.id)"  autocomplete="off">
                                                </div>
                                            </div>                                
                                        </div>
                                        <div class="col-sm-2">
                                            <b>Numero</b>
                                            <input type="text" name="" class="form-control input-xs" id="TxtNumSerietres" onblur="validar_num_factura(this.id)" placeholder="000000001" onkeyup="solo_9_numeros(this.id)"  autocomplete="off">
                                        </div>
                                        <div class="col-sm-3">
                                            <b>Autorizacion</b>
                                            <input type="text" name="" class="form-control input-xs text-right" id="TxtNumAutor" onblur="autorizacion_factura()" placeholder="0000000001"  autocomplete="off"> <!--onkeyup="solo_10_numeros(this.id)"-->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="col-sm-2"  style="padding-left: 0px;padding-right: 0px">
                                             <b>Emision</b>
                                                <input type="date" name="" class="form-control input-xs" value="<?php echo date('Y-m-d') ?>" id="MBFechaEmi" autocomplete="off">
                                            </div>
                                            <div class="col-sm-2"  style="padding-left: 0px;padding-right: 0px">
                                                <b>Registro</b>
                                                <input type="date" name="" class="form-control input-xs" value="<?php echo date('Y-m-d') ?>" id="MBFechaRegis" onblur="validar_fecha()"  autocomplete="off">
                                            </div>                                            
                                         <div class="col-sm-2" style="padding-left: 0px;padding-right: 0px">
                                                <b>Caducidad</b>
                                                <input type="date" name="" class="form-control input-xs" value="<?php echo date('Y-m-d') ?>" id="MBFechaCad"  autocomplete="off">
                                            </div>
                                         <div class="col-sm-2">
                                                <b>No Obj. IVA</b>
                                                <input type="text" name="" class="form-control input-xs text-right" value="0.00" id="TxtBaseImpoNoObjIVA"  autocomplete="off" onblur="base_impo_cal()">
                                            </div>
                                            <div class="col-sm-1" style="padding-right: 5px;padding-left: 5px;">
                                                <b>Tarifa 0</b>
                                                <input type="text" name="" class="form-control input-xs text-right" value="0.00" id="TxtBaseImpo"  autocomplete="off"  onblur="base_impo_cal()">
                                            </div>
                                            <div class="col-sm-1" style="padding-right: 5px;padding-left: 5px;">
                                                <b>Tarifa 12</b>
                                                <input type="text" name="" class="form-control input-xs text-right" value="0.00" id="TxtBaseImpoGrav"  autocomplete="off"  onblur="base_impo_cal()">
                                            </div>
                                            <div class="col-sm-2">
                                                <b>Valor ICE</b>
                                             <input type="text" name="" class="form-control input-xs  text-right" value="0.00"  id="TxtBaseImpoIce"  autocomplete="off">
                                            </div>  
                                    </div>                          
                                 </div>
                                </div>
                            </div>
                        </div>            
                    </div> 

                     <div class="row">
                        <div class="col-sm-6">
                            <div class="box box-info">
                                <div class="box-header" style="padding:0px">
                                    <h3 class="box-title">Porcentajes de las bases Imponibles</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            IVA
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="form-control input-xs" id="DCPorcenIva" onchange="calcular_iva()" onblur="calcular_iva();calcular_ice();">
                                                <option value="I">Iva</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            Valor I.V.A
                                        </div>
                                        <div class="col-sm-4">
                                           <input type="text" name="" class="form-control input-xs  text-right" id="TxtMontoIva" value="0"  autocomplete="off">
                                        </div>                            
                                    </div>
                                    <div class="row">
                                         <div class="col-sm-1">
                                            ICE
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="form-control input-xs" id="DCPorcenIce" onchange="calcular_ice()" onblur="calcular_iva();calcular_ice();">
                                                <option value="0">ICE</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            Valor ICE
                                        </div>
                                        <div class="col-sm-4">
                                           <input type="text" name="" class="form-control input-xs  text-right" id="TxtMontoIce"  value="0.00" readonly=""  autocomplete="off">
                                        </div>       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="box box-warning" style="margin-bottom:0px">
                                <div class="box-header" style="padding:0px">
                                    <h3 class="box-title">Retencion del IVA por Bienes Y/O Servicios </h3>
                                </div>
                                <div class="box-body"style="padding-top: 0px;" >
                                     <div class="row">
                                         <div class="col-sm-4"><br>
                                            Monto
                                        </div>
                                        <div class="col-sm-4">
                                            <b>BIENES</b>
                                            <input type="text" name="" class="form-control input-xs  text-right" id="TxtIvaBienMonIva" readonly="" value="0"  autocomplete="off">
                                        </div>                            
                                        <div class="col-sm-4">
                                            <b>SERVICIOS</b>
                                           <input type="text" name="" class="form-control input-xs  text-right" id="TxtIvaSerMonIva" readonly="" value="0"  autocomplete="off">
                                        </div>       
                                    </div>
                                    <div class="row">
                                         <div class="col-sm-4">
                                            Porcentaje
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="" style="width:100%" id="DCPorcenRetenIvaBien" disabled="" onchange="calcular_retencion_porc_bienes()">
                                                <option value="0">0</option>
                                            </select>
                                        </div>                            
                                        <div class="col-sm-4">
                                          <select class="" style="width:100%"  id="DCPorcenRetenIvaServ" disabled="" onchange="calcular_retencion_porc_serv()">
                                                <option value="0">0</option>
                                            </select>
                                        </div>       
                                    </div>
                                    <div class="row">
                                         <div class="col-sm-4">
                                Valor RET
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="" class="form-control input-xs  text-right" id="TxtIvaBienValRet" value="0" autocomplete="off" readonly>
                                        </div>                            
                                        <div class="col-sm-4">
                                           <input type="text" name="" class="form-control input-xs  text-right" id="TxtIvaSerValRet" value="0" autocomplete="off" readonly>
                                        </div>       
                                    </div>
                                </div>
                            </div>
                        </div>            
                    </div>

                     <div class="row" id="panel_notas" style="display: none">
                        <div class="col-sm-12">
                            <div class="box box-info">
                                <div class="box-header" style="padding:0px">
                                    <h3 class="box-title"><b>NOTAS DE DEBITO / NOTAS DE CREDITO</b></h3>
                                </div>
                                <div class="box-body" style="padding-top:0px">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <b>tipo de comprobate</b>
                                            <select class="form-control input-xs" id="DCDctoModif">
                                                <option>Seleccione tipo de comprobante</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <b>Serie</b>
                                            <div class="row">
                                                <div class="col-sm-6" style="padding: 0px">
                                                    <input type="text" name="" class="form-control input-xs" id="TxtNumSerieUnoComp" placeholder="001" onblur="autocompletar_serie_num(this.id)" onkeyup="solo_3_numeros(this.id)"  autocomplete="off">
                                                </div>
                                                <div class="col-sm-6" style="padding: 0px">
                                                    <input type="text" name="" class="form-control input-xs" id="TxtNumSerieDosComp" placeholder="001" onblur="autocompletar_serie_num(this.id)" onkeyup="solo_3_numeros(this.id)"  autocomplete="off">
                                                </div>
                                            </div>                                
                                        </div>
                                        <div class="col-sm-1" style="padding-left: 5px;padding-right: 5px">
                                            <b>Numero</b>
                                            <input type="text" name="" class="form-control input-xs" id="CNumSerieTresComp" onkeyup="solo_9_numeros(this.id)" onblur="validar_num_factura(this.id)" placeholder="000000001"  autocomplete="off">
                                        </div>
                                        <div class="col-sm-2" style="padding-left: 5px;padding-right: 5px">
                                            <b>Fecha</b>
                                            <input type="date" name="" class="form-control input-xs" id="MBFechaEmiComp"  autocomplete="off">
                                        </div>
                                        <div class="col-sm-3" style="padding-right: 5px;">
                                            <b>Autorizacion sri</b>
                                            <input type="text" name="" class="form-control input-xs" id="TxtNumAutComp"  autocomplete="off">
                                        </div>
                                    </div>
                                 </div>
                            </div>
                        </div>            
                    </div> 
               </div>
               <div class="tab-pane modal-body fade" id="menu1" style="padding-top:0px" >
                  <div class="row">
                    <div class="col-sm-4">
                      <b>Forma de pago</b>
                      <select class="" style="width: 100%;" onchange="mostrar_panel_ext()" id="CFormaPago">
                        <option value="">Seleccione forma de pago</option>
                        <option value="1" selected="">Local</option>
                        <option value="2">Exterior</option>
                      </select>
                    </div>
                    <div class="col-sm-8">
                      <b>Tipo de pago</b>
                      <select class="" style="width: 100%;" id="DCTipoPago" onchange="$('#DCTipoPago').css('border','1px solid #d2d6de');">
                        <option value="">Seleccione tipo de pago</option>
                      </select>                    
                    </div>
                  </div>
                  <div class="row" id="panel_exterior" style="display: none;">
                    <div class="col-sm-4">
                      <b>Pais al que se efectua el pago</b>
                      <select class="form-control input-xs" id="DCPais">
                        <option>Seleccione Pais</option>
                      </select>
                    </div>
                    <div class="col-sm-6"><br>
                      Aplica convenio de doble tributacion?                   
                      <br>
                      Pago sujeto a retencion en aplicacion de la forma legal?
                      <br>
                    </div>
                    <div class="col-sm-2 text-right"><br>
                      <label class="radio-inline"><input type="radio" name="rbl_convenio" checked="" value="SI">SI</label>
                      <label class="radio-inline"><input type="radio" name="rbl_convenio" value="NO">NO</label>
                      <label class="radio-inline"><input type="radio" name="rbl_pago_retencion" checked="" value="SI">SI</label>
                      <label class="radio-inline"><input type="radio" name="rbl_pago_retencion" value="NO">NO</label>
                    </div>
                  </div>
                  <div class="row">
                        <div class="col-sm-12">
                            <div class="box box-info" style="margin-bottom: 0px;" >
                                <div class="box-header" style="padding:0px">
                                    <h3 class="box-title"><b>INGRESE LOS DATOS DE LA RETENCION_________________FORMULARIO 103</b></h3>
                                </div>
                                <div class="box-body" style="padding-top:0px">
                                    <div class="row">
                                        <div class="col-sm-4">
                                          <label class="radio-inline" onclick="mostra_select()" id="lbl_rbl"><input type="checkbox" name="ChRetF" id="ChRetF"> Retencion en la fuente</label>
                                        </div>
                                        <div class="col-sm-8">
                                          <select class="" id="DCRetFuente" style="display: none; width: 100%" onchange="$('#DCRetFuente').css('border','1px solid #d2d6de');">
                                            <option value=""> Seleccione Tipo de retencion</option>
                                          </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-sm-2">
                                        Serie
                                        <div class="row">
                                          <div class="col-sm-6"style="padding-left: 0px;padding-right: 0px;"><input type="text" class="form-control input-xs" name="TxtNumUnoComRet" id="TxtNumUnoComRet" onkeyup="solo_3_numeros(this.id)" placeholder="001" onblur="autocompletar_serie_num(this.id)"  autocomplete="off"></div>
                                          <div class="col-sm-6"style="padding-left: 0px;padding-right: 0px;"><input type="text" class="form-control input-xs" name="TxtNumDosComRet" id="TxtNumDosComRet" onkeyup="solo_3_numeros(this.id)" placeholder="001" onblur="autocompletar_serie_num(this.id)"  autocomplete="off"></div>
                                        </div>
                                      </div>
                                      <div class="col-sm-2">
                                        Numero
                                        <input type="text" class="form-control input-xs" name="TxtNumTresComRet" id="TxtNumTresComRet" onblur="validar_num_retencion()" onkeyup="solo_9_numeros(this.id)" placeholder="000000001" tabindex="-1"  autocomplete="off">
                                        <input type="hidden" name="val_num" id="val_num" value="0"  autocomplete="off">
                                      </div>
                                      <div class="col-sm-4">
                                        Autorizacion
                                        <input type="text" name="" class="form-control input-xs" id="TxtNumUnoAutComRet" onblur="validar_autorizacion()"  autocomplete="off">
                                      </div>
                                      <script type="text/javascript"> 
                                               function selec_tipo_comp()
                                               {
                                                if($('#DCTipoComprobante').val()=='')
                                                {
                                                 Swal.fire('Seleccione tipo de comprobante','','info').then(()=>{ $('#DCTipoComprobante').focus();});
                                                }

                                               }                                             
                                              function cambiar_fecha()
                                              {

                                                var sus = $('#DCSustento').val();
                                                if(sus=='00')
                                                { 
                                                  if(Date.parse($('#MBFechaEmi').val()) <= Date.parse('2000-01-01')){
                                                    $('#MBFechaRegis').val($('#MBFechaEmi').val());       
                                                }else{
                                                    Swal.fire('La Fecha de Emisión debe ser menor a 2000-01-01','','info').then(()=>{ $('#MBFechaEmi').focus();});
                                                     today = '2000-01-01';
                                                    // console.log('2000/01/01');
                                                    $('#MBFechaEmi').val(today);
                                                 }

                                                   

                                                }else
                                                {        
                                                 var fecha = '<?php echo $fec;?>';                                            
                                                    $('#MBFechaEmi').val(fecha);
                                                    $('#MBFechaRegis').val($('#MBFechaEmi').val());
                                                }
                                                $('#MBFechaRegis').val($('#MBFechaEmi').val());
                                              }
                                              function cambiar_fecha_sus()
                                              {
                                                var sus = $('#DCSustento').val();
                                                if(sus=='00')
                                                {                                                   
                                                    today = '2000-01-01';
                                                    // console.log('2000/01/01');
                                                    $('#MBFechaEmi').val(today);
                                                    $('#MBFechaRegis').val($('#MBFechaEmi').val());

                                                }else
                                                {           
                                                 var fecha = '<?php echo $fec;?>';                                         
                                                    $('#MBFechaEmi').val(fecha);
                                                    $('#MBFechaRegis').val($('#MBFechaEmi').val());
                                                }
                                              }
                                              function validar_fecha(){
                                              var fr = new Date($('#MBFechaRegis').val());
                                              var fe = new Date($('#MBFechaEmi').val());
                                              if(fr<fe)
                                              {
                                                Swal.fire('La Fecha de Registro debe ser mayor o igual que la Fecha de Emisión','','info');
                                                $('#MBFechaRegis').val($('#MBFechaEmi').val());
                                              }
                                            }
                                            </script>
                                      <div class="col-sm-4">
                                        <div class="row">
                                          <div class="col-sm-4"><br>
                                            SUMATORIA
                                          </div>
                                          <div class="col-sm-8"><br>
                                            <input type="text" name="" class="form-control input-xs  text-right" id="TxtSumatoria"  autocomplete="off">
                                          </div>
                                        </div>                                      
                                      </div>                          
                                    </div>
                                    <div class="row">
                                      <div class="col-sm-7">
                                        <b>CODIGO DE RETENCION</b>
                                        <select class="" id="DCConceptoRet" style="width:100%" name="DCConceptoRet" onchange="calcular_porc_ret()" onblur="calcular_porc_ret()">
                                          <option value="">Seleccione Codigo de retencion</option>
                                        </select>                                        
                                      </div>
                                      <div class="col-sm-2">
                                        <b>BASE IMP</b>
                                        <input type="text" class="form-control input-xs  text-right" name="TxtBimpConA" id="TxtBimpConA"  autocomplete="off" onblur="validar_base_impo()">
                                      </div>
                                       <div class="col-sm-1" style="padding-left: 0px;padding-right: 0px">
                                        <b>PORC</b>
                                        <input type="text" class="form-control input-xs  text-right" name="TxtPorRetConA" id="TxtPorRetConA" onblur="insertar_grid()" readonly=""  autocomplete="off">
                                      </div>
                                       <div class="col-sm-2">
                                        <b>VALOR RET</b>
                                        <input type="text" class="form-control input-xs text-right" name="TxtValConA" id="TxtValConA" readonly=""  autocomplete="off">
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>            
                  </div>
                  <div class="row">
                    <div class="table-responsive" id="tbl_retencion">
                     
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 text-right">
                      <b>Total Retencion</b>
                      <input type="text" class="input-xs" name="" id="txt_total_retencion" readonly=""  autocomplete="off">
                    </div>
                  </div>        
               </div>
               <div class="tab-pane modal-body fade" id="menu2">
                 <div class="row text">
                   <div class="col-sm-12">
                     <div class="row">
                       <div class="col-sm-8">
                         <b>NUMERO DEL CONTRATO DEL PARTIDO POLITICO</b>
                       </div>
                       <div class="col-sm-4">
                         <input type="text"  class="form-control" name="" id="TxtNumConParPol"  autocomplete="off">
                       </div>
                     </div>
                   </div>
                   <div class="col-sm-12">                     
                     <div class="row">
                       <div class="col-sm-8">
                         <b>MONTO TITULO ONEROSO</b>
                       </div>
                       <div class="col-sm-4">
                         <input type="text"  class="form-control" name="TxtMonTitOner" id="TxtMonTitOner"  autocomplete="off">
                       </div>
                     </div>
                   </div>
                   <div class="col-sm-12">
                      <div class="row">
                       <div class="col-sm-8">
                         <b>MONTO DEL CONTRATO</b>
                       </div>
                       <div class="col-sm-4">
                         <input type="text"  class="form-control" name="TxtMonTitGrat" id="TxtMonTitGrat"  autocomplete="off">
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
             </div>
            
          </div>
</div>
     