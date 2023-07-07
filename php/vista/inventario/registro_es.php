
<script src="../../lib/dist/js/kardex_ing.js"></script>

<style type="text/css">
	td,
th {
  padding: 8px;
}
</style>
<script type="text/javascript">

  $(document).ready(function()
  {
    familias();
    contracuenta();
    Trans_Kardex();
    bodega();
    marca();
    
  })

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
             option+='<option value="'+item.CodBod+'">'+item.CodBod+' '+item.Bodega+'</option';
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
    $('#rbl_retencion').prop('checked',false);
    $('#myModal').modal('hide');
    cancelar();
  }
</script>

<div class="container-lg">
  <div class="row">
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
    	 <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
             <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" id="imprimir_pdf" title="Descargar PDF">
            <img src="../../img/png/impresora.png">
          </button>           
        </div>
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" id="imprimir_excel" title="Descargar Excel">
            <img src="../../img/png/table_excel.png">
          </button>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button title="Guardar"  class="btn btn-default" onclick="">
            <img src="../../img/png/grabar.png" >
          </button>
        </div>     
 </div>
</div>
<div class="container">

    <input type="hidden" name="si_no" id="si_no">


    <input type="hidden" name="" id="Codigo">
    <input type="hidden" name="" id="Cuenta">
    <input type="hidden" name="" id="SubCta">
    <input type="hidden" name="" id="Moneda_US">
    <input type="hidden" name="" id="TipoCta">
    <input type="hidden" name="" id="TipoPago">


    <input type="hidden" name="grupo_no" id="grupo_no">
    <input type="hidden" name="Tipodoc" id="Tipodoc">
    <input type="hidden" name="TipoBenef" id="TipoBenef">
    <input type="hidden" name="cod_benef" id="cod_benef">
    <input type="hidden" name="InvImp" id="InvImp">
    <input type="hidden" name="ci" id="ci">


	<div class="row"><br>
		<div class="col-sm-2">
			<select class="form-control">
				<option value="">Seleccione TP</option>
                <option value="CD">CD</option>
                <option value="NC">NC</option>
                <option value="ND">ND</option>
                <option value="CD">CD</option>
			</select>
		</div>
		<div class="col-sm-2">
			<label class="radio-inline" ><b><input type="radio" name="rbl_tipo" checked="" id="ingreso" onclick="tipo_ingreso()"> Ingreso</b></label>
			<label class="radio-inline" ><b><input type="radio" name="rbl_tipo" id="egreso" onclick="tipo_ingreso()"> Egreso</b></label>
		</div>
		<div class="col-sm-2">			
			<label class="radio-inline"><b><input type="checkbox" name="cbx_contra_cta" checked="" id="cbx_contra_cta"> CONTRA CUENTA</b></label>
		</div>
		<div class="col-sm-3">
			<select class="form-control" id="DCCtaObra" onchange="leercuenta();">
				<option>seleccione contra cuenta</option>
			</select>
		</div>
		<div class="col-sm-3">
			<select class="form-control" id="DCBenef" name="DCBenef" onchange="DCBenef_LostFocus();">
				<option>seleccione cliente </option>
			</select>
		</div>
    </div>
    <div class="row">
    	<div class="col-sm-2">
    		<b>Fecha:</b><input type="date" name="" value="<?php echo date('Y-m-d')?>" id="MBFechaI">
    	</div>
    	<div class="col-sm-2">
    		<b>Vencimiento:</b><input type="date" name="">
    	</div>
    	<div class="col-sm-5">
    		<b>POR CONCEPTO DE:</b><input type="text" name="" class="form-control input-sm" id="TextConcepto"> 
    	</div>
    	<div class="col-sm-3 text-right">
    		<br>
    		<b>N° Factura:</b><input type="text" name="" class="input-sm"> 
    	</div>
    </div>
    <div class="row">
    	<div class="col-sm-4">
    		<label class="radio-inline"><b><input type="checkbox" name="rbl_retencion" onclick="Ult_fact_Prove($('#DCProveedor').val());modal_retencion();" id="rbl_retencion" > Retencion en la fuente:</b> <input type="text" name="" class="input-sm"></label>
    	</div>
    	<div class="col-sm-4">
    		<b>Retencion del I.V.A:</b> <input type="text" name="" class="input-sm">
    	</div>
    </div> 
    <div class="row"><br>
        
            
    	<div class="col-md-4"><br>
            <select class="form-control input-sm" id="ddl_familia" name="ddl_producto" onchange="producto_famili($('#ddl_familia').val())">
                <option value="">Seleccione un Familiar</option>
            </select>
    		<select class="form-control input-sm" id="ddl_producto" onchange="detalle_articulo()">
    			<option>Seleccione un articulo</option>
    		</select>
    	</div>
        <div class="col-md-8">
            <div class="col-sm-12">
                <input type="text" class="form-control text-center" name="" id="labelProductro" value="PRODUCTO" readonly="">
            </div>
            
    	<div class="col-sm-4">
    		<label class="radio-inline" ><b><input type="radio" name="rbl_" id="con"> Con Iva</b></label>
			<label class="radio-inline" ><b><input type="radio" name="rbl_" id="Sin"> Sin Iva</b></label>   
			<select class="form-control input-sm" id="DCBodega">
				<option>Seleccione</option>
			</select> 		
    	</div>
    	<div class="col-sm-4">
    		<b>MARCA</b><br>
    		<select class="form-control input-sm" id="DCMarca">
    			<option>Seleccione Marca</option>
    		</select>
    	</div>
    	<div class="col-sm-4">
    		<b>CODIGO</b><br>
    		<input type="text" class="form-control input-sm" id="LabelCodigo">    			
    	</div>
    	
        </div>


    </div>
    <div class="row">
    	<div class="col-sm-1">
    	  <b>UNIDAD</b>
    	  <input type="text" name="" class="form-control input-sm" id="LabelUnidad">
    	</div>
    	<div class="col-sm-1">
    		<b>GUIA N°</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div>
    	<div class="col-sm-1">
    		<b>CANTIDAD</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div>
    	<div class="col-sm-1">
    		 <b>VALOR UNI</b>
    		 <input type="text" name="" class="form-control input-sm">
    	</div>
    	<div class="col-sm-2">
    		<b>CODIGO DE BARRAS</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div>
    	<div class="col-sm-2">
    		<b>LOTE N°</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-2">
    		<b>FECHA FAB</b>
    		<input type="date" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-2">
    		<b>FECHA EXP</b>
    		<input type="date" name="" class="form-control input-sm">
    	</div>     	
    </div> 
    <div class="row">
    	<div class="col-sm-3">
    		<b>REG. SANITARIO</b>
    		<input type="text" name="" class="form-control input-sm" id="TxtRegSanitario">
    	</div> 
    	<div class="col-sm-3">
    		<b>MODELO</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-3">
    		<b>PROCEDENCIA</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-3">
    		<b>SERIE N°</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-5">
    		<b>DESC.1</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-5">
    		<b>DESC.2</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-2">
    		<b>VALOR TOTAL</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div>     	
    </div>
    <div class="row">
    	<div class="table-responsive" style="height: 400px">
    		<table>
    			<thead>
    				<th width="25px">TP</th>
    				<th>CODIGO_INV</th>
    				<th>DH</th>
    				<th>PRODUCTO</th>
    				<th>CANT_ES</th>
    				<th>VALOR_UNI</th>
    				<th>VALOR_TOTAL</th>
    				<th>CANTIDAD</th>
    				<th>SALDO</th>
    				<th>P_DESC</th>
    				<th>P_DESC1</th>
    				<th>IVA</th>
    				<th>CTA_INVENTARIO</th>
    				<th>CONTRA_CTA</th>
    				<th>UNIDAD</th>
    				<th>CodBod</th>
    				<th>CodMar</th>
    				<th>COD_BAR</th>
    				<th>T_No</th>
    				<th>Item</th>
    				<th>CodigoU</th>
    				<th>SUBCTA</th>
    				<th>Cod_Tarifa</th>
    				<th>Fecha_DUI</th>
    				<th>No_Refrendo</th>
    				<th>DUI</th>
    				<th>A_No</th>
    				<th>ValorEM</th>
    				<th>Especifico</th>
    				<th>Consumo</th>
    				<th>Antidumping</th>
    				<th>Modernizacion</th>
    				<th>Control</th>
    				<th>Almacenaje</th>
    				<th>FODIN</th>
    				<th>Salvaguardas</th>
    				<th>Interes</th>
    				<th>CODIGO_INV1</th>
    				<th>CodBod1</th>
    				<th>Codigo_B</th>
    				<th>Codigo_Dr</th>
    				<th>ORDEN</th>
    				<th>VALOR_FOB</th>
    				<th>COMIS</th>
    				<th>TRANS_UNI</th>
    				<th>TRANS_TOTAL</th>
    				<th>PRECION_CIF</th>
    				<th>UTIL</th>
    				<th>PVP</th>
    				<th>CTA_COSTO</th>
    				<th>CTA_VENTA</th>
    				<th>TOTAL_PVP</th>
    				<th>Codigo_Tra</th>
    				<th>Lote_N°</th>
    				<th>Fecha_Fab</th>
    				<th>Fecha_Exp</th>
    				<th>Reg_Sanitario</th>
    				<th>Modelo</th>
    				<th>Procedencia</th>
    				<th>Serie_N°</th>
    			</thead>
    			<tbody>
    				
    			</tbody>
    		</table>
    	</div>
    	
    </div>	
    <div class="row"><br><br>
    	<div class="col-sm-2">
    		<button class="btn btn-default" data-toggle="modal" data-target="#myModal_comprobante">Seleccionar <br> comprobante</button>
    	</div>
    	<div class="col-sm-2">
    		<b>DIF x DECIMALES</b>
    		<input type="text" class="input-sm form-control" name="">
    	</div>
    	<div class="col-sm-2">
    		<b>SUBTOTAL</b>
    		<input type="text" class="input-sm form-control" name="">
    	</div>
    	<div class="col-sm-2">
    		<b>I.V.A</b>
    		<input type="text" class="input-sm form-control" name="">
    	</div>
    	<div class="col-sm-2">
    		<b>TOTAL</b>
    		<input type="text" class="input-sm form-control" name="">
    	</div>
    </div>
