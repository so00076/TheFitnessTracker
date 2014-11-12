<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
	<head>		
		<title>The Fitness Tracker</title>
		<link rel="stylesheet" type="text/css" href="styles.css">
		<script src="scripts/jquery-1.10.2.min.js"></script>
		<script src="scripts/fullscreen.js"></script>
		<script src="scripts/newWindow.js"></script>
		<?php
			include_once("include.php");
		?>
	</head>

	<body class=cycling>

		<div id="head-menu">
			<ul>
				<li><a href="index.php">Summary</a></li>
				<li><a href="Top10Movies.php">Swimming</a></li>
				<li><a href="CyclingData.php">Cycling</a></li>
				<li><a href="Feedback.php">Running</a></li>
				<li><a href="Feedback.php">Triathlon</a></li>
				<li><a href="Feedback.php">Other</a></li>
			</ul>
		</div><!-- #head-menu -->
	
		<div id='sport-bar' class='head-bar'>
			<div style='position:absolute;'>
				<h1>Cycling</h1>
			</div>
			<div style='position:absolute; right:0px; padding-top:10px;'>
				<ul>
					<li><a href="CyclingData.php">Data</a></li>
					<li><a href="CyclingStats.php">Stats</a></li>
					<li><a href="CyclingGraphs.php" style='font-weight:bold'>Graphs</a></li>
					<li><a href='#head-menu' onClick='openNewWindow()'>New</a>
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
				<form METHOD='POST'>
				<li>
					<label for='graphtype'>Graph Type:</label>
					<select name='graphtype' id='graphtype'>
						<?php
							$totaltypes = ['Bar', 'Pie'];
							if (ISSET($_POST['graphtypetype'])) {
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
				</li>
				<li>
					<label for='totaltype'>Total By:</label>
					<select name='totaltype' id='totaltype'>
						<?php
							$totaltypes = ['Month', 'Year', 'Day'];
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
				</li>
				<li>
					<input type="submit" value="Go" title="Go" style="font-size:20px; border:none;">
				</li>
				<li>
					<button id='fullscreenbutton' onClick='fullscreen()'>full screen</button>
				</li>
				</form>
			</ul>
		</div>
		
		<div id="content-wrapper">		
			<div id="centered">
				<h2>Graphs</h2>
					<div id='line-graph'>
					
						<?php
							$rides = mysqli_query($link, "SELECT date, location, distance, hours, minutes, seconds, actual_time FROM Rides ORDER BY date");
							
							$firstdateset = false;
							
							$previousDate = new DateTime('2008-08-03 14:52:10');
							
							echo "<canvas id='linegraph' width='1366px' height='500px'></canvas>";
							
							$i=0;
							
							while($ride = mysqli_fetch_array($rides)) {
								
								$date	 		= 	new DateTime($ride["date"]);
								$distance		= 	$ride["distance"];
								
								if ($firstdateset == false) {
									$firstdateset = true;
									$currentdate = $date;
									$previousDate = $date;
									$lastHeight = $distance;
								}
								else {
									$currentdate=$date;
									
									$previousI = $i;
									
									while($previousDate < $date) {
										//echo ($previousDate->format('Y-m-d H:i:s'));
										$previousDate = new DateTime("+1 day" . ($previousDate->format('Y-m-d H:i:s')));
										//$previousdate->add(new DateInterval('P1D'));
										$i++;
										//echo $i;
									}
									
									echo "
										<script>
											var canvas = document.getElementById('linegraph');
											var context = canvas.getContext('2d');
											context.beginPath();
											context.moveTo(" . (($previousI)*1.1) . ", " . (100 - $lastHeight)*5 . ");
											context.lineTo(" . (($i)*1.1) . ", " . (100 - $distance)*5 . ");
											context.lineWidth = 2;
											context.strokeStyle = '#FF2f2f';
											context.stroke();
											context.beginPath();
											context.arc(" . (($previousI)*1.1) . ", " . (100 - $lastHeight)*5 . ", 3, 0, 2 * Math.PI, false);
											context.fillStyle = '#FF2f2f';
											context.fill();
										</script>
									";
									
									$lastHeight = $distance;
									$previousDate = $date;
								}
							}
						?>

				</div><!-- line-graph -->
			</div><!-- #centered -->
		</div>
		
		<div id="head-menu">
				<ul>
					<li><a href="index.php">Summary</a></li>
					<li><a href="Top10Movies.php">Swimming</a></li>
					<li><a href="CyclingData.php">Cycling</a></li>
					<li><a href="Feedback.php">Running</a></li>
					<li><a href="Feedback.php">Triathlon</a></li>
					<li><a href="Feedback.php">Other</a></li>
				</ul>
		</div><!-- #head-menu -->

	</body>
</html>