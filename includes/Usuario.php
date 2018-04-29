<?php

namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Aplicacion as App;

class Usuario {

  public static function login($username, $password) {
    $user = self::buscaUsuario($username);
    if ($user && $user->compruebaPassword($password)) {
      $app = App::getSingleton();
      $conn = $app->conexionBd();
      $query = sprintf("SELECT R.nombre FROM rolesusuario RU, roles R WHERE RU.rol = R.id AND RU.usuario=%s", $conn->real_escape_string($user->id));
      $rs = $conn->query($query);
      if ($rs) {
       // while($fila = $rs->fetch_assoc()) { 
        //  $user->addRol($fila['nombre']);
        //}
        $rs->free();
      }
      return $user;
    }
    return false;
  }

public static function registro($username, $password,$email,$fechaNac,$descripcion)
  {
    $buscarUsuario = "SELECT * FROM usuarios WHERE username = '$_POST[username]' ";
    $app = App::getSingleton();
    $conn = $app->conexionBd();
	  $result = $conn->query($buscarUsuario);
	  $count = mysqli_num_rows($result);
	  if ($count >= 1)
	  {
		  echo "<br />". "El Nombre de Usuario ya ha sido tomado." . "<br />";
	  }
	  else
	  {
		  $pass = password_hash($password, PASSWORD_DEFAULT);      
		  //$reg = "INSERT INTO usuarios(username, password, Email, FechaNac, Descripcion) VALUES ($username, $pass, $email, $fechaNac, $descripcion)"; 
      $reg = sprintf("INSERT INTO usuarios(username, password,Email,FechaNac, Descripcion, imagenPerfil) VALUES('%s', '%s', '%s', '%s', '%s', '%s')"
          , $conn->real_escape_string($username)
          , password_hash($password, PASSWORD_DEFAULT)
          , $conn->real_escape_string($email)
          , $fechaNac
          , $conn->real_escape_string($descripcion)
          , 'img/imgBasica.jpg');
      if ($conn->query($reg)=== TRUE)
		  {
			 echo "<br />" . "<h2>" . "Usuario Creado Exitosamente!" . "</h2>";
		  }
      else
      {
        echo "Error al configurar la codificación de la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
      }
	  }
	  return $conn;
  }

  public static function buscaUsuario($username) {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT * FROM usuarios WHERE username='%s'", $conn->real_escape_string($username));
    $rs = $conn->query($query);
    if ($rs && $rs->num_rows == 1) {
      $fila = $rs->fetch_assoc();
      $user = new Usuario($fila['id'], $fila['username'], $fila['password'], $fila['Descripcion'], $fila['Email'],$fila['FechaNac'], $fila['imagenPerfil']);
      $rs->free();

      return $user;
    }
    return false;
  }

  public static function modPerfil ($username, $password,$email,$fechaNac,$descripcion)
  {
    $app = App::getSingleton();
    $conn = $app->conexionBd();

    $idUser = "SELECT U.id FROM usuarios U WHERE username = '$_POST[username]' ";

    $result = $conn->query($idUser);

    //$fila = $result->fetch_array($conn);

    $count = mysqli_num_rows($result);


    if ($count == 1)
    { 
      $reg = sprintf("UPDATE `usuarios U` SET `id`= $idUser,`username`= $conn->real_escape_string($username),`password`=password_hash($password, PASSWORD_DEFAULT),`Email`= $conn->real_escape_string($email),`FechaNac`=$fechaNac,`Descripcion`=$conn->real_escape_string($descripcion),`imagenPerfil`='img/imgBasica.jpg' WHERE $result = U.id");


      if ($conn->mysqli_query($reg[])=== TRUE)
      {
  
         echo "<br />" . "<h2>" . "Usuario modificado exitosamente!" . "</h2>";
    
      }
      else
      {
        echo "Error al configurar la codificación de la BD: (" . $conn->errno . ") " . utf8_encode($conn->error);
      }
    }
    return $conn;
  }
 
  private $id;

  private $username;

  private $password;

  private $roles;

 private $descripcion;
 
 private $email;

 private $fechaNac;

 private $imgPerfil;

  private function __construct($id, $username, $password, $descripcion, $email,$fechaNac, $imgPerfil) {
    $this->id = $id;
    $this->username = $username;
    $this->password = $password;
    $this->roles = [];
    $this->descripcion = $descripcion;
    $this->email = $email;
    $this->fechaNac = $fechaNac;
    $this->imgPerfil = $imgPerfil;
  }

  public function id() {
    return $this->id;
  }

  public function addRol($role) {
    $this->roles[] = $role;
  }

  public function roles() {
    return $this->roles;
  }

  public function username() {
    return $this->username;
  }
  public function email()
{
	return $this->email;
}
  public function descripcion()
{
	return $this->descripcion;
}
public function imgPerfil()
{
  return $this->imgPerfil;
}
public function fechaNac()
{
  return $this->fechaNac;
}

  public function compruebaPassword($password) {
    return password_verify($password, $this->password);
  }

  public function cambiaPassword($nuevoPassword) {
    $this->password = password_hash($nuevoPassword, PASSWORD_DEFAULT);
  }
}
