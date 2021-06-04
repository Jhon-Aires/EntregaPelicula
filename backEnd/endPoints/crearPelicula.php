<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
error_reporting(0);

include_once '../config/db_connection.php';
include_once '../object/pelicula_conn.php';

$database = new Database();
$db = $database->getConnection();

$groupCount = new pelicula($db);

$data = json_decode(file_get_contents("php://input"));

$groupCount->nombre = $data->nombre;
$groupCount->existenceVerify();

if ($groupCount->nombre == null) {
  $groupCount->nombre = $data->nombre;
  $groupCount->img = $data->img;

  $groupCount->agregarPelicula();

  $groupCount->nombre = $data->nombre;
  $groupCount->sendMail();
  http_response_code(200);
  echo json_encode(array("peliculaCreada" => true));
} else {
  echo json_encode(array("peliculaYaExistente" => true));
}
