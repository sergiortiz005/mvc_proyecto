<?php
class equipos extends Controlador
{
    private $equipoModelo;

    public function __construct()
    {
        $this->equipoModelo = $this->modelo('Liga');
    }

    public function index()
    {
        $equipos = $this->equipoModelo->obtenerEquipos();
        $datos = [
            'equipos' => $equipos
        ];
        $this->vista('paginas/inicio', $datos);
    }

    public function nuevo()
    {
        $datos = [
            'nombre' => '',
            'ciudad' => '',
            'errores' => []
        ];
        $this->vista('paginas/agregar', $datos);
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redireccionar('/equipos/nuevo');
            return;
        }

        $nombre = trim($_POST['nombre']);
        $ciudad = trim($_POST['ciudad']);
        $rutaDestino = null;
        $errores = [];

        if (empty($nombre)) {
            $errores['nombre'] = 'El nombre del equipo es obligatorio.';
        } else {
            if ($this->equipoModelo->existeEquipoConNombre($nombre)) {
                $errores['nombre'] = 'Ya existe un equipo con ese nombre.';
            }
        }

        if (!empty($_FILES['escudo']['name'])) {
            $extension = strtolower(pathinfo($_FILES['escudo']['name'], PATHINFO_EXTENSION));
            $extensionesValidas = ['png', 'jpg', 'jpeg'];

            if (!in_array($extension, $extensionesValidas)) {
                $errores['escudo'] = 'Solo se permiten imágenes PNG, JPG o JPEG.';
            }
        }

        if (!empty($errores)) {
            $datos = [
                'nombre' => $nombre,
                'ciudad' => $ciudad,
                'errores' => $errores
            ];
            $this->vista('paginas/agregar', $datos);
            return;
        }

        if (!empty($_FILES['escudo']['name'])) {
            $nombreEscudo = $nombre . '.' . $extension;
            $rutaTemporal = $_FILES['escudo']['tmp_name'];
            $rutaDestinoRelativa = 'img/escudos/' . $nombreEscudo;
            $rutaDestinoAbsoluta = RUTA_APP . '/../public/' . $rutaDestinoRelativa;

            if (!move_uploaded_file($rutaTemporal, $rutaDestinoAbsoluta)) {
                $errores['escudo'] = 'Error al subir el archivo.';
                $datos = [
                    'nombre' => $nombre,
                    'ciudad' => $ciudad,
                    'errores' => $errores
                ];
                $this->vista('paginas/agregar', $datos);
                return;
            }

            $rutaDestino = $rutaDestinoRelativa;
        }

        $datos = [
            'nombre' => $nombre,
            'ciudad' => $ciudad,
            'escudo' => $rutaDestino
        ];

        $this->equipoModelo->agregarEquipo($datos);

        redireccionar('/equipos');
    }

    public function borrar($id)
    {
        $equipo = $this->equipoModelo->obtenerEquipoPorId($id);

        $rutaImagen = RUTA_APP . '/../public/img/escudos/' . $equipo->nombre . '.png';
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }

        if ($this->equipoModelo->borrarEquipo($id)) {
            redireccionar('/equipos');
        } else {
            die("Error al borrar el equipo");
        }
    }

    public function editar($id)
    {
        $equipo = $this->equipoModelo->obtenerEquipoPorId($id);

        if ($equipo) {
            $datos = ['equipo' => $equipo];
            $this->vista('paginas/editar_equipo', $datos);
        } else {
            redireccionar('/equipos');
        }
    }
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $nombre = trim($_POST['nombre']);
            $ciudad = trim($_POST['ciudad']);

            $equipoActual = $this->equipoModelo->obtenerEquipoPorId($id);
            $rutaDestino = $equipoActual->escudo;

            if (!empty($_FILES['escudo']['name'])) {
                $extension = strtolower(pathinfo($_FILES['escudo']['name'], PATHINFO_EXTENSION));
                $nombreEscudo = $nombre . '.' . $extension;
                $rutaTemporal = $_FILES['escudo']['tmp_name'];
                $rutaRelativa = 'img/escudos/' . $nombreEscudo;
                $rutaAbsoluta = RUTA_APP . '/../public/' . $rutaRelativa;

                if (file_exists(RUTA_APP . '/../public/' . $rutaDestino)) {
                    unlink(RUTA_APP . '/../public/' . $rutaDestino);
                }

                move_uploaded_file($rutaTemporal, $rutaAbsoluta);
                $rutaDestino = $rutaRelativa;
            }

            $datos = [
                'id' => $id,
                'nombre' => $nombre,
                'ciudad' => $ciudad,
                'escudo' => $rutaDestino
            ];

            $this->equipoModelo->actualizarEquipo($datos);
            redireccionar('/equipos');
        }
    }

    public function obtenerClasificacion()
    {

        $clasificacion = $this->equipoModelo->obtenerClasificacion();

        $datos = [
            'clasificacion' => $clasificacion
        ];

        $this->vista('paginas/clasificacion', $datos);
    }

    public function ver($idEquipo)
    {
        $equipoModel = $this->modelo('Liga');
        $jugadorModel = $this->modelo('Jugadores');
        $ligaModel = $this->modelo('Liga');

        $equipo = $equipoModel->getEquipoById($idEquipo);
        $jugadores = $jugadorModel->getJugadoresPorEquipo($idEquipo);
        $resultados = $ligaModel->obtenerResultadosPorEquipo($idEquipo, 5);
        $datos = [
            'equipo' => $equipo,
            'jugadores' => $jugadores,
            'resultados' => $resultados,
            'clasificacion' => $ligaModel->obtenerClasificacion(),
            'calendario' => $ligaModel->obtenerPartidosCalendario($idEquipo),
        ];


        $this->vista('paginas/equipo_detalle', $datos);
    }





}