<!DOCTYPE HTML>

<html lang="en">
	<head>		
		<title>The Fitness Tracker</title>
		<link rel="stylesheet" type="text/css" href="styles.css">
		<script src="js_scripts/jquery-1.10.2.min.js"></script>
		<script src="js_scripts/fullscreen.js"></script>
		<script src="js_scripts/newWindow.js"></script>
		<?php include_once("php_scripts/include.php"); ?>
	</head>

	<body class=cycling>
	
		<?php 
			include_once('php_scripts/draw_menus.php'); 
			drawMenu("top");
		?>
	
		<header class='head-bar'>
			<div style='position:absolute;'>
				<h1>Cycling</h1>
			</div>
			<div style='position:absolute; right:0px; padding-top:10px;'>
				<ul>
					<li><a href="CyclingAllRides.php" id='selected'>All Rides</a></li>
					<li><a href="CyclingData.php">Data</a></li>
					<li><a href="CyclingStats.php">Stats</a></li>
					<li><a href="CyclingWeek.php">Week</a></li>
					<li><a href="CyclingGraphs.php">Graphs</a></li>
					<li><a href='javascript:void(0);' onClick='openNewWindow()'>New</a>
						<?php include_once('php_scripts/draw_new_ride_window.php'); ?>
					</li>
				</ul>
			</div>
		</header><!-- #head-title -->
		
		<nav class='head-bar'>
			<ul style='position:absolute; right:0px;'>
				<li><form METHOD='POST'>
					<label for='ordertype'>Order By:</label>
					<select name='ordertype' id='ordertype'>
						<?php
							$ordertypes = ['Date', 'Distance', 'Time'];
							if (ISSET($_POST['ordertype'])) {
								$ordertype = $_POST['ordertype'];
							}
							else {
								$ordertype = $ordertypes[0];
							}
							foreach ($ordertypes as $y) {
								echo "<option value='" . $y . "'";
								if ($y == $ordertype) {
									echo " selected='selected'";
								}
								echo ">" . $y . "</option>";
							}
							if ($ordertype == 'Time') {
								if ($ordertype == 'Descending') {
									$ordertype = 'Hours';
								}
								else {
									$ordertype = 'Hours';
								}
							}
						?>	
					</select>
					<select name='order' id='order'>
						<?php
							$orders = ['Descending', 'Ascending'];
							if (ISSET($_POST['order'])) {
								$order = $_POST['order'];
							}
							else {
								$order = $orders[0];
							}
							foreach ($orders as $z) {
								echo "<option value='" . $z . "'";
								if ($z == $order) {
									echo " selected='selected'";
								}
								echo ">" . $z . "</option>";
							}
							if ($order == 'Descending') {
								$order = 'DESC';
							}
							else {
								$order = '';
							}
						?>
					</select>
					<input type="submit" value="Go" title="Go" style="font-size:20px; border:none;">
				</form></li>
				<li>
					<button id='fullscreenbutton' onClick='fullscreen()'>full screen</button>
				</li>
			</ul>
		</nav>
		
		<div id="content-wrapper">		
			<div id="centered" class='data'>
				<h2>All Rides</h2>
				<table style="width:100%;" class='padded'>
				
					<tr class='header'>
						<td>Date</td>
						<td>Location</td>
						<td>Distance</td>
						<td>Time</td>
						<td>Ave Speed</td>
						<td>Bike</td>
					</tr>
				
					<?php
						$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time, bike_name FROM Rides ORDER BY " . $ordertype . " " . $order);

						while($ride = mysqli_fetch_array($rides)) {

							$date	 		= 	new DateTime($ride["date"]);
							$location		=	$ride["location"];
							$distance		= 	$ride["distance"];
							$hours 			= 	$ride["hours"];
							$minutes	 	= 	$ride["minutes"];
							$seconds	 	= 	$ride["seconds"];
							$bikeName		=	$ride["bike_name"];
							
							$date = $date->format('d/m/Y');
							$location = substr($location, 0, 100);
							$time = ($hours*3600) + ($minutes*60) + $seconds;
							$timeText = $hours . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT ) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT );
							$aveSpeed = number_format( $distance / ($time/3600), 2);
							
							echo "
								<tr'>
									<td>$date</td>
									<td>$location</td>
									<td>$distance</td>
									<td>$timeText</td>
									<td>$aveSpeed</td>
									<td>$bikeName</td>
								</tr>
							";
							
						}
					?>
				
				</table>
			</div><!-- #centered -->
		</div><!-- #content-wrapper -->
		
		<?php drawMenu("bottom"); ?>

	</body>
</html>