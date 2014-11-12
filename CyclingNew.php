<!DOCTYPE HTML>

<html lang="en">
	<head>
	
		<script>
			if (screen.width <= 720) {
				//window.location = "m/cyclingNew.php";
			}

		</script>
		
		<title>The Fitness Tracker</title>
		<link rel="stylesheet" type="text/css" href="styles.css">
		<script src="js_scripts/jquery-1.10.2.min.js"></script>
		<script src="js_scripts/fullscreen.js"></script>
		
		<?php
			include_once("php_scripts/include.php");
		?>
		
	</head>

	<body class=cycling>
		<?php 
			include_once('php_scripts/draw_menus.php'); 
			drawMenu("top");
		?>
	
		<div class='head-bar'>
			<div style='position:absolute;'>
				<h1>Cycling</h1>
			</div>
			<div style='position:absolute; right:0px; padding-top:10px;'>
				<ul>
					<li><a href="CyclingAllRides.php">All Rides</a></li>
					<li><a href="CyclingData.php">Data</a></li>
					<li><a href="CyclingStats.php">Stats</a></li>
					<li><a href="CyclingWeek.php">Week</a></li>
					<li><a href="CyclingGraphs.php">Graphs</a></li>
					<li><a href="CyclingNew.php" style='font-weight:bold'>New</a></li>
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
				<h2>New Ride</h2>
				
				<div id='centered2'>
					<table>
					<tr>
						<td style='width:30%'>
						<!--<div style='width:30%'>-->
							<form METHOD='POST' id='entryform' autocomplete='off'>
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
										<td><label for='location'>Where?</label></td>
										<td><textarea style='overflow:auto; width:100%;' maxlength='100' rows='3' id='location' name='location' required='required'></textarea></td>
									</tr>
									<tr>
										<td><label for='distance'>Distance</label></td>
										<td><input style='width:100%' id='distance' name='distance' required='required' pattern='[0-9]([0-9]|.)*' title='Positive number'></input></td>
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
								
								<span id='newStats'></span>
								
								<input type="submit" value="Enter" title="Enter" style="width:100%; font-size:20px; border:none; background-color:#FF2f2f; margin-top:5px; padding:5px; color:white;" />

							</form>
						</td>
						<td style='width:260px; text-align:center; vertical-align:middle;'>
							<?php
								if(isset($_POST['distance'])){
									echo "<img src='icons/newStatsArrow.gif' style='height:120px;' />";
								}
							?>
						</td>
						<td style='vertical-align:top; width:auto;'>
							<?php
								if(isset($_POST['distance'])){
									echo "
										<table class='padded'>
											<tr>
												<td>
													<h3>" . $_POST['day'] . "/" . $_POST['month'] . "/" . $_POST['year'] . " (saved)</h3>
												</td>
												<td>
												</td>
											</tr>
											<tr>
												<td>
													Location
												</td>
												<td>
													" . $_POST['location'] . "
												</td>
											</tr>
											<tr>
												<td>
													Distance
												</td>
												<td>
													" . $_POST['distance'] . "mi
												</td>
											</tr>
												<td>
													Time
												</td>
												<td>
													" . $_POST['hours'] . ":" . $_POST['minutes'] . ":" . $_POST['seconds']. "
												</td>
											</tr>
											<tr>
												<td>
													Average Speed
												</td>
												<td>
													" . $_POST['distance'] / (($_POST['hours']*3600 + $_POST['minutes']*60 + $_POST['seconds']*0)/3600) . "
												</td>
											</tr>
										</table>";
									/*
									mysqli_query($link,
										"INSERT INTO Rides VALUES ('20" . 
										$_POST['year'] . "-" . 
										$_POST['month'] . "-" . 
										$_POST['day'] . "', '" . 
										$_POST['location'] . "', " . 
										$_POST['distance'] . ", " . 
										$_POST['hours'] . ", " . 
										$_POST['minutes'] . ", " . 
										$_POST['seconds'] . ", '');"
									);
									*/
								}
							?>
						</td>
					</tr>
					</table>
				</div>
			</div><!-- #centered -->
		</div>
		
		<?php drawMenu("bottom"); ?>

	</body>
	
	<script>
		function createNewStats() {
			var distance 	= 	document.getElementById('distance').value;
			var hours 		= 	document.getElementById('hours').value;
			var minutes 	= 	document.getElementById('minutes').value;
			var seconds 	= 	document.getElementById('seconds').value;
			
			var time 		= 	(hours * 3600 + minutes * 60 + seconds * 1);
			var averageSpeed = distance / (time / 3600);
			averageSpeed = averageSpeed.toFixed(1);
			
			var newStats 	= document.getElementById('newStats');
			
			newStats.innerHTML = "";
			
			if (time > 0 && averageSpeed != 0) {
				$(newStats).append("<p>Average Speed: " + averageSpeed + "</p>");
			}
		}
		
		$('#distance').on('input propertychange paste', createNewStats);
		
		$('#hours').on('input propertychange paste', createNewStats);
		$('#minutes').on('input propertychange paste', createNewStats);
		$('#seconds').on('input propertychange paste', createNewStats);
	</script>
	
</html>