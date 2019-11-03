<?php
	require 'server/conn.php';

	for ($i=0; $i <= 8; $i++) {
		$MQ135 = rand(0,5);
		$MQ9 = rand(0,5);
		$MQ2 = rand(0,5);
		$MQ5 = rand(0,5);
		$FechaLectura =  "2019-11-02";

		$sql = "INSERT INTO Datos (Tiempo, MQ135, MQ9, MQ2, MQ5, FechaLectura, FechaCarga)
		VALUES ('$i', '$MQ135', '$MQ9', '$MQ2', '$MQ5', '$FechaLectura', NOW())";
		$connection = conn();
		$query = $connection->prepare($sql);
		$result = $query->execute();

		echo $FechaLectura." - Tiempo: ".$i."<br>";
	}
?>