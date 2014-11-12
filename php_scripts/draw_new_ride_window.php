<?php
	
	echo "
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
				
				<span id='newStats'></span>
				
				<input type='submit' value='Enter' title='Enter' style='width:100%; font-size:20px; border:none; background-color:#FF2f2f; margin-top:5px; padding:5px; color:white;'>

			</form>
						
		</div>
		<script>
			jQuery('#seconds').on('input propertychange paste', function() {
				var distance 	= 	document.getElementById('distance').value;
				var hours 		= 	document.getElementById('hours').value;
				var minutes 	= 	document.getElementById('minutes').value;
				var seconds 	= 	document.getElementById('seconds').value;
				
				var time 		= 	(hours * 3600 + minutes * 60 + seconds * 1);
				var averageSpeed = distance / (time / 3600);
				averageSpeed = averageSpeed.toFixed(1);
				
				var newStats 	= document.getElementById('newStats');
				newStats.innerHTML = '<p>Average Speed: ' + averageSpeed + '</p>';
			});
		</script>
	";
	
	
?>