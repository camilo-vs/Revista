<?php

class Conectar{
    public static function conexion(){
		
		$servidor = 'localhost';
		$usuario = 'encontrandoLideres_admin';
		$contraseña = 'drhinf00@1_12';
        $conexion = new mysqli($servidor, $usuario, $contraseña);
        return $conexion;
    }
}
