
// Creta Testimonial Showcase plugin activation
document.addEventListener('DOMContentLoaded', function () {
    const healthy_food_blogger_button = document.getElementById('install-activate-button');

    if (!healthy_food_blogger_button) return;

    healthy_food_blogger_button.addEventListener('click', function (e) {
        e.preventDefault();

        const healthy_food_blogger_redirectUrl = healthy_food_blogger_button.getAttribute('data-redirect');

        // Step 1: Check if plugin is already active
        const healthy_food_blogger_checkData = new FormData();
        healthy_food_blogger_checkData.append('action', 'check_creta_testimonial_activation');

        fetch(installcretatestimonialData.ajaxurl, {
            method: 'POST',
            body: healthy_food_blogger_checkData,
        })
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data.active) {
                // Plugin is already active → just redirect
                window.location.href = healthy_food_blogger_redirectUrl;
            } else {
                // Not active → proceed with install + activate
                healthy_food_blogger_button.textContent = 'Nevigate Getstart';

                const healthy_food_blogger_installData = new FormData();
                healthy_food_blogger_installData.append('action', 'install_and_activate_creta_testimonial_plugin');
                healthy_food_blogger_installData.append('_ajax_nonce', installcretatestimonialData.nonce);

                fetch(installcretatestimonialData.ajaxurl, {
                    method: 'POST',
                    body: healthy_food_blogger_installData,
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        window.location.href = healthy_food_blogger_redirectUrl;
                    } else {
                        alert('Activation error: ' + (res.data?.message || 'Unknown error'));
                        healthy_food_blogger_button.textContent = 'Try Again';
                    }
                })
                .catch(error => {
                    alert('Request failed: ' + error.message);
                    healthy_food_blogger_button.textContent = 'Try Again';
                });
            }
        })
        .catch(error => {
            alert('Check request failed: ' + error.message);
        });
    });
});
