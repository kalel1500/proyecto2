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
		$fechaCompuestaAcual = $anyo."-".$mes."-".$dia." ".$hora.":".$minuto.":".$segundo;
		$fechaCompuestaAcual2 = date('Y-m-d H:i:s');
		// insertar reserva -----------------------------------
		if (isset($_REQUEST['accionRes']) && $_REQUEST['accionRes'] == 'insertar') {
			
			if (isset($_REQUEST['fechaInicio_reserva'])) {
				$bien = true;
				$idRecurso=$_REQUEST['idRecurso'];
				$fechaInicio=$_REQUEST['fechaInicio_reserva'];
				$fechaFin=$_REQUEST['fechaFin_reserva'];
				$horaInicio=$_REQUEST['horaInicio_reserva'];
				$horaFin=$_REQUEST['horaFin_reserva'];
				$descripcion=$_REQUEST['descripcionReserva'];
				$fechaInicioCompuesta = $fechaInicio." ".$horaInicio;
				$fechaFinCompuesta = $fechaFin." ".$horaFin;
				/*echo "Datos insertados <br>";
				echo "$fechaInicioCompuesta <br>";
				echo "$fechaFinCompuesta <br>";
				echo "<br>";
				echo "<br>";*/
				// comparaciones ---------------------------------
				$queryComparacion = "SELECT * FROM tbl_reserva INNER JOIN `tbl_recurso` ON `tbl_reserva`.`id_recurso` = `tbl_recurso`.`id_recurso` INNER JOIN `tbl_usuario` ON `tbl_reserva`.`id_usuario` = `tbl_usuario`.`id_usuario` INNER JOIN `tbl_tiporecurso` ON `tbl_recurso`.`id_tipoRecurso` = `tbl_tiporecurso`.`id_tipoRecurso` WHERE `tbl_reserva`.`modoFinalizacion_reserva` = 'pendiente' AND `tbl_recurso`.`id_recurso` = $idRecurso ORDER BY `fechaInicio_reserva` DESC";
				
				$consultaComparacion=mysqli_query($link, $queryComparacion);
				if(mysqli_num_rows($consultaComparacion)>0) {
					while($arrayComparacion = mysqli_fetch_array($consultaComparacion)) {
						$idReservaComparacion = $arrayComparacion['id_reserva'];
						$descripcionComparacion = $arrayComparacion['descripcion_reserva'];
						$fechaInicioComparacion = $arrayComparacion['fechaInicio_reserva'];
						$fechaFinComparacion = $arrayComparacion['fechaFinal_reserva'];
						$modoFinalizacionComparacion = $arrayComparacion['modoFinalizacion_reserva'];
						/*del inner-------------------------------------*/
						$recursoComparacion = $arrayComparacion['nombre_recurso'];
						$tipoComparacion = $arrayComparacion['nombre_tipoRecurso'];
						$usuarioComparacion = $arrayComparacion['usuario_usuario'];
						/*echo "Datos bd <br>";
						echo "$fechaInicioComparacion <br>";
						echo "$fechaFinComparacion <br>";
						echo "<br>";*/
						//if ($fechaFinCompuesta <= $fechaInicioComparacion || $fechaInicioCompuesta >= $fechaFinComparacion) {
						if ($fechaFinCompuesta > $fechaInicioComparacion && $fechaInicioCompuesta < $fechaFinComparacion) {
							$bien = false;
						}
					}
				}

				// inesertar ---------------------------------------------
				$query1="INSERT INTO `tbl_reserva` ( `descripcion_reserva`, `fechaRealizacion_reserva`, `fechaInicio_reserva`, `fechaFinal_reserva`, `modoFinalizacion_reserva`, `id_usuario`, `id_recurso`) VALUES ('$descripcion', '$fechaCompuestaAcual2', '$fechaInicioCompuesta', '$fechaFinCompuesta', 'pendiente', '$idUsuario', '$idRecurso');";
				//echo "$query1";
				if ($bien) {
					$consulta1 = mysqli_query($link, $query1);
					header('Location: index.php?mostrar=reservas');
				} else {
					$consultaFallida = "<div class='falloConsulta'>Ya hay una reserva en las fechas seleccionadas. Consulte el calendario.</div>";
					header('Location: index.php?idRecurso='.$idRecurso.'&msgFallo='.$consultaFallida.'#mostrarAñadirReserva');
				}
			}
		}
		// Confirmar finalizalizacion reserva -------------------------------------
		if (isset($_REQUEST['idReservaConfirmar'])) {
			$idReserva=$_REQUEST['idReservaConfirmar'];
			$update1=mysqli_query($link, "UPDATE `tbl_reserva` SET `modoFinalizacion_reserva` = 'bien' WHERE `tbl_reserva`.`id_reserva` = '$idReserva';");
		}

		// Cancelar reserva -------------------------------------
		if (isset($_REQUEST['idReservaCancelar'])) {
			$idReserva=$_REQUEST['idReservaCancelar'];
			$update1=mysqli_query($link, "UPDATE `tbl_reserva` SET `modoFinalizacion_reserva` = 'cancelada', `fechaFinal_reserva` = '$fechaCompuestaAcual2' WHERE `tbl_reserva`.`id_reserva` = '$idReserva';");
		}

		// Eliminar reserva -------------------------------------
		if (isset($_REQUEST['idReservaEliminar'])) {
			$idReserva=$_REQUEST['idReservaEliminar'];
			$update1=mysqli_query($link, "DELETE FROM `tbl_reserva` WHERE `tbl_reserva`.`id_reserva` = '$idReserva';");
		}

		// editar --------------------------------------------------
		if (isset($_REQUEST['accionRes']) && $_REQUEST['accionRes'] == 'editar') {
			if (isset($_REQUEST['idReservaEditar'])) {

				$idRes = $_REQUEST['idReservaEditar'];
				$queryScarId = "SELECT * FROM tbl_reserva INNER JOIN `tbl_recurso` ON `tbl_reserva`.`id_recurso` = `tbl_recurso`.`id_recurso` WHERE `tbl_reserva`.`id_reserva` = '$idRes'";
				$consultaScarId=mysqli_query($link, $queryScarId);
				$arrayScarId = mysqli_fetch_array($consultaScarId);
				$idRecurso = $arrayScarId['id_recurso'];


				$bien = true;
				//$idRecurso=$_REQUEST['idRecurso'];
				$fechaInicio=$_REQUEST['fechaInicio_reserva'];
				$fechaFin=$_REQUEST['fechaFin_reserva'];
				$horaInicio=$_REQUEST['horaInicio_reserva'];
				$horaFin=$_REQUEST['horaFin_reserva'];
				$descripcion=$_REQUEST['descripcionReserva'];
				$fechaInicioCompuesta = $fechaInicio." ".$horaInicio;
				$fechaFinCompuesta = $fechaFin." ".$horaFin;
				/*echo "Datos insertados <br>";
				echo "$fechaInicioCompuesta <br>";
				echo "$fechaFinCompuesta <br>";
				echo "<br>";
				echo "<br>";*/

				// comparaciones ---------------------------------
				$queryComparacion = "SELECT * FROM tbl_reserva INNER JOIN `tbl_recurso` ON `tbl_reserva`.`id_recurso` = `tbl_recurso`.`id_recurso` INNER JOIN `tbl_usuario` ON `tbl_reserva`.`id_usuario` = `tbl_usuario`.`id_usuario` INNER JOIN `tbl_tiporecurso` ON `tbl_recurso`.`id_tipoRecurso` = `tbl_tiporecurso`.`id_tipoRecurso` WHERE `tbl_reserva`.`modoFinalizacion_reserva` = 'pendiente' AND `tbl_recurso`.`id_recurso` = $idRecurso AND `tbl_reserva`.`id_reserva` != $idRes ORDER BY `fechaInicio_reserva` DESC";
				/*echo "$queryComparacion";
				echo "<br>";
				echo "<br>";*/
				$consultaComparacion=mysqli_query($link, $queryComparacion);
				if(mysqli_num_rows($consultaComparacion)>0) {
					echo "sss";
					while($arrayComparacion = mysqli_fetch_array($consultaComparacion)) {
						$idReservaComparacion = $arrayComparacion['id_reserva'];
						$descripcionComparacion = $arrayComparacion['descripcion_reserva'];
						$fechaInicioComparacion = $arrayComparacion['fechaInicio_reserva'];
						$fechaFinComparacion = $arrayComparacion['fechaFinal_reserva'];
						$modoFinalizacionComparacion = $arrayComparacion['modoFinalizacion_reserva'];
						/*del inner-------------------------------------*/
						$recursoComparacion = $arrayComparacion['nombre_recurso'];
						$tipoComparacion = $arrayComparacion['nombre_tipoRecurso'];
						$usuarioComparacion = $arrayComparacion['usuario_usuario'];
						/*echo "Datos bd <br>";
						echo "$fechaInicioComparacion <br>";
						echo "$fechaFinComparacion <br>";
						echo "<br>";*/
						//if ($fechaFinCompuesta <= $fechaInicioComparacion || $fechaInicioCompuesta >= $fechaFinComparacion) {
						if ($fechaFinCompuesta > $fechaInicioComparacion && $fechaInicioCompuesta < $fechaFinComparacion) {
							$bien = false;
						}
					}
				}

				// inesertar ---------------------------------------------
				$query1="UPDATE `tbl_reserva` SET `descripcion_reserva` = '$descripcion', `fechaInicio_reserva` = '$fechaInicioCompuesta', `fechaFinal_reserva` = '$fechaFinCompuesta' WHERE `tbl_reserva`.`id_reserva` = '$idRes';";
				echo "$query1";
				if ($bien) {
					$consulta1 = mysqli_query($link, $query1);
					header('Location: index.php?mostrar=reservas');
				} else {
					$consultaFallida = "<div class='falloConsulta'>Ya hay una reserva en las fechas seleccionadas. Consulte el calendario.</div>";
					header('Location: index.php?mostrar=reservas&idReservaEditar='.$idRes.'&msgFallo='.$consultaFallida.'#mostrarEditarReserva');
				}
			}
		}
			

		// Mostrar --------------------------------------------------
		if (isset($_REQUEST['idUsuMisReservas'])) {
			$idUsuMisReservas=$_REQUEST['idUsuMisReservas'];
			$query = "SELECT * FROM tbl_reserva INNER JOIN `tbl_recurso` ON `tbl_reserva`.`id_recurso` = `tbl_recurso`.`id_recurso` INNER JOIN `tbl_usuario` ON `tbl_reserva`.`id_usuario` = `tbl_usuario`.`id_usuario` INNER JOIN `tbl_tiporecurso` ON `tbl_recurso`.`id_tipoRecurso` = `tbl_tiporecurso`.`id_tipoRecurso` WHERE `tbl_usuario`.`id_usuario`='$idUsuMisReservas' ORDER BY `fechaRealizacion_reserva` DESC";
			$consulta=mysqli_query($link, $query);
			$boton = true;
		} else {
			$query = "SELECT * FROM tbl_reserva INNER JOIN `tbl_recurso` ON `tbl_reserva`.`id_recurso` = `tbl_recurso`.`id_recurso` INNER JOIN `tbl_usuario` ON `tbl_reserva`.`id_usuario` = `tbl_usuario`.`id_usuario` INNER JOIN `tbl_tiporecurso` ON `tbl_recurso`.`id_tipoRecurso` = `tbl_tiporecurso`.`id_tipoRecurso` ORDER BY `fechaRealizacion_reserva` DESC";
			
			$consulta=mysqli_query($link, $query);
			$boton = false;
		}
		echo "<div class='contenedorFiltros'>";
			include "assets/php/filtrosReservas.php";
			if (isset($_REQUEST['idUsuMisReservas'])) {
				echo "<div class='aviso'>";
					echo "AVISO: Si la fecha actual es mayor que la fecha final, el usuario debe confirmar devolucion. En caso de que no haya comenzado el periodo de la reserva usuario puede cancelarla. Si el periodo de reserva no ha finalizado, el boton de confirmar estara deshabilitado.";
				echo "</div>";
			} elseif ($grupoUsuario == 'administrador') {
				echo "<div class='aviso'>";
					echo "AVISO: El administrador puede editar y eliminar las reservas que no hayan empezado el periodo de resrva";
				echo "</div>";
			}
			echo "<div class='tabla'>";
				if(mysqli_num_rows($consulta)>0) {
					echo "<div class='fila encabezado'>";
						echo "<div class='columna noRecursos'>Recurso</div>";
						echo "<div class='columna noRecursos'>Tipo</div>";
						echo "<div class='columna noRecursos'>Descripción</div>";
						echo "<div class='columna noRecursos'>Fecha inicio</div>";
						echo "<div class='columna noRecursos'>Fecha final</div>";
						echo "<div class='columna noRecursos'>Modo finalizacion</div>";
						echo "<div class='columna noRecursos'>Usuario</div>";
						if ($grupoUsuario == 'administrador') {
							echo "<div class='columna noRecursos'>Eliminar</div>";
							echo "<div class='columna noRecursos'>Editar</div>";
						}
					echo "</div>";
					while($array = mysqli_fetch_array($consulta)) {
						$idReserva = $array['id_reserva'];
						$descripcion = $array['descripcion_reserva'];
						$fechaInicio = $array['fechaInicio_reserva'];
						$fechaFin = $array['fechaFinal_reserva'];
						$modoFinalizacion = $array['modoFinalizacion_reserva'];
						/*del inner-------------------------------------*/
						$idRecurso = $array['id_recurso'];
						$recurso = $array['nombre_recurso'];
						$tipo = $array['nombre_tipoRecurso'];
						$usuario = $array['usuario_usuario'];
						echo "<div class='fila'>";
							echo "<div class='columna noRecursos'>$recurso</div>";
							echo "<div class='columna noRecursos'>$tipo</div>";
							echo "<div class='columna noRecursos'>$descripcion</div>";
							echo "<div class='columna noRecursos'>$fechaInicio</div>";
							echo "<div class='columna noRecursos'>$fechaFin</div>";
							echo "<div class='columna noRecursos'>$modoFinalizacion</div>";
							echo "<div class='columna noRecursos'>$usuario</div>";
							if ($boton && $modoFinalizacion == 'pendiente') {
								/*echo "Fechas:----<br>";
								echo "Fecha inicio: $fechaInicio<br>";
								echo "Fecha fin: $fechaFin<br>";
								echo "Fecha actual: $fechaCompuestaAcual<br>";
								echo "Fecha actual2: $fechaCompuestaAcual2<br>";*/
								if ($fechaFin < $fechaCompuestaAcual2) {
									echo "<div class='columna noRecursos'><a href='index.php?mostrar=reservas&idUsuMisReservas=$idUsuMisReservas&idReservaConfirmar=$idReserva'><input class='añadir-lista' type='button' value='Confirmar devolucion' title='Periodo de reserva finalizado'></a></div>";
								} elseif ($fechaInicio > $fechaCompuestaAcual2) {
									echo "<div class='columna noRecursos'><a href='index.php?mostrar=reservas&idUsuMisReservas=$idUsuMisReservas&idReservaCancelar=$idReserva'><input class='añadir-lista' type='button' value='Cancelar' title='El periodo de reserva aun no ha empezado'></a></div>";
									
								} elseif ($fechaFin > $fechaCompuestaAcual2) {
									echo "<div class='columna noRecursos desabilitado'><input class='añadir-lista desabilitado' type='button' value='Confirmar devolucion' title='La reserva aun no ha finalizado'></div>";
								}
								
							}

							if ($grupoUsuario == 'administrador' && $modoFinalizacion == 'pendiente' && $fechaInicio > $fechaCompuestaAcual2) {
								echo "<div class='columna noRecursos'>";
									echo "<a href='index.php?mostrar=reservas&idReservaEliminar=$idReserva'>";
										//echo "<input class='añadir-lista' type='button' value='Eliminar' title='Eliminar como administrador'>";
										echo "<i class='fas fa-times' title='Eliminar como administrador'></i>";
									echo "</a>";
								echo "</div>";
								echo "<div class='columna noRecursos'>";
									echo "<a href='index.php?mostrar=reservas&idReservaEditar=$idReserva#mostrarEditarReserva'>";
										echo "<i class='fas fa-pencil-alt' title='Editar como administrador'></i>";
									echo "</a>";
								echo "</div>";
							}
							
							
						echo "</div>";
					}
				} else {
					echo "Aun no hay reservas";
				}
			echo "</div>";
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

	<?php
		if (isset($_REQUEST['idReservaEditar'])) {
			$idRes = $_REQUEST['idReservaEditar'];
			$query = "SELECT *, DATE(fechaInicio_reserva) AS soloFechaInicio, TIME(fechaInicio_reserva) AS soloHoraInicio, DATE(fechaFinal_reserva) AS soloFechaFin, TIME(fechaFinal_reserva) AS soloHoraFin FROM `tbl_reserva` WHERE `id_reserva` = '$idRes'";
			$consulta = mysqli_query($link, $query);
			$arrayRec = mysqli_fetch_array($consulta);

			$fechaInicioCompuesta = $arrayRec['fechaInicio_reserva'];
			$soloFechaInicio = $arrayRec['soloFechaInicio'];
			$soloHoraInicio = $arrayRec['soloHoraInicio'];

			$fechaFinCompuesta = $arrayRec['fechaFinal_reserva'];
			$soloFechaFin = $arrayRec['soloFechaFin'];
			$soloHoraFin = $arrayRec['soloHoraFin'];
		}
	?>
	<div id="mostrarEditarReserva" class='divEmergente'>
		<div class='subDivEmergente'>
			<a href='#close' title='Close' class='close'>X</a>
			<h3 class='ventanaModal'>Editar Reserva</h3>
			<div class='aviso'>
				AVISO: Para una mejor comprobacion de las funciones de la aplicacion, se permite reservar un recurso en una franja horaria anterior a la hora actual.
			</div>
			<div class='formularios'>
				<div id="erroresFormAnyadirReserva" class="divErroresForms"></div>
				<form name="formValidar3" action='index.php?mostrar=reservas&accionRes=editar&idReservaEditar=<?php echo $idRes ?>' method='POST'>
					<label>Fecha inicio:</label>
					<input id="fechaIni" class="formValidar3" type='date' name='fechaInicio_reserva' min="<?php echo $fechaActual2; ?>" value= "<?php echo $soloFechaInicio ?>">
					<br style='clear: both;'><br>
					<label>Fecha fin:</label>
					<input id="fechaFin" class="formValidar3" type='date' name='fechaFin_reserva' min="<?php echo $fechaActual2; ?>" value= "<?php echo $soloFechaFin ?>">
					<br style='clear: both;'><br>
					<label>Hora inicio:</label>
					<select id="horaIni" class="anyadir" name="horaInicio_reserva">
						<?php
							for ($i=8; $i <= 21; $i++) { 
								if ($i < 10) {
									$hora1 = "0".$i.":00";
									$valor1 = "0".$i.":00:00";
								} else {
									$hora1 = $i.":00";
									$valor1 = $i.":00:00";
								}
								
								if ($soloHoraInicio == $valor1) {
									echo "<option value='$valor1' selected>$hora1</option>";
								} else {
									echo "<option value='$valor1'>$hora1</option>";
								}
								
							}
						?>
					</select>
					<!-- <input class="formValidar3" type='number' name='horaInicio_reserva' min="0" max="23" placeholder='Hora inicio'> -->
					<br style='clear: both;'><br>
					<label>Hora fin:</label>
					<select id="horaFin" class="anyadir" name="horaFin_reserva">
						<?php
							for ($i=8; $i <= 21; $i++) { 
								if ($i < 10) {
									$hora2 = "0".$i.":00";
									$valor2 = "0".$i.":00:00";
								} else {
									$hora2 = $i.":00";
									$valor2 = $i.":00:00";
								}
								
								if ($soloHoraFin == $valor2) {
									echo "<option value='$valor2' selected>$hora2</option>";
								} else {
									echo "<option value='$valor2'>$hora2</option>";
								}

							}
						?>
					</select>
					<!-- <input class="formValidar3" type='number' name='horaFin_reserva' min="0" max="23" placeholder='Hora fin'> -->
					<br style='clear: both;'><br>
					<label>Descripción reserva:</label>
					<textarea class="formValidar3" rows='10' cols='70' name='descripcionReserva' placeholder='Explica brevemente tu reserva'><?php echo $arrayRec['descripcion_reserva']; ?></textarea>
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
	<?php
		if (isset($_REQUEST['idReservaEditar'])) {
			$idRes = $_REQUEST['idReservaEditar'];
			$queryScarId = "SELECT * FROM tbl_reserva INNER JOIN `tbl_recurso` ON `tbl_reserva`.`id_recurso` = `tbl_recurso`.`id_recurso` WHERE `tbl_reserva`.`id_reserva` = '$idRes'";
			$consultaScarId=mysqli_query($link, $queryScarId);
			$arrayScarId = mysqli_fetch_array($consultaScarId);
			$idRecurso = $arrayScarId['id_recurso'];
		}
	?>
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
				<a href="#mostrarEditarReserva">Volver</a>
			</div>
		</div>
	</div>
</article>