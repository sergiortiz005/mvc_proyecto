<?php

class Jugadores
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }

    public function obtenerJugadores()
    {
        $this->db->query(
            'SELECT jugadores.*, equipos.nombre AS nombre_equipo, equipos.escudo 
             FROM jugadores 
             INNER JOIN equipos ON jugadores.id_equipo = equipos.id'
        );
        return $this->db->registros();
    }

    public function obtenerJugadorPorId($id)
    {
        $this->db->query('SELECT * FROM jugadores WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function agregarJugador($datos)
    {
        $this->db->query(
            'INSERT INTO jugadores (nombre, dorsal, posicion, id_equipo) 
         VALUES (:nombre, :dorsal, :posicion, :id_equipo)'
        );
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':dorsal', $datos['dorsal']);
        $this->db->bind(':posicion', $datos['posicion']);
        $this->db->bind(':id_equipo', $datos['id_equipo']);

        return $this->db->execute();
    }

    public function actualizarJugador($datos)
    {
        $this->db->query(
            'UPDATE jugadores 
             SET nombre = :nombre, dorsal = :dorsal, posicion = :posicion, id_equipo = :id_equipo 
             WHERE id = :id'
        );
        $this->db->bind(':id', $datos['id']);
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':dorsal', $datos['dorsal']);
        $this->db->bind(':posicion', $datos['posicion']);
        $this->db->bind(':id_equipo', $datos['id_equipo']);

        return $this->db->execute();
    }

    public function borrarJugador($id)
    {
        $this->db->query('DELETE FROM jugadores WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    public function getJugadoresPorEquipo($id_equipo)
    {
        $this->db->query("SELECT * FROM jugadores WHERE id_equipo = :id_equipo ORDER BY dorsal ASC");
        $this->db->bind(':id_equipo', $id_equipo);
        return $this->db->registros();
    }
    public function obtenerEstadisticasPorJugador($id_jugador)
    {
        $this->db->query("
        SELECT 
            sjp.*, 
            p.fecha, 
            CASE 
                WHEN p.id_equipo_local = e.id THEN e.nombre
                ELSE ev.nombre 
            END AS rival,
            CASE 
                WHEN p.id_equipo_local = e.id THEN 'Local'
                ELSE 'Visitante' 
            END AS local_visitante,
            p.goles_local,
            p.goles_visitante,
            el.nombre AS equipo_local,
            ev.nombre AS equipo_visitante
        FROM estadisticas_jugador_partido sjp
        JOIN partidos p ON sjp.id_partido = p.id
        JOIN equipos e ON (p.id_equipo_local = e.id OR p.id_equipo_visitante = e.id)
        JOIN equipos el ON p.id_equipo_local = el.id
        JOIN equipos ev ON p.id_equipo_visitante = ev.id
        WHERE sjp.id_jugador = :id_jugador
        ORDER BY p.fecha DESC
    ");
        $this->db->bind(':id_jugador', $id_jugador);
        return $this->db->registros();
    }

    public function buscarPorNombre($nombre)
    {
        $this->db->query("SELECT id, nombre FROM jugadores WHERE nombre LIKE :nombre LIMIT 10");
        $this->db->bind(':nombre', "%$nombre%");
        return $this->db->registros();
    }

}
