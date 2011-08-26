var Events = { };

Events.edit = function(eventId) {
	
    $.get(KODELEARN.config.base_url + "event/edit/id/" + eventId,  {},
            function(html){
    			$('#edit_event').html(html);
            }, "html");
};

Events.save = function() {
	
	data = $('#event_form').serializeArray();
	$.post(KODELEARN.config.base_url + "event/edit/",  data, function(data){
    			if(data.success){
    				var msg = data.message;
    				KODELEARN.modules.get('ajax_message').showAjaxSuccess($("#event_form"),msg);
    				
    			} else {
    				msg = data.errors;
    				KODELEARN.modules.get('ajax_message').showAjaxError($("#event_form"),msg);
    			}
    }, "json");
};