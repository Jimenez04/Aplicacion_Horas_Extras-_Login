<?php
   
    //Configurar datos de acceso a la Base de datos
    $host = "MSI\SQLEXPRESS";
   $dbname = "HorasExtras";
    $dbuser = "Guapo";
    $userpass = "Guapo1";
    
    $dsn = "sqlsrv:Server=$host; Database=$dbname"; $dbuser; $userpass;
    
    try{
     //Crear conexión a postgress
     $conex = new PDO($dsn);
    
     //Mostgrar mensaje si la conexión es correcta
     if($conex){
     //  echo "Conectado a la base  correctamente!"; 
     //echo "\n";
     }
    }catch (PDOException $e){
     //Si hay error en la conexión mostrarlo
     echo $e->getMessage();
    }
?>
