<?php  require_once("panel.php");?>

<div class="container-lg">
  <div class="row">
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" id="imprimir_pdf" title="Descargar PDF">
            <img src="../../img/png/impresora.png">
          </button>           
        </div>
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" id="imprimir_excel" title="Descargar Excel">
            <img src="../../img/png/table_excel.png">
          </button>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button title="Guardar"  class="btn btn-default" onclick="">
            <img src="../../img/png/grabar.png" >
          </button>
        </div>     
 </div>
</div>
<div class="container">
  <div class="row"><br>
     <div class="panel panel-primary">
      <div class="panel-heading text-center"><b>INSERTAR PACIENTE</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-6">
            <b>Nombres y apellidos :</b>
            <input type="text" name="txt_nombre" id="txt_nombre" class="form-control input-sm">
            <b>RUC / CI:</b>
            <input type="text" name="txt_ruc" id="txt_ruc" class="form-control input-sm">
            <b>Procedimiento:</b>
            <input type="text" name="txt_proce" id="txt_proce" class="form-control input-sm">
            <b>Departamento:</b>
            <input type="text" name="txt_departamento" id="txt_departamento" class="form-control input-sm">            
            <b>Habitacion:</b>
            <input type="text" name="txt_habitacion" id="txt_habitacion" class="form-control input-sm">           
          </div>
          <div class="col-sm-6">
            
          </div>          
        </div>
      </div>
    </div>
  </div>
  <div class="row">
     <div class="modal-footer">
        <button type="button" class="btn btn-primary"><i class="fa fa-check"></i> Aceptar</button>
        <button type="button" class="btn btn-info"><i class="fa fa-paint-brush"></i> Limpiar</button>
        <button type="button" class="btn btn-danger"><i class="fa fa-close"></i> Cancelar</button>
      </div>
  </div>   
</div>
