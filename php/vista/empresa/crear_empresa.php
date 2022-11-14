<div class="row">
    <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12">
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
                <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Grabar Empresa" onclick="boton1()"><img src="../../img/png/grabar.png"></button>
        </div>        
</div>

<div class="col-sm-12">
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-2">
                    <label>LISTA DE EMPRESAS</label>
                </div>
                <div class="col-sm-10">
                    <select class="form-control input-xs" id="" name="">
                            <option value="">Seleccione</option>
                    </select>
                </div>		
            </div>
        </div>
    </div>
</div>
            
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Datos Principales</a></li>
                        <li><a href="#tab_2" data-toggle="tab">Procesos Generales</a></li>
                        <li><a href="#tab_3" data-toggle="tab">Comprobantes Electrónicos</a></li>                        
                    </ul>
                    <div class="tab-content">
                        <!-- DATOS PRINCIPALES INICIO -->
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label>EMPRESA:</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="text" name="TxtEmpresa" id="TxtEmpresa" class="form-control input-xs" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <label>RAZON SOCIAL:</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="text" name="TxtEmpresa" id="TxtEmpresa" class="form-control input-xs" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <label>NOMBRE COMERCIAL:</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="text" name="TxtEmpresa" id="TxtEmpresa" class="form-control input-xs" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <label>RUC:</label>
                                    <input type="text" name="TxtRuc" id="TxtRuc" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-2">
                                    <label>OBLIG</label>
                                    <select class="form-control input-xs" id="" name="">
                                        <option value="">Seleccione</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>REPRESENTANTE LEGAL:</label>
                                    <input type="text" name="TxtRepresentanteLegal" id="TxtRepresentanteLegal" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-2">
                                    <label>C.I/PASAPORTE</label>
                                    <input type="text" name="TxtCI" id="TxtCI" class="form-control input-xs" value="">
                                </div>
                            </div>                    
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>NACIONALIDAD</label>
                                        <select class="form-control input-xs" id="" name="">
                                            <option value="">Seleccione</option>
                                        </select>
                                </div>
                                <div class="col-sm-4">
                                    <label>PROVINCIA</label>
                                        <select class="form-control input-xs" id="" name="">
                                            <option value="">Seleccione</option>
                                        </select>
                                </div>
                                <div class="col-sm-4">
                                    <label>CIUDAD</label>
                                        <select class="form-control input-xs" id="" name="">
                                            <option value="">Seleccione</option>
                                        </select>
                                </div>                        
                            </div>                    
                            <div class="row">
                                <div class="col-sm-10">
                                    <label>DIRECCION MATRIS:</label>
                                    <input type="text" name="TxtDirMatriz" id="TxtDirMatriz" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-2">
                                    <label>ESTA.</label>
                                    <input type="text" name="TxtEsta" id="TxtEsta" class="form-control input-xs" value="">
                                </div>                        
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <label>TELEFONO:</label>
                                    <input type="text" name="TxtTelefono" id="TxtTelefono" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-2">
                                    <label>TELEFONO 2:</label>
                                    <input type="text" name="TxtTelefono2" id="TxtTelefono2" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-1">
                                    <label>FAX:</label>
                                    <input type="text" name="TxtFax" id="TxtFax" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-1">
                                    <label>MONEDA</label>
                                    <input type="text" name="TxtMoneda" id="TxtMoneda" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-2">
                                    <label>NO. PATRONAL:</label>
                                    <input type="text" name="TxtNPatro" id="TxtNPatro" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-1">
                                    <label>COD.BANCO</label>
                                    <input type="text" name="TxtCodBanco" id="TxtCodBanco" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-1">
                                    <label>TIPO CAR.</label>
                                    <input type="text" name="TxtTipoCar" id="TxtTipoCar" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-2">
                                    <label>ABREVIATURA</label>
                                    <input type="text" name="TxtAbrevi" id="TxtAbrevi" class="form-control input-xs" value="Ninguna">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>EMAIL DE LA EMPRESA:</label>
                                    <input type="text" name="TxtEmailEmpre" id="TxtEmailEmpre" class="form-control input-xs" value="@">
                                </div>                        
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>EMAIL DE CONTABILIDAD:</label>
                                    <input type="text" name="TxtEmailConta" id="TxtEmailConta" class="form-control input-xs" value="@">
                                </div>
                                <div class="col-sm-4">
                                    <label>SEGURO DESGRAVAMEN %</label>
                                    <input type="text" name="TxtSegDes" id="TxtSegDes" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-2">
                                    <label>SUBDIR:</label>
                                    <input type="text" name="TxtSubdir" id="TxtSubdir" class="form-control input-xs" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                    <label>NOMBRE DEL CONTADOR</label>
                                    <input type="text" name="TxtNombConta" id="TxtNombConta" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-2">
                                    <label>RUC CONTADOR:</label>
                                    <input type="text" name="TxtRucConta" id="TxtRucConta" class="form-control input-xs" value="">
                                </div>
                            </div>
                        </div>
                        <!-- DATOS PRINCIPALES FIN -->
                        <!-- PROCESOS GENERALES INICIO -->
                        <div class="tab-pane" id="tab_2">                                
                                <div class="row">
                                    <div class="col-md-4">                                   
                                                <!-- setesos -->
                                                <label>|Seteos Generales|</label>
                                                <div class="checkbox">
                                                    <label><input type="checkbox">Agrupar Saldos Detalle Auxiliar de Submodulos</label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox">Modificar Facturas o Notas de Venta</label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox">Modificar Precio de Venta al Público</label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox">Imprimir Recibo de Caja en Facturación</label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox">Imprimir Medio Rol</label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox">Imprimir dos Roles Individuales por pagina</label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox">Procesar Detalle Auxiliar de Comprobantes</label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox">Registrar el IVA en el Asiento Contable</label>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox">Funciona como Matriz de Sucursales</label>
                                                </div>
                                    </div>
                                    <div class="col-md-4">                                        
                                                <label>LOGO TIPO</label>
                                                <input type="text" name="TxtXXXX" id="TxtXXXX" class="form-control input-xs" value="XXXXXXXXXX">
                                                <div class="form-group">                                        
                                                    <select multiple="" class="form-control">
                                                    <option>option 1</option>
                                                    <option>option 2</option>
                                                    <option>option 3</option>
                                                    <option>option 4</option>
                                                    <option>option 5</option>
                                                    </select>                                                
                                                </div>
                                    </div>
                                    <div class="col-md-4">                                        
                                            <div class="box-body">
                                                <textarea class="form-control" rows="3" placeholder="Enter ..."></textarea>                                    
                                            </div>                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>|Numeración de Comprobantes|</label><br>                                    
                                                    <input type="checkbox">Diarios por meses &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                    <input type="checkbox">Diarios secuenciales <BR>
                                                    <input type="checkbox">Ingresos por meses &nbsp&nbsp&nbsp&nbsp
                                                    <input type="checkbox">Ingresos secuenciales <BR>
                                                    <input type="checkbox">Egresos por meses &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                    <input type="checkbox">Egresos secuenciales <BR>
                                                    <input type="checkbox">N/D por meses &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                    <input type="checkbox">N/D secuenciales <BR>
                                                    <input type="checkbox">N/C por meses &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                    <input type="checkbox">N/C secuenciales                                                                                            
                                    </div>
                                    <div class="col-sm-6">
                                        <label>|Servidor de Correos|</label><BR>
                                        <label>Servidor SMTP</label>
                                        <input type="text" name="TxtRepresentanteLegal" id="TxtRepresentanteLegal" class="form-control input-xs" value="">                                    
                                        <div class="col-sm-8">
                                        <input type="checkbox">Autentificación &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                        <input type="checkbox">SSL &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                        <input type="checkbox">SECURE &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                        PUERTO                                    
                                    </div>
                                    <div class="col-sm-2">                                        
                                        <input type="text" name="" id="" class="form-control input-xs" value="">
                                    </div>
                                </div>
                                <div class="row">
                                    <img src="" alt="IMG">
                                </div>
                            </div>
                        </div>
                        <!-- PROCESOS GENERALES FIN -->
                        <!-- COMPROBANTES ELECTRONICOS INICIO-->
                        <div class="tab-pane" id="tab_3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>|Firma Electrónica|</label>
                                </div>                                
                                <div class="col-sm-4">                                    
                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
                                    Ambiente de Prueba
                                </div>
                                <div class="col-sm-4">
                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                    Ambiente de Producción
                                </div>
                                <div class="col-sm-2">
                                    CONTRIBUYENTE ESPECIAL                                    
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" name="TxtContriEspecial" id="TxtContriEspecial" class="form-control input-xs" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                        <label>WEBSERVICE SRI RECEPCION</label>
                                        <input type="text" name="TxtWebSRIre" id="TxtWebSRIre" class="form-control input-xs" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                        <label>WEBSERVICE SRI AUTORIZACIÓN</label>
                                        <input type="text" name="TxtWebSRIau" id="TxtWebSRIau" class="form-control input-xs" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                        <label>CERTIFICADO FIRMA ELECTRONICA (DEBE SER EN FORMATO DE EXTENSION P12</label>
                                        <input type="text" name="TxtEXTP12" id="TxtEXTP12" class="form-control input-xs" value="">
                                </div>
                                <div class="col-sm-2">
                                        <label>CONTRASEÑA:</label>
                                        <input type="text" name="TxtContraExtP12" id="TxtContraExtP12" class="form-control input-xs" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                        <label>EMAIL PARA PROCESOS GENERALES:</label>
                                        <input type="text" name="TxtEmailGE" id="TxtEmailGE" class="form-control input-xs" value="@">
                                </div>
                                <div class="col-sm-2">
                                        <label>CONTRASEÑA:</label>
                                        <input type="text" name="TxtContraEmailGE" id="TxtContraEmailGE" class="form-control input-xs" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                        <label>EMAIL PARA DOCUMENTOS ELECTRONICOS:</label>
                                        <input type="text" name="TxtEmaiElect" id="TxtEmaiElect" class="form-control input-xs" value="@">
                                </div>
                                <div class="col-sm-2">
                                        <label>CONTRASEÑA:</label>
                                        <input type="text" name="TxtContraEmaiElect" id="TxtContraEmaiElect" class="form-control input-xs" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                    <label><input type="checkbox">Enviar Copia de Email</label>                                        
                                    <input type="text" name="TxtCopiaEmai" id="TxtCopiaEmai" class="form-control input-xs" value="@">
                                </div>
                                <div class="col-sm-2">
                                        <label>RUC Operadora</label>
                                        <input type="text" name="TxtRUCOpe" id="TxtRUCOpe" class="form-control input-xs" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>LEYENDA AL FINAL DE LOS DOCUMENTOS ELECTRONICOS</label><br>
                                    Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: NUMERO_TELEFONO, o 
                                    escriba al correo EMAIL_EMPRESA; para Transferencia o Depósitos hacer en El Banco NOMBRE_BANCO a Nombre de REPRESENTANTE_LEGAL/CTA_AHR_CTE_NUMERO, a Nombre de RAZON_SOCIAL
                                </div>
                                <div class="col-sm-12">
                                    <label>LEYENDA AL FINAL DE LA IMPRESION EN LA IMPRESORA DE PUNTO DE VENTA DE DOCUMENTOS ELECTRONICOS</label><br>
                                    Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: NUMERO_TELEFONO
                                </div>
                            </div>
                        </div>
                        <!-- COMPROBANTES ELECTRONICOS FIN-->
                    </div>
                </div>
            </div>            