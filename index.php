<!DOCTYPE html>
<html>
<head>
	<title>Arduambiente</title>
	<?php include 'includes/head.php'; ?>
	<style>
	canvas{
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	</style>
</head>

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

	$mySQLinicio = substr($fechainicial, 6,4)."-".substr($fechainicial, 3,2)."-".substr($fechainicial, 0,2);;
	$mySQLfinal = substr($fechafinal, 6,4)."-".substr($fechafinal, 3,2)."-".substr($fechafinal, 0,2);;
	//SQL
	$sql = "SELECT * FROM Datos WHERE (FechaLectura BETWEEN '$mySQLinicio' AND '$mySQLfinal') ORDER BY FechaLectura LIMIT 15";
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
		$max = 5; //3600 máximo de linhas lidas para contar 1 hr
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
				$intervalo = $intervalo."0".$hora.":00\n".$fecha;
				$MQ135 = $MQ135.($sumMQ135/($counter-1));
				$MQ9 = $MQ9.($sumMQ9/($counter-1));
				$MQ2 = $MQ2.($sumMQ2/($counter-1));
				$MQ5 = $MQ5.($sumMQ5/($counter-1));

				//echo "Média por DATA: 0".$hora.":00(".$sumMQ135.")/(".($counter-1).") = ".$sumMQ135/($counter-1)."<br>"; //fecha ciclo anterior se esse não chegou no contador ainda
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
				$intervalo = $intervalo."0".$hora.":00\n".$fecha;
				$MQ135 = $MQ135.(($sumMQ135-$lectura['MQ135'])/($counter-1));
				$MQ9 = $MQ9.(($sumMQ9-$lectura['MQ9'])/($counter-1));
				$MQ2 = $MQ2.(($sumMQ2-$lectura['MQ2'])/($counter-1));
				$MQ5 = $MQ5.(($sumMQ5-$lectura['MQ5'])/($counter-1));
				//echo "Média por máxima: 0".$hora.":00(".($sumMQ135-$lectura['MQ135']).")/(".($counter-1).") = ".($sumMQ135-$lectura['MQ135'])/($counter-1)."<br>"; //fecha ciclo anterior se esse não chegou no contador ainda
				$counter = 1;
				$hora++;
				$sumMQ135 = $lectura['MQ135'];
				$sumMQ9 = $lectura['MQ9'];
				$sumMQ2 = $lectura['MQ2'];
				$sumMQ5 = $lectura['MQ5'];
			} else {
				//echo $fecha." -> ".$lectura['MQ135']." - ".$counter." ---- ".$sumMQ135."<br>";
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
			$intervalo = $intervalo."0".$hora.":00\n".$fecha;
			$MQ135 = $MQ135.($sumMQ135/($counter));
			$MQ9 = $MQ9.($sumMQ9/($counter));
			$MQ2 = $MQ2.($sumMQ2/($counter));
			$MQ5 = $MQ5.($sumMQ5/($counter));
			//echo "Média por máxima: 0".$hora.":00(".$sumMQ135.")/(".($counter-1).") = ".($sumMQ135-$lectura['MQ135'])/($counter-1)."<br>"; //fecha ciclo anterior se esse não chegou no contador ainda
		}
	}

