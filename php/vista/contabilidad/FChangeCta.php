
<div class="modal fade bd-example-modal" id="ModalChangeCa" tabindex="-1" role="dialog" aria-labelledby="ModalChangeCa" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Label6ModalChangeCa"><b>CAMBIO DE VALORES DE LA CUENTA</b></h5>
      </div>
      <div class="modal-body" style="padding-top: 0px;">
       	<div class="row">
					<div class="col-md-9">
						<div class="row">
							<div class="col-sm-12 text-center">
								<b id="cambiar_select"></b>
							</div>
						</div>
						<select class="form-control input-sm" id="DCCuentaChangeCa">
							<option>Seleccionar Cuenta</option>
						</select> 
						<input type="hidden" name="Codigo1" id="Codigo1ChangeCta">           
						<input type="hidden" name="Asiento" id="AsientoChangeCta">           
						<input type="hidden" name="Producto" id="ProductoChangeCta">           
						<input type="hidden" name="TP" id="TPChangeCta">           
						<input type="hidden" name="Numero" id="NumeroChangeCta">           
					</div>
					<div class="col-md-3 text-center">
						<div class="row">
							<div class="col-md-12 col-sm-6 col-xs-2">                
								<button type="button" class="btn btn-default" onclick="CambiarCta()">
									<img src="../../img/png/agregar.png"><br>
									Aceptar
								</button>
							</div>
							<div class="col-md-12 col-sm-6 col-xs-2">
								<br>
								<button type="button" class="btn btn-default" title="Cerrar" data-dismiss="modal">
									<img src="../../img/png/salire.png"><br>&nbsp; &nbsp;Salir&nbsp;&nbsp;&nbsp;
								</button>
							</div>              
						</div>
					</div>
				</div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	function Form_Activate_ModalChangeCa(Codigo1, Asiento, Producto, Codigo3, TP, Numero) {
		if(Codigo1 != '<?php echo G_NINGUNO ?>'){
			$("#Codigo1ChangeCta").val(Codigo1)
			$("#AsientoChangeCta").val(Asiento)
			$("#ProductoChangeCta").val(Producto)
			$("#TPChangeCta").val(TP)
			$("#NumeroChangeCta").val(Numero)
			$("#Label6ModalChangeCa").html("<b>"+Codigo3+"<br>SELECCIONE LA CUENTA A CAMBIAR</b>")
			$('#DCCuentaChangeCa').select2({
		        placeholder: 'Seleccionar Cuenta',
		        width:'90%',
		        ajax: {
		          url:   '../controlador/contabilidad/FChangeCtaC.php?CargarDCCuenta=true&Codigo1='+Codigo1,
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
		}else{
			Swal.fire({
		         title: 'No existe Cuenta para cambiar',
		         type: 'warning',
		         showCancelButton: false,
		         confirmButtonColor: '#3085d6',
		         cancelButtonColor: '#d33',
		         confirmButtonText: 'Ok'
		       }).then((result) => {
		         	$('#ModalChangeCa').modal('hide');
		       })
		}
	}

	function CambiarCta() {
		let Codigo1 = $("#Codigo1ChangeCta").val()
		let Asiento = $("#AsientoChangeCta").val()
		let Producto = $("#ProductoChangeCta").val()
		let TP = $("#TPChangeCta").val()
		let Numero = $("#NumeroChangeCta").val()
		let Codigo2 = $("#DCCuentaChangeCa").val()
		$('#myModal_espera').modal('show');
		$.ajax({
			url:   '../controlador/contabilidad/FChangeCtaC.php?CambiarCta=true',
			type:  'post',
			dataType: 'json',
			data:  {
				'Codigo1':Codigo1,
				'Asiento':Asiento,
				'Producto':Producto,
				'Codigo2':Codigo2,
				'TP': TP,
				'Numero': Numero
			},
			success:  function (response) {
				$('#myModal_espera').modal('hide');
				Swal.fire({
		         title: response.message,
		         type: response.ico,
		         showCancelButton: false,
		         confirmButtonColor: '#3085d6',
		         cancelButtonColor: '#d33',
		         confirmButtonText: 'Ok'
		       }).then((result) => {
		         	$('#ModalChangeCa').modal('hide');
		         	if(response.ico=='success' && typeof listar_comprobante === 'function' && listar_comprobante !== null && listar_comprobante !== undefined){
		         		listar_comprobante()
		         	}
		       })
			},
          	error: function (e) {
	            $('#myModal_espera').modal('hide');
	            alert("error inesperado al Cambiar la Cuenta")
          	}
		});

	}
</script>
