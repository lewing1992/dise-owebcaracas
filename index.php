<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conecta a la base de datos  con usuario, contraseña y nombre de la BD
$servidor = "localhost"; $usuario = "root"; $contrasenia = ""; $nombreBaseDatos = "javascript";
$conexionBD = new mysqli($servidor, $usuario, $contrasenia, $nombreBaseDatos);


// Consulta datos y recepciona una clave para consultar dichos datos con dicha clave
if (isset($_GET["search"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM employees WHERE id=".$_GET["search"]);
    if(mysqli_num_rows($sqlEmpleaados) > 0){
        $employees = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($employees);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}
//borrar pero se le debe de enviar una clave ( para borrado )
if (isset($_GET["delete"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"DELETE FROM employees WHERE id=".$_GET["delete"]);
    if($sqlEmpleaados){
        echo json_encode(["success"=>1]);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}
//Inserta un nuevo registro y recepciona en método post los datos de nombre y correo
if(isset($_GET["add"])){
    $data = json_decode(file_get_contents("php://input"));
    $names=$data->names;
    $email=$data->email;
        if(($email!="")&&($names!="")){
            
    $sqlEmpleaados = mysqli_query($conexionBD,"INSERT INTO employees (names,email) VALUES('$names','$email') ");
    echo json_encode(["success"=>1]);
        }
    exit();
}
// Actualiza datos pero recepciona datos de nombre, correo y una clave para realizar la actualización
if(isset($_GET["update"])){
    
    $data = json_decode(file_get_contents("php://input"));

    $id=(isset($data->id))?$data->id:$_GET["update"];
    $names=$data->names;
    $email=$data->email;
    
    $sqlEmpleaados = mysqli_query($conexionBD,"UPDATE employees SET names='$names',email='$email' WHERE id='$id'");
    echo json_encode(["success"=>1]);
    exit();
}
// Consulta todos los registros de la tabla empleados
$sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM employees ");
if(mysqli_num_rows($sqlEmpleaados) > 0){
    $employees = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
    echo json_encode($employees);
}
else{ echo json_encode([["success"=>0]]); }


?>
