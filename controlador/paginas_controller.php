<?php
class paginas_controller
{
    // Verificar si el usuario está logeado
    private function verificarLogin()
    {
        session_start();
        return isset($_SESSION['usu_id']);
    }

    function home()
    {
        include_once('vistas/header.php');
        include_once('vistas/inicio.php');
    }
    function error404()
    {
        include_once('vistas/error404.php');
    }
    
    function logout()
    {
        include_once('vistas/logout.php');
    }

}
