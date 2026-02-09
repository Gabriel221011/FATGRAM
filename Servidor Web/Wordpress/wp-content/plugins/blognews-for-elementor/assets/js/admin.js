jQuery( document ).ready( function( $ ) {
    // jQuery( '.colorpicker' ).wpColorPicker();
    const buttons = document.querySelectorAll('.blogfoel-builder-tab-button');
    const rows = document.querySelectorAll('.blogfoel-template-type');

    buttons.forEach(button => {
      button.addEventListener('click', () => {
        // Remove active class from all buttons
        buttons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        const type = button.getAttribute('temp-type');
        var pagename = type.split("-");
        pagename = pagename.map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        if (type === 'all') {
            document.querySelector('.blogfoel-site-builder-create-template').setAttribute("temp-type", '');
            document.querySelector('.blogfoel-site-builder-create-template-head-foot').setAttribute("temp-type", '');
            document.querySelector('.blogfoel-site-builder-create-template').classList.add('hidden');
            document.querySelector('.blogfoel-site-builder-create-template-head-foot').classList.add('hidden');
            document.querySelector('.blogfoel-heading').textContent = "All Template";
        } else {
            document.querySelector('.blogfoel-heading').textContent = pagename;
            if(type == 'header' || type == 'footer'){
                document.querySelector('.blogfoel-site-builder-create-template-head-foot').classList.remove('hidden');
                document.querySelector('.blogfoel-site-builder-create-template-head-foot').setAttribute("temp-type", type);
                document.querySelector('.blogfoel-site-builder-create-template').classList.add('hidden');
                document.querySelector('.blogfoel-site-builder-create-template').setAttribute("temp-type", '');
            }else{
                document.querySelector('.blogfoel-site-builder-create-template').classList.remove('hidden');
                document.querySelector('.blogfoel-site-builder-create-template').setAttribute("temp-type", type);
                document.querySelector('.blogfoel-site-builder-create-template-head-foot').classList.add('hidden');
                document.querySelector('.blogfoel-site-builder-create-template-head-foot').setAttribute("temp-type", '');
            }
        }
        rows.forEach(row => {
          if (type === 'all' || row.getAttribute('temp-type') === type) {
            row.classList.remove('hidden');
          } else {
            row.classList.add('hidden');
          }
        });
      });
    });
    
    const openPopup = document.getElementById('openPopup');
    const closePopup = document.getElementById('closePopup');
    const overlay = document.getElementById('overlay');
    const popup = document.getElementById('popup');
    const confirmButton = document.getElementById('confirmButton');

    // Show popup
    openPopup.addEventListener('click', () => {
        popup.style.display = 'block';
        overlay.style.display = 'block';
    });

    // Hide popup
    closePopup.addEventListener('click', () => {
        popup.style.display = 'none';
        overlay.style.display = 'none';
    });

    overlay.addEventListener('click', () => {
        popup.style.display = 'none';
        overlay.style.display = 'none';
    });

    // Confirm action
    confirmButton.addEventListener('click', () => {
        const selectedOption = document.getElementById('templateType').value;
        if (selectedOption) {
            console.log(`You selected: ${selectedOption}`);
            popup.style.display = 'none';
            overlay.style.display = 'none';
        } else {
            console.log('Please select a template type!');
        }
    });

	$( 'body' ).on(
		'click',
		'.blogfoel-site-builder-create-template, #confirmButton',
		function(e) {
			var $this      = $( this );
            if($this.attr('id') == 'confirmButton'){
                var temp_type = $('.blogfoel-site-builder-create-template-head-foot').attr('temp-type');
                var show_type = $this.closest('#popup').find('#templateType').val();
                console.log(show_type);
                var data     = {
                    action: 'blogfoel_create_temp_type',
                    blogfoel_admin_nonce: admin_ajax_obj.nonce,
                    temp_type: temp_type,
                    show_type: show_type,
                };
            }else{
                var temp_type = $this.attr('temp-type');
                
                var data     = {
                    action: 'blogfoel_create_temp_type',
                    blogfoel_admin_nonce: admin_ajax_obj.nonce,
                    temp_type: temp_type,
                };
            }

			$.ajax(
				{
					type: 'POST',
					url: blogfoel_admin.ajax_url,
					data: data,
					beforeSend: function (response) {
                        // console.log(response);
					},
					success: function (response) {
                        console.log(response);
                        window.open(response.data.edit_link, "_self");
                        // window.location.href = response.data.edit_link;
						// $( '.head-foot-metabox' ).removeClass( 'loading' );
						// btn.parents( '.main_cls' ).find( '.posttype_val' ).html( response );
					},
					error: function(errorThrown){
						alert('ajax error');
					},
				}
				
			);
			
		}
	);

	$( 'body' ).on(
		'click',
		'.delete-temp',
		function(e) {
            
            if (confirm("Are you sure you want to permanently delete this template?")) {
                var $this      = $( this );
                var parent = $this.closest('.blogfoel-template-type');
                var post_id = $this.attr('pid');
                
                var data     = {
                    action: 'blogfoel_actions',
                    blogfoel_admin_nonce: admin_ajax_obj.nonce,
                    post_id: post_id,
                };
    
                $.ajax(
                	{
                		type: 'POST',
                		url: admin_ajax_obj.ajax_url,
                		data: data,
                		beforeSend: function (response) {
                            // console.log(response);
                		},
                		success: function (response) {
                            console.log(response);
                            parent.remove();
                            // window.location.href = response.data.edit_link;
                			// $( '.head-foot-metabox' ).removeClass( 'loading' );
                			// btn.parents( '.main_cls' ).find( '.posttype_val' ).html( response );
                		},
                		error: function(errorThrown){
                			alert('ajax error');
                		},
                	}
                    
                );
            } else {
                console.log("You clicked No!");
            }
				
		}
	);

    var Radio1 = document.getElementById("radio-1");
    var Radio2 = document.getElementById("radio-2");
  
    Radio1.addEventListener("change", function() {
      var checked = this.checked;
      var otherCheckboxes = document.querySelectorAll(".blogfoel-widget-switches .toggleable");
      [].forEach.call(otherCheckboxes, function(item) {
        item.checked = checked;
      });
    });
  
    Radio2.addEventListener("change", function() {
      var checked = this.checked;
      var otherCheckboxes = document.querySelectorAll(".blogfoel-widget-switches .toggleable");
      [].forEach.call(otherCheckboxes, function(item) {
        item.checked = false;
      });
    });
    
} );
// search box for tab two
document.addEventListener("DOMContentLoaded", () => {
  const searchBoxTab2 = document.querySelector(".blogfoel-tab-content .search");
  const itemsTab2 = document.querySelectorAll(".blogfoel-tab-content .blogfoel-admin-widget, .blogfoel-tab-content .heading");

  if (!searchBoxTab2) return;

  searchBoxTab2.addEventListener("keyup", (e) => {
    const searchFilter = e.target.value;
    const pat = new RegExp(searchFilter, 'i');
    
    itemsTab2.forEach((item) => {
      if (pat.test(item.innerText)) {
        item.style.display = "block";
      } else {
        item.style.display = "none";
      }
    });
  });
});

