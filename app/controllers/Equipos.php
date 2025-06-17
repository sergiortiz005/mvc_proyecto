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
        $this->vista('paginas/agregar');
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $ciudad = trim($_POST['ciudad']);
            $rutaDestino = null;

            if ($this->equipoModelo->existeNombreEquipo($nombre)) {
                $datos = [
                    'error' => 'El nombre del equipo ya existe. Por favor, elige otro.',
                    'nombre' => $nombre,
                    'ciudad' => $ciudad
                ];
                $this->vista('paginas/agregar', $datos);
                return;
            }

            if (!empty($_FILES['escudo']['name'])) {
                $extension = strtolower(pathinfo($_FILES['escudo']['name'], PATHINFO_EXTENSION));
                $nombreEscudo = $nombre . '.' . $extension;
                $rutaTemporal = $_FILES['escudo']['tmp_name'];
                $rutaDestinoRelativa = 'img/escudos/' . $nombreEscudo;
                $rutaDestinoAbsoluta = RUTA_APP . '/../public/' . $rutaDestinoRelativa;

                $extensionesValidas = ['png', 'jpg', 'jpeg'];
                if (!in_array($extension, $extensionesValidas)) {
                    $datos = [
                        'error' => 'Extensión no permitida. Solo PNG, JPG y JPEG.',
                        'nombre' => $nombre,
                        'ciudad' => $ciudad
                    ];
                    $this->vista('paginas/agregar', $datos);
                    return;
                }

                if (!move_uploaded_file($rutaTemporal, $rutaDestinoAbsoluta)) {
                    $datos = [
                        'error' => 'Error al subir el archivo.',
                        'nombre' => $nombre,
                        'ciudad' => $ciudad
                    ];
                    $this->vista('paginas/agregar', $datos);
                    return;
                }

                $rutaDestino = $rutaDestinoRelativa;
            }

            $datosEquipo = [
                'nombre' => $nombre,
                'ciudad' => $ciudad,
                'escudo' => $nombreEscudo
            ];

            $this->equipoModelo->agregarEquipo($datosEquipo);

            redireccionar('/equipos');
        }
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
    public function actualizar($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $equipoOriginal = $this->equipoModelo->getEquipoById($id);

            if (!$equipoOriginal) {
                die('Equipo no encontrado.');
            }

            $nombreNuevo = trim($_POST['nombre']);
            $ciudad = trim($_POST['ciudad']);
            $nombreEscudo = $equipoOriginal->escudo;

            $rutaEscudos = RUTA_APP . '/../public/img/escudos/';

            if (empty($nombreNuevo) || empty($ciudad)) {
                $datos = [
                    'equipo' => $equipoOriginal,
                    'error' => 'Faltan campos obligatorios.'
                ];
                return $this->vista('paginas/editar_equipo', $datos);
            }

            if ($this->equipoModelo->existeEquipoConNombreDistinto($nombreNuevo, $id)) {
                $datos = [
                    'equipo' => $equipoOriginal,
                    'error' => 'El nombre del equipo ya existe. Elige otro.'
                ];
                return $this->vista('paginas/editar_equipo', $datos);
            }

            if (!empty($_FILES['escudo']['name'])) {
                $archivoTmp = $_FILES['escudo']['tmp_name'];
                $nombreArchivo = basename($_FILES['escudo']['name']);
                $rutaDestino = $rutaEscudos . $nombreArchivo;

                if (move_uploaded_file($archivoTmp, $rutaDestino)) {
                    $nombreEscudo = $nombreArchivo;
                }
            } elseif (!empty($nombreEscudo) && $nombreNuevo !== $equipoOriginal->nombre) {
                $extension = pathinfo($nombreEscudo, PATHINFO_EXTENSION);
                $nuevoNombreEscudo = $nombreNuevo . '.' . $extension;

                $rutaActual = $rutaEscudos . $nombreEscudo;
                $rutaNueva = $rutaEscudos . $nuevoNombreEscudo;

                if (file_exists($rutaActual)) {
                    if (rename($rutaActual, $rutaNueva)) {
                        $nombreEscudo = $nuevoNombreEscudo;
                    } else {
                        echo "No se pudo renombrar el archivo. Verifica permisos.";
                    }
                }
            }

            $this->equipoModelo->actualizarEquipo([
                'id' => $id,
                'nombre' => $nombreNuevo,
                'ciudad' => $ciudad,
                'escudo' => $nombreEscudo
            ]);

            redireccionar('/equipos/ver/' . $id);
        } else {
            $equipo = $this->equipoModelo->getEquipoById($id);
            if (!$equipo) {
                die('Equipo no encontrado');
            }
            $datos = [
                'equipo' => $equipo
            ];
            $this->vista('equipos/editar_equipo', $datos);
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