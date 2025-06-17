<?php

class Usuario extends Controlador
{
    private $usuarioModelo;

    public function __construct()
    {
        $this->usuarioModelo = $this->modelo('Usuarios');
    }

    // Mostrar formulario de registro
    public function registro()
    {
        $datos = [
            'nombre' => '',
            'apellido' => '',
            'email' => '',
            'telefono' => '',
            'rol' => 'usuario',
            'usuario' => '',
            'password' => '',
            'password_confirm' => '',
            'errores' => []
        ];

        $this->vista('usuario/registro', $datos);
    }

    // Procesar registro
    public function registrar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $datos = [
                'nombre' => trim($_POST['nombre']),
                'apellido' => trim($_POST['apellido']),
                'email' => trim($_POST['email']),
                'telefono' => trim($_POST['telefono']),
                'rol' => 'usuario',
                'usuario' => trim($_POST['usuario']),
                'contrasena' => trim($_POST['contrasena']),
                'contrasena_confirmar' => trim($_POST['contrasena_confirmar']),
                'errores' => []
            ];

            // Validaciones
            if (empty($datos['nombre']))
                $datos['errores']['nombre'] = 'El nombre es obligatorio';
            if (empty($datos['apellido']))
                $datos['errores']['apellido'] = 'El apellido es obligatorio';

