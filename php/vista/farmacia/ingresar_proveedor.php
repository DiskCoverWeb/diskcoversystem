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
      <div class="panel-heading text-center"><b>INSERTAR PROVEEDOR</b></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-6">
            <b>Nombre:</b>
            <input type="text" name="txt_nombre" id="txt_nombre" class="form-control input-sm">
            <b>RUC / CI:</b>
            <input type="text" name="txt_ruc" id="txt_ruc" class="form-control input-sm">
            <b>Direccion:</b>
            <input type="text" name="txt_direccion" id="txt_direccion" class="form-control input-sm">
            <b>Localidad:</b>
            <input type="text" name="txt_localidad" id="txt_localidad" class="form-control input-sm">
            <b>Provincia:</b>
            <select class="form-control input-sm" id="ddl_provincia">
              <option>Seleccione una provincia</option>
            </select>            
            <b>Código postal:</b>
            <input type="text" name="txt_cod_postal" id="txt_cod_postal" class="form-control input-sm">
            <b>Teléfono:</b>
            <input type="text" name="txt_telefono" id="txt_telefono" class="form-control input-sm">
            <b>Móvil:</b>
            <input type="text" name="txt_movil" id="txt_movil" class="form-control input-sm">
            <b>Correo electónico:</b>
            <input type="text" name="txt_email" id="txt_email" class="form-control input-sm">
            <b>dirección Web:</b>
            <input type="text" name="txt_web" id="txt_web" class="form-control input-sm">            
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
