<?php
	 @session_start();
	// include(dirname(__DIR__,2)."/controlador/contabilidad/contabilidad_controller.php");
 //verificacion titulo accion
	$_SESSION['INGRESO']['ti']='';
	if(isset($_GET['ti'])) 
	{
		$_SESSION['INGRESO']['ti']=$_GET['ti'];
	}
	else
	{
		unset( $_SESSION['INGRESO']['ti']);
		$_SESSION['INGRESO']['ti']='BALANCE DE COMPROBACIÓN';
	}
?>
<!-- <meta charset="ISO-8859-1"> -->
<div class="row">
	<div class="col-sm-5">
	   <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style=" width: fit-content;padding: 0px;">
	     <a class="btn btn-default" title="Salir del modulo" href="./contabilidad.php?mod=contabilidad#">
	         <img src="../../img/png/salire.png">
	     </a>
	   </div>    
	   <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
	       <a id='l7' class="btn btn-default" title="Exportar Excel"	href="descarga.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>&Opcb=6&Opcen=0&b=0&ex=1" onclick='modificar1();' target="_blank"><img src="../../img/png/table_excel.png"></a>	      
	   </div>
	   <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">                 
	    <button class="btn btn-default" title="Modificar el comprobante" onclick="modificar_comprobante()">
					 <img src="../../img/png/modificar.png" >
	     </button>		   
	  </div>
	  <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
	      <a id='l2' class="btn btn-default" title="Anular comprobante"	href="contabilidad.php?mod=contabilidad&acc=compro&acc1=Comprobantes Procesados&b=1"><img src="../../img/png/anular.png" >
				</a>
	  </div>
	  <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
	    <a id='l3' class="btn btn-default" title="Autorizar comprobante autorizado">
					<img src="../../img/png/autorizar.png" > 
				</a>
	  </div>
	  <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
	      <a id='l4' class="btn btn-default" title="Realizar una copia al comprobante" href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta balance de Comprobación&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
					<img src="../../img/png/copiar.png" > 
				             </a>
	  </div>
	  <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
	     <a id='l5' class="btn btn-default" title="Copiar a otra empresa el comprobante" href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de situación (general)&ti=ESTADO SITUACIÓN&Opcb=5&Opcen=1&b=0">
					<img src="../../img/png/copiare.png" > 
				</a>		
	  </div>   	
	</div>
	<div class="col-sm-4">
			<?php echo $_SESSION['INGRESO']['item']; ?> 
    	<button type="submit" class="btn btn-default active" onclick="reset_('comproba','CD');" id='CD'>Diario</button>
			<button type="submit" class="btn btn-default" onclick="reset_('comproba','CI');" id='CI'>Ingreso</button>
			<button type="submit" class="btn btn-default" onclick="reset_('comproba','CE');" id='CE'>Egreso</button>
			<button type="submit" class="btn btn-default" onclick="reset_('comproba','ND');" id='ND'>N/D</button>
			<button type="submit" class="btn btn-default" onclick="reset_('comproba','NC');" id='NC'>N/C</button>
			<input id="tipoc" name="tipoc" type="hidden" value="CD">					
	</div>	
	<div class="col-sm-3">
		<div class="row">
  		<div class="col-sm-5" style="padding:0px">
  			<select class="form-control input-sm" name="tipo" id='mes' onchange="comprobante()">
				   <option value='0'>Todos</option><?php echo  Tabla_Dias_Meses();?>
			  </select>			      			
  		</div>
  		<div class="col-sm-7">
  			 <select class="form-control input-sm" name="ddl_comprobantes" id="ddl_comprobantes" onchange="listar_comprobante()">
		    	<option value="">Seleccione</option>
		    </select>			      			
  		</div>			      		
  	</div>		
	</div>
