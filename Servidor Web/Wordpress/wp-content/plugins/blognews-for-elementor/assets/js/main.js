(function ($) {
	'use strict';
	// Show Auto Complete display
	$( 'body' ).on(
		'change',
		'.opt-display-on',
		function(e) {
			var btn      = $( this );
			var postType = $( this ).val();
			var data     = {
				action: 'blognews_pt_update',
				token: admin.nonce,
				post_type1: postType,
			};

			$.ajax(
				{
					type: 'POST',
					url: admin.url,
					data: data,
					beforeSend: function (response) {
						$( '.head-foot-metabox' ).addClass( 'loading' );
					},
					success: function (response) {
						$( '.head-foot-metabox' ).removeClass( 'loading' );
						btn.parents( '.main_cls' ).find( '.posttype_val' ).html( response );
					},
					error: function(errorThrown){
						alert('ajax error');
					},
				}
				
			);
			
		}
	);

function formatState(state) {
    if (!state.id) {
        return state.text;
    }
    var $state = $(
        '<span>' + state.text + '</span>' 
        );
    return $state;
};  

$(document).ready(function() {
    $(".custome-dropdown").each(function() { 
        var _this = $(this);
        var jQuerypl = _this.attr("data-placeholder");
        _this.select2({
            dropdownParent: _this.closest("div"), 
            placeholder: jQuerypl, 
            minimumResultsForSearch: Infinity, 
            allowClear: true, 
            templateSelection: formatState,
            theme: "classic" 
        });
    }).on('select2:opening select2:closing', function(event) {
        var $searchfield = $(this).parent().find('.select2-search__field');
        $searchfield.prop('disabled', true);
    }); 

});
$('#id').val(null).trigger('change');

} )( jQuery );