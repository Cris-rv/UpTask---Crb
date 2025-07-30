<?php

namespace Model;

use Model\ActiveRecord;

class Usuario extends ActiveRecord {

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    // Validar Login
    public function validarLogin() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no valido';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }

        return self::$alertas;
    }

    // Validacion para cuentas nuevas
    public function validarCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = "El nombre del usuario es obligatorio";
        }

        if(!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio";
        }

        if(!$this->password) {
            self::$alertas['error'][] = "El password es obligatorio";
        }

        if( $this->password != $this->password2 ) {
            self::$alertas['error'][] = "Los password no coinciden";
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe ser de 6 caracteres";
        }

        return self::$alertas;
    }

    public function nuevo_password() {
        if(!$this->password_actual) {
            self::$alertas['error'][] = 'El Password Actual no Debe ir vacio';
        }

        if(!$this->password_nuevo) {
            self::$alertas['error'][] = 'El Password Actual no Debe ir vacio';
        }

        if(strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'El Password Nuevo debe ser de 6 caracteres';
        }

        return self::$alertas;
    }

    public function comprobarPassword() : bool {
        return password_verify($this->password_actual, $this->password);
    }

    // Hashea El password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar el Token
    public function crearToken() : void {
        $this->token = uniqid();
    }

    // Valida un email
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no valido';
        }

        return self::$alertas;
    }

    // Validar Password
    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe ser de 6 caracteres";
        }

        return self::$alertas;
    }

    public function validarPerfil() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if(!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio";
        }

        return self::$alertas;
    }
}