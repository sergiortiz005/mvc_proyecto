<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/css/login.css"> <!-- Puedes usar este archivo después -->
</head>
<body>
    <div class="container">
        <h2>Iniciar sesión</h2>

        <?php if (!empty($datos['error'])) : ?>
            <div class="error" style="color: red; margin-bottom: 10px;">
                <?= $datos['error']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo RUTA_URL; ?>/usuario/login" method="post">
            <div>
                <label for="usuario">Usuario:</label>
                <input type="text" name="usuario" id="usuario" value="<?= $datos['usuario']; ?>" required>
            </div>

            <div>
                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" id="contrasena" required>
            </div>

            <div>
                <input type="submit" value="Entrar">
            </div>
        </form>

        <p>¿No tienes una cuenta? <a href="<?php echo RUTA_URL; ?>/usuario/registro">Regístrate aquí</a></p>
        <a href="<?php echo RUTA_URL; ?>/principal/index">Pagina principal</a>
    </div>
</body>
</html>