?>
<body class="hold-transition login-page">
		<!-- Cabicera de Contenido (Título) -->
		<section class="content-header">
		</section>

		<!-- Contenido Principal -->
		<section class="content">
			<!-- Caja de Texto de color gris (Default) -->
			<div class="box" >
				<div class="box-header with-border">
					<form method="post">
						<div class="col-md-4">
						</div>
						<div class="col-sm-4 col-md-2">
							<div class="form-group">
								<label for="fechainicial">Fecha Inicial</label>
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input class="form-control" id="fechainicial" name="fechainicial" placeholder="DD/MM/YYYY" type="text" value="<?php echo $fechainicial;?>" required />
								</div>
							</div>
						</div>
						<div class="col-sm-4 col-md-2">
							<div class="form-group">
								<label for="fechafinal">Fecha Final</label>
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input class="form-control" id="fechafinal" name="fechafinal" placeholder="DD/MM/YYYY" type="text" value="<?php echo $fechafinal;?>" required/>
								</div>
							</div>
						</div>
						<div class="col-sm-2 col-md-1">
							<button type="submit" class="btn btn-primary" style="margin-top: 24px;" name="ok" id="ok">Buscar</button>
						</div>
						<div class="col-md-3">
						</div>
					</form>
				</div> <!-- box-header  -->
				<!-- Corpo de Caja -->
				<div class="box-body text-center" >
					<!-- <div class="col-md-2">
					</div>
					<div class="col-md-8"> -->
						<canvas id="myChart" style="width: 80%"></canvas>
					<!-- </div>
					<div class="col-md-2">
					</div> -->
					<input type="hidden" name="fechasg" id="fechasg" value="<?php echo $intervalo;?>">
					<input type="hidden" name="mq135" id="mq135" value="<?php echo $MQ135;?>">
					<input type="hidden" name="mq9" id="mq9" value="<?php echo $MQ9;?>">
					<input type="hidden" name="mq2" id="mq2" value="<?php echo $MQ2;?>">
					<input type="hidden" name="mq5" id="mq5" value="<?php echo $MQ5;?>">
				</div>
				<div class="box-footer">
				</div> <!-- /.box-footer-->
			</div> <!-- /.Caja de Texto de color gris (Default) -->
			
		</section><!-- /.content -->

	<!-- SCRIPTS (js) -->
	<?php include "includes/scripts.php"; ?>
	<!-- ./SCRIPTS (js) -->
<script>
	$(document).ready(function(){
		var fechai_input=$('input[name="fechainicial"]'); //our date input has the name "date"
		var fechaf_input=$('input[name="fechafinal"]'); //our date input has the name "date"
		var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
		fechai_input.datepicker({
			format: 'dd/mm/yyyy',
			container: container,
			todayHighlight: true,
			autoclose: true,
		})
		fechaf_input.datepicker({
			format: 'dd/mm/yyyy',
			container: container,
			todayHighlight: true,
			autoclose: true,
		})
	})
	var aFechas = document.getElementById("fechasg").value;
	var aMq135 = document.getElementById("mq135").value;
	var aMq9 = document.getElementById("mq9").value;
	var aMq2 = document.getElementById("mq2").value;
	var aMq5 = document.getElementById("mq5").value;

	aFechas = aFechas.split(",");
	aMq135 = aMq135.split(",");
	aMq9 = aMq9.split(",");
	aMq2 = aMq2.split(",");
	aMq5 = aMq5.split(",");

	// console.log(aFechas);

	var MONTHS = aFechas;
	var config = {
		type: 'line',
		data: {
			labels: aFechas,
			datasets: [{
				label: 'MQ135 - CO2',
				backgroundColor: window.chartColors.red,
				borderColor: window.chartColors.red,
				data: aMq135,
				fill: false,
			}, {
				label: 'MQ9 - Combustibles inflamables',
				fill: false,
				backgroundColor: window.chartColors.blue,
				borderColor: window.chartColors.blue,
				data: aMq9,
			}, {
				label: 'MQ2 - Propano',
				fill: false,
				backgroundColor: window.chartColors.orange,
				borderColor: window.chartColors.orange,
				data: aMq2,
			} , {
				label: 'MQ5 - Gas natural',
				fill: false,
				backgroundColor: window.chartColors.purple,
				borderColor: window.chartColors.purple,
				data: aMq5,
			}]
		},
		options: {
			responsive: true,
			// title: {
			// 	display: true,
			// 	text: 'Chart.js Line Chart'
			// },
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Tiempo/Fechas'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Lectura en mg'
					}
				}]
			}
		},
		plugins: [{
			beforeInit: function (chart) {
			chart.data.labels.forEach(function (e, i, a) {
				if (/\n/.test(e)) {
					a[i] = e.split(/\n/)
					}
				})
			}
		}]
	};

	window.onload = function() {
		var ctx = document.getElementById('myChart').getContext('2d');
		window.myLine = new Chart(ctx, config);
	};
</script>
</body>
</html>