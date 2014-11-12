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
					<li><a href="CyclingData.php">Data</a></li>
					<li><a href="CyclingStats.php" style='font-weight:bold'>Stats</a></li>
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
					<label for='totaltype'>Total By:</label>
					<select name='totaltype' id='totaltype'>
						<?php
							$totaltypes = ['Year', 'Month'];
							if (ISSET($_POST['totaltype'])) {
								$totaltype = $_POST['totaltype'];
							}
							else {
								$totaltype = $totaltypes[0];
							}
							foreach ($totaltypes as $y) {
								echo "<option value='" . $y . "'";
								if ($y == $totaltype) {
									echo " selected='selected'";
								}
								echo ">" . $y . "</option>";
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
				<h2>Stats</h2>
				<table class=padded style="width:100%;">
					<tr class=header>
						<td></td>
						<td>Miles</td>
						<td>Times out</td>
						<td>Ave distance</td>
						<td>Total time / hours</td>
						<td>Ave time out</td>
						<td>Ave speed</td>
						<td>Longest</td>
					</tr>
				<?php
				
					if ($totaltype == 'Year') {
						$group = 'Y';
						$grouptext = 'Y';
					}
					else if ($totaltype == 'Month') {
						$group = 'm';
						$grouptext = 'M y';
					}
					
					$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time FROM Rides ORDER BY date");
					
					$currentyear=0;
					
					$all_distance = 0;
					$all_rides = 0;
					$all_time = 0;
					$all_longest = 0;
					
					$total_distance = 0;
					$total_rides = 0;
					$total_time = 0;
					$longest = 0;
					
					while($ride = mysqli_fetch_array($rides)) {
						
						$date	 		= 	new DateTime($ride["date"]);
						$distance		= 	$ride["distance"];
						$hours 			= 	$ride["hours"];
						$minutes	 	= 	$ride["minutes"];
						$seconds	 	= 	$ride["seconds"];
						
						// If this is the first year
						if ($currentyear == 0) {
							$currentyear = $date->format($group);
							echo "<tr><td>" . $date->format($grouptext) . "</td>";
						}
						else if ($date->format($group) != $currentyear) {
							$currentyear = $date->format($group);
							echo "<td>" . number_format($total_distance, 0) . "</td>
								<td>" . $total_rides . "</td>
								<td>" . number_format($total_distance / $total_rides, 1) . "</td>
								<td>" . number_format($total_time, 0) . "</td>
								<td>" . number_format($total_time / $total_rides, 1) . "</td>
								<td>" . number_format($total_distance / $total_time, 1) . "</td>
								<td>" . number_format($longest, 0) . "</td>";
								
							echo "<tr";
								if ($group == 'm' && $currentyear == 1) {
									echo " style='border-top: #AAAAAA 2px solid;'";
								}
							echo "><td>" . $date->format($grouptext) . "</td>";
							
							
							
							
							$total_distance = 0;
							$total_rides = 0;
							$total_time = 0;
							$longest = 0;
						}
						
						$total_distance += $distance;
						$total_rides++;
						$total_time += $hours + $minutes / 60 + $seconds / 3600;
						if ($distance > $longest) {
							$longest = $distance;
						}
						
						$all_distance += $distance;
						$all_rides++;
						$all_time += $hours + $minutes / 60 + $seconds / 3600;
						if ($distance > $all_longest) {
							$all_longest = $distance;
						}
						
					}
					echo "<td>" . number_format($total_distance, 0) . "</td>
							<td>" . $total_rides . "</td>
							<td>" . number_format($total_distance / $total_rides, 1) . "</td>
							<td>" . number_format($total_time, 0) . "</td>
							<td>" . number_format($total_time / $total_rides, 1) . "</td>
							<td>" . number_format($total_distance / $total_time, 1) . "</td>
							<td>" . number_format($longest, 0) . "</td>";
					
					echo "</tr><tr style='color:#FF2f2f; font-weight:bold;'>";
					echo "<td>Overall</td>";
					echo "<td>" . number_format($all_distance, 0) . "</td>
							<td>" . $all_rides . "</td>
							<td>" . number_format($all_distance / $all_rides, 1) . "</td>
							<td>" . number_format($all_time, 0) . "</td>
							<td>" . number_format($all_time / $all_rides, 1) . "</td>
							<td>" . number_format($all_distance / $all_time, 1) . "</td>
							<td>" . number_format($all_longest, 0) . "</td>";
					echo "</tr></table>"
				?>
				
			</div><!-- #centered -->
		</div>
		
		<?php drawMenu("bottom"); ?>

	</body>
</html>