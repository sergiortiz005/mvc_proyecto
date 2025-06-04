<?php
class Buscar extends Controlador {
    private $jugadoresModelo;
    private $ligaModelo;

    public function __construct() {
        $this->jugadoresModelo = $this->modelo('Jugadores');
        $this->ligaModelo = $this->modelo('Liga');
    }

    public function sugerencias() {
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        $resultados = [];

        if ($q !== '') {
            $jugadores = $this->jugadoresModelo->buscarPorNombre($q);
            $equipos = $this->ligaModelo->buscarEquiposPorNombre($q);

            foreach ($jugadores as $j) {
                $resultados[] = ['id' => $j->id, 'nombre' => $j->nombre, 'tipo' => 'jugador'];
            }

            foreach ($equipos as $e) {
                $resultados[] = ['id' => $e->id, 'nombre' => $e->nombre, 'tipo' => 'equipo'];
            }
        }

        header('Content-Type: application/json');
        echo json_encode($resultados);
    }
}
