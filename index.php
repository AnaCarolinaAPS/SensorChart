<!DOCTYPE html>
<html>
<head>
	<title>Sensor Chart</title>
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
	$sql = "SELECT * FROM tb_sensor WHERE (fechalectura BETWEEN '$mySQLinicio' AND '$mySQLfinal') ORDER BY fechalectura";
	$connection = conn();
	$query = $connection->prepare($sql);
	$result = $query->execute();
	$result = "";

	$intervalo = "";
	$MQ135 = "";
	$MQ9 = "";
	$MQ2 = "";
	$MQ5 = "";
	if ($query->rowCount() > 0) {
		$result = $query->fetchAll();
		foreach ($result as $lectura) {
			if ($intervalo != "") {
				$intervalo = $intervalo.", ";
				$MQ135 = $MQ135.", ";
				$MQ9 = $MQ9.", ";
				$MQ2 = $MQ2.", ";
				$MQ5 = $MQ5.", ";
			}
			$fechaMySQL = "";
			$fechaMySQL = substr($lectura['fechalectura'], 8,2)."/".substr($lectura['fechalectura'], 5,2)."/".substr($lectura['fechalectura'], 0,4); //hace la transformacion de yyyy-mm-dd para dd/mm/yyyy
			$intervalo = $intervalo.$fechaMySQL;
			$MQ135 = $MQ135.$lectura['mq135'];
			$MQ9 = $MQ9.$lectura['mq9'];
			$MQ2 = $MQ2.$lectura['mq2'];
			$MQ5 = $MQ5.$lectura['mq5'];
		}
	}

	// var_dump($intervalo);
	// var_dump($MQ135);
	// var_dump($MQ9);
	// var_dump($MQ2);
	// var_dump($MQ5);

	//Datos Fijos para Teste
	// $intervalo = '01/10/2019, 10/10/2019, 20/10/2019';
	// $MQ135 = '2, 1, 2';
	// $MQ9 = '1, 0, 1';
	// $MQ2 = '1, 2, 3';
	// $MQ5 = '0, 1, 0';
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
							<button type="submit" class="btn btn-primary" style="margin-top: 24px;" name="ok" id="ok">OK</button>
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
				label: 'MQ135',
				backgroundColor: window.chartColors.red,
				borderColor: window.chartColors.red,
				data: aMq135,
				fill: false,
			}, {
				label: 'MQ9',
				fill: false,
				backgroundColor: window.chartColors.blue,
				borderColor: window.chartColors.blue,
				data: aMq9,
			}, {
				label: 'MQ2',
				fill: false,
				backgroundColor: window.chartColors.orange,
				borderColor: window.chartColors.orange,
				data: aMq2,
			} , {
				label: 'MQ5',
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
						labelString: 'Lectura'
					}
				}]
			}
		}
	};

	window.onload = function() {
		var ctx = document.getElementById('myChart').getContext('2d');
		window.myLine = new Chart(ctx, config);
	};
</script>
</body>
</html>