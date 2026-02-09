( function( $ ) {
	/**
	* Search widget JS
	*/
	var WidgetBLOGFOELSearchButton = function( $scope, $ ){

		if ( 'undefined' == typeof $scope )
			return;
		const wId = $scope.data("id");
		const wrapper = document.querySelector(`.elementor-element-${wId}`);

		var $input = $scope.find( "input.blogfoel_search_input" );
		var $clear = $scope.find( "button#clear" );
		var $clear_with_button = $scope.find( "button#clear-with-button" );
		var $search_button = $scope.find( ".blogfoel-search-submit" );
		var $toggle_search = $scope.find( ".blogfoel-search-icon-toggle input" );

		$scope.find( '.search-btn i.fa-search' ).on( 'click', function( ){
			$scope.find( ".blogfoel-search-wrapper" ).toggleClass( "blogfoel-input-focus" );					
		});

		$scope.find('.blogfoel-search-btn').on('click', function (event) {
			event.preventDefault();
			const searchForm = $scope.find('.blogfoel-search-from');
			
			// Toggle the class
			searchForm.toggleClass('show-search');
		
			// Check if the class is present
			if (searchForm.hasClass('show-search')) {
				// Remove any existing event listener before adding a new one
				$(document).on('click.searchClose', function (e) {
					if (!$(e.target).closest('.blogfoel-search-btn, .blogfoel-search-from').length) {
						searchForm.removeClass('show-search');
						$(document).off('click.searchClose'); // Remove the event listener
					}
				});
			} else {
				$(document).off('click.searchClose'); // Ensure the event is removed if closed manually
			}
		
			// Prevent the click event from bubbling up to window
			event.stopPropagation();
		});
		
		$toggle_search.css( 'padding-right', $toggle_search.next().outerWidth() + 'px' );

		$input.on( 'keyup', function(){
			$clear.style = (this.value.length) ? $clear.css('visibility','visible'): $clear.css('visibility','hidden');
			$clear_with_button.style = (this.value.length) ? $clear_with_button.css('visibility','visible'): $clear_with_button.css('visibility','hidden');
			$clear_with_button.css( 'right', $search_button.outerWidth() + 'px' );
		});

		$clear.on("click",function(){
			this.style = $clear.css('visibility','hidden');
			$input.value = "";
		});
		$clear_with_button.on("click",function(){
			this.style = $clear_with_button.css('visibility','hidden');
			$input.value = "";
		});
  
		const searchInput = wrapper.querySelector('#blogfoel-search-input');
		const suggestionsContainer = wrapper.querySelector('#blogfoel-suggestions-container');

		searchInput.addEventListener('input', function() {
			const inputText = searchInput.value.trim().toLowerCase();
			console.log(inputText);
            var thisEl = $(this);
            var data = {action: 'search_site', search_item: inputText};
        
            jQuery.ajax({
                url: myajax.ajaxurl,
                type: "POST",
                data: data,
                success: function (response) {
                    var suggestions = JSON.parse(response.data);
					if (inputText === '') {
						suggestionsContainer.innerHTML = "";
						return;
					}
			
					const filteredSuggestions = suggestions.filter(suggestion =>
						suggestion.title.toLowerCase().includes(inputText)
					);
                    console.log(suggestions);

			
					displaySuggestions(filteredSuggestions);
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                    console.log('woo search ajax error');
                },
            });
		});

		function displaySuggestions(suggestionsList) {
			suggestionsContainer.innerHTML = '';
	
			if (suggestionsList.length === 0) {
				suggestionsContainer.innerHTML = '<div class="blogfoel-no-results"><p>No results found</p></div>';
				suggestionsContainer.style.display = 'block';
				return;
			}
	
			suggestionsList.forEach(suggestion => {
				const suggestionElement = document.createElement('div');
				suggestionElement.className = 'search-suggestion'; 
				// Create title anchor tag
				const titleAnchorElement = document.createElement('a');
				titleAnchorElement.href = suggestion.url; 
				titleAnchorElement.target = '_blank'; 
		
				// Create title element
				const titleElement = document.createElement('p');
				titleAnchorElement.textContent = suggestion.title;
				titleElement.appendChild(titleAnchorElement);
				suggestionElement.appendChild(titleElement);
		
				suggestionElement.addEventListener('click', function() {
					searchInput.value = suggestion.title;
					suggestionsContainer.style.display = 'none';
				});
		
				suggestionsContainer.appendChild(suggestionElement);
			});
	
			suggestionsContainer.style.display = 'block';
		}
	
		document.addEventListener('click', function(event) {
			if (event.target !== searchInput && event.target !== suggestionsContainer) {
				suggestionsContainer.style.display = 'none';
			}
		});

	}

    $( window ).on( 'elementor/frontend/init', function () {

		elementorFrontend.hooks.addAction( 'frontend/element_ready/blognews-search.default', WidgetBLOGFOELSearchButton );
	});
} )( jQuery );