<?php	
	session_start();
	if (isset($_SESSION['user_id'])) {
		$records = $conn->prepare('SELECT * FROM `tbl_usuario` INNER JOIN `tbl_grupoUsuario` ON `tbl_usuario`.`id_grupoUsuario` = `tbl_grupoUsuario`.`id_grupoUsuario` WHERE `id_usuario` = :id');
		$records->bindParam(':id', $_SESSION['user_id']);
		$records->execute();
		$results = $records->fetch(PDO::FETCH_ASSOC);
		$usuario = null;
		if (count($results) > 0) {
			$usuario = $results;
		}

		$idUsuario = $usuario['id_usuario'];
		$nombreUsuario = $usuario['usuario_usuario'];
		$nombreUsuario = $usuario['usuario_usuario'];
		$habilitadoUsuario = $usuario['habilitado_usuario'];
		$grupoUsuario = $usuario['nombre_grupoUsuario'];
		$idGrupo = $usuario['id_grupoUsuario'];
	}
?>