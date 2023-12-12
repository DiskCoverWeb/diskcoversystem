<?php
	Ejecutar_SQL_SP("UPDATE Comprobantes " .
        "SET Cotizacion = 0.004 " .
        "WHERE Cotizacion = 0 " .
        "AND Item = '" . $_SESSION['INGRESO']['item'] . "' " .
        "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'");
?>
<script type="text/javascript">
	var Individual = false;
	$(document).ready(function()
	{

		// console.log(screen.height);
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
    	    ConsultarDatosLibroBanco();
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
		
	function ConsultarDatosLibroBanco()
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
			'height':screen.height,			
		}
		$titulo = 'Mayor de '+$('#DCCtas option:selected').html();
		$('#myModal_espera').modal('show');
		$.ajax({
			data:  {parametros:parametros},
			url:   '../controlador/contabilidad/libro_bancoC.php?consultar=true',
			type:  'post',
			dataType: 'json',
			success:  function (response) {
				$('#debe').val(addCommas(response.LabelTotDebe));
				$('#haber').val(addCommas(response.LabelTotHaber));					
				$('#saldo_ant').val(addCommas(response.LabelSaldoAntMN));
				$('#saldo').val(addCommas(response.LabelTotSaldo));

				$('#debe_').val(addCommas(response.LabelTotDebeME));
				$('#haber_').val(addCommas(response.LabelTotHaberME));
				$('#saldo_ant_').val(addCommas(response.LabelSaldoAntME));
				$('#saldo_').val(addCommas(response.LabelTotSaldoME));
				
				 $('#tabla_').html(response.DGBanco);
				 var nFilas = $("#tabla_ tr").length;
				 $('#myModal_espera').modal('hide');	
				 $('#tit').text($titulo+" (Registros: "+response.TotalRegistros+")");			    
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
		var cuentas='';
		$.ajax({
			url:   '../controlador/contabilidad/libro_bancoC.php?cuentas=true',
			type:  'post',
			dataType: 'json',
				success:  function (response) {	
				$.each(response, function(i, item){
					cuentas+='<option value="'+response[i].Codigo+'" '+((i==0)?'selected':'')+'>'+response[i].Nombre_Cta+'</option>';
				});				
				if($.trim(cuentas) === ''){
					cuentas='<option value=".">Sin Cuentas</option>';
				}
				$('#DCCtas').html(cuentas);					    
		        ConsultarDatosLibroBanco();				
			}
		});

	}
	</script>

   	<div class="row">
   		<div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
   			<div class="col-xs-2 col-md-2 col-sm-2">
   				<a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" data-toggle="tooltip" title="Salir de modulo" class="btn btn-default">
            		<img src="../../img/png/salire.png">
            	</a>
            </div>
             <div class="col-xs-2 col-md-2 col-sm-2">
            	<button title="Consultar"  data-toggle="tooltip" class="btn btn-default" onclick="ConsultarDatosLibroBanco();">
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
            <input type="date" name="hasta" id="hasta"  class="input-xs"  value="<?php echo date("Y-m-d");?>" onblur="validar_year_menor(this.id);ConsultarDatosLibroBanco();" onkeyup="validar_year_mayor(this.id)">  	              	
	  	</div>

	  	<div class="col-sm-3">
                <label style="margin:0px"><input type="checkbox" name="CheckUsu" id="CheckUsu">  <b>Por usuario</b></label>
                <select class="form-control input-xs" id="DCUsuario"  onchange="ConsultarDatosLibroBanco();">
                	<option value="">Seleccione usuario</option>
                </select>
          	    <label id="lblAgencia" style="margin:0px"><input type="checkbox" name="CheckAgencia" id="CheckAgencia">  <b>Agencia</b></label>
          	     <select class="form-control input-xs" id="DCAgencia" onchange="ConsultarDatosLibroBanco();">
                	<option value="">Seleccione agencia</option>
                </select>             
        </div>
        <div class="col-sm-3">
        	<b>Por cuenta</b>
                <select class="form-control input-xs" id="DCCtas" onchange="ConsultarDatosLibroBanco();">
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
	  	    	   <div id="tabla_">
	  	    	   		  	    	   	
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