</div>
<div class="row">
		<input type="hidden" name="" id="txt_empresa" value="<?php echo $_SESSION['INGRESO']['item'];?>">
		<input type="hidden" name="" id="TP" value="CD">
		<input type="hidden" name="" id="beneficiario" value="">
		<input type="hidden" name="" id="Co" value="">
		<input type="hidden" name="" id="" value="">
		<input type="hidden" name="" id="" value="">
			<div class="col-sm-12">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
                   <li class="nav-item active">
                     <a class="nav-link" id="home-tab" data-toggle="tab" href="#contabilizacion" role="tab" aria-controls="contabilizacion" aria-selected="true">CONTABILIZACION</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" id="profile-tab" data-toggle="tab" href="#retencion" role="tab" aria-controls="retencion" aria-selected="false">RETENCIONES</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" id="contact-tab" data-toggle="tab" href="#subcuenta" role="tab" aria-controls="subcuenta" aria-selected="false">SUBCUENTAS</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" id="contact-tab" data-toggle="tab" href="#kardex" role="tab" aria-controls="kardex" aria-selected="false">KARDEX</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" id="contact-tab" data-toggle="tab" href="#informe" role="tab" aria-controls="informe" aria-selected="false">INFORME</a>
                   </li>
                 </ul>
                 <div class="tab-content" id="myTabContent" style="height: 330px;">
                   <div class="tab-pane active" id="contabilizacion" role="tabpanel" aria-labelledby="home-tab">
                   	 <div class="row" ><br>
                   	 	<div class="col-sm-12" id="tbl_contabilidad">
                   	 		
                   	 	</div>
                   	 	
                   	 </div>
                   </div>
                   <div class="tab-pane fade" id="retencion" role="tabpanel" aria-labelledby="profile-tab">
                   	<div class="row">
                   		<div class="col-sm-12" id="tbl_retenciones_co">
                   	 		
                   	 	</div> 
                   	 	<div class="col-sm-12" id="tbl_retenciones_ve">
                   	 		
                   	 	</div> 
                   	 	<div class="col-sm-12" id="tbl_retenciones">
                   	 		
                   	 	</div>                   	 	
                   	 </div>
                   </div>
                   <div class="tab-pane fade" id="subcuenta" role="tabpanel" aria-labelledby="contact-tab">
                   	<div class="row">
                   	 	<div class="col-sm-12" id="tbl_subcuentas">
                   	 		
                   	 	</div>                   	 	
                   	 </div>
                   </div>
                   <div class="tab-pane fade" id="kardex" role="tabpanel" aria-labelledby="contact-tab">
                   	 <div class="row">
                   	 	<div class="col-sm-12" id="tbl_kardex">
                   	 		
                   	 	</div>                   	 	
                   	 </div>
                   	 <div class="row">
                   	 	<div class="col-sm-6"></div>
                   	 	<div class="col-sm-1">
                   	 		<b>Total compra</b>
                   	 	</div> 
                   	 	<div class="col-sm-2">
                   	 		<input type="text" name="txt_total" readonly="" class="form-control input-sm" id="txt_total">
                   	 	</div> 
                   	 	<div class="col-sm-1">
                   	 		<b>Total costo</b>
                   	 	</div>                   	 	
                   	 	<div class="col-sm-2">
                   	 		<input type="text" name="txt_saldo" readonly="" class="form-control input-sm" id="txt_saldo">
                   	 	</div> 
                   	 </div>
                   </div>                   
                   <div class="tab-pane fade" id="informe" role="tabpanel" aria-labelledby="contact-tab">
                   	<div class="row">
                   		<div class="col-sm-12">
                   			<div id='pdfcom'></div>                      			
                   		</div>                   		
                   	</div>                	
                   </div>
                 </div>
			</div>			
</div>
<div class="row">
			<div class="col-sm-2">
			  <b>Elaborador por</b>				
			</div>
			<div class="col-sm-4">
			  <input type="text" name="" readonly="" class="form-control input-sm">		
			</div>
			<div class="col-sm-2">
			  <b>Totales</b>				
			</div>
			<div class="col-sm-2">			  				
			  <input type="text" name="txt_debe" readonly="" class="form-control input-sm" id="txt_debe">
			</div>			
			<div class="col-sm-2">
			  <input type="text" name="txt_haber" readonly="" class="form-control input-sm" id="txt_haber">				
			</div>
		</div>











