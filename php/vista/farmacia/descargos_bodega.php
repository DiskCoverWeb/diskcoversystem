<?php  $num_ped = '';$cod=''; $area = ''; $pro=''; if(isset($_GET['num_ped'])){$num_ped =$_GET['num_ped'];} if(isset($_GET['cod'])){$cod =$_GET['cod'];} if(isset($_GET['area'])){$area1 = explode('-', $_GET['area']); $area =$area1[0];$pro=$area1[1]; } $_SESSION['INGRESO']['modulo_']='99'; date_default_timezone_set('America/Guayaquil'); 
      unset($_SESSION['NEGATIVOS']['CODIGO_INV']);?>
<script type="text/javascript">
    var c = '<?php echo $cod; ?>';
    var area = '<?php echo $area; ?>';
   $( document ).ready(function() {

     if(c!='')
    {
      buscar_codi();
    }
    if(area !='')
    {
      buscar_Subcuenta();
    }


    autocoplet_paci();
    autocoplet_ref();
    autocoplet_desc();
    autocoplet_cc();
    autocoplet_area();
    num_comprobante();
    autocopletar_solicitante();
    $('#txt_procedimiento').val('<?php echo $pro; ?>');
     // buscar_cod();
    var pro = '<?php echo $pro; ?>';

    cargar_pedido();
    

  });



   function buscar_Subcuenta()
   {
     var area = '<?php echo $area; ?>';

      var  parametros = 
      { 
        'cod':area,
      }    
      // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?areas=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response.length >0){       
           $('#ddl_areas').append($('<option>',{value: response[0].Codigo, text:response[0].Detalle,selected: true }));
         }
      }
    });


   }
   function autocoplet_paci(){
      $('#ddl_paciente').select2({
        placeholder: 'Seleccione una paciente',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?paciente=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }

   function autocopletar_solicitante(){
      $('#txt_soli').select2({
        placeholder: 'Seleccione una paciente',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?solicitante=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }

  function autocoplet_cc(){
      $('#ddl_cc').select2({
        placeholder: 'Seleccione centro de costos',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?cc=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }
    function autocoplet_ref(){
      $('#ddl_referencia').select2({
        placeholder: 'Escriba Referencia',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?producto=true&tipo=ref',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }
   function autocoplet_desc(){
      $('#ddl_descripcion').select2({
        placeholder: 'Escriba Descripcion',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?producto=true&tipo=desc',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }

 
  function buscar_cod()
  {
      var  parametros = 
      { 
        'query':$('#txt_soli').val(),
        'tipo':'R1',
        'codigo':'',
      }    
      // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?buscar_edi_solicitante=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response != -1){       
           $('#txt_codigo').val(response.matricula);
           $('#txt_nombre').val(response.nombre);
           $('#txt_soli').append($('<option>',{value: response.ci, text:response.nombre,selected: true }));
           $('#txt_ruc').val(response.ci);
         }
      }
    });
  }

   function buscar_codi()
  {
      var  parametros = 
      { 
        'query':'<?php echo $cod; ?>',
        'tipo':'R1',
        'codigo':'',
      }    
      // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
       
           $('#txt_codigo').val(response.matricula);
           $('#txt_nombre').val(response.nombre);
           $('#txt_soli').append($('<option>',{value: response.Codigo, text:response.nombre,selected: true }));
           $('#txt_ruc').val(response.ci);
           cargar_pedido();
      }
    });
  }

  function producto_seleccionado(tipo)
  {
    if(tipo=='R')
    {
      var val = $('#ddl_referencia').val();
      var partes = val.split('_');
        $('#ddl_descripcion').append($('<option>',{value: partes[0]+'_'+partes[1]+'_'+partes[2]+'_'+partes[3]+'_'+partes[4]+'_'+partes[5]+'_'+partes[6], text:partes[2],selected: true }));
        $('#txt_precio').val(partes[1]); 
        $('#txt_iva').val(partes[6]); 
        $('#txt_unidad').val(partes[7]); 
        $('#txt_Stock').val(partes[8]);

        $('#txt_max').val(partes[9]);
        $('#txt_min').val(partes[10]); 

        if(partes[8]>=partes[10])
        {
          $('#txt_Stock').css('background-color','greenyellow');
        }else
        {
           $('#txt_Stock').css('background-color','coral');
        }
        // console.log($('#ddl_referencia').val());
    }else
    {
      var val = $('#ddl_descripcion').val();
      var partes = val.split('_');
        $('#ddl_referencia').append($('<option>',{value: partes[0]+'_'+partes[1]+'_'+partes[2]+'_'+partes[3]+'_'+partes[4]+'_'+partes[5]+'_'+partes[6], text:partes[0],selected: true }));

        // console.log($('#ddl_descripcion').val());
        $('#txt_precio').val(partes[1]); 
        $('#txt_iva').val(partes[6]);  
        $('#txt_unidad').val(partes[7]);
        $('#txt_Stock').val(partes[8]);
        $('#txt_max').val(partes[9]);
        $('#txt_min').val(partes[10]); 
         if(partes[8]>=partes[10])
        {
          $('#txt_Stock').css('background-color','greenyellow');
        }else
        {
           $('#txt_Stock').css('background-color','coral');
        }
        // console.log($('#ddl_descripcion').val());
    }

  }


  function autocoplet_area(){
      $('#ddl_areas').select2({
        placeholder: 'Seleccione una Area de descargo',
        ajax: {
          url:   '../controlador/farmacia/descargosC.php?areas=true',
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


  function Guardar()
  {
   var producto = $('#ddl_descripcion').val();
   var cc = $('#ddl_cc').val();
   var cc1 = cc.split('-');
   var ruc = $('#txt_ruc').val();
   var cc = $('#ddl_cc').val();
   var cos = $('#txt_precio').val();
   var are = $('#ddl_areas').val();
   var soli = $('#txt_soli').val();
   if(cc=='' || are =='' || soli=='')
   {
     Swal.fire('Ingrese todos los datos','','info');
     return false;
   }
   if(cos=='' || cos ==0)
   {
     Swal.fire('No se pudo agregar por que el costo de este articulo es igual 0.','','info');
     return false;
   }
    if(producto !='' && ruc!='' && cc!='')
    {
      if($('#txt_cant').val()<=0)
      {
        Swal.fire('','La cantidad Debe ser mayor que 0.','info');
        $('#txt_cant').val('1');
        return false;
      }
      // if(parseFloat($('#txt_cant').val()) > parseFloat($('#txt_Stock').val()))
      // {
      //   Swal.fire('','Stock insuficiente.','info');
      //   $('#txt_cant').val($('#txt_Stock').val());
      //   return false;
      // }
      var prod = producto.split('_');
    // console.log(producto);
    // return false;
       var parametros = 
       {
           'codigo':prod[0],
           'producto':prod[2],
           'cta_pro':prod[3],
           'uni':'',
           'cant':$('#txt_cant').val(),
           'cc':cc1[0],
           'rubro':$('#ddl_areas').val(),
           'bajas':'',
           'observacion':'',
           'id':$('#txt_num_item').val(),
           'ante':'',
           'fecha':$('#txt_fecha').val(),
           'bajas_por':'',
           'TC':prod[4],
           'valor':$('#txt_precio').val(),
           'total':$('#txt_importe').val(),
           'num_ped':$('#txt_pedido').val(),
           'ci':$('#txt_ruc').val(),
           'CodigoP':$('#txt_soli').val(),
           'descuento':0,
           'iva':$('#txt_iva').val(),
           'pro':'PEDIDO BODEGA',
           'area':$('#ddl_areas option:selected').text(),
           'solicitante':soli,
       };
       $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/farmacia/ingreso_descargosC.php?guardar_bod=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 

            // console.log(response);
           if(response.resp==null)
           {
            $('#txt_pedido').val(response.ped);
              Swal.fire({
                type:'success',
                title: 'Agregado a pedido',
                text :'',
              }).then( function() {
                   cargar_pedido();
                });

            // Swal.fire('','Agregado a pedido.','success');
            limpiar();
            // location.reload();
           }else
           {
            Swal.fire('','Algo extraño a pasado.','error');
           }           
         }
       });
    }else
    {
       Swal.fire('','Producto,Centro de costos ó Cliente no seleccionado.','error');
    }
  }




  function cargar_pedido()
  {
    var pedido = '<?php echo $num_ped;?>'
    if(pedido=='')
    {
      var num_ped =$('#txt_pedido').val();
      var area = $('#ddl_areas').val();
      var num_his = $('#txt_ruc').val();
      var pro = $('#ddl_areas option:selected').text();
    }else
    {
       var num_ped ='<?php echo $num_ped; ?>';
        var area = '<?php echo $area; ?>';
        var num_his = '<?php echo $cod; ?>';
        var pro = '<?php echo $pro; ?>';
    }

    var parametros=
    {
      'num_ped':num_ped,
      'area':area,
      'num_his':num_his,
      'paciente':$('#txt_soli').val(),
    }
    // console.log(parametros); return false;
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?pedido_bod=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        // console.log(response);
        num_ped = $('#txt_pedido').val();
        if(num_ped=='')
        {
           $('#tabla').html(response.tabla);
        }else{
          var ped = reload_();
          if(ped==-1)
          {
            num_ped = $('#txt_pedido').val();

            var mod = '<php echo $_SESSION["INGRESO"]["modulo_"]; ?>';
            var url="../vista/farmacia.php?mod="+mod+"&acc=descargos_bodega&acc1=Ingresar%20Descargos&b=1&po=subcu&area="+area+"-"+pro+"&num_ped="+num_ped+"&cod="+num_his;
            $(location).attr('href',url);
          }else
          {

             $('#txt_num_lin').val(response.num_lin);
            $('#txt_num_item').val(response.item);
            $('#tabla').html(response.tabla);
            $('#txt_neg').val(response.neg);
            $('#txt_sub_tot').val(response.subtotal);
            $('#txt_tot_iva').val(response.iva);
            $('#txt_pre_tot').val(response.total);
            $('#txt_procedimiento').val(response.detalle);
            if($('#txt_num_lin').val()!=0 && $('#txt_num_lin').val()!='')
            {
              $('#btn_comprobante').css('display','block');
            }

          }
        }
      }
    });
  }

  function reload_()
  {
    var url = location.href;
    let posicion = url.indexOf('num_ped');
    if (posicion !== -1)
    {
      return 1; //econtro
    }else{
      return -1; //no encontro
    }

  }

  function editar_lin(num)
  {
    var parametros=
    {
      'can':$('#txt_can_lin_'+num).val(),
      'pre':$('#txt_pre_lin_'+num).val(),
      'des':0,
      'tot':$('#txt_tot_lin_'+num).val(),
      'lin':num,
      'ped':$('#txt_pedido').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?lin_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          Swal.fire('Linea de pedido Editado.','','success');         
          cargar_pedido();
        }
      }
    });
  }
  function eliminar_lin(num)
  {
    var ruc = $('#txt_ruc').val();
    var cli = $('#ddl_paciente').text();
    // console.log(cli);
    Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            var parametros=
            {
              'lin':num,
              'ped':$('#txt_pedido').val(),
            }
             $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/farmacia/ingreso_descargosC.php?lin_eli=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                  cargar_pedido();
                }
              }
            });
        }
      });
  }

  function calcular_totales(num=false)
  {
    if(num)
    {
      var cant = parseFloat($('#txt_can_lin_'+num).val());
      var pre = $('#txt_pre_lin_'+num).val();
      var uti = parseFloat($('#txt_uti_lin_'+num).val());


      var sin_des = (cant*pre);
      var des = 0;
      var val_des = (sin_des*des)/100;
      var impo = parseFloat(sin_des-val_des);
      var iva =0; 
      // parseFloat($('#txt_iva_lin_'+num).val());
      var tot = $('#txt_tot_lin_'+num).val(parseFloat(impo));

       if(iva!=0 && uti!=0)
       {
         var sin_des = (cant*pre);
         var des = 0;
         var val_des = (sin_des*des)/100;
         var impo = parseFloat(sin_des-val_des);
         var tot_iva = ((impo*1.12)-impo);
         // console.log(tot_iva);
          $('#txt_iva_lin_'+num).val(0);
          $('#txt_tot_lin_'+num).val(parseFloat(impo).toFixed(4));
       }else if(uti!=0 && iva==0)
       {
         var sin_des = (cant*pre);
         var des = 0;
         var val_des = ((sin_des*des)/100);
         var impo = parseFloat(sin_des-val_des);
         var tot_iva = ((impo*1.12)-impo);
         // console.log(tot_iva);
          // $('#txt_iva_lin_'+num).val(parseFloat(tot_iva));
          $('#txt_tot_lin_'+num).val(parseFloat(impo).toFixed(4));
       }

    }else
    {
      // console.log('entr');
      var cant = $('#txt_cant').val();
      var pre = $('#txt_precio').val();
      var sin_des = (cant*pre);
      var des = 0;
      var val_des = (sin_des*des)/100;
      var tot = $('#txt_importe').val((sin_des-val_des).toFixed(2));

    }

      // descuentos();
  }

  function descuentos()
  {
    var num = $('#txt_num_lin').val();
    var item = $('#txt_num_item').val();
    var op = $('input:radio[name=rbl_des]:checked').val();

      // console.log(op);
    if(op=='L')
    {
       $('#txt_tot_des').val(0);
       var tot = 0;
       var sub = 0;
       var iva = 0;
      for (var i = 0; i <=item ; i++) {
            $('#txt_des_lin_'+i).attr("readonly", false);
            calcular_totales(i);
            if($('#txt_tot_lin_'+i).length)
            {
              var des = parseFloat($('#txt_des_lin_'+i).val());          
              pre = parseFloat($('#txt_pre_lin_'+i).val());
              can = parseFloat($('#txt_can_lin_'+i).val());
              uti = parseFloat($('#txt_uti_lin_'+i).val());
              sub+= parseFloat($('#txt_tot_lin_'+i).val());
              iva+=parseFloat($('#txt_iva_lin_'+i).val());
              tot+=((((pre*can)+uti)*des)/100);
            }
             $('#txt_tot_des').val(tot.toFixed(2))
             $('#txt_sub_tot').val(sub.toFixed(2));
             $('#txt_tot_iva').val(iva.toFixed(2));
      }

    }else if(op=='TL')
    {
      var des =$('#txt_des').val();
      var tot = 0;
      var sub = 0;
      var iva = 0;
      for (var i = 0; i <=item ; i++) {
            // console.log(i);
           
            if($('#txt_tot_lin_'+i).length)
            {
              $('#txt_des_lin_'+i).val(des);            
              $('#txt_des_lin_'+i).attr("readonly", true);
              calcular_totales(i);
              pre = parseFloat($('#txt_pre_lin_'+i).val());
              can = parseFloat($('#txt_can_lin_'+i).val());
              uti = parseFloat($('#txt_uti_lin_'+i).val());
              sub+= parseFloat($('#txt_tot_lin_'+i).val());
              iva+= parseFloat($('#txt_iva_lin_'+i).val());
              
              if(uti!=0)
              {
                tot+=(((can*uti)*des)/100);
              }else
              {
                tot+=(((can*pre)*des)/100);
              }
            }
      }
      $('#txt_tot_des').val(tot.toFixed(2))
      $('#txt_sub_tot').val(sub.toFixed(2));
      $('#txt_tot_iva').val(iva.toFixed(2));
    }else
    {
      var tot = 0;
      var des = parseFloat($('#txt_des').val());
      var iva = 0;
      for (var i = 0; i <=item ; i++) {
            // console.log(i);
            $('#txt_des_lin_'+i).attr("readonly", true);
            calcular_totales(i);
            if($('#txt_tot_lin_'+i).length)
            {
              
            // $('#txt_des_lin_'+i).val(0);
              tot = parseFloat($('#txt_tot_lin_'+i).val())+tot;
              iva+=parseFloat($('#txt_iva_lin_'+i).val());
            }
      }

      // console.log(iva);
      $('#txt_sub_tot').val(tot.toFixed(2));
      var des_t = ((tot*des)/100);
      $('#txt_tot_des').val(des_t.toFixed(2));
      $('#txt_tot_iva').val(iva.toFixed(2));


    }
    var sub = parseFloat($('#txt_sub_tot').val());
    var des = parseFloat($('#txt_tot_des').val());
    var iva = parseFloat($('#txt_tot_iva').val());
    $('#txt_pre_tot').val(((sub-des)+iva).toFixed(2));
  }

  function validar_pvp_costo(i)
  {
    var costo = $('#txt_pre_lin_'+i).val();
    var pvp = $('#txt_uti_lin_'+i).val();
    // console.log(costo);
    // console.log(pvp);
    if(parseFloat(pvp)< parseFloat(costo))
    {
      Swal.fire('','Precio de PVP debe ser mayor al costo.','error'); 
      $('#txt_uti_lin_'+i).focus();
      $('#txt_uti_lin_'+i).val(parseFloat(costo)+0.01);           
    }

  }

  function limpiar()
  {
    $('#txt_precio').val(0);
    $('#txt_cant').val(1);
    $('#txt_descuento').val(0);
    $('#txt_importe').val(0);
    $('#txt_precio').val(0);
    // $('#txt_precio').val(0);
    $("#ddl_referencia").empty();
    $("#ddl_descripcion").empty();
    $("#txt_iva").val(0);
  }

  function generar_factura(fecha)
  {

    // if($('#txt_neg').val()=='true')
    // {
    //   Swal.fire('','Tiene Stocks en negativos Ingrese el producto faltante.','info'); 
    //   return false;
    // }

    // $('#myModal_espera').modal('show');    
    var orden = $('#txt_pedido').val();
    var ruc= $('#txt_soli').val();
    var area= $('#ddl_areas').val();
    var his= $('#txt_codigo').val();
    var pro = '<?php echo $pro; ?>';
    var nombre=  $('#txt_soli option:selected').text();

    var reg=  $('#txt_num_lin').val();
     $.ajax({
      data:  {orden:orden,ruc:ruc,area:area,nombre:nombre,fecha:fecha},
      url:   '../controlador/farmacia/ingreso_descargosC.php?facturar_bodega=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {    
      $('#myModal_espera').modal('hide');    
        if(response.resp==1)
        {
          cargar_pedido();
          Swal.fire({
            title: 'Comprobante '+response.com+' generado.',
            type:'success',
            showDenyButton: true,
            showCancelButton: false,
            allowOutsideClick:false,
            confirmButtonText: `OK`,
            denyButtonText: `Don't save`,
          }).then((result) => {

            var mod = '<php echo $_SESSION["INGRESO"]["modulo_"]; ?>';
              if (result.isConfirmed) {
                if(reg==0)
                {
                var url="../vista/farmacia.php?mod="+mod+"&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&area="+area+"-"+pro+"&num_ped="+orden+"&cod="+his+"#";
                }else
                {
                  var url="../vista/farmacia.php?mod="+mod+"&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&area="+area+"-"+pro+"&num_ped="+orden+"&cod="+his+"#";
                }
                $(location).attr('href',url);             
                  } else
                  {
                    if(reg==0)
                {
                 var url="../vista/farmacia.php?mod="+mod+"&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&area="+area+"-"+pro+"&cod="+his+"#";
                }else
                {
                  var url="../vista/farmacia.php?mod="+mod+"&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&area="+area+"-"+pro+"&num_ped="+orden+"&cod="+his+"#";
                }
                
                   
                  $(location).attr('href',url);
                  }
            })    


        }else if(response.resp==-3)
        {
          Swal.fire('','Esta salida tiene mas de una fecha','info'); 
          cargar_pedido();
        }
        else
        {
          Swal.fire('','No se pudo generado.','info'); 
        }
      }
    });

  }

  function cambiar_procedimiento()
  {
    $('#modal_procedimiento').modal('show');
  }

  function guardar_new_pro()
  {

    $('#modal_procedimiento').modal('show');
    var orden = $('#txt_pedido').val();
    var new_pro = $('#txt_new_proce').val();
    if(orden!='')
    {
      cambiar_proce_orden(new_pro);
    }else
    {
      $('#txt_procedimiento').val(new_pro);
    $('#modal_procedimiento').modal('hide');
    }
  }

  function cambiar_proce_orden(pro)
  {
    var parametros=
    {
      'text':pro,
      'ped':$('#txt_pedido').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?edi_proce=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          cargar_pedido();
          $('#modal_procedimiento').modal('hide');
        }else
        {

        }
      }
    });
  }

  // function generar_informe()
  // {
  //   var url =  '../controlador/farmacia/ingreso_descargosC.php?imprimir_pdf=true',
  //  var datos = ;
  //   window.open(url+datos, '_blank');
  //    $.ajax({
  //        data:  {datos:datos},
  //        url:   url,
  //        type:  'post',
  //        dataType: 'json',
  //        success:  function (response) {  
          
  //         } 
  //      });
  // }



   function num_comprobante()
   {
    var fecha = $('#txt_fecha').val();
     $.ajax({
       data:  {fecha:fecha},
      url:   '../controlador/farmacia/articulosC.php?num_com=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        $('#num').text(response);
      }
    });

   }

   function mayorizar_inventario()
  {
    $('#myModal_espera').modal('show');
     $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?mayorizar=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        $('#myModal_espera').modal('hide');
        Swal.fire('Mayorizacion completada','','success');
      
      }
    });
  }

