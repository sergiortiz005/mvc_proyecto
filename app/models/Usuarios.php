<?php

class Usuarios
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }

    // Comprobar si existe usuario
    public function existeUsuario($usuario)
    {
        $this->db->query('SELECT id FROM usuarios WHERE usuario = :usuario');
        $this->db->bind(':usuario', $usuario);
        $this->db->registro();
        return $this->db->rowCount() > 0;
    }

    // Comprobar si existe email
    public function existeEmail($email)
    {
        $this->db->query('SELECT id FROM usuarios WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->registro();
        return $this->db->rowCount() > 0;
    }

    // Registrar usuario
    public function registrarUsuario($datos)
    {
        $this->db->query('INSERT INTO usuarios (nombre, apellido, email, telefono, rol, usuario, contrasena) VALUES (:nombre, :apellido, :email, :telefono, :rol, :usuario, :contrasena)');
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':apellido', $datos['apellido']);
        $this->db->bind(':email', $datos['email']);
        $this->db->bind(':telefono', $datos['telefono']);
        $this->db->bind(':rol', $datos['rol']);
        $this->db->bind(':usuario', $datos['usuario']);
        $this->db->bind(':contrasena', $datos['contrasena']);

        return $this->db->execute();
    }

    public function login($usuario, $contrasena)
    {
        $this->db->query('SELECT * FROM usuarios WHERE usuario = :usuario');
        $this->db->bind(':usuario', $usuario);

        $usuarioEncontrado = $this->db->registro();

        if ($usuarioEncontrado) {
            // Verificar contraseña con hash
            if (password_verify($contrasena, $usuarioEncontrado->contrasena)) {
                return $usuarioEncontrado;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function logout()
    {
        session_start();
        $_SESSION = [];
        session_destroy();
        redireccionar('/usuario/login');
    }

    public function obtenerUsuarios($email = null)
    {
        if ($email) {
            $this->db->query("SELECT id, nombre, apellido, email, telefono, rol, usuario, foto_perfil 
                          FROM usuarios 
                          WHERE email LIKE :email");
            $this->db->bind(':email', '%' . $email . '%');
        } else {
            $this->db->query("SELECT id, nombre, apellido, email, telefono, rol, usuario, foto_perfil FROM usuarios");
        }

        return $this->db->registros();
    }


    public function cambiarRol($id, $nuevoRol)
    {
        if ($id == 1) {
            return false;
        }

        $this->db->query("UPDATE usuarios SET rol = :rol WHERE id = :id");
        $this->db->bind(':rol', $nuevoRol);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    public function getUsuarioPorId($id)
    {
        $this->db->query('SELECT id, nombre, apellido, email, telefono, rol, usuario, creado_en, foto_perfil FROM usuarios WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->registro();
    }

    public function actualizarDatosUsuario($id, $nombre, $apellido, $telefono, $foto_perfil = null)
    {
        if ($foto_perfil) {
            $this->db->query("UPDATE usuarios SET nombre = :nombre, apellido = :apellido, telefono = :telefono, foto_perfil = :foto_perfil WHERE id = :id");
            $this->db->bind(':foto_perfil', $foto_perfil);
        } else {
            $this->db->query("UPDATE usuarios SET nombre = :nombre, apellido = :apellido, telefono = :telefono WHERE id = :id");
        }
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':apellido', $apellido);
        $this->db->bind(':telefono', $telefono);
        $this->db->bind(':id', $id);
        $this->db->execute();
    }


}
