<script type="text/javascript">
	$(document).ready(function(){
		contratistas()
		orden();
		meses();
		semanas()
  	});

  	function NumeroSemanasxAnio(year) {
	    const firstDayOfYear = new Date(year, 0, 1);
	    const dayOfWeek = firstDayOfYear.getDay();
	    // Verificar si el año es bisiesto
	    const isLeapYear = (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
	    if (dayOfWeek === 4 || (isLeapYear && dayOfWeek === 3)) {
	        return 53;
	    } else {
	        return 52;
	    }
	}

  	function meses()
  	{
  		
  		response = [
  			{'num':'01','mes':'Enero'},
  			{'num':'02','mes':'Febrero'},
  			{'num':'03','mes':'Marzo'},
  			{'num':'04','mes':'Abril'},
  			{'num':'05','mes':'Mayo'},
  			{'num':'06','mes':'Junio'},
  			{'num':'07','mes':'Julio'},
  			{'num':'08','mes':'Agosto'},
  			{'num':'09','mes':'Septiembre'},
  			{'num':'10','mes':'Octubre'},
  			{'num':'11','mes':'Noviembre'},
  			{'num':'12','mes':'diciembre'},
  		]
       		response.forEach(function(item,i)
       		{
       			$('#ddl_meses').append('<option value="'+item.num+'">'+item.mes+'</option>');
       		})
     
  	}

  	function semanas()
  	{
  		const year = new Date().getFullYear();
  		semanas = NumeroSemanasxAnio(year)
  		for (var i = 1; i <= semanas; i++) {
  			$('#ddl_semanas').append('<option value="'+i+'">'+i+'</option>')
  		}

  	}

  	function cargar_datos()
  	{
  		var parametros = 
  		{
  			'contratista':$('#ddl_contratista').val(),
  			'orden':$('#ddl_orden').val(),
  			'mes':$('#ddl_meses').val(),
  			'semanas':$('#ddl_semanas').val(),
  			'fecha':$('#txt_fecha').val(),
  		}
  		$.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/reporte_constructora_Compras.php?cargar_datos=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) {
        	table = '';
        response.forEach(function(item,i){
        	compra = parseFloat(item.valor_compra);
        	refere = parseFloat(item.valor_ref);
        	ahorro = refere-compra;
        	color = 'chartreuse';
        	if(ahorro<0)
        	{
        		color = 'coral';
        	}

        	table+=`<tr>
			        			<td><a href="#" onclick='abrir_detalle("`+item.Orden_No+`","`+item.Codigo_P+`")'>`+item.Orden_No+`</a></td>
			        			<td>`+item.Cliente+`</td>
			        			<td>`+item.Fecha.date+`</td>
			        			<td>`+refere.toFixed(3)+`</td>
			        			<td>`+compra.toFixed(3)+`</td>
			        			<td style="background: `+color+`;">`+ahorro.toFixed(3)+`</td>
			        	</tr>`
        })        	
        	$('#tbl_body').html(table)
  		}
      });
  	}

  	function contratistas()
  	{
  		$('#ddl_contratista').select2({
	      placeholder: 'Seleccione contratista',
	      width:'resolve',
	      ajax: {
	        url:   '../controlador/inventario/reporte_constructora_Compras.php?contratistas=true',
	        dataType: 'json',
	        delay: 250,
	        processResults: function (data) {
	          return {
	            results: data
	          };
	        },
	        cache: true
	      }
	    });

  	}

  	function orden()
  	{
  		let contra = $('#ddl_contratista').val() || ''
  		$('#ddl_orden').select2({
	      placeholder: 'Seleccione orden',
	      ajax: {
	        url:   '../controlador/inventario/reporte_constructora_Compras.php?ddl_orden=true&contratista='+contra,
	        dataType: 'json',
	        delay: 250,
	        processResults: function (data) {
	          return {
	            results: data
	          };
	        },
	        cache: true
	      }
	    });

  	}

  	function limpiar_contra()
  	{
  		$('#ddl_contratista').empty()
  		orden();
  	}
  	function limpiar_orden()
  	{
  		$('#ddl_orden').empty();
  		cargar_datos();
  	}

  	function abrir_detalle(orden,proveedor)
  	{
  		cargar_detalle(orden,proveedor)
  		$('#myModal_detalle').modal('show');
  	}

  	function cargar_detalle(orden,proveedor)
  	{
  		var parametros = 
  		{
  			'proveedor':proveedor,
  			'orden':orden,
  		}
  		$.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/reporte_constructora_Compras.php?cargar_detalles=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) {
        	console.log(response);
        	tr='';
        	ahorro_t = 0;
        	ref_t = 0;
        	compra_t = 0;

        	response.forEach(function(item,i){
        		compra = parseFloat(item.Valor_Total);
	        	refere = parseFloat(item.Costo)*parseFloat(item.Entrada);
	        	ahorro = refere-compra;
	        	color = 'chartreuse';
	        	if(ahorro<0)
	        	{
	        		color = 'coral';
	        	}

        	 tr+=`<tr>
	        	 			<td>`+(i+1)+`</td>
	      					<td>`+item.familia+`</td>
	      					<td>`+item.Codigo_Inv+`</td>
	      					<td>`+item.Cliente+`</td>
	      					<td>`+item.Marca+`</td>
	      					<td>`+item.Entrada+`</td>
	      					<td>`+item.Costo+`</td>
	      					<td>`+refere+`</td>
	      					<td>`+item.Valor_Unitario+`</td>
	      					<td>`+item.Valor_Total+`</td>	      					
	      					<td style="background:`+color+`" >`+ahorro.toFixed(3)+`</td>
	      				</tr>`;
	      				ahorro_t = ahorro_t+ahorro
	      				ref_t = ref_t+refere
								compra_t = compra_t+compra
        	})

        	color = 'chartreuse';
        	if(ahorro_t<0)
        	{
        		color = 'coral';
        	}

        	$('#lbl_comprobante').text(response[0].Numero);
					$('#lbl_referencial').text(ref_t.toFixed(3));
					$('#lbl_compra').text(compra_t.toFixed(3));
					$('#lbl_ahorro').text(ahorro_t.toFixed(3));					
					$('#lbl_ahorro').css('background',color);
					$('#lbl_proveedor').text(response[0].Cliente);       

        	$('#tbl_detalle').html(tr)
  			}
      });
  	}


  	function imprimir_pdf()
  	{  		
  		var url = '../controlador/inventario/reporte_constructora_Compras.php?cargar_detalles_pdf=true&contratista='+$('#ddl_contratista').val()+'&orden='+$('#ddl_orden').val()+'&mes='+$('#ddl_meses').val()+'&semanas='+$('#ddl_semanas').val()+'&fecha='+$('#txt_fecha').val()                 
      window.open(url, '_blank');
  	}
  	function imprimir_excel()
  	{  		
  		var url = '../controlador/inventario/reporte_constructora_Compras.php?cargar_detalles_excel=true&contratista='+$('#ddl_contratista').val()+'&orden='+$('#ddl_orden').val()+'&mes='+$('#ddl_meses').val()+'&semanas='+$('#ddl_semanas').val()+'&fecha='+$('#txt_fecha').val()                 
      window.open(url, '_blank');
  	}
	
