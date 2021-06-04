<?php

require_once 'config.mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'MailClass/vendor/autoload.php';

class pelicula
{
  private $conn;
  private $table_name = "pelicula";

  public $idPelicula;
  public $nombre;
  public $img;
  public $activo = 1;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  function agregarPelicula()
  {
    $query = "INSERT INTO $this->table_name (nombre, img, activo) VALUES(?, ?, $this->activo);";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(1, $this->nombre);
    $stmt->bindParam(2, $this->img);

    $stmt->execute();
  }

  function consultarPeliculas()
  {
    $query = "SELECT nombre, img, activo FROM $this->table_name WHERE nombre = ?;";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(1, $this->nombre);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
  }

  function existenceVerify()
  {
    $query = "SELECT * FROM $this->table_name
    WHERE nombre = ?";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(1, $this->nombre);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->nombre = $row['nombre'];
  }

  function sendMail()
  {
    $asunto = "Pelicula";
    $texto = "La película '$this->nombre' ha sido agregada a la Base de Datos.";
    $to = "rodrigoalbano@anima.edu.uy";

    $mail = new PHPMailer(true);
    $mail->isSMTP();

    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587;
    $mail->SMTPAuth = true;

    $mail->Username = REMITENTE;
    $mail->Password = CLAVE;
    $mail->SMTPSecure = 'tls';

    $mail->setFrom(REMITENTE, REMITENTE_NAME);

    $para = explode(";", $to);
    for ($i = 0; $i < count($para); $i++) {
      $mail->addAddress($para[$i]);
    }
    if ($mail->reply != '') {
      $mail->addReplyTo(RESPONDE_A);
    }

    $mail->Subject = $asunto;
    $mail->Body    = $texto;

    $mail->send();
  }
}
