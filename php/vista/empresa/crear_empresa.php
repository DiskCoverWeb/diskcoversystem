<script>
buscar_empresas();
// TraerCheqCopiarEmpresa();
$(document).ready(function()
{
    autocompletar();
    // autocompletarCempresa();
    informacion_empresa();
    
    naciones();
    provincias();
    ciudad(17);
    MostrarUsuClave();
    MostrarEmpresaCopia();
    AmbientePrueba();
});
function autocompletar(){
        $('#select_empresa').select2({
        placeholder: 'Seleccionar empresa',
        ajax: {
            url: '../controlador/empresa/crear_empresaC.php?empresas=true',
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
}
function buscar_empresas() 
{
    var option ="<option value=''>Seleccione empresa</option>";
    $.ajax({
        url:'../controlador/empresa/crear_empresaC.php?empresas=true',
        type:'post',
        dataTye:'jason',
        success: function(response){
            response.forEach(function(data,index){
                option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
            });
            $('#select_empresa').html(option);
            console.log(response);
        }
    });
}

function MostrarUsuClave() 
{
    if($('#AsigUsuClave').prop('checked'))
    {
        $('#TxtUsuario').css('display','block');
        $('#lblUsuario').css('display','block');
        $('#TxtClave').css('display','block');
        $('#lblClave').css('display','block');
        TraerUsuClave();
    }else
    {
        $('#TxtUsuario').css('display','none');
        $('#lblUsuario').css('display','none');
        $('#TxtClave').css('display','none');
        $('#lblClave').css('display','none');
    }
}
function MostrarEmpresaCopia()
{//CheqCopiiarEmpresa_Click
    if($('#CopSeEmp').prop('checked'))
    {
        $('#ListaCopiaEmpresa').css('display','block');
        autocompletarCempresa();
        TraerCheqCopiarEmpresa();
    }else
    {
        $('#ListaCopiaEmpresa').css('display','none');
    }
}
function TraerUsuClave()
{
    var form = $('#TxtCI').val();
        $.ajax({
            data:{form:form},//son los datos que se van a enviar por $_POST
            url: '../controlador/empresa/crear_empresaC.php?traer_usuario=true',//los datos hacia donde se van a enviar el envio por url es por GET
            type:'post',//envio por post
            dataType:'json',
            success: function(response){
                console.log(response);
                $('#TxtUsuario').val(response[0]['Usuario']);
                $('#TxtClave').val(response[0]['Clave']);
            }
        });
}
function autocompletarCempresa(){
        $('#ListaCopiaEmpresa').select2({
        placeholder: 'Seleccionar copia empresa',
        ajax: {
            url: '../controlador/empresa/crear_empresaC.php?Copiarempresas=true',
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
}
function TraerCheqCopiarEmpresa()
{
    var option ="<option value=''>Seleccione empresa</option>";
    var Nomempresa = $('#Txtempresa').val();
    $.ajax({
        data:{Nomempresa:Nomempresa},
        url:'../controlador/empresa/crear_empresaC.php?Copiarempresas=true',
        type:'post',
        dataTye:'jason',
        success: function(response){
            response.forEach(function(data,index){
                option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
            });
            $('#ListaCopiaEmpresa').html(option);
            console.log(response);
        }
    });
}
function naciones()
{
    var option ="<option value=''>Seleccione Pais</option>"; 
    $.ajax({
        url: '../controlador/empresa/crear_empresaC.php?naciones=true',
        type:'post',
        dataType:'json',
        // data:{usu:usu,pass:pass},
        beforeSend: function () {
            $("#prov").html("<option value=''>OTRO</option>");
            $("#ddl_ciudad").html("<option value=''>Seleccione provincia</option>");
        },
        success: function(response){
            response.forEach(function(data,index){
                option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
            });
            $('#ddl_naciones').html(option);
            $('#ddl_naciones').val('593');
            provincias('593');
            console.log(response);
        }
    });
}

function provincias(pais)
  {
   var option ="<option value=''>Seleccione Provincia</option>"; 
     $.ajax({
      url: '../controlador/empresa/crear_empresaC.php?provincias=true',
      type:'post',
      dataType:'json',
     data:{pais:pais},
      beforeSend: function () {
                   $("#ddl_ciudad").html("<option value=''>Seleccione provincia</option>");
             },
      success: function(response){
      response.forEach(function(data,index){
        option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
      });
        $('#prov').html(option);       
        $('#prov').val(17);
      console.log(response);
    }
    });

  }

  function ciudad(idpro)
	{
		// console.log(idpro);
		var option ="<option value=''>Seleccione Ciudad</option>"; 
		if(idpro !='')
		{
	   $.ajax({
		  url: '../controlador/empresa/crear_empresaC.php?ciudad=true',
		  type:'post',
		  dataType:'json',
		  data:{idpro:idpro},
		  success: function(response){
			response.forEach(function(data,index){
				option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
			});
            $('#ddl_ciudad').html(option);
            $('#ddl_ciudad').val(21701);
			console.log(response);
		}
	  });
	 } 

	}
function informacion_empresa()
{
    var id =$('#TxtEmpresa').val();    
    parametros =
    {
        'id':id,
    }    
        $.ajax({
            data:{parametros:parametros},
            url: '../controlador/empresa/crear_empresaC.php?informacion_empre=true',
            type:'post',
            dataType:'json',            
            success: function(response){
                // console.log(response);                
            }
        });
}
function formulario()
{
    // var form = $('#formulario').serialize();
    var form = $('#TxtCI').val();
        $.ajax({
            data:{form:form},//son los datos que se van a enviar por $_POST
            url: '../controlador/empresa/crear_empresaC.php?usuario=true',//los datos hacia donde se van a enviar el envio por url es por GET
            type:'post',//envio por post
            dataType:'json',
            success: function(response){
                console.log(response);
                $('#TxtUsuario').val(response[0]['Usuario']);
                $('#TxtClave').val(response[0]['Clave']);
                // console.log(response[0].id);
                // $('#TxtEmpresa').val(response[0]['Empresa']);
                // $('#TxtRazonSocial').val(response[0]['Razon_Social']);
                // $('#TxtNomComercial').val(response[0]['Nombre_Comercial']);                
                // $('#TxtRuc').val(response[0]['RUC']);
                // $('#TxtRepresentanteLegal').val(response[0]['Gerente']);
                // $('#TxtCI').val(response[0]['CI_Representante']);
                // $('#TxtDirMatriz').val(response[0]['Direccion']);
                // $('#TxtEsta').val(response[0]['Establecimientos']);
                // $('#TxtTelefono').val(response[0]['Telefono1']);
                // $('#TxtTelefono2').val(response[0]['Telefono2']);
                // $('#TxtFax').val(response[0]['FAX']);
                // $('#TxtMoneda').val(response[0]['S_M']);
                // $('#TxtNPatro').val(response[0]['No_Patronal']);
                // $('#TxtCodBanco').val(response[0]['CodBanco']);
                // $('#TxtTipoCar').val(response[0]['Tipo_Carga_Banco']);
                // $('#TxtAbrevi').val(response[0]['Abreviatura']);
                // $('#TxtEmailEmpre').val(response[0]['Email']);
                // $('#TxtEmailConta').val(response[0]['Email_Contabilidad']);
                // $('#TxtEmailRespa').val(response[0]['Email_Respaldos']);
                // $('#TxtSegDes').val(response[0]['Seguro']);
                // $('#TxtSubdir').val(response[0]['SubDir']);
                // $('#TxtNombConta').val(response[0]['Contador']);
                // $('#TxtRucConta').val(response[0]['RUC_Contador']);
            }
        });
}
function llamar()
{
    var item = $('#select_empresa').val();
    $.ajax({
            data:{item:item},//son los datos que se van a enviar por $_POST
            url: '../controlador/empresa/crear_empresaC.php?llamar=true',//los datos hacia donde se van a enviar el envio por url es por GET
            type:'post',//envio por post
            dataType:'json',
            success: function(response){
                console.log(response);
                $('#TxtItem').val(response[0]['Item']);
                $('#TxtEmpresa').val(response[0]['Empresa']);
                $('#TxtRazonSocial').val(response[0]['Razon_Social']);
                $('#TxtNomComercial').val(response[0]['Nombre_Comercial']);                
                $('#TxtRuc').val(response[0]['RUC']);
                $('#TxtRepresentanteLegal').val(response[0]['Gerente']);
                $('#TxtCI').val(response[0]['CI_Representante']);
                $('#TxtDirMatriz').val(response[0]['Direccion']);
                $('#TxtEsta').val(response[0]['Establecimientos']);
                $('#TxtTelefono').val(response[0]['Telefono1']);
                $('#TxtTelefono2').val(response[0]['Telefono2']);
                $('#TxtFax').val(response[0]['FAX']);
                $('#TxtMoneda').val(response[0]['S_M']);
                $('#TxtNPatro').val(response[0]['No_Patronal']);
                $('#TxtCodBanco').val(response[0]['CodBanco']);
                $('#TxtTipoCar').val(response[0]['Tipo_Carga_Banco']);
                $('#TxtAbrevi').val(response[0]['Abreviatura']);
                $('#TxtEmailEmpre').val(response[0]['Email']);
                $('#TxtEmailConta').val(response[0]['Email_Contabilidad']);
                $('#TxtEmailRespa').val(response[0]['Email_Respaldos']);
                $('#TxtSegDes1').val(response[0]['Seguro']);
                $('#TxtSegDes2').val(response[0]['Seguro2']);
                $('#TxtSubdir').val(response[0]['SubDir']);
                $('#TxtNombConta').val(response[0]['Contador']);
                $('#TxtRucConta').val(response[0]['RUC_Contador']);
                if(response[0]['CPais']!=0)
                {
                    $("#ddl_naciones").val(response[0]['CPais']);
                }
                if(response[0]['Prov']==response[0]['CPais'])
                {
                    $("#prov").val(response[0]['Prov']);
                }
                if(response[0]['Ciudad']==response[0]['Prov'])
                {
                    $("#ddl_ciudad").val(response[0]['Ciudad']);
                }
                $('#TxtServidorSMTP').val(response[0]['smtp_Servidor']);//mail.diskcoversystem.com
                $('#TxtPuerto').val(response[0]['smtp_Puerto']);
                $('#TxtPVP').val(response[0]['Dec_PVP']);
                $('#TxtCOSTOS').val(response[0]['Dec_Costo']);
                $('#TxtIVA').val(response[0]['Dec_IVA']);
                $('#TxtCantidad').val(response[0]['Dec_Cant']);
                $('#TxtContriEspecial').val(response[0]['Codigo_Contribuyente_Especial']);
                if(response[0]['Ambiente'] == 1)
                {
                    $('#optionsRadios1').prop('checked',true);
                }else if(response[0]['Ambiente'] == 2)
                {
                    $('#optionsRadios2').prop('checked',true);
                }
                $('#TxtWebSRIre').val(response[0]['Web_SRI_Recepcion']);
                $('#TxtWebSRIau').val(response[0]['Web_SRI_Autorizado']);
                $('#TxtEXTP12').val(response[0]['Ruta_Certificado']);
                $('#TxtContraExtP12').val(response[0]['Clave_Certificado']);
                $('#TxtEmailGE').val(response[0]['Email_Conexion']);
                $('#TxtContraEmailGE').val(response[0]['Email_Contraseña']);
                $('#TxtEmaiElect').val(response[0]['Email_Conexion_CE']);
                $('#TxtContraEmaiElect').val(response[0]['Email_Contraseña_CE']);
                $('#TxtCopiaEmai').val(response[0]['Email_Procesos']);
                $('#TxtRUCOpe').val(response[0]['RUC_Operadora']);
                $('#txtLeyendaDocumen').val(response[0]['LeyendaFA']);
                $('#txtLeyendaImpresora').val(response[0]['LeyendaFAT']);
                
                if(response[0]['Det_SubMod']!=0)
                {
                    $('#ASDAS').prop('checked',true);
                }else if(response[0]['Det_SubMod']==0)
                {
                    $('#ASDAS').prop('checked',false);
                }

                if(response[0]['Mod_Fact']!=0)
                {
                    $('#MFNV').prop('checked',true);
                }else if(response[0]['Mod_Fact']==0)
                {
                    $('#MFNV').prop('checked',false);
                }

                if(response[0]['Mod_PVP']!=0)
                {
                    $('#MPVP').prop('checked',true);
                }else if(response[0]['Mod_PVP']==0)
                {
                    $('#MPVP').prop('checked',false);
                }

                if(response[0]['Imp_Recibo_Caja']!=0)
                {
                    $('#IRCF').prop('checked',true);
                }else if(response[0]['Imp_Recibo_Caja']==0)
                {
                    $('#IRCF').prop('checked',false);
                }

                if(response[0]['Medio_Rol']!=0)
                {
                    $('#IMR').prop('checked',true);
                }else if(response[0]['Medio_Rol']==0)
                {
                    $('#IMR').prop('checked',false);
                }

                if(response[0]['Rol_2_Pagina']!=0)
                {
                    $('#IRIP').prop('checked',true);
                }else if(response[0]['Rol_2_Pagina']==0)
                {
                    $('#IRIP').prop('checked',false);
                }

                if(response[0]['Det_Comp']!=0)
                {
                    $('#PDAC').prop('checked',true);
                }else if(response[0]['Det_Comp']==0)
                {
                    $('#PDAC').prop('checked',false);
                }

                if(response[0]['Registrar_IVA']!=0)
                {
                    $('#RIAC').prop('checked',true);
                }else if(response[0]['Registrar_IVA']==0)
                {
                    $('#RIAC').prop('checked',false);
                }

                // if(response[0]['Sucursal']!=0)
                // {
                //     $('#FCMS').prop('checked',true);
                // }
                //DS   IS  ES  NDS  NCS
                //NUMERACION DE COMPROBANTES
                if(response[0]['Num_CD']!=0)
                {
                    $('#DM').prop('checked',true);
                    $('#DS').prop('checked',false);
                }else if(response[0]['Num_CD']==0)
                {
                    $('#DM').prop('checked',false);
                    $('#DS').prop('checked',true);
                }

                if(response[0]['Num_CI']!=0)
                {
                    $('#IM').prop('checked',true);
                    $('#IS').prop('checked',false);
                }else if(response[0]['Num_CI']==0)
                {
                    $('#IM').prop('checked',false);
                    $('#IS').prop('checked',true);
                }

                if(response[0]['Num_CE']!=0)
                {
                    $('#EM').prop('checked',true);
                    $('#ES').prop('checked',false);
                }else if(response[0]['Num_CE']==0)
                {
                    $('#EM').prop('checked',false);
                    $('#ES').prop('checked',true);
                }

                if(response[0]['Num_ND']!=0)
                {
                    $('#NDM').prop('checked',true);
                    $('#NDS').prop('checked',false);
                }else if(response[0]['Num_ND']==0)
                {
                    $('#NDM').prop('checked',false);
                    $('#NDS').prop('checked',true);
                }

                if(response[0]['Num_NC']!=0)
                {
                    $('#NCM').prop('checked',true);
                    $('#NCS').prop('checked',false);
                }else if(response[0]['Num_NC']==0)
                {
                    $('#NCM').prop('checked',false);
                    $('#NCS').prop('checked',true);
                }

                if(response[0]['smtp_UseAuntentificacion']!=0)
                {
                    $('#Autenti').prop('checked',true);
                }else if(response[0]['smtp_UseAuntentificacion']==0)
                {
                    $('#Autenti').prop('checked',false);
                }

                if(response[0]['smtp_SSL']!=0)
                {
                    $('#SSL').prop('checked',true);
                }else if(response[0]['smtp_SSL']==0)
                {
                    $('#SSL').prop('checked',false);
                }

                if(response[0]['smtp_Secure']!=0)
                {
                    $('#Secure').prop('checked',true);
                }else if(response[0]['smtp_Secure']==0)
                {
                    $('#Secure').prop('checked',false);
                }

                if(response[0]['Obligado_Conta']!= '')
                {
                    $('#ddl_obli').val(response[0]['Obligado_Conta']);
                }
            }
        });
}
// function cambiar()
// {
   
    
// }
function guardar_empresa()
{       
    if(
        $('#TxtEmpresa').val()==''|| 
        $("#TxtRazonSocial").val()==''||
        $("#TxtNomComercial").val()==''||
        $("#TxtRuc").val()==''||
        $("#ddl_obli").val()==''||
        $("#TxtRepresentanteLegal").val()==''||
        $("#TxtCI").val()==''||
        $("#ddl_naciones").val()==''||
        $("#prov").val()==''||
        $("#ddl_ciudad").val()==''||
        $("#TxtDirMatriz").val()==''||
        $("#TxtEsta").val()==''||
        $("#TxtTelefono").val()==''||
        $("#TxtTelefono2").val()==''||
        $("#TxtFax").val()==''||
        $("#TxtMoneda").val()==''||
        $("#TxtNPatro").val()==''||
        $("#TxtCodBanco").val()==''||
        $("#TxtTipoCar").val()==''||
        $("#TxtAbrevi").val()==''||
        $("#TxtEmailEmpre").val()==''||
        $("#TxtEmailConta").val()==''||
        // $("#TxtEmailRespa").val()==''||
        $("#TxtSegDes1").val()==''||
        $("#TxtSegDes2").val()==''||
        $("#TxtSubdir").val()==''||
        $("#TxtNombConta").val()==''||
        $("#TxtRucConta").val()==''||
        $("#TxtRucConta").val()==''
    )
    {
        Swal.fire('Llene todos los campos para guardar la empresa','','info');
        return false;
    }
    // var razon = $('#TxtRazonSocial').val();
    var ckASDAS = $('#ASDAS').prop('checked');
    var ckMFNV = $('#MFNV').prop('checked');
    var ckMPVP = $('#MPVP').prop('checked');
    var ckIRCF = $('#IRCF').prop('checked');
    var ckIMR = $('#IMR').prop('checked');
    var ckIRIP = $('#IRIP').prop('checked');
    var ckPDAC = $('#PDAC').prop('checked');
    var ckRIAC = $('#RIAC').prop('checked');
    var ckFCMS = $('#FCMS').prop('checked');
    var ckDM = $('#DM').prop('checked');
    var ckDS = $('#DS').prop('checked');
    var ckIM = $('#IM').prop('checked');
    var ckIS = $('#IS').prop('checked');
    var ckEM = $('#EM').prop('checked');
    var ckES = $('#ES').prop('checked');
    var ckNDM = $('#NDM').prop('checked');
    var ckNDS = $('#NDS').prop('checked');
    var ckNCM = $('#NCM').prop('checked');
    var ckNCS = $('#NCS').prop('checked');
    var ckAutenti = $('#Autenti').prop('checked');
    var ckSSL = $('#SSL').prop('checked');
    var ckSecure = $('#Secure').prop('checked');
    var Ambiente1 = $('#optionsRadios1').prop('checked');
    var Ambiente2 = $('#optionsRadios2').prop('checked');
    var datos = $('#formulario').serialize();
    // guardar_usuario_clave();
    limpiar();
    
    $.ajax({	 	    
	 	type: "POST",
	 		url: '../controlador/empresa/crear_empresaC.php?guardar_empresa=true',
	 		// data: {razon1:razon}, 
            data:datos+
            '&ckASDAS='+ckASDAS+
            '&ckMFNV='+ckMFNV+
            '&ckMPVP='+ckMPVP+
            '&ckIRCF='+ckIRCF+
            '&ckIMR='+ckIMR+
            '&ckIRIP='+ckIRIP+
            '&ckPDAC='+ckPDAC+
            '&ckRIAC='+ckRIAC+
            '&ckDM='+ckDM+
            '&ckDS='+ckDS+
            '&ckIM='+ckIM+
            '&ckIS='+ckIS+
            '&ckEM='+ckEM+
            '&ckES='+ckES+
            '&ckNDM='+ckNDM+
            '&ckNDS='+ckNDS+
            '&ckNCM='+ckNCM+
            '&ckNCS='+ckNCS+
            '&ckAutenti='+ckAutenti+
            '&ckSSL='+ckSSL+
            '&ckSecure='+ckSecure+
            '&Ambiente1='+Ambiente1+
            '&Ambiente2='+Ambiente2,
	 		dataType:'json',
	 		success: function(response)
	 		{
                if(response==2)
		  	{
		  		Swal.fire('Datos Guardados','','success').then(function(){
		  			$('#TxtEmpresa').attr('readonly',true);
		  		});
		  		console.log(response);
	 		}
	 		}
	 	});
}
function eliminar_empresa()
 {
 	var id = $('#TxtItem').val();
 		if(id=='')
 		{
 			Swal.fire('Seleccione una empresa','','error');
 			return false;
 		}
 		Swal.fire({
      title: 'Quiere eliminar esta empresa?',
      text: "Esta seguro de eliminar esta empresa!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            eliminar(id);
        }
    })
 }
function eliminar(id)
{
    $.ajax({
        url: '../controlador/empresa/crear_empresaC.php?delete=true',
        type:'post',
        dataType:'json',
        data:{id:id},     
        success: function(response){
            if(response==1)
            {
                Swal.fire('Empresa eliminada','','success');
            }else{
                Swal.fire('Intente mas tarde','','error');
            }
            limpiar();
        }
    });
}

function limpiar()
{    
    $('#TxtEmpresa').val('');
    $("#TxtRazonSocial").val('');
    $("#TxtNomComercial").val('');
    $("#TxtRuc").val('');
    $("#ddl_obli").val('');
    $("#TxtRepresentanteLegal").val('');
    $("#TxtCI").val('');
    $("#ddl_naciones").val('');
    $("#TxtDirMatriz").val('');
    $("#TxtEsta").val('000')
    $("#TxtTelefono").val('');
    $("#TxtTelefono2").val('');
    $("#TxtFax").val('');
    $("#TxtMoneda").val('USD');
    $("#TxtNPatro").val('');
    $("#TxtCodBanco").val('');
    $("#TxtTipoCar").val('');
    $("#TxtAbrevi").val('');
    $("#TxtEmailEmpre").val('');
    $("#TxtEmailConta").val('');
    $("#TxtEmailRespa").val('');
    $("#TxtSegDes1").val('');
    $("#TxtSegDes2").val('');
    $("#TxtSubdir").val('');
    $("#TxtNombConta").val('')
    $("#TxtRucConta").val('');
}
function AmbientePrueba()
{
    $('#TxtWebSRIre').val('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl');
    $('#TxtWebSRIau').val('https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl');
}
function AmbienteProduccion()
{
    $('#TxtWebSRIre').val('https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl');
    $('#TxtWebSRIau').val('https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl');
}
function DiariosM()
{
    $('#DM').prop('checked',true);
    $('#DS').prop('checked',false);
}
function DiariosS()
{
    $('#DM').prop('checked',false);
    $('#DS').prop('checked',true);
}
function IngresosM()
{
    $('#IM').prop('checked',true);
    $('#IS').prop('checked',false);
}
function IngresosS()
{
    $('#IM').prop('checked',false);
    $('#IS').prop('checked',true);
}
function EgresosM()
{
    $('#EM').prop('checked',true);
    $('#ES').prop('checked',false);
}
function EgresosS()
{
    $('#EM').prop('checked',false);
    $('#ES').prop('checked',true);
}
function NDPM()
{
    $('#NDM').prop('checked',true);
    $('#NDS').prop('checked',false);
}
function NDPS()
{
    $('#NDM').prop('checked',false);
    $('#NDS').prop('checked',true);
}
function NCPM()
{
    $('#NCM').prop('checked',true);
    $('#NCS').prop('checked',false);
}
function NCPS()
{
    $('#NCM').prop('checked',false);
    $('#NCS').prop('checked',true);
}
function validar_CI()
{
    var ci = $('#TxtCI').val();
    if(ci.length<10)
    {
        Swal.fire('La cedula no tiene 10 caracteres','','info');
        $('#TxtCI').val('');
        return false;
    }
    $.ajax
    ({
        data:  {ci:ci},
        url:   '../controlador/empresa/crear_empresaC.php?validarCI=true',
        type:  'post',
        dataType: 'json',
        success:  function (response)
        { 
            console.log(response);
            
        }
    });
}
function validar_RUC()
{
    var txtruc = $('#TxtRuc').val();
    if(txtruc.length<13)
    {
        Swal.fire('El RUC no tiene 13 caracteres','','info');
        $('#TxtRuc').val('');
        return false;
    }
    $.ajax
    ({
        data:  {txtruc:txtruc},
        url:   '../controlador/empresa/crear_empresaC.php?validarRUC=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) 
        { 
            console.log(response);
            
        }
    });
}
function validar_RUConta()
{
    var txtruconta = $('#TxtRucConta').val();
    if(txtruconta.length<13)
    {
        Swal.fire('El RUC no tiene 13 caracteres','','info');
        $('#TxtRucConta').val('');
        return false;
    }
    $.ajax
    ({
        data:  {txtruconta:txtruconta},
        url:   '../controlador/empresa/crear_empresaC.php?validarRUConta=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) 
        { 
            console.log(response);
            $('#TxtEmpresa').val('Tipo');
            if(response == 2)
            {
                Swal.fire('Numero de cedula invalido.','','error');
                $('#txt_ruc').val('');
                return false;
        }
        }
    });
}
function subdireccion()
{
    var txtsubdi = $('#TxtSubdir').val();
    $.ajax
    ({
        data:  {txtsubdi:txtsubdi},
        url:   '../controlador/empresa/crear_empresaC.php?subdireccion=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) 
        { 
            if(response == null)
            {
                Swal.fire('Este directorio ya existe seleccione otro','','error');
                $('#TxtSubdir').val('');
            }
            else{
                console.log(response);
            $('#TxtSubdir').val(response);
            }
        }
    });
}
</script>
<div class="row">
    <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12">
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
                <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button  title="Eliminar Registro" data-toggle="tooltip" class="btn btn-default" onclick="eliminar_empresa()">
                <img src="../../img/png/delete_file.png" >
            </button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Grabar Empresa" onclick="guardar_empresa()">
                <img src="../../img/png/grabar.png">
            </button>
        </div>        
</div>


<div class="col-sm-12">
    <div class="box-body">        
        <div class="row">
            <div class="col-sm-2">
                <label>LISTA DE EMPRESAS</label>
            </div>
            <div class="col-sm-10">
                <select class="form-control input-xs" id="select_empresa" name="select_empresa" onchange="llamar()" onblur="cambiar()">
                        <option value="">Seleccione</option>
                </select>
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
                <form action="" id="formulario">
                <div class="row">
                    <div class="col-sm-2">
                        <label>EMPRESA:</label>
                    </div>
                    <div class="col-sm-10">                                
                        <input type="text" name="TxtEmpresa" id="TxtEmpresa" class="form-control input-xs" value="">
                    </div>
                    <div class="col-sm-2">
                        <label hidden="hidden">ITEM:</label>
                    </div>
                    <div class="col-sm-10">                                
                        <input type="hidden" name="TxtItem" id="TxtItem" class="form-control input-xs" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <label>RAZON SOCIAL:</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" name="TxtRazonSocial" id="TxtRazonSocial" class="form-control input-xs" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <label>NOMBRE COMERCIAL:</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" name="TxtNomComercial" id="TxtNomComercial" class="form-control input-xs" value="">
                    </div>
                </div>                
                <div class="row">
                    <div class="col-sm-2">
                        <label>RUC:</label>
                        <input type="text" name="TxtRuc" id="TxtRuc" class="form-control input-xs" value="" onblur="validar_RUC()" onkeyup="num_caracteres('TxtRuc',13)" autocomplete="off">
                    </div>
                    <div class="col-sm-2">
                        <label>OBLIG</label>
                        <select class="form-control input-xs" id="ddl_obli" name="ddl_obli">
                            <option value="">Seleccione</option>
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>REPRESENTANTE LEGAL:</label>
                        <input type="text" name="TxtRepresentanteLegal" id="TxtRepresentanteLegal" class="form-control input-xs" value="">
                    </div>
                    <div class="col-sm-2">
                        <label>C.I/PASAPORTE</label>
                        <input type="text" name="TxtCI" id="TxtCI" class="form-control input-xs" value="" onblur="validar_CI()" onkeyup="num_caracteres('TxtCI',10)" autocomplete="off">
                    </div>
                </div>                    
                <div class="row">
                    <div class="col-sm-4">
                        <label>NACIONALIDAD</label>
                        <select class="form-control input-xs" id="ddl_naciones" name="ddl_naciones" onchange="provincias(this.value)">
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>PROVINCIA</label>
                        <select class="form-control input-xs"  id="prov" name="prov" onchange="ciudad(this.value)">
                            <option value="">Seleccione una provincia</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>CIUDAD</label>
                        <select class="form-control input-xs" id="ddl_ciudad" name="ddl_ciudad">
                            <option value="">Seleccione una ciudad</option>
                        </select>
                    </div>                        
                </div>                    
                <div class="row">
                    <div class="col-sm-10">
                        <label>DIRECCION MATRIZ:</label>
                        <input type="text" name="TxtDirMatriz" id="TxtDirMatriz" class="form-control input-xs" value="">
                    </div>
                    <div class="col-sm-2">
                        <label>ESTA.</label>
                        <input type="text" name="TxtEsta" id="TxtEsta" class="form-control input-xs" value="000">
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
                        <input type="text" name="TxtMoneda" id="TxtMoneda" class="form-control input-xs" value="USD">
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
                    <div class="col-sm-12">
                        <label>EMAIL DE CONTABILIDAD:</label>
                        <input type="text" name="TxtEmailConta" id="TxtEmailConta" class="form-control input-xs" value="@">
                    </div>                        
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>EMAIL DE RESPALDO:</label>
                        <input type="text" name="TxtEmailRespa" id="TxtEmailRespa" class="form-control input-xs" value="@">
                    </div>
                    <div class="col-sm-4 text-center">
                        <label>SEGURO DESGRAVAMEN %</label>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <input type="text" name="TxtSegDes1" id="TxtSegDes1" class="form-control input-xs" value="">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="TxtSegDes2" id="TxtSegDes2" class="form-control input-xs" value="">
                            </div>
                        </div>                        
                    </div>
                    <div class="col-sm-2">
                        <label>SUBDIR:</label>
                        <input type="text" name="TxtSubdir" id="TxtSubdir" class="form-control input-xs" value="" onblur="subdireccion()" onkeyup="mayusculas('TxtSubdir',this.value);">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10">
                        <label>NOMBRE DEL CONTADOR</label>
                        <input type="text" name="TxtNombConta" id="TxtNombConta" class="form-control input-xs" value="">
                    </div>
                    <div class="col-sm-2">
                        <label>RUC CONTADOR:</label>
                        <input type="text" name="TxtRucConta" id="TxtRucConta" class="form-control input-xs" value="" onblur="validar_RUConta()" onkeyup="num_caracteres('TxtRucConta',13)" autocomplete="off">
                    </div>
                </div>
            </div>
            <!-- DATOS PRINCIPALES FIN -->
            <!-- PROCESOS GENERALES INICIO -->
            <div class="tab-pane" id="tab_2">                                
                <div class="row">
                    <div class="col-md-4" style="background-color:#ffe0c0">                                   
                    <!-- setesos -->
                        <label>|Seteos Generales|</label>
                        <div class="checkbox">
                            <label><input type="checkbox" id="ASDAS">Agrupar Saldos Detalle Auxiliar de Submodulos</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" id="MFNV">Modificar Facturas o Notas de Venta</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" id="MPVP">Modificar Precio de Venta al Público</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" id="IRCF">Imprimir Recibo de Caja en Facturación</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" id="IMR">Imprimir Medio Rol</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" id="IRIP">Imprimir dos Roles Individuales por pagina</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" id="PDAC">Procesar Detalle Auxiliar de Comprobantes</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" id="RIAC">Registrar el IVA en el Asiento Contable</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" id="FCMS">Funciona como Matriz de Sucursales</label>
                        </div>
                    </div>
                    <div class="col-md-4">                                        
                        <label>LOGO TIPO</label>
                        <input type="text" name="TxtXXXX" id="TxtXXXX" class="form-control input-xs" value="XXXXXXXXXX">
                        <div class="form-group" rows="11">                                        
                            <select multiple="" class="form-control" >
                                <option>ADDSCCES.DLL</option>
                                <option>ADDSCCUS.DLL</option>
                                <option>BIBLIO.MDB</option>
                                <option>C2.EXE</option>
                                <option>CVPACK.EXE</option>
                                <option>DATAVIEW.DLL</option>
                                <option>INSTALL.HTM</option>
                                <option>LINK.EXE</option>
                                <option>MSDIS110.DLL</option>
                                <option>MSPDB60.DLL</option>                                                    
                            </select>                                                
                        </div>
                    </div>
                    <style type="text/css">
                        textarea {
                            resize : none;
                        }
                    </style>
                    <div class="col-md-4">                                        
                        <div class="box-body">
                            <textarea class="form-control" rows="11" resize="none" placeholder=""></textarea>
                        </div>                                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>|Numeración de Comprobantes|</label>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <input type="checkbox" name="dm1" id="DM" onclick="DiariosM()">Diarios por meses
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <input type="checkbox" name="dm1" id="DS" onclick="DiariosS()">Diarios secuenciales
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <input type="checkbox" id="IM" onclick="IngresosM()">Ingresos por meses
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <input type="checkbox" id="IS" onclick="IngresosS()">Ingresos secuenciales
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <input type="checkbox" id="EM" onclick="EgresosM()">Egresos por meses
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <input type="checkbox" id="ES" onclick="EgresosS()">Egresos secuenciales
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <input type="checkbox" id="NDM" onclick="NDPM()">N/D por meses
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <input type="checkbox" id="NDS" onclick="NDPS()">N/D secuenciales
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <input type="checkbox" id="NCM" onclick="NCPM()">N/C por meses
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="checkbox">
                                    <input type="checkbox" id="NCS" onclick="NCPS()">N/C secuenciales
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">                        
                        <div class="row">
                            <div class="col-sm-12" style="background-color:#ffffc0">
                                <b>|Servidor de Correos|</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10" style="background-color:#ffffc0">
                                <b>Servidor SMTP</b>
                                <input type="text" name="TxtServidorSMTP" id="TxtServidorSMTP" class="form-control input-xs" value="">
                            </div>
                            <div class="col-sm-2" style="background-color:#ffffc0">
                                <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
                                <button type="button" class="btn btn-default" title="Grabar Empresa" onclick="()">
                                    <img src="../../img/png/grabar.png">
                                </button>
                            </div>
                            </div>
                        </div>
                        <div class="row" style="background-color:#ffffc0">
                            <div class="col-sm-2">
                                <input type="checkbox" id="Autenti">Autentificación
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" id="SSL">SSL
                            </div>
                            <div class="col-sm-2">
                                <input type="checkbox" id="Secure">SECURE
                            </div>
                            <div class="col-sm-1">
                                PUERTO
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="TxtPuerto" id="TxtPuerto" class="form-control input-xs" value="">                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <input type="checkbox" id="AsigUsuClave" onclick="MostrarUsuClave()">ASIGNA USUARIO Y CLAVE DEL REPRESENTANTE LEGAL
                            </div>
                            <div class="col-sm-2">
                                <label id="lblUsuario">USUARIO</label>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="TxtUsuario" id="TxtUsuario" class="form-control input-xs" value="USUARIO"> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <input type="checkbox" id="CopSeEmp" onclick="MostrarEmpresaCopia()">COPIAR SETEOS DE OTRA EMPRESA
                            </div>
                            <div class="col-sm-2">
                                <label id="lblClave">CLAVE</label>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="TxtClave" id="TxtClave" class="form-control input-xs" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <select class="form-control input-xs" id="ListaCopiaEmpresa" name="ListaCopiaEmpresa">
                                    <option value="">Empresa</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
                
                <div class="row">
                    <div class="col-md-4" style="background-color:#c0ffc0">
                        <label>|Cantidad de Decimales en|</label>
                    </div>                                    
                </div>
                <div class="row">
                    <div class="col-md-1" style="background-color:#c0ffc0">
                        P.V.P
                        <input type="text" name="TxtPVP" id="TxtPVP" class="form-control input-xs" value="">
                    </div>
                    <div class="col-md-1" style="background-color:#c0ffc0">
                        COSTOS
                        <input type="text" name="TxtCOSTOS" id="TxtCOSTOS" class="form-control input-xs" value="">
                    </div>
                    <div class="col-md-1" style="background-color:#c0ffc0">
                        I.V.A
                        <input type="text" name="TxtIVA" id="TxtIVA" class="form-control input-xs" value="">
                    </div>
                    <div class="col-md-1" style="background-color:#c0ffc0">
                        CANTIDAD
                        <input type="text" name="TxtCantidad" id="TxtCantidad" class="form-control input-xs" value="">
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
                        <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="" onclick="AmbientePrueba()">
                        Ambiente de Prueba
                    </div>
                    <div class="col-sm-4">
                        <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2" onclick="AmbienteProduccion()">
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
                            <input type="text" name="TxtWebSRIre" id="TxtWebSRIre" class="form-control input-xs" value="TxtWebSRIre"><!-- disabled="disabled">-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>WEBSERVICE SRI AUTORIZACIÓN</label>
                                <input type="text" name="TxtWebSRIau" id="TxtWebSRIau" class="form-control input-xs" value="TxtWebSRIau"><!-- disabled="disabled">-->
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
                                <label>LEYENDA AL FINAL DE LOS DOCUMENTOS ELECTRONICOS</label>
                                <textarea name="txtLeyendaDocumen" id="txtLeyendaDocumen"class="form-control" rows="2" resize="none" placeholder="Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: NUMERO_TELEFONO, o escriba al correo EMAIL_EMPRESA; para Transferencia o Depósitos hacer en El Banco NOMBRE_BANCO a Nombre de REPRESENTANTE_LEGAL/CTA_AHR_CTE_NUMERO, a Nombre de RAZON_SOCIAL"></textarea>                            
                        </div>
                        <div class="col-sm-12">
                            <label>LEYENDA AL FINAL DE LA IMPRESION EN LA IMPRESORA DE PUNTO DE VENTA DE DOCUMENTOS ELECTRÓNICOS</label><br>                            
                            <textarea name="txtLeyendaImpresora" id="txtLeyendaImpresora"class="form-control" rows="2" resize="none" placeholder="Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: NUMERO_TELEFONO"></textarea>
                        </div>
                    </div>
                </div>                
            </div>
            <!-- COMPROBANTES ELECTRONICOS FIN-->  
            </form>                  
        </div>
    </div>
</div>            