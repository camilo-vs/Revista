<?php

class registro_model
{

    private $db;
    public function __construct()
    {
        $this->db = Conectar::conexion();
        mysqli_set_charset($this->db, 'utf8');
    }

    function crearUsuarioNotificaciones($data)
    {
        mysqli_select_db($this->db, "encontrado_lideres");
        $validarExistencia = $this->validarExistencia($data);
        if ($validarExistencia['error'] == false) {
            if ($validarExistencia['numero_registros'] == 0) {
                $values = "";
                $values .= "'" . $data['nombres'] . "',";
                $values .= "'" . $data['telefono'] . "',";
                $values .= "'" . $data['email'] . "',";
                if ($data['interes'] != '') {
                    $values .= "'" . $data['interes'] . "',";
                } else {
                    $values .= "null,";
                }
                $values .= "1,";
                $values = rtrim($values, ',');

                $sql = "INSERT INTO usuarios (
                usu_nombre,
                usu_telefono,
                usu_correo,
                usu_interes,
                usu_tipo
                )
                VALUES (" . $values . ")";

                $result = mysqli_query($this->db, $sql);
                if (!$result) {
                    $respuesta = array(
                        'error' => true,
                        'msg' => 'Error al insertar: ' . mysqli_error($this->db),
                        'sql' => $sql,
                        'resultado' => '',
                        'insert' => false
                    );
                } else {
                    $respuesta = array(
                        'error' => false,
                        'msg' => 'Todo Bien',
                        'sql' => $sql,
                        'resultado' => $result,
                        'existencia' => false,
                        'insert' => true
                    );
                }
            } else {
                $respuesta = array(
                    'error' => false,
                    'msg' => 'El usuario ya se encuentra registrado',
                    'existencia' => true,
                    'insert' => false,
                    'resultado' => $validarExistencia['registros']
                );
            }
        } else {
            $respuesta = array(
                'error' => true,
                'msg' => $validarExistencia['msg'],
                'sql' => $validarExistencia['sql'],
                'resultado' => '',
                'insert' => false
            );
        }
        echo json_encode($respuesta);
    }

    function validarExistencia($data)
    {
        mysqli_select_db($this->db, "encontrado_lideres");
        $condicion = "";
        $condicion .= " AND usu_correo = '" . $data['email'] . "'";
        $condicion .= " OR usu_telefono = '" . $data['telefono'] . "'";
        $condicion .= " AND usu_tipo = 1 ";

        $sql = "SELECT usu_id, CASE WHEN usu_correo = '" . $data['email'] . "' 
                THEN 'Correo Electronico' WHEN usu_telefono = '" . $data['telefono'] . "' 
                THEN 'Numero Telefonico' ELSE NULL END AS tipo_coincidencia 
                FROM usuarios WHERE 1=1 " . $condicion . " LIMIT 1";

        $result = mysqli_query($this->db, $sql);

        if (!$result) {
            $respuesta = array(
                'error' => true,
                'msg' => 'Error en la consulta: ' . mysqli_error($this->db),
                'sql' => $sql,
                'registros' => '',
                'numero_registros' => ''
            );
        } else {
            $registros = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $registros[] = $row;
            }

            $numero_registros = count($registros);

            $respuesta = array(
                'error' => false,
                'msg' => 'Todo Bien',
                'sql' => $sql,
                'registros' => $registros,
                'numero_registros' => $numero_registros
            );
        }

        return $respuesta;
    }

    function crearUsuario($data)
    {
        mysqli_select_db($this->db, "encontrado_lideres");
        $validarExistenciaUsuario = $this->validarExistenciaUsuario($data);
        if ($validarExistenciaUsuario['error'] == false) {
            if ($validarExistenciaUsuario['numero_registros'] == 0) {
                $values = "";
                $values .= "'" . $data['nombres'] . "',";
                $values .= "'" . $data['telefono'] . "',";
                $values .= "'" . $data['email'] . "',";
                $values .= "'" . password_hash($data['password'], PASSWORD_BCRYPT) . "',";
                $values .= "2,";
                $values = rtrim($values, ',');

                $sql = "INSERT INTO usuarios (
                usu_nombre,
                usu_telefono,
                usu_correo,
                usu_password,
                usu_tipo
                )
                VALUES (" . $values . ")";

                $result = mysqli_query($this->db, $sql);
                if (!$result) {
                    $respuesta = array(
                        'error' => true,
                        'msg' => 'Error al insertar: ' . mysqli_error($this->db),
                        'sql' => $sql,
                        'resultado' => '',
                        'insert' => false
                    );
                } else {
                    $respuesta = array(
                        'error' => false,
                        'msg' => 'Todo Bien',
                        'sql' => $sql,
                        'resultado' => $result,
                        'existencia' => false,
                        'insert' => true
                    );
                }
            } else {
                $respuesta = array(
                    'error' => false,
                    'msg' => 'El usuario ya se encuentra registrado',
                    'existencia' => true,
                    'insert' => false,
                    'resultado' => $validarExistenciaUsuario['registros']
                );
            }
        } else {
            $respuesta = array(
                'error' => true,
                'msg' => $validarExistenciaUsuario['msg'],
                'sql' => $validarExistenciaUsuario['sql'],
                'resultado' => '',
                'insert' => false
            );
        }
        echo json_encode($respuesta);
    }


    function validarExistenciaUsuario($data)
    {
        mysqli_select_db($this->db, "encontrado_lideres");
        $condicion = "";
        $condicion .= " AND usu_correo = '" . $data['email'] . "'";
        $condicion .= " AND usu_tipo = 2 ";

        $sql = "SELECT count(*) total FROM usuarios where 1=1 " . $condicion . "";

        $result = mysqli_query($this->db, $sql);

        if (!$result) {
            $respuesta = array(
                'error' => true,
                'msg' => 'Error en la consulta: ' . mysqli_error($this->db),
                'sql' => $sql,
                'registros' => '',
                'numero_registros' => ''
            );
        } else {
            $row = mysqli_fetch_assoc($result);
            $numero_registros = $row['total'];

            $respuesta = array(
                'error' => false,
                'msg' => 'Todo Bien',
                'sql' => $sql,
                'registros' => $result,
                'numero_registros' => $numero_registros
            );
        }

        return $respuesta;
    }

    function ingresar($data)
    {
        mysqli_select_db($this->db, "encontrado_lideres");
        $condiciones = '';
        $condiciones .= " AND usu_correo = '" . mysqli_real_escape_string($this->db, $data['email']) . "'";

        // Consulta para validar el usuario y contraseña
        $sql = "SELECT usu_id, usu_password FROM usuarios WHERE 1=1 " . $condiciones . "";
        $result = mysqli_query($this->db, $sql);
    
        if (!$result) {
            $respuesta = array(
                'error' => true,
                'msg' => 'Error en la consulta: ' . mysqli_error($this->db),
                'sql' => $sql,
                'registros' => '',
                'numero_registros' => '',
            );
            echo json_encode($respuesta);
            return;
        }
    
        if (mysqli_num_rows($result) === 0) {
            $respuesta = array(
                'error' => false,
                'msg' => 'Correo o contraseña incorrectos.',
                'sql' => $sql,
                'registros' => $result,
                'ingreso' => false
            );
            echo json_encode($respuesta);
            return;
        }
    
        $user = mysqli_fetch_assoc($result);
    
        if(!password_verify($data['password'], $user['usu_password'])) {
            $respuesta = array(
                'error' => false,
                'msg' => 'Contraseña incorrecta.',
                'sql' => $sql,
                'registros' => $result,
                'ingreso' => false
            );
            echo json_encode($respuesta);
            return;
        }
    
        $user_id = $user['usu_id'];
        $sql = "SELECT * FROM usuarios WHERE usu_id = '$user_id'";
        $result = mysqli_query($this->db, $sql);
    
        if (!$result) {
            $respuesta = array(
                'error' => true,
                'msg' => 'Error al obtener datos del usuario: ' . mysqli_error($this->db),
                'sql' => $sql,
                'registros' => $result,
                'ingreso' => false
            );
            echo json_encode($respuesta);
            return;
        }
    
        $user_data = mysqli_fetch_assoc($result);

        session_start();
        $_SESSION['usuario'] = $user_data;

        $respuesta = array(
            'error' => false,
            'msg' => 'Inicio de sesión exitoso.',
            'sql' => $sql,
            'registros' => $user_data,
            'ingreso' => true,
            'numero_registros' => mysqli_num_rows($result)
        );

        echo json_encode($respuesta);
    }
}
