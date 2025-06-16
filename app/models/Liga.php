<?php

class Liga
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }

    public function obtenerEquipos()
    {
        $this->db->query("SELECT * from equipos");

        $resultados = $this->db->registros();
        return $resultados;
    }

    public function agregarEquipo($datos)
    {
        $this->db->query("INSERT INTO equipos (nombre, ciudad, escudo) VALUES (:nombre, :ciudad, :escudo)");
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':ciudad', $datos['ciudad']);
        $this->db->bind(':escudo', $datos['escudo']);

        return $this->db->execute();
    }
    public function existeEquipoConNombre($nombre)
    {
        $this->db->query("SELECT id FROM equipos WHERE LOWER(nombre) = LOWER(:nombre)");
        $this->db->bind(':nombre', $nombre);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }


    public function obtenerEquipoPorId($id)
    {
        $this->db->query("SELECT * FROM equipos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function borrarEquipo($id)
    {
        $this->db->query("DELETE FROM equipos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function actualizarEquipo($datos)
    {
        $this->db->query("UPDATE equipos SET nombre = :nombre, ciudad = :ciudad, escudo = :escudo WHERE id = :id");
        $this->db->bind(':id', $datos['id']);
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':ciudad', $datos['ciudad']);
        $this->db->bind(':escudo', $datos['escudo']);

        return $this->db->execute();
    }
    public function obtenerClasificacion()
    {
        $this->db->query("SELECT * FROM partidos WHERE goles_local IS NOT NULL AND goles_visitante IS NOT NULL");
        $partidos = $this->db->registros(); // Esto devuelve objetos stdClass

        $clasificacion = [];

        foreach ($partidos as $partido) {
            $equipos = [
                'local' => $partido->id_equipo_local,
                'visitante' => $partido->id_equipo_visitante
            ];

            foreach ($equipos as $tipo => $id_equipo) {
                if (!isset($clasificacion[$id_equipo])) {
                    $clasificacion[$id_equipo] = [
                        'equipo_id' => $id_equipo,
                        'pj' => 0,
                        'pg' => 0,
                        'pe' => 0,
                        'pp' => 0,
                        'gf' => 0,
                        'gc' => 0,
                        'puntos' => 0
                    ];
                }
            }

            $local = &$clasificacion[$partido->id_equipo_local];
            $visitante = &$clasificacion[$partido->id_equipo_visitante];

            $local['pj']++;
            $visitante['pj']++;

            $local['gf'] += $partido->goles_local;
            $local['gc'] += $partido->goles_visitante;

            $visitante['gf'] += $partido->goles_visitante;
            $visitante['gc'] += $partido->goles_local;

            if ($partido->goles_local > $partido->goles_visitante) {
                $local['pg']++;
                $visitante['pp']++;
                $local['puntos'] += 3;
            } elseif ($partido->goles_local < $partido->goles_visitante) {
                $visitante['pg']++;
                $local['pp']++;
                $visitante['puntos'] += 3;
            } else {
                $local['pe']++;
                $visitante['pe']++;
                $local['puntos'] += 1;
                $visitante['puntos'] += 1;
            }
        }

        // Obtener nombres de los equipos
        foreach ($clasificacion as &$equipo) {
            $this->db->query("SELECT nombre FROM equipos WHERE id = :id");
            $this->db->bind(':id', $equipo['equipo_id']);
            $resultado = $this->db->registro();

            if ($resultado && isset($resultado->nombre)) {
                $equipo['nombre'] = $resultado->nombre;
            } else {
                $equipo['nombre'] = "Equipo desconocido";
            }

            $equipo['dg'] = $equipo['gf'] - $equipo['gc'];
        }



        // Ordenar por puntos, diferencia de goles y goles a favor
        usort($clasificacion, function ($a, $b) {
            if ($a['puntos'] != $b['puntos']) {
                return $b['puntos'] - $a['puntos'];
            }
            if (($a['gf'] - $a['gc']) != ($b['gf'] - $b['gc'])) {
                return ($b['gf'] - $b['gc']) - ($a['gf'] - $a['gc']);
            }
            return $b['gf'] - $a['gf'];
        });

        return $clasificacion;
    }

    public function getEquipoById($id)
    {
        $this->db->query("SELECT * FROM equipos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function obtenerResultadosPorEquipo($id_equipo)
    {
        $this->db->query("
        SELECT 
            p.*, 
            el.nombre AS nombre_local, 
            ev.nombre AS nombre_visitante
        FROM partidos p
        JOIN equipos el ON p.id_equipo_local = el.id
        JOIN equipos ev ON p.id_equipo_visitante = ev.id
        WHERE p.jugado = 1 AND (p.id_equipo_local = :id OR p.id_equipo_visitante = :id)
        ORDER BY p.fecha DESC, p.hora DESC
    ");
        $this->db->bind(':id', $id_equipo);
        return $this->db->registros();
    }

    public function obtenerPartidosCalendario($id_equipo)
    {
        $this->db->query("
        SELECT 
            p.*, 
            el.nombre AS nombre_local, 
            ev.nombre AS nombre_visitante 
        FROM partidos p 
        JOIN equipos el ON p.id_equipo_local = el.id 
        JOIN equipos ev ON p.id_equipo_visitante = ev.id 
        WHERE p.id_equipo_local = :id OR p.id_equipo_visitante = :id
    ");
        $this->db->bind(':id', $id_equipo);
        return $this->db->registros();
    }

    public function buscarEquiposPorNombre($nombre)
    {
        $this->db->query("SELECT id, nombre FROM equipos WHERE nombre LIKE :nombre LIMIT 10");
        $this->db->bind(':nombre', "%$nombre%");
        return $this->db->registros();
    }

}