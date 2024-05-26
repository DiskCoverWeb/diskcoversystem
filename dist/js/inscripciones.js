document.addEventListener("DOMContentLoaded", function (){
    let area = $('#form-contenedor').parent().parent();
    area.css('background-color', 'rgb(251, 241, 221)');
    area.css('overflow-y', 'scroll');
    $('#contenedor-cf').parent().css('position', 'relative');
    $.ajax({
        type: "GET",
        url: '../controlador/inscripciones/voluntarios.php?CatalogoForm=true',
        //data: { 'fafactura': faFactura },
        dataType: 'json',
        success: function (data) {
            //console.log(data);
            cargarEncabezado();
            construirFormulario(data);
            setRestricciones();
            $('#contenedor-cf').css('visibility', 'hidden');
        }
    });
})

function cargarEncabezado(){
    let encabezado = `
    <div class="f-seccion encabezado">
        <h1>Formulario de Inscripción Voluntario Operativo</h1>
        <p>
            Estimado/a postulante por favor llenar el formulario 
            correctamente, si al final le falta completar algún 
            campo el sistema no le permitirá enviar la información. 
            Lea detenidamente la información de cada una de los campos 
            y secciones.
        </p>
    </div>
    `;
    $('#form-contenedor').html(encabezado);
}

function construirFormulario(data){
    let seccionesForm = [];
    for(d of data){
        let rCodSplit = d['Codigo'].split('.');
        if(rCodSplit.length < 3){
            let dgrupo = d;
            if(rCodSplit.length == 2 && d['DG'] == "G"){
                dgrupo['Respuestas'] = new Array();
            }
            seccionesForm.push(dgrupo);
        }else if(rCodSplit.length == 3){
            seccionesForm[seccionesForm.length-1]['Respuestas'].push(d);
            
        }
    }
    
    let tiposInput = {
        "T": 'text',
        "B": 'radio',
        "D": 'date',
        "N": 'number',
        "%": 'number',
        "M": 'checkbox',
        "F": 'file'
    };
    let cuerpoHtml = `<div class="f-cuerpo">`;

    for(seccion of seccionesForm){
        let rCodSplit = seccion['Codigo'].split('.');
        if(rCodSplit.length == 1){
            cuerpoHtml += `
                <div class="f-seccion fc-pregunta">
                    <h2>${capitalizarString(seccion['Cuenta'])}</h2>
                    <p>${(seccion['Comentario'] == '.' || seccion['Comentario'] == null) ? '' : seccion['Comentario']}</p>
                    <img class="center-block" src="../../img/inscripcion/${seccion['Imagen']}.png" alt="" height="250px">
                </div>
            `;
        }else{
            cuerpoHtml += `
                <div class="f-seccion fc-pregunta">
                    <div class="f-sec-label">
                        ${capitalizarString(seccion['Cuenta'])} <span class="f-label-obl">*</span>
                    </div>
                    <p>${(seccion['Comentario'] == '.' || seccion['Comentario'] == null) ? '' : seccion['Comentario']}</p>
                    <div class="f-sec-input">
                `;
            if(seccion.hasOwnProperty('Respuestas')){
                for(respuestas of seccion['Respuestas']){
                    cuerpoHtml += `
                        <input class="f-input f-input-${tiposInput[respuestas['Tipo']]}" type="${tiposInput[respuestas['Tipo']]}" name="${seccion['Codigo']}" id="${respuestas['Codigo']}" value="${respuestas['Cuenta']}"> <label for="${respuestas['Codigo']}"> ${capitalizarString(respuestas['Cuenta'])}</label><br/>
                    `;
                }
            }else{
                let esPorcentaje = (seccion['Tipo']=='%');
                cuerpoHtml += `
                    <input class="f-input f-input-${esPorcentaje?'perc':tiposInput[seccion['Tipo']]}" type="${tiposInput[seccion['Tipo']]}" id="${seccion['Codigo']}" name="${seccion['Codigo']}" ${esPorcentaje?'min="0" max="100"':''}>${esPorcentaje?'   %':''}
                `;
            }
            cuerpoHtml += `</div></div>`;
        }
    }


    cuerpoHtml += `
            <div class="form-footer">
                <button class="env-insc-form" onclick="enviarFormInsc()">Enviar</button>
                <button class="reset-insc-form" onclick="resetFormInsc()">Borrar formulario</button>
            </div>
        </div>
    `;
    $('#form-contenedor').append(cuerpoHtml);
}

