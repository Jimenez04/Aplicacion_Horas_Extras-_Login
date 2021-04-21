<?php 
    include_once("conexion_sql.php");

    $cedula = isset($_GET['cedula']) ? trim($_GET['cedula']) : '';
    $consulta = "SELECT * FROM empleados where cedula=:cedula";
    $stmt = $conex->prepare($consulta);
    $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
    $stmt->execute();
    $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ( Count($respuesta) == 0) {
        $res = false;
    }else{
        $res = $respuesta;
    }
   

    echo json_encode($res);


?>