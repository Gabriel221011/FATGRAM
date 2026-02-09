(function ($) {
  $(document).ready(function () {
    $('.newseblog-btn-get-started').on('click', function (e) {
      e.preventDefault();
      if (!newseblog_ajax_object.can_install) {
        alert('Sorry, you are not allowed to access this page.');
        return;
      }
      var $btn = $(this);
      $btn.html('Processing.. Please wait').addClass('updating-message');
      $.post(newseblog_ajax_object.ajax_url, {
        action: 'install_act_plugin',
        security: newseblog_ajax_object.install_nonce
      }, function (response) {
        if (response.success) {
          window.location.href = 'admin.php?page=blognews_admin_menu&tab=starter-sites';
        } else {
          alert(response.data?.message || 'Installation failed.');
          $btn.html('Try Again').removeClass('updating-message');
        }
      }).fail(function () {
        alert('Something went wrong. Please try again.');
        $btn.html('Try Again').removeClass('updating-message');
      });
    });
  });
$(document).on('click', '.notice-get-started-class .notice-dismiss', function () {
  var type = $(this).closest('.notice-get-started-class').data('notice');
  $.post(ajaxurl, {
    action: 'newseblog_dismissed_notice_handler',
    type: type,
  });
});
})(jQuery);