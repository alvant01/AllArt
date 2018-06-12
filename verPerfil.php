<?php
namespace es\ucm\fdi\aw;
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/javascript/jsFollow.js';
?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/style.css') ?>" />
  <title>Perfil</title>
</head>
<body>

	<script type="text/javascript">
		
		function seguir(follow, follower, s)
		{
			if(s)
			{
				<input type='button' value='Unfollow' onclick= Seguidos::follow()/>;
			}
			else
			{

			}

		}
	</script>
<div id="contenedor">
<?php
$nombreUsuario=htmlspecialchars(trim(strip_tags($_GET['usuario'])));
$usuario=Usuario::buscaUsuario($nombreUsuario);
$app->doInclude('comun/cabecera.php');
$app->doInclude('comun/sidebarIzq.php');
?>
	<div id="contenido">
		


    	<?php

			
			$user2 = Usuario::buscaUsuario($_SESSION['username']);

			$id = $usuario->id();
    	if(!Seguidos::alredyFollow($id, $user2->id()))
    		{
    			echo "<input type='button' value='Unfollow' onclick=' " . javascript/jsFollow/seguir($id,$user2->id(), true) . "'/>";
    		}
    		else
    		{
    			seguir($id,$user2->id(), false);
    			//echo "<input type='button' value='Follow' onclick=' " .  . "'/>";
    		}
			//echo "<input type='button' value='Follow' onclick='seguir('". $id . "' , '" . $user2->id() . "');'/>";
    		//echo '<script type="text/javascript">','seguir();','</script>';

    
			echo "<img src= '" . $usuario->imgPerfil() . "' border='0' width='100' height='100'>";
			echo "</br>";
			echo 'Nombre: ' . $usuario->username();
			echo "</br>";
			//echo "Email: " . "$usuario->email()";
			//echo "</br>";
			echo "Descripción: " . $usuario->descripcion();
			echo "</br>";
			echo "Fecha nacimiento: " . $usuario->fechaNac();
			echo "</br>";
			
			$img = archivo::buscarImagenDest($id);
			if ($img !== FALSE)
			{
				$ruta = $img->ruta();
				echo "Imagen destacada: ";
				echo "<img src= '" . $ruta . "' border='0' width='300' height='300'>";
				echo "</br>";
			}
			else
			{
				echo $usuario->username() . " no tiene ninguna imagen destacada.";
				echo "</br>";
			}

			//
			$arch = archivo::buscarMejoresArch($id);

			if ($arch !== FALSE ||empty($arch))
			{
				$ite = 0;
				foreach($arch as $value)
				{
					if ($ite == 10)
					{
						break;
					}
					$img = (object) $arch[$ite];
					$ite++;
					echo "<img src= '" . $img->ruta . "' border='0' width='100' height='100'>";
					if ($ite == 4)
					{
						echo "</br>";
					}
				}
			}
			
		?>
	</div>
<?php
$app->doInclude('comun/pie.php');
?>
</div>
</body>
</html>