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
	     <a class="btn btn-default" title="Salir del modulo" href="./contabilidad.php?mod=<?php echo $_SESSION['INGRESO']['modulo_']; ?>">
	         <img src="../../img/png/salire.png">
	     </a>
	   </div>    
	   <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
	       <a class="btn btn-default" title="Exportar Excel"	href="javascript:void(0)" onclick="GenerarExcelResultadoComprobante()" ><img src="../../img/png/table_excel.png"></a>	      
	   </div>
	   <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">                 
	    <button class="btn btn-default" title="Modificar el comprobante" onclick="IngClave('Contador')">
					 <img src="../../img/png/modificar.png" >
	     </button>		   
	  </div>
	  <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
	      <button type="button" id='l2' class="btn btn-default" title="Anular comprobante" onclick="anular_comprobante()"><img src="../../img/png/anular.png" >
				</button>
	  </div>
	  <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
	    <a id='l3' class="btn btn-default" title="Autorizar comprobante autorizado">
					<img src="../../img/png/autorizar.png" > 
				</a>
	  </div>
	  <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
	      <a id='l4' class="btn btn-default" title="Realizar una copia al comprobante" href="contabilidad.php?mod=<?php echo $_SESSION['INGRESO']['modulo_'];?>&acc=bacsg&acc1=Presenta balance de Comprobación&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
					<img src="../../img/png/copiar.png" > 
				             </a>
	  </div>
	  <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1" style="width: fit-content;padding: 0px;">
	     <a id='l5' class="btn btn-default" title="Copiar a otra empresa el comprobante" href="contabilidad.php?mod=<?php echo $_SESSION['INGRESO']['modulo_'];?>&acc=bacsg&acc1=Presenta estado de situación (general)&ti=ESTADO SITUACIÓN&Opcb=5&Opcen=1&b=0">
					<img src="../../img/png/copiare.png" > 
				</a>		
	  </div>   	
	</div>
	
	<div class="col-sm-4">
		<br>
		<?php echo $_SESSION['INGRESO']['item']; ?> 
		<div class="btn-group btn-group-toggle" data-toggle="buttons">
		  <label class="btn btn-primary btn-sm active">
		    <input type="radio" name="options" id="CD" value="CD" autocomplete="off" checked onchange="comprobante();"> Diario
		  </label>
		  <label class="btn btn-primary btn-sm">
		    <input type="radio" name="options" id="CI" value="CI" autocomplete="off" onchange="comprobante();"> Ingresos
		  </label>
		  <label class="btn btn-primary btn-sm">
		    <input type="radio" name="options" id="CE" value="CE" autocomplete="off" onchange="comprobante();"> Egresos
		  </label>
		   <label class="btn btn-primary btn-sm">
		    <input type="radio" name="options" id="ND" value="ND" autocomplete="off" onchange="comprobante();"> N/D
		  </label>
		   <label class="btn btn-primary btn-sm">
		    <input type="radio" name="options" id="NC" value="NC" autocomplete="off" onchange="comprobante();"> N/C
		  </label>
	 		<input id="tipoc" name="tipoc" type="hidden" value="CD">					
		<input type="hidden" name="TipoProcesoLlamadoClave" id="TipoProcesoLlamadoClave">
		</div>
	</div>	
	<div class="col-sm-3">
		<br>
		<div class="row">
  		<div class="col-sm-5" style="padding:0px">
  			<select class="form-control input-xs" name="tipo" id='mes' onchange="comprobante()">
				   <option value='0'>Todos</option><?php echo  Tabla_Dias_Meses();?>
			  </select>			      			
  		</div>
  		<div class="col-sm-7">
  			 <select class="form-control input-xs" name="ddl_comprobantes" id="ddl_comprobantes" onchange="listar_comprobante()">
		    	<option value="">Seleccione</option>
		    </select>			      			
  		</div>			      		
  	</div>		
	</div>
