<?php ?>

<script type="text/javascript">
	
	$(document).ready(function () {
		$('#myModal').modal('show');
	})

	function reindexar()
	{		
		$('#myModal_espera').modal('show');
        $.ajax({
            url: '../controlador/contabilidad/reindexarC.php?reindexarT=true',
            type: 'POST',
            dataType: 'json',
            // data: { param: param },
            success: function (data) {
            	$('#myModal_espera').modal('hide');
            	if(data==1)
            	{
            		Swal.fire("Reindexado","","success");
            	}
            },
            error: function (error) {
                console.log(error);
                $('#myModal_espera').modal('hide');
            }
        });
	}
</script>

<div class="container">
	<div class="row">
		ss
	</div>
</div>

<div class="modal fade" id="myModal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" >
		<div class="modal-content">
			<div class="modal-header">
		  		<button type="button" class="close" data-dismiss="modal">&times;</button>
		  		<h4 class="modal-title"><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg"> Reindexar cuentas</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">							
					<div class="row">
						<div class="col-sm-12">
							<p  align='left'><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg">Reindexar cuentas</p>											
						</div>										
					</div>
				</div>
			</div>
			<div class="modal-footer" style="background-color: #fff;">
				<button id="btnCopiar" class="btn btn-primary" onclick='reindexar();'>Cambiar</button>
			    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>			  
	</div>
</div>
