<?php 
  include "includes/header.php";
    //Configurar la zona horaria
    date_default_timezone_set("America/Costa_Rica");
    //Consluta
    $Consulta = "SELECT * FROM registros";
    $stmt = $conex->prepare($Consulta); //Se puede usar el metodo -> query
    $respuesta = $stmt->execute();
    //Se obtiene todo
    $registros = $stmt->fetchAll(PDO::FETCH_OBJ);
      //Accion
          if (isset($_POST['registrarHoras'])) {
            $idEmpleado = $_POST['idEmpleado'];
            $fecha = $_POST['fecha'];
            $festivo = $_POST['festivo'];
            $horaInicial = $_POST['horaInicial'];
            $horaFinal = $_POST['horaFinal'];
                if (empty(trim($idEmpleado)) || empty(trim($fecha)) || empty(trim($festivo)) || empty(trim($horaInicial)) || 
                    empty(trim($horaFinal))) {
                    $error = "Algunos campos estan vacios";
                    header('Location: lista_horas.php?error='.$error);
                }else{
                    $consulta = "INSERT INTO registros(tipo, fecha, festivo, hora_inicial, hora_final, empleados_id, fecha_creacion) 
                    VALUES (:tipo, :fecha, :festivo, :hora_inicial, :hora_final, :empleados_id, :fecha_creacion)";
                    $stmt = $conex->prepare($consulta);
                    $fechaActual = date('d-m-Y');
                    $festivoevaluado = null;
                    if ($festivo != "") {
                        $festivoevaluado = $festivo;
                    }
                    $tipo = "Registro horas extras";
                    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                    $stmt->bindParam(':fecha', $fecha , PDO::PARAM_STR);
                    $stmt->bindParam(':festivo', $festivoevaluado , PDO::PARAM_STR);
                    $stmt->bindParam(':hora_inicial', $horaInicial , PDO::PARAM_STR);
                    $stmt->bindParam(':hora_final', $horaFinal , PDO::PARAM_STR);
                    $stmt->bindParam(':empleados_id', $idEmpleado , PDO::PARAM_INT);
                    $stmt->bindParam(':fecha_creacion', $fechaActual , PDO::PARAM_STR);
                    $resultado = $stmt->execute();
                        if ($resultado) {
                            $mensaje = "Registro de horas creado correctamente";
                            echo ("<meta http-equiv='refresh' content='1'>"); //Refrescar pagina en html truco
                            exit();
                        }else{
                            $error = "Error desconocido, no se pudo crear el registro";
                            header('Location: lista_horas.php?error='.$error);
                            exit();
                        }

                }
          }


?>

              <div class="card-header">               
                <div class="row">
                  <div class="col-md-9">
                    <h3 class="card-title">Lista de todos los registros horas extras</h3>
                  </div>
                  <div class="col-md-3">
                    <button type="button" class="btn btn-primary btn-xl pull-right w-100" data-toggle="modal" data-target="#modal-ingresar-horas">
                      <i class="fa fa-plus"></i> Ingresar nuevo registro
                    </button>
                 </div>
              </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tblRegistros" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Festivo</th>
                    <th>Hora inicial</th>
                    <th>Hora final</th>
                    <th>Empleado</th>                  
                    <th>Fecha creación</th>
                  
                  </tr>
                  </thead>
                  <tbody>
                   <?php foreach($registros as $valor) : ?>
                      <tr>
                          <td><?php echo $valor->id; ?></td>
                          <td><?php echo $valor->tipo; ?></td>
                          <td><?php echo $valor->fecha; ?></td>
                          <td><?php echo $valor->festivo; ?></td>
                          <td><?php echo $valor->hora_inicial; ?></td>
                          <td><?php echo $valor->hora_final; ?></td>
                          <td><?php echo $valor->empleados_id; ?></td>
                          <td><?php echo $valor->fecha_creacion; ?></td>                        
                      </tr>
                   <?php endforeach; ?>
                  </tbody>                  
                </table>
              </div>
              <!-- /.card-body -->


<?php include "includes/footer.php" ?>

<!-- page script -->
<script>
  $(function () {   
    $('#tblRegistros').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });   

    //Date range picker
    $('#fecha').datetimepicker({
      format:'YYYY-MM-DD HH:mm:ss'
    });

    //Hora Inicial
    $('#timepicker').datetimepicker({
        format: 'HH:mm',        
        enabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24],
        stepping: 30
    })

    //Hora Final
    $('#timepicker2').datetimepicker({
        format: 'HH:mm',        
        enabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24],
        stepping: 30
    })
    //ajax
    $('#buscar_cedula').click(function () {
      //Si el boton se ha tocado
      var cedula = $('#buscaCedula').val();
      if (cedula == "" || cedula == null) {
        $('#mensajes').html("Error, campo vacio, ingresa un numero de cedula");
        $('#nombre').val("");
        $('#idEmpleado').val("");
        return false;
      }
      //Enviar con ajax
      $.ajax({
        type: "GET",
        url: "http://localhost/cursophp/Aplicacion_Horas_Extras/buscar.php",
        data: {
          cedula : cedula
        },
        success: function (returnData) {
             // console.log(returnData);
              $('#nombre').val("");
            $('#idEmpleado').val("");
            //Obtenemos el JSON
            var results = JSON.parse(returnData);
            if (!results) {
                $('#nombre').val("");
                $('#idEmpleado').val("");
                $('#mensajes').html("Error, no existe esa persona");
              }
            $.each(results, function(key, value)
            {
              if (value=="" || value == null) {
                $('#nombre').val("");
                $('#idEmpleado').val("");
                $('#mensajes').html("Error, no existe esa persona");
              }else{
                $('#nombre').val(value.nombre);
                $('#idEmpleado').val(value.id);
                $('#mensajes').html("");
              }
            });
            
        }
      });
    });

  });
</script>