</div>
<br>
<div class="row">	
		<div class="col-sm-3">    
       <div class="input-group">
         <div class="input-group-addon input-xs btn btn-info" onclick="BtnFechaClick()" style="background-color:#00c0ef">
           <b>FECHA:</b>
         </div>
         <input type="date" class="form-control input-xs" id="MBFecha" placeholder="01/01/2019" value="<?php echo date("Y-m-d") ?>" maxlength="10" size="15" disabled onblur="MBFecha_LostFocus()">
       </div>
	  </div>
		<div class="col-sm-6 text-center">
			<div class="input-group">
         <div class="input-group-addon input-xs">
           <b id="LabelEst">Normal</b>
         </div>       
       </div>

       	<!-- <label>Normal</label> -->
		</div>
		<div class="col-sm-3">    
       <div class="input-group">
         <div class="input-group-addon input-xs">
           <b>CANTIDAD:</b>
         </div>
         <input type="" class="form-control input-xs" id="LabelCantidad">
       </div>
	  </div>
</div>
<div class="row">
	<div class="col-sm-9">    
       <div class="input-group">
         <div class="input-group-addon input-xs">
           <b>PAGADO A:</b>
         </div>
         <input type="text" class="form-control input-xs" id="LabelRecibi">
       </div>
	  </div>	
	  <div class="col-sm-3">    
       <div class="input-group">
         <div class="input-group-addon input-xs">
           <b>EFECTIVO:</b>
         </div>
         <input type="text" class="form-control input-xs" id="LabelFormaPago" >
       </div>
	  </div>	
</div>
<div class="row">
	<div class="col-sm-12">    
       <div class="input-group">
         <div class="input-group-addon input-xs">
           <b>POR CONCEPTO DE :</b>
         </div>
         <!-- <input type="text" class="form-control input-xs" id="LabelFormaPago" > -->
         <textarea id="LabelConcepto" class="form-control input-xs"></textarea>
       </div>
	  </div>		
</div>
<div class="row">
		<input type="hidden" name="" id="txt_empresa" value="<?php echo $_SESSION['INGRESO']['item'];?>">
		<input type="hidden" name="" id="TP" value="CD">
		<!-- <input type="hidden" name="" id="beneficiario" value=""> -->
		<input type="hidden" name="" id="Co" value="">
		<!-- <input type="hidden" name="" id="Concepto" value=""> -->
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
                 <div class="tab-content" id="myTabContent">
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
                   	 	<div class="col-sm-1" style="padding:0px;">
                   	 		<b>Total compra</b>
                   	 	</div> 
                   	 	<div class="col-sm-2">
                   	 		<input type="text" name="txt_total" readonly="" class="form-control input-sm" id="txt_total">
                   	 	</div> 
                   	 	<div class="col-sm-1" style="padding:0px;">
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
	  <input type="text" id="LabelUsuario" name="LabelUsuario" readonly="" class="form-control input-sm">		
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

<div id="myModal_anular" class="modal fade myModalNuevoCliente" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Formulario de Anulacion</h4>
            </div>
            <div class="modal-body">
            	<div class="row">
            		<div class="col-sm-12">
            			<b>Motivo de la anulacion</b>
            			<input type="" name="" id="txt_motivo_anulacion" class="form-control input-sm">
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="anular_comprobante_procesar()">Aceptar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
  </div>


