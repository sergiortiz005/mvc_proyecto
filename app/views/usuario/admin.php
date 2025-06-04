<?php

// Solo permitir admins
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ' . RUTA_URL . '/index');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="<?= RUTA_URL ?>/public/css/admin.css">
    
</head>

<body>
    <h1>Panel de Control - Administrador</h1>
    <p>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?> (<?= $_SESSION['rol'] ?>)</p>
    <a href="<?= RUTA_URL ?>/usuario/logout">Cerrar sesión</a>
<h2>Listado de Usuarios</h2>

<form method="GET" action="<?= RUTA_URL ?>/usuario/admin">
    <input type="text" name="email" placeholder="Buscar por email" value="<?= htmlspecialchars($datos['emailBuscado']) ?>">
    <button type="submit">Buscar</button>
    <?php if (!empty($datos['emailBuscado'])): ?>
        <a href="<?= RUTA_URL ?>/usuario/admin">Quitar filtro</a>
    <?php endif; ?>
</form>

    <h2>Listado de Usuarios</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Cambiar Rol</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos['usuarios'] as $usuario): ?>
                <tr>
                    <td><?= $usuario->id ?></td>
                    <td><?= htmlspecialchars($usuario->usuario) ?></td>
                    <td><?= htmlspecialchars($usuario->nombre) ?></td>
                    <td><?= htmlspecialchars($usuario->apellido) ?></td>
                    <td><?= htmlspecialchars($usuario->email) ?></td>
                    <td><?= htmlspecialchars($usuario->telefono) ?></td>
                    <td><?= htmlspecialchars($usuario->rol) ?></td>
                    <td>
                        <?php if ($usuario->id == 1): ?>
                            <span>No se puede cambiar el rol del admin</span>
                        <?php else: ?>
                            <form action="<?= RUTA_URL ?>/usuario/cambiarRol" method="POST">
                                <input type="hidden" name="id" value="<?= $usuario->id ?>">
                                <select name="rol">
                                    <option value="usuario" <?= $usuario->rol === 'usuario' ? 'selected' : '' ?>>Usuario</option>
                                    <option value="admin" <?= $usuario->rol === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <button type="submit">Cambiar</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>