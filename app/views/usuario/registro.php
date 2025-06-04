<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Registro</title>
    <link rel="stylesheet" href="<?= RUTA_URL ?>/public/css/registro.css" />
</head>

<body>
    <div class="container">
        <h2>Registro de usuario</h2>
        <form action="<?= RUTA_URL ?>/usuario/registrar" method="POST" novalidate>
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= $datos['nombre']; ?>" />
            <span class="error"><?= $datos['errores']['nombre'] ?? ''; ?></span>

            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?= $datos['apellido']; ?>" />
            <span class="error"><?= $datos['errores']['apellido'] ?? ''; ?></span>

            <label>Email:</label>
            <input type="email" name="email" value="<?= $datos['email']; ?>" />
            <span class="error"><?= $datos['errores']['email'] ?? ''; ?></span>

            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?= $datos['telefono']; ?>" />
            <span class="error"><?= $datos['errores']['telefono'] ?? ''; ?></span>

            <label>Usuario:</label>
            <input type="text" name="usuario" value="<?= $datos['usuario']; ?>" />
            <span class="error"><?= $datos['errores']['usuario'] ?? ''; ?></span>

            <label>Contraseña:</label>
            <input type="password" name="contrasena" />
            <span class="error"><?= $datos['errores']['contrasena'] ?? ''; ?></span>

            <label>Confirmar contraseña:</label>
            <input type="password" name="contrasena_confirmar" />
            <span class="error"><?= $datos['errores']['contrasena_confirmar'] ?? ''; ?></span>

            <input type="submit" value="Registrarse" />
        </form>

        <p>¿Ya tienes cuenta? <a href="<?= RUTA_URL ?>/usuario/login">Inicia sesión</a></p>
        <a href="<?= RUTA_URL ?>/principal/index">Página principal</a>
    </div>
</body>

</html>
