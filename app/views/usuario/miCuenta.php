<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Mi Cuenta</title>
    <link rel="stylesheet" href="<?= RUTA_URL ?>/public/css/miCuenta.css" />
</head>

<body>
    <?php require RUTA_APP . '/views/inc/header.php'; ?>

    <div class="container">
        <h2>Mi cuenta</h2>

        <div class="usuario-info">
            <p><strong>Nombre:</strong> <?= htmlspecialchars($datos['usuario']->nombre) ?></p>
            <p><strong>Apellido:</strong> <?= htmlspecialchars($datos['usuario']->apellido) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($datos['usuario']->email) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($datos['usuario']->telefono) ?></p>
            <p><strong>Usuario:</strong> <?= htmlspecialchars($datos['usuario']->usuario) ?></p>
            <p><strong>Rol:</strong> <?= htmlspecialchars($datos['usuario']->rol) ?></p>
            <p><strong>Registrado desde:</strong> <?= htmlspecialchars($datos['usuario']->creado_en) ?></p>

            <?php if (!empty($datos['usuario']->foto_perfil)): ?>
                <img src="<?= RUTA_URL . '/public/' . $datos['usuario']->foto_perfil ?>" alt="Foto perfil"
                    style="max-width:150px; margin-top:10px;">

            <?php endif; ?>
        </div>

        <hr>
        <h3>Editar datos</h3>
        <form action="<?= RUTA_URL ?>/usuario/actualizarMiCuenta" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $datos['usuario']->id ?>" />

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre"
                value="<?= htmlspecialchars($datos['nombre'] ?? $datos['usuario']->nombre) ?>" required />

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido"
                value="<?= htmlspecialchars($datos['apellido'] ?? $datos['usuario']->apellido) ?>" required />

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono"
                value="<?= htmlspecialchars($datos['telefono'] ?? $datos['usuario']->telefono) ?>" required />
            <?php if (!empty($datos['errores']['telefono'])): ?>
                <p style="color:red;"><?= $datos['errores']['telefono'] ?></p>
            <?php endif; ?>

            <label for="foto_perfil">Foto de perfil (opcional):</label>
            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" />

            <p><em>Email y usuario no se pueden modificar desde aquí.</em></p>

            <input type="submit" value="Actualizar datos" />
        </form>


        <p><a href="<?= RUTA_URL ?>/usuario/cambiarContrasena">Cambiar contraseña</a></p>
        <p><a href="<?= RUTA_URL ?>">Volver al inicio</a></p>
    </div>
</body>

</html>