</script>
 <div class="row">
      <div class="col-lg-4 col-sm-4 col-md-8 col-xs-12">
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <a  href="./inventario.php?mod=Inventario#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
          <button type="button" class="btn btn-default" id="imprimir_pdf" title="Descargar PDF" onclick="imprimir_pdf()">
            <img src="../../img/png/pdf.png">
          </button>           
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
          <button type="button" class="btn btn-default" id="imprimir_excel" title="Descargar Excel" onclick="imprimir_excel()">
            <img src="../../img/png/table_excel.png">
          </button>         
        </div>
        
      </div>      
</div>
<div class="row mb-2">
	<div class="col-sm-4">
		<b>Contratista</b>
		<div class="input-group input-group-sm">
			<select class="form-control" id="ddl_contratista" onchange="cargar_datos();orden()">
				<option value="">Selecciones</option>
			</select>
			<span class="input-group-btn">
					<button class="btn btn-danger btn-flat p-0" style="height: 18pt;padding-top: 3px;" onclick="limpiar_contra()"><i class="fa fa-close"></i></button>
			</span>
		</div>
	</div>
	<div class="col-sm-3">
		<b>Orden</b>
		<div class="input-group input-group-sm">
			<select class="form-control input-sm" id="ddl_orden" onchange="cargar_datos()">
				<option value="">Selecciones</option>
			</select>
			<span class="input-group-btn">
					<button class="btn btn-danger btn-flat p-0" style="height: 18pt;padding-top: 3px;" onclick="limpiar_orden()"><i class="fa fa-close"></i></button>
			</span>
		</div>
	</div>
	<div class="col-sm-2">
		<b>Meses</b>
		<select class="form-control input-sm" id="ddl_meses" onchange=" cargar_datos()">
			<option value="">Selecciones</option>
		</select>
	</div>
	<div class="col-sm-1">
		<b>Semana</b><select class="form-control input-sm" id="ddl_semanas" onchange=" cargar_datos()">
			<option value="">Selecciones</option>
		</select>
	</div>
	<div class="col-sm-2">
		<b>Fecha</b>
		<input type="date" name="txt_fecha" id="txt_fecha" class="form-control input-sm" onblur="cargar_datos()">
	</div>
	
</div>

<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm">
			<thead>
				<th>#ORDEN</th>
				<th>SOLICITANTE</th>
				<th>FECHA SOLICITUD</th>
				<th>VALOR REF</th>
				<th>VALOR COMPRA</th>
				<th>AHORRO</th>
				<tbody id="tbl_body">
					
				</tbody>

			</thead>
		</table>		
	</div>
	<div class="col-sm-12" id="pnl_tablas">
			
	</div>	
</div>


  <div id="myModal_detalle" class="modal fade myModalNuevoCliente" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detalle de orden</h4>
            </div>
            <div class="modal-body">
            	<div class="row">
            		<div class="col-sm-12">
            			<div class="row">
            				<div class="col-sm-12">
            					<b># COMPROBANTE: </b><span id="lbl_comprobante"></span>
            				</div> 
            				<div class="col-sm-12">
            					<b>Precio Referencial Total:</b><span id="lbl_referencial"></span>
            				</div>
            				<div class="col-sm-12">
            					<b>Precio compra: </b><span id="lbl_compra"></span>
            				</div>
            				<div class="col-sm-12">
            					<b>ahorro: </b>	<span id="lbl_ahorro"></span>
            				</div>           
            				<div class="col-sm-12">
            					<b>Proveedor:</b><span id="lbl_proveedor"></span>
            				</div>				
            			</div>
            			
            		</div>  
            		<div class="col-sm-12" style="overflow-x: scroll;">
            			<table class="table table-sm">
            				<thead>
            					<th>No</th>
            					<th>FAMILIA</th>
            					<th>CODIGO</th>
            					<th>ITEM</th>
            					<th>MARCAS</th>
            					<th>CANT</th>
            					<th>P. UNI</th>
            					<th>PRECIO REFERENCIAL</th>
            					<th>P. UNI</th>
            					<th>PREVIO COMPRA</th>
            					<th>AHO/UNI</th>
            				</thead>
            				<tbody id="tbl_detalle">
            					
            				</tbody>            				
            			</table>            			
            		</div>          		
            	</div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>