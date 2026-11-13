
<!doctype html>
<html lang="en" class="semi-dark">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="../../img/jpg/logo.jpg" type="image/png" />
	<!--plugins-->
	<link href="../../assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="../../assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="../../assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="../../assets/css/pace.min.css" rel="stylesheet" />
	<script src="../../assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="../../assets/css/app.css" rel="stylesheet">
	<link href="../../assets/css/icons.css" rel="stylesheet">

	<title>Diskcover system - Login</title>

	<style>
		/* Estilo moderno y refinado para el formulario de Login con tono amarillo pastel bajito */
		.login-card {
			border: 1px solid #f6e8b1;
			border-radius: 16px;
			box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15), 0 5px 15px rgba(0, 0, 0, 0.08);
			background-color: #fffde7 !important; /* Amarillo pastel bajito suave y descansado a la vista */
			overflow: hidden;
			transition: all 0.3s ease;
		}

		.login-logo-container {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			padding: 10px 24px;
			background: #ffffff;
			border: 1px solid #f3e5ab;
			border-radius: 14px;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
			min-height: 75px;
			min-width: 220px;
			max-width: 290px;
		}

		.login-logo-container img,
		#img_logo {
			max-height: 68px !important;
			max-width: 250px !important;
			width: auto !important;
			height: auto !important;
			object-fit: contain;
			transition: transform 0.3s ease;
		}

		.login-title {
			font-size: 1.3rem;
			font-weight: 700;
			color: #1e293b;
			margin-top: 0.75rem;
			margin-bottom: 0.15rem;
			letter-spacing: -0.3px;
		}

		.login-subtitle {
			font-size: 0.85rem;
			color: #64748b;
			margin-bottom: 0;
		}

		.login-form-label {
			font-size: 0.78rem;
			font-weight: 700;
			color: #475569;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			margin-bottom: 0.35rem;
		}

		.login-input-group {
			border-radius: 8px;
			transition: all 0.2s ease;
		}

		.login-input-group .input-group-text {
			background-color: #ffffff;
			border-color: #cbd5e1;
			color: #64748b;
			font-size: 1.15rem;
			padding-left: 0.85rem;
			padding-right: 0.85rem;
			border-top-left-radius: 8px;
			border-bottom-left-radius: 8px;
		}

		.btn-elegir-empresa {
			cursor: pointer;
			background-color: #ffffff !important;
			border-color: #cbd5e1;
			color: #0d6efd !important;
			transition: all 0.2s ease;
		}

		.btn-elegir-empresa:hover {
			background-color: #e7f1ff !important;
			border-color: #0d6efd !important;
			color: #0a58ca !important;
			transform: scale(1.02);
		}

		.login-input-group .form-control {
			border-color: #cbd5e1;
			font-size: 0.92rem;
			padding: 0.6rem 0.85rem;
			color: #1e293b;
			border-top-right-radius: 8px;
			border-bottom-right-radius: 8px;
		}

		.login-input-group .form-control:focus {
			border-color: #0d6efd;
			box-shadow: none;
		}

		.login-input-group:focus-within {
			box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
			border-radius: 8px;
		}

		.login-input-group:focus-within .input-group-text {
			border-color: #0d6efd;
			color: #0d6efd;
			background-color: #f0f7ff;
		}

		.login-input-group:focus-within .form-control {
			border-color: #0d6efd;
		}

		.btn-login-submit {
			height: 44px;
			font-size: 0.95rem;
			font-weight: 700;
			letter-spacing: 0.3px;
			border-radius: 8px;
			background: linear-gradient(180deg, #0d6efd 0%, #0b5ed7 100%);
			border: none;
			box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
			transition: all 0.2s ease;
		}

		.btn-login-submit:hover {
			background: linear-gradient(180deg, #0b5ed7 0%, #0a58ca 100%);
			box-shadow: 0 6px 16px rgba(13, 110, 253, 0.35);
			transform: translateY(-1px);
		}

		.btn-login-submit:active {
			transform: translateY(0);
			box-shadow: 0 2px 6px rgba(13, 110, 253, 0.2);
		}

		.login-forgot-link {
			font-size: 0.825rem;
			font-weight: 600;
			color: #0d6efd;
			text-decoration: none;
			transition: color 0.2s ease;
		}

		.login-forgot-link:hover {
			color: #0a58ca;
			text-decoration: underline;
		}

		/* Modal de empresas más estilizado */
		.modal-content {
			border-radius: 16px;
			border: none;
			box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
		}

		.company-card {
			transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
			background: #ffffff;
			border: 1px solid #e2e8f0;
			border-radius: 10px;
		}

		.company-card:hover {
			border-color: #0d6efd !important;
			background-color: #ffffff !important;
			box-shadow: 0 6px 18px rgba(13, 110, 253, 0.12) !important;
			transform: translateY(-2px);
		}

		.company-card:hover .btn-select-company {
			background-color: #0d6efd !important;
			color: #ffffff !important;
			border-color: #0d6efd !important;
		}

		.company-logo-frame {
			width: 110px;
			height: 56px;
			min-width: 110px;
			background: #ffffff;
			border: 1px solid #e2e8f0;
			border-radius: 8px;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 4px;
		}

		.company-logo-frame img {
			max-height: 48px;
			max-width: 100px;
			object-fit: contain;
		}

		.cursor-pointer {
			cursor: pointer;
		}
	</style>
</head>

<body class="bg-login">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="card login-card mb-0">
							<div class="card-body p-4">
								<div class="text-center mb-3">
									<div class="login-logo-container mb-2">
										<img src="../../img/jpg/logo.jpg" id="img_logo" alt="Logo" />
									</div>
									<h4 class="login-title" id="lbl_razon">Diskcover system</h4>
									<h6 class="text-primary fw-bold font-14 mb-1" id="lbl_nombre" style="display: none;"></h6>
									<p class="login-subtitle">Por favor, inicie sesión en su cuenta</p>
								</div>
								<div class="form-body">
									<form class="row g-3" autocomplete="on">
										<input type="hidden" name="txt_cartera" id="txt_cartera" value="0">
										<input type="hidden" name="txt_entidad_id" id="txt_entidad_id">
										<input type="hidden" name="txt_item" id="txt_item">

										<div class="col-12">
											<div class="d-flex align-items-center justify-content-between">
												<label for="txt_entidad" class="form-label login-form-label mb-1">RUC/NIC Entidad</label>
												<span class="small text-primary fw-bold font-11 d-none cursor-pointer" id="lnk_volver_elegir" onclick="reabrir_seleccion_empresa()" style="cursor: pointer;" title="Haga clic para volver a seleccionar otra empresa">
													<i class='bx bx-refresh'></i> Volver a elegir
												</span>
											</div>
											<div class="input-group login-input-group">
												<button type="button" class="btn input-group-text btn-elegir-empresa" id="btn_elegir_empresa" onclick="reabrir_seleccion_empresa()" title="Haga clic aquí para volver a elegir empresa">
													<i class='bx bx-buildings'></i>
												</button>
												<input type="text" class="form-control" placeholder="Entidad a la que pertenece" onblur="validar_entidad()" name="txt_entidad" id="txt_entidad">
											</div>
										</div>

										<div class="col-12">
											<label for="txt_correo" class="form-label login-form-label">Email / Usuario</label>
											<div class="input-group login-input-group">
												<span class="input-group-text"><i class='bx bx-user'></i></span>
												<input type="email" class="form-control" name="txt_correo" id="txt_correo" placeholder="Correo / Usuario" onblur="validar_usuario()" autocomplete="username">
											</div>
										</div>

										<div class="col-12 mb-0">
											<label for="txt_contra" class="form-label login-form-label">Contraseña</label>
											<div class="input-group login-input-group" id="show_hide_password">
												<span class="input-group-text"><i class='bx bx-lock-alt'></i></span>
												<input type="password" class="form-control border-end-0" name="txt_contra" id="txt_contra" placeholder="Ingrese su contraseña" autocomplete="current-password">
												<a href="javascript:;" class="input-group-text bg-transparent border-start-0 text-muted" title="Mostrar/Ocultar contraseña"><i class='bx bx-hide'></i></a>
											</div>
										</div>

										<div id="form_cartera" style="display:none">
											<div class="input-group input-group-lg" >
												<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-envelope"></i></span>
												<input type="text" class="form-control" name="correo_cartera" placeholder="Correo Electrónico/Usuario" id="correo_cartera" onblur="validar_usuario()">                 
											</div>  <br>        
											<div class="input-group input-group-lg" >
												<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
												<input type="password" name="contra_cartera" id="contra_cartera" class="form-control" placeholder="******" aria-describedby="sizing-addon1" required autocomplete="new-password">
											</div>							
										</div>

										<div class="col-md-12 text-end">
											<a href="recuperar.php" class="login-forgot-link">¿Olvidó su contraseña?</a>
										</div>

										<div class="col-12">
											<div class="d-grid">
												<button type="button" class="btn btn-primary btn-login-submit d-flex align-items-center justify-content-center gap-2" id="btn_ingreso" onclick="/*Ingresar();*/Ingresar_vali()">
													<i class='bx bx-log-in fs-5'></i>
													<span>Ingresar</span>
													<div class="spinner-border text-light spinner-border-sm" style="display:none;" role="status" id="loader_ingreso"> 
														<span class="visually-hidden">Loading...</span>
													</div>
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
										<!-- 	<div class="col-12">
												<div class="text-center ">
													<p class="mb-0">Don't have an account yet? <a href="auth-basic-signup.html">Sign up here</a>
													</p>
												</div>
											</div> -->
										</form>
									</div>
									<!--
									<div class="login-separater text-center mb-2"> <span>OR SIGN IN WITH</span>
										<hr/>
									</div>
									<div class="list-inline contacts-social text-center">
										<a href="javascript:;" class="list-inline-item bg-facebook text-white border-0 rounded-3"><i class="bx bxl-facebook"></i></a>
										<a href="javascript:;" class="list-inline-item bg-twitter text-white border-0 rounded-3"><i class="bx bxl-twitter"></i></a>
										<a href="javascript:;" class="list-inline-item bg-google text-white border-0 rounded-3"><i class="bx bxl-google"></i></a>
										<a href="javascript:;" class="list-inline-item bg-linkedin text-white border-0 rounded-3"><i class="bx bxl-linkedin"></i></a>
									</div>
									-->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="../../assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="../../assets/js/jquery.min.js"></script>
	<script src="../../assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="../../assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="../../assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>

	<script src="../../dist/js/login.js"></script>
	<script src="../../dist/js/sweetalert2@11.js"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>

	<!-- Modal Selección de Empresas -->
	<div class="modal fade" id="mis_empresas" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content shadow-lg border-0" style="border-radius: 16px; overflow: hidden;">
				<div class="modal-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
					<div class="d-flex align-items-center gap-2">
						<div class="d-flex align-items-center justify-content-center rounded-3 bg-primary text-white" style="width: 42px; height: 42px;">
							<i class='bx bx-buildings fs-4'></i>
						</div>
						<div>
							<h5 class="modal-title fw-bold text-dark mb-0">Seleccione su Empresa</h5>
							<small class="text-muted">Empresas asociadas a la entidad: <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold" id="lbl_ruc"></span></small>
						</div>
					</div>
					<button type="button" class="btn-close" onclick="location.reload()" aria-label="Close"></button>
				</div>
				<div class="modal-body p-3 bg-light" style="background-color: #f8fafc !important;">
					<!-- Buscador en tiempo real si hay varias empresas -->
					<div class="input-group mb-3 bg-white shadow-sm rounded-3">
						<span class="input-group-text bg-white border-end-0 text-muted"><i class='bx bx-search fs-5'></i></span>
						<input type="text" class="form-control border-start-0 ps-0" id="txt_buscar_empresa" placeholder="Buscar por nombre, razón social o RUC..." onkeyup="filtrarEmpresasModal(this.value)">
					</div>
					<!-- Contenedor de tarjetas con scroll suave -->
					<div class="customers-list p-1" style="max-height: 380px; overflow-y: auto;" id="tbl_empresas"></div>
				</div>
				<div class="modal-footer bg-white py-2 border-top d-flex justify-content-between align-items-center">
					<span class="small text-muted fw-bold" id="lbl_total_empresas"></span>
					<button type="button" class="btn btn-outline-secondary px-4 fw-bold rounded-3" onclick="location.reload()">
						<i class='bx bx-x me-1'></i> Cerrar
					</button>
				</div>
			</div>
		</div>
	</div>
	
</body>

</html>