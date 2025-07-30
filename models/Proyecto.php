<?php 

namespace Model;

use Model\ActiveRecord;

class Proyecto extends ActiveRecord {
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'proyecto', 'url', 'propietarioId'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietarioId = $args['propietarioId'] ?? '';
    }

    public function validarProyecto() {
        if(!$this->proyecto) {
            self::$alertas['error'][] = 'El nombre del proyecto es obligatorio';
        }

        if(strlen($this->proyecto) > 32) {
            self::$alertas['error'][] = 'El limite de es de 32 caracteres';
        }

        return self::$alertas;
    }
}