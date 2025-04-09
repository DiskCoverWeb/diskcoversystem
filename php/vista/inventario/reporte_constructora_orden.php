<script type="text/javascript">
	$(document).ready(function(){
		contratistas()
		orden();
		meses();
		Calcularsemanas()
  	});

	function obtenerSemanasDelMes(year, month) {
    // Ajustar month (0-11 en JavaScript)
	    const firstDay = new Date(year, month - 1, 1);
	    const lastDay = new Date(year, month, 0); // Último día del mes
	    
	    // Función para número de semana ISO
	    const getISOWeek = (date) => {
	        const d = new Date(date);
	        d.setHours(0, 0, 0, 0);
	        d.setDate(d.getDate() + 3 - (d.getDay() + 6) % 7);
	        const firstDayOfYear = new Date(d.getFullYear(), 0, 1);
	        const diffDays = Math.round((d - firstDayOfYear) / (86400000));
	        return Math.floor(diffDays / 7) + 1;
	    };

	    // Calcular semanas del primer y último día
	    const firstWeek = getISOWeek(firstDay);
	    const lastWeek = getISOWeek(lastDay);

	    // Generar array de semanas
	    const semanas = [];
	    for (let week = firstWeek; week <= lastWeek; week++) {
	        semanas.push(week);
	    }

	    return semanas;
	}

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

 	function Calcularsemanas()
  	{
  		const year = new Date().getFullYear();
  		$('#ddl_semanas').html('<option value="">Seleccione</option>');
  		if($('#ddl_meses').val()=='')
  		{
  			semanas = NumeroSemanasxAnio(year)
  			for (var i = 1; i <= semanas; i++) {
	  			$('#ddl_semanas').append('<option value="'+i+'">'+i+'</option>')
	  		}
  		}else
  		{
  			var month = $('#ddl_meses').val();
  		 	semanas = obtenerSemanasDelMes(year, month);
  		 	semanas.forEach(function(item,i){
  		 			$('#ddl_semanas').append('<option value="'+item+'">'+item+'</option>')
  		 	})
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
  			'hasta':$('#txt_fecha_hasta').val(),
  		}
  		$.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/reporte_constructora_Compras.php?cargar_datos_orden=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) {
        	console.log(response);
        	var table = '';
        	var total = 0;
        	response.forEach(function(item,i){
        		table+=`<tr>
        		<td>`+item.Cliente+`</td>
        		<td>`+item.Orden_No+`</td>
        		<td>`+item.semana+`</td>
        		<td>`+item.Fecha+`</td>
        		<td>`+item.total+`</td>
        		</tr>`
        		total= total+parseFloat(item.total);
        	})

        	$('#lbl_num_ord').text(response.length);
			$('#lbl_resumen').text(total.toFixed(2));
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
  		$('#ddl_contratista').val('')
  		orden();
  	}
  	function limpiar_orden()
  	{
  		$('#ddl_orden').empty();
  		cargar_datos();
  	}


  	function imprimir_pdf()
  	{  		
  		var url = '../controlador/inventario/reporte_constructora_Compras.php?cargar_orden_pdf=true&contratista='+$('#ddl_contratista').val()+'&orden='+$('#ddl_orden').val()+'&mes='+$('#ddl_meses').val()+'&semanas='+$('#ddl_semanas').val()+'&fecha='+$('#txt_fecha').val()+'&hasta='+$('#txt_fecha_hasta').val()                                  
      window.open(url, '_blank');
  	}
  	function imprimir_excel()
  	{  		
  		var url = '../controlador/inventario/reporte_constructora_Compras.php?cargar_orden_excel=true&contratista='+$('#ddl_contratista').val()+'&orden='+$('#ddl_orden').val()+'&mes='+$('#ddl_meses').val()+'&semanas='+$('#ddl_semanas').val()+'&fecha='+$('#txt_fecha').val()+'&hasta='+$('#txt_fecha_hasta').val()                                  
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
			<select class="form-control input-sm" id="ddl_contratista" onchange=" cargar_datos();orden()">
				<option value="">Selecciones</option>
			</select>
			<span class="input-group-btn">
				<button class="btn btn-danger btn-xs" style="height: 18pt;padding-top: 3px;"  onclick="limpiar_contra()"><i class="fa fa-close"></i></button>
			</span>
		</div>
	</div>
	<div class="col-sm-3">
		<b>Orden</b>
		<div class="input-group input-group-sm">
			<select class="form-control input-sm" id="ddl_orden" onchange=" cargar_datos()">
				<option value="">Selecciones</option>
			</select>
			<span class="input-group-btn">
					<button class="btn btn-danger btn-xs" style="height: 18pt;padding-top: 3px;" onclick="limpiar_orden()"><i class="fa fa-close"></i></button>
				</span>
		</div>
		
	</div>
	<div class="col-sm-2">
		<b>Meses</b>
		<div class="input-group input-group-sm">
			<select class="form-control input-sm" id="ddl_meses" onchange="Calcularsemanas();">
				<option value="">Selecciones</option>
			</select>
			<span class="input-group-btn">
					<button class="btn btn-danger btn-flat p-0" onclick="$('#ddl_meses').val('');Calcularsemanas()"><i class="fa fa-close"></i></button>
			</span>
		</div>
	</div>
	<div class="col-sm-2">
		<b>Semana</b>
		<div class="input-group input-group-sm">
			<select class="form-control input-sm" id="ddl_semanas">
				<option value="">Selecciones</option>
			</select>
			<span class="input-group-btn">
						<button class="btn btn-danger btn-flat p-0" onclick="$('#ddl_semanas').val('')"><i class="fa fa-close"></i></button>
			</span>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="row">
			<div class="col-sm-6">
				Desde
				<div class="input-group input-group-sm">
					<input type="date" name="txt_fecha" id="txt_fecha" class="form-control input-sm">
					<span class="input-group-btn">
						<button class="btn btn-danger btn-flat p-0" onclick="$('#txt_fecha').val('')"><i class="fa fa-close"></i></button>
					</span>
				</div>
			</div>
			<div class="col-sm-6">
					Hasta
				<div class="input-group input-group-sm">
					<input type="date" name="txt_fecha" id="txt_fecha_hasta" class="form-control input-sm">
					<span class="input-group-btn">
							<button class="btn btn-danger btn-flat p-0" onclick="$('#txt_fecha_hasta').val('')"><i class="fa fa-close"></i></button>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-8 text-right">
		<br>
		<button class="btn btn-primary btn-sm" onclick="cargar_datos()"><i class="fa fa-search"></i>Buscar</button>		
	</div>
	
</div>
<div class="row text-right">
	<div class="col-sm-12">
		<b># Ordenes:</b> <span id="lbl_num_ord">0</span>
	</div>
	<div class="col-sm-12">
		<b>Resumen:</b> <span id="lbl_resumen">0</span>
	</div>	
</div>

<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm">
			<thead>
				<th>Contratista</th>
				<th>Orden</th>
				<th>Semana del año</th>
				<th>Fecha</th>
				<th>Precio total</th>
			</thead>
			<tbody id="tbl_body">
				
			</tbody>
			
		</table>
			
	</div>	
</div>