jQuery(document).ready(function($) {
  $(document).on('click', '.ins-ansar-imp', function(e) {
    e.preventDefault();
    var $this = $(this);

    // Prefer using data attributes to avoid conflicts with browser attributes
    var plugin = $this.data('plugin') || $this.attr('plug');
    var status = $this.data('status') || $this.attr('status');

    if (!plugin) {
      console.error('No plugin slug found on the clicked element.');
      return;
    }

    if (status === 'active') {
      console.log('Plugin is already active.');
      return;
    }

    // Prepare UI
    $this.prop('disabled', true);
    var originalText = $this.text();
    var actionText = (status === 'not-installed') ? 'Installing...' : (status === 'not-active' ? 'Activating...' : 'Processing...');
    $this.text(actionText);
    var loading = $('<span class="ins-ansar-imp-spin"><i class="dashicons dashicons-image-rotate spinning"></i></span>');
    $this.append(loading);

    $.ajax({
      url: admin_ajax_obj.ajax_url,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'admin_install_plug',
        plugin_name: plugin,
        blogfoel_admin_nonce: admin_ajax_obj.nonce
      },
      success: function(resp) {
        console.log('AJAX success:', resp);
        if (resp.success) {
          // optionally show notice
          // reload to reflect plugin state
          location.reload();
        } else {
          alert('Error: ' + (resp.data || 'Unknown error'));
          $this.prop('disabled', false).text(originalText);
          loading.remove();
        }
      },
      error: function(xhr, status, error) {
        console.error('AJAX error:', xhr.responseText || error);
        alert('AJAX error: ' + error);
        $this.prop('disabled', false).text(originalText);
        loading.remove();
      }
    });
  });
});