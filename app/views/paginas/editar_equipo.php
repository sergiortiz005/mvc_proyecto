<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar equipo</title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/css/formulario_equipos.css">
</head>
<body>
    <div class="form-container">
        <h2>Editar Equipo</h2>
        <form action="<?php echo RUTA_URL; ?>/equipos/actualizar" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $datos['equipo']->id; ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $datos['equipo']->nombre; ?>" required>

            <label for="ciudad">Ciudad:</label>
            <input type="text" name="ciudad" value="<?php echo $datos['equipo']->ciudad; ?>">

            <label>Escudo actual:</label><br>
            <img src="<?php echo RUTA_URL . '/public/img/escudos/' . $datos['equipo']->escudo; ?>" style="height:60px;"><br><br>

            <label for="escudo">Nuevo escudo (opcional):</label>
            <input type="file" name="escudo">

            <input type="submit" value="Actualizar equipo">
            <p><a href="<?php echo RUTA_URL; ?>/equipos">Volver a la lista de equipos</a></p>

        </form>
    </div>
</body>
</html>
