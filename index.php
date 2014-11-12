<!DOCTYPE HTML>

<html lang="en">
	<head>
		<!-- In include.php -->
		<!--<link rel="shortcut icon" href="icons/logo.png" />-->
		<title>The Fitness Tracker</title>
		<link rel="stylesheet" type="text/css" href="styles.css">
		<script src="js_scripts/jquery-1.10.2.min.js"></script>
		<script src="js_scripts/fullscreen.js"></script>
		<script src="js_scripts/newWindow.js"></script>
		<?php
			include_once("php_scripts/include.php");
		?>
	</head>

	<body class=summary>
		<?php 
			include_once('php_scripts/draw_menus.php'); 
			drawMenu("top");
		?>
	
		<!--<img style='position:absolute; z-index:+2; width:100%;' src='icons/logoLarge.png' />-->
		
		<div id='sport-bar' class='head-bar'>
			<div style='position:absolute;'>
				<h1>Summary</h1>
			</div>
			<div style='position:absolute; right:0px; padding-top:10px;'>
				<ul>
					<li><a href="index.php" style='font-weight:bold'>Summary</a></li>
					<li><a href="summaryCalendar.php">Calendar</a></li>
					<li><a href="CyclingStats.php">Stats</a></li>
					<li><a href="CyclingGraphs.php">Graphs</a></li>
				</ul>
			</div>
		</div><!-- #head-title -->
		
		<div class='head-bar'>
			<ul style='position:absolute; right:0px;'>
				<li>
					<button id='fullscreenbutton' onClick='fullscreen()'>full screen</button>
				</li>
			</ul>
		</div>
		
		<div id="content-wrapper">		
			<div id="centered">
				<h2>Targets</h2>
				<?php
				
					class Target
					{
						private $values = [];
						
						private $name = null;
						
						public function __construct($_name, array $_values)
						{
							$this->name = $_name;
							$this->values = $_values;
						}
						
						public function getName()
						{
							return $this->name;
						}
						
						public function getValues() 
						{
							return $this->values;
						}
						
						public function addValue($_value) 
						{
							array_push($this->values, $_value);
						}
					}
					
					//$aTarget = new Target('cyclingTotalMiles', [1000, 1200, 1500]);
					
					//echo $aTarget->getValues()[0];
					
					//$aTarget->addValue(3000);
					/*
					echo "
						<table style='width:100%'>
							<tr>
					";

					$targets = [];
					
					array_push($targets, new Target('cyclingTotalMiles', [1000, 1200, 1500]));
					array_push($targets, new Target('cyclingTotalTime', [50, 60, 80]));
					
					foreach ($targets as $currentTarget)
					{
						echo "
							<td style='vertical-align:middle;'>" . $currentTarget->getName() . "</td>
						";
						
						echo "<td><table>";
						
						foreach($currentTarget->getValues() as $number)
						{
							echo "<tr>";
							echo $number;
							echo "<br>";
							echo "</tr>";
						}
						echo "</td></table>";
					}
					
					echo "</tr></table>";
					*/
					
					echo "<table class=padded>";
					
					$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time FROM Rides ORDER BY date");
					
					$total_distance = 0;
					$total_rides = 0;
					$total_time = 0;
					$longest = 0;
					
					$distance2014 = 0;
					
					while($ride = mysqli_fetch_array($rides)) {
						
						$date	 		= 	new DateTime($ride["date"]);
						$distance		= 	$ride["distance"];
						$hours 			= 	$ride["hours"];
						$minutes	 	= 	$ride["minutes"];
						$seconds	 	= 	$ride["seconds"];
						
						if($date->format('Y') == 2014) {
							$distance2014 += $distance;
						}

					}
					
					echo "
						<tr>
							<td>" . $distance2014 . "
							<td>Total 1000mi</td>
							<td>";
					if($distance2014 >= 1000) {
						echo "&nbsp&nbsp<img src='images/tick.png' style='height:15px;' />";
					}
					echo "
							</td>
						</tr>";
						
					echo "
						<tr>
							<td>" . $distance2014 . "
							<td>Total 1200mi</td>
							<td>";
					if($distance2014 >= 1200) {
						echo "&nbsp&nbsp<img src='images/tick.png' style='height:15px;' />";
					}
					echo "
							</td>
						</tr>";
						
					echo "
						<tr>
							<td>" . $distance2014 . "
							<td>Total 1500mi</td>
							<td>";
					if($distance2014 >= 1500) {
						echo "&nbsp&nbsp<img src='images/tick.png' style='height:15px;' />";
					}
					echo "
							</td>
						</tr>";
						
					echo "</table>"

				?>
				
			</div><!-- #centered -->
		</div>
		
		<?php drawMenu("bottom"); ?>

	</body>
</html>