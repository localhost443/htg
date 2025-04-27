(function ($) {
	'use strict';

	$("#startaSync").click(function () {
		event.preventDefault();
		syncproduct();
	});

	function syncproduct() {
		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', htgdropshipping_data.nonce)
			},
			url: htgdropshipping_data.root_url + '/wp-json/htgdropshipping/v1/sync',
			type: 'POST',
			data: {
				'hello': 'Hello World',
			},
			success: (res) => {
				// console.log(res);
			},
			error: (res) => {
				console.log(res);
				if (res.responseText === 'You have reched your note limit') {
					$('.note-limit-message').addClass('active');
				}
			}
		});
		alert('sync has been started');
	}
	// alert("Hello World");

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})(jQuery);