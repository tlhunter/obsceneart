function fbs_click() {
	u=location.href;
	t=document.title;
	window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');
	return false;
}
$(function() {
	$('.quote_meta a.score-link').click(function(event) {
		event.preventDefault();
		var thisObj = $(this);
		thisObj.parent('.quote_meta').find('.selected').removeClass('selected');
		var score = thisObj.attr('data-score');
		var quote = thisObj.attr('data-quote');
		$.ajax({
			url: "quotes/" + quote + "/vote/" + score,
			success: function(){
				thisObj.addClass("selected");
			}
		});
	});

	var availableTags = [
		"ActionScript",
		"AppleScript",
		"Asp",
		"BASIC",
		"C",
		"C++",
		"Clojure",
		"COBOL",
		"ColdFusion",
		"Erlang",
		"Fortran",
		"Groovy",
		"Haskell",
		"Java",
		"JavaScript",
		"Lisp",
		"Perl",
		"PHP",
		"Python",
		"Ruby",
		"Scala",
		"Scheme"
	];
	function split( val ) {
		return val.split( /,\s*/ );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}

	$("#tags")
		// don't navigate away from the field on tab when selecting an item
		.bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).data( "autocomplete" ).menu.active ) {
				event.preventDefault();
			}
		})
		.autocomplete({
			minLength: 1,
			source: "submit/autocomplete/",
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			select: function( event, ui ) {
				var terms = split( this.value );
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push( ui.item.value );
				// add placeholder to get the comma-and-space at the end
				terms.push( "" );
				this.value = terms.join( ", " );
				return false;
			}
		});
});

function getToURI(submitEvent) {
	window.location = submitEvent.action + '/' + submitEvent.q.value;
	return false;
}

function confirmQuoteDelete() {
	if (confirm("Are you sure you would like to delete this quote?")) {
		return true;
	}
	return false;
}