function setRestricciones(){
    // RESTRICCIONES INPUT FILES
    document.querySelectorAll(".f-input-file").forEach(elem => elem.addEventListener('change', (e)=> {
        let archivo = e.target.files[0];
        if (archivo && /\s+/g.test(archivo.name)) {
            const p = document.createElement("p");
            const text = document.createTextNode("* No se pudo seleccionar el archivo debido a que su nombre tiene espacios.");
            p.appendChild(text);
            p.id = `f-error-${e.target.id}`;
            p.classList.add('f-error-file');
            e.target.parentElement.parentElement.appendChild(p);
            e.target.value = "";
        }else{
            document.getElementById(`f-error-${e.target.id}`).remove();
        }
    }));

    //RESTRICCIONES CAMPO EDAD
    document.getElementById('01.07').setAttribute('disabled', 'true');
    document.getElementById('01.06').addEventListener('change', (e) => {
        let hoy = new Date();
        let fNac = new Date(e.target.value);
        let edad = hoy.getFullYear() - fNac.getFullYear();
        let m = hoy.getMonth() - fNac.getMonth();

        if (m < 0 || (m === 0 && hoy.getDate() < fNac.getDate())) {
            edad--;
        }
        document.getElementById('01.07').value = parseInt(edad);
    })

    //RESTRICCIONES CAMPOS OBLIGATORIOS
    document.getElementsByName('02.12')[0].parentElement.parentElement.children[0].children[0].remove();
    document.getElementsByName('04.08')[0].parentElement.parentElement.children[0].children[0].remove();
    document.getElementsByName('04.10')[0].parentElement.parentElement.children[0].children[0].remove();
}

function capitalizarString(cadena){
    return cadena.toLowerCase().replace(/^\w|(?<=\s)\w/g, l => l.toUpperCase()); //#TODO: Capitalizar \w luego de ?
}

function getEstadoCivil(valor){
    if(valor.includes(" ")){
        return `${valor.split(" ")[0][0]}${valor.split(" ")[2][0]}`;
    }else{
        return valor.split(" ")[0][0];
    }
}


// TODO: Implementar funcion para la respuesta "Otros"
function selectOtros(){
    let elem = document.getElementById('b-01.05.03');
    setTimeout(() => {
        if(elem.checked == true){
            elem.nextElementSibling.nextElementSibling.removeAttribute('disabled')
        }else{
            elem.nextElementSibling.nextElementSibling.setAttribute('disabled', 'true')
        }
    }, 200);
}

function resetFormInsc(){
    document.querySelectorAll('.f-input').forEach(elem => {
        switch(elem.type){
            case 'text':
            case 'date':
            case 'number':
            case 'file':
                elem.value = '';
                break;
            case 'radio':
            case 'checkbox':
                elem.checked = false;
                break;
        }
    });
}

