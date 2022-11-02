function validar_cuenta(campo)
{
	var id = campo.id;
	let cap = $('#'+id).val();
	let cuentaini = cap.replace(/[.]/gi,'');
	var cuentafin = $('#txt_CtaF').val();
	// var formato = "<?php if(isset($_SESSION['INGRESO']['Formato_Cuentas'])){echo $_SESSION['INGRESO']['Formato_Cuentas'];}?>";
	// console.log(formato);
	// ---formato se se encuenta en header
	let parte =formato.split('.');
	var nuevo =  new Array(); 
	let cadnew ='';
	for (var i = 0 ; i < parte.length; i++) {

		if(cuentaini.length != '')
		{
			var b = parte[i].length;
			var c = cuentaini.substr(0,b);
			if(c.length==b)
			{
				nuevo[i] = c;
				cuentaini = cuentaini.substr(b);
			}else
			{   
			  if(c != 0){  
				//for (var ii =0; ii<b; ii++) {
					var n = c;
					//if(n.length==b)
					//{
					   //if(n !='00')
					  // {
						nuevo[i] =n;
			            cuentaini = cuentaini.substr(b);
			         //  }
			         //break;
					  
					//}else
					//{
					//	c = n;
					//}
					
				//}
			  }else
			  {
			  	nuevo[i] =c;
			    cuentaini = cuentaini.substr(b);
			  }
			}
		}
	}
	var m ='';
	nuevo.forEach(function(item,index){
		m+=item+'.';
	})
	//console.log(m);
	$('#'+id).val(m);
}

function validar_cuenta_inv(campo)
{
	var id = campo.id;
	let cap = $('#'+id).val();
	let cuentaini = cap.replace(/[.]/gi,'');
	// var formato = "<?php if(isset($_SESSION['INGRESO']['Formato_Cuentas'])){echo $_SESSION['INGRESO']['Formato_Cuentas'];}?>";
	// console.log(formato);
	// ---formato se se encuenta en header
	let parte =formato_inv.split('.');
	var nuevo =  new Array(); 
	let cadnew ='';
	for (var i = 0 ; i < parte.length; i++) {

		if(cuentaini.length != '')
		{
			var b = parte[i].length;
			var c = cuentaini.substr(0,b);
			if(c.length==b)
			{
				nuevo[i] = c;
				cuentaini = cuentaini.substr(b);
			}else
			{   
			  if(c != 0){  
				//for (var ii =0; ii<b; ii++) {
					var n = c;
					//if(n.length==b)
					//{
					   //if(n !='00')
					  // {
						nuevo[i] =n;
			            cuentaini = cuentaini.substr(b);
			         //  }
			         //break;
					  
					//}else
					//{
					//	c = n;
					//}
					
				//}
			  }else
			  {
			  	nuevo[i] =c;
			    cuentaini = cuentaini.substr(b);
			  }
			}
		}
	}
	var m ='';
	nuevo.forEach(function(item,index){
		m+=item+'.';
	})
	//console.log(m);
	$('#'+id).val(m);
}



function validar_year_mayor(nombre)
{

	var fecha = $('#'+nombre+'').val();
	var partes = fecha.split('-');
	console.log(partes);
	if(partes[0].length > 4 || partes[0] > 2050)
	{
		$('#'+nombre+'').val('2050-'+partes[1]+'-'+partes[2]);
	}
	

}
function validar_year_menor(nombre)
{

	var fecha = $('#'+nombre+'').val();
	var partes = fecha.split('-');
	console.log(partes);
	if(partes[0] < 2000)
	{
		alert('Año seleccionado menor a 1999');
		$('#'+nombre+'').val('1999-'+partes[1]+'-'+partes[2]);
	}
}
function addCommas(nStr) 
{
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
function num_caracteres(campo,num)
{
	var val = $('#'+campo).val();
	var cant = val.length;
	console.log(cant+'-'+num);

	if(cant>num)
	{
		$('#'+campo).val(val.substr(0,num));
		return false;
	}

}

String.prototype.ucwords = function() {
	str = this.toLowerCase();
	// return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
	// 	function($1){
	// 		return $1.toUpperCase();
	// 		});
	return str.toUpperCase(); 
}

function llenarComboList(datos,nombre){
    var nombreCombo = $("#"+nombre);
    nombreCombo.find('option').remove();
    for (var indice in datos) {      
      nombreCombo.append('<option value="' + datos[indice].codigo + '">' + datos[indice].nombre + '</option>');        
    }
}

function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
}

function soloNumerosDecimales(e)
{
	var key = window.Event ? e.which : e.keyCode
	return (key <= 13 || (key >= 48 && key <= 57) || key==46)
}
function mayusculas(campo,valor)
{
    $('#'+campo).val(valor.ucwords());
}

function generar_ceros(num,cant)
{
	var n = num.toString();
	var len_num = n.length;
	// console.log(len_num);
	// console.log(cant);
	if(len_num<cant)
	{
		ceros = cant-len_num;
		var new_num = '0'.repeat(ceros);
		return new_num+''+num;
	} 
}

function paginacion(inicio,fin,funcion,posicion)
{
	var pag = '<nav aria-label="...">'+
	  '<ul class="pagination">'+
	   ' <li class="page-item disabled">'+
	      '<a class="page-link" href="#" tabindex="-1">Inicio</a>'+
	   ' </li>'
	    for (var i = 1; i <= 10; i++) {
	    	var ini = i*100
	    	if(inicio==0)
	    	{
		   		pag+=' <li class="page-item active" onclick="paginacion("'+ini+'")"><a class="page-link" href="#">'+i+'</a></li>'
			}else
			{
				pag+=' <li class="page-item"  onclick="paginacion("'+ini+'")"><a class="page-link" href="#">'+i+'</a></li>'
			}
		}
	    pag+='<li class="page-item">'+
	      '<a class="page-link" href="#">Fin</a>'+
	    '</li>'+
	  '</ul>'+
	'</nav>';
	$('#'+posicion).html(pag);
}