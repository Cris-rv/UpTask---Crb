<?php 

namespace Model;

use Model\ActiveRecord;

class Tarea extends ActiveRecord {
    protected static $tabla = 'tareas';
    protected static $columnasDB = ['id', 'nombre', 'estado', 'proyectoId'];

    public function __construct($args=[])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->estado = $args['estado'] ?? 0; // 0 quiere decir que la tarea esta incompleta
        $this->proyectoId = $args['proyectoId'] ?? '';
    }

    
}