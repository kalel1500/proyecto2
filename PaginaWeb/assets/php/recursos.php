<article>
	<?php
		$cogerFecha = getdate();
		$dia = $cogerFecha['mday'];
		$mes = $cogerFecha['mon'];
		$anyo = $cogerFecha['year'];
		$hora = $cogerFecha['hours'];
		$minuto = $cogerFecha['minutes'];
		$segundo = $cogerFecha['seconds'];
		$fechaActual = $anyo."-".$mes."-".$dia;
		$fechaActual2 = date('Y-m-d');

		// boton añadir recurso ----------------------------------------
		if ($grupoUsuario == 'administrador') {
			echo "<a href='#mostrarAñadirRecurso'><input type='button' value='Añadir Recurso'></a>";
			//echo "<br>";
			echo "<a href='index.php?mostrar=recuperar'><input type='button' value='Recuperar Recurso'></a>";
		}
		
		// Insertar --------------------------------------------------
		if (isset($_REQUEST['accionRec']) && $_REQUEST['accionRec'] == 'insertar') {
			if (isset($_REQUEST['nombreRecurso'])) {
				$nombreRec = $_REQUEST['nombreRecurso'];
				$descRec = $_REQUEST['descripcionRecurso'];
				$imgNombre = $_REQUEST['nombreImg'];
				$idTipoRec = $_REQUEST['idTipoRec'];
				$query="INSERT INTO `tbl_recurso` (`nombreArchivos_recurso`, `nombre_recurso`, `descripcion_recurso`, `disp_recurso`, `id_tipoRecurso`) VALUES ('$imgNombre', '$nombreRec', '$descRec', 'si', '$idTipoRec');";
				mysqli_query($link,$query);
				header('Location: index.php');
			}
			if (isset($_REQUEST['nombreTipoRecurso'])) {
				$nombreTipoRec = $_REQUEST['nombreTipoRecurso'];
				$query="INSERT INTO `tbl_tiporecurso` (`nombre_tipoRecurso`) VALUES ('$nombreTipoRec');";
				mysqli_query($link,$query);
				header('Location: index.php');
			}
		}

		// eliminar --------------------------------------------------
		if (isset($_REQUEST['accionRec']) && $_REQUEST['accionRec'] == 'eliminar') {
			$idRecurso = $_REQUEST['idRecurso'];
			
			$query="UPDATE `tbl_recurso` SET `estadoEliminado_recurso` = 'si' WHERE `tbl_recurso`.`id_recurso` = '$idRecurso';";
			mysqli_query($link,$query);
			header('Location: index.php');
		}

		// editar --------------------------------------------------
		if (isset($_REQUEST['accionRec']) && $_REQUEST['accionRec'] == 'editar') {
			$idRecurso = $_REQUEST['idRecurso'];
			
			$nombreRec = $_REQUEST['nombreRecurso'];
			$descRec = $_REQUEST['descripcionRecurso'];
			$imgNombre = $_REQUEST['nombreImg'];
			$idTipoRec = $_REQUEST['idTipoRec'];
			$disp = $_REQUEST['dispRec'];
			
			$query1="UPDATE `tbl_recurso` SET `nombreArchivos_recurso` = '$imgNombre', `nombre_recurso` = '$nombreRec', `descripcion_recurso` = '$descRec', `disp_recurso` = '$disp', `id_tipoRecurso` = '$idTipoRec' WHERE `tbl_recurso`.`id_recurso` = '$idRecurso';";
			
			$consulta1 = mysqli_query($link, $query1);
			header('Location: index.php');
		}

		// Mostrar --------------------------------------------------
		$consulta=mysqli_query($link, "SELECT * FROM tbl_recurso WHERE `estadoEliminado_recurso` = 'no' ORDER BY id_recurso");
		
		if ($grupoUsuario == 'administrador') {
			?><div class='aviso'>
				AVISO: Los recursos deshabilitados lo estan a causa de una incidencia.
				<br>
				AVISO: El administador puede editar los recursos deshabilitados.
				<br>
				AVISO: Al elimminar un recurso solo se elimina en apariencia, internamente sigue en la bd pero no se puede ver de ningun modo.
			</div><?php
		} else {
			?><div class='aviso'>
				AVISO: Los recursos deshabilitados lo estan a causa de una incidencia.
			</div><?php
		}
			
		echo "<div class='tabla tablaRecursos'>";
			include "assets/php/filtrosRecursos.php";
			if(mysqli_num_rows($consulta)>0) {
				while($array = mysqli_fetch_array($consulta)) {
					$idRecurso=$array['id_recurso'];
					$nombre = $array['nombre_recurso'];
					$disponible = $array['disp_recurso'];
					$rutaImagen = $array['rutaArchivos_recurso'];
					$nombreImagen = $array['nombreArchivos_recurso'];
					$extensionImagen = $array['extensionArchivos_recurso'];
					$imagen = $rutaImagen.$nombreImagen.$extensionImagen;
					if ($disponible == 'si') {
						echo "<div class='fila filaRecursos'>";
							echo "<div class='columna columnaRecursos'><img class='imgRecursos' src='$imagen'></div>";
							echo "<div class='columna columnaRecursos'>$nombre</div>";
							if (isset($_REQUEST['buscar'])) {
								$filtroBus = $_REQUEST['buscar'];
								$filtroTip = $_REQUEST['filtrarTipoRecurso'];
								$filtroDisp = $_REQUEST['disponibilidad'];
								echo "<div class='columna columnaRecursos'>";
									echo "<a href='index.php?buscar=$filtroBus&filtrarTipoRecurso=$filtroTip&disponibilidad=$filtroDisp&idRecurso=$idRecurso#mostrarAñadirReserva'>";
										echo "<input type='button' value='Reservar'>";
									echo "</a>";
									if ($grupoUsuario == 'administrador') {
										// boton eliminar.......................
										echo "<a href='index.php?buscar=$filtroBus&filtrarTipoRecurso=$filtroTip&disponibilidad=$filtroDisp&accionRec=eliminar&idRecurso=$idRecurso'>";
											echo "<i class='fas fa-times'></i>";
										echo "</a>";
										// boton editar.......................
										echo "<a href='index.php?buscar=$filtroBus&filtrarTipoRecurso=$filtroTip&disponibilidad=$filtroDisp&idRecurso=$idRecurso#mostrarEditarRecurso'>";
											echo "<i class='fas fa-pencil-alt'></i>";
										echo "</a>";
									}
								echo "</div>";
									
							} else {
								echo "<div class='columna columnaRecursos'>";
									echo "<a href='index.php?idRecurso=$idRecurso#mostrarAñadirReserva'>";
										echo "<input type='button' value='Reservar'>";
									echo "</a>";
									if ($grupoUsuario == 'administrador') {
										// boton eliminar.......................
										echo "<a href='index.php?accionRec=eliminar&idRecurso=$idRecurso'>";
											echo "<i class='fas fa-times'></i>";
										echo "</a>";
										// boton editar.......................
										echo "<a href='index.php?idRecurso=$idRecurso#mostrarEditarRecurso'>";
											echo "<i class='fas fa-pencil-alt'></i>";
										echo "</a>";
									}
								echo "</div>";
							}
						echo "</div>";
					} else {
						echo "<div class='fila filaRecursos'>";
							echo "<div class='columna columnaRecursos desabilitado'><img class='imgRecursos' src='$imagen'></div>";
							echo "<div class='columna columnaRecursos desabilitado'>$nombre</div>";
							echo "<div class='columna columnaRecursos'>";
								echo "<input class='desabilitado' type='button' value='Reservar'>";
								if ($grupoUsuario == 'administrador') {
									// boton eliminar.......................
									echo "<i class='desabilitado fas fa-times'></i>";
									// boton editar.......................
									echo "<a href='index.php?idRecurso=$idRecurso#mostrarEditarRecurso'>";
											echo "<i class='fas fa-pencil-alt'></i>";
									echo "</a>";
								}
							echo "</div>";
						echo "</div>";
					}
				}
			} else {
				echo "No hay recursos que visualizar";
			}
		echo "</div>";
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
	<div id='mostrarAñadirRecurso' class='divEmergente'>
		<div class='subDivEmergente'>
			<a href='#close' title='Close' class='close'>X</a>
			<h3 class='ventanaModal'>Añadir Recurso</h3>
			<div class='formularios'>
				<form name="formValidar1" action='index.php?mostrar=recursos&accionRec=insertar' method='POST'>
					<label>Nombre Recurso:</label>
					<input class="formValidar1" type='text' name='nombreRecurso' placeholder='Nombre del recurso'>
					<br style='clear: both;'><br>
					<label>Descripción del Recurso:</label>
					<textarea class="formValidar1" rows='10' cols='70' name='descripcionRecurso' placeholder='Descripcion del recurso'></textarea>
					<br style='clear: both;'><br>
					<label>Nombre de la imagen:</label>
					<input class="formValidar1" type="text" name="nombreImg">
					<br style='clear: both;'><br>
					<label>Tipo de Recurso:</label>
					<select class="formValidar1" name="idTipoRec">
						<option value="" class="opHidden">-- selecciona --</option>
						<?php
							/*falta inner para que solo se muestren los recursos que estan en una reserva*/
							$consulta=mysqli_query($link, "SELECT * FROM tbl_tiporecurso ORDER BY id_tipoRecurso");
							if(mysqli_num_rows($consulta)>0) {
								while($array = mysqli_fetch_array($consulta)) {
									$idTipoRecurso = $array['id_tipoRecurso'];
									$nombreTipoRecurso = $array['nombre_tipoRecurso'];									
									echo "<option value='$idTipoRecurso'>$nombreTipoRecurso</option>";
								}
							}
						?>
						<option value='El campo es obligatorio' class="opHidden">El campo es obligatorio</option>
					</select><br>
					<a href="#mostrarAñadirTipoRecurso"><input type="button" value="Añadir Tipo Recurso"></a>
					<br style='clear: both;'><br>
					<input type='submit' value='Enviar'>
				</form>
			</div>
		</div>
	</div>

	<?php
		if (isset($_REQUEST['idRecurso'])) {
			$idRec = $_REQUEST['idRecurso'];
			$query = "SELECT * FROM `tbl_recurso` WHERE `id_recurso` = '$idRec'";
			$consulta = mysqli_query($link, $query);
			$arrayRec = mysqli_fetch_array($consulta);
		}
	?>
	<div id='mostrarEditarRecurso' class='divEmergente'>
		<div class='subDivEmergente'>
			<a href='#close' title='Close' class='close'>X</a>
			<h3 class='ventanaModal'>Editar Recurso</h3>
			<div class='formularios'>
				<form name="formValidar4" action='index.php?mostrar=recursos&accionRec=editar&idRecurso=<?php echo $idRec ?>' method='POST'>
					<label>Nombre Recurso:</label>
					<input class="formValidar4" type='text' name='nombreRecurso' placeholder='Nombre del recurso' value= "<?php echo $arrayRec['nombre_recurso'] ?>" >
					<br style='clear: both;'><br>
					<label>Descripción del Recurso:</label>
					<textarea class="formValidar4" rows='10' cols='70' name='descripcionRecurso' placeholder='Descripcion del recurso'><?php echo $arrayRec['descripcion_recurso'] ?></textarea>
					<br style='clear: both;'><br>
					<label>Nombre de la imagen:</label>
					<input class="formValidar4" type="text" name="nombreImg" value= "<?php echo $arrayRec['nombreArchivos_recurso'] ?>">
					<br style='clear: both;'><br>
					<label>Tipo de Recurso:</label>
					<select class="formValidar4" name="idTipoRec">
						<option value="" class="opHidden">-- selecciona --</option>
						<?php
							/*falta inner para que solo se muestren los recursos que estan en una reserva*/
							$consulta=mysqli_query($link, "SELECT * FROM tbl_tiporecurso ORDER BY id_tipoRecurso");
							if(mysqli_num_rows($consulta)>0) {
								while($array = mysqli_fetch_array($consulta)) {
									$idTipoRecurso = $array['id_tipoRecurso'];
									$nombreTipoRecurso = $array['nombre_tipoRecurso'];
									if ($idTipoRecurso == $arrayRec['id_tipoRecurso']) {
										echo "<option value='$idTipoRecurso' selected>$nombreTipoRecurso</option>";
									} else {
										echo "<option value='$idTipoRecurso'>$nombreTipoRecurso</option>";
									}
								}
							}
						?>
						<option value='El campo es obligatorio' class="opHidden">El campo es obligatorio</option>
					</select>
					<br style='clear: both;'><br>

					<label>Disponibilidad recurso:</label>
					<?php if ($arrayRec['disp_recurso'] == 'si') { ?>
						<input type="radio" name="dispRec" value="si" checked>
							<label class="radio">Si</label>
						<input type="radio" name="dispRec" value="no">
							<label class="radio">No</label>
						<br style='clear: both;'><br>
					<?php } else { ?>
						<input type="radio" name="dispRec" value="si">
							<label class="radio">Si</label>
						<input type="radio" name="dispRec" value="no" checked>
							<label class="radio">No</label>
						<br style='clear: both;'><br>
					<?php } ?>
					<br style='clear: both;'><br>

					<input type='submit' value='Enviar'>
				</form>
			</div>
		</div>
	</div>

	<div id='mostrarAñadirTipoRecurso' class='divEmergente'>
		<div class='subDivEmergente'>
			<a href='#close' title='Close' class='close'>X</a>
			<h3 class='ventanaModal'>Añadir Tipo Recurso</h3>
			<div class='formularios'>
				<form name="formValidar2" action='index.php?mostrar=recursos' method='POST'>
					<label>Nombre Tipo Recurso:</label>
					<input class="formValidar2" type='text' name='nombreTipoRecurso' placeholder='Nombre del tipo de recurso'>
					<br style='clear: both;'><br>
					<input type='submit' value='Enviar'>
				</form>
			</div>
		</div>
	</div>
	<?php
		if (isset($_REQUEST['idRecurso'])) {
			$idRecurso = $_REQUEST['idRecurso'];
		}
	?>
	<div id="mostrarAñadirReserva" class='divEmergente'>
		<div class='subDivEmergente'>
			<a href='#close' title='Close' class='close'>X</a>
			<h3 class='ventanaModal'>Añadir Reserva</h3>
			<div class='aviso'>
				AVISO: Para una mejor comprobacion de las funciones de la aplicacion, se permite reservar un recurso en una franja horaria anterior a la hora actual.
			</div>
			<div class='formularios'>
				<div id="erroresFormAnyadirReserva" class="divErroresForms"></div>
				<form name="formValidar3" action='index.php?mostrar=reservas&accionRes=insertar' method='POST'>
					<label>Fecha inicio:</label>
					<input id="fechaIni" class="formValidar3" type='date' name='fechaInicio_reserva' min="<?php echo $fechaActual2; ?>">
					<br style='clear: both;'><br>
					<label>Fecha fin:</label>
					<input id="fechaFin" class="formValidar3" type='date' name='fechaFin_reserva' min="<?php echo $fechaActual2; ?>">
					<br style='clear: both;'><br>
					<label>Hora inicio:</label>
					<select id="horaIni" class="anyadir" name="horaInicio_reserva">
						<option value="08:00:00">08:00</option>
						<option value="09:00:00">09:00</option>
						<option value="10:00:00">10:00</option>
						<option value="11:00:00">11:00</option>
						<option value="12:00:00">12:00</option>
						<option value="13:00:00">13:00</option>
						<option value="14:00:00">14:00</option>
						<option value="15:00:00">15:00</option>
						<option value="16:00:00">16:00</option>
						<option value="17:00:00">17:00</option>
						<option value="18:00:00">18:00</option>
						<option value="19:00:00">19:00</option>
						<option value="20:00:00">20:00</option>
						<option value="21:00:00">21:00</option>
					</select>
					<!-- <input class="formValidar3" type='number' name='horaInicio_reserva' min="0" max="23" placeholder='Hora inicio'> -->
					<br style='clear: both;'><br>
					<label>Hora fin:</label>
					<select id="horaFin" class="anyadir" name="horaFin_reserva">
						<option value="08:00:00">08:00</option>
						<option value="09:00:00">09:00</option>
						<option value="10:00:00">10:00</option>
						<option value="11:00:00">11:00</option>
						<option value="12:00:00">12:00</option>
						<option value="13:00:00">13:00</option>
						<option value="14:00:00">14:00</option>
						<option value="15:00:00">15:00</option>
						<option value="16:00:00">16:00</option>
						<option value="17:00:00">17:00</option>
						<option value="18:00:00">18:00</option>
						<option value="19:00:00">19:00</option>
						<option value="20:00:00">20:00</option>
						<option value="21:00:00">21:00</option>
					</select>
					<!-- <input class="formValidar3" type='number' name='horaFin_reserva' min="0" max="23" placeholder='Hora fin'> -->
					<br style='clear: both;'><br>
					<label>Descripción reserva:</label>
					<textarea class="formValidar3" rows='10' cols='70' name='descripcionReserva' placeholder='Explica brevemente tu reserva'></textarea>
					<input type="hidden" name="idRecurso" value=<?php echo"$idRecurso" ?>>
					<br><br>
					<br style='clear: both;'>
					<input type='submit' value='Enviar'>
					<?php
						if (isset($_REQUEST['msgFallo'])) {
							$msg = $_REQUEST['msgFallo'];
							echo "$msg";
						}
					?>
				</form>
				<a href="#mostrarCalendarioReservas">Ver calendario reservas</a>
			</div>
		</div>
	</div>
	<div id='mostrarCalendarioReservas' class='divEmergente'>
		<div class='subDivEmergente'>
			<a href='#close' title='Close' class='close'>X</a>
			<h3 class='ventanaModal'>Calendario Reservas</h3>
			<div class='formularios'>
				<?php
					$query = "SELECT * FROM tbl_reserva INNER JOIN `tbl_recurso` ON `tbl_reserva`.`id_recurso` = `tbl_recurso`.`id_recurso` INNER JOIN `tbl_usuario` ON `tbl_reserva`.`id_usuario` = `tbl_usuario`.`id_usuario` INNER JOIN `tbl_tiporecurso` ON `tbl_recurso`.`id_tipoRecurso` = `tbl_tiporecurso`.`id_tipoRecurso` WHERE `tbl_reserva`.`modoFinalizacion_reserva` = 'pendiente' AND `tbl_recurso`.`id_recurso` = $idRecurso ORDER BY `fechaInicio_reserva` DESC";
					//echo "$query";
					$consulta=mysqli_query($link, $query);
					echo "<div class='tabla'>";
						if(mysqli_num_rows($consulta)>0) {
							echo "<div class='fila encabezado'>";
								echo "<div class='columna noRecursos'>Recurso</div>";
								echo "<div class='columna noRecursos'>Fecha inicio</div>";
								echo "<div class='columna noRecursos'>Fecha final</div>";
							echo "</div>";
							while($array = mysqli_fetch_array($consulta)) {
								$idReserva = $array['id_reserva'];
								$descripcion = $array['descripcion_reserva'];
								$fechaInicio = $array['fechaInicio_reserva'];
								$fechaFin = $array['fechaFinal_reserva'];
								$modoFinalizacion = $array['modoFinalizacion_reserva'];
								/*del inner-------------------------------------*/
								$recurso = $array['nombre_recurso'];
								$tipo = $array['nombre_tipoRecurso'];
								$usuario = $array['usuario_usuario'];
								echo "<div class='fila'>";
									echo "<div class='columna noRecursos'>$recurso</div>";
									echo "<div class='columna noRecursos'>$fechaInicio</div>";
									echo "<div class='columna noRecursos'>$fechaFin</div>";
								echo "</div>";
							}
						} else {
							echo "No se han hecho reservas del recurso";
						}
					echo "</div>";
				?>
				<a href="#mostrarAñadirReserva">Volver</a>
			</div>
		</div>
	</div>
	<!-- <a href="index.php?insertaRecurso=si">Insertar Recursos <i class="fas fa-plus-square"></i></a><br> -->
</article>