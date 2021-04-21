<?php 
    include "includes/header.php";
    //Accion
    if (isset($_POST['crearEmpleado'])) {
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $email_ = $_POST['email'];
        $es_admin = $_POST['es_admin'];
        //vaidamos el usuario
        $validarcedula = "SELECT * from empleados WHERE cedula = :cedula";
        $stmt = $conex->prepare($validarcedula);
        $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
        $resultado = $stmt->execute();
        $contador = Count($stmt->fetchAll());
        //se valida si existe 
        if ($contador==0) {
            if (empty(trim($cedula)) || empty(trim($nombre)) || empty(trim($email_)) || $es_admin=="") {
                $error = "Algunos campos estan vacios";
            }else{
                $consulta = "INSERT INTO empleados(cedula, nombre, email, admin_) 
                VALUES (:cedula, :nombre, :email, :admin_)";
                $stmt = $conex->prepare($consulta);
                $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
                $stmt->bindParam(':nombre', $nombre , PDO::PARAM_STR);
                $stmt->bindParam(':email', $email_ , PDO::PARAM_STR);
                $stmt->bindParam(':admin_', $es_admin , PDO::PARAM_INT);
                $resultado = $stmt->execute();
                    if ($resultado) {
                        $mensaje = "Usuario creado correctamente";
                    }else{
                        $error = "Error desconocido, no se pudo crear el usuario";
                    }

            }
        }else{
            $error = "Usuario ya existe";
        }
            
      }

?>

    <div class="row">
        <div class="col-sm-12">
            <?php if(isset($mensaje)) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><?php echo $mensaje ?></strong> 
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-sm-12">
            <?php if(isset($error)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?php echo $error ?></strong> 
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
            <?php endif; ?>
        </div>

    </div>  
              <div class="card-header">               
                <div class="row">
                  <div class="col-md-9">
                    <h3 class="card-title">Lista de todos los registros usuarios</h3>
                  </div>
                
              </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="cedula"># de Cédula:</label>
                        <input type="number" class="form-control" name="cedula" placeholder="Ingresa el # de cédula">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" placeholder="Ingresa el nombre">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" placeholder="name@example.com">
                    </div>                 
                    <div class="form-group">
                        <label for="esAdmin">Es Administrador</label>
                        <select class="form-control" name="es_admin">
                        <option value="">--Selecciona un valor--</option>
                        <option value="1">Si</option>
                        <option value="0">No</option>                        
                        </select>
                    </div>
                    <button type="submit" name="crearEmpleado" class="btn btn-primary w-100"><i class="fas fa-user"></i> Crear Nuevo Empleado</button>
                    </form>
              </div>
              <!-- /.card-body -->
<?php include "includes/footer.php" ?>
            