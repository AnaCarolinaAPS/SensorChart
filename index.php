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
	$fechainicial = "";
	$fechafinal = "";
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
					<div class="col-md-4">
					</div>
					<div class="col-sm-4 col-md-2">
						<div class="form-group">
							<label for="fechainicial">Fecha Inicial</label>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input class="form-control" id="fechafinal" name="fechafinal" placeholder="DD/MM/YYYY" type="text" required />
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
								<input class="form-control" id="fechafinal" name="fechafinal" placeholder="DD/MM/YYYY" type="text" required/>
							</div>
						</div>
					</div>
					<div class="col-sm-2 col-md-1">
						<button type="submit" class="btn btn-primary" style="margin-top: 24px;">OK</button>
					</div>
					<div class="col-md-3">
					</div>
				</div> <!-- box-header  -->
				<!-- Corpo de Caja -->
				<div class="box-body text-center" >
					<div class="col-md-2">
					</div>
					<div class="col-md-8">
						<canvas id="myChart"></canvas>
					</div>
					<div class="col-md-2">
					</div>
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
	var MONTHS = ['01/10/2019', '02/10/2019', '03/10/2019', '04/10/2019', '05/10/2019', '06/10/2019', '07/10/2019', '08/10/2019', '09/10/2019', '10/10/2019', '11/10/2019', '12/10/2019'];
	var config = {
		type: 'line',
		data: {
			labels: ['01/10/2019', '02/10/2019', '03/10/2019', '04/10/2019', '05/10/2019', '06/10/2019', '07/10/2019'],
			datasets: [{
				label: 'MQ135',
				backgroundColor: window.chartColors.red,
				borderColor: window.chartColors.red,
				data: [
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor()
				],
				fill: false,
			}, {
				label: 'MQ9',
				fill: false,
				backgroundColor: window.chartColors.blue,
				borderColor: window.chartColors.blue,
				data: [
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor()
				],
			}, {
				label: 'MQ2',
				fill: false,
				backgroundColor: window.chartColors.orange,
				borderColor: window.chartColors.orange,
				data: [
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor()
				],
			} , {
				label: 'MQ5',
				fill: false,
				backgroundColor: window.chartColors.purple,
				borderColor: window.chartColors.purple,
				data: [
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor()
				],
			}]
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'Chart.js Line Chart'
			},
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
						labelString: 'Month'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Value'
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