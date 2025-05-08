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
    
    public function actualizarEquipo($datos) {
        $this->db->query("UPDATE equipos SET nombre = :nombre, ciudad = :ciudad, escudo = :escudo WHERE id = :id");
        $this->db->bind(':id', $datos['id']);
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':ciudad', $datos['ciudad']);
        $this->db->bind(':escudo', $datos['escudo']);
    
        return $this->db->execute();
    }
    

}