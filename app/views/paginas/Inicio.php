<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Listado de Equipos</title>
  <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/css/equipos.css">
</head>

<body>

  <div class="container">
    <?php
    require RUTA_APP . '/views/inc/header.php';
    ?>
    <h1 class="titulo">Listado de Equipos</h1>

    <table class="tabla-equipos">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Ciudad</th>
          <th>Escudo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($datos['equipos'] as $equipo): ?>
          <tr>
            <td><?= $equipo->nombre ?></td>
            <td><?= $equipo->ciudad ?></td>
            <td>
              <img src="<?= RUTA_URL . "/public/img/escudos/" . $equipo->nombre . ".png" ?>"
                alt="Escudo de <?= $equipo->nombre ?>" style="height:40px;">
            </td>
            <td>
              <a href="<?= RUTA_URL . "/equipos/editar/" . $equipo->id ?>" class="btn editar">Editar</a>
              <a href="<?= RUTA_URL . "/equipos/borrar/" . $equipo->id ?>" class="btn borrar"
                onclick="return confirm('¿Estás seguro de que quieres eliminar este equipo?')">Borrar</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</body>

</html>