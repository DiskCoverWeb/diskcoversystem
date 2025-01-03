<link rel="stylesheet" href="../../dist/css/arbol.css">
<script type="text/javascript">
	 $(document).ready(function () { 
	 		TVcatalogo();
	 		var h = (screen.height)-478;
    $('#tabla').css('height',h);

	 /*$('#txt_codigo').keyup(function(e){ 
			if(e.keyCode != 46 && e.keyCode !=8)
			{
				validar_cuenta_inv(this);
			}
		 })*/

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

	 function generarQR(){
		var codigo = $('#txt_codigo').val();

		if(!codigo.trim()){
			Swal.fire('No se puede generar el QR sin el codigo','','info');
			return;
		}

		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/catalogo_productosC.php?generarQR=true',
			data: {codigo}, 
			dataType:'json',
			success: function(data){
				if(data.res == 1){
					$('#codigo_qr').attr('src', data.qr);
				}
			}
		});
	 }

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
	 	var qr = $('#codigo_qr').attr('src');
  		$.ajax({
			  type: "POST",
			  url: '../controlador/facturacion/catalogo_productosC.php?eliminarINVBod=true',
			  data: {codigo,codigo,qr}, 
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
							//TVcatalogo(parseInt(padre_nl), padre);
							TVcatalogo(padre_nl,padre);
						}*/
						TVcatalogo(padre_nl,padre);
						$('#codigo_qr').attr('src', '');
						$('#txt_codigo').val('');
						$('#txt_nomenclatura').val('');
						$('#txt_concepto').val('');
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
	      url: '../controlador/facturacion/catalogo_productosC.php?TVcatalogo_Bodega=true',
	      data:{nivel:nivel,cod:cod},
        dataType:'json',
        beforeSend: function () {
            $('#hijos_'+che).html("<img src='../../img/gif/loader4.1.gif' style='width:20%' />");
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
	      url: '../controlador/facturacion/catalogo_productosC.php?LlenarInvBod=true',
	      data:{parametros,parametros},
        dataType:'json',
	      success: function(data)
	      {
			let qr = data['qr'];
	      	let detalle = data['detalle'][0];
	      	console.log(detalle);
			console.log(qr);
	      	$('#txt_concepto').val(detalle.Producto);
	      	$('#txt_nomenclatura').val(detalle.Nomenclatura);
	      	$('#txt_codigo').val(detalle.CodBod);
	      	$('#pvp').val(detalle.PVP);
	      	$('#pvp2').val(detalle.PVP_2);
	      	$('#pvp3').val(detalle.PVP_3);
	      	$('#maximo').val(detalle.Maximo);
	      	$('#minimo').val(detalle.Minimo);
			$('#codigo_qr').attr('src', qr);
	      	if(detalle.TC=='P'){ $('#cbx_final').prop('checked',true);}else{$('#cbx_inv').prop('checked',true);}
	      	// if(data.IVA==1){ $('#rbl_iva').prop('checked',true);}else{$('#rbl_iva').prop('checked',false);}
	      	if(detalle.INV==1){ $('#rbl_inv').prop('checked',true);}else{$('#rbl_inv').prop('checked',false);}
	      	/*if(data.Agrupacion==1){ $('#rbl_agrupacion').prop('checked',true);}else{$('#rbl_agrupacion').prop('checked',false);}
	      	if(data.Por_Reservas==1){ $('#rbl_reserva').prop('checked',true);}else{$('#rbl_reserva').prop('checked',false);}
	      	if(data.Div==1){ $('#cbx_dividir').prop('checked',true);}else{$('#cbx_multiplicar').prop('checked',true);}


	      	$('#cta_costo_venta').val(data.Cta_Costo_Venta);
	      	$('#cta_inventario').val(data.Cta_Inventario);
	      	$('#cta_venta').val(data.Cta_Ventas);
	      	$('#cta_venta_anterior').val(data.Cta_Ventas_Ant);
	      	$('#cta_tarifa_0').val(data.Cta_Ventas_0);

	      	$('#txt_unidad').val(data.Unidad);
	      	$('#txt_barras').val(data.Codigo_Barra);
	      	$('#txt_marca').val(data.Marca);
	      	$('#txt_reg_sanitario').val(data.Reg_Sanitario);
	      	$('#txt_ubicacion').val(data.Ubicacion);
	      	$('#txt_iess').val(data.Codigo_IESS);
	      	$('#txt_codres').val(data.Codigo_RES);
	      	$('#txt_utilidad').val(data.Utilidad);
	      	$('#txt_codbanco').val(data.Item_Banco);
	      	$('#txt_descripcion').val(data.Desc_Item);

	      	$('#txt_gramaje').val(data.Gramaje);
	      	$('#txt_posx').val(data.PX);
	      	$('#txt_posy').val(data.PY);
	      	$('#txt_formula').val(data.Ayuda);*/
          
	      }
	    });
   }


  function guardarINV()
  {
  	var datos = $('#form_datos').serialize();
  		$.ajax({
			  type: "POST",
			  url: '../controlador/facturacion/catalogo_productosC.php?guardarINVBod=true',
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

  function imprimirEtiqueta(){
	  let codigo = $('#txt_codigo').val();
	  let nomenclatura = $('#txt_nomenclatura').val();
	  let producto = $('#txt_concepto').val();
	  let qr = $('#codigo_qr').attr('src');
	
	  if(!(codigo && nomenclatura && producto && qr)){
		Swal.fire('Seleccione una bodega para poder imprimir.', '', 'info');
		return;
	  }
	
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/catalogo_productosC.php?imprimirEtiqueta=true',
		data: {codigo, nomenclatura, producto, qr}, 
		dataType:'json',
		success: function(data)
		{
			var url = '../../TEMP/' + data.pdf + '.pdf';
			window.open(url, '_blank');
		}
	})
  }

  function codigo_barras(cant=1)
  {
  	var codigo = $('#txt_codigo').val();
		var url= '../controlador/facturacion/catalogo_productosC.php?cod_barras=true&codigo='+codigo+'&cant='+cant;
  	window.open(url,'_blank');
  }

  function codigo_barras_grupo()
  {
  	var codigo = $('#txt_codigo').val();
		var url= '../controlador/facturacion/catalogo_productosC.php?cod_barras_grupo=true&codigo='+codigo;
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
	<div class="col-sm-12">
		<a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
          <img src="../../img/png/salire.png">
       </a>
	   <button class="btn btn-default"  data-toggle="tooltip" title="Grabar" onclick="guardarINV()">  <img src="../../img/png/grabar.png"></button>
	   <a title="IMPRIMIR ETIQUETA DE PRODUCTO" id="imprimir_etiqueta" class="btn btn-default" onclick="imprimirEtiqueta()">
			<img src="../../img/png/paper.png" height="32px">
		</a>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="box">
			<div class="box" id="tabla" style="overflow-y:auto">
				<ol class="tree" id="tree1">
				</ol>	
			</div>		
		</div>
	</div>
    <div class="col-sm-6">
		<form id="form_datos" name="form_datos">
			<div class="row" style="margin-bottom:5px;">
				<div class="col-sm-7">
					<b>Codigo del producto</b>
					<input type="hidden" name="txt_padre" id="txt_padre">
					<input type="hidden" name="txt_padre" id="txt_padre_nl">
					<input type="hidden" name="txt_anterior" id="txt_anterior">
					<input type="text" name="txt_codigo" id="txt_codigo" class="form-control input-xs" placeholder="<?php echo "CCC.CCC.CCCC.CCCCC.CCCC";/*$_SESSION['INGRESO']['Formato_Inventario'];*/ ?>" onblur="generarQR()">
				</div>
				<div class="col-sm-5">
					<b>Nomenclatura</b>
					<input type="text" name="txt_nomenclatura" id="txt_nomenclatura" class="form-control input-xs" >
				</div>
			</div>
			<div class="row" style="margin-bottom:5px;">
				<div class="col-sm-12">
					<b>Concepto o detalle del producto</b>
					<input type="text" name="txt_concepto" id="txt_concepto" class="form-control input-xs">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<b>Código QR</b>
					<div style="background-color: #a0a0a0;border-radius: 5px;">
						<div style="display:flex;justify-content:center;padding:5px;">
							<img id="codigo_qr" src="" style="min-width:200px;min-height:200px;max-height:200px;max-width:200px;object-fit:cover;"/>
						</div>
					</div>
				</div>
			</div>
		</form>
    	<!-- <button class="btn btn-default"  data-toggle="tooltip" title="Imprimir Grupo" onclick="codigo_barras_grupo();"><img src="../../img/png/impresora.png"></button><br> -->
    	<!-- <button class="btn btn-default"  data-toggle="tooltip" title="Imprimir" onclick="cantidad_codigo_barras();">  <img src="../../img/png/barcode.png"></button><br> -->
    	 
    </div>
</div>
<!--<form id="form_datos" name="form_datos">
<div class="row">
	<div class="col-sm-2">
		<b>Codigo del producto</b>
		<input type="hidden" name="txt_padre" id="txt_padre">
		<input type="hidden" name="txt_padre" id="txt_padre_nl">
		<input type="hidden" name="txt_anterior" id="txt_anterior">
		<input type="text" name="txt_codigo" id="txt_codigo" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Inventario']; ?>" >
	</div>
	<div class="col-sm-10">
		<b>Concepto o detalle del producto</b>
		<input type="text" name="txt_concepto" id="txt_concepto" class="form-control input-xs">
	</div>
</div>-->

<!-- <div class="row">
	<div class="col-sm-3">
		<div class="form-group">
          <label class="col-sm-6" style="padding:0px">CTA. INVENTARIO</label>
          <div class="col-sm-6" style="padding:0px">
            <input type="text" name="cta_inventario" id="cta_inventario" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>
	</div>
	<div class="col-sm-3" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-6" style="padding:1px">CTA. COSTO DE VENTA</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="cta_costo_venta" id="cta_costo_venta" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>
	</div>
	<div class="col-sm-3" style="padding:1px;">
		<div class="form-group">
          <label class="col-sm-5" style="padding:1px">CTA. DE VENTA</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="cta_venta" id="cta_venta" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>
	</div>
	<div class="col-sm-3" style="padding:1px;">
		<div class="form-group">
          <label class="col-sm-6" style="padding:1px">CTA. VENTA TARIFA 0%</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="cta_tarifa_0" id="cta_tarifa_0" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>
	</div>
</div> -->
<!-- <div class="row">
	<div class="col-sm-4">
		<div class="form-group">
          <label class="col-sm-7" style="padding:1px">CTA. DE VENTA AÑO ANTERIOR</label>
          <div class="col-sm-5" style="padding:1px">
            <input type="text" name="cta_venta_anterior" id="cta_venta_anterior" class="form-control input-xs" placeholder="<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" >
          </div>
        </div>
	</div>
	<div class="col-sm-2"  style="padding:1px">
		<div class="form-group">
          <label class="col-sm-5" style="padding:1px">UNIDAD: U:</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="txt_unidad" id="txt_unidad" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-1" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-4" style="padding:1px">P.V.P</label>
          <div class="col-sm-8" style="padding:1px">
            <input type="text" name="pvp" id="pvp" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-1" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-5" style="padding:1px">P.V.P2</label>
          <div class="col-sm-7" style="padding:3px">
            <input type="text" name="pvp2" id="pvp2" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-1" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-5" style="padding:1px">P.V.P3</label>
          <div class="col-sm-7" style="padding:3px">
            <input type="text" name="pvp3" id="pvp3" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-2" style="padding:1px">
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
</div> -->
<!-- <div class="row">
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
</div> -->
<!-- <div class="row">
	<div class="col-sm-3">
		<div class="form-group">
          <label class="col-sm-3" style="padding:1px">UBICACION</label>
          <div class="col-sm-9">
            <input type="text" name="txt_ubicacion" id="txt_ubicacion" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-3" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-4" style="padding:1px">COD. I.E.S.S</label>
          <div class="col-sm-8" style="padding:1px">
            <input type="text" name="txt_iess" id="txt_iess" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
          <label class="col-sm-3" style="padding:1px">COD RES</label>
          <div class="col-sm-9" style="padding:1px">
            <input type="text" name="txt_codres" id="txt_codres" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-3" style="padding-left:1px">
		<div class="form-group">
          <label class="col-sm-4" style="padding:1px">UTILIDAD %</label>
          <div class="col-sm-8" style="padding:1px">
            <input type="text" name="txt_utilidad" id="txt_utilidad" class="form-control input-xs">
          </div>
        </div>
	</div>
</div> -->
<!-- <div class="row">
	<div class="col-sm-4">
		<div class="form-group">
          <label class="col-sm-6" style="padding:1px">Codigo item del banco</label>
          <div class="col-sm-6">
            <input type="text" name="txt_codbanco" id="txt_codbanco" class="form-control input-xs">
          </div>
        </div>
	</div>

	<div class="col-sm-3" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-3" style="padding:1px">Descripcion</label>
          <div class="col-sm-9">
            <input type="text" name="txt_descripcion" id="txt_descripcion" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-2"  style="padding:1px">
		<div class="form-group">
          <label class="col-sm-6" style="padding:1px">Gramaje</label>
          <div class="col-sm-6">
            <input type="text" name="txt_gramaje" id="txt_gramaje" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-1" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-6" style="padding:1px">POS. X</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="txt_posx" id="txt_posx" class="form-control input-xs">
          </div>
        </div>
	</div>
	<div class="col-sm-1" style="padding:1px">
		<div class="form-group">
          <label class="col-sm-6" style="padding:1px">POS. Y</label>
          <div class="col-sm-6" style="padding:1px">
            <input type="text" name="txt_posy" id="txt_posy" class="form-control input-xs">
          </div>
        </div>
	</div>
</div> -->
<!-- <div class="row">
	<div class="col-sm-12">
		<div class="form-group">
          <label class="col-sm-3" style="padding:1px">FORMULA FARMACEUTICA (AYUA)</label>
          <div class="col-sm-9">
            <input type="text" name="txt_formula" id="txt_formula" class="form-control input-xs">
          </div>
        </div>
	</div>
</div> -->
<!--</div>
</form>-->