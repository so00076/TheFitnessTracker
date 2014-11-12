var title 	= document.getElementById('title');

if (window.innerWidth <= 1085) {	
	title.innerHTML = "TFT";
	$("#menu").hide();
}

window.addEventListener('resize', function(event){
	
	if (window.innerWidth <= 1085) {
		title.innerHTML = "TFT";
		$("#menu").hide();
	}
	else {
		title.innerHTML = "The Fitness Tracker";
		$("#menu").show();
	}
	
});

var open = false;

function openMenu() {
	if (open) {
		$('#menu').slideUp(200);
		open = false;
	}
	else {
		$('#menu').slideDown(200);		
		open = true;
	}
}