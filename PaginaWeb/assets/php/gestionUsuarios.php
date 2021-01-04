<article>
	<?php 
		if (isset($_REQUEST['accion'])) {
			$accion = $_REQUEST['accion'];
			switch ($accion) {
				case 'nuevo':
					//echo "<script type='text/javascript'>alert('dd');</script>";
					// crear -----------------------------------
					$email = $_REQUEST['email'];
					$usuario = $_REQUEST['usuario'];
					$nombre = $_REQUEST['nombre'];
					$apellido = $_REQUEST['apellido'];
					$contra = $_REQUEST['contrasenya'];
					$idGrupo = $_REQUEST['grupo'];
					$habilitado = $_REQUEST['habilitado'];
					
					$records = $conn->prepare('SELECT * FROM tbl_usuario WHERE usuario_usuario = :usuario or email_usuario = :email');
					$records->bindParam(':usuario', $usuario);
					$records->bindParam(':email', $email);
					$records->execute();
					$results = $records->fetch(PDO::FETCH_ASSOC);
					if (count($results['nombre_usuario']) > 0) {
						$message = "El usuario o el correo ya exsisten.";
						header('Location: index.php?mostrar=gestionUsuarios&msgFallo='.$message.'#mostrarAñadirUsuario');
					} else {
						$sql = "INSERT INTO `tbl_usuario` (`email_usuario`, `usuario_usuario`, `password_usuario`, `nombre_usuario`, `apellido_usuario`, `habilitado_usuario`, `id_grupoUsuario`) VALUES (:email, :usuario, :password, :nombre, :apellido, '$habilitado', :idGrupo)";
						$stmt = $conn->prepare($sql);
						$stmt->bindParam(':usuario', $usuario);
						$stmt->bindParam(':email', $email);
						$password = password_hash($contra, PASSWORD_BCRYPT);
						$stmt->bindParam(':password', $password);
						$stmt->bindParam(':nombre', $nombre);
						$stmt->bindParam(':apellido', $apellido);
						$stmt->bindParam(':idGrupo', $idGrupo);
						if ($stmt->execute()) {
							$message = "El usuario se ha creado correctamente <a href='index.php' class=login>Inicia Sesión</a>";
							header('Location: index.php?mostrar=gestionUsuarios');
						} else {
							$message = "Lo sentimos, ha debido de haber algun error al crear la cuenta.";
							header('Location: index.php?mostrar=gestionUsuarios&msgFallo='.$message.'#mostrarAñadirUsuario');
						}
					}
					break;
				case 'deshabilitar':
					// deshabilitar -----------------------------------
					$idUsu=$_REQUEST['idUsuario'];
					$query1="UPDATE `tbl_usuario` SET `habilitado_usuario` = 'no' WHERE `tbl_usuario`.`id_usuario` = '$idUsu';";
					$consulta1 = mysqli_query($link, $query1);
					header('Location: index.php?mostrar=gestionUsuarios');
					break;
				case 'habilitar':
					// habilitar -----------------------------------
					$idUsu=$_REQUEST['idUsuario'];
					$query1="UPDATE `tbl_usuario` SET `habilitado_usuario` = 'si' WHERE `tbl_usuario`.`id_usuario` = '$idUsu';";
					$consulta1 = mysqli_query($link, $query1);
					header('Location: index.php?mostrar=gestionUsuarios');
					break;
				case 'editar':
					// editar -----------------------------------
					$idUsu=$_REQUEST['idUsuario'];
					$email = $_REQUEST['email'];
					$usuario = $_REQUEST['usuario'];
					$nombre = $_REQUEST['nombre'];
					$apellido = $_REQUEST['apellido'];
					$contra = $_REQUEST['contrasenya'];
					$idGrupo = $_REQUEST['grupo'];
					$habilitado = $_REQUEST['habilitado'];
					echo "$contra";
					//echo "<script type='text/javascript'>alert('$contra');</script>";
					if ($contra == "") {
						echo "sss";
						$query1="UPDATE `tbl_usuario` SET `email_usuario` = '$email', `usuario_usuario` = '$usuario', `nombre_usuario` = '$nombre', `apellido_usuario` = '$apellido', `habilitado_usuario` = '$habilitado', `id_grupoUsuario` = '$idGrupo' WHERE `tbl_usuario`.`id_usuario` = '$idUsu';";
					} else {
						echo "www";
						$password = password_hash($contra, PASSWORD_BCRYPT);
						echo "$password";
						$query1="UPDATE `tbl_usuario` SET `email_usuario` = '$email', `usuario_usuario` = '$usuario', `password_usuario` = '$password', `nombre_usuario` = '$nombre', `apellido_usuario` = '$apellido', `habilitado_usuario` = '$habilitado', `id_grupoUsuario` = '$idGrupo' WHERE `tbl_usuario`.`id_usuario` = '$idUsu';";
					}
					
					$consulta1 = mysqli_query($link, $query1);
					header('Location: index.php?mostrar=gestionUsuarios');
					break;
				default:
					echo "Error";
					break;
			}	
		}

		

		// Mostrar --------------------------------------------------
		$query = "SELECT * FROM `tbl_usuario` INNER JOIN `tbl_grupoUsuario` ON `tbl_usuario`.`id_grupoUsuario` = `tbl_grupoUsuario`.`id_grupoUsuario` WHERE `tbl_usuario`.`id_usuario` = $idUsuario OR `tbl_grupoUsuario`.`id_grupoUsuario` < $idGrupo ORDER BY `tbl_usuario`.`id_grupoUsuario` ASC, `tbl_usuario`.`usuario_usuario` ASC";
		
		$consulta=mysqli_query($link, $query);

		echo "<div class='aviso'>";
					echo "AVISO: El administrador no puede visualizar a otros administradores. Aqunue sea el el que los de de alta.";
				echo "</div>";
		echo "<div class='tabla'>";
			if(mysqli_num_rows($consulta)>0) {
				echo "<div class='fila encabezado'>";
					echo "<div class='columna noRecursos'>Usuario usuario</div>";
					echo "<div class='columna noRecursos'>Email usuario</div>";
					echo "<div class='columna noRecursos'>Grupo usuario</div>";
					echo "<div class='columna noRecursos'>Nombre usuario</div>";
					echo "<div class='columna noRecursos'>Apellido usuario</div>";
					echo "<div class='columna noRecursos'>Dar de baja</div>";
					echo "<div class='columna noRecursos'>Editar</div>";
				echo "</div>";
				while($array = mysqli_fetch_array($consulta)) {
					$id = $array['id_usuario'];
					$usuario = $array['usuario_usuario'];
					$email = $array['email_usuario'];
					$nivel = $array['nombre_grupoUsuario'];
					$nombre = $array['nombre_usuario'];
					$apellido = $array['apellido_usuario'];
					$habilitado = $array['habilitado_usuario'];
					if ($habilitado == "si") {
					echo "<div class='fila'>";
					} else {
					echo "<div class='fila deshabilitado'>";
					}
						echo "<div class='columna noRecursos'>$usuario</div>";
						echo "<div class='columna noRecursos'>$email</div>";
						echo "<div class='columna noRecursos'>$nivel</div>";
						echo "<div class='columna noRecursos'>$nombre</div>";
						echo "<div class='columna noRecursos'>$apellido</div>";
						if ($habilitado == "si") {
							echo "<div class='columna noRecursos'><a href='index.php?mostrar=gestionUsuarios&accion=deshabilitar&idUsuario=$id'><i class='far fa-eye'></i></a></div>";
						} else {
							echo "<div class='columna noRecursos'><a href='index.php?mostrar=gestionUsuarios&accion=habilitar&idUsuario=$id'><i class='fas fa-eye-slash'></i></a></div>";
						}
						echo "<div class='columna noRecursos'><a href='index.php?mostrar=gestionUsuarios&idUsuario=$id#mostrarEditarUsuario'><i class='fas fa-pencil-alt'></i></a></div>";
					echo "</div>";
				}
			} else {
				echo "Aun no hay usuarios";
			}
		echo "</div>";
		if ($grupoUsuario == 'administrador') {
			echo "<a href='index.php?mostrar=gestionUsuarios#mostrarAñadirUsuario'><input type='button' value='Añadir Usuario'></a>";
		}
	?>

	<!-- Ventanas modales....................................................................................................... -->
	<!-- Ventanas modales....................................................................................................... -->
	<!-- Ventanas modales....................................................................................................... -->
	<!-- Ventanas modales....................................................................................................... -->
	<!-- Ventanas modales....................................................................................................... -->
	<!-- Ventanas modales....................................................................................................... -->
	<!-- Ventanas modales....................................................................................................... -->
	<!-- Ventanas modales....................................................................................................... -->
	<!-- Ventanas modales....................................................................................................... -->
	<!-- Ventanas modales....................................................................................................... -->

	<div id="mostrarAñadirUsuario" class='divEmergente'>
		<div class='subDivEmergente'>
			<a href='#close' title='Close' class='close'>X</a>
			<h3 class='ventanaModal'>Añadir Usuario</h3>
			<div class='formularios'>
				<div id="erroresFormAnyadirUsuario" class="divErroresForms"></div>
				<form name="formValidar5" action='index.php?mostrar=gestionUsuarios&accion=nuevo' method='POST'>
					<label>Email:</label>
					<input class="formValidar5" type='email' name='email'>
					<br style='clear: both;'><br>
					<label>Usuario:</label>
					<input class="formValidar5" type='text' name='usuario'>
					<br style='clear: both;'><br>
					<label>Nombre:</label>
					<input class="formValidar5" type='text' name='nombre'>
					<br style='clear: both;'><br>
					<label>Apellido:</label>
					<input class="formValidar5" type='text' name='apellido'>
					<br style='clear: both;'><br>

					<label>Grupo usuario:</label>
					<select class="login" name="grupo" required>
						<?php
							$query="SELECT * FROM tbl_grupoUsuario";
							$consulta=mysqli_query($link,$query);
							if (mysqli_num_rows($consulta)>0) {
								while ($array=mysqli_fetch_array($consulta)) {
									$idGrupo = $array['id_grupoUsuario'];
									$nombreGrupo = $array['nombre_grupoUsuario'];
									echo "<option value=$idGrupo>$nombreGrupo</option>";
								}
							}
						?>
					</select>
					<br style='clear: both;'><br>

					<label>Habilitado usuario:</label>
					<input type="radio" name="habilitado" value="si" checked>
						<label class="radio">Si</label>
					<input type="radio" name="habilitado" value="no">
						<label class="radio">No</label>
					<br style='clear: both;'><br>

					<label>Contraseña:</label>
					<input id="input_contra5" class="formValidar5" type='password' name='contrasenya'>
					<br style='clear: both;'><br>
					<label>Confirmar contraseña:</label>
					<input id="input_conf_contra5" class="formValidar5" type='password' name='confContrasenya'>
					<br style='clear: both;'><br>
					<!-- <input type="hidden" name="accion" value="nuevo"> -->
					<input type='submit' value='Enviar'>
					<?php
						if (isset($_REQUEST['msgFallo'])) {
							$msg = $_REQUEST['msgFallo'];
							echo "<div class='falloConsulta'>";
								echo "$msg";
							echo "</div>";
						}
					?>
				</form>
			</div>
		</div>
	</div>

	<?php
		if (isset($_REQUEST['idUsuario'])) {
			$idUsu = $_REQUEST['idUsuario'];
			$query = "SELECT * FROM `tbl_usuario` WHERE `id_usuario` = '$idUsu'";
			$consulta = mysqli_query($link, $query);
			$arrayUsu = mysqli_fetch_array($consulta);
		}
	?>
	<div id="mostrarEditarUsuario" class='divEmergente'>
		<div class='subDivEmergente'>
			<a href='#close' title='Close' class='close'>X</a>
			<h3 class='ventanaModal'>Editar Usuario</h3>
			<div class='formularios'>
				<div id="erroresFormAnyadirUsuario" class="divErroresForms"></div>
				<form name="formValidar6" action='index.php?mostrar=gestionUsuarios&accion=editar&idUsuario=<?php echo $idUsu ?>' method='POST'>
					<label>Email:</label>
					<input  class="formValidar6" type='email' name='email' value= "<?php echo $arrayUsu['email_usuario'] ?>" >
					<br style='clear: both;'><br>
					<label>Usuario:</label>
					<input class="formValidar6" type='text' name='usuario' value= "<?php echo $arrayUsu['usuario_usuario'] ?>" >
					<br style='clear: both;'><br>
					<label>Nombre:</label>
					<input class="formValidar6" type='text' name='nombre' value= "<?php echo $arrayUsu['nombre_usuario'] ?>" >
					<br style='clear: both;'><br>
					<label>Apellido:</label>
					<input class="formValidar6" type='text' name='apellido' value= "<?php echo $arrayUsu['apellido_usuario'] ?>" >
					<br style='clear: both;'><br>

					<label>Grupo usuario:</label>
					<select class="login" name="grupo" required>
						<?php
							$query2="SELECT * FROM tbl_grupoUsuario";
							$consulta2=mysqli_query($link,$query2);
							if (mysqli_num_rows($consulta2)>0) {
								while ($array2=mysqli_fetch_array($consulta2)) {
									$idGrupo = $array2['id_grupoUsuario'];
									$nombreGrupo = $array2['nombre_grupoUsuario'];
									if ($idGrupo == $arrayUsu['id_grupoUsuario']) {
										echo "<option value=$idGrupo selected>$nombreGrupo</option>";
									} else {
										echo "<option value=$idGrupo>$nombreGrupo</option>";
									}
									
								}
							}
						?>
					</select>
					<br style='clear: both;'><br>

					<label>Habilitado usuario:</label>

					<?php if ($arrayUsu['habilitado_usuario'] == 'si') { ?>
						<input type="radio" name="habilitado" value="si" checked>
							<label class="radio">Si</label>
						<input type="radio" name="habilitado" value="no">
							<label class="radio">No</label>
						<br style='clear: both;'><br>
					<?php } else { ?>
						<input type="radio" name="habilitado" value="si">
							<label class="radio">Si</label>
						<input type="radio" name="habilitado" value="no" checked>
							<label class="radio">No</label>
						<br style='clear: both;'><br>
					<?php } ?>
						

					<label>Contraseña:</label>
					<input id="input_contra6" type='password' name='contrasenya'>
					<br style='clear: both;'><br>
					<label>Confirmar contraseña:</label>
					<input id="input_conf_contra6" type='password' name='confContrasenya'>
					<br style='clear: both;'><br>
					<!-- <input type="hidden" name="accion" value="nuevo"> -->
					<input type='submit' value='Enviar'>
					<?php
						if (isset($_REQUEST['msgFallo'])) {
							$msg = $_REQUEST['msgFallo'];
							echo "<div class='falloConsulta'>";
								echo "$msg";
							echo "</div>";
						}
					?>
				</form>
			</div>
		</div>
	</div>
</article>