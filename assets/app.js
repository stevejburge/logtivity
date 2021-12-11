var LogtivityLogIndex = {
	
	init: function() {
		this.container = jQuery('#logtivity-log-index');

		var $this = this;

		jQuery("body").on( "submit", "#logtivity-log-index-search-form", function(e) {
			e.preventDefault();

			$this.filter(jQuery(this));
		});

		jQuery('#logtivity-log-index-search-form').submit();
	},

	filter: function(form) {

		var $this = this;

		jQuery.ajax({
		    url: form.attr('action'),
		    type: 'GET',
		    data: form.serialize(),
		    success: function(result) {
			    $this.container.html(result.view);
			},
			error: function(error) {
				// console.log(error);
			}
		});

	}

};

jQuery(function() {
	LogtivityLogIndex.init();
});