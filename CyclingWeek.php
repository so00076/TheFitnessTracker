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
		<?php
			$now = new DateTime(date('Y-m-d H:i:s'));
			$now = $now->format('Y-m-d H:i:s');
			
			$endToday = new DateTime("+1 day" . ($now));
			$endToday = $endToday->format('Y-m-d');
			$endToday = $endToday . ' 00:00:00';
			
			$weekAgo = new DateTime("-7 day" . " -1 second" . ($endToday));
			$weekAgo = $weekAgo->format('Y-m-d H:i:s');
			
			$weekStart = new DateTime($weekAgo);
			$today = new DateTime($endToday);
			
			while ($weekStart < $today) {
				$day = $weekStart->format('D');
				
				if ($day == 'Sun') {
					break;
				}
				
				$weekStart = new DateTime("+1 day" . ($weekStart->format('Y-m-d H:i:s')));
			}
			
			
			$weekStartText = new DateTime("+1 day" . ($weekStart->format('Y-m-d H:i:s')));
			$weekStartText = $weekStartText->format('d/m');
			
			$weekStart = $weekStart->format('Y-m-d H:i:s');
			$weekEnd = new DateTime("+7 day" . " +1 second" . ($weekStart));
			
			$weekEndText = new DateTime("-1 day" . ($weekEnd->format('Y-m-d H:i:s')));
			$weekEndText = $weekEndText->format('d/m');
			$weekEnd = $weekEnd->format('Y-m-d H:i:s');
			
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
					<li><a href="CyclingWeek.php" style='font-weight:bold'>Week</a></li>
					<li><a href="CyclingGraphs.php">Graphs</a></li>
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
					<button id='fullscreenbutton' onClick='fullscreen()'>full screen</button>
				</li>
			</ul>
		</div>
		
		<div id="content-wrapper">		
			<div id="centered">
				<ul id='page-header'>
					<li>
						<h2>This Week</h2>
					</li>
					<li>
						<?php
							echo $weekStartText;
							echo " - ";
							echo $weekEndText;
						?>
					</li>
				</ul>
				<?php
				
					$totalRides = 0;
					$totalRideDistance = 0;
					$totalRideTime = 0;
					$longest = 0;
				
					$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time FROM Rides ORDER BY Date");
										
					while($ride = mysqli_fetch_array($rides)) {

						$date	 		= 	$ride["date"];
						$location		= 	$ride["location"];
						$distance		= 	$ride["distance"];
						$hours 			= 	$ride["hours"];
						$minutes	 	= 	$ride["minutes"];
						$seconds	 	= 	$ride["seconds"];
						
						if ($date > $weekStart && $date < $weekEnd) {
							
							$totalRides++;
							$totalRideDistance += $distance;
							$totalRideTime += $hours + $minutes / 60 + $seconds / 3600;
							if ($distance > $longest) {
								$longest = $distance;
							}
						}
					}
					
					$targetTexts 			= ['Rides', 'Distance', 'Time', 'Longest'];
					$targetVars 			= [$totalRides, $totalRideDistance, $totalRideTime, $longest];
					$targetTargets 			= [2, 60, 4, 30];
					$targetDenominations 	= [1, 10, 1, 5];
					$targetFormats			= [0, 0, 1, 0];
					
					$arraySize = count($targetVars);
					
					$i=0;
					
					while($i < $arraySize) {
						$targetVar = $targetVars[$i];
						$targetTarget = $targetTargets[$i];
						$targetText = $targetTexts[$i];
						$targetDenomination = $targetDenominations[$i];
						$targetFormat = $targetFormats[$i];
						$bonus = 0;
						
						if ($targetVar > $targetTarget) {
							$bonus = $targetVar - $targetTarget;
							$targetVar = $targetTarget;
						}
						
						$percentage = ($targetVar / $targetTarget) * 100;
						
						echo "
							<table class='target'>
							<tr>
								<td class='number'>
									" . number_format($targetVar, $targetFormat) . "
								</td>
								<td class='targetbar'>
									<div class='info'>
						";
										$percentageText = number_format($percentage, 0) . "%";
										if ($percentage >= 100) {
											echo "You've done it! Nice one. Now to see how many bonus tokens you can get...";
										}
										else if ($percentage > 75) {
											echo "You have achieved ${percentageText} of your target - nearly there!";
										}
										else if ($percentage > 50) {
											echo "You have achieved ${percentageText} of your target - over halfway done.";
										}
										else if ($percentage == 50) {
											echo "You've achieved exactly ${percentageText} of you're target - halfway there.";
										}
										else if ($percentage > 25) {
											echo "You're making good progress - currently at ${percentageText} of your target.";
										}
										else if ($percentage > 0) {
											"Great start - now let's see if you can reach your target. You're currently at ${percentageText}.";
										}
										else {
											echo "Nothing for this week so far - try to change that in style.";
										}
						echo "
									</div>
									<div class='targettext'>
										" . $targetText . "
									</div>
									<div class='targetmover' style='width:" . $percentage . "%;'></div>
								</td>
								<td class='number'>
									" . $targetTarget . "
								</td>
							</tr>
							</table>
						";
						
						if ($bonus >= $targetDenomination) {
						
							echo "
								<table class='target'>
								<tr>
									<td class='number'>
										+
									</td>
							";
							
							while ($bonus >= $targetDenomination) {
								echo "
										<td class='targetbonus'>
											" . $targetDenomination . "
										</td>
										<td style='width:20px'></td>
								";
								
								$bonus = $bonus - $targetDenomination;
							}
							
							echo "
								<td></td>
								</tr>
								</table>
							";
							
							echo "
								<table class='target'>
								<tr>
									<td class='number'>
									<td class='number' style='border-top:3px black solid; width:80%; text-align:right;'>= 
										" . number_format($targetVars[$i], 0) . "
									</td>
									<td>
								</tr>
								</table>
							";
						}
						$i++;
					}
				?>
				
			</div><!-- #centered -->
		</div>
		
		<?php drawMenu("bottom"); ?>
		
		<script>
			$.each($('.target .targetmover'), function(index, elem) {
				$(elem).css('animation', 'progress-bar');
				$(elem).css('-webkit-animation', 'progress-bar');
				$(elem).css('animation-duration', (index)*0.5+1 + 's');
				$(elem).css('-webkit-animation-duration', (index)*0.5+1 + 's');
			});
		</script>
		
	</body>
</html>