<?php
session_start();

if(isset($_SESSION['estaLogueado'])){
	if(!($_SESSION['estaLogueado'])){
		header("Location: ../loginProfesor.php"); # Debe estar logueado
	}
} else {
	header("Location: ../loginProfesor.php"); # Debe estar logueado
}

	include '../inc/header.php';
	include '../inc/banner.php';
	include '../inc/navbarprofesores.php';
	include '../bd/database.php';

	$buscarjurado = "select jurado.idjurado, idpresidente as '1',idsecretario as '2',idvocal as '3',idsuplente as '4', fecha
		from jurado
		inner join actoprotocolario on jurado.idjurado = actoprotocolario.idjurado
		inner join titulacion on actoprotocolario.idtitulacion = titulacion.idtitulacion
		where titulacion.status = \"p\"
    	and (idpresidente = \"".$_SESSION['userId']."\"
    	or idsecretario = \"".$_SESSION['userId']."\"
    	or idvocal = \"".$_SESSION['userId']."\"
    	or idsuplente = \"".$_SESSION['userId']."\")
		order by idjurado;";

	$tabla = '<table id="miTabla" class="table">
					<thead class="tablaheader">
						<tr>
							<th>
								Presidente
							</th>
							<th>
								Secretario
							</th>
							<th>
								Vocal
							</th>
							<th>
								Suplente
							</th>
							<th>
								Fecha Evento
							</th>
						</tr>
					</thead>
				<tbody>
			<tr>';

	if (!$resultado = $db->query($buscarjurado)) {
		echo "Error. Fallo al intentar buscar al jurado";
	} else {
		if ($resultado->num_rows == 0) {
			$tabla .= '<tr><td>No se cuenta con un jurado disponible para visualizar.</td></tr>';
		} else {
			while ($consulta = mysqli_fetch_array($resultado)) {
				for ($i=1; $i < 5; $i++) { 
					$setnombre = "select nombre
								from maestro
								where rfc =\"".$consulta["$i"]."\"";
					if (!$resultado2 = $db->query($setnombre)) {
						echo "Error. Fallo al localizar los nombres del personal";
					} else {
						$consulta2 = mysqli_fetch_array($resultado2);
						$nombre = $consulta2['nombre'];
						$tabla .= '<td>'.$nombre.'</td>';
					}
				}
				$tabla .= '<td>'.$consulta['fecha'].'</td></tr>';
			}
		}
	}
	$tabla .= '</tr></tbody></table>';

?>
<br>
	<div class="container">
		<div class="row">
			<div class="col">
				<?php
					echo $tabla;
				?>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<center>
					<a href="index.php"><h2>Regresar</h2></a>
				</center>
			</div>
		</div>
	</div>
<?php
	include '../inc/footer.php';
?>