<!DOCTYPE HTML>

<html lang="en">
	<head>		
		<title>The Fitness Tracker</title>
		<link rel="stylesheet" type="text/css" href="styles.css">
		<script src="js_scripts/jquery-1.10.2.min.js"></script>
		<script src="js_scripts/fullscreen.js"></script>
		<script src="js_scripts/newWindow.js"></script>
		<?php
			include_once("php_scripts/include.php");
		?>
	</head>

	<body class=cycling>
	
		<?php 
			include_once('php_scripts/draw_menus.php'); 
			drawMenu("top");
		?>
	
		<div id='sport-bar' class='head-bar'>
			<div style='position:absolute;'>
				<h1>Cycling</h1>
			</div>
			<div style='position:absolute; right:0px; padding-top:10px;'>
				<ul>
					<li><a href="CyclingAllRides.php">All Rides</a></li>
					<li><a href="CyclingData.php" id='selected'>Data</a></li>
					<li><a href="CyclingStats.php">Stats</a></li>
					<li><a href="CyclingWeek.php">Week</a></li>
					<li><a href="CyclingGraphs.php">Graphs</a></li>
					<li><a href='javascript:void(0);' onClick='openNewWindow()'>New</a>
						<?php include_once('php_scripts/draw_new_ride_window.php'); ?>
					</li>
				</ul>
			</div>
		</div><!-- #head-title -->
		
		<div class='head-bar'>
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
		</div>
		
		<div id="content-wrapper">		
			<div id="centered">
				<?php

					
					$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time, bike_name FROM Rides WHERE date BETWEEN '" . $_GET['ride'] . "' AND '" . $_GET['ride'] . " 23:59:59'" );
					
					$ride = mysqli_fetch_array($rides);
					
					$date	 		= 	new DateTime($ride["date"]);
					$location		= 	$ride["location"];
					$distance		= 	$ride["distance"];
					$hours 			= 	$ride["hours"];
					$minutes	 	= 	$ride["minutes"];
					$seconds	 	= 	$ride["seconds"];
					
					$date = $date->format('l jS F Y');
					$time = ($hours*3600) + ($minutes*60) + $seconds;
					$timeText = $hours . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT ) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT );
					$aveSpeed = number_format( $distance / ($time/3600), 2);
					
					echo "
						<h2>" . $date . "</h2>
						<table>
							<tr>
								<td>
									<h3>About this ride</h3>
									<p>Distance: " . $distance . "</p>
									<p>Time: " . $timeText . "</p>
									<p>Ave speed: " . $aveSpeed . "</p>
								</td>
							</tr>
						</table>
					";
					
				?>
			</div><!-- #centered -->
		</div>
		
		<?php drawMenu("bottom"); ?>

	</body>
</html>