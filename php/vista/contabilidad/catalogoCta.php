<?php  

//require_once("../vista/header.php");

?>
	<script type="text/javascript">
		
	function consultar_datos()
	{
		var parametros =
		{
			'OpcT':$("#OpcT").is(':checked'),
			'OpcG':$("#OpcG").is(':checked'),
			'OpcD':$("#OpcD").is(':checked'),
			'txt_CtaI':$('#txt_CtaI').val(),
			'txt_CtaF':$('#txt_CtaF').val(),			
		}
		$.ajax({
			data:  {parametros:parametros},
			url:   '../controlador/contabilidad/catalogoCtaC.php?consultar=true',
			type:  'post',
			dataType: 'json',
			beforeSend: function () {		
			  //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 // $('#tabla_').html(spiner);
				 $('#myModal_espera').modal('show');
			},
				success:  function (response) {
				
				 $('#tabla_').html(response);	
				 $('#myModal_espera').modal('hide');				    
				
			}
		});

	}

/*       funcion enviada  a panel 
	function validar_cuenta(campo)
	{
		var id = campo.id;
		let cap = $('#'+id).val();
		let cuentaini = cap.replace(/[.]/gi,'');
	//	var cuentafin = $('#txt_CtaF').val();
		var formato = "<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>";
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


	}*/

	$(document).ready(function()
	{
		consultar_datos();

		$('#txt_CtaI').keyup(function(e){ 
			if(e.keyCode != 46 && e.keyCode !=8)
			{
				validar_cuenta(this);
			}
		 })

		$('#txt_CtaF').keyup(function(e){ 
			if(e.keyCode != 46 && e.keyCode !=8)
			{
				validar_cuenta(this);
			}
		 })


       $('#imprimir_excel').click(function(){      		

      	var url = '../controlador/contabilidad/catalogoCtaC.php?imprimir_excel=true&OpcT='+$("#OpcT").is(':checked')+'&OpcG='+$("#OpcG").is(':checked')+'&OpcD='+$("#OpcD").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val();
      	    window.open(url, '_blank');
       });

        $('#imprimir_pdf').click(function(){      		

      	var url = '../controlador/contabilidad/catalogoCtaC.php?imprimir_pdf=true&OpcT='+$("#OpcT").is(':checked')+'&OpcG='+$("#OpcG").is(':checked')+'&OpcD='+$("#OpcD").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val();
      	    window.open(url, '_blank');
       });

       
    });



	</script>

   <div class="col-ms-12" style="margin-top: -60px">
	  <br><br>
	  <div class="row">          
          <div class="panel-body">
	  	      <div class="col-sm-3">
            	<a href="./contabilidad.php?mod=contabilidad#" data-toggle="tooltip" title="Salir de modulo" class="btn btn-default">
            		<img src="../../img/png/salire.png">
            	</a>
            	 <a href="#" class="btn btn-default" id='imprimir_pdf'  data-toggle="tooltip"title="Descargar PDF">
            		<img src="../../img/png/pdf.png">
            	</a>
            	<a href="#"  class="btn btn-default"  data-toggle="tooltip"title="Descargar excel" id='imprimir_excel'>
            		<img src="../../img/png/table_excel.png">
            	</a>
            	<button title="Consultar Catalogo de cuentas"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos();">
            		<img src="../../img/png/consultar.png" >
            	</button>

	  	     </div>
	  	<div class="col-sm-5">
	  		<div class="row">
	  			<div class="col-xs-6">
             		<b>Cuenta inicial:</b>
             		<br>
             	   <input type="text" name="txt_CtaI" id="txt_CtaI" class="form-control input-xs" placeholder="<?php 
						echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
             	</div>
                <div class="col-xs-6">
             	  <b> Cuenta final:</b>
             	<br>
             	   <input type="text" name="txt_CtaF" id="txt_CtaF" class="form-control input-xs" placeholder="<?php 
						echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>"> 
             	</div>       	
             	  		
	  			
	  		</div>             	
	  	</div>
	  	<div class="col-sm-4">
           <div class="row">
             <div class="col-sm-12">
             	<br>
                <label class="radio-inline"><input type="radio" name="OpcP" id="OpcT" checked="" onchange="consultar_datos();"><b>Todos</b></label>
          	    <label class="radio-inline"><input type="radio" name="OpcP" id="OpcG" onchange="consultar_datos();"><b>De grupo</b></label>            
          	    <label class="radio-inline"><input type="radio" name="OpcP" id="OpcD" onchange="consultar_datos();"><b>De Detalles</b></label>              			
            </div>             		          		
          </div>
          </div>	
	   </div>

	  </div>	 
	  <!--seccion de panel-->
	  <div class="row">
	  	<input type="input" name="activo" id="activo" value="1" hidden="">
	  	<div class="col-sm-12">
	  		<ul class="nav nav-tabs">
	  		   <li class="active">
	  		   	<a data-toggle="tab" href="#home" id="titulo_tab" onclick="activar(this)"><b>PLAN DE CUENTAS</b></a></li>
	  		</ul>
	  	    <div class="tab-content" style="background-color:#E7F5FF">
	  	    	<div id="home" class="tab-pane fade in active">
	  	    	   <div  id="tabla_">
	  	    	   		  	    	   	
	  	    	   </div>
	  	    	 </div>	  	    	
	  	    </div>
	  	</div>
	  </div>
	</div>
   <br><br>