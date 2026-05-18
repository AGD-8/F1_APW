<?php
require_once 'functions.php';

// Procesar subida de nuevo contenido desde el modal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'subir') {
    if (!isset($_SESSION['user_id']))
        die("Acceso denegado");

    $tipo = $_POST['tipo_elemento'];
    $nombre = $_POST['nombre'];
    $imagen_url = $_POST['imagen_url'];
    $user_id = $_SESSION['user_id'];

    if ($tipo === 'circuito') {
        $stmt = $pdo->prepare("INSERT INTO circuitos (nombre, pais, longitud_km, imagen_url, curvas_principales, forma_circuito, vuelta_rapida, anio_inauguracion, capacidad, creado_por) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $_POST['pais'], $_POST['longitud'], $imagen_url, $_POST['curvas'], $_POST['forma'], $_POST['vuelta_rapida'], $_POST['anio_inauguracion'], $_POST['capacidad'], $user_id]);
    } elseif ($tipo === 'piloto') {
        $stmt = $pdo->prepare("INSERT INTO pilotos (nombre, nacionalidad, dorsal, imagen_url, anio_nacimiento, cualidades, titulos, equipo_actual, historia_equipos, creado_por) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $_POST['nacionalidad'], $_POST['dorsal'], $imagen_url, $_POST['anio'], $_POST['cualidades'], $_POST['titulos'], $_POST['equipo_actual'], $_POST['historia_equipos'], $user_id]);
    } elseif ($tipo === 'vehiculo') {
        $stmt = $pdo->prepare("INSERT INTO vehiculos (nombre, equipo, categoria, imagen_url, tipos_neumaticos, motor, velocidad_max, aceleracion_0_100, peso_kg, creado_por) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $_POST['equipo'], $_POST['categoria_veh'], $imagen_url, $_POST['neumaticos'], $_POST['motor'], $_POST['velocidad_max'], $_POST['aceleracion_0_100'], $_POST['peso_kg'], $user_id]);
    }
    header("Location: index.php");
    exit;
}

// Obtener datos
$circuitos = $pdo->query("SELECT * FROM circuitos ORDER BY id DESC")->fetchAll();
$pilotos = $pdo->query("SELECT * FROM pilotos ORDER BY id DESC")->fetchAll();
$vehiculos = $pdo->query("SELECT * FROM vehiculos ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motorsport World | Experience the Speed</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .hero-home {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #000;
        }

        .split-container {
            flex: 1;
            min-height: 65vh;
            width: 100%;
            position: relative;
            overflow: hidden;
            --split-top: 50%;
            --split-bottom: 50%;
            transition: --split-top 0.5s cubic-bezier(0.25, 1, 0.5, 1), --split-bottom 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        }

        @property --split-top {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 50%;
        }
        @property --split-bottom {
            syntax: '<percentage>';
            inherits: false;
            initial-value: 50%;
        }

        .split-container:has(.split-f1:hover) {
            --split-top: 60%;
            --split-bottom: 55%;
        }

        .split-container:has(.split-motogp:hover) {
            --split-top: 40%;
            --split-bottom: 45%;
        }

        .split-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .split-f1 {
            background: linear-gradient(135deg, rgba(140,10,0,0.85) 0%, rgba(15,0,0,0.95) 100%),
                        repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(255,255,255,0.03) 20px, rgba(255,255,255,0.03) 40px);
            clip-path: polygon(0 0, var(--split-top) 0, var(--split-bottom) 100%, 0 100%);
            z-index: 1;
        }

        .split-motogp {
            background: linear-gradient(135deg, rgba(10,0,0,0.95) 0%, rgba(0,80,130,0.85) 100%);
            clip-path: polygon(var(--split-top) 0, 100% 0, 100% 100%, var(--split-bottom) 100%);
            z-index: 1;
        }

        .split-f1 .split-content {
            transform: translateX(-15vw);
        }
        .split-motogp .split-content {
            transform: translateX(15vw);
        }

        .split-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            opacity: 0.3;
            transition: opacity 0.5s ease, transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
            cursor: pointer;
        }

        .split-bg:hover .split-content {
            opacity: 1;
        }

        .split-f1:hover .split-content {
            transform: translateX(-15vw) scale(1.15);
        }
        .split-motogp:hover .split-content {
            transform: translateX(15vw) scale(1.15);
        }

        .split-icon {
            font-size: 6rem;
            margin-bottom: 10px;
            filter: drop-shadow(0 0 20px rgba(255,255,255,0.3));
        }

        .split-text {
            font-size: 4rem;
            font-weight: 900;
            font-style: italic;
            letter-spacing: 2px;
            color: #fff;
            text-shadow: 0 5px 15px rgba(0,0,0,0.5);
        }

        .hero-bottom-content {
            padding: 50px 20px 60px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-top: 2px solid rgba(255, 255, 255, 0.05);
            background: linear-gradient(to bottom, #07090e 0%, #000 100%);
            position: relative;
            z-index: 10;
            animation: fadeIn 1.5s ease-out;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 900;
            letter-spacing: -1px;
            margin: 0;
            text-transform: uppercase;
            color: #fff;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: #b0b8c5;
            margin-top: 10px;
            font-weight: 300;
        }

        .scroll-down {
            position: absolute;
            bottom: 40px;
            z-index: 10;
            animation: bounce 2s infinite;
            cursor: pointer;
            color: #fff;
            text-align: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-15px); }
            60% { transform: translateY(-7px); }
        }

        .section-header {
            padding: 100px 0 50px;
            text-align: center;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .section-header.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <span class="logo-ultra">ULTRA</span><span class="logo-speed">SPEED</span>
            </div>
            <ul class="nav-links">
                <li><a href="#circuitos">Circuitos</a></li>
                <li><a href="#vehiculos">Vehículos</a></li>
                <li><a href="#pilotos">Pilotos</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><button id="btn-abrir-subida-nav" class="nav-btn"
                            style="background: var(--primary); border: 2px solid var(--primary); color: white; padding: 10px 20px; cursor:pointer; font-weight: bold; border-radius: 5px; box-shadow: 0 4px 15px rgba(255, 69, 0, 0.3);">+
                            Añadir Contenido</button></li>
                    <li><a href="perfil.php">Mi Perfil (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                    <li><a href="logout.php" class="nav-btn"
                            style="background: transparent; border: 1px solid var(--primary);">Salir</a></li>
                <?php else: ?>
                    <li><a href="login.php" style="color: var(--primary);">Entrar</a></li>
                    <li><a href="registro.php" class="nav-btn">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <header class="hero-home">
        <div class="split-container">
            <!-- Fondo F1 Izquierda -->
            <div class="split-bg split-f1">
                <div class="split-content">
                    <span class="split-icon">🏁</span>
                    <div class="split-text">F1</div>
                </div>
            </div>
            
            <!-- Fondo MotoGP Derecha -->
            <div class="split-bg split-motogp">
                <div class="split-content">
                    <span class="split-icon">🏆</span>
                    <div class="split-text">MotoGP</div>
                </div>
            </div>
        </div>

        <div class="hero-bottom-content">
            <h1 class="hero-title">WORLD OF <span style="color:var(--primary)">SPEED</span></h1>
            <p class="hero-subtitle">Descubre la élite del motor. Una plataforma premium diseñada para los amantes de la Fórmula 1 y MotoGP.</p>
        </div>
    </header>

    <div class="container">

        <!-- SECCIÓN CIRCUITOS -->
        <section id="circuitos" class="section">
            <div class="section-header">
                <h2 class="section-title">Circuitos Legendarios</h2>
                <p style="color: #8b92a5;">Donde la historia se escribe sobre el asfalto.</p>
            </div>
            <div class="grid">
                <?php foreach ($circuitos as $c): ?>
                    <div class="card">
                        <div class="card-img-wrapper">
                            <img src="<?php echo $c['imagen_url']; ?>" alt="<?php echo $c['nombre']; ?>">
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $c['nombre']; ?></h3>
                            <?php echo renderStars(getAverageRating($pdo, 'circuito', $c['id'])); ?>
                            <div style="display: flex; gap: 10px; margin-top: 15px;">
                                <a href="detalle.php?tipo=circuito&id=<?php echo $c['id']; ?>" class="submit-btn"
                                    style="padding: 8px 15px; font-size: 0.8rem;">Detalles</a>
                                <a href="chat.php?tipo=circuito&id=<?php echo $c['id']; ?>" class="submit-btn"
                                    style="padding: 8px 15px; font-size: 0.8rem; background: #444;">Comentar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- SECCIÓN VEHÍCULOS -->
        <section id="vehiculos" class="section">
            <div class="section-header">
                <h2 class="section-title">Máquinas de Precisión</h2>
                <p style="color: #8b92a5;">Ingeniería llevada al límite absoluto.</p>
            </div>
            <div class="grid">
                <?php foreach ($vehiculos as $v): ?>
                    <div class="card">
                        <div class="card-img-wrapper">
                            <img src="<?php echo $v['imagen_url']; ?>" alt="<?php echo $v['nombre']; ?>">
                        </div>
                        <div class="card-content">
                            <span
                                class="badge <?php echo strtolower($v['categoria']) === 'formula 1' ? 'f1-badge' : 'motogp-badge'; ?>">
                                <?php echo $v['categoria']; ?>
                            </span>
                            <h3 class="card-title"><?php echo $v['nombre']; ?></h3>
                            <?php echo renderStars(getAverageRating($pdo, 'vehiculo', $v['id'])); ?>
                            <div style="display: flex; gap: 10px; margin-top: 15px;">
                                <a href="detalle.php?tipo=vehiculo&id=<?php echo $v['id']; ?>" class="submit-btn"
                                    style="padding: 8px 15px; font-size: 0.8rem;">Detalles</a>
                                <a href="chat.php?tipo=vehiculo&id=<?php echo $v['id']; ?>" class="submit-btn"
                                    style="padding: 8px 15px; font-size: 0.8rem; background: #444;">Comentar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- SECCIÓN PILOTOS -->
        <section id="pilotos" class="section">
            <div class="section-header">
                <h2 class="section-title">Héroes de la Pista</h2>
                <p style="color: #8b92a5;">El factor humano que marca la diferencia.</p>
            </div>
            <div class="grid">
                <?php foreach ($pilotos as $p): ?>
                    <div class="card">
                        <div class="card-img-wrapper">
                            <img src="<?php echo $p['imagen_url']; ?>" alt="<?php echo $p['nombre']; ?>">
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $p['nombre']; ?></h3>
                            <?php echo renderStars(getAverageRating($pdo, 'piloto', $p['id'])); ?>
                            <div style="display: flex; gap: 10px; margin-top: 15px;">
                                <a href="detalle.php?tipo=piloto&id=<?php echo $p['id']; ?>" class="submit-btn"
                                    style="padding: 8px 15px; font-size: 0.8rem;">Detalles</a>
                                <a href="chat.php?tipo=piloto&id=<?php echo $p['id']; ?>" class="submit-btn"
                                    style="padding: 8px 15px; font-size: 0.8rem; background: #444;">Comentar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    </div>

    <!-- MODAL DE SUBIDA DE CONTENIDO -->
    <div id="modal-subida" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="close-subida">&times;</span>
            <h2 style="color: var(--primary); margin-bottom: 30px;">Añadir Nuevo Elemento</h2>

            <form action="index.php" method="POST">
                <input type="hidden" name="action" value="subir">

                <div class="input-group">
                    <label>¿Qué quieres añadir?</label>
                    <select name="tipo_elemento" id="select-tipo" required
                        style="width:100%; padding:15px; border-radius:10px; background:#222; color:white; border:1px solid #444;">
                        <option value="circuito">Circuito</option>
                        <option value="piloto">Piloto</option>
                        <option value="vehiculo">Vehículo</option>
                    </select>
                </div>

                <div class="input-group"><label>Nombre / Título</label><input type="text" name="nombre" required></div>
                <div class="input-group"><label>URL de Imagen</label><input type="text" name="imagen_url" required
                        placeholder="https://ejemplo.com/imagen.jpg"></div>

                <!-- Campos Circuito -->
                <div id="f-circuito">
                    <div class="info-grid" style="grid-template-columns: 1fr 1fr;">
                        <div class="input-group"><label>País</label><input type="text" name="pais"></div>
                        <div class="input-group"><label>Longitud (km)</label><input type="number" step="0.01"
                                name="longitud"></div>
                        <div class="input-group"><label>Inauguración</label><input type="number"
                                name="anio_inauguracion"></div>
                        <div class="input-group"><label>Capacidad</label><input type="number" name="capacidad"></div>
                    </div>
                    <div class="input-group"><label>Vuelta Rápida</label><input type="text" name="vuelta_rapida"></div>
                    <div class="input-group"><label>Curvas Principales</label><textarea name="curvas"
                            rows="2"></textarea></div>
                    <div class="input-group"><label>Forma del Circuito (Desc)</label><textarea name="forma"
                            rows="2"></textarea></div>
                </div>

                <!-- Campos Piloto -->
                <div id="f-piloto" style="display:none;">
                    <div class="info-grid" style="grid-template-columns: 1fr 1fr;">
                        <div class="input-group"><label>Nacionalidad</label><input type="text" name="nacionalidad">
                        </div>
                        <div class="input-group"><label>Dorsal</label><input type="number" name="dorsal"></div>
                        <div class="input-group"><label>Año Nacimiento</label><input type="number" name="anio"></div>
                        <div class="input-group"><label>Títulos</label><input type="number" name="titulos"></div>
                    </div>
                    <div class="input-group"><label>Equipo Actual</label><input type="text" name="equipo_actual"></div>
                    <div class="input-group"><label>Cualidades</label><textarea name="cualidades" rows="2"></textarea>
                    </div>
                    <div class="input-group"><label>Trayectoria</label><textarea name="historia_equipos"
                            rows="2"></textarea></div>
                </div>

                <!-- Campos Vehículo -->
                <div id="f-vehiculo" style="display:none;">
                    <div class="info-grid" style="grid-template-columns: 1fr 1fr;">
                        <div class="input-group"><label>Equipo</label><input type="text" name="equipo"></div>
                        <div class="input-group">
                            <label>Categoría</label>
                            <select name="categoria_veh"
                                style="width:100%; padding:15px; border-radius:10px; background:#222; color:white; border:1px solid #444;">
                                <option value="Formula 1">Formula 1</option>
                                <option value="MotoGP">MotoGP</option>
                            </select>
                        </div>
                        <div class="input-group"><label>Vel. Máxima</label><input type="text" name="velocidad_max">
                        </div>
                        <div class="input-group"><label>Peso (kg)</label><input type="number" name="peso_kg"></div>
                    </div>
                    <div class="input-group"><label>Motor</label><input type="text" name="motor"></div>
                    <div class="input-group"><label>Aceleración 0-100</label><input type="text"
                            name="aceleracion_0_100"></div>
                    <div class="input-group"><label>Neumáticos</label><input type="text" name="neumaticos"></div>
                </div>

                <button type="submit" class="submit-btn" style="width:100%; margin-top:20px;">Guardar en
                    UltraSpeed</button>
            </form>
        </div>
    </div>

    <footer>
        <p>© 2026 UltraSpeed | La pasión por el motor.</p>
    </footer>

    <script>
        // Modal de Subida
        const modalSubida = document.getElementById('modal-subida');
        const btnAbrirNav = document.getElementById('btn-abrir-subida-nav');
        const btnCerrar = document.getElementById('close-subida');
        const selectTipo = document.getElementById('select-tipo');

        function abrirModalSubida() {
            <?php if (isset($_SESSION['user_id'])): ?>
                modalSubida.style.display = 'flex';
            <?php else: ?>
                alert('Debes iniciar sesión para añadir contenido a la comunidad.');
                window.location.href = 'login.php';
            <?php endif; ?>
        }

        if (btnAbrirNav) btnAbrirNav.onclick = abrirModalSubida;
        if (btnCerrar) btnCerrar.onclick = () => modalSubida.style.display = 'none';
        window.onclick = (e) => { if (e.target == modalSubida) modalSubida.style.display = 'none'; };

        // Cambio dinámico de campos
        if (selectTipo) {
            selectTipo.onchange = (e) => {
                const tipo = e.target.value;
                document.getElementById('f-circuito').style.display = (tipo === 'circuito') ? 'block' : 'none';
                document.getElementById('f-piloto').style.display = (tipo === 'piloto') ? 'block' : 'none';
                document.getElementById('f-vehiculo').style.display = (tipo === 'vehiculo') ? 'block' : 'none';
            };
        }

        // Animaciones al hacer scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.section-header').forEach(header => {
            observer.observe(header);
        });
    </script>
</body>

</html>