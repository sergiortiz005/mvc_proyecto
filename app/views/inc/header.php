<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>


<header class="main-header">
  <link rel="stylesheet" href="<?= RUTA_URL ?>/public/css/header.css" />

  <div class="logo">
    <a href="<?= RUTA_URL ?>/">
      <img src="<?= RUTA_URL ?>/public/img/laliga.png" alt="Logo LaLiga" />
    </a>
  </div>

  <nav class="main-nav">
    <ul>
      <li class="has-dropdown">
        <a href="#">Equipos</a>
        <ul class="dropdown-menu">
          <li><a href="<?= RUTA_URL ?>/equipos/index">Ver todos</a></li>
          <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
            <li><a href="<?= RUTA_URL ?>/equipos/nuevo">Alta equipo</a></li>
          <?php endif; ?>
        </ul>
      </li>

      <li class="has-dropdown">
        <a href="#">Jugadores</a>
        <ul class="dropdown-menu">
          <li><a href="<?= RUTA_URL ?>/jugador/index">Ver todos</a></li>
          <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
            <li><a href="<?= RUTA_URL ?>/jugador/nuevo">Alta jugador</a></li>
          <?php endif; ?>
        </ul>
      </li>


      <li><a href="<?= RUTA_URL ?>/equipos/obtenerClasificacion">Clasificación</a></li>
    </ul>
  </nav>

  <div class="user-actions">
    <?php if (isset($_SESSION['usuario_id'])): ?>
      <a href="<?= RUTA_URL ?>/usuario/miCuenta">Mi cuenta</a>
      <a href="<?= RUTA_URL ?>/usuario/logout">Cerrar sesión</a>
    <?php else: ?>
      <a href="<?= RUTA_URL ?>/usuario/login">Iniciar sesión</a>
      <a href="<?= RUTA_URL ?>/usuario/registro">Registrarse</a>
    <?php endif; ?>
  </div>
</header>