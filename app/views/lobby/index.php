<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Bienvenido a la Liga</title>
    <link rel="stylesheet" href="<?= RUTA_URL ?>/public/css/index.css">
</head>

<body>

    <header class="header-index">
        <div class="container-header">
            <h1 class="titulo-liga">Liga Deportiva</h1>
            <div class="acciones-usuario">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <span>Hola, <?= htmlspecialchars($_SESSION['usuario']); ?></span>

                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <a href="<?= RUTA_URL ?>/usuario/admin">Panel de Control</a>
                    <?php endif; ?>

                    <a href="<?= RUTA_URL ?>/usuario/logout">Cerrar sesión</a>
                <?php else: ?>
                    <a href="<?= RUTA_URL ?>/usuario/login">Iniciar sesión</a>
                    <a href="<?= RUTA_URL ?>/usuario/registro">Registrarse</a>
                <?php endif; ?>
            </div>

        </div>
    </header>

    <main class="contenido-principal">
        <div class="buscador-container">
            <input type="text" id="buscador" placeholder="Buscar jugador o equipo...">
            <div id="resultados"></div>
        </div>

        <section class="mis-ligas">
            <h2>Mis Ligas</h2>
            <div class="liga-card" onclick="window.location.href='<?= RUTA_URL ?>/Equipos/obtenerClasificacion'">
                <img src="<?= RUTA_URL ?>/public/img/laliga.png" alt="Liga Española">
                <span>Liga Española</span>
            </div>
        </section>
    </main>

    <script>
        const buscador = document.getElementById('buscador');
        const resultados = document.getElementById('resultados');

        buscador.addEventListener('input', function () {
            const query = this.value.trim();

            if (query.length < 2) {
                resultados.innerHTML = '';
                return;
            }

            fetch('<?= RUTA_URL ?>/buscar/buscarAjax?termino=' + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    resultados.innerHTML = '';
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'resultado-item';
                        div.textContent = item.nombre + (item.tipo === 'jugador' ? ' (Jugador)' : ' (Equipo)');
                        div.onclick = () => {
                            if (item.tipo === 'jugador') {
                                window.location.href = '<?= RUTA_URL ?>/jugador/ver/' + item.id;
                            } else {
                                window.location.href = '<?= RUTA_URL ?>/equipos/ver/' + item.id;
                            }
                        };
                        resultados.appendChild(div);
                    });
                });
        });
    </script>

</body>

</html>