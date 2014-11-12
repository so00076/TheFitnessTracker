<?php
	function drawMenu($where) {
		switch ($where) {
			case "top":
				echo "
					
					<header class='head-menu collapse'>
						<ul>
							<li>
								<a href='index.php'>
									<h1 id='title'>The Fitness Tracker</h1>
								</a>
							</li>
							<li>
								<a href='javascript:void(0);' onClick='openMenu()'>
									<h1 id='mMenu'>&equiv;</h1>
								</a>
							</li>
						</ul>
					</header><!-- .head-menu -->

					<nav id='menu' class='head-menu collapse'>
						<ul>
							<li><a href='index.php'>Summary</a></li>
							<li><a href='Top10Movies.php'>Swimming</a></li>
							<li><a href='CyclingData.php'>Cycling</a></li>
							<li><a href='Feedback.php'>Running</a></li>
							<li><a href='Feedback.php'>Triathlon</a></li>
							<li><a href='Feedback.php'>Other</a></li>
						</ul>
					</nav><!-- .head-menu -->
				";
				break;
			case "bottom":
				echo "
					<nav id='foot-menu' class='head-menu'>
						<ul>
							<li><a href='index.php'>Summary</a></li>
							<li><a href='Top10Movies.php'>Swimming</a></li>
							<li><a href='CyclingData.php'>Cycling</a></li>
							<li><a href='Feedback.php'>Running</a></li>
							<li><a href='Feedback.php'>Triathlon</a></li>
							<li><a href='Feedback.php'>Other</a></li>
						</ul>
					</nav><!-- .head-menu -->
					<footer>
						&copy Single Key Software
					</footer>
					
					<script src='js_scripts/mobile.js'></script>
				";
				break;
			default:
				break;
		}
	};
?>