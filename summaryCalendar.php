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
					<?php
						$now = new DateTime(date('Y-m-d H:i:s'));
						echo $now->format('M y');
					?>
				</li>
				<li>
					<button id='fullscreenbutton' onClick='fullscreen()'>full screen</button>
				</li>
			</ul>
		</div>
		
		<div id="content-wrapper">
			<div id="centered">
				<!--<h2>Calendar</h2>-->
				<table id='calendar' style='width:100%;'>
					<thead>
						<td>Monday</td>
						<td>Tuesday</td>
						<td>Wednesday</td>
						<td>Thursday</td>
						<td>Friday</td>
						<td>Saturday</td>
						<td>Sunday</td>
					</thead>
				
				
				<?php
				
					$now = new DateTime(date('Y-m-d H:i:s'));
					
					//$now = new DateTime(date($now->format('Y-') . 7 . $now->format('-d H:i:s')));
					
					$monthStart = new DateTime(date($now->format('Y-m-') . 1));
					$monthEnd = new DateTime(date( $now->format('Y-') . ($now->format('m') + 1) . "-" . 0));
					
					$daysInMonth = $monthEnd->format('d') + 1 - $monthStart->format('d');
					
					$days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
					
					$daysBefore = array_search($monthStart->format('D'), $days);
					
					$calStart = new DateTime("-" . $daysBefore . " day" . ($monthStart->format('Y-m-d')));
					
					$counter = 0;
					$day = $calStart->format('d');
					
					$ride = null;
					
					$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time FROM Rides ORDER BY date");
					
					function findRide($rideDate) {
						global $ride, $rides, $daysInMonth;
						
						// Check the current selected ride (it may be the one we are looking for)
						
						if ($ride) {
							
							$date	 		= 	$ride["date"];
							$distance		= 	$ride["distance"];
							$hours 			= 	$ride["hours"];
							$minutes	 	= 	$ride["minutes"];
							$seconds	 	= 	$ride["seconds"];
							
							// If the date of the ride is still beyond the date we're looking for, return nothing
							if ($date > (date($rideDate->format('Y-m-') .  ($rideDate->format('d')) . " 23:59:59"))) {
								return "";
							}
							
							$dateFrom = new DateTime("-1 day " . $rideDate->format('Y-m-d') . " 23:59:59");
							$dateTo = new DateTime("+1 day " . $rideDate->format('Y-m-d') . " 0:0:0"); 
							
							$date = new DateTime(date($date . " 00:00:00"));
							
							if ($date->getTimestamp() > $dateFrom->getTimestamp() && $date->getTimestamp() < $dateTo->getTimestamp()) {
								return number_format($distance, 0) . "mi";
							}
						}
						
						while($ride = mysqli_fetch_array($rides)) {
							
							$date	 		= 	$ride["date"];
							$distance		= 	$ride["distance"];
							$hours 			= 	$ride["hours"];
							$minutes	 	= 	$ride["minutes"];
							$seconds	 	= 	$ride["seconds"];

							$dateFrom = date($rideDate->format('Y-m-') . ($rideDate->format('d') - 1) . " 23:59:99");
							$dateTo = date($rideDate->format('Y-m-') .  ($rideDate->format('d') + 1) . " 0:0:0");
							
							// If we have gone past the date we're looking for, STOP
							if ($date >= (date($rideDate->format('Y-m-') .  ($rideDate->format('d')) . " 23:59:99"))) {
								return "";
							}
							
							if ($date > $dateFrom && $date < $dateTo) {
								return number_format($distance, 0) . "mi";
							}
						}
					}
					
					for ($i = 0; $i < 6; $i++) {
						echo "<tr>";
							for ($j = 0; $j < 7; $j++) {
								echo "<td class='calCell'";
									if ($counter < $daysBefore || $counter >= $daysInMonth + $daysBefore) {
										echo " id='notThisMonth'";
									}
									else {
										echo " id='thisMonth'";
									}
									echo ">";
									if ($counter == $daysBefore || $counter == $daysInMonth + $daysBefore) {
										$day = 1;
									}
									echo "<span id='day'>$day</span>";
									$cellDate = new DateTime("+" . $counter . " day" . ($calStart->format('Y-m-d')));
									$cellRide = findRide($cellDate);
									if ($cellRide != "") {
										echo "
											<table class='cycling'><tr>
												<td id='type'>
													Cycling
												</td>
												<td id='points'>" .
													$cellRide . "
												</td>
											</tr></table>";
									}
									$counter++;
									$day++;
								echo "</td>";
							}
						echo "</tr>";
					}
					
					echo "
						</table>
					";
					
				?>
				
			</div><!-- #centered -->
		</div>
		
		<?php drawMenu("bottom"); ?>

	</body>
</html>