<?php
$fotoUsuarioModal = !empty($_SESSION['INGRESO']['Foto']) && $_SESSION['INGRESO']['Foto'] != '.' ? $_SESSION['INGRESO']['Foto'] : 'ejecutivo.png';
$rucInfoModal = !empty($_SESSION['INGRESO']['RUC']) && $_SESSION['INGRESO']['RUC'] != '.' ? $_SESSION['INGRESO']['RUC'] : ($_SESSION['INGRESO']['RUCEnt'] ?? '');
?>
<style>
    .avatar-wrapper {
        position: relative;
        display: inline-block;
        border-radius: 50%;
        padding: 4px;
        background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        box-shadow: 0 8px 24px rgba(13, 110, 253, 0.2);
    }
    .profile-avatar-img {
        width: 170px;
        height: 170px;
        border-radius: 50%;
        object-fit: cover;
        object-position: center;
        background-color: #fff;
        border: 3px solid #fff;
        display: block;
    }
    .cam-viewport-box {
        position: relative;
        width: 330px;
        height: 330px;
        max-width: 100%;
        margin: 0 auto;
        background-color: #0b1120;
        border-radius: 12px;
        overflow: hidden;
    }
    .cam-video-feed {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
    }
    .cam-canvas-feed {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .cam-guide-circle {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 250px;
        height: 250px;
        border-radius: 50%;
        border: 2px dashed rgba(255, 255, 255, 0.9);
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.45);
        pointer-events: none;
        z-index: 2;
    }
    .cam-flash {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #fff;
        opacity: 0;
        pointer-events: none;
        z-index: 10;
        transition: opacity 0.15s ease-out;
    }
    .cam-flash.flash-active {
        opacity: 0.9;
    }
    @media (min-width: 768px) {
        .border-end-md {
            border-right: 1px solid #dee2e6 !important;
        }
    }
</style>

<!-- Modal Pantalla Emergente: Perfil de Usuario -->
<div class="modal fade" id="modal_perfil" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalPerfilLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-white border-bottom py-3 px-4">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2 m-0" id="modalPerfilLabel">
                    <i class="bx bx-user-circle fs-3 text-primary"></i> Perfil de usuario
                </h5>
                <button type="button" class="btn-close" onclick="cerrar_modal_perfil()" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4 align-items-center">
                    <div class="col-12 col-md-5 text-center border-end-md pe-md-4">
                        <div class="avatar-wrapper mb-3">
                            <img id="img_foto_perfil_modal" 
                                 src="<?php echo (strpos($_SERVER['PHP_SELF'], '/php/vista/') !== false && basename(dirname($_SERVER['PHP_SELF'])) != 'vista' ? '../../' : '../../'); ?>img/usuarios/<?php echo $fotoUsuarioModal; ?>" 
                                 onerror="this.src='../../img/usuarios/ejecutivo.png'" 
                                 class="profile-avatar-img shadow-sm" 
                                 alt="Foto de perfil">
                        </div>
                        
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <form enctype="multipart/form-data" id="form_img_perfil_modal" method="post" class="m-0">
                                <input type="file" class="d-none" id="file_img_perfil_modal" name="file_img" accept="image/*" onchange="guardar_imagen_perfil_modal()">
                                <label class="btn btn-primary btn-sm d-flex align-items-center gap-1 shadow-sm px-3" for="file_img_perfil_modal" style="cursor: pointer;">
                                    <i class="bx bx-upload fs-6"></i> Actualizar imagen
                                </label>
                            </form>
                            <button type="button" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 shadow-sm px-3" onclick="abrir_modal_camara_perfil()">
                                <i class="bx bx-camera fs-6"></i> Tomar foto
                            </button>
                        </div>
                        <div class="text-muted small mt-2">
                            Formatos soportados: JPG, PNG, GIF
                        </div>
                    </div>
                    <div class="col-12 col-md-7 ps-md-4">
                        <ul class="nav nav-tabs nav-fill mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a data-bs-toggle="pill" href="#tab_modal_usuario" class="nav-link active fw-bold small">
                                    <i class="bx bx-user me-1"></i> DATOS DE USUARIO
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a data-bs-toggle="pill" href="#tab_modal_empresa" class="nav-link fw-bold small">
                                    <i class="bx bxs-business me-1"></i> DATOS DE EMPRESA
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="tab_modal_usuario">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="mb-2">
                                        <label class="form-label small text-muted fw-bold mb-1"><i class="bx bx-user text-primary me-1"></i> Nombre Completo</label>
                                        <input type="text" disabled class="form-control form-control-sm bg-white" value='<?php echo $_SESSION['INGRESO']['Nombre_Completo'] ?? ($_SESSION['INGRESO']['Nombre'] ?? '')?>'>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small text-muted fw-bold mb-1"><i class="bx bx-id-card text-primary me-1"></i> Identificación (ID)</label>
                                        <input type="text" disabled class="form-control form-control-sm bg-white" value='<?php echo $_SESSION['INGRESO']['Id'] ?? ''?>'>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label small text-muted fw-bold mb-1"><i class="bx bx-at text-primary me-1"></i> Usuario / Correo</label>
                                        <input type="text" disabled class="form-control form-control-sm bg-white" value='<?php echo $_SESSION['INGRESO']['usuario'] ?? ''?>'>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab_modal_empresa">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="mb-2">
                                        <label class="form-label small text-muted fw-bold mb-1"><i class="bx bx-id-card text-primary me-1"></i> R.U.C. Empresa</label>
                                        <input type="text" disabled class="form-control form-control-sm bg-white" value='<?php echo $rucInfoModal; ?>'>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small text-muted fw-bold mb-1"><i class="bx bxs-building text-primary me-1"></i> Razón Social</label>
                                        <input type="text" disabled class="form-control form-control-sm bg-white" value='<?php echo $_SESSION['INGRESO']['Razon_Social'] ?? ''?>'>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small text-muted fw-bold mb-1"><i class="bx bxs-institution text-primary me-1"></i> Entidad</label>
                                        <input type="text" disabled class="form-control form-control-sm bg-white" value='<?php echo $_SESSION['INGRESO']['Entidad'] ?? ''?>'>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label small text-muted fw-bold mb-1"><i class="bx bxs-business text-primary me-1"></i> Empresa</label>
                                        <input type="text" disabled class="form-control form-control-sm bg-white" value='<?php echo $_SESSION['INGRESO']['noempr'] ?? ''?>'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 px-4 border-top">
                <button type="button" class="btn btn-secondary btn-sm px-3" onclick="cerrar_modal_perfil()">
                    <i class="bx bx-x me-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cámara Web -->
<div class="modal fade" id="modal_camara" tabindex="-1" aria-labelledby="modalCamaraLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title fs-6 d-flex align-items-center gap-2" id="modalCamaraLabel">
                    <i class="bx bx-camera"></i> Tomar foto con la cámara
                </h5>
                <button type="button" class="btn-close btn-close-white" onclick="cerrar_modal_camara_perfil()" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center p-3">
                <div class="cam-viewport-box">
                    <video id="video_camara_modal" class="cam-video-feed" autoplay playsinline muted></video>
                    <canvas id="canvas_camara_modal" class="cam-canvas-feed" style="display: none;"></canvas>
                    <div id="guia_camara_modal" class="cam-guide-circle"></div>
                    <div id="cam_flash_modal" class="cam-flash"></div>
                    
                    <div id="camara_loading_modal" class="position-absolute top-50 start-50 translate-middle text-white text-center" style="z-index: 5;">
                        <div class="spinner-border text-light spinner-border-sm mb-2" role="status"></div>
                        <div class="small">Iniciando cámara...</div>
                    </div>

                    <div id="camara_error_modal" class="position-absolute top-50 start-50 translate-middle text-white text-center p-3" style="display: none; z-index: 5;">
                        <i class="bx bx-video-off fs-1 text-warning mb-2"></i>
                        <div class="small">No se pudo acceder a la cámara. Verifique permisos o conexión.</div>
                    </div>
                </div>
                <div class="text-muted small mt-2">
                    <i class="bx bx-info-circle me-1"></i>Enfoque su rostro dentro del círculo guía y pulse <b>Capturar</b>.
                </div>
            </div>
            <div class="modal-footer justify-content-between py-2 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" onclick="cerrar_modal_camara_perfil()">
                    <i class="bx bx-x me-1"></i>Cancelar
                </button>
                <div class="d-flex gap-2">
                    <button type="button" id="btn_repetir_foto_modal" class="btn btn-outline-secondary btn-sm" onclick="repetir_foto_camara_modal()" style="display: none;">
                        <i class="bx bx-refresh me-1"></i>Repetir
                    </button>
                    <button type="button" id="btn_capturar_foto_modal" class="btn btn-primary btn-sm px-3" onclick="capturar_foto_camara_modal()">
                        <i class="bx bx-camera me-1"></i>Capturar
                    </button>
                    <button type="button" id="btn_guardar_captura_modal" class="btn btn-success btn-sm px-3" onclick="guardar_foto_camara_modal()" style="display: none;">
                        <i class="bx bx-check me-1"></i>Guardar foto
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var streamCamaraPerfil = null;
    var modalPerfilInstanceGlobal = null;

    function getBaseApiUrlPerfil() {
        var path = window.location.pathname;
        var idx = path.indexOf('/php/');
        var root = (idx !== -1) ? path.substring(0, idx) : '';
        return window.location.origin + root;
    }

    function abrir_modal_perfil() {
        var modalEl = document.getElementById('modal_perfil');
        if (!modalEl) return;
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            modalPerfilInstanceGlobal = bootstrap.Modal.getOrCreateInstance(modalEl, {
                backdrop: 'static',
                keyboard: false
            });
            modalPerfilInstanceGlobal.show();
        } else if (typeof $ !== 'undefined') {
            $('#modal_perfil').modal('show');
        }
    }

    function cerrar_modal_perfil() {
        detener_stream_camara_perfil();
        if (modalPerfilInstanceGlobal) {
            modalPerfilInstanceGlobal.hide();
        } else if (typeof $ !== 'undefined') {
            $('#modal_perfil').modal('hide');
        }
        
        // Si el usuario navegó directamente a profile.php en la barra de direcciones, regresar
        if (window.location.pathname.indexOf('profile.php') !== -1) {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = 'inicio.php';
            }
        }
    }

    function guardar_imagen_perfil_modal() {
        var form = new FormData(document.getElementById("form_img_perfil_modal"));
        var inputFile = document.getElementById("file_img_perfil_modal");
        if (!inputFile || inputFile.files.length === 0) return;

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Subiendo imagen...',
                text: 'Por favor espere un momento',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        }

        var urlEnvio = getBaseApiUrlPerfil() + '/php/controlador/panel.php?cargar_imagen=true';

        $.ajax({
            url: urlEnvio,
            type: 'post',
            data: form,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response == -1) {
                    if (typeof Swal !== 'undefined') Swal.fire('Error', 'No se pudo guardar la imagen en el servidor.', 'error');
                } else if (response == -2) {
                    if (typeof Swal !== 'undefined') Swal.fire('Atención', 'Asegúrese de que el archivo sea una imagen válida (JPG, PNG, GIF).', 'warning');
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'Imagen de perfil actualizada correctamente',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                    var cacheBuster = '?' + Math.random();
                    var newImgUrl = getBaseApiUrlPerfil() + '/img/usuarios/' + response + cacheBuster;
                    $('#img_foto_perfil_modal').prop('src', newImgUrl);
                    $('#img_foto').prop('src', newImgUrl);
                    $('#img_foto1').prop('src', newImgUrl);
                    $('#file_img_perfil_modal').val('');  
                } 
            },
            error: function() {
                if (typeof Swal !== 'undefined') Swal.fire('Error', 'Error de conexión al subir la imagen.', 'error');
            }
        });
    }

    // Cámara Web
    function abrir_modal_camara_perfil() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Cámara no disponible',
                    text: 'Su navegador requiere una conexión segura (HTTPS o localhost) para acceder a la cámara.',
                    icon: 'warning'
                });
            }
            return;
        }

        if (modalPerfilInstanceGlobal) {
            modalPerfilInstanceGlobal.hide();
        } else if (typeof $ !== 'undefined') {
            $('#modal_perfil').modal('hide');
        }

        $('#video_camara_modal').show();
        $('#canvas_camara_modal').hide();
        $('#guia_camara_modal').show();
        $('#btn_capturar_foto_modal').show();
        $('#btn_repetir_foto_modal').hide();
        $('#btn_guardar_captura_modal').hide();
        $('#camara_loading_modal').show();
        $('#camara_error_modal').hide();

        var modalCamEl = document.getElementById('modal_camara');
        var modalCam = bootstrap.Modal.getOrCreateInstance(modalCamEl, { backdrop: 'static', keyboard: false });
        modalCam.show();

        navigator.mediaDevices.getUserMedia({
            video: { width: { ideal: 640 }, height: { ideal: 640 }, facingMode: 'user' },
            audio: false
        }).then(function(stream) {
            streamCamaraPerfil = stream;
            var video = document.getElementById('video_camara_modal');
            video.srcObject = stream;
            video.onloadedmetadata = function() {
                video.play();
                $('#camara_loading_modal').hide();
            };
        }).catch(function(err) {
            $('#camara_loading_modal').hide();
            $('#camara_error_modal').show();
            console.error("Error accediendo a cámara:", err);
        });
    }

    function detener_stream_camara_perfil() {
        if (streamCamaraPerfil) {
            streamCamaraPerfil.getTracks().forEach(function(track) { track.stop(); });
            streamCamaraPerfil = null;
        }
        var video = document.getElementById('video_camara_modal');
        if (video) video.srcObject = null;
    }

    function cerrar_modal_camara_perfil() {
        detener_stream_camara_perfil();
        var modalEl = document.getElementById('modal_camara');
        var modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) modalInstance.hide();
        if (modalPerfilInstanceGlobal) {
            modalPerfilInstanceGlobal.show();
        } else if (typeof $ !== 'undefined') {
            $('#modal_perfil').modal('show');
        }
    }

    function capturar_foto_camara_modal() {
        var video = document.getElementById('video_camara_modal');
        var canvas = document.getElementById('canvas_camara_modal');
        if (!video.videoWidth || !video.videoHeight) return;

        var flash = document.getElementById('cam_flash_modal');
        if (flash) {
            flash.classList.add('flash-active');
            setTimeout(function() { flash.classList.remove('flash-active'); }, 180);
        }

        var vWidth = video.videoWidth;
        var vHeight = video.videoHeight;
        var size = Math.min(vWidth, vHeight);

        canvas.width = size;
        canvas.height = size;
        var ctx = canvas.getContext('2d');
        ctx.translate(size, 0);
        ctx.scale(-1, 1);

        var sx = (vWidth - size) / 2;
        var sy = (vHeight - size) / 2;
        ctx.drawImage(video, sx, sy, size, size, 0, 0, size, size);

        $('#video_camara_modal').hide();
        $('#guia_camara_modal').hide();
        $('#canvas_camara_modal').show();
        $('#btn_capturar_foto_modal').hide();
        $('#btn_repetir_foto_modal').show();
        $('#btn_guardar_captura_modal').show();
    }

    function repetir_foto_camara_modal() {
        $('#canvas_camara_modal').hide();
        $('#video_camara_modal').show();
        $('#guia_camara_modal').show();
        $('#btn_capturar_foto_modal').show();
        $('#btn_repetir_foto_modal').hide();
        $('#btn_guardar_captura_modal').hide();
    }

    function guardar_foto_camara_modal() {
        var canvas = document.getElementById('canvas_camara_modal');
        if (!canvas) return;

        var btn = $('#btn_guardar_captura_modal');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

        canvas.toBlob(function(blob) {
            if (!blob) {
                btn.prop('disabled', false).html('<i class="bx bx-check me-1"></i> Guardar foto');
                if (typeof Swal !== 'undefined') Swal.fire('Error', 'No se pudo generar la imagen capturada.', 'error');
                return;
            }

            var formData = new FormData();
            formData.append('file_img', blob, 'foto_camara.jpg');

            var urlEnvio = getBaseApiUrlPerfil() + '/php/controlador/panel.php?cargar_imagen=true';

            $.ajax({
                url: urlEnvio,
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    btn.prop('disabled', false).html('<i class="bx bx-check me-1"></i> Guardar foto');
                    if (response == -1 || response == -2) {
                        if (typeof Swal !== 'undefined') Swal.fire('Error', 'Error al procesar la foto.', 'error');
                    } else {
                        detener_stream_camara_perfil();
                        var modalEl = document.getElementById('modal_camara');
                        var modalInstance = bootstrap.Modal.getInstance(modalEl);
                        if (modalInstance) modalInstance.hide();
                        if (modalPerfilInstanceGlobal) modalPerfilInstanceGlobal.show();

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: '¡Foto actualizada!',
                                text: 'La fotografía se ha guardado correctamente.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                        var cacheBuster = '?' + Math.random();
                        var newImgUrl = getBaseApiUrlPerfil() + '/img/usuarios/' + response + cacheBuster;
                        $('#img_foto_perfil_modal').prop('src', newImgUrl);
                        $('#img_foto').prop('src', newImgUrl);
                        $('#img_foto1').prop('src', newImgUrl);
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="bx bx-check me-1"></i> Guardar foto');
                    if (typeof Swal !== 'undefined') Swal.fire('Error', 'Error de conexión con el servidor.', 'error');
                }
            });
        }, 'image/jpeg', 0.92);
    }

    $(document).ready(function() {
        $('#modal_camara').on('hidden.bs.modal', function () {
            detener_stream_camara_perfil();
        });
    });
</script>