</div>
</div>


<div id="myModal" class="modal fade" role="dialog"  role="dialog"  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title">Compras</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-8">
                    <div class="box box-info">
                        <div class="box-header" style="padding:0px">
                            <h3 class="box-title">Retencion de IVA por</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="radio-inline" onclick="habilitar_bienes()"><input type="checkbox" name="ChRetB" id="ChRetB"> Bienes</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm" id="DCRetIBienes">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="radio-inline" onclick="habilitar_servicios()"><input type="checkbox" name="ChRetS" id="ChRetS">Servicios</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm" id="DCRetISer" onblur="alert('s');">
                                        <option>Seleccione Tipo Retencion</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-center">
                  <button class="btn btn-default"> <img src="../../img/png/grabar.png"  onclick="validar_formulario()"><br> Guardar</button>
                  <button class="btn btn-default"  data-dismiss="modal" onclick="limpiar_retencaion()"> <img src="../../img/png/bloqueo.png" ><br> Cancelar</button>
                </div>            
            </div>
            <div class="row">
              <div class="col-sm-8">
                <b>PROVEEDOR</b>
                <select class="form-control" id="DCProveedor">
                  <option value="">No seleccionado</option>
                </select>
              </div>
              <div class="col-sm-1"><br>
                <input type="text" class="form-control input-sm" name="" id="LblTD" style="color: red" readonly="">
              </div>
              <div class="col-sm-3"><br>                
                <input type="text" class="form-control input-sm" name="" id="LblNumIdent" readonly="">
              </div>
            </div>
          </div><br>
          <div class="col-sm-12">
            <ul class="nav nav-tabs">
               <li class="nav-item active">
                 <a class="nav-link" data-toggle="tab" href="#home">Comprobante de compra</a>
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
               <div class="tab-pane modal-body active" id="home">
                   <div class="row">
                            <div class="col-sm-10">
                                <div class="row">
                                     <div class="col-sm-3">
                                        <b>Devolucion del IVA:</b>
                                     </div>
                                     <div class="col-sm-19">
                                        <label class="radio-inline"><input type="radio" name="cbx_iva" id="iva_si" value="S" checked=""> SI</label>
                                        <label class="radio-inline"><input type="radio" name="cbx_iva" id="iva_no" value="N"> NO</label>
                                    </div>                                    
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <b>Tipo de sustento Tributario</b>
                                        <select class="form-control input-sm" id="DCSustento" onchange="ddl_DCTipoComprobante();ddl_DCDctoModif();">
                                            <option value="">seleccione sustento </option>
                                        </select>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <br>
                                <button type="button" id="btn_air" class="btn btn-default text-center" onclick="cambiar_air()"><i class="fa fa-arrow-right"></i><br>AIR</button>
                            </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="box box-info">
                                <div class="box-header" style="padding:0px">
                                    <h3 class="box-title"><b>INGRESE LOS DATOS DE LA FACTURA, NOTA DE VENTA, ETC_________________FORMULARIO 104</b></h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <b>tipo de comprobate</b>
                                            <select class="form-control input-sm" id="DCTipoComprobante" onchange="mostrar_panel()">
                                                <option value="">Seleccione tipo de comprobante</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <b>Serie</b>
                                            <div class="row">
                                                <div class="col-sm-6" style="padding: 0px">
                                                    <input type="text" name="" class="form-control input-sm" id="TxtNumSerieUno" placeholder="001" onblur="autocompletar_serie_num(this.id)" onkeyup=" solo_3_numeros(this.id)">
                                                </div>
                                                <div class="col-sm-6" style="padding: 0px">
                                                    <input type="text" name="" class="form-control input-sm" id="TxtNumSerieDos" placeholder="001" onblur="autocompletar_serie_num(this.id)" onkeyup=" solo_3_numeros(this.id)">
                                                </div>
                                            </div>                                
                                        </div>
                                        <div class="col-sm-2">
                                            <b>Numero</b>
                                            <input type="text" name="" class="form-control input-sm" id="TxtNumSerietres" onblur="validar_num_factura(this.id)" placeholder="000000001" onkeyup="solo_9_numeros(this.id)">
                                        </div>
                                        <div class="col-sm-3">
                                            <b>Autorizacion</b>
                                            <input type="text" name="" class="form-control input-sm" id="TxtNumAutor" onblur="autorizacion_factura()" placeholder="0000000001" onkeyup="solo_10_numeros(this.id)">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="col-sm-2"  style="padding-left: 0px;padding-right: 0px">
                                             <b>Emision</b>
                                                <input type="date" name="" class="form-control input-sm" value="<?php echo date('Y-m-d') ?>" id="MBFechaEmi">
                                            </div>
                                            <div class="col-sm-2"  style="padding-left: 0px;padding-right: 0px">
                                                <b>Registro</b>
                                                <input type="date" name="" class="form-control input-sm" value="<?php echo date('Y-m-d') ?>" id="MBFechaRegis">
                                            </div>
                                         <div class="col-sm-2" style="padding-left: 0px;padding-right: 0px">
                                                <b>Caducidad</b>
                                                <input type="date" name="" class="form-control input-sm" value="<?php echo date('Y-m-d') ?>" id="MBFechaCad">
                                            </div>
                                         <div class="col-sm-2">
                                                <b>No Obj. IVA</b>
                                                <input type="text" name="" class="form-control input-sm" value="0.00" id="TxtBaseImpoNoObjIVA">
                                            </div>
                                            <div class="col-sm-1" style="padding-right: 5px;padding-left: 5px;">
                                                <b>Tarifa 0</b>
                                                <input type="text" name="" class="form-control input-sm" value="0.00" id="TxtBaseImpo">
                                            </div>
                                            <div class="col-sm-1" style="padding-right: 5px;padding-left: 5px;">
                                                <b>Tarifa 12</b>
                                                <input type="text" name="" class="form-control input-sm" value="0.00" id="TxtBaseImpoGrav">
                                            </div>
                                            <div class="col-sm-2">
                                                <b>Valor ICE</b>
                                             <input type="text" name="" class="form-control input-sm" value="0.00"  id="TxtBaseImpoIce">
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
                                            <select class="form-control input-sm" id="DCPorcenIva" onchange="calcular_iva()">
                                                <option value="I">Iva</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            Valor I.V.A
                                        </div>
                                        <div class="col-sm-4">
                                           <input type="text" name="" class="form-control input-sm" id="TxtMontoIva" value="0">
                                        </div>                            
                                    </div>
                                    <div class="row"><br>
                                         <div class="col-sm-1">
                                            ICE
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="form-control input-sm" id="DCPorcenIce" onchange="calcular_ice()">
                                                <option value="I">ICE</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            Valor ICE
                                        </div>
                                        <div class="col-sm-4">
                                           <input type="text" name="" class="form-control input-sm" id="TxtMontoIce" readonly="">
                                        </div>       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="box box-warning">
                                <div class="box-header" style="padding:0px">
                                    <h3 class="box-title">Retencion del IVA por Bienes Y/O Servicios </h3>
                                </div>
                                <div class="box-body">
                                     <div class="row">
                                         <div class="col-sm-4"><br>
                                            Monto
                                        </div>
                                        <div class="col-sm-4">
                                            <b>BIENES</b>
                                            <input type="text" name="" class="form-control input-sm" id="TxtIvaBienMonIva" readonly="">
                                        </div>                            
                                        <div class="col-sm-4">
                                            <b>SERVICIOS</b>
                                           <input type="text" name="" class="form-control input-sm" id="TxtIvaSerMonIva" readonly="">
                                        </div>       
                                    </div>
                                    <div class="row">
                                         <div class="col-sm-4">
                                            Porcentaje
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="form-control input-sm" id="DCPorcenRetenIvaBien" disabled="" onchange="calcular_retencion_porc_bienes()">
                                                <option value="0">0</option>
                                            </select>
                                        </div>                            
                                        <div class="col-sm-4">
                                          <select class="form-control input-sm" id="DCPorcenRetenIvaServ" disabled="" onchange="calcular_retencion_porc_serv()">
                                                <option value="0">0</option>
                                            </select>
                                        </div>       
                                    </div>
                                    <div class="row">
                                         <div class="col-sm-4">
                                Valor RET
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="" class="form-control input-sm" id="TxtIvaBienValRet" value="0" readonly="">
                                        </div>                            
                                        <div class="col-sm-4">
                                           <input type="text" name="" class="form-control input-sm" id="TxtIvaSerValRet" value="0" readonly="">
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
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <b>tipo de comprobate</b>
                                            <select class="form-control input-sm" id="DCDctoModif">
                                                <option>Seleccione tipo de comprobante</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <b>Serie</b>
                                            <div class="row">
                                                <div class="col-sm-6" style="padding: 0px">
                                                    <input type="text" name="" class="form-control input-sm" id="TxtNumSerieUnoComp" placeholder="001" onblur="autocompletar_serie_num(this.id)" onkeyup="solo_3_numeros(this.id)">
                                                </div>
                                                <div class="col-sm-6" style="padding: 0px">
                                                    <input type="text" name="" class="form-control input-sm" id="TxtNumSerieDosComp" placeholder="001" onblur="autocompletar_serie_num(this.id)" onkeyup="solo_3_numeros(this.id)">
                                                </div>
                                            </div>                                
                                        </div>
                                        <div class="col-sm-1" style="padding-left: 5px;padding-right: 5px">
                                            <b>Numero</b>
                                            <input type="text" name="" class="form-control input-sm" id="CNumSerieTresComp" onkeyup="solo_9_numeros(this.id)" onblur="validar_num_factura(this.id)" placeholder="000000001">
                                        </div>
                                        <div class="col-sm-2" style="padding-left: 5px;padding-right: 5px">
                                            <b>Fecha</b>
                                            <input type="date" name="" class="form-control input-sm" id="MBFechaEmiComp">
                                        </div>
                                        <div class="col-sm-3" style="padding-right: 5px;">
                                            <b>Autorizacion sri</b>
                                            <input type="text" name="" class="form-control input-sm" id="TxtNumAutComp">
                                        </div>
                                    </div>
                                 </div>
                            </div>
                        </div>            
                    </div> 
               </div>
               <div class="tab-pane modal-body fade" id="menu1">
                  <div class="row">
                    <div class="col-sm-4">
                      <b>Forma de pago</b>
                      <select class="form-control input-sm" onchange="mostrar_panel_ext()" id="CFormaPago">
                        <option value="">Seleccione forma de pago</option>
                        <option value="1">Local</option>
                        <option value="2">Exterior</option>
                      </select>
                    </div>
                    <div class="col-sm-8">
                      <b>Tipo de pago</b>
                      <select class="form-control input-sm" id="DCTipoPago" onchange="$('#DCTipoPago').css('border','1px solid #d2d6de');">
                        <option value="">Seleccione tipo de pago</option>
                      </select>                    
                    </div>
                  </div>
                  <div class="row" id="panel_exterior" style="display: none;">
                    <div class="col-sm-4">
                      <b>Pais al que se efectua el pago</b>
                      <select class="form-control input-sm" id="DCPais">
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
                  <div class="row"><br>
                        <div class="col-sm-12">
                            <div class="box box-info">
                                <div class="box-header" style="padding:0px">
                                    <h3 class="box-title"><b>INGRESE LOS DATOS DE LA RETENCION_________________FORMULARIO 103</b></h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                          <label class="radio-inline" onclick="mostra_select()" id="lbl_rbl"><input type="checkbox" name="ChRetF" id="ChRetF"> Retenecion en la fuente</label>
                                        </div>
                                        <div class="col-sm-8">
                                          <select class="form-control input-sm" id="DCRetFuente" style="display: none;" onchange="$('#DCRetFuente').css('border','1px solid #d2d6de');">
                                            <option value=""> Seleccione Tipo de retencion</option>
                                          </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-sm-2">
                                        Serie
                                        <div class="row">
                                          <div class="col-sm-6"style="padding-left: 0px;padding-right: 0px;"><input type="text" class="form-control input-sm" name="TxtNumUnoComRet" id="TxtNumUnoComRet" onkeyup="solo_3_numeros(this.id)" placeholder="001" onblur="autocompletar_serie_num(this.id)"></div>
                                          <div class="col-sm-6"style="padding-left: 0px;padding-right: 0px;"><input type="text" class="form-control input-sm" name="TxtNumDosComRet" id="TxtNumDosComRet" onkeyup="solo_3_numeros(this.id)" placeholder="001" onblur="autocompletar_serie_num(this.id)"></div>
                                        </div>
                                      </div>
                                      <div class="col-sm-2">
                                        Numero
                                        <input type="text" class="form-control input-sm" name="TxtNumTresComRet" id="TxtNumTresComRet" onblur="validar_num_retencion()" onkeyup="solo_9_numeros(this.id)" placeholder="000000001">
                                      </div>
                                      <div class="col-sm-4">
                                        Autorizacion
                                        <input type="text" name="" class="form-control input-sm" id="TxtNumUnoAutComRet" >
                                      </div>
                                      <div class="col-sm-4">
                                        <div class="row">
                                          <div class="col-sm-4"><br>
                                            SUMATORIA
                                          </div>
                                          <div class="col-sm-8"><br>
                                            <input type="text" name="" class="form-control input-sm" id="TxtSumatoria">
                                          </div>
                                        </div>                                      
                                      </div>                          
                                    </div>
                                    <div class="row">
                                      <div class="col-sm-7">
                                        <b>CODIGO DE RETENCION</b>
                                        <select class="form-control input-sm" id="DCConceptoRet" name="DCConceptoRet" onchange="calcular_porc_ret()">
                                          <option value="">Seleccione Codigo de retencion</option>
                                        </select>                                        
                                      </div>
                                      <div class="col-sm-2">
                                        <b>BASE IMP</b>
                                        <input type="text" class="form-control input-sm" name="TxtBimpConA" id="TxtBimpConA">
                                      </div>
                                       <div class="col-sm-1" style="padding-left: 0px;padding-right: 0px">
                                        <b>PORC</b>
                                        <input type="text" class="form-control input-sm" name="TxtPorRetConA" id="TxtPorRetConA" onblur="insertar_grid()" readonly="">
                                      </div>
                                       <div class="col-sm-2">
                                        <b>VALOR RET</b>
                                        <input type="text" class="form-control input-sm" name="TxtValConA" id="TxtValConA" readonly="">
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>            
                  </div>
                  <div class="row">
                    <div class="table-responsive">
                      <table class="table table-striped table-hover">
                        <thead>
                          <th>CodRet</th>
                          <th>Detalle</th>
                          <th>BaseImp</th>
                          <th>Porcentaje</th>
                          <th>ValRet</th>
                          <th>EstabRetencion</th>
                          <th>PtoEmiRetencion</th>
                          <th>SecRetencion</th>
                          <th>AutoRetencion</th>
                          <th>FechaEmiRet</th>
                          <th>Cta_Retencion</th>
                          <th>EstabFactura</th>
                          <th>PuntoEmiFactura</th>
                          <th>Factura_No</th>
                          <th>IdProv</th>
                          <th>Item</th>
                          <th>codigoU</th>
                          <th>A_No</th>
                          <th>T_No</th>
                          <th>Tipo_Trans</th>
                        </thead>
                        <tbody id="tbl_retencion">
                          
                        </tbody>
                        
                      </table>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 text-right">
                      <b>Total Retencion</b>
                      <input type="text" class="input-sm" name="">
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
                         <input type="text"  class="form-control" name="" id="TxtNumConParPol">
                       </div>
                     </div>
                   </div>
                   <div class="col-sm-12">                     
                     <div class="row">
                       <div class="col-sm-8">
                         <b>MONTO TITULO ONEROSO</b>
                       </div>
                       <div class="col-sm-4">
                         <input type="text"  class="form-control" name="TxtMonTitOner" id="TxtMonTitOner">
                       </div>
                     </div>
                   </div>
                   <div class="col-sm-12">
                      <div class="row">
                       <div class="col-sm-8">
                         <b>MONTO DEL CONTRATO</b>
                       </div>
                       <div class="col-sm-4">
                         <input type="text"  class="form-control" name="TxtMonTitGrat" id="TxtMonTitGrat">
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
             </div>
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="myModal_comprobante" class="modal fade" role="dialog"  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- partial:index.partial.html -->

<!-- partial -->
<!-- //<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->