</script>
  <div class="row">
    <div class="col-lg-8 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <!-- <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button href="#" title="Generar reporte pdf"  class="btn btn-default" onclick="generar_informe()">
            <img src="../../img/png/impresora.png" >
          </button>
        </div>  -->
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=pacientes&acc1=Visualizar%20paciente&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_pdf" title="Pacientes">
            <img src="../../img/png/pacientes.png">
          </a>           
        </div>
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_excel" title="Descargos">
            <img src="../../img/png/descargos.png">
          </a>         
        </div>        
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulos"  class="btn btn-default" onclick="">
            <img src="../../img/png/articulos.png" >
          </a>
        </div> 
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button title="Mayorizar Articulos"  class="btn btn-default" onclick="mayorizar_inventario()">
            <img src="../../img/png/update.png" >
          </button>
        </div> 
 </div>
</div>
<div class="row">
  <div class="col-sm-12">
     <div class="panel panel-primary">
      <div class="panel-heading">
        <div class="row">
         <div class="col-sm-6 text-right"><b>DESCARGO DE BODEGA</b></div>         
         <div class="col-sm-6 text-right"> No. COMPROBANTE  <u id="num"></u></div>        
        </div>
      </div>
      <div class="panel-body" style="border: 1px solid #337ab7;">
        <div class="row">
          <div class="col-sm-3"> 
            <b>Num Historia clinica:</b>
            <input type="text" name="txt_codigo" id="txt_codigo" class="form-control input-xs" readonly="">      
          </div>
          <div class="col-sm-6">
            <b>Solicitante:</b>
            <!-- <input type="text" name="txt_nombre" id="txt_nombre" class="form-control input-xs"> -->
            <!-- <select class="form-control input-xs" id="ddl_paciente" onchange="buscar_cod()">
              <option value="">Seleccione paciente</option>
            </select> -->
             <select class="form-control" name="txt_soli" id="txt_soli" onchange="buscar_cod()">
                  <option value="">Seleccione solicitante</option>
            </select>
          </div>
          <div class="col-sm-3">
            <b>RUC:</b>
            <input type="text" name="txt_ruc" id="txt_ruc" class="form-control input-xs">             
          </div>          
        </div>
      </div>
       <div class="panel-body">
        <div class="row">
          <div class="col-sm-4"> 
            <b>Centro de costos:</b>
            <select class="form-control input-xs" id="ddl_cc" onchange="')">
              <option value="">Seleccione Centro de costos</option>
            </select>           
          </div>
          <div class="col-sm-2">    
          <b>Numero de pedido</b>
          <input type="text" name="" id="txt_pedido" readonly="" class="form-control input-xs" value="<?php echo $num_ped;?>">     
          </div>
          <div class="col-sm-3">
             <b>Fecha:</b>
            <input type="date" name="txt_fecha" id="txt_fecha" class="form-control input-xs" value="<?php echo date('Y-m-d')  ?>" onblur="num_comprobante()">                 
          </div>
          <div class="col-sm-3">
            <b>Area de descargo</b>
            <select class="form-control input-xs" id="ddl_areas">
              <option value="">Seleccione motivo de ingreso</option>
            </select>            
          </div>          
        </div>
        <div class="row">
          <div class="col-sm-4"> 
            <b>Cod Producto:</b>
            <select class="form-control input-xs" id="ddl_referencia" onchange="producto_seleccionado('R')">
              <option value="">Escriba referencia</option>
            </select>           
          </div>
          <div class="col-sm-5"> 
                <b>Descripcion:</b>
                <select class="form-control input-xs" id="ddl_descripcion" onchange="producto_seleccionado('D')">
                  <option value="">Escriba descripcion</option>
                </select>          
              </div> 
          <div class="col-sm-3"> 
           <!--  <b>Procedimiento:</b>
            <div class="input-group input-group-sm">
                <textarea class="form-control input-xs" style="resize: none;" name="txt_procedimiento" id="txt_procedimiento" readonly=""></textarea>          
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onclick="cambiar_procedimiento()"><i class="fa fa-pencil"></i></button>
                    </span>
              </div> -->
           
          </div>           
        </div>
        <div class="row">
               <div class="col-sm-2"> 
                  <div class="col-sm-6"> 
                    <b>MIN:</b>
                    <input type="text" name="txt_min" id="txt_min" class="form-control input-xs"readonly="">
                  </div>
                  <div class="col-sm-6"> 
                    <b>MAX:</b>
                    <input type="text" name="txt_max" id="txt_max" class="form-control input-xs"readonly="">
                  </div>   
                  
              </div>               
              <div class="col-sm-2"> 
                <b>Costo:</b>
                <input type="text" name="txt_precio" id="txt_precio" class="form-control input-xs" value="0" onblur="calcular_totales();" readonly="">            
              </div>   
              <div class="col-sm-1"> 
                <b>Cantidad:</b>
                <input type="text" name="txt_cant" id="txt_cant" class="form-control input-xs" value="1" onblur="calcular_totales();">            
              </div>   
              <div class="col-sm-1"> 
                <b>UNI:</b>
                <input type="text" name="txt_unidad" id="txt_unidad" class="form-control input-xs" readonly="">            
              </div>
              <div class="col-sm-1"> 
                <b>Stock:</b>
                <input type="text" name="txt_Stock" id="txt_Stock" class="form-control input-xs" readonly="">            
              </div>    
              <div class="col-sm-1"> 
                <b>Importe:</b>
                <input type="text" name="txt_importe" id="txt_importe" class="form-control input-xs" readonly="">
                <input type="hidden" name="txt_iva" id="txt_iva" class="form-control input-xs">            
              </div> 
              <div class="col-sm-3"> 
                <!-- <b>Persona solicitante:</b>
                <select class="form-control" name="txt_soli" id="txt_soli">
                  <option value="">Seleccione solicitante</option>
                </select> -->
              </div>   
              <div class="col-sm-1" style="padding-left: 0px;"><br>
                <button class="btn btn-primary btn-sm" onclick="calcular_totales();Guardar()"><i class="fa fa-arrow-down"></i> Agregar</button>
              </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-12">
    <div class="table-responsive">
      <input type="hidden" name="" id="txt_num_lin" value="0">
      <input type="hidden" name="" id="txt_num_item" value="0">
      <input type="hidden" name="txt_neg" id="txt_neg" value="false">
      <div id="tabla">
        
      </div>
      

         
    </div>
    
  </div>

<div class="modal fade" id="modal_procedimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Cambiar procedimiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div class="row">
          <div class="col-sm-12">
            Nombre de procedimiento
            <input type="text" class="form-control input-xs" name="txt_new_proce" id="txt_new_proce">
          </div>        
         </div> 
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="guardar_new_pro();">Guardar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>
