<?php

class Jugador extends Controlador
{
    private $jugadorModelo;
    private $equipoModelo;

    public function __construct()
    {
        $this->jugadorModelo = $this->modelo('Jugadores');
        $this->equipoModelo = $this->modelo('Liga');
    }

    public function index()
    {
        $jugadores = $this->jugadorModelo->obtenerJugadores();
        $datos = [
            'jugadores' => $jugadores
        ];
        $this->vista('jugadores/index', $datos);
    }

    public function editar($id)
    {
        $jugador = $this->jugadorModelo->obtenerJugadorPorId($id);
        $equipos = $this->equipoModelo->obtenerEquipos();

        $datos = [
            'jugador' => $jugador,
            'equipos' => $equipos
        ];

        $this->vista('jugadores/editar', $datos);
    }

    public function actualizar()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $nombre = trim($_POST['nombre']);
        $dorsal = trim($_POST['dorsal']);
        $posicion = trim($_POST['posicion']);
        $id_equipo = $_POST['id_equipo'];

        $errores = [];

        $this->jugadorModelo = $this->modelo('Jugadores');
        if ($this->jugadorModelo->existeDorsalEnEquipo($dorsal, $id_equipo, $id)) {
            $errores['dorsal'] = "Ya existe un jugador con ese dorsal en este equipo.";
        }

        if (!empty($errores)) {
            $equipos = $this->equipoModelo->obtenerEquipos(); 
            $datos = [
                'jugador' => (object)[
                    'id' => $id,
                    'nombre' => $nombre,
                    'dorsal' => $dorsal,
                    'posicion' => $posicion,
                    'id_equipo' => $id_equipo
                ],
                'equipos' => $equipos,
                'errores' => $errores
            ];
            $this->vista('jugadores/editar', $datos);
            return;
        }

        $this->jugadorModelo->actualizarJugador([
            'id' => $id,
            'nombre' => $nombre,
            'dorsal' => $dorsal,
            'posicion' => $posicion,
            'id_equipo' => $id_equipo
        ]);

        redireccionar('/jugador');
    }
}


    public function borrar($id)
    {
        $this->jugadorModelo->borrarJugador($id);
        redireccionar('/jugador');
    }

    public function nuevo()
    {
        $equipos = $this->equipoModelo->obtenerEquipos();

        $datos = [
            'equipos' => $equipos
        ];

        $this->vista('jugadores/nuevo', $datos);
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $dorsal = (int) $_POST['dorsal'];
            $posicion = trim($_POST['posicion']);
            $id_equipo = (int) $_POST['id_equipo'];
            $errores = [];

            // Comprobación de dorsal repetido en el equipo
            if ($this->jugadorModelo->existeDorsalEnEquipo($dorsal, $id_equipo)) {
                $errores['dorsal'] = 'Este dorsal ya está asignado en el equipo seleccionado.';
            }

            if (!empty($errores)) {
                $equipos = $this->equipoModelo->obtenerEquipos();
                $datos = [
                    'errores' => $errores,
                    'equipos' => $equipos,
                    'nombre' => $nombre,
                    'dorsal' => $dorsal,
                    'posicion' => $posicion,
                    'id_equipo' => $id_equipo
                ];
                $this->vista('jugadores/nuevo', $datos);
                return;
            }

            $datos = [
                'nombre' => $nombre,
                'dorsal' => $dorsal,
                'posicion' => $posicion,
                'id_equipo' => $id_equipo
            ];

            $this->jugadorModelo->agregarJugador($datos);
            redireccionar('/jugador');
        }
    }


    public function ver($id)
    {
        $jugador = $this->jugadorModelo->obtenerJugadorPorId($id);
        if (!$jugador) {
            die('Jugador no encontrado');
        }

        $equipo = $this->equipoModelo->getEquipoById($jugador->id_equipo);

        $estadisticas = $this->jugadorModelo->obtenerEstadisticasPorJugador($id);

        $datos = [
            'jugador' => $jugador,
            'equipo' => $equipo,
            'estadisticas' => $estadisticas
        ];

        $this->vista('jugadores/estadisticas', $datos);
    }
}
