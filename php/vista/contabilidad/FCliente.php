<?php
$mostrar_medidor = false;
switch ($_SESSION['INGRESO']['modulo_']) {
  case '07': //AGUA POTABLE
    $mostrar_medidor = true;
    break;
  default:

    break;
}

?>
<script type="text/javascript">
  var prove = '<?php if (isset($_GET['proveedor'])) {
    echo 1;
  } ?>'
  $(document).ready(function () {
    provincias();
    tipo_proveedor_Cliente()

    $("#CMedidor").on('change', function () {
      if ($("#CMedidor").val() != "." && $("#CMedidor").val() != "") {
        $("#DeleteMedidor").removeClass("no-visible")
      } else {
        $("#DeleteMedidor").addClass("no-visible")
      }
    })
  });



  function buscar_numero_ci() {
    $('#LblSRI').html('');
    var ci_ruc = $('#ruc').val();
    if (ci_ruc == '' || ci_ruc == '.') {
      return false;
    }
    $.ajax({
      url: '../controlador/modalesC.php?buscar_cliente=true',
      type: 'post',
      dataType: 'json',
      data: { search: ci_ruc },
      beforeSend: function () {
        $("#myModal_espera").modal('show');
      },
      success: function (response) {
        limpiar();
        if (response.length > 0) {
          // console.log(response[0]);
          $('#txt_id').val(response[0].value); // display the selected text
          $('#ruc').val(response[0].label); // display the selected text
          $('#nombrec').val(response[0].nombre); // save selected id to input
          $('#direccion').val(response[0].direccion); // save selected id to input
          $('#telefono').val(response[0].telefono); // save selected id to input
          $('#codigoc').val(response[0].codigo); // save selected id to input
          $('#email').val(response[0].email); // save selected id to input
          $('#nv').val(response[0].vivienda); // save selected id to input
          $('#grupo').val(response[0].grupo); // save selected id to input
          $('#naciona').val(response[0].nacionalidad); // save selected id to input
          $('#prov').val(response[0].provincia); // save selected id to input
          if (response[0].provincia == '' || response[0].provincia == '.') {
            $('#prov').append('<option value=".">Seleccione</option>'); // save selected id to input                  
          }
          $('#ciu').val(response[0].ciudad); // save selected id to input
          $('#TD').val(response[0].TD); // save selected id to input
          $('#txt_ejec').val(response[0].Cod_Ejec); // save selected id to input

          // Verificar si ya existe una opción con el mismo valor
          if ($('#txt_actividadC option[value="' + response[0].Actividad + '"]').length === 0) {
            // Si no existe, agregar la nueva opción al final del select
            var nuevaOpcion = '<option value="' + response[0].Actividad + '">' + response[0].Actividad + '</option>';
            $('#txt_actividadC').append(nuevaOpcion);
          }
          $('#txt_actividadC').val(response[0].Actividad); // save selected id to input

          if (response[0].FA == 1) { $('#rbl_facturar').prop('checked', true); } else { $('#rbl_facturar').prop('checked', false); }
          MostrarOcultarBtnAddMedidor()
        } else {
          $('#ruc').val(ci_ruc);
          codigo();
        }

        $("#myModal_espera").modal('hide');

      }
    });
  }

  function provincias() {
    var option = "<option value=''>Seleccione provincia</option>";
    $.ajax({
      url: '../controlador/educativo/detalle_estudianteC.php?provincias=true',
      type: 'post',
      dataType: 'json',
      // data:{usu:usu,pass:pass},
      beforeSend: function () {
        $("#select_ciudad").html("<option value=''>Seleccione provincia</option>");
      },
      success: function (response) {
        response.forEach(function (data, index) {
          option += "<option value='" + data.Codigo + "'>" + data.Descripcion_Rubro + "</option>";
        });
        $('#prov').html(option);
        console.log(response);
      }
    });

  }

  function limpiar() {
    $('#txt_id').val(''); // display the selected text
    $('#ruc').val(''); // display the selected text
    $('#nombrec').val(''); // save selected id to input
    $('#direccion').val(''); // save selected id to input
    $('#telefono').val(''); // save selected id to input
    $('#codigoc').val(''); // save selected id to input
    $('#email').val(''); // save selected id to input
    $('#nv').val(''); // save selected id to input
    $('#grupo').val(''); // save selected id to input
    $('#naciona').val(''); // save selected id to input
    $('#prov').val(''); // save selected id to input
    $('#ciu').val(''); // save selected id to input
    $('#CMedidor').empty();
    MostrarOcultarBtnAddMedidor()
  }

  function codigo() {
    $("#myModal_espera").modal('show');
    var ci = $('#ruc').val();
    if (ci != '') {
      $.ajax({
        url: '../controlador/modalesC.php?codigo=true',
        type: 'post',
        dataType: 'json',
        data: { ci: ci },
        beforeSend: function () {
          // $("#myModal_espera").modal('show');
        },
        success: function (response) {
          console.log(response);
          $('#codigoc').val(response.Codigo_RUC_CI);
          $('#TD').val(response.Tipo_Beneficiario);
          $("#myModal_espera").modal('hide');
          MostrarOcultarBtnAddMedidor()

        }
      });
    } else {
      limpiar();
    }

  }


  function buscar_cliente_nom() {
    var ci = $('#nombrec').val();
    var parametros =
    {
      'nombre': ci,
    }
    $.ajax({
      data: { parametros: parametros },
      url: '../controlador/modalesC.php?buscar_cliente_nom=true',
      type: 'post',
      dataType: 'json',
      success: function (response) {
        // console.log(response);
        if (response) {

        }
      }
    });
  }

  function guardar_cliente() {
    if (validar() == true) {
      swal.fire('Llene todos los campos', '', 'info')
      return false;
    }
    var rbl = $('#rbl_facturar').prop('checked');
    var datos = $('#form_cliente').serialize();
    $.ajax({
      data: datos + '&rbl=' + rbl + '&cxp=' + prove,
      url: '../controlador/modalesC.php?guardar_cliente=true',
      type: 'post',
      dataType: 'json',
      success: function (response) {
        // console.log(response);
        var url = location.href;
        if (response == 1) {
          if ($('#txt_id').val() != '') {
            swal.fire('Registro guardado', '', 'success');
          } else {
            swal.fire('Registro guardado', '', 'success');
          }

        } else if (response == 2) {
          swal.fire('Este CI / RUC ya esta registrado', '', 'info');
        } else if (response == 3) {
          swal.fire('El Nombre ya esta registrado', '', 'info');
        }
      }
    });
  }

  function validar() {

    $('#e_ruc').css('display', 'none');
    $('#e_telefono').css('display', 'none');
    $('#e_nombrec').css('display', 'none');
    $('#e_direccion').css('display', 'none');

    var vali = false;
    if ($('#ruc').val() == '') {
      $('#e_ruc').css('display', 'initial');
      vali = true;
    }
    if ($('#telefono').val() == '') {
      $('#e_telefono').css('display', 'initial');
      vali = true;
    }
    if ($('#nombrec').val() == '') {
      $('#e_nombrec').css('display', 'initial');
      vali = true;
    }
    if ($('#direccion').val() == '') {
      $('#e_direccion').css('display', 'initial');
      vali = true;
    }
    if ($('#email').val() == '') {
      $('#e_email').css('display', 'initial');
      vali = true;
    }

    return vali;

  }

  function AddMedidor() {
    let CodigoC = $("#codigoc").val();

    if (CodigoC != "" && CodigoC != ".") {
      Swal.fire({
        title: 'Ingresar Nuevo Medidor:',
        showCancelButton: true,
        cancelButtonText: 'Cerrar',
        confirmButtonText: 'Guardar',
        html:
          '<label for="CMedidorNew">Numero de Medidor</label>' +
          '<input type="tel" id="CMedidorNew" class="swal2-input" required>' +
          '<span id="error1" style="color: red;"></span><br>' +
          '<label for="LecturaInicial">Lectura Anterior</label>' +
          '<input type="tel" id="LecturaInicial" class="swal2-input inputNumero">' +
          '<span id="error2" style="color: red;"></span><br>',
        focusConfirm: false,
        preConfirm: () => {
          const CMedidorNew = document.getElementById('CMedidorNew').value;
          const LecturaInicial = document.getElementById('LecturaInicial').value;

          if ($.isNumeric(CMedidorNew)) {
            if ($.isNumeric(LecturaInicial) || LecturaInicial == "") {
              return [CMedidorNew, LecturaInicial];
            } else {
              Swal.getPopup().querySelector('#error2').textContent = 'Debe ingresar un valor numérico';
              return false
            }
          } else {
            Swal.getPopup().querySelector('#error1').textContent = 'Debe ingresar un valor numérico';
            return false
          }
        }
      }).then((result) => {
        if (result.value) {
          const [CMedidorNew, LecturaInicial] = result.value;
          if ($.isNumeric(CMedidorNew)) {
            $.ajax({
              type: 'POST',
              dataType: 'json',
              url: '../controlador/modalesC.php?AddMedidor=true',
              data: { 'Cuenta_No': CMedidorNew, 'TxtCodigo': CodigoC, 'LecturaInicial': LecturaInicial },
              beforeSend: function () {
                $('#myModal_espera').modal('show');
              },
              success: function (response) {
                $('#myModal_espera').modal('hide');
                if (response.rps) {
                  Swal.fire('¡Bien!', response.mensaje, 'success')
                  ListarMedidores(CodigoC)
                } else {
                  Swal.fire('¡Oops!', response.mensaje, 'warning')
                }
              },
              error: function () {
                $('#myModal_espera').modal('hide');
                alert("Ocurrio un error inesperado, por favor contacte a soporte.");
              }
            });
          }
        }
      });
    } else {
      swal.fire('No se ha definido un Codigo de usuario, ingrese un RUC/CI para obtener el codigo.', '', 'warning')
    }
  }

  function DeleteMedidor() {
    let idMedidor = $("#CMedidor").val();
    let TxtApellidosS = $("#nombrec").val();
    let CodigoC = $("#codigoc").val();

    if (idMedidor != "." && idMedidor != "") {
      Swal.fire({
        title: `Esta seguro que desea Eliminar\nEl Medidor No. ${idMedidor} \nDe ${TxtApellidosS}`,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'No.',
        confirmButtonText: 'Si, Eliminar'
      }).then((result) => {
        if (result.value == true) {
          $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '../controlador/modalesC.php?DeleteMedidor=true',
            data: { 'Cuenta_No': idMedidor, 'TxtCodigo': CodigoC },
            beforeSend: function () {
              $('#myModal_espera').modal('show');
            },
            success: function (response) {
              $('#myModal_espera').modal('hide');
              if (response.rps) {
                Swal.fire('¡Bien!', response.mensaje, 'success')
              } else {
                Swal.fire('¡Oops!', response.mensaje, 'warning')
              }
              ListarMedidores(CodigoC)
            },
            error: function () {
              $('#myModal_espera').modal('hide');
              alert("Ocurrio un error inesperado, por favor contacte a soporte.");
            }
          });
        }
      })
    } else {
      swal.fire('Debe seleccionar el medidor que desea eliminar', '', 'warning')
    }
  }

  function ListarMedidores(codigo) {
    if (codigo != "" && codigo != ".") {
      $.ajax({
        url: '../controlador/modalesC.php?ListarMedidores=true',
        type: 'POST',
        dataType: 'json',
        data: { 'codigo': codigo },
        success: function (response) {
          // construye las opciones del select dinámicamente
          var select = $('#CMedidor');
          select.empty(); // limpia las opciones existentes
          $.each(response, function (i, opcion) {
            if (opcion.Cuenta_No == ".") {
              select.append($('<option>', {
                value: '.',
                text: 'NINGUNO'
              }));
            } else {
              select.append($('<option>', {
                value: opcion.Cuenta_No,
                text: opcion.Cuenta_No
              }));
            }
          });
          $('#CMedidor').change()
        }
      });
    }

  }

  function MostrarOcultarBtnAddMedidor() {
    if ($('#codigoc').val() != "" && $('#codigoc').val() != ".") {
      $("#AddMedidor").removeClass("no-visible")
      ListarMedidores($('#codigoc').val())
    } else {
      $("#AddMedidor").addClass("no-visible")
    }
  }

  function tipo_proveedor_Cliente() {
    $.ajax({
      url: '../controlador/modalesC.php?tipo_proveedor=true&TP=TIPOPROV',
      type: 'post',
      dataType: 'json',
      success: function (response) {
        var op = '<option value=".">Seleccione</option>';
        response.forEach(function (item, i) {
          console.log(item)
          op += "<option value='" + item.Proceso + "'>" + item.Proceso + "</option>";
        })
        $('#txt_actividadC').html(op);
      },
      error: function (xhr, textStatus, error) {
        $('#myModal_espera').modal('hide');
      }
    });
  }
  //FUNCIONES BOTONES CXC y CXP
  function cargar_cuentas(tipo) {
    if ($('#txt_id').val() == '') {
      Swal.fire('Selecione un registro', '', 'info');
      return false;
    }
    $('#modal_cuentas').modal('show');

    $('#txt_nombre_cuenta').val($('#nombrec').val());
    $('#txt_ci_cuenta').val($('#codigoc').val());
    if (tipo == 'cxc') {
      $('#titulo').text('ASIGNACION DE CUENTAS POR COBRAR');
      $('#SubCta').val('C');
      $('#cbx_cuenta_g').prop('disabled', true);


    } else {

      $('#cbx_cuenta_g').prop('disabled', false);
      $('#titulo').text('ASIGNACION DE CUENTAS POR PAGAR');
      $('#SubCta').val('P');
    }
    DLCxCxP();
    DLGasto();
    DLSubModulo();
  }

  function DLCxCxP() {
    $('#DLCxCxP').select2({
      placeholder: 'Seleccione una beneficiario',
      ajax: {
        url: '../controlador/modalesC.php?DLCxCxP=true&SubCta=' + $('#SubCta').val(),
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          console.log(data);
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function DLGasto() {
    $('#DLGasto').select2({
      placeholder: 'Seleccione una beneficiario',
      width: '100%',
      ajax: {
        url: '../controlador/modalesC.php?DLGasto=true&SubCta=' + $('#SubCta').val(),
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          console.log(data);
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function DLSubModulo() {
    $('#DLSubModulo').select2({
      placeholder: 'Seleccione una beneficiario',
      width: '100%',
      ajax: {
        url: '../controlador/modalesC.php?DLSubModulo=true&SubCta=' + $('#SubCta').val(),
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          console.log(data);
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function cancelar() {
    $('#DLCxCxP').empty();
    $('#DLGasto').empty();
    $('#DLSubModulo').empty();
    $('#TxtCodRet').val('.');
    $('#TxtRetIVAB').val('.');
    $('#TxtRetIVAS').val('.');
    $('#txt_ci_cuenta').val('.');
    $('#Ttxt_nombre_cuenta').val('.');

    if ($('#cbx_retencion').prop('checked')) {
      $('#cbx_retencion').click();
    }
    if ($('#cbx_cuenta_g').prop('checked')) {
      $('#cbx_cuenta_g').click();
    }

  }
  function guardar_cuentas() {
    datos = $('#form_cuentas').serialize();
    $.ajax({
      url: '../controlador/farmacia/proveedor_bodegaC.php?guardar_cuentas=true',
      type: 'post',
      dataType: 'json',
      data: datos,
      success: function (response) {
        if (response == 1) {
          cancelar();
          $('#modal_cuentas').modal('hide');
          swal.fire('Asignación realizada correctamente', '', 'success');
        }else{
          swal.fire('Error al asignar', '', 'error');
        }
      }
    });
  }
  function mostar_porcentaje_retencion() {
    if ($('#cbx_retencion').prop('checked')) {
      $('#panel_retencion').css('display', 'block');
    } else {
      $('#panel_retencion').css('display', 'none');
    }
  }
  function mostar_cuenta_Gastos() {
    if ($('#cbx_cuenta_g').prop('checked')) {
      $('#panel_cuenta_gasto').css('display', 'block');
    } else {
      $('#panel_cuenta_gasto').css('display', 'none');
    }

  }
  //FIN FUNCIONES BOTONES CXC y CXP
</script>

<style type="text/css">
  .visible {
    visibility: visible;
  }

  .no-visible {
    visibility: hidden;
  }

  .LblSRI {
    display: inline-grid;
    max-width: 80%;
  }

  .LblSRI p {
    padding: 0;
  }

  #swal2-content {
    font-weight: 600;
  }
</style>
<!-- BOTONES CXC y CXP -->
<div class="row">
  <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12" style="padding-bottom: 5px;">
    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1" style="padding: 0px">
      <a href="./farmacia.php?mod=Farmacia#" data-toggle="tooltip" title="Salir de modulo" class="btn btn-default"
        style="border: solid 2px;">
        <img src="../../img/png/salire.png">
      </a>
    </div>
    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1" style="padding-left: 15px;">
      <button type="button" class="btn btn-default" onclick="cargar_cuentas('cxc')" data-toggle="tooltip"
        title="Asignar a Cuenta por Cobrar Contabilidad" style="border: solid 2px;">
        <img src="../../img/png/cxc.png">
      </button>
    </div>
    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1" style="padding-left: 30px ;">
      <button type="button" class="btn btn-default" onclick="cargar_cuentas('cxp')" data-toggle="tooltip"
        title="Asignar a Cuenta por Pagar Contabilidad " style="border: solid 2px;">
        <img src="../../img/png/cxp.png">
      </button>
    </div>
  </div>
</div>
<!-- FIN BOTONES CXC y CXP -->

<div class="box box-info">

  <!-- /.box-header -->
  <!-- form start -->
  <form class="form-horizontal" id="form_cliente">
    <div class="box-body">
      <div class="row">
        <div class="col-xs-4 col-sm-3 ">
          <label for="ruc" class="control-label" id="resultado"><span style="color: red;">*</span>RUC/CI</label>
          <input type="hidden" class="form-control" id="txt_id" name="txt_id" placeholder="ruc" autocomplete="off">
          <input type="text" class="form-control input-sm" id="ruc" name="ruc" placeholder="RUC/CI" autocomplete="off"
            onblur="buscar_numero_ci();/*codigo()*/" style="z-index: 1;">
          <span class="help-block" id='e_ruc' style='display:none;color: red;'>Debe ingresar RUC/CI</span>

        </div>
        <div class="col-xs-2 col-sm-1" style="padding:0px"><br>
          <!-- <iframe src="https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/existePorNumeroRuc?numeroRuc=1722214507001&output=embed"></iframe> -->
          <button type="button" class="btn btn-sm" onclick="validar_sriC($('#ruc').val())">
            <img src="../../img/png/SRI.jpg" style="width: 60%">
          </button>

        </div>
        <div class="col-xs-3 col-sm-3 ">
          <label for="telefono" class="col-sm-1 control-label"><span style="color: red;">*</span>Telefono</label>
          <input type="text" class="form-control input-sm" id="telefono" name="telefono" placeholder="Telefono"
            autocomplete="off">
          <span class="help-block" id='e_telefono' style='display:none;color: red;'>Debe ingresar Telefono</span>
        </div>
        <div class="col-xs-3 col-sm-3 ">
          <label for="codigoc" class="control-label"><span style="color: red;">*</span>Codigo</label>
          <input type="hidden" id='buscar' name='buscar' value='' />
          <input type="text" id='TD' name='TD' value='' readonly style="width:30px" />
          <input type="text" class="form-control input-sm" id="codigoc" name="codigoc" placeholder="Codigo" readonly="">
          <span class="help-block" id='e_codigoc' style='display:none;color: red;'>debe agregar Codigo</span>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-9 col-sm-11 col-lg-10">
          <label for="nombrec" class="control-label"><span style="color: red;">*</span>Apellidos y Nombres</label>
          <input type="text" class="form-control input-sm" id="nombrec" name="nombrec" placeholder="Razon social"
            onkeyup="buscar_cliente_nom();mayusculas('nombrec',this.value) " onblur="mayusculas('nombrec',this.value)">
          <span class="help-block" id='e_nombrec' style='display:none;color: red;'>Debe ingresar nombre</span>
        </div>
        <div class="col-xs-3 col-sm-1 col-lg-2">
          <br>
          <label> </label><input type="checkbox" name="rbl_facturar" id="rbl_facturar" checked> Para Facturar
        </div>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <label for="direccion" class="control-label"><span style="color: red;">*</span>Direccion</label>
          <input type="text" class="form-control input-sm" id="direccion" name="direccion" placeholder="Direccion"
            tabindex="0" onkeyup="mayusculas('direccion',this.value)" onblur="mayusculas('direccion',this.value)">
          <span class="help-block" id='e_direccion' style='display:none;color: red;'>debe agregar Direccion</span>
        </div>
        <div class="col-xs-4">
          <label for="email" class="control-label"><span style="color: red;">*</span>Email Principal</label>
          <input type="email" class="form-control input-sm" id="email" name="email" placeholder="Email" tabindex="0"
            onblur="validador_correo('email')">
          <span class="help-block" id='e_email' style='display:none;color: red;'> debe agregar un email</span>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-4 col-xs-4">
          <b>Abreviado</b>
          <input type="" name="txt_ejec" id="txt_ejec" class="form-control input-sm">
        </div>
        <div class="col-sm-8 col-xs-8">
          <b>Tipo de proveedor</b>
          <select class="form-control input-sm" id="txt_actividadC" name="txt_actividad">
            <option value=".">Seleccione</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-5">
          <label for="nv" class="control-label">Ubicacion geografica</label>
          <input type="text" class="form-control input-sm" id="nv" name="nv" placeholder="Numero vivienda" tabindex="0"
            onkeyup="mayusculas('nv',this.value)" onblur="mayusculas('nv',this.value)">
        </div>
        <div class="col-xs-2">
          <label for="grupo" class="control-label">Grupo</label>
          <input type="text" class="form-control input-sm" id="grupo" name="grupo" placeholder="Grupo" tabindex="0">
        </div>
        <div class="col-xs-5">
          <label for="naciona" class="col-sm-1 control-label">Nacionalidad</label>
          <input type="text" class="form-control" id="naciona" name="naciona" placeholder="Nacionalidad" tabindex="0">
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6">
          <label for="prov" class="control-label">Provincia</label>
          <select class="form-control input-sm" id="prov" name="prov">
            <option>Seleccione una provincia</option>
          </select>
        </div>
        <div class="col-xs-6">
          <label for="ciu" class="control-label">Ciudad</label>
          <input type="text" class="form-control input-sm" id="ciu" name="ciu" placeholder="Ciudad" tabindex="0">
        </div>
      </div>
      <?php if ($mostrar_medidor): ?>
        <div class="row">
          <div class="col-xs-6 col-sm-4">
            <label for="CMedidor" class="control-label">Medidor No.</label>
            <div class="input-group contenedor_item_center">
              <select class="form-control input-sm" id="CMedidor" name="CMedidor">
                <option value="<?php echo G_NINGUNO ?>">NINGUNO</option>
              </select>
              <a class="btn btn-sm btn-success no-visible" id="AddMedidor" title="Agregar Medidor"
                onclick="AddMedidor()"><i class="fa fa-plus"></i></a>
              <a class="btn btn-sm btn-danger no-visible" id="DeleteMedidor" title="Eliminar Medidor"
                onclick="DeleteMedidor()"><i class="fa fa-trash-o"></i></a>

            </div>
          </div>
        </div>
      <?php endif ?>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      <button type="button" id="BtnGuardarClienteFCliente" onclick="guardar_cliente()"
        class="btn btn-primary">Guardar</button>
      <div class="text-left LblSRI">

      </div>
    </div>
    <!-- /.box-footer -->
  </form>
</div>
<!-- Modal CXC y CXP -->
<div class="modal fade" id="modal_cuentas" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titulo">ASIGNACION DE CUENTAS POR COBRAR</h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <form id="form_cuentas">
            <input type="hidden" name="SubCta" id="SubCta" value="">

            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-4">
                  <input id="txt_ci_cuenta" name="txt_ci_cuenta" class="form-control form-control-sm" readonly
                    style="background-color:black; color: yellow;" value="999999999999">
                </div>
                <div class="col-sm-8">
                  <input id="txt_nombre_cuenta" name="txt_nombre_cuenta" class="form-control form-control-sm" readonly
                    style="background-color:black; color: yellow;" value="CONSUMIDOR FINAL">
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <b>Asignar a:</b>
                  <select class="form-control form-control-sm" id="DLCxCxP" name="DLCxCxP" style="width: 100%;">
                    <option value="">Seleccione Cuenta</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <label><input type="checkbox" name="cbx_cuenta_g" id="cbx_cuenta_g" onclick="mostar_cuenta_Gastos()">
                    ASIGNAR A LA CUENTA DE GASTOS</label>
                </div>
              </div>
              <div class="row" id="panel_cuenta_gasto" style="display:none">
                <div class="col-sm-6 nopadding">
                  <select class="form-control form-control-sm col-sm-6" id="DLGasto" name="DLGasto">
                    <option value="">Seleccione Cuenta</option>
                  </select>
                </div>
                <div class="col-sm-6 nopadding">
                  <select class="form-control form-control-sm col-sm-6" id="DLSubModulo" name="DLSubModulo">
                    <option value="">Seleccione Cuenta</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <label><input type="checkbox" name="cbx_retencion" id="cbx_retencion"
                      onclick="mostar_porcentaje_retencion()"> PORCENTAJES DE RETENCION</label>
                </div>
              </div>
              <div class="row" id="panel_retencion" style="display:none">
                <div class="col-sm-4">
                  Codigo Retencion
                  <input type="" name="TxtCodRet" id="TxtCodRet" class="form-control form-control-sm">
                </div>
                <div class="col-sm-4">
                  Retencion IVA Bienes
                  <input type="" name="TxtRetIVAB" id="TxtRetIVAB" class="form-control form-control-sm">
                </div>
                <div class="col-sm-4">
                  Retencion IVA servicios
                  <input type="" name="TxtRetIVAS" id="TxtRetIVAS" class="form-control form-control-sm">
                </div>

              </div>
            </div>
          </form>
          <div class="col-sm-2">
            <div class="btn-group">
              <button class="btn btn-default btn-sm" onclick="guardar_cuentas()"><img
                  src="../../img/png/grabar.png"><br>&nbsp;&nbsp;&nbsp;Aceptar&nbsp;&nbsp;&nbsp;</button>
              <button class="btn btn-default" data-dismiss="modal" onclick="cancelar()"> <img
                  src="../../img/png/bloqueo.png"><br> Cancelar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal CXC y CXP -->