<!-- 




 <div class="row">
			 <div class="box" style='margin-bottom: 5px;'>
			  <div class="box-header">
			  	<div class="row">
			  		<div class="col-sm-4">
            <div class="btn-group"> 
               <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style=" width: fit-content;padding: 0px;">
                 <a class="btn btn-default" title="Salir del modulo" href="./contabilidad.php?mod=contabilidad#">
					           <img src="../../img/png/salire.png">
			           </a>
               </div>    
               <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
                   <a id='l7' class="btn btn-default" title="Exportar Excel"	href="descarga.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>&Opcb=6&Opcen=0&b=0&ex=1" onclick='modificar1();' target="_blank"><img src="../../img/png/table_excel.png"></a>	      
               </div>
               <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">                 
                <button class="btn btn-default" title="Modificar el comprobante" onclick="modificar_comprobante()">
										 <img src="../../img/png/modificar.png" >
			           </button>		   
              </div>
              <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
                  <a id='l2' class="btn btn-default" title="Anular comprobante"	href="contabilidad.php?mod=contabilidad&acc=compro&acc1=Comprobantes Procesados&b=1"><img src="../../img/png/anular.png" >
									</a>
              </div>
              <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
                <a id='l3' class="btn btn-default" title="Autorizar comprobante autorizado">
										<img src="../../img/png/autorizar.png" > 
									</a>
              </div>
              <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
                  <a id='l4' class="btn btn-default" title="Realizar una copia al comprobante" href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta balance de Comprobación&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
										<img src="../../img/png/copiar.png" > 
									             </a>
              </div>
              <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
                 <a id='l5' class="btn btn-default" title="Copiar a otra empresa el comprobante" href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de situación (general)&ti=ESTADO SITUACIÓN&Opcb=5&Opcen=1&b=0">
										<img src="../../img/png/copiare.png" > 
									</a>		
              </div>      
            </div>
            </div>
           <div class="col-sm-4 col-md-4 text-center">			      	
								<?php echo $_SESSION['INGRESO']['item']; ?> 
			      	<button type="submit" class="btn btn-default active" onclick="reset_('comproba','CD');" id='CD'>Diario</button>
								<button type="submit" class="btn btn-default" onclick="reset_('comproba','CI');" id='CI'>Ingreso</button>
								<button type="submit" class="btn btn-default" onclick="reset_('comproba','CE');" id='CE'>Egreso</button>
								<button type="submit" class="btn btn-default" onclick="reset_('comproba','ND');" id='ND'>N/D</button>
								<button type="submit" class="btn btn-default" onclick="reset_('comproba','NC');" id='NC'>N/C</button>
								<input id="tipoc" name="tipoc" type="hidden" value="CD">			      	
			      </div>
			      <div class="col-sm-3 col-sm-3">
			      	<div class="row">
			      		<div class="col-sm-6">
			      			<select class="form-control" name="tipo" id='mes' onchange="comprobante()">
									   <option value='0'>Todos</option><?php echo  Tabla_Dias_Meses();?>
								  </select>			      			
			      		</div>
			      		<div class="col-sm-6">
			      			 <select class="form-control" name="ddl_comprobantes" id="ddl_comprobantes" onchange="listar_comprobante()">
							    	<option value="">Seleccione</option>
							    </select>			      			
			      		</div>			      		
			      	</div>
			      	
			      </div>
