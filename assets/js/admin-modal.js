(function($) {
		var popup = {
				init: function() {
						$('.wrap').on('click', 'a.page-title-action.add-form', this.openModal);
						$('.registration-form-template-modal-backdrop, .registration-form-template-modal .close').on('click', $.proxy(this.closeModal, this) );

						$('body').on( 'keydown', $.proxy(this.onEscapeKey, this) );
				},

				openModal: function(e) {
						e.preventDefault();

						$('.registration-form-template-modal').show();
						$('.registration-form-template-modal-backdrop').show();
				},

				onEscapeKey: function(e) {
						if ( 27 === e.keyCode ) {
								this.closeModal(e);
						}
				},

				closeModal: function(e) {
						if ( typeof e !== 'undefined' ) {
								e.preventDefault();
						}

						$('.registration-form-template-modal').hide();
						$('.registration-form-template-modal-backdrop').hide();
				}
		};

		$(function() {
				popup.init();
		});

})(jQuery);