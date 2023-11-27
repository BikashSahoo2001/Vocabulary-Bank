jQuery(document).ready(function ($) {
	$('body').on('click', '#vocabulary-letters a', function () {
		$('#vocabulary-letters a').removeClass('active');
		$(this).addClass('active');
		// Get the class of the clicked anchor tag
		var clickedid = $(this).attr('id');

		// Get the last character of the class
		var lastCharacter = clickedid.charAt(clickedid.length - 1).toLowerCase();
		// Show and hide div elements based on their id's first letter
		$("#vocabulary-contents > div").each(function () {
			var divId = $(this).attr('id');
			var divFirstLetter = divId.charAt(0).toLowerCase();

			if (lastCharacter === '0' || divFirstLetter === lastCharacter) {
				$(this).show(); // Show all div elements when lastCharacter is '0'
			} else {
				$(this).hide();
			}
		});
	});

	$("#search-bar").on("input", function() {
		var query = $(this).val().toLowerCase();
		filterDivs(query);
	});

	function filterDivs(query) {
		$("#vocabulary-contents > div").each(function() {
			var divId = $(this).attr('id').toLowerCase(); // Convert to lowercase
			if (query === '' || divId.startsWith(query)) {
			$(this).show();
			} else {
			$(this).hide();
			}
		});
	}
})