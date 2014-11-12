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
		<script>
			//towers = [];
		</script>
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
				<table id='towers' style="width:100%;">
					<tr>
						<td style="width:50%;">
			
				<?php
				
					$longest = 0;
				
					$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time FROM Rides ORDER BY " . $ordertype . " " . $order);
					
					while($ride = mysqli_fetch_array($rides)) {
						$distance		= 	$ride["distance"];
						if ($distance > $longest) {
							$longest = $distance;
						}
					}
					
					
					$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time FROM Rides ORDER BY " . $ordertype . " " . $order);
					
					echo "
					<h2>Overview</h2>
					<br>
					<table style='width:100%; min-height:" . $longest * 4 . "px;'><tr>";
					
					$total_distance = 0;
					$total_rides = 0;
					$total_time = 0;
					
					//$ridearray = [];
					//$i = 0;

					while($ride = mysqli_fetch_array($rides)) {

						$date	 		= 	$ride["date"];
						$distance		= 	$ride["distance"];
						$hours 			= 	$ride["hours"];
						$minutes	 	= 	$ride["minutes"];
						$seconds	 	= 	$ride["seconds"];
						
						//$ride = new Ride($date, $distance, $hours, $minutes, $seconds);
						//$ridearray[0] = $ride;
						//$i++;
						
						$total_distance += $distance;
						$total_rides++;
						$total_time += $hours + $minutes / 60 + $seconds / 3600;

						echo "
							<td>
								<div style='height:" . $distance * 4 . "px; background-color:#EE7777;'>
								</div>
							</td>
						";
					}
					
					echo "
						</tr></table>
						</td>
						<td style='width:50%;'>

						<h2>Overall Statistics</h2>
						<br>
						<table style='width:100%'><tr>
						
							<table class='padded'>
								<tr><td>Total Miles:</td>
									<td id='miles'>
										". number_format($total_distance, 0) . "
									</td>
								</tr>
								<tr><td>Total Rides:</td><td>" . $total_rides . "</td></tr>
								<tr><td>Average Distance:</td><td>" . number_format($total_distance / $total_rides, 1) . "</td></tr>
								<tr><td>Total Time:</td><td>" . number_format($total_time, 0) . "</td></tr>
								<tr><td>Average Time Out:</td><td>" . number_format($total_time / $total_rides, 1) . "</td></tr>
								<tr><td>Average Speed:</td><td>" . number_format($total_distance / $total_time, 1) . "</td></tr>
							</table>
						</tr></table>
						</td>
						</tr>
						</table>
						
						<br>
					";
					
					echo "
						<div id='centered2'>
						<h2>All Data</h2>
					";
					
					// WHERE bike_name = 'Apollo Paradox' - put this in query
					
					$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time, bike_name FROM Rides ORDER BY " . $ordertype . " " . $order);
					
					while($ride = mysqli_fetch_array($rides)) {

						$date	 		= 	new DateTime($ride["date"]);
						$location		= 	$ride["location"];
						$distance		= 	$ride["distance"];
						$hours 			= 	$ride["hours"];
						$minutes	 	= 	$ride["minutes"];
						$seconds	 	= 	$ride["seconds"];
						// $movies 		= 	mysqli_query($link, "SELECT movie_id, title FROM Movies WHERE movie_id IN (SELECT movie_id FROM MovieActor WHERE actor_id IN (SELECT Actor_id FROM Actors WHERE actor_id = '" . $actor_id . "'))");
						
						$timeText = $hours . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT ) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT );
						
						/*
						if(isset($_POST[$actor_id])){
							$score++;
							mysqli_query($link, "UPDATE Actors SET score= $score WHERE actor_id='$actor_id'");
						}
						*/
						
						echo "
							<div id='oneRide' style='position:relative; height:90px;'>
							
								<div id='oneRide' style='position:absolute; overflow:hidden; background-color:#EE7777; z-index:+1; height:100%; width:" . $distance / 1.00 . "%;'>
								</div>
						
								<div style='position:absolute; z-index:+2;'>
									<table><tr>
										<td style='padding:10px; vertical-align:middle; width:100%;'>
											<h3><a href='CyclingRide.php?ride=" . $date->format('Y-m-d') . "'>" . $date->format('d/m/Y') . "</a></h3>
											<p style='padding-top:5px; padding-bottom:2px;'>$location</p>
											<p style='padding-top:5px; padding-bottom:2px;'>" . $timeText . "</p>
										</td>
										<td id='score'>" . 
											number_format(round($distance, 1), 1) . "
										</td>
									</tr></table>
								</div>
							</div>
							<br>
							
							<script>
								//function changeMiles(miles) {
									//setTimeout(function() {
										//document.getElementById('miles').innerHTML = miles;
									//},
									//100
									//);
								//}
								//var i=0;
								
								/*
								$(window).ready(requestAnimationFrame(doThis(0)));
								
								function doThis(x) {
									if (x < " . $total_distance . ") {
										//x++;
										setTimeout(function() {
											document.getElementById('miles').innerHTML = x;
											document.getElementById('miles').style.display = 'none';
											document.getElementById('miles').style.display = 'block';
											x=x+100;
											doThis(x);
										},
										1
										);
										//doThis(x);
									}
								}
								*/
							</script>
						";
					}
				?>
				</div>
			</div><!-- #centered -->
		</div>
		
		<?php drawMenu("bottom"); ?>
		
		<script>
		/*
			$.each($('.tower'), function(index, elem) {
				$(elem).css('height', '0px');
			});
			*/
		</script>
		
		<script>
			/*
			$.each($('.tower'), function(index, elem) {
				$(elem).css('height', (towers[(index)]) + 'px');
				$(elem).css('animation', 'increaseHeight');
				//$(elem).css('animation-duration', 1 + 's');
				$(elem).css('animation-duration', (index)*0.05 + 's');
			});
			*/
		</script>

	</body>
</html>