<?php

require_once('modelo/registro_model.php');

class registro_controller
{
    private $model;

    function __construct()
    {
        $this->model = new registro_model();
    }

    function crearUsuarioNotificaciones()
    {
        $data['nombres'] = $_REQUEST['nombres'];
        $data['telefono'] = $_REQUEST['telefono'];
        $data['email'] = $_REQUEST['email'];
        $data['interes'] = $_REQUEST['interes'];
        $resultado = $this->model->crearUsuarioNotificaciones($data);
    }
    
    function crearUsuario()
    {
        $data['nombres'] = $_REQUEST['nombres'];
        $data['telefono'] = $_REQUEST['telefono'];
        $data['email'] = $_REQUEST['email'];
        $data['password'] = $_REQUEST['password'];
        $resultado = $this->model->crearUsuario($data);
    }

    function ingresar()
    {
        $data['email'] = $_REQUEST['email'];
        $data['password'] = $_REQUEST['password'];
        $resultado = $this->model->ingresar($data);
    }
    
}

