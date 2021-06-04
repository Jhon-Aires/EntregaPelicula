<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/db_connection.php';
include_once '../object/pelicula_conn.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

$groupCount = new pelicula($db);

$data = json_decode(file_get_contents("php://input"));

$groupCount->nombre = $data->nombre;
$groupCount->existenceVerify();

if ($groupCount->nombre != null) {
  $groupCount->nombre = $data->nombre;
  $grupo = $groupCount->consultarPeliculas();
  
  http_response_code(200);
  echo json_encode($grupo);
} else {
  echo json_encode(array("agendaYaExistente" => true));
}