</div>

			  	
			  </div>
			 </div>
	  </div>
	<div class="">
		<input type="hidden" name="" id="txt_empresa" value="<?php echo $_SESSION['INGRESO']['item'];?>">
		<input type="hidden" name="" id="TP" value="CD">
		<input type="hidden" name="" id="beneficiario" value="">
		<input type="hidden" name="" id="Co" value="">
		<input type="hidden" name="" id="" value="">
		<input type="hidden" name="" id="" value="">
		<div class="row">
			<div class="col-sm-12">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
                   <li class="nav-item active">
                     <a class="nav-link" id="home-tab" data-toggle="tab" href="#contabilizacion" role="tab" aria-controls="contabilizacion" aria-selected="true">CONTABILIZACION</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" id="profile-tab" data-toggle="tab" href="#retencion" role="tab" aria-controls="retencion" aria-selected="false">RETENCIONES</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" id="contact-tab" data-toggle="tab" href="#subcuenta" role="tab" aria-controls="subcuenta" aria-selected="false">SUBCUENTAS</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" id="contact-tab" data-toggle="tab" href="#kardex" role="tab" aria-controls="kardex" aria-selected="false">KARDEX</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" id="contact-tab" data-toggle="tab" href="#informe" role="tab" aria-controls="informe" aria-selected="false">INFORME</a>
                   </li>
                 </ul>
                 <div class="tab-content" id="myTabContent" style="height: 330px;">
                   <div class="tab-pane active" id="contabilizacion" role="tabpanel" aria-labelledby="home-tab">
                   	 <div class="row" ><br>
                   	 	<div class="col-sm-12" id="tbl_contabilidad">
                   	 		
                   	 	</div>
                   	 	
                   	 </div>
                   </div>
                   <div class="tab-pane fade" id="retencion" role="tabpanel" aria-labelledby="profile-tab">
                   	<div class="row">
                   		<div class="col-sm-12" id="tbl_retenciones_co">
                   	 		
                   	 	</div> 
                   	 	<div class="col-sm-12" id="tbl_retenciones_ve">
                   	 		
                   	 	</div> 
                   	 	<div class="col-sm-12" id="tbl_retenciones">
                   	 		
                   	 	</div>                   	 	
                   	 </div>
                   </div>
                   <div class="tab-pane fade" id="subcuenta" role="tabpanel" aria-labelledby="contact-tab">
                   	<div class="row">
                   	 	<div class="col-sm-12" id="tbl_subcuentas">
                   	 		
                   	 	</div>                   	 	
                   	 </div>
                   </div>
                   <div class="tab-pane fade" id="kardex" role="tabpanel" aria-labelledby="contact-tab">
                   	 <div class="row">
                   	 	<div class="col-sm-12" id="tbl_kardex">
                   	 		
                   	 	</div>                   	 	
                   	 </div>
                   	 <div class="row">
                   	 	<div class="col-sm-6"></div>
                   	 	<div class="col-sm-1">
                   	 		<b>Total compra</b>
                   	 	</div> 
                   	 	<div class="col-sm-2">
                   	 		<input type="text" name="txt_total" readonly="" class="form-control input-sm" id="txt_total">
                   	 	</div> 
                   	 	<div class="col-sm-1">
                   	 		<b>Total costo</b>
                   	 	</div>                   	 	
                   	 	<div class="col-sm-2">
                   	 		<input type="text" name="txt_saldo" readonly="" class="form-control input-sm" id="txt_saldo">
                   	 	</div> 
                   	 </div>
                   </div>                   
                   <div class="tab-pane fade" id="informe" role="tabpanel" aria-labelledby="contact-tab">
                   	<div class="row">
                   		<div class="col-sm-12">
                   			<div id='pdfcom'></div>                      			
                   		</div>                   		
                   	</div>                	
                   </div>
                 </div>
			</div>			
		</div>
		<div class="row">
			<div class="col-sm-2">
			  <b>Elaborador por</b>				
			</div>
			<div class="col-sm-4">
			  <input type="text" name="" readonly="" class="form-control input-sm">		
			</div>
			<div class="col-sm-2">
			  <b>Totales</b>				
			</div>
			<div class="col-sm-2">			  				
			  <input type="text" name="txt_debe" readonly="" class="form-control input-sm" id="txt_debe">
			</div>			
			<div class="col-sm-2">
			  <input type="text" name="txt_haber" readonly="" class="form-control input-sm" id="txt_haber">				
			</div>
		</div>
		
	</div>
 -->

<!-- <div class="panel box box-primary">	  
  <div id="collapseOne" class="panel-collapse collapse in">
	<div class="box-body">
		<div class="box table-responsive" width="100%" height="100%">
			
		  </div>
		   
	</div>
  </div>
