<article>
	<?php
		// boton volver ----------------------------------------
		echo "<a href='index.php'><input type='button' value='Volver'></a>";

		// recuperar --------------------------------------------------
		if (isset($_REQUEST['idRecursoRecuperar'])) {
			$idRec = $_REQUEST['idRecursoRecuperar'];
			
			$query="UPDATE `tbl_recurso` SET `estadoEliminado_recurso` = 'no' WHERE `tbl_recurso`.`id_recurso` = '$idRec';";
			mysqli_query($link,$query);
			header('Location: index.php?mostrar=recuperar');
			
		}

		// Mostrar --------------------------------------------------
		$consulta=mysqli_query($link, "SELECT * FROM tbl_recurso WHERE `estadoEliminado_recurso` = 'si' ORDER BY id_recurso");
		
			
		echo "<div class='tabla tablaRecursos'>";
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
							echo "<div class='columna columnaRecursos'>";
								echo "<a href='index.php?mostrar=recuperar&idRecursoRecuperar=$idRecurso'>";
									echo "<input type='button' value='Recuperar'>";
								echo "</a>";
								// boton editar.......................
								echo "<a href='index.php?idRecurso=$idRecurso#mostrarEditarRecurso'>";
									echo "<i class='fas fa-pencil-alt'></i>";
								echo "</a>";
							echo "</div>";
						echo "</div>";
					} else {
						echo "<div class='fila filaRecursos'>";
							echo "<div class='columna columnaRecursos desabilitado'><img class='imgRecursos' src='$imagen'></div>";
							echo "<div class='columna columnaRecursos desabilitado'>$nombre</div>";
							echo "<div class='columna columnaRecursos'>";
								echo "<input class='desabilitado' type='button' value='Recuperar'>";
								// boton editar.......................
								echo "<a href='index.php?idRecurso=$idRecurso#mostrarEditarRecurso'>";
										echo "<i class='fas fa-pencil-alt'></i>";
								echo "</a>";
							echo "</div>";
						echo "</div>";
					}
				}
			} else {
				echo "No hay recursos que recuperar.";
			}
		echo "</div>";
	?>
</article>