<?php include_once("FChangeCta.php") ?>
<?php include_once("FChangeValores.php") ?>

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



	function comprobante()
	{
		var tp = $('input[name="options"]:checked').val();
		$('#tipoc').val(tp);
		console.log(tp);
		var parametros = 
		{
			'MesNo':$('#mes').val(),
			'TP':tp,
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
        		$('#LabelRecibi').val(response.beneficiario);
        		$('#Co').val(response.Co);
        		$('#MBFecha').val(response.Co.fecha);
        		$('#LabelConcepto').val(response.Co.Concepto);
        		$('#LabelCantidad').val(response.Debe);
        		$('#LabelFormaPago').val(response.Co.Efectivo);
        		$('#LabelUsuario').val(response.Nombre_Completo);
        		if(response.Co.T=='A')
        		{
        			$('#LabelEst').text('ANULADO');
        		}else
        		{
        			$('#LabelEst').text('NORMAL');
        		}
        		console.log(response);

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
       url=  '../controlador/contabilidad/comproC.php?reporte&comprobante='+$('#ddl_comprobantes').val()+'&TP='+$('#tipoc').val();
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
		$("#TipoProcesoLlamadoClave").val("");
		if(com!='')
		{
		 /*$('#clave_contador').modal('show');
		 $('#titulo_clave').text('Contador General');
		 $('#TipoSuper').val('Contador');*/
		 IngClave('Contador');
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
	 	 	 if($("#TipoProcesoLlamadoClave").val() =="ModalChangeCa"){
	 	 	 		$('#ModalChangeCa').modal('show');
	 	 	 }else if($("#TipoProcesoLlamadoClave").val() =="ModalChangeValores"){
					$('#ModalChangeValores').modal('show')
	 	 	 }else{
	 	 	 	confirmar_edicion(response);
	 	 	 }
	 	 }else
	 	 {

	 	 }
	 }
	 //------------------------------------

	 function confirmar_edicion(response)
	 {
	 	var ti = $('#tipoc').val();
	 	var be = $('#LabelRecibi').val(); 
	 	var co = $('#ddl_comprobantes').val();
	 	var va = $('#Co').val();
	 	var mod = '<?php echo $_SESSION['INGRESO']['modulo_']; ?>';
	 	 Swal.fire({
         title: 'Esta seguro que quiere modificar el comprobante '+ti+' No. '+co+' de '+be,
         text: "Esta usted seguro de que quiere modificar!",
         type: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Si!'
       }).then((result) => {
         if (result.value==true) {
         	// location.href='../vista/contabilidad.php?mod='+mod+'&acc=incom&acc1=Ingresar%20Comprobantes&b=1&modificar=1&variables='+va+'#';
         	location.href='../vista/contabilidad.php?mod='+mod+'&acc=incom&acc1=Ingresar%20Comprobantes&b=1&modificar=1&TP='+ti+'&com='+co+'&num_load=1#';
         }
       })
	 }

	 function anular_comprobante()
	 {
	 	Swal.fire({
			  title: 'Seguro de Anular El Comprobante No. '+$('#tipoc').val()+' - '+$('#ddl_comprobantes').val(),
			  // text: "You won't be able to revert this!",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'SI'
			}).then((result) => {
			  if (result.value) {
			  	$('#myModal_anular').modal('show');			  	
			  }
			})			
	 }

	 function anular_comprobante_procesar()
	 {
	 		$('#myModal_espera').modal('show');
	    var parametros = 
				{	
					'numero':$('#ddl_comprobantes').val(),
					'item':$('#txt_empresa').val(),
					'TP':$('#tipoc').val(),		
					'Fecha':$('#MBFecha').val(),
					'Concepto':$('#LabelConcepto').val(),
					'Motivo_Anular':$('#txt_motivo_anulacion').val(),
				}
				 $.ajax({
		      data:  {parametros:parametros},
		       url:   '../controlador/contabilidad/comproC.php?anular_comprobante=true',
		      type:  'post',
		      dataType: 'json',
		        success:  function (response) {
		        	$('#myModal_espera').modal('hide');
		        	$('#myModal_anular').modal('hide');	
		        	setTimeout(listar_comprobante, 1000);
		      }
		    }); 
	 }

	 function BtnFechaClick(){
      Swal.fire({
        title: 'PREGUNTA DE MODIFICACION',
        text: "Seguro desea cambiar la Fecha del Comprobante",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SI'
      }).then((result) => {
        if (result.value) {
          $('#MBFecha').removeAttr('disabled');
          FechaTemp = $('#MBFecha').val();
          $('#MBFecha').focus();          
        }else{
          $('#MBFecha').attr('disabled','disabled');
        }
      })  
   }

   function MBFecha_LostFocus() {
      if(FechaTemp != $('#MBFecha').val()){
        Swal.fire({
          title: 'PREGUNTA DE MODIFICACION',
          text: "Seguro de realizar el cambio",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'SI'
        }).then((result) => {
          if (result.value) {
            $('#myModal_espera').modal('show');
            $.ajax({
              url:   '../controlador/contabilidad/comproC.php?ActualizarFechaComprobante=true',
              type:  'post',
              data: {
                'MBFecha': $("#MBFecha").val(),
                'Numero': $("#ddl_comprobantes").val(),
                'TP': $('input[name="options"]:checked').val(),
              },
              dataType: 'json',
              success:  function (response) {
                Swal.fire('Proceso terminado con exito, vuelva a listar el comprobante','','info');
                $('#MBFecha').attr('disabled','disabled');
                $('#myModal_espera').modal('hide');
              }
            }); 

          }else{
            $('#MBFecha').focus();
          }
        })
      }
   }

  function Eliminar_Cuenta(Cta, CuentaBanco, Debe, Haber, Asiento) {
   	Swal.fire({
      title: 'PREGUNTA DE ELIMINACION',
      text: "Esta seguro de eliminar la cuenta: "+Cta+' '+CuentaBanco,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'SI'
    }).then((result) => {
      if (result.value) {
        $('#myModal_espera').modal('show');
        $.ajax({
          url:   '../controlador/contabilidad/comproC.php?Eliminar_Cuenta=true',
          type:  'post',
          data: {
            'Cta': Cta,
            'Asiento': Asiento,
            'Numero': $("#ddl_comprobantes").val(),
            'TP': $('input[name="options"]:checked').val(),
          },
          dataType: 'json',
          success:  function (response) {
          	$('#myModal_espera').modal('hide');
            Swal.fire('Proceso terminado con exito, vuelva a listar el comprobante','','info');
          }
        }); 

      }else{
        $('#MBFecha').focus();
      }
    })
  }
  
	 function Cambiar_Cuenta(Codigo1, Cuenta, Asiento) {
  	$("#TipoProcesoLlamadoClave").val("ModalChangeCa");
  	/*$('#clave_contador').modal('show');
		$('#titulo_clave').text('Contador General');
		$('#TipoSuper').val('Contador');*/
		IngClave('Contador');

  	let Codigo3 = Codigo1+' - '+Cuenta;
  	let Producto = "Transacciones";
  	let TP = $('#tipoc').val();
  	let Numero = $('#ddl_comprobantes').val();
  	Form_Activate_ModalChangeCa(Codigo1, Asiento, Producto, Codigo3, TP, Numero)
  }

  function Cambiar_Valores(Cta, Cuenta_No, Debe, Haber, NomCtaSup, NoCheque, Asiento) {  	
  	$("#TipoProcesoLlamadoClave").val("ModalChangeValores");
  	/*$('#clave_contador').modal('show');
		$('#titulo_clave').text('Contador General');
		$('#TipoSuper').val('Contador');*/
		IngClave('Contador');
  	;
  	let NomCta = $("#LabelConcepto").val();
  	let TP = $('#tipoc').val();
  	let Numero = $('#ddl_comprobantes').val();
  	let Fecha = $('#MBFecha').val();
  	Form_Activate_ModalChangeValores(NomCta, Cta, Cuenta_No, Debe, Haber, NomCtaSup, NoCheque, Asiento, TP, Numero, Fecha)
  }

  function GenerarExcelResultadoComprobante() {
  	url = '../controlador/contabilidad/comproC.php?ExcelResultadoComprobante=true&Numero='+$('#ddl_comprobantes').val()+'&fecha='+$('#MBFecha').val()+'&concepto='+$("#LabelConcepto").val();
    window.open(url, '_blank');
  }
</script>
