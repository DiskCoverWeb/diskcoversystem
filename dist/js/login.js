$(document).ready(function(){
 	$('#txt_contra').on('keypress', function(e) {
            if (e.which === 13) {
               /*Ingresar();*/Ingresar_vali();
            }
        });
});

function reabrir_seleccion_empresa() {
	var entidad = $("#txt_entidad").val();
	if (entidad !== undefined && entidad !== null) {
		entidad = entidad.trim();
	} else {
		entidad = '';
	}

	if (!entidad) {
		$("#txt_entidad").focus();
		Swal.fire({
			icon: 'info',
			title: 'RUC/NIC Entidad',
			text: 'Por favor, ingrese primero el RUC o NIC de la entidad para seleccionar su empresa.',
			confirmButtonColor: '#0d6efd'
		});
		return;
	}

	// Si las empresas ya están cargadas en el modal para esta entidad, abrir directamente
	if ($('#tbl_empresas .company-card').length > 0 && $('#lbl_ruc').text() === entidad) {
		$('#mis_empresas').modal('show');
	} else {
		validar_entidad();
	}
}

function validar_entidad()
  { 
	var entidad = $("#txt_entidad").val();

     $.ajax({
      data:  {'entidad':entidad},
      url:   '../controlador/loginC.php?Cartera_Entidad=true&pantalla='+screen.height,
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) { 
         if(response==-2)
         {     	
	  		$('#lbl_razon').text('Diskcover system');
	  		$('#img_logo').removeAttr('style');
	  		$('#img_logo').attr('src','../../img/jpg/logo.jpg');
	        $('#txt_entidad_id').val('');	        
	  		$('#lbl_nombre').css('display','none');
	        Swal.fire('Error Entidad!','"La entidad que ingresaste no tiene el formato correcto.','error');
         }
        if(response.length==1)
        {
        	$('#alerta').css('display','block');
        	$('#txt_item').val(response[0].Item);
        	if(response[0].Nombre == response[0].Razon_Social)
					{

        	$('#lbl_razon').text(response[0].Nombre);
        		$('#alerta').html(response[0].Nombre);
        	}else
        	{

        		$('#lbl_razon').text(response[0].Razon_Social);
        		$('#lbl_nombre').text(response[0].Nombre)
        		$('#alerta').html(response[0].Razon_Social+'<br>'+response[0].Nombre);   
        		$('#alerta').css('font-size','10px');    
        		$('#lbl_nombre').css('display','block');    		
        	}
			var newImgSrc = response[0].Logo + "?timestamp=" + new Date().getTime();
        	$('#img_logo').attr('src',newImgSrc);
        	$('#img_logo').css({'max-height': '68px', 'max-width': '240px', 'width': 'auto', 'border-radius': '6px'});
        	$('#txt_entidad_id').val(response[0].entidad);
        	$('#txt_cartera').val('0');


        }else if(response.length>1)
        {
        	 $('#lbl_ruc').text(entidad);
        	 if ($('#lbl_total_empresas').length) {
        	 	 $('#lbl_total_empresas').text(response.length + ' empresas disponibles');
        	 }
        	 tr = '';
        	 response.forEach(function(item, i) {
				var uniqueImgSrc = item.Logo + '?v=' + new Date().getTime();
				var itemNombre = item.Nombre ? item.Nombre.replace(/'/g, "\\'") : '';
				var itemRazon = item.Razon_Social ? item.Razon_Social.replace(/'/g, "\\'") : '';

				tr += '<div class="company-card card mb-2 p-2 p-md-3 cursor-pointer shadow-xs" '
					+ 'onclick="seleccionar_empresa(\'' + itemNombre + '\',\'' + itemRazon + '\',\'' + uniqueImgSrc + '\',\'' + item.entidad + '\',\'' + item.Item + '\')">'
					+ '<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">'
						+ '<div class="d-flex align-items-center gap-3">'
							+ '<div class="company-logo-frame">'
								+ '<img src="' + uniqueImgSrc + '" alt="Logo" onerror="this.src=\'../../img/jpg/logo.jpg\';" />'
							+ '</div>'
							+ '<div>'
								+ '<h6 class="mb-1 fw-bold text-dark font-14">' + item.Razon_Social + '</h6>'
								+ '<p class="mb-1 font-13 text-secondary d-flex align-items-center gap-1">'
									+ '<i class="bx bx-store-alt text-primary"></i> ' + item.Nombre
								+ '</p>'
								+ '<div class="d-flex align-items-center gap-2 flex-wrap">'
									+ '<span class="badge bg-light text-secondary border font-11"><i class="bx bx-id-card me-1"></i>RUC: ' + item.ci + '</span>'
									+ '<span class="badge bg-primary-subtle text-primary border border-primary-subtle font-11">Item: ' + item.Item + '</span>'
								+ '</div>'
							+ '</div>'
						+ '</div>'
						+ '<div class="ms-auto">'
							+ '<button type="button" class="btn btn-sm btn-outline-primary btn-select-company rounded-pill px-3 fw-bold d-flex align-items-center gap-1 shadow-sm">'
								+ '<i class="bx bx-check-circle"></i> <span>Seleccionar</span>'
							+ '</button>'
						+ '</div>'
					+ '</div>'
				+ '</div>';
			});
        	 $('#tbl_empresas').html(tr);
        	 $('#txt_buscar_empresa').val('');
        	 $('#mis_empresas').modal('show');
        }else
        {
	        	$('#lbl_razon').text('Diskcover system');
		  		$('#img_logo').removeAttr('style');
		  		$('#img_logo').attr('src','../../img/jpg/logo.jpg');
		        $('#txt_entidad_id').val('');	        
		  		$('#lbl_nombre').css('display','none');
        		Swal.fire('Error Entidad!','No se a encontrado la entidad.','error');
        }

      }
    });

  }

  function seleccionar_empresa(Nombre,Razon_Social,Logo,entidad,item)
  {
  		$('#mis_empresas').modal('hide');
  		$('#lnk_volver_elegir').removeClass('d-none');

  	 	$('#txt_item').val(item);
	  	if(Nombre == Razon_Social)
		{
	  		$('#lbl_razon').text(Nombre);
	  		$('#lbl_nombre').css('display','none');
	  	}else
	  	{
	  		$('#lbl_nombre').css('display','block');

	  		$('#lbl_razon').text(Razon_Social);
	  		$('#lbl_nombre').text(Nombre);      		
	  	}

	  	$('#img_logo').attr('src',Logo);
	  	$('#img_logo').css({'max-height': '68px', 'max-width': '240px', 'width': 'auto', 'border-radius': '6px'});
	  	$('#txt_entidad_id').val(entidad);
	  	$('#txt_cartera').val('0');
	  	$('#txt_correo').focus();

	  	// var parametros = 
	  	// {
	  	// 	'empresa':Nombre,
	  	// 	'item_cartera':item,
	  	// }

	  	//  $.ajax({
	    //   data:  {parametros:parametros},
	    //   url:   '../controlador/login_controller.php?setear_empresa=true',
	    //   type:  'post',
	    //   dataType: 'json',
	    //   /*beforeSend: function () {   
	    //        var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
	    //      $('#tabla_').html(spiner);
	    //   },*/
	    //     success:  function (response) { 
	        
	    //   }
	    // });
  }
  
  function validar_usuario()
  { 

		 var usuario = $("#txt_correo").val();
		 var entidad = $("#txt_entidad_id").val();		 
		 var item = $("#txt_item").val();

		 if(usuario =='')
		 {
		 	return false;
		 }
		 if(entidad=='')
		 {
		 	return false;
		 }
		 var parametros = 
		 {
		 	 'usuario':usuario,
		 	 'entidad':entidad,
		 	 'item':item,
		 }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/loginC.php?Validar_Usuario=true',
      type:  'post',
      dataType: 'json',     
        success:  function (response) { 
        	// console.log(response);
        if(response.respuesta == -1)
        {
            $('#txt_cartera').val('1');
            $('#correo_cartera').val(response.cartera_usu);
			$('#contra_cartera').val(response.cartera_pass);
      }else if(response.respuesta==-2)
        {      	
        	$('#txt_cartera').val('0');
        	Swal.fire('Este usuario se encuentra bloqueado!','Usuario bloqueado.','error');
        }else if(response.respuesta==1)
        {
        	$('#txt_cartera').val('0');
        }     
      }
    });

  }

  function Ingresar_vali()
  {
  	if($('#txt_cartera').val()==1)
  	{
  		 Swal.fire({
           title: 'Esta seguro?',
           text: "Esta apunto de entrar a la cartera de clientes!",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Si!'
         }).then((result) => {
           if (result.value==true) {
				Ingresar();
           }else
           {
           		$('#txt_correo').val('');
           		$('#txt_contra').val('');
           		$('#txt_cartera').val(0);           	 
           }
         })

  	}else
  	{
		Ingresar();	
  	}
  }

  function Ingresar()
  { 

		 var usuario = $("#txt_correo").val();
		 var entidad = $("#txt_entidad_id").val();
		 var item = $("#txt_item").val();
		 var pass = $("#txt_contra").val();		 
		 var cartera = $("#txt_cartera").val();
		 var cartera_usu = $("#correo_cartera").val();
		 var cartera_pass = $("#contra_cartera").val();
		 var ci_empresa = $("#txt_entidad").val();
		 if(entidad =='')
		 {
		 		Swal.fire('No se a verificado la entidad Asegurese de colocar una entidad valida','Se volvera a verificar la empresa','info').then(function(){ $('#entidad').focus()});
		 	return false;
		 }
		 if(pass=='' || entidad=='')
		 {
		 	Swal.fire('Llene todo los campos','Asegurese de colocar una entidad, usuario y password validos','info')
		 	return false;
		 }

		 var localIp;
		 var pc = new RTCPeerConnection();
      pc.createDataChannel('');
      pc.createOffer(function(sdp) {
          pc.setLocalDescription(sdp);
      }, function() {});
      pc.onicecandidate = function(event) {
        if (event.candidate) {
            var ipRegex = /(\d+\.\d+\.\d+\.\d+)/;
            let a = ipRegex.exec(event.candidate.candidate);
            if(a){
            	localIp = a[1];
            }
				}
			}

		
			
		 var parametros = 
		 {
		 	 'usuario':usuario,
		 	 'entidad':entidad,
		 	 'item':item,
		 	 'empresa':ci_empresa,
		 	 'pass':pass,
		 	 'cartera':cartera,
		 	 'cartera_usu':cartera_usu,
		 	 'cartera_pass':cartera_pass,
		 	 'localIp':localIp,
			 'ipWAN': ""
		 }


      $('#btn_ingreso').attr('disabled',true);
      $('#loader_ingreso').css('display','inline-block');

     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/loginC.php?Ingresar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) { 

        	console.log(response);
        if(response.rps==-1)
        {
        	Swal.fire('Clave o usuario invalidos!','No se pudo acceder. Comuniquese con la institucion para ser habilitado.','error');
        	$('#btn_ingreso').attr('disabled',false);
      		$('#loader_ingreso').css('display','none');
        }else if(response.rps==-2)
        {        	
        	Swal.fire('Clave o usuario invalidos!','No se pudo acceder.  Comuniquese con la institucion para ser habilitado.','error');
        	$('#btn_ingreso').attr('disabled',false);
      		$('#loader_ingreso').css('display','none');
        }else if(response.rps==-3)
        {     	
        	Swal.fire('',response.mensaje,'error');
        		$('#btn_ingreso').attr('disabled',false);
      		$('#loader_ingreso').css('display','none');
        }
        else if(response.rps==1)
        {     	
        	// console.log(response); return false;
        	window.location.href = "modulos.php";
        }     
      },
	    error: function (error) {
	      console.error('Error en numero_comprobante:', error);
      		$('#btn_ingreso').attr('disabled',false);
      		$('#loader_ingreso').css('display','none');
	    },
    });

  }


    function logout() {

      $.ajax({
        // data:  {parametros:parametros},
        url: '../controlador/loginC.php?logout=true',
        type: 'post',
        dataType: 'json',
        /*beforeSend: function () {   
             var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
           $('#tabla_').html(spiner);
        },*/
        success: function (response) {
          console.log(response);
          if (response == 1) {
            location.href = 'login.php';
          }
        }
      });
    }
  

  function Recuperar()
  { 

		 var usuario = $("#txt_usuario").val();
		 var entidad = $("#txt_entidad_id").val();
		 var cartera = $("#txt_cartera").val();
		 var ci_empresa = $("#txt_entidad").val();
		 if(entidad =='')
		 {
		 		Swal.fire('No se a verificado la entidad Asegurese de colocar una entidad valida','Se volvera a verificar la empresa','info').then(function(){ $('#entidad').focus()});
		 	return false;
		 }
		 if(entidad=='')
		 {
		 	Swal.fire('Llene todo los campos','Asegurese de colocar una entidad, usuario y password validos','info')
		 	return false;
		 }
		 
		 var parametros = 
		 {
		 	 'usuario':usuario,
		 	 'entidad':entidad,
		 	 'empresa':ci_empresa,		 	
		 }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/loginC.php?recuperar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) { 
        	console.log(response);
        if(response==-1 || response.respuesta==-1)
        {
        	Swal.fire('Usuario invalidos!','No se pudo recuperar.','error');
        }else if(response.respuesta==-2)
        {        	
        	Swal.fire('Clave o usuario de cartera invalidos!','No se pudo acceder.','error');
        }else if(response.respuesta==2)
        {        	
        	Swal.fire('¡Disculpe!','No fue posible enviar el correo. No se pudo obtener la información de las empresas','error');
        }
        else
        {      	
        	var ema = response.email;
       		if(ema!='' && ema !='.')
       		{
       			"intimundosa@hotmail.com"
       			var ini = ema.substring(0,4);
       			var divi = ema.split('@');
       			var num_car =  divi[0].substring(4).length;
       			// num_car = num_car
       			var medio = '';
       			for (var i = 0; i < num_car; i++) {
       				medio+='*';       				
       			}
       			var fin = divi[1];
       		}
       			// console.log(ini+medio+fin);

       		 // $('#lbl_email').text(ini+medio+'@'+fin);


        	// console.log(response); return false;
        	Swal.fire('Sus credenciales han sido enviadas al email <br>'+ini+medio+'@'+fin,'','success').then(function(){
        		location.href = 'login.php';
        	});
        }     
      }
    });

  }

function filtrarEmpresasModal(query) {
	var q = (query || '').toLowerCase().trim();
	$('.company-card').each(function() {
		var text = $(this).text().toLowerCase();
		if (text.indexOf(q) !== -1) {
			$(this).show();
		} else {
			$(this).hide();
		}
	});
}	
