<?php 
namespace Model;

class Usuario extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','email','password','token','confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;

    public function __construct($args= [])
    {
        $this->id=$args['id'] ?? null;
        $this->nombre=$args['nombre'] ?? '';
        $this->email=$args['email'] ?? '';
        $this->password=$args['password'] ?? '';
        $this->password2=$args['password2'] ?? null;
        $this->password_actual=$args['password_actual'] ?? null;
        $this->password_nuevo=$args['password_nuevo'] ?? null;
        $this->token=$args['token'] ?? '';
        $this->confirmado=$args['confirmado'] ?? 0;
        

    }

    public function validarLogin() {
        
        if (!$this->email) {
            self::$alertas['error'][]='El email es obligatorio';
        }
        if (!filter_var($this->email,FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][]='El email no es válido';
        }
        if (!$this->password) {
            self::$alertas['error'][]='El password es obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][]='El password es menor a seis caracteres';
        }
        
        return self::$alertas;
    }
    public function validarNuevaCuenta() {
        if (!$this->nombre) {
            self::$alertas['error'][]='El nombre es obligatorio';
        }
        if (!$this->email) {
            self::$alertas['error'][]='El email es obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][]='El password es obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][]='El password es menor a seis caracteres';
        }
        if ($this->password !== $this->password2) {
            self::$alertas['error'][]='El password es diferente';
        }
        return self::$alertas;
    }
    
    public function validarMail() {
        if (!$this->email) {
            self::$alertas['error'][]='El email es obligatorio';
        }
        if (!filter_var($this->email,FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][]='El email no es válido';
        }
        return self::$alertas;
    }
    public function validarPassword() {
        if (!$this->password) {
            self::$alertas['error'][]='El password es obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][]='El password es menor a seis caracteres';
        }
        if ($this->password !== $this->password2) {
            self::$alertas['error'][]='El password es diferente';
        }
        return self::$alertas;
    }
    public function validarPerfil() {
        if (!$this->nombre) {
            self::$alertas['error'][]='El nombre es obligatorio';
        }
        if (!$this->email) {
            self::$alertas['error'][]='El email es obligatorio';
        }
        
        return self::$alertas;
    }
    public function nuevoPassword() {
        if (!$this->password_actual) {
            self::$alertas['error'][]='El password actual no puede ir vacio';
        }
        if (!$this->password_nuevo) {
            self::$alertas['error'][]='El password nuevo no puede ir vacio';
        }
        if (strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][]='El password es menor a seis caracteres';
        }
        
        return self::$alertas;
    }
    public function comprobarPassword() : bool {
        return password_verify($this->password_actual, $this->password);
    }
    public function hashPassword(){
        $this->password = password_hash($this->password,PASSWORD_BCRYPT);
    }
    public function crearToken() {
        $this->token = uniqid();
    }
}


?>