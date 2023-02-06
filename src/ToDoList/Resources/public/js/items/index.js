$(function() {
	$( "#sortable" ).sortable({
		stop: function(event, ui) {
			var eventObj = {
				fields: [],
			};

        	var list = $("#sortable li");
			$.each(list, function( index, value ) {
  				eventObj.fields.push({
  					id: $(this).attr("data-id"),
  					order: index,
  				});
			});

			$.ajax({
		        type: 'post',
		        url: Routing.generate('items.ajax.update'),
		    	data: JSON.stringify(eventObj),
	    	});
    	},
	});
	$( "#sortable" ).sortable();
	$("#sortable").disableSelection();

	$('#datetimepicker').datetimepicker({
		defaultDate: $('input[name=completionDate]').attr('data-date'),
	});
});