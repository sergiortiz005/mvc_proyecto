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
            $datos = [
                'id' => $_POST['id'],
                'nombre' => trim($_POST['nombre']),
                'dorsal' => (int) $_POST['dorsal'],
                'posicion' => trim($_POST['posicion']),
                'id_equipo' => (int) $_POST['id_equipo']
            ];

            $this->jugadorModelo->actualizarJugador($datos);
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
            $datos = [
                'nombre' => trim($_POST['nombre']),
                'dorsal' => (int) $_POST['dorsal'],
                'posicion' => trim($_POST['posicion']),
                'id_equipo' => (int) $_POST['id_equipo']
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
