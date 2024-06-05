<script type="text/javascript">
	$(document).ready(function () {
  		lista_egreso_checking();
  		areas();  
  		motivo_egreso()	
  	})
</script>
<div class="row mb-2">
    <div class="col-lg-4 col-sm-10 col-md-6 col-xs-12">
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
            print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-default">
                <img src="../../img/png/salire.png">
            </a>
        </div>
         <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button title="Guardar" class="btn btn-default" onclick="guardar()">
                <img src="../../img/png/grabar.png">
            </button>
        </div>
    </div>
       
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="box" style="background: #ccff99;">
			<div class="box-body">
				 <div class="col-sm-12">
				        <div class="row mb-2">            
				            <div class="col-sm-5">
				                <div class="row">                   
				                     <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b>Beneficiario/ Usuario:</b>
				                            </div>
				                             <select name="beneficiario" id="beneficiario" class="form-control input-xs" onchange="listaAsignacion()"></select>
				                        </div>
				                    </div>
				                </div>
				            </div>
				             <div class="col-sm-3">
				                <div class="row">                   
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">  
				                                <b> Estado</b>
				                            </div>
				                        <input type="tipoEstado" name="tipoEstado" id="tipoEstado" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				            </div>
				            <div class="col-sm-3">
				                <div class="row">
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">  
				                                <b> Tipo de Entrega</b>
				                            </div>
				                        <input type="text" name="tipoEntrega" id="tipoEntrega" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				            </div>

				            <div class="col-sm-3">
				                <div class="row">                   
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">                   
				                                <i class="fa fa-calendar"></i>
				                                <b>Fecha de Atención:</b>
				                            </div>
				                            <input type="date" name="fechAten" id="fechAten" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				            </div>
				            <div class="col-sm-3">
				                <div class="row">                    
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b>Día de Entrega</b>
				                            </div>
				                            <input type="text" name="diaEntr" id="diaEntr" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				            </div>
				            <div class="col-sm-3">
				                <div class="row">                    
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">  
				                                <b><i class="fa fa-clock-o"></i> Hora de Entrega</b>
				                            </div>
				                        <input type="time" name="horaEntrega" id="horaEntrega" class="form-control input-xs">
				                        </div>
				                    </div>
				                </div>
				            </div>
				            <div class="col-sm-3">
				                <div class="row">
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">  
				                                <b>Frecuencia</b>
				                            </div>
				                        <input type="text" name="frecuencia" id="frecuencia" class="form-control input-xs">
				                        </div>
				                    </div>
				                </div>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-4">
				                <div class="row">
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b> Tipo de Beneficiario:</b>
				                            </div>
				                        <input type="text" name="tipoBenef" id="tipoBenef" class="form-control input-xs" readonly>
				                        <span class="input-group-btn">
				                            <button type="button" class="">
				                                <img id="img_tipoBene"  src="../../img/png/cantidad_global.png" style="width: 20px;" />
				                            </button>
				                        </span>
				                        </div>
				                    </div>
				                </div>
				                <div class="row">                    
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b>Total, Personas Atendidas:</b>
				                            </div>
				                            <input type="text" name="totalPersAten" id="totalPersAten" class="form-control input-xs" readonly>
				                            <span class="input-group-btn">
				                            <button type="button" class="" onclick="llenarCamposPoblacion()">
				                                <img id="img_tipoBene"  src="../../img/png/Personas_atendidas.png" style="width: 32px;" />
				                            </button>
				                        </span>
				                        </div>
				                    </div>
				                </div>
				                <div class="row">                   
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b>Tipo de Población:</b>
				                            </div>
				                            <input type="text" name="tipoPobl" id="tipoPobl" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				                <div class="row">                    
				                     <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b>  Acción Social:</b>
				                            </div>
				                            <input type="text" name="acciSoci" id="acciSoci" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				                <div class="row">                   
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b> Vulnerabalidad:</b>
				                            </div>
				                            <input type="text" name="vuln" id="vuln" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				                <div class="row">                    
				                    <div class="col-md-12 col-sm-6 col-xs-6">  
				                        <div class="input-group">
				                            <div class="input-group-addon input-xs">
				                                <b> Tipo de Atención:</b>
				                            </div>
				                            <input type="text" name="tipoAten" id="tipoAten" class="form-control input-xs" readonly>
				                        </div>
				                    </div>
				                </div>
				            </div>
				            <div class="col-sm-4">
				                <div class="row">				                	
				                   <div class="col-sm-12 ">				                   
							               <div class="input-group input-group-sm">
							               		<div class="input-group-addon input-xs">
						                            <b>CANTIDAD:</b>
						                        </div>

												<input type="text" class="form-control input-xs">
							               		<div class="input-group-addon input-xs">
						                            <b>Dif:</b>
						                        </div>
												<input type="text" class="form-control input-xs">
												<span class="input-group-btn">
													<button type="button" class="btn btn-info btn-flat btn-sm"><i class="fa fa-eye"></i> Ver detalle</button>
												</span>
											</div>
									</div>					                   
				                </div>
				               
				                <div class="row">
				                	 <div class="col-sm-12 ">
					                	 <div class="input-group input-group-sm">
						               		<div class="input-group-addon input-xs">
					                            <b>Información Nutricional</b>
					                        </div>
					                           <textarea name="infoNutr" id="infoNutr" rows="4" class="form-control input-xs">
					                        </textarea>
										</div>	
									</div>			                   
				                </div>
				                  <div class="row">
				                	 <div class="col-sm-12 ">
					                	 <div class="input-group input-group-sm">
						               		<div class="input-group-addon input-xs">
					                            <b>Comentario de asignacion</b>
					                        </div>
					                           <textarea name="infoNutr" id="infoNutr" rows="4" class="form-control input-xs">
					                        </textarea>
										</div>	
									</div>			                   
				                </div>
				            </div>
				            <div class="col-sm-4">
								<div class="row">	
					              <div class="col-sm-12">
					                  <b>Responsable de asignacion</b><br> 
					                  <div class="input-group">
					                      <input type="text" name="txt_responsable" id="txt_responsable" value="" class="form-control input-xs" readonly>
					                      <span class="input-group-btn">
					                        <button type="button" class="btn btn-warning btn-flat btn-xs" onclick="nueva_notificacion()"><i class="fa  fa-envelope"></i></button>
					                      </span>
					                  </div>
					              </div>	
					            </div>  	
		            			<hr style="margin: 5px 0 5px 0;">          
					            <div class="row"> 					
									<div class="col-sm-12">
										<div class="row text-center">
											<div class="col-sm-6">
												<label style="color:green" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" checked  value="V" > <img src="../../img/png/smile.png"><br> Conforme</label>											
											</div>
											<div class="col-sm-6">
												<label style="color:red" onclick="ocultar_comentario()"><input type="radio" name="cbx_evaluacion" value="R">  <img src="../../img/png/sad.png"><br> Inconforme </label>											
											</div>
		                  
										</div>
									</div>
					            </div>            
					            <div class="row"> 
									<div class="col-sm-12" id="pnl_comentario">
					                  <div class="input-group">
					                      <textarea class="form-control input-sm" rows="3" style="font-size:16px" id="txt_comentario2" name="txt_comentario2" placeholder="COMENTARIO DE PICKING"></textarea>
					                      <span class="input-group-btn">
					                        <button type="button" class="btn btn-primary btn-sm" onclick="comentar()"><i class="fa fa-save"></i></button>   
					                      </span>
					                  </div>
									</div>
								</div>
         
				            </div>
				        </div>
				    </div>
			</div>
		</div>
	</div>
</div>