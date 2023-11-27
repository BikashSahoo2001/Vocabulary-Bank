jQuery(document).ready(function($) {
 
  $(document).on('click', '.ql-editor', function(e) {
    
		 e.preventDefault();

        var selector = $(this).parent('div');

        $.ajax({
            type: 'POST',  // Adjust the HTTP method as needed (GET, POST, etc.).
			      dataType: 'json',
            url: myLocalizedData.ajax_url,  // Specify the URL for your AJAX request.
            data: {

              action: 'add_question',  // The name of the AJAX action to be handled on the server
            },

            success: function(response) {

				      showQuestion(response, selector);

            }

        });

    });

    
    
    $('body').on('change', '#questionSelector', function() {

        var selectedOption = $(this).val(); // Get the value of the selected option
        // Your code to handle the selected option goes here

        $(this).siblings('.ql-editor').text(selectedOption);

    });

});

function showQuestion(response, selector) {

  var childExists = selector.find("select").length > 0;

  if ( !childExists ) {

    var data = response.data;

    var responseObject = JSON.parse(data);

    var questionData = responseObject.question_data;

    var select = jQuery("<select id ='questionSelector'></select>");

    var options = [];

    for ( var i = 0; i < questionData.length; i++ ) {
      //  console.log(questionData[i]);
        options.push( questionData[i] );

        select.append(jQuery("<option>", {

          value: questionData[i], // Set the value of the option
          text: questionData[i],  // Set the text displayed for the option

        }));
    }

    selector.append(select);

  }

}
