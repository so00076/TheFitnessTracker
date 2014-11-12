var ableToClose = false;

$(document).click(function(event) { 
	if($(event.target).closest('#newWindow').length == 0 ) { 
		if(ableToClose == true) {
			$('#newWindow').css({
				'visibility' : 'hidden'
			});
			ableToClose = false;
		}
		else {
			ableToClose = true;
		}
	}
});

function openNewWindow() {
	$('#newWindow').css({
		'visibility' : 'visible'
	});
	$('#day').focus();
	if(ableToClose == true) {
		window.location.href = 'cyclingNew.php';
	}
	ableToClose = false;
}