</div> -->
<script>
	$( document ).ready(function() {
		//buscar('comproba');
		comprobante();
		// listar_comprobante();
	
	});
	//modificar url
	function modificar(texto){
		var l1=$('#l1').attr("href");  
		var l1=l1+'&OpcDG='+texto;
		//asignamos
		$("#l1").attr("href",l1);
		
		var l2=$('#l2').attr("href");  
		var l2=l2+'&OpcDG='+texto;
		//asignamos
		$("#l2").attr("href",l2);
		
		var l4=$('#l4').attr("href");  
		var l4=l4+'&OpcDG='+texto;
		//asignamos
		$("#l4").attr("href",l4);
		
		var l5=$('#l5').attr("href");  
		var l5=l5+'&OpcDG='+texto;
		//asignamos
		$("#l5").attr("href",l5);
		
		var l6=$('#l6').attr("href");  
		var l6=l6+'&OpcDG='+texto;
		//asignamos
		$("#l6").attr("href",l6);
		//var ti=getParameterByName('ti');
		//alert(ti);
	  //document.getElementById("mienlace").innerHTML = texto;
	  //document.getElementById("mienlace").href = url;
	  //document.getElementById("mienlace").target = destino;
	} 
	function modificar1()
	{
		var ti=getParameterByName('ti');
		//alert(ti);
		if( ti=='BALANCE DE COMPROBACIÓN')
		{
			var l1=$('#l1').attr("href"); 
			patron = "contabilidad.php";
			nuevoValor    = "descarga.php";
			l1 = l1.replace(patron, nuevoValor);		
			//asignamos
			$("#l7").attr("href",l1+'&ex=1');
		}
		if( ti=='BALANCE MENSUAL')
		{
			var l1=$('#l2').attr("href"); 
			patron = "contabilidad.php";
			nuevoValor    = "descarga.php";
			l1 = l1.replace(patron, nuevoValor);		
			//asignamos
			$("#l7").attr("href",l1+'&ex=1');
		}
		if( ti=='ESTADO SITUACIÓN')
		{
			var l1=$('#l5').attr("href"); 
			patron = "contabilidad.php";
			nuevoValor    = "descarga.php";
			l1 = l1.replace(patron, nuevoValor);		
			//asignamos
			$("#l7").attr("href",l1+'&ex=1');
		}
		if( ti=='ESTADO RESULTADO')
		{
			var l1=$('#l6').attr("href"); 
			patron = "contabilidad.php";
			nuevoValor    = "descarga.php";
			l1 = l1.replace(patron, nuevoValor);		
			//asignamos
			$("#l7").attr("href",l1+'&ex=1');
		}
		
	}



	function reset_(idMensaje,tipoc)
	{
		$('#TP').val(tipoc);
		if(tipoc!='')
		{
			//creamos cookie
			//document.cookie = "tipoco=;";
			//if(readCookie('tipoco')==null)
			//{
			document.cookie = "tipoco=; ";
			document.cookie = "tipoco="+tipoc+"; ";
			//}
			//alert(' 1 '+readCookie('tipoco'));
			if(tipoc!='CD')
			{
				var element = document.getElementById("CD");
				element.classList.remove("active");
			}
			if(tipoc!='CI')
			{
				var element = document.getElementById("CI");
				element.classList.remove("active");
			}
			if(tipoc!='CE')
			{
				var element = document.getElementById("CE");
				element.classList.remove("active");
			}
			if(tipoc!='ND')
			{
				var element = document.getElementById("ND");
				element.classList.remove("active");
			}
			if(tipoc!='NC')
			{
				var element = document.getElementById("NC");
				element.classList.remove("active");
			}
			
			var select = document.getElementById('tipoc'); //El <select>
			select.value = tipoc;
		}
		//si ya esta la cookies verificamos para que este presionado
		//alert(' 2 '+readCookie('tipoco'));
		if(readCookie('tipoco')!=null)
		{
			var element = document.getElementById(readCookie('tipoco'));
			//element.classList.remove("active");
			element.classList.add('active');
			//myElemento.classList.add('nombreclase1','nombreclase2');
			if(readCookie('tipoco')!='CD')
			{
				var element = document.getElementById("CD");
				element.classList.remove("active");
			}
			if(readCookie('tipoco')!='CI')
			{
				var element = document.getElementById("CI");
				element.classList.remove("active");
			}
			if(readCookie('tipoco')!='CE')
			{
				var element = document.getElementById("CE");
				element.classList.remove("active");
			}
			if(readCookie('tipoco')!='ND')
			{
				var element = document.getElementById("ND");
				element.classList.remove("active");
			}
			if(readCookie('tipoco')!='NC')
			{
				var element = document.getElementById("NC");
				element.classList.remove("active");
			}
		}
	}

	function comprobante()
	{
		var parametros = 
		{
			'MesNo':$('#mes').val(),
			'TP':$('#tipoc').val(),
		}
		 $.ajax({
      data:  {parametros:parametros},
       url:   '../controlador/contabilidad/contabilidad_controller.php?comprobantes',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        	$('#ddl_comprobantes').html(response);
      }
    }); 
	}


	function listar_comprobante()
	{
      $('#myModal_espera').modal('show');
		reporte_comprobante();
		var parametros = 
		{
			'numero':$('#ddl_comprobantes').val(),
			'item':$('#txt_empresa').val(),
			'TP':$('#tipoc').val(),
		}
		 $.ajax({
      data:  {parametros:parametros},
       url:   '../controlador/contabilidad/contabilidad_controller.php?listar_comprobante',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        	if(response==2)
        	{
        		Swal.fire('El Comprobante no exite.','','info');
        	}else
        	{
        		$('#tbl_contabilidad').html(response.tbl1);      		
        		$('#tbl_retenciones').html(response.tbl2);       		
        		$('#tbl_retenciones_co').html(response.tbl2_1);  		
        		$('#tbl_retenciones_ve').html(response.tbl2_2);       		
        		$('#tbl_subcuentas').html(response.tbl3);        		
        		$('#tbl_kardex').html(response.tbl4);
        		$('#txt_debe').val(response.Debe);
        		$('#txt_haber').val(response.haber);        		
        		$('#txt_total').val(response.total);
        		$('#txt_saldo').val(response.saldo);
        		$('#beneficiario').val(response.beneficiario);
        		$('#Co').val(response.Co);

        	}
            $('#myModal_espera').modal('hide');

      }
    }); 
	}

	function reporte_comprobante()
	{

		var parametros = 
		{
			'comprobante':$('#ddl_comprobantes').val(),
		}
		 // $.ajax({
      // data:  {parametros:parametros},
       url=  '../controlador/contabilidad/comproC.php?reporte&comprobante='+$('#ddl_comprobantes').val();
      // type:  'post',
      // dataType: 'json',
        // success:  function (response) {
        	$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="'+url+'" frameborder="0" allowfullscreen></iframe>');

      // }
    // }); 


				// value1 = $('#ddl_comprobantes').val();
				// //alert(value1);
				// $.post('ajax/vista_ajax.php'
				// 	, {ajax_page: 'comp', com: value1 }, function(data){
				// 		//$('div.pdfcom').load(data);
				// 		$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
				// 		//alert('entrooo '+idMensaje+" ajax/TEMP/'+value1+'.pdf");
				// 	});
	}

	function modificar_comprobante()
	{
		var com = $('#ddl_comprobantes').val();
		if(com!='')
		{
		 $('#clave_contador').modal('show');
		 $('#titulo_clave').text('Contador General');
		 $('#TipoSuper').val('Contador');
	  }else
	  {
	  	Swal.fire('Seleccione un comprobante','','info');
	  }
	}

	// funcion de respuesta para la clave
	 function resp_clave_ingreso(response)
	 {
	 	 if(response['respuesta']==1)
	 	 {
	 	 	 confirmar_edicion(response);
	 	 }else
	 	 {

	 	 }
	 }
	 //------------------------------------

	 function confirmar_edicion(response)
	 {
	 	var ti = $('#tipoc').val();
	 	var be = $('#beneficiario').val(); 
	 	var co = $('#ddl_comprobantes').val();
	 	var va = $('#Co').val();
	 	 Swal.fire({
                 title: 'Esta seguro que quiere modificar el comprobante '+ti+ 'No. '+co+' de '+be,
                 text: "Esta usted seguro de que quiere modificar!",
                 type: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',
                 confirmButtonText: 'Si!'
               }).then((result) => {
                 if (result.value==true) {
                 	location.href='../vista/contabilidad.php?mod=contabilidad&acc=incom&acc1=Ingresar%20Comprobantes&b=1&modificar=1&variables='+va+'#';
                 }
               })
	 }

</script>