function enviarFormInsc(){
    let servicio_basico = new Array();
    document.querySelectorAll('input[name="04.19"]:checked').forEach(el => {
        servicio_basico.push(el.value);
    });
    let parametros = {
        cliente: `${document.getElementById('01.01').value} ${document.getElementById('01.02').value}`.toUpperCase(),
        telefono: document.getElementById('01.03').value.toUpperCase(),
        cedula: document.getElementById('01.04').value.toUpperCase(),
        genero: document.querySelector('input[name="01.05"]:checked').value[0],
        fecha_nacimiento: document.getElementById('01.06').value,
        //edad: document.getElementById('01.07').value,
        ciudadania: document.querySelector('input[name="01.08"]:checked').value.substr(0,4),
        estado_civil: getEstadoCivil(document.querySelector('input[name="01.09"]:checked').value),
        estado_lactancia: document.querySelector('input[name="02.01"]:checked').value,
        estado_gestacion: document.querySelector('input[name="02.02"]:checked').value,
        toma_medicina: document.querySelector('input[name="02.03"]:checked').value=="SI"?1:0,
        medicamento: document.getElementById('02.04').value.toUpperCase(),
        dosis: document.getElementById('02.05').value.toUpperCase(),
        alergia: document.querySelector('input[name="02.06"]:checked').value=="SI"?1:0,
        prod_alergia: document.getElementById('02.07').value.toUpperCase(),
        nombre_conyugue: document.getElementById('02.08').value.toUpperCase(),
        cedula_conyugue: document.getElementById('02.09').value.toUpperCase(),
        nombre_emergencia: document.getElementById('02.10').value.toUpperCase(),
        telefono_emergencia: document.getElementById('02.11').value.toUpperCase(),
        parentesco_emergencia: document.querySelector('input[name="02.12"]:checked').value, //No va a dar
        canton: document.querySelector('input[name="03.01"]:checked').value,
        parroquia: document.getElementById('03.02').value.toUpperCase(),
        barrio: document.getElementById('03.03').value.toUpperCase(),
        direccion: `${document.getElementById('03.04').value} y ${document.getElementById('03.05').value}`.toUpperCase(),
        num_casa: document.getElementById('03.06').value,
        personas_domicilio: document.getElementById('04.01').value,
        num_hijos: document.getElementById('04.02').value,
        hijos_mayores: document.getElementById('04.03').value,
        hijos_menores: document.getElementById('04.04').value,
        discapacidad: document.querySelector('input[name="04.05"]:checked').value=="SI"?1:0,
        tipo_discapacidad: document.querySelector('input[name="04.06"]:checked').value,
        porc_discapacidad: document.getElementById('04.07').value,
        conadis: document.getElementById('04.08').value,
        familiar_discapacidad: document.querySelector('input[name="04.09"]:checked').value=="SI"?1:0,
        parentesco_fdiscapacidad: document.querySelector('input[name="04.10"]:checked').value,
        conadis_familiar: document.getElementById('04.11').value,
        enfermedad: document.getElementById('04.12').value.toUpperCase(),
        nivel_estudio: document.querySelector('input[name="04.13"]:checked').value,
        ocupacion: document.getElementById('04.14').value.toUpperCase(),
        bono: document.querySelector('input[name="04.15"]:checked').value=="SI"?1:0,
        jubilacion: document.querySelector('input[name="04.16"]:checked').value=="SI"?1:0,
        actividad_economica: document.getElementById('04.17').value.toUpperCase(),
        vivienda: document.querySelector('input[name="04.18"]:checked').value,
        servicios_basicos: servicio_basico.join(','),
        cedula_pdf: document.getElementById('05.01').files[0].name,
        record_policial: document.getElementById('05.02').files[0].name,
        planilla_sbasico: document.getElementById('05.03').files[0].name,
        carta_recomendacion: document.getElementById('05.04').files[0].name,
        certificado_medico: document.getElementById('05.05').files[0].name,
        prueba_vih: document.getElementById('05.06').files[0].name,
        reglamento_baq: document.getElementById('05.07').files[0].name
    }

    //TODO: RESTRICCION CAMPOS OBLIGATORIOS
    
    $.ajax({
        type: "POST",
        url: '../controlador/inscripciones/voluntarios.php?EnviarInscripcion=true',
        data: { 'parametros': parametros },
        dataType: 'json',
        success: function (respuesta) {
            if(respuesta['codigo'] == 1){
                //Guardar documentos y redirigir
            }else{
                //Mostrar mensaje de error
            }
        }
    });

    //Creacion de reporte del formulario
}