            if (empty($datos['email'])) {
                $datos['errores']['email'] = 'El email es obligatorio';
            } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
                $datos['errores']['email'] = 'Email no válido';
            } elseif ($this->usuarioModelo->existeEmail($datos['email'])) {
                $datos['errores']['email'] = 'El email ya está registrado';
            }

            if (empty($datos['telefono'])) {
                $datos['errores']['telefono'] = 'El teléfono es obligatorio';
            } elseif (!preg_match('/^\d{9}$/', $datos['telefono'])) {
                $datos['errores']['telefono'] = 'El teléfono debe contener exactamente 9 dígitos numéricos';
            }

            if (empty($datos['usuario'])) {
                $datos['errores']['usuario'] = 'El usuario es obligatorio';
            } elseif ($this->usuarioModelo->existeUsuario($datos['usuario'])) {
                $datos['errores']['usuario'] = 'El usuario ya está registrado';
            }

            if (empty($datos['contrasena']))
                $datos['errores']['contrasena'] = 'La contraseña es obligatoria';

            if ($datos['contrasena'] !== $datos['contrasena_confirmar']) {
                $datos['errores']['contrasena_confirmar'] = 'Las contraseñas no coinciden';
            }

            // Si no hay errores, registrar usuario
            if (empty($datos['errores'])) {
                $datos['contrasena'] = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
                if ($this->usuarioModelo->registrarUsuario($datos)) {
                    redireccionar('/usuario/login');
                } else {
                    die('Algo salió mal al registrar');
                }
            }

            // Mostrar formulario con errores
            $this->vista('usuario/registro', $datos);
        } else {
            $datos = [
                'nombre' => '',
                'apellido' => '',
                'email' => '',
                'telefono' => '',
                'usuario' => '',
                'contrasena' => '',
                'contrasena_confirmar' => '',
                'errores' => []
            ];
            $this->vista('usuario/registro', $datos);
        }
    }

    // Mostrar formulario login y procesar login
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = trim($_POST['usuario']);
            $contrasena = trim($_POST['contrasena']);

            $datos = [
                'usuario' => $usuario,
                'contrasena' => $contrasena,
                'error' => ''
            ];

            if (empty($usuario) || empty($contrasena)) {
                $datos['error'] = 'Por favor, rellena todos los campos.';
                $this->vista('usuario/login', $datos);
                return;
            }

            $usuarioAutenticado = $this->usuarioModelo->login($usuario, $contrasena);

            if ($usuarioAutenticado) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['usuario_id'] = $usuarioAutenticado->id;
                $_SESSION['usuario'] = $usuarioAutenticado->usuario;
                $_SESSION['rol'] = $usuarioAutenticado->rol;

                redireccionar('/principal');
            } else {
                $datos['error'] = 'Usuario o contraseña incorrectos.';
                $this->vista('usuario/login', $datos);
            }
        } else {
            $datos = [
                'usuario' => '',
                'contrasena' => '',
                'error' => ''
            ];
            $this->vista('usuario/login', $datos);
        }
    }

    // Cerrar sesión
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
        redireccionar('/usuario/login');
    }

    // Página de administración para mostrar usuarios y buscar por email
    public function admin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Podrías añadir validación de rol admin aquí si quieres

        $filtroEmail = isset($_GET['email']) ? trim($_GET['email']) : null;

        $usuarios = $this->usuarioModelo->obtenerUsuarios($filtroEmail);

        $datos = [
            'usuarios' => $usuarios,
            'emailBuscado' => $filtroEmail
        ];

        $this->vista('usuario/admin', $datos);
    }

    // Cambiar rol de usuario desde admin
    public function cambiarRol()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $rol = $_POST['rol'];

            if ($this->usuarioModelo->cambiarRol($id, $rol)) {
                redireccionar('/usuario/admin');
            } else {
                die('Error al actualizar el rol.');
            }
        } else {
            redireccionar('/usuario/index');
        }
    }

    // Mostrar página "Mi cuenta" con datos del usuario logueado
    public function miCuenta()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . RUTA_URL . '/usuario/login');
            exit();
        }

        $id = $_SESSION['usuario_id'];

        $usuarioModel = $this->modelo('Usuarios');
        $usuario = $usuarioModel->getUsuarioPorId($id);

        if (!$usuario) {
            header('Location: ' . RUTA_URL);
            exit();
        }

        $datos = [
            'usuario' => $usuario
        ];

        $this->vista('usuario/miCuenta', $datos);
    }

    public function actualizarMiCuenta()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . RUTA_URL . '/usuario/login');
            exit();
        }

        $id = $_SESSION['usuario_id'];
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $telefono = trim($_POST['telefono']);

        $errores = [];

        if (empty($telefono)) {
            $errores['telefono'] = 'El teléfono es obligatorio';
        } elseif (!preg_match('/^\d{9}$/', $telefono)) {
            $errores['telefono'] = 'El teléfono debe contener exactamente 9 dígitos numéricos';
        }

        $nombreFoto = null;
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === 0) {
            $extension = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
            $extensionesValidas = ['png', 'jpg', 'jpeg'];

            if (!in_array($extension, $extensionesValidas)) {
                $errores['foto_perfil'] = 'Solo se permiten archivos PNG, JPG y JPEG.';
            } else {
                $nombreFoto = uniqid() . '.' . $extension;
                $rutaDestinoRelativa = 'img/perfiles/' . $nombreFoto;
                $rutaDestinoAbsoluta = RUTA_APP . '/../public/' . $rutaDestinoRelativa;

                if (!move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $rutaDestinoAbsoluta)) {
                    $errores['foto_perfil'] = 'Error al subir la imagen.';
                }
            }
        }

        $usuarioModel = $this->modelo('Usuarios');

        if (!empty($errores)) {
            $usuario = $usuarioModel->getUsuarioPorId($id);

            $datos = [
                'usuario' => $usuario,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'telefono' => $telefono,
                'errores' => $errores
            ];

            $this->vista('usuario/miCuenta', $datos);
            return;
        }

        $usuarioModel->actualizarDatosUsuario($id, $nombre, $apellido, $telefono, $nombreFoto);

        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido'] = $apellido;

        header('Location: ' . RUTA_URL . '/usuario/miCuenta');
        exit();
    }
    public function cambiarContrasena()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . RUTA_URL . '/usuario/login');
        exit();
    }

    $usuario = $this->usuarioModelo->obtenerUsuarioPorId($_SESSION['usuario_id']);

    if (!$usuario) {
        die('Usuario no encontrado');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $datos = [
            'contrasena_actual' => trim($_POST['contrasena_actual']),
            'nueva_contrasena' => trim($_POST['nueva_contrasena']),
            'confirmar_contrasena' => trim($_POST['confirmar_contrasena']),
            'errores' => []
        ];

        // Verificar contraseña actual
        if (!password_verify($datos['contrasena_actual'], $usuario->contrasena)) {
            $datos['errores']['contrasena_actual'] = 'La contraseña actual es incorrecta.';
        }

        // Comprobar si la nueva es igual a la actual
        if (password_verify($datos['nueva_contrasena'], $usuario->contrasena)) {
            $datos['errores']['nueva_contrasena'] = 'La nueva contraseña no puede ser igual a la actual.';
        }

        // Verificar que coincidan
        if ($datos['nueva_contrasena'] !== $datos['confirmar_contrasena']) {
            $datos['errores']['confirmar_contrasena'] = 'Las contraseñas no coinciden.';
        }

        if (empty($datos['errores'])) {
            $nuevaContrasenaHash = password_hash($datos['nueva_contrasena'], PASSWORD_DEFAULT);

            if ($this->usuarioModelo->actualizarContrasena($usuario->id, $nuevaContrasenaHash)) {
                redireccionar('/usuario/miCuenta');
            } else {
                die('Error al actualizar la contraseña.');
            }
        } else {
            $this->vista('usuario/cambiarContrasena', $datos);
        }
    } else {
        $datos = [
            'contrasena_actual' => '',
            'nueva_contrasena' => '',
            'confirmar_contrasena' => '',
            'errores' => []
        ];

        $this->vista('usuario/cambiarContrasena', $datos);
    }
}



}
