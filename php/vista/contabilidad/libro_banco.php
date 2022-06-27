<script type="text/javascript">
	var Individual = false;
	$(document).ready(function()
	{
		sucursal_exis();
		llenar_combobox();
		llenar_combobox_cuentas();    
		   $('#imprimir_pdf').click(function(){
            var url = '../controlador/contabilidad/libro_bancoC.php?imprimir_pdf=true&CheckUsu='+$("#CheckUsu").is(':checked')+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&DCAgencia='+$('#DCAgencia').val()+'&DCUsuario='+$('#DCUsuario').val()+'&DCCtas='+$('#DCCtas').val();
                 
      	   window.open(url, '_blank');
       });

	
	  $('#imprimir_excel').click(function(){
            var url = '../controlador/contabilidad/libro_bancoC.php?imprimir_excel=true&CheckUsu='+$("#CheckUsu").is(':checked')+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val()+'&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&DCAgencia='+$('#DCAgencia').val()+'&DCUsuario='+$('#DCUsuario').val()+'&DCCtas='+$('#DCCtas').val()+'&OpcUno='+$('#OpcU').val()+'&PorConceptos='+Individual+'&submodulo=false';
                 
      	   window.open(url, '_blank');
       });

    });



    function fecha_fin()
    {
    	$fecha = $('#desde').val();
    	partes = $fecha.split('-');
    	var fecha = new Date();
        var ano = fecha.getFullYear();
    	if(partes[0] <= (ano+30) && partes[0]>1999)
    	{
    		console.log(ano+10);
    	    var date = new Date($fecha);
    	    var primerDia = new Date(date.getFullYear(), date.getMonth(), 1);
    	    var ultimoDia = new Date(partes[0],partes[1],0);
    	    var mes= date.getMonth()+1;
    	    console.log(ultimoDia);

    	    if(mes <10)
    	    {
    		    mes = '0'+mes;
    	    }
    	    $('#hasta').val(partes[0]+"-"+partes[1]+"-"+ultimoDia.getDate());
    	    consultar_datos();
        }else
        {
       	 alert('Procure que la fecha no sea mayor a '+(ano+30)+' y menor a 2000');
        }
 

    }
    function sucursal_exis()
  { 

     $.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/contabilidad/diario_generalC.php?sucu_exi=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) { 
        if(response == 1)
        {
          $("#CheckAgencia").show();
          $('#DCAgencia').show();
          $('#lblAgencia').show();
        } else
        {
          $("#CheckAgencia").hide();
          $('#DCAgencia').hide();
          $('#lblAgencia').hide();
        }     
        
      }
    });

  }
		
	function consultar_datos()
	{
		var parametros =
		{
			'CheckUsu':$("#CheckUsu").is(':checked'),
			'CheckAgencia':$("#CheckAgencia").is(':checked'),
			'desde':$('#desde').val(),
			'hasta':$('#hasta').val(),	
			'DCAgencia':$('#DCAgencia').val(),
			'DCUsuario':$('#DCUsuario').val(),	
			'DCCtas':$('#DCCtas').val(),			
		}
		$titulo = 'Mayor de '+$('#DCCtas option:selected').html(),
		$.ajax({
			data:  {parametros:parametros},
			url:   '../controlador/contabilidad/libro_bancoC.php?consultar=true',
			type:  'post',
			dataType: 'json',
			beforeSend: function () {		
			  //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 // $('#tabla_').html(spiner);
				 $('#myModal_espera').modal('show');
			},
				success:  function (response) {
				consultar_totales();
				
				 $('#tabla_').html(response);
				 var nFilas = $("#tabla_ tr").length;
				 // $('#num_r').html(nFilas-1);	
				 $('#myModal_espera').modal('hide');	
				 $('#tit').text($titulo);			    
				
			}
		});

	}

	function consultar_totales()
	{
		var parametros =
		{
			'CheckUsu':$("#CheckUsu").is(':checked'),
			'CheckAgencia':$("#CheckAgencia").is(':checked'),
			'desde':$('#desde').val(),
			'hasta':$('#hasta').val(),	
			'DCAgencia':$('#DCAgencia').val(),
			'DCUsuario':$('#DCUsuario').val(),	
			'DCCtas':$('#DCCtas').val(),			
		}
		$titulo = 'Mayor de '+$('#DCCtas option:selected').html(),
		$.ajax({
			data:  {parametros:parametros},
			url:   '../controlador/contabilidad/libro_bancoC.php?consultar_tot=true',
			type:  'post',
			dataType: 'json',
			beforeSend: function () {		
			  //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 // $('#tabla_').html(spiner);
				// $('#myModal_espera').modal('show');
			},
				success:  function (response) {
					$('#debe').val(addCommas(response.Debe));
					$('#haber').val(addCommas(response.Haber));					
					$('#saldo_ant').val(addCommas(response.SalAnt));
					$('#saldo').val(addCommas(response.Saldo));

					$('#debe_').val(addCommas(response.Debe_ME));
					$('#haber_').val(addCommas(response.Haber_ME));
					$('#saldo_ant_').val(addCommas(response.SalAnt_));
					$('#saldo_').val(addCommas(response.Saldo_ME));

				console.log(response);
			}
		});

	}

		
	function llenar_combobox()
	{	

		var agencia='<option value="">Seleccione Agencia</option>';
		var usu='<option value="">Seleccione Usuario</option>';
		$.ajax({
			//data:  {parametros:parametros},
			url:   '../controlador/contabilidad/diario_generalC.php?drop=true',
			type:  'post',
			dataType: 'json',
			/*beforeSend: function () {		
			     var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 $('#tabla_').html(spiner);
			},*/
				success:  function (response) {				
				$.each(response.agencia, function(i, item){
					agencia+='<option value="'+response.agencia[i].Item+'">'+response.agencia[i].NomEmpresa+'</option>';
				});				
				$('#DCAgencia').html(agencia);
				$.each(response.usuario, function(i, item){
					usu+='<option value="'+response.usuario[i].Codigo+'">'+response.usuario[i].CodUsuario+'</option>';
				});					
				$('#DCUsuario').html(usu);			    
				
			}
		});

	}

	function llenar_combobox_cuentas()
	{	

		var agencia='<option value="">Seleccione Cuenta</option>';
		$.ajax({
			//data:  {ini:ini,fin:fin},
			url:   '../controlador/contabilidad/libro_bancoC.php?cuentas=true',
			type:  'post',
			dataType: 'json',
			/*beforeSend: function () {		
			     var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 $('#tabla_').html(spiner);
			},*/
				success:  function (response) {	
				var count=0;			
				$.each(response, function(i, item){
					if(count == 0)
					{
					  agencia+='<option value="'+response[i].Codigo+'" selected>'+response[i].Nombre_Cta+'</option>';
				    }else
				    {
				      agencia+='<option value="'+response[i].Codigo+'">'+response[i].Nombre_Cta+'</option>';	
				    } 

					count = count+1;
				});				

				$('#DCCtas').html(agencia);					    
		        consultar_datos(true,Individual);				
			}
		});

	}
	</script>

   	<div class="row">
   		<div class="col-lg-4 col-sm-4 col-md-8 col-xs-12">
   			<div class="col-xs-2 col-md-2 col-sm-2">
   				<a href="./contabilidad.php?mod=contabilidad#" data-toggle="tooltip" title="Salir de modulo" class="btn btn-default">
            		<img src="../../img/png/salire.png">
            	</a>
            </div>
             <div class="col-xs-2 col-md-2 col-sm-2">
            	<button title="Consultar Mayores auxiliares"  data-toggle="tooltip" class="btn btn-default" onclick="consultar_datos(true,Individual);">
            		<img src="../../img/png/consultar.png" >
            	</button>
            	</div>		
           
            <div class="col-xs-2 col-md-2 col-sm-2">
              <a href="#" id="imprimir_pdf" class="btn btn-default" data-toggle="tooltip" title="Descargar PDF">
                 <img src="../../img/png/pdf.png">
              </a>                          	
            </div>
            	
            <div class="col-xs-2 col-md-2 col-sm-2">
            		<a href="#" id="imprimir_excel"  class="btn btn-default" data-toggle="tooltip" title="Descargar excel">
            	      <img src="../../img/png/table_excel.png">
            	     </a>                          	
                </div>

           
   		</div>
   		
   	</div>
	<div class="row">   	  	
	  	<div class="col-sm-3"><br>
	  		<b>Desde:</b>
            <input type="date" name="desde" id="desde" class="input-xs"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);fecha_fin()" onkeyup="validar_year_mayor(this.id)">
			<br>
            <b>Hasta:&nbsp;</b>
            <input type="date" name="hasta" id="hasta"  class="input-xs"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);consultar_datos(true,Individual);" onkeyup="validar_year_mayor(this.id)">  	              	
	  	</div>

	  	<div class="col-sm-3">
                <label style="margin:0px"><input type="checkbox" name="CheckUsu" id="CheckUsu">  <b>Por usuario</b></label>
                <select class="form-control input-xs" id="DCUsuario"  onchange="consultar_datos(true,Individual);">
                	<option value="">Seleccione usuario</option>
                </select>
          	    <label id="lblAgencia" style="margin:0px"><input type="checkbox" name="CheckAgencia" id="CheckAgencia">  <b>Agencia</b></label>
          	     <select class="form-control input-xs" id="DCAgencia" onchange="consultar_datos(true,Individual);">
                	<option value="">Seleccione agencia</option>
                </select>             
        </div>
        <div class="col-sm-3">
        	<b>Por cuenta</b>
                <select class="form-control input-xs" id="DCCtas" onchange="consultar_datos(true,Individual);">
                	<option value="">Seleccione cuenta</option>
                </select>
          	   
        </div>		
	</div>
	<br>
	  <!--seccion de panel-->
	  <div class="row">
	  	<input type="input" name="OpcU" id="OpcU" value="true" hidden="">
	  	<div class="col-sm-12">
	  		<ul class="nav nav-tabs">
	  		   <li class="active">
	  		   	<a data-toggle="tab" href="#home" id="titulo_tab" onclick="activar(this)"><b id="tit">Mayores auxiliares</b></a></li>
	  		</ul>
	  	    <div class="tab-content" style="background-color:#E7F5FF">
	  	    	<div id="home" class="tab-pane fade in active">	  	    			
	  	    	   <div id="tabla_" style="height:600px;">
	  	    	   		  	    	   	
	  	    	   </div>
	  	    	 </div>		  	    	  	    	
	  	    </div>	  	  
	  	</div>
	  </div>
	  <div class="row">
	  	<div class="col-sm-2">
	  		<b>Saldo Ant MN:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="saldo_ant" class="text-right rounded border border-primary" size="8" readonly value="0.00" />
	  	</div>
	  	<div class="col-sm-1">
	  		<b>Debe MN:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="debe" class="text-right rounded border border-primary" size="8" readonly value="0.00" />
	  	</div>
	  	<div class="col-sm-1">
	  		<b>Haber MN:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="haber" class="text-right rounded border border-primary" size="8" readonly value="0.00" />
	  	</div>
	  	<div class="col-sm-1">
	  		<b>Saldo MN:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="saldo" class="text-right rounded border border-primary" size="8" readonly value="0.00"/>
	  	</div>	  	
	  </div>
	  <div class="row">
	  	<div class="col-sm-2">
	  		<b>Saldo Ant ME:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="saldo_ant_" class="text-right rounded border border-primary" size="8" readonly value="0.00"/>
	  	</div>
	  	<div class="col-sm-1">
	  		<b>Debe ME:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="debe_" class="text-right rounded border border-primary" size="8" readonly value="0.00"/>
	  	</div>
	  	<div class="col-sm-1">
	  		<b>Haber ME:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="haber_" class="text-right rounded border border-primary" size="8" readonly value="0.00"/>
	  	</div>
	  	<div class="col-sm-1">
	  		<b>Saldo ME:</b>
	  	</div>
	  	<div class="col-sm-1">
	  		<input type="text" id="saldo_" class="text-right rounded border border-primary" size="8" readonly value="0.00"/>
	  	</div>
	  </div>