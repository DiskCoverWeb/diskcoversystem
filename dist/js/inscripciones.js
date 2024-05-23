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
                        ${capitalizarString(seccion['Cuenta'])}
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
                    <input class="f-input f-input-${esPorcentaje?'perc':tiposInput[seccion['Tipo']]}" type="${tiposInput[seccion['Tipo']]}" id="${seccion['Codigo']}" ${esPorcentaje?'min="0" max="100"':''}>${esPorcentaje?'   %':''}
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
}

function capitalizarString(cadena){
    return cadena.toLowerCase().replace(/^\w|(?<=\s)\w/g, l => l.toUpperCase()); //#TODO: Capitalizar \w luego de ?
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
            default:
                alert('Elemento raro');
                break;
        }
    });
}