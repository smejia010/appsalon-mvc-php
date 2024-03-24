<?php

namespace Model;

class Usuario extends ActiveRecord {
    // Informacion de la base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = [
        'id',
        'nombre',
        'apellido',
        'email',
        'password',
        'telefono',
        'admin',
        'confirmado', 
        'token'
    ];

    // Object properties
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    // Constructor
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null; 
        $this->nombre = $args['nombre'] ?? null; 
        $this->apellido = $args['apellido'] ?? null; 
        $this->email = $args['email'] ?? null; 
        $this->password = $args['password'] ?? null; 
        $this->telefono = $args['telefono'] ?? null; 
        $this->admin = $args['admin'] ?? '0'; // 
        $this->confirmado = $args['confirmado'] ?? '0'; // 
        $this->token = $args['token'] ?? null; 
    }

    //MENSAJE DE VALIDACION PARA LA CREACION DE CUENTA
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = "El nombre es obligatorio";
        }

        if(!$this->apellido) {
            self::$alertas['error'][] = "El apellido es obligatorio";
        }

        if(!$this->email) {
            self::$alertas['error'][] = "El correo electrónico es obligatorio";
        }

        if(!$this->password) {
            self::$alertas['error'][] = "La contraseña es obligatoria";
        }
        if(strlen($this->password) < 8){
            self::$alertas['error'][] = "La contraseña debe contener mínimo 8 caracteres";
        }

        if(!$this->telefono) {
            self::$alertas['error'][] = "El teléfono es obligatorio";
        }
        return self::$alertas;

    }

    public function validarLogin () {
        if(!$this->email) {
            self::$alertas["error"][] = "El email es obligatorio";
        }
        if(!$this->password) { 
            self::$alertas["error"][] = "La contraseña es obligatoria";
        }
        return self::$alertas;

    }

    public function validarEmail() {
        if(!$this->email) { 
            self::$alertas["error"][] = "El correo es obligatorio";
        }
        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas["error"][] = 'La contraseña es obligatoria';
        }
        if(strlen($this->password) < 8) {
            self::$alertas['error'][] = 'La contraseña debe tener mínimo 8 caracteres';
        }
        return self::$alertas;
    }

    //REVISA SI EL USUARIO YA EXISTE
    public function existeUsuario() {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" .$this->email. "' LIMIT 1";
        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][] = "El usuario ya se encuentra registrado";
        }
        return $resultado;
    }

    public function hashPassword() {
        $this -> password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this -> token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);
        if(!$resultado || !$this->confirmado) {
            self::$alertas["error"][] = "Contraseña incorrecta o falta verificar";
        } else {
            return true;
        }
    }
    
    
}
