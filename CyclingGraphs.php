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
					<li><a href="CyclingStats.php">Stats</a></li>
					<li><a href="CyclingWeek.php">Week</a></li>
					<li><a href="CyclingGraphs.php" style='font-weight:bold'>Graphs</a></li>
					<li><a href='javascript:void(0);' onClick='openNewWindow()'>New</a>
						<div id='newWindow' style='visibility:hidden; color:black; position:absolute; width:320px; border: solid #EE7777 4px; background:#EEEEEE; right:40px; top:40px; z-index:+1;'>
							<h2 style='padding-left:5px;'>New Ride</h2>
							
							<form METHOD='POST' id='entryform'>
							<table>
								<tr>
									<td><label for='date'>Date</label></td>
									<td>
										<input class='time' id='day' name='day' value='d' required='required' pattern='[0-9]*' title='Positive number'></input>
										/
										<input class='time' id='month' name='month' value='m' required='required' pattern='[0-9]*' title='Positive number'></input>
										/
										<input class='time' id='year' name='year' value='y' required='required' pattern='[0-9][0-9]' title='10 or greater'></input>
									</td>
								</tr>
								<tr>
									<td><label for='distance'>Where?</label></td>
									<td><textarea style='overflow:auto; width:90%;' maxlength='100' rows='3' id='location' name='location' required='required'></textarea></td>
								</tr>
								<tr>
									<td><label for='distance'>Distance</label></td>
									<td><input style='width:90%' id='distance' name='distance' required='required' pattern='[0-9]([0-9]|.)*' title='Positive number'></input></td>
								</tr>
								<tr>
									<td><label for='hours'>Time</label></td>
									<td>
										<input class='time' id='hours' name='hours' value='hh' required='required' pattern='[0-9]*' title='Positive number'></input>
										:
										<input class='time' id='minutes' name='minutes' value='mm' required='required' pattern='[0-9]*' title='Positive number'></input>
										:
										<input class='time' id='seconds' name='seconds' value='ss' required='required' pattern='[0-9]*' title='Positive number'></input>
									</td>
								</tr>
							</table>
							
							<input type="submit" value="Enter" title="Enter" style="width:100%; font-size:20px; border:none; background-color:#FF2f2f; margin-top:5px; padding:5px; color:white;">

						</form>
						
						</div>
					</li>
				</ul>
			</div>
		</div><!-- #head-title -->
		
		<div class='head-bar'>
			<ul style='position:absolute; right:0px;'>
				
				<li>
				<form METHOD='POST'>
					<input type='checkbox' name='trendline' id='trendline' style='transform:scale(2); border:none;'
						<?php
							// If the user checked the checkbox, make sure it is still checked
							if (isset($_POST['trendline'])) {
								echo " checked";
							};
						?>
					></input>
					<label class='rightmargin' for='checkbox'>Trend Line</label>
					
					<label for='graphtype'>Graph Type:</label>
					<select name='graphtype' id='graphtype'>
						<?php
							// Insert new graph types here
							$graphtypes = ['Distance', 'Rides', 'Time', 'Average Speed'];
							// If the user has picked a graph type
							if (ISSET($_POST['graphtype'])) {
								// Set $graphtype to be what the user selected
								$graphtype = $_POST['graphtype'];
							}
							else {
								// Otherwise, default to the first option
								$graphtype = $graphtypes[0];
							}
							// Create the dropdown box
							setupDropdown($graphtypes, $graphtype);
						?>
					</select>
					
					<label for='totaltype'>Total By:</label>
					<select name='totaltype' id='totaltype'>
						<?php
							// Insert new total types here
							$totaltypes = ['Year', 'Month', 'Day'];
							// If the user has picked a total type
							if (ISSET($_POST['totaltype'])) {
								// Set $totaltype to what the user selected
								$totaltype = $_POST['totaltype'];
							}
							else {
								// Otherwise, default to the first option
								$totaltype = $totaltypes[0];
							}
							// Create the dropdown box
							setupDropdown($totaltypes, $totaltype);
						?>
					</select>
				
					<input type="submit" value="Go" title="Go" style="font-size:20px; border:none;">
				</form>
				</li>
				<li>
				<button id='fullscreenbutton' onClick='fullscreen()'>full screen</button>
				</li>
			</ul>
		</div>
		
		<div id="content-wrapper">
			<div id="centered">
				<h2>Graphs</h2>
					<?php
						$format = 0;
					
						switch ($graphtype) {
							case 'Distance':
								$format = 0;
								break;
							case 'Rides':
								$format = 0;
								break;
							case 'Time':
								$format = 0;
								break;
							case 'Average Speed':
								$format = 1;
								break;
							default:
								$format = 0;
						}
					
						/*
						This is a variable used to count a certain type of data, depending on the graph type.
						For example, if the graph type is Distance, then the graphVar will add each new distance
						to it's current total, until it is reset.
						*/
						$graphVar = 0.0;
						/* 
						When $graphVar is the average speed, this variable counts the amount of time we have
						covered so far, so that we can calculate the new overall average speed
						*/
						$time = 0.0;
						
						// Function to update the graphVar depending on the graph type selected by the user.
						function updateVar()
						{
							// Get the information we need to make the calculations.
							global $graphtype, $graphVar, $distance, $hours, $minutes, $seconds, $time;
							// Do different things depending on the graph type
							switch ($graphtype) {
								case 'Distance':
									$graphVar += $distance;
									break;
								case 'Rides':
									$graphVar++;
									break;
								case 'Time':
									$graphVar += $hours + $minutes / 60 + $seconds / 3600;
									break;
								case 'Average Speed':
									if ($graphVar == 0) {
										$time = 0;
									}
									
									$distanceSoFar = $graphVar * $time;
									$newDistance = $distance;
									$graphVar = ($distanceSoFar + $newDistance) / ($time + $hours + $minutes / 60 + $seconds / 3600);
									
									$time += $hours + $minutes / 60 + $seconds / 3600;
									
									break;
								// If the option the user has selected isn't in the switch statement, alert
								// the developer
								default:
									echo "This option is not declared in the 'updateVar()' switch statement!<br>";
							}
						}
						
						// Function to update a specified variable depending on the graph type selected by the user.
						function updateThisVar($var)
						{
							// Get the information we need to make the calculations.
							global $graphtype, $distance, $hours, $minutes, $seconds;
							// Do different things depending on the graph type
							switch ($graphtype) {
								case 'Distance':
									$var += $distance;
									break;
								case 'Rides':
									$var++;
									break;
								case 'Time':
									$var += $hours + $minutes / 60 + $seconds / 3600;
									break;
								case 'Average Speed':
									$var = $distance / ($hours + $minutes / 60 + $seconds / 3600);
									break;
								// If the option the user has selected isn't in the switch statement, alert
								// the developer
								default:
									echo "This option is not declared in the 'updateThisVar($var)' switch statement!<br>";
							}
							return $var;
						}
						
						// The height (in pixels) that the graph will appear on screen (applies to ALL graphs)
						$graphHeight = 500;
						
						// If we are not looking at each individual day, then we should display a bar chart
						if ($totaltype != 'Day') {
							// Set how text should appear when displaying different total types
							switch ($totaltype) {
								case 'Year':
									$group = 'Y';
									$grouptext = 'Y';
									break;
								case 'Month':
									$group = 'm';
									$grouptext = 'M y';
									break;
								default:
									// If the option the user has selected isn't in the switch statement, alert
									// the developer
									echo "You haven't declared how the total type: '$totaltype' should display text";
									break;
							}
							
							// Get all ride data from the database - ordered by date ascending
							$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time FROM Rides ORDER BY date");
							
							// Counts the total number of time periods from start to finish
							$count = 0;
							
							$currentDate = 0;
							
							$highest = 0;
						
							while($ride = mysqli_fetch_array($rides)) {
								
								$date	 		= 	new DateTime($ride["date"]);
								$distance		= 	$ride["distance"];
								$hours 			= 	$ride["hours"];
								$minutes	 	= 	$ride["minutes"];
								$seconds	 	= 	$ride["seconds"];
								
								if ($currentDate == 0) {
									$currentDate = $date->format($group);
								}
								else if ($date->format($group) != $currentDate) {
									
									if ($graphVar > $highest) {
										$highest = $graphVar;
									}
									
									/*
									* We need to check that the new date is exactly 1 above the date before.
									* This is so that dates with no rides will still show up with 0s
									*/

									// If the new date is not directly above the previous date, fill the missing
									// date with 0s.
									// NOTE: This only works for 1 month of 0 in a row.
									if ( ($date->format($group) != $currentDate + 1) && $date->format($group) != 1) {
										$count++;
									}
									else if ($date->format($group) == 1 && $currentDate == 11) {
										$count++;
									}
									
									$currentDate = $date->format($group);
									
									$graphVar = 0;
									$count++;
								}
								
								updateVar();
							}
							
							$count++;
							if ($graphVar > $highest) {
								$highest = $graphVar;
							}
							
							$subtractor = $highest * 1.1;
							$divisor = $subtractor / $graphHeight;
							$separator = (4 / $count) * 200;
							$fontsize = pow(2, (-log($count/4, 10))) * 21;
							
							$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time FROM Rides ORDER BY date");
							
							$currentdate=0;
							
							$graphVar = 0;
							
							echo "
								<table style='width:99%; height:" . $graphHeight . "px;'>
									<tr id='graph'>
							";
						
							while($ride = mysqli_fetch_array($rides)) {
								
								$date	 		= 	new DateTime($ride["date"]);
								$distance		= 	$ride["distance"];
								$hours 			= 	$ride["hours"];
								$minutes	 	= 	$ride["minutes"];
								$seconds	 	= 	$ride["seconds"];
								
								if ($currentdate == 0) {
									$currentdate = $date->format($group);
								}
								else if ($date->format($group) != $currentdate) {
									echo "
										<td style='max-width:2px; background:red;'>
											<div style='width:100%; height:" . ($subtractor - ($graphVar)) / $divisor . "px; background:#EEEEEE;'>&nbsp</div>
											<div style='height:0px; position:relative; top:-20px; text-align:center;'>" . number_format($graphVar, $format) . "</div>
										</td>
									";
									echo "<td style='width:" . $separator . "px;'></td>";
									
									/*
									* We need to check that the new date is exactly 1 above the date before.
									* This is so that dates with no rides will still show up with 0s
									*/

									// If the new date is not directly above the previous date, fill the missing
									// date with 0s.
									// NOTE: This only works for 1 month of 0 in a row.
									if ( ($date->format($group) != $currentdate + 1) && $date->format($group) != 1) {
										echo "
											<td style='max-width:2px; background:red;'>
												<div style='width:100%; height:" . (($subtractor - (0)) / $divisor) . "px; background:#EEEEEE;'>&nbsp</div>
												<div style='height:0px; position:relative; top:-20px; text-align:center;'>" . number_format(0, $format) . "</div>
											</td>
										";
										echo "<td style='width:" . $separator . "px;'></td>";
									}
									else if ($date->format($group) == 1 && $currentdate == 11) {
										echo "
											<td style='max-width:2px; background:red;'>
												<div style='width:100%; height:" . (($subtractor - (0)) / $divisor) . "px; background:#EEEEEE;'>&nbsp</div>
												<div style='height:0px; position:relative; top:-20px; text-align:center;'>" . number_format(0, $format) . "</div>
											</td>
										";
										echo "<td style='width:" . $separator . "px;'></td>";
									}
									
									$currentdate = $date->format($group);
									
									$graphVar = 0;
								}
								
								updateVar();
							}
							
							echo "
								<td style='max-width:2px; background:red;'>
									<div style='width:100%; height:" . ($subtractor - ($graphVar)) / $divisor . "px; background:#EEEEEE;'>&nbsp</div>
									<div style='height:0px; position:relative; top:-20px; text-align:center;'>" . number_format($graphVar, $format) . "</div>
								</td>
							</tr>
							<tr>
							";
											
							$rides = mysqli_query($link, "SELECT date FROM Rides ORDER BY date");
							
							$currentdate=0;

							while($ride = mysqli_fetch_array($rides)) {
								
								$date	 		= 	new DateTime($ride["date"]);
								
								if ($currentdate == 0) {
									$currentdate = $date->format($group);
									echo "<td style='padding-top:5px; max-width:2px; font-size:" . $fontsize . "px; text-align:center;'>" . $date->format($grouptext) . "</td>";
									echo "<td></td>";
								}
								else if ($date->format($group) != $currentdate) {
									
									/*
									* We need to check that the new date is exactly 1 above the date before.
									* This is so that dates with no rides will still show up with 0s
									*/

									// If the new date is not directly above the previous date, fill the missing
									// date with 0s.
									// NOTE: This only works for 1 month of 0 in a row.
									if ( (!($date->format($group) == $currentdate + 1)) && $date->format($group) != 1) {
										echo "<td style='padding-top:5px; max-width:2px; font-size:" . $fontsize . "px; text-align:center;'>--</td>";
										echo "<td></td>";
									}
									else if ($date->format($group) == 1 && $currentdate == 11) {
										echo "<td style='padding-top:5px; max-width:2px; font-size:" . $fontsize . "px; text-align:center;'>--</td>";
										echo "<td></td>";
									}
									
									echo "<td style='padding-top:5px; max-width:2px; font-size:" . $fontsize . "px; text-align:center;'>" . $date->format($grouptext) . "</td>";
									echo "<td></td>";
									
									$currentdate = $date->format($group);
								}
							}
							
							echo "
										</tr>
									</table>
								</div><!-- bar-graph -->
							";
						}
					?>

					<?php
						
						if ($totaltype == 'Day') {
						
							$graphVar = 0;
						
							$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time FROM Rides ORDER BY date");
							
							$count = 0;
							
							$highest = 0;
							
							while($ride = mysqli_fetch_array($rides)) {
								
								$date	 		= 	new DateTime($ride["date"]);
								$distance		= 	$ride["distance"];
								$hours 			= 	$ride["hours"];
								$minutes	 	= 	$ride["minutes"];
								$seconds	 	= 	$ride["seconds"];
								
									$graphVar = updateThisVar($graphVar);
									if($graphVar > $highest) {
										$highest = $graphVar;
									}
									$graphVar = 0;
									
									$count++;
							}
						
						
							$subtractor = $highest * 1.1;
							//echo $highest;
							$divisor = $subtractor / $graphHeight;
							//$separator = (4 / $count) * 200;
							//$fontsize = pow(2, (-log($count/4, 10))) * 21;
							
							echo "
								<div id='line-graph'>
							";
							
							$lastVar = 0;
							$currentVar = 0;
							
							$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time FROM Rides ORDER BY date");
							
							$firstdateset = false;
							
							$previousDate = new DateTime('2008-08-03 14:52:10');
							
							echo "<canvas id='linegraph' height='" . $graphHeight . "px'></canvas>";
							
							echo "
								<script>
									var canvas = document.getElementById('linegraph');
									var context = canvas.getContext('2d');
									canvas.width = window.innerWidth;
								</script>
							";
							
							$i=0;
							
							echo "
								<script>
									var Xmultiplier = 1.1;
								</script>
							";
							
							
							//$Xmultiplier = 1.1;
							
							$sumX	=	0;
							$sumY	=	0;
							$sumXY	=	0;
							$sumX2	=	0;
							$n		=	0;
							
							while($ride = mysqli_fetch_array($rides)) {
								
								$date	 		= 	new DateTime($ride["date"]);
								$distance		= 	$ride["distance"];
								$hours 			= 	$ride["hours"];
								$minutes	 	= 	$ride["minutes"];
								$seconds	 	= 	$ride["seconds"];
								
								if ($firstdateset == false) {
									$firstdateset = true;
									$currentdate = $date;
									$previousDate = $date;
									$lastHeight = $distance;
									$lastVar = updateThisVar($lastVar);
								}
								else {
									$currentdate=$date;
									
									$previousI = $i;
									
									while($previousDate < $date) {
										$previousDate = new DateTime("+1 day" . ($previousDate->format('Y-m-d H:i:s')));
										$i++;
									}
									
									$currentVar = updateThisVar($currentVar);
									
									echo "
										<script>
											context.beginPath();
											context.moveTo(" . $previousI . "*Xmultiplier, " . ($subtractor - $lastVar) / $divisor . ");
											context.lineTo(" . $i . "*Xmultiplier, " . ($subtractor - $currentVar) / $divisor . ");
											context.lineWidth = 2;
											context.strokeStyle = '#FF2f2f';
											context.stroke();
											context.beginPath();
											/*
											context.arc(" . $previousI . "*Xmultiplier, " . ($subtractor - $lastVar) / $divisor . ", 3, 0, 2 * Math.PI, false);
											context.fillStyle = '#FF2f2f';
											context.fill();
											*/
											drawGraphPoint(0, " . $previousI . "*Xmultiplier, " . ($subtractor - $lastVar) / $divisor . ", '#FF2f2f', context);
										</script>
									";
									
									//$lastHeight = $distance;
									$previousDate = $date;
									
									$sumX	+= $previousI;
									$sumY	+= $lastVar;
									$sumXY	+= $previousI * $lastVar;
									$sumX2	+= $previousI * $previousI;
									$n++;
									
									$currentVar = 0;
									$lastVar = 0;
									$lastVar = updateThisVar($lastVar);
								}
							}
							
							$currentVar = updateThisVar($currentVar);
							
							echo "
								<script>
									context.beginPath();
									context.arc(" . $i . "*Xmultiplier, " . ($subtractor - $currentVar) / $divisor . ", 3, 0, 2 * Math.PI, false);
									context.fillStyle = '#FF2f2f';
									context.fill();
								</script>
							";
							
							if (isset($_POST['trendline'])) {
							
								$currentVar = 0;
								$currentVar = updateThisVar($currentVar);
								
								$sumX	+= $i;
								$sumY += updateThisVar(0);
								//$sumY	+= $distance;
								$sumXY	+= $i * $currentVar;
								$sumX2	+= $i * $i;
								$n++;
								
								$m = ($sumXY - (($sumX * $sumY)/$n))  /  ($sumX2 - ($sumX^2 / $n));
								$mText = number_format($m, 5);
								
								$c = ($sumY / $n) - ($m * ($sumX / $n));
								$cText = number_format($c, 1);
								
								echo "
									<br>
									<br>
									Trendline equation:
									<br>
									y = ${m}x + ${c}
									<script>
										context.beginPath();
										context.moveTo(" . 0 . ", " . ($subtractor - $c) / $divisor . ");
										context.lineTo(" . $i . "*Xmultiplier, " .  ($subtractor - (($m * $i) + $c)) / $divisor . ");
										context.lineWidth = 3;
										context.strokeStyle = 'rgba(47, 47, 255, 0.5)';
										context.stroke();
									</script>
								";
								
							}
							
							echo "
								<script>
									context.beginPath();
									context.moveTo(" . 0 . ", " . $graphHeight . ");
									context.lineTo(" . $i . "*Xmultiplier, " .  $graphHeight . ");
									context.lineWidth = 3;
									context.strokeStyle = 'rgb(0, 0, 0)';
									context.stroke();
								</script>
							";
							
							echo "
								</div><!-- line-graph -->
							";
						}
					?>
				
			</div><!-- #centered -->
		</div>
		
		<?php drawMenu("bottom"); ?>
		
	</body>
</html>