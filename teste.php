<?php 
	require "server/conn.php";
	$fechainicial = strtotime("-7 day"); // Siete días antes
	$fechainicial = date("d/m/Y", $fechainicial);
	$fechafinal = date("d/m/Y");

	if($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST['ok'])){
			$fechainicial = $_POST['fechainicial'];
			$fechafinal = $_POST['fechafinal'];
		}
	}

	$mySQLinicio = substr($fechainicial, 6,4)."-".substr($fechainicial, 3,2)."-".substr($fechainicial, 0,2);
	$mySQLfinal = substr($fechafinal, 6,4)."-".substr($fechafinal, 3,2)."-".substr($fechafinal, 0,2);
	//SQL
	$sql = "SELECT * FROM Datos WHERE (FechaLectura BETWEEN '$mySQLinicio' AND '$mySQLfinal') ORDER BY FechaLectura";
	$connection = conn();
	$query = $connection->prepare($sql);
	$result = $query->execute();
	$result = "";

	//variaveis para impressão
	$intervalo = "";
	$MQ135 = "";
	$MQ9 = "";
	$MQ2 = "";
	$MQ5 = "";

	//Variaveis para Calculo
	$sumMQ135 = 0; //somatorio de MQ135
	$sumMQ9 = 0; //somatorio de MQ9
	$sumMQ2 = 0; //somatorio de MQ2
	$sumMQ5 = 0; //somatorio de MQ5
	if ($query->rowCount() > 0) {
		$result = $query->fetchAll();
		$first = 1;
		$counter = 1; //empeza en 1 para quedar más fácil la división y conteo de lineas
		$max = 5; //3600
		$fecha = ""; //para controlar por fecha 
		$hora = 1; //para imprimir hora
		foreach ($result as $lectura) {
			$fechaMySQL = "";
			$fechaMySQL = substr($lectura['FechaLectura'], 8,2)."/".substr($lectura['FechaLectura'], 5,2)."/".substr($lectura['FechaLectura'], 0,4); //hace la transformacion de yyyy-mm-dd para dd/mm/yyyy
			if($first == 1) {
				$first = 0; // solo para que la primer fecha entre sin pasar por el "diferente de la actual"
				$fecha = $fechaMySQL;
			}

			if ($fecha <> $fechaMySQL) {
				$fecha = $fechaMySQL;

				//Se não for o primeiro intervalo, colocar virgula para separar valores
				if ($intervalo != "") {
					$intervalo = $intervalo.", ";
					$MQ135 = $MQ135.", ";
					$MQ9 = $MQ9.", ";
					$MQ2 = $MQ2.", ";
					$MQ5 = $MQ5.", ";
				}
				//impressão dos dados
				$intervalo = $intervalo."0".$hora.":00 ".$fecha;
				$MQ135 = $MQ135.($sumMQ135/($counter-1));
				$MQ9 = $MQ9.($sumMQ9/($counter-1));
				$MQ2 = $MQ2.($sumMQ2/($counter-1));
				$MQ5 = $MQ5.($sumMQ5/($counter-1));

				echo "Média por DATA: 0".$hora.":00(".$sumMQ135.")/(".($counter-1).") = ".$sumMQ135/($counter-1)."<br>"; //fecha ciclo anterior se esse não chegou no contador ainda
				$counter = 1;
				$hora = 1;
				$sumMQ135 = 0;
				$sumMQ9 = 0;
				$sumMQ2 = 0;
				$sumMQ5 = 0;
			}

			$sumMQ135 = $sumMQ135+$lectura['MQ135'];
			$sumMQ9 = $sumMQ9+$lectura['MQ9'];
			$sumMQ2 = $sumMQ9+$lectura['MQ2'];
			$sumMQ5 = $sumMQ9+$lectura['MQ5'];
			
			if ($counter > $max) {
				//Se não for o primeiro intervalo, colocar virgula para separar valores
				if ($intervalo != "") {
					$intervalo = $intervalo.", ";
					$MQ135 = $MQ135.", ";
					$MQ9 = $MQ9.", ";
					$MQ2 = $MQ2.", ";
					$MQ5 = $MQ5.", ";
				}
				//impressão dos dados
				$intervalo = $intervalo."0".$hora.":00 ".$fecha;
				$MQ135 = $MQ135.(($sumMQ135-$lectura['MQ135'])/($counter-1));
				$MQ9 = $MQ9.(($sumMQ9-$lectura['MQ9'])/($counter-1));
				$MQ2 = $MQ2.(($sumMQ2-$lectura['MQ2'])/($counter-1));
				$MQ5 = $MQ5.(($sumMQ5-$lectura['MQ5'])/($counter-1));
				echo "Média por máxima: 0".$hora.":00(".($sumMQ135-$lectura['MQ135']).")/(".($counter-1).") = ".($sumMQ135-$lectura['MQ135'])/($counter-1)."<br>"; //fecha ciclo anterior se esse não chegou no contador ainda
				$counter = 1;
				$hora++;
				$sumMQ135 = $lectura['MQ135'];
				$sumMQ9 = $lectura['MQ9'];
				$sumMQ2 = $lectura['MQ2'];
				$sumMQ5 = $lectura['MQ5'];
			} else {
				echo $fecha." -> ".$lectura['MQ135']." - ".$counter." ---- ".$sumMQ135."<br>";
				$counter++; //conta mais 1
			}
		}

		if($counter <= $max) {
			//Se não for o primeiro intervalo, colocar virgula para separar valores
			if ($intervalo != "") {
				$intervalo = $intervalo.", ";
				$MQ135 = $MQ135.", ";
				$MQ9 = $MQ9.", ";
				$MQ2 = $MQ2.", ";
				$MQ5 = $MQ5.", ";
			}
			//impressão dos dados
			$intervalo = $intervalo."0".$hora.":00 ".$fecha;
			$MQ135 = $MQ135.($sumMQ135/($counter-1));
			$MQ9 = $MQ9.($sumMQ9/($counter-1));
			$MQ2 = $MQ2.($sumMQ2/($counter-1));
			$MQ5 = $MQ5.($sumMQ5/($counter-1));
			echo "Média por máxima: 0".$hora.":00(".$sumMQ135.")/(".($counter-1).") = ".($sumMQ135)/($counter-1)."<br>"; //fecha ciclo anterior se esse não chegou no contador ainda
		}
	}

?>
