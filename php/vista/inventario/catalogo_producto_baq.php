<link rel="stylesheet" href="../../dist/css/arbol.css">
<script type="text/javascript">
	 $(document).ready(function () { 
	 		TVcatalogo();
	 		var h = (screen.height)-478;
    $('#tabla').css('height',h);

	 $('#txt_codigo').keyup(function(e){ 
			if(e.keyCode != 46 && e.keyCode !=8)
			{
				validar_cuenta_inv(this);
			}
		 })

	 $('#cta_inventario').keyup(function(e){ 
			if(e.keyCode != 46 && e.keyCode !=8)
			{
				validar_cuenta(this);
			}
		 })
	 $('#cta_costo_venta').keyup(function(e){ 
			if(e.keyCode != 46 && e.keyCode !=8)
			{
				validar_cuenta(this);
			}
		 })
	 $('#cta_venta').keyup(function(e){ 
			if(e.keyCode != 46 && e.keyCode !=8)
			{
				validar_cuenta(this);
			}
		 })
	 $('#cta_tarifa_0').keyup(function(e){ 
			if(e.keyCode != 46 && e.keyCode !=8)
			{
				validar_cuenta(this);
			}
		 })
	 $('#cta_venta_anterior').keyup(function(e){ 
			if(e.keyCode != 46 && e.keyCode !=8)
			{
				validar_cuenta(this);
			}
		 })
	 })
   $(document).keyup(function(e){ 
   	// console.log(e);   	
   	// console.log(document.activeElement);
   	var ele = document.activeElement.tagName;   
		if((e.keyCode==46 && e.target.type=='checkbox') || (e.keyCode==46 && ele=='A'))
		{
			eliminar();
		}
	 })

	 function eliminar()
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
        	 delete_cuenta();
        }
      })
	 }

	 function delete_cuenta()
	 {
	 	var codigo = $('#txt_codigo').val();
  		$.ajax({
			  type: "POST",
			  url: '../controlador/inventario/catalogo_productos_baqC.php?eliminarINV=true',
			  data: {codigo,codigo}, 
			  dataType:'json',
			  success: function(data)
			  {
			  	if(data==1)
			  	{
				  	var padre_nl = $('#txt_padre_nl').val();
				  	var padre = $('#txt_padre').val();
				  	Swal.fire('Eliminado','','success').then(function(){ 
				  		var cod = $('#txt_codigo').val();
			  				var cod = cod.split('.');
			  				/*if(padre!=cod[0] && cod.length==2)
			  				{
			  					TVcatalogo(padre_nl,padre);
							}else
							{
								TVcatalogo();
							}*/
							TVcatalogo(padre_nl,padre);
				  	});
			    }else
			    {
			    	Swal.fire('No se puede eliminar','','error');
			    }
			  }
			})
	 }

	 function TVcatalogo(nl='',cod=false)
	 {

	 	//pinta el seleccionado
	 	if(cod)
    {
	 	var ant = $('#txt_anterior').val();
	 	var che = cod.split('.').join('_');	
	 	if(ant==''){	$('#txt_anterior').val(che); }else{	$('#label_'+ant).css('border','0px');}
	 	$('#label_'+che).css('border','1px solid');
	 	$('#txt_anterior').val(che); 
	  }
	 	//fin de pinta el seleccionado
    if(cod)
    {
      $('#txt_codigo').val(cod);
      $('#txt_padre_nl').val(nl);
      $('#txt_padre').val(cod);
      LlenarInv(cod);
      var che = cod.split('.').join('_');
      if($('#'+che).prop('checked')==false){ return false;}
    }

    $('#txt_padre_nl').val(nl);
    var nivel = nl;
        $.ajax({
	      type: "POST",
	      url: '../controlador/inventario/catalogo_productos_baqC.php?TVcatalogo=true',
	      data:{nivel,nivel,cod:cod},
        dataType:'json',
        beforeSend: function () {
			if(nl){
            	$('#hijos_'+che).html("<img src='../../img/gif/loader4.1.gif' style='width:20%' />");
			}else{
				$('#tree1').html("<img src='../../img/gif/loader4.1.gif' style='width:80%' />");
			}
        },
	      success: function(data)
	      {
          if(nivel=='')
          {
            $('#tree1').html(data);
          }else
          {
            cod = cod.split('.').join('_');
            // cod = cod.replace(//g,'_');
            console.log(cod);
            $('#hijos_'+cod).html(data);
            // if('hijos_01_01'=='hijos_'+cod)
            // {
            //   $('#hijos_'+cod).html('<li>hola</li>');
            // }
            // $('#hijos_'+cod).html('hola');
          }	        
	      }
	    });
	 }

   function detalle(nl,cod)
   {
   		
	 	//pinta el seleccionado
	 	if(cod)
    {
	 	var ant = $('#txt_anterior').val();
	 	var che = cod.split('.').join('_');	
	 	if(ant==''){	$('#txt_anterior').val(che); }else{	$('#label_'+ant).css('border','0px');}
	 	$('#label_'+che).css('border','1px solid');
	 	$('#txt_anterior').val(che); 
	  }
	 	//fin de pinta el seleccionado


     	$('#txt_codigo').val(cod);
      $('#txt_padre_nl').val(nl-1);
      var pa = cod.split('.');

      var padre = '';
      for (var i = 0; i < nl-2; i++) {
      		padre+= pa[i]+'.';
      }


      // console.log(padre);
      // console.log(cod);
      padre2 = padre.substr(-1*padre.length,padre.length-1);
      // console.log(padre2);

      $('#txt_padre').val(padre2);
      LlenarInv(cod);

   }

   function LlenarInv(cod)
   {
   	var parametros = 
   	{
   		'codigo':cod,
   	}
   		$.ajax({
	      type: "POST",
	      url: '../controlador/inventario/catalogo_productos_baqC.php?LlenarInv=true',
	      data:{parametros,parametros},
        dataType:'json',
	      success: function(data)
	      {
	      	data = data[0];
	      	console.log(data);
	      	$('#txt_concepto').val(data.Producto);
	      	$('#txt_codigo').val(data.Codigo_Inv);
	      	$('#pvp').val(data.PVP);
	      	$('#pvp2').val(data.PVP_2);
	      	$('#pvp3').val(data.PVP_3);
	      	$('#maximo').val(data.Maximo);
	      	$('#minimo').val(data.Minimo);
	      	if(data.TC=='P'){ $('#cbx_final').prop('checked',true);}else{$('#cbx_inv').prop('checked',true);}
	      	if(data.IVA==1){ $('#rbl_iva').prop('checked',true);}else{$('#rbl_iva').prop('checked',false);}
	      	if(data.INV==1){ $('#rbl_inv').prop('checked',true);}else{$('#rbl_inv').prop('checked',false);}
	      	//if(data.Agrupacion==1){ $('#rbl_agrupacion').prop('checked',true);}else{$('#rbl_agrupacion').prop('checked',false);}
	      	//if(data.Por_Reservas==1){ $('#rbl_reserva').prop('checked',true);}else{$('#rbl_reserva').prop('checked',false);}
	      	//if(data.Div==1){ $('#cbx_dividir').prop('checked',true);}else{$('#cbx_multiplicar').prop('checked',true);}


	      	$('#cta_costo_venta').val(data.Cta_Costo_Venta);
	      	$('#cta_inventario').val(data.Cta_Inventario);
	      	$('#cta_venta').val(data.Cta_Ventas);
	      	$('#cta_venta_anterior').val(data.Cta_Ventas_Ant);
	      	$('#cta_tarifa_0').val(data.Cta_Ventas_0);

	      	$('#txt_unidad').val(data.Unidad);
	      	//$('#txt_barras').val(data.Codigo_Barra);
	      	//$('#txt_marca').val(data.Marca);
	      	//$('#txt_reg_sanitario').val(data.Reg_Sanitario);
	      	$('#txt_ubicacion').val(data.Ubicacion);
	      	//$('#txt_iess').val(data.Codigo_IESS);
	      	//$('#txt_codres').val(data.Codigo_RES);
	      	$('#txt_utilidad').val(data.Utilidad);
	      	$('#txt_codbanco').val(data.Item_Banco);
	      	$('#txt_descripcion').val(data.Desc_Item);

	      	//$('#txt_gramaje').val(data.Gramaje);
	      	//$('#txt_posx').val(data.PX);
	      	//$('#txt_posy').val(data.PY);
	      	$('#txt_formula').val(data.Ayuda);
          
	      }
	    });
   }


  function guardarINV()
  {
  	var datos = $('#form_datos').serialize();
  		$.ajax({
			  type: "POST",
			  url: '../controlador/inventario/catalogo_productos_baqC.php?guardarINV=true',
			  data: datos, 
			  dataType:'json',
			  success: function(data)
			  {
			  	if(data==1)
			  	{
			  		var padre_nl = $('#txt_padre_nl').val();
			  		var padre = $('#txt_padre').val();
			  		Swal.fire('Guardado correctamente','','success').then(function()
			  			{ 
			  				console.log(padre_nl);
			  				console.log(padre);
			  				var cod = $('#txt_codigo').val();
			  				var cod = cod.split('.');
			  				/*if(padre==cod[0])
			  				{
			  					TVcatalogo(padre_nl,padre);
			  				}else
			  				{
			  					TVcatalogo();
			  				}*/
							  TVcatalogo(padre_nl,padre);
			  			});
			  	}
			  	console.log(data);
			  }
			})
  }

  function codigo_barras(cant=1)
  {
  	var codigo = $('#txt_codigo').val();
		var url= '../controlador/inventario/catalogo_productos_baqC.php?cod_barras=true&codigo='+codigo+'&cant='+cant;
  	window.open(url,'_blank');
  }

  function codigo_barras_grupo()
  {
  	var codigo = $('#txt_codigo').val();
		var url= '../controlador/inventario/catalogo_productos_baqC.php?cod_barras_grupo=true&codigo='+codigo;
  	window.open(url,'_blank');
  }

  function cantidad_codigo_barras()
  {
  	 Swal.fire({
      title: 'Cantidad de etiquetas',
		  input: 'text',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Generar'
    }).then((result) => {
    	// console.log(result);
        if (result.value) {
        	codigo_barras(result.value);
        }
    })
  }   
</script>
<div class="row">
	<div class="col-sm-5">
		<div>
			<div class="box">
				<div class="box" id="tabla" style="overflow-y:auto">
					<ol class="tree" id="tree1">
					</ol>	
				</div>		
			</div>
		</div>
		<div>
			<button class="btn btn-default"  data-toggle="tooltip" title="Grabar" onclick="guardarINV()">  <img src="../../img/png/grabar.png"></button>
			<button class="btn btn-default"  data-toggle="tooltip" title="Imprimir Grupo" onclick="codigo_barras_grupo();"><img src="../../img/png/impresora.png"></button>
			<button class="btn btn-default"  data-toggle="tooltip" title="Imprimir" onclick="cantidad_codigo_barras();//codigo_barras()">  <img src="../../img/png/barcode.png"></button>
			<a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
				<img src="../../img/png/salire.png">
			</a>
		</div>
	</div>
    <div class="col-sm-7">
		<form id="form_datos" name="form_datos">
			<div class="row">
				<div class="col-sm-4">
					<b>Codigo del producto</b>
					<input type="hidden" name="txt_padre" id="txt_padre">
					<input type="hidden" name="txt_padre" id="txt_padre_nl" value="">
					<input type="hidden" name="txt_anterior" id="txt_anterior">
					<input type="text" name="txt_codigo" id="txt_codigo" class="form-control input-xs" placeholder="CC.CC.CCC.CCCCCC">
				</div>
				<div class="col-sm-8">
					<b>Concepto o detalle del producto</b>
					<input type="text" name="txt_concepto" id="txt_concepto" class="form-control input-xs">
				</div>
			</div>
			<div class="row" style="margin-top: 5px;">
				<div class="col-sm-12">		
					<i>Tipo de Producto</i>
					<div class="row">
						<div class="col-sm-3" style="padding-right:0px">
							<label>Tipo de Inventario <input type="radio" name="cbx_tipo" id="cbx_inv" value="I"></label>
						</div>
						<div class="col-sm-3">
							<label>Producto final <input type="radio" name="cbx_tipo" id="cbx_final" value="P"></label>
						</div>
						<div class="col-sm-3">
							<label><input type="checkbox" name="rbl_iva" id="rbl_iva"> Factura con IVA</label>
						</div>
						<div class="col-sm-3" style="padding-right:0px">
							<label><input type="checkbox" name="rbl_inv" id="rbl_inv"> Producto para facturar</label>
						</div>
						<!--<div class="col-sm-1" style="padding:0px;display:none;">
							<label><input type="checkbox" name="rbl_agrupacion" id="rbl_agrupacion"> Agrupar</label>
						</div>
						<div class="col-sm-2" style="display:none;">
							<label><input type="checkbox" name="rbl_reserva" id="rbl_reserva"> Por Reservas</label>
						</div>-->
					</div>
				</div>
				<!--<div class="col-sm-2" style="display:none;">
					<i>Calcular Divisas</i>
					<div class="row">
						<div class="col-sm-5" style="padding: 0px;">
							<label>Dividir <input type="radio" name="cbx_calcular" id="cbx_dividir" value="div"></label>
						</div>
						<div class="col-sm-7" style="padding: 0px;">
							<label>Multiplicar <input type="radio" name="cbx_calcular" id="cbx_multiplicar" value="mul"></label>
						</div>
					</div>
				</div>-->
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
					<label class="col-sm-6" style="padding:0px">CTA. INVENTARIO</label>
					<div class="col-sm-6" style="padding:0px">
						<input type="text" name="cta_inventario" id="cta_inventario" class="form-control input-xs" placeholder="C.C.CC.CC.CC.CCC">
					</div>
					</div>
				</div>
				<div class="col-sm-6" style="padding:1px">
					<div class="form-group">
					<label class="col-sm-6" style="padding:1px">CTA. COSTO DE VENTA</label>
					<div class="col-sm-6" style="padding:1px">
						<input type="text" name="cta_costo_venta" id="cta_costo_venta" class="form-control input-xs" placeholder="C.C.CC.CC.CC.CCC">
					</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
					<label class="col-sm-5" style="padding:1px">CTA. DE VENTA</label>
					<div class="col-sm-6" style="padding:1px">
						<input type="text" name="cta_venta" id="cta_venta" class="form-control input-xs" placeholder="C.C.CC.CC.CC.CCC">
					</div>
					</div>
				</div>
				<div class="col-sm-6" style="padding:1px;">
					<div class="form-group">
					<label class="col-sm-6" style="padding:1px">CTA. VENTA TARIFA 0%</label>
					<div class="col-sm-6" style="padding:1px">
						<input type="text" name="cta_tarifa_0" id="cta_tarifa_0" class="form-control input-xs" placeholder="C.C.CC.CC.CC.CCC">
					</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-8">
					<div class="form-group">
					<label class="col-sm-6" style="padding:1px;width:fit-content;">CTA. DE VENTA AÑO ANTERIOR</label>
					<div class="col-sm-6" style="padding:1px; padding-left:5px;">
						<input type="text" name="cta_venta_anterior" id="cta_venta_anterior" class="form-control input-xs" placeholder="C.C.CC.CC.CC.CCC">
					</div>
					</div>
				</div>
				<div class="col-sm-4" style="padding:1px">
					<div class="form-group">
					<label class="col-sm-5" style="padding:1px;width:fit-content;">UNIDAD: U:</label>
					<div class="col-sm-6" style="padding:1px; padding-left:5px;">
						<input type="text" name="txt_unidad" id="txt_unidad" class="form-control input-xs">
					</div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
					<label class="col-sm-4" style="padding:1px">P.V.P</label>
					<div class="col-sm-8" style="padding:1px">
						<input type="text" name="pvp" id="pvp" class="form-control input-xs">
					</div>
					</div>
				</div>
				<div class="col-sm-2" style="padding:1px">
					<div class="form-group">
					<label class="col-sm-5" style="padding:1px">P.V.P2</label>
					<div class="col-sm-7" style="padding:3px">
						<input type="text" name="pvp2" id="pvp2" class="form-control input-xs">
					</div>
					</div>
				</div>
				<div class="col-sm-2" style="padding:1px">
					<div class="form-group">
					<label class="col-sm-5" style="padding:1px">P.V.P3</label>
					<div class="col-sm-7" style="padding:3px">
						<input type="text" name="pvp3" id="pvp3" class="form-control input-xs">
					</div>
					</div>
				</div>
				<div class="col-sm-offset-1 col-sm-5" style="padding:1px">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
							<label class="col-sm-6" style="padding:1px">MINIMO</label>
							<div class="col-sm-6" style="padding:3px">
								<input type="text" name="minimo" id="minimo" class="form-control input-xs">
							</div>
							</div>				
						</div>
						<div class="col-sm-6">
							<div class="form-group">
							<label class="col-sm-6" style="padding:1px">MAXIMO</label>
							<div class="col-sm-6" style="padding:3px">
								<input type="text" name="maximo" id="maximo" class="form-control input-xs">
							</div>
							</div>				
						</div>
					</div>
				</div>	
			</div>
			<!--<div class="row" style="display:none;">
				<div class="col-sm-4">
					<div class="form-group">
					<label class="col-sm-5" style="padding:1px">CODIGO DE BARRAS</label>
					<div class="col-sm-7">
						<input type="text" name="txt_barras" id="txt_barras" class="form-control input-xs">
					</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
					<label class="col-sm-2" style="padding:1px">MARCA</label>
					<div class="col-sm-10">
						<input type="text" name="txt_marca" id="txt_marca" class="form-control input-xs">
					</div>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="form-group">
					<label class="col-sm-4" style="padding:1px">REGISTRO SANITARIO</label>
					<div class="col-sm-8">
						<input type="text" name="txt_reg_sanitario" id="txt_reg_sanitario" class="form-control input-xs">
					</div>
					</div>
				</div>
			</div>-->
			<div class="row" style="margin-bottom:5px">
				<div class="col-sm-7">
					<div class="form-group">
					<label class="col-sm-3" style="padding:1px">UBICACION</label>
					<div class="col-sm-9">
						<input type="text" name="txt_ubicacion" id="txt_ubicacion" class="form-control input-xs">
					</div>
					</div>
				</div>
				<!--<div class="col-sm-3" style="padding:1px;display:none;">
					<div class="form-group">
					<label class="col-sm-4" style="padding:1px">COD. I.E.S.S</label>
					<div class="col-sm-8" style="padding:1px">
						<input type="text" name="txt_iess" id="txt_iess" class="form-control input-xs">
					</div>
					</div>
				</div>
				<div class="col-sm-3" style="display:none;">
					<div class="form-group">
					<label class="col-sm-3" style="padding:1px">COD RES</label>
					<div class="col-sm-9" style="padding:1px">
						<input type="text" name="txt_codres" id="txt_codres" class="form-control input-xs">
					</div>
					</div>
				</div>-->
				<div class="col-sm-5" style="padding-left:1px;">
					<div class="form-group">
					<label class="col-sm-4" style="padding:1px">UTILIDAD %</label>
					<div class="col-sm-8" style="padding:1px">
						<input type="text" name="txt_utilidad" id="txt_utilidad" class="form-control input-xs">
					</div>
					</div>
				</div>
			</div>
			<div class="row">
				<!--
					<div class="col-sm-6">
						<b>Concepto o detalle del producto</b>
						<input type="text" name="txt_concepto" id="txt_concepto" class="form-control input-xs">
					</div>
				-->
				<div class="col-sm-6">
					<!--<div class="form-group">
					<label class="col-sm-6" style="padding:1px">Abreviatura del producto</label>
					<div class="col-sm-6">
						<input type="text" name="txt_codbanco" id="txt_codbanco" class="form-control input-xs">
					</div>
					</div>-->
					<b>Abreviatura del producto</b>
					<input type="text" name="txt_codbanco" id="txt_codbanco" class="form-control input-xs">
				</div>

				<div class="col-sm-6" style="padding:1px">
					<!--<div class="form-group">
					<label class="col-sm-3" style="padding:1px">Categoria de GFN</label>
					<div class="col-sm-9">
						<input type="text" name="txt_descripcion" id="txt_descripcion" class="form-control input-xs">
					</div>
					</div>-->
					<b>Categoria de GFN</b>
					<input type="text" name="txt_descripcion" id="txt_descripcion" class="form-control input-xs">
				</div>
				<!--<div class="col-sm-2" style="padding:1px;display:none;">
					<div class="form-group">
					<label class="col-sm-6" style="padding:1px">Gramaje</label>
					<div class="col-sm-6">
						<input type="text" name="txt_gramaje" id="txt_gramaje" class="form-control input-xs">
					</div>
					</div>
				</div>
				<div class="col-sm-1" style="padding:1px;display:none;">
					<div class="form-group">
					<label class="col-sm-6" style="padding:1px">POS. X</label>
					<div class="col-sm-6" style="padding:1px">
						<input type="text" name="txt_posx" id="txt_posx" class="form-control input-xs">
					</div>
					</div>
				</div>
				<div class="col-sm-1" style="padding:1px;display:none;">
					<div class="form-group">
					<label class="col-sm-6" style="padding:1px">POS. Y</label>
					<div class="col-sm-6" style="padding:1px">
						<input type="text" name="txt_posy" id="txt_posy" class="form-control input-xs">
					</div>
					</div>
				</div>-->
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
					<label class="col-sm-3" style="padding:1px">Indicador nutricional</label>
					<div class="col-sm-9">
						<input type="text" name="txt_formula" id="txt_formula" class="form-control input-xs">
					</div>
					</div>
				</div>
			</div>
		</form>
    </div>
</div>