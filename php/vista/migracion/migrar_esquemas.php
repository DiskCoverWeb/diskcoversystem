<h2>Migracion de esquemas SQL Server a MySQL</h2>
<div class="container-fluid">
    <div class="row">
        <div class="form-group">
            <label for="archivo_esquema">Archivo de texto del esquema:</label>
            <input type="file" class="form-control-file" id="archivo_esquema">
        </div>
    </div>
    <div class="row" id="table-container" style="max-height:250px;overflow-y:auto;">
        
    </div>
    <div class="row" id="cont-subir">
        <button class="btn btn-primary" id="btn-subir">Subir Archivo</button>
    </div>
    <script>
        $(document).ready(() => {
            $('#cont-subir').hide();
        });

        $('#archivo_esquema').on('change', (e) => {
            let archivo = e.target.files[0];
            if (!archivo) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const content = e.target.result;
                createTableFromContent(content);
            };

            reader.readAsText(archivo);
        });

        $('#btn-subir').on('click', (e) => {
            const archivo = document.querySelector('#archivo_esquema').files[0];
            //e.target.setAttribute('disabled', 'true');
            
            let formData = new FormData();
            formData.append('archivo', archivo);
            //$('#myModal_espera').modal('show');
            fetch('../controlador/migracion/migrar_esquemasC.php?subir_archivo=true', {
                method: 'post',
                body: formData
            })
            .then(response => response.json())
            .then(respuesta => {
                if(respuesta['res'] == 1){
                    Swal.fire(
                        "Archivo subido",
                        respuesta['mensaje'],
                        "success"
                    );
                }else{
                    Swal.fire(
                        "Error al subir el archivo",
                        respuesta['mensaje'], 
                        "error"
                    );
                }
            })
            .catch(err => {
                Swal.fire(
                    "Ocurrió un error",
                    `Error: ${err}`, 
                    "error"
                )
            });
            /*$.ajax({
                type: "post",
                url: '../controlador/migracion/migrar_esquemasC.php?SubirArchivo=true',
                data: formData,
                dataType: 'json',
                success: function (respuesta) {
                    //$('#myModal_espera').modal('hide');
                    if(respuesta['codigo'] == 1){
                        
                    }else{
                        
                    }
                }
            });*/
        });

        function createTableFromContent(content) {
            const rows = content.split('\n');
            let table = $('<table class="table"></table>')
            let tHead = $('<thead></thead>');
            let tBody = $('<tbody></tbody>');
            rows.forEach((row, index) => {
                const cells = row.split(';');
                if(index === 0){
                    let tr = $('<tr></tr>');
                    cells.forEach(cell => {
                        let th = $('<th></th>').text(cell.trim());
                        tr.append(th);
                    });
                    tHead.append(tr);
                }else{
                    let tr = $('<tr></tr>');
                    cells.forEach(cell => {
                        let td = $('<td></td>').text(cell.trim().replaceAll('^',''));
                        tr.append(td);
                    });
                    tBody.append(tr);
                }
            });
            table.append(tHead);
            table.append(tBody);
            $('#table-container').append(table);
            $('#cont-subir').show();
        }
    </script>
</div>