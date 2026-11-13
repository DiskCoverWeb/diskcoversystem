<?php require_once(dirname(__Dir__,2).'/headers/header2.php'); ?>

<style>
	/* =========================================================================
	   ESTILOS 3D PARA TARJETAS DE MÓDULOS (DISKCOVER SYSTEM)
	   ========================================================================= */
	.modulos-3d-container {
		perspective: 1200px;
		padding: 10px 0;
	}

	.modulo-card-link {
		text-decoration: none !important;
		display: block;
		perspective: 1000px;
		margin-bottom: 22px;
	}

	.modulo-card-3d {
		position: relative;
		border-radius: 16px;
		border: 1px solid rgba(255, 255, 255, 0.32);
		overflow: hidden;
		transform-style: preserve-3d;
		transition: transform 0.22s ease-out, box-shadow 0.22s ease-out, filter 0.22s ease-out;
		box-shadow: 
			0 10px 22px -5px rgba(15, 23, 42, 0.28),
			0 4px 8px -2px rgba(15, 23, 42, 0.15),
			inset 0 1px 1px rgba(255, 255, 255, 0.55),
			inset 0 -5px 0 rgba(0, 0, 0, 0.24);
		cursor: pointer;
		user-select: none;
	}

	/* Capa de reflejo / brillo cristalino 3D */
	.modulo-card-3d::before {
		content: "";
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 52%;
		background: linear-gradient(
			180deg, 
			rgba(255, 255, 255, 0.28) 0%, 
			rgba(255, 255, 255, 0.08) 55%, 
			rgba(255, 255, 255, 0) 100%
		);
		border-radius: 16px 16px 50% 50% / 16px 16px 20% 20%;
		pointer-events: none;
		z-index: 1;
	}

	/* Bisel inferior 3D físico */
	.modulo-card-3d::after {
		content: "";
		position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
		height: 5px;
		background: linear-gradient(to top, rgba(0, 0, 0, 0.3), transparent);
		pointer-events: none;
		z-index: 1;
	}

	.modulo-card-3d .card-body {
		position: relative;
		z-index: 2;
		padding: 1.15rem 1.25rem;
		transform-style: preserve-3d;
	}

	/* Efecto al pasar el cursor (Hover 3D) */
	.modulo-card-link:hover .modulo-card-3d {
		box-shadow: 
			0 22px 35px -8px rgba(15, 23, 42, 0.38),
			0 12px 18px -4px rgba(15, 23, 42, 0.22),
			inset 0 1px 2px rgba(255, 255, 255, 0.7),
			inset 0 -6px 0 rgba(0, 0, 0, 0.28);
		filter: brightness(1.05);
	}

	/* Efecto presionado 3D (Tactile feedback) */
	.modulo-card-link:active .modulo-card-3d {
		transform: translateY(-2px) scale(0.99) !important;
		box-shadow: 
			0 6px 14px -3px rgba(15, 23, 42, 0.3),
			0 2px 4px -1px rgba(15, 23, 42, 0.18),
			inset 0 1px 1px rgba(255, 255, 255, 0.4),
			inset 0 -3px 0 rgba(0, 0, 0, 0.25);
	}

	/* Textos con relieve 3D */
	.modulo-badge-3d {
		font-size: 0.68rem;
		font-weight: 800;
		letter-spacing: 1.8px;
		color: rgba(255, 255, 255, 0.88);
		text-transform: uppercase;
		text-shadow: 0 1px 2px rgba(0, 0, 0, 0.35);
		margin-bottom: 2px;
	}

	.modulo-title-3d {
		font-size: 1.18rem;
		font-weight: 800;
		color: #ffffff;
		text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4), 0 1px 1px rgba(0, 0, 0, 0.25);
		letter-spacing: -0.2px;
		margin-bottom: 6px;
		line-height: 1.25;
	}

	.modulo-action-3d {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		font-size: 0.78rem;
		font-weight: 600;
		color: rgba(255, 255, 255, 0.95);
		text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
		transition: transform 0.2s ease, color 0.2s ease;
	}

	.modulo-card-link:hover .modulo-action-3d {
		transform: translateX(4px);
		color: #ffffff;
	}

	.modulo-action-3d i {
		font-size: 0.82rem;
		transition: transform 0.2s ease;
	}

	.modulo-card-link:hover .modulo-action-3d i {
		transform: scale(1.15);
	}

	/* Ícono contenedor esférico con relieve 3D */
	.widgets-icons-3d {
		width: 52px;
		height: 52px;
		min-width: 52px;
		border-radius: 50%;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: radial-gradient(circle at 35% 35%, rgba(255, 255, 255, 0.5) 0%, rgba(255, 255, 255, 0.18) 55%, rgba(0, 0, 0, 0.12) 100%);
		border: 2px solid rgba(255, 255, 255, 0.55);
		box-shadow: 
			0 6px 14px rgba(0, 0, 0, 0.22),
			inset 0 2px 3px rgba(255, 255, 255, 0.65),
			inset 0 -2px 3px rgba(0, 0, 0, 0.25);
		transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
		transform: translateZ(15px);
		overflow: hidden;
		padding: 4px;
	}

	.modulo-card-link:hover .widgets-icons-3d {
		transform: translateZ(28px) scale(1.14) rotate(6deg);
		box-shadow: 
			0 10px 20px rgba(0, 0, 0, 0.3),
			inset 0 2px 4px rgba(255, 255, 255, 0.8),
			inset 0 -2px 4px rgba(0, 0, 0, 0.3);
	}

	.widgets-icons-3d img {
		max-width: 100%;
		max-height: 100%;
		border-radius: 50%;
		object-fit: cover;
		filter: drop-shadow(0 2px 4px rgba(0,0,0,0.25));
	}

	/* PALETA DE COLOR 3D (Gradientes vivos con profundidad) */
	.mod-3d-1  { background: linear-gradient(135deg, #4f7bf5 0%, #2b57e6 55%, #183bb5 100%); } /* Azul Real */
	.mod-3d-2  { background: linear-gradient(135deg, #ff5e62 0%, #e82c42 55%, #b71427 100%); } /* Coral Rubí */
	.mod-3d-3  { background: linear-gradient(135deg, #10b981 0%, #059669 55%, #046c4e 100%); } /* Esmeralda */
	.mod-3d-4  { background: linear-gradient(135deg, #f59e0b 0%, #d97706 55%, #b45309 100%); } /* Ámbar Dorado */
	.mod-3d-5  { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 55%, #4c1d95 100%); } /* Púrpura Imperial */
	.mod-3d-6  { background: linear-gradient(135deg, #ec4899 0%, #db2777 55%, #9d174d 100%); } /* Rosa Fucsia */
	.mod-3d-7  { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 55%, #0e7490 100%); } /* Cian Océano */
	.mod-3d-8  { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 55%, #3730a3 100%); } /* Índigo */
	.mod-3d-9  { background: linear-gradient(135deg, #14b8a6 0%, #0d9488 55%, #0f766e 100%); } /* Turquesa */
	.mod-3d-10 { background: linear-gradient(135deg, #f97316 0%, #ea580c 55%, #c2410c 100%); } /* Naranja Cálido */
	.mod-3d-11 { background: linear-gradient(135deg, #e11d48 0%, #be123c 55%, #881337 100%); } /* Carmesí */
	.mod-3d-12 { background: linear-gradient(135deg, #334155 0%, #1e293b 55%, #0f172a 100%); } /* Grafito / Dark */
</style>

<div class="modulos-3d-container">
	<div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-4 g-3">
		<?php
			$modulo = $_SESSION['INGRESO']['modulo'] ?? []; 
			$moduloView = '';
    		$pos = 1;
			foreach ($modulo as $key => $value) {
				$descripcion = trim($value['descripcion'] ?? '');
				$tooltipAttrs = '';
				if($descripcion !== '' && $descripcion !== '.'){
					$tooltipAttrs = ' data-bs-toggle="tooltip" data-bs-placement="top" title="'.htmlspecialchars($descripcion, ENT_QUOTES).'"';
				}
				$colorClass = 'mod-3d-' . $pos;

				$moduloView .= '
				<div class="col">
					<a href="'.$value['link'].'" class="modulo-card-link"'.$tooltipAttrs.'>
						<div class="card modulo-card-3d '.$colorClass.'">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<div class="modulo-badge-3d"><i class="bx bx-cube-alt me-1"></i>MODULO</div>
										<h4 class="modulo-title-3d">'.$value['apli'].'</h4>
										<div class="modulo-action-3d">
											<i class="bx bxs-right-arrow align-middle"></i>
											<span>Ingresar al módulo</span>
										</div>
									</div>
									<div class="widgets-icons-3d text-white ms-auto">
										<img src="' . $value['icono'] . '" class="user-img" alt="'.$value['apli'].'" onerror="this.src=\'../../img/jpg/logo.jpg\';">
									</div>
								</div>
							</div>
						</div>
					</a>
				</div>';

				$pos++;
				if($pos > 12){ $pos = 1; }
			}
			echo $moduloView;
		?>
	</div>
</div>

<script>
	$(function () {
		// Inicializar Tooltips
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		tooltipTriggerList.forEach(function (el) {
			new bootstrap.Tooltip(el);
		});

		// Efecto Parallax Tilt 3D interactivo según la posición del cursor
		const cards = document.querySelectorAll('.modulo-card-3d');
		cards.forEach(card => {
			card.addEventListener('mousemove', function (e) {
				const rect = card.getBoundingClientRect();
				const x = e.clientX - rect.left;
				const y = e.clientY - rect.top;
				const centerX = rect.width / 2;
				const centerY = rect.height / 2;

				const rotateX = ((y - centerY) / centerY) * -10; // inclinación vertical
				const rotateY = ((x - centerX) / centerX) * 10;  // inclinación horizontal

				card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) translateY(-8px) scale(1.02)`;
			});

			card.addEventListener('mouseleave', function () {
				card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0) scale(1)';
			});
		});
	});
</script>

<?php require_once(dirname(__Dir__,2).'/headers/footer2.php'); ?>
