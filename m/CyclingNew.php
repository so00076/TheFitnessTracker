<!DOCTYPE HTML>

<html lang="en">
	<head>
	

		
		<title>The Fitness Tracker</title>
		<link rel="stylesheet" type="text/css" href="styles.css">
		<script src="scripts/jquery-1.10.2.min.js"></script>
		<script src="scripts/fullscreen.js"></script>
		
		<?php
			include_once("include.php");
		?>
	</head>

	<body class=cycling>

		<div id="head-menu" class='menu'>
			<ul>
				<li><a href="index.php">Menu</a></li>
			<!--
				<li><a href="index.php">Summary</a></li>
				<li><a href="Top10Movies.php">Swimming</a></li>
				<li><a href="CyclingData.php">Cycling</a></li>
				<li><a href="Feedback.php">Running</a></li>
				<li><a href="Feedback.php">Triathlon</a></li>
				<li><a href="Feedback.php">Other</a></li>
			-->
			</ul>
		</div><!-- #head-menu -->
	
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
				
				<?php
					if(isset($_POST['distance'])){
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
					}
				?>
				
				<div id='centered'>
					<div style='width:100%'>
						<form METHOD='POST' id='entryform'>
							<table>
								<tr><td><label for='date'>Date</label></td></tr>
								<tr>
									
									<td>
										<input class='time' id='day' name='day' value='d' required='required' pattern='[0-9]*' title='Positive number'></input>
										/
										<input class='time' id='month' name='month' value='m' required='required' pattern='[0-9]*' title='Positive number'></input>
										/
										<input class='time' id='year' name='year' value='y' required='required' pattern='[0-9][0-9]' title='10 or greater'></input>
									</td>
								</tr>
								<tr><td><label for='distance'>Where?</label></td></tr>
								<tr>
									
									<td><textarea style='overflow:auto; width:100%;' maxlength='100' rows='3' id='location' name='location' required='required'></textarea></td>
								</tr>
								<tr><td><label for='distance'>Distance</label></td></tr>
								<tr>
									
									<td><input style='width:100%' id='distance' name='distance' required='required' pattern='[0-9]([0-9]|.)*' title='Positive number'></input></td>
								</tr>
								<tr><td><label for='hours'>Time</label></td></tr>
								<tr>
									
									<td>
										<input class='time' id='hours' name='hours' value='hh' required='required' pattern='[0-9]*' title='Positive number'></input>
										:
										<input class='time' id='minutes' name='minutes' value='mm' required='required' pattern='[0-9]*' title='Positive number'></input>
										:
										<input class='time' id='seconds' name='seconds' value='ss' required='required' pattern='[0-9]*' title='Positive number'></input>
									</td>
								</tr>
							</table>
							
							<input type="submit" value="Enter" title="Enter" style="width:100%; border:none; background-color:#FF2f2f; margin-top:20px; padding:5px; color:white;">

						</form>
					</div>
				</div>
			</div><!-- #centered -->
		</div id >
		


	</body>
</html>