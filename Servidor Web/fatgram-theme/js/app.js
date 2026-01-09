document.addEventListener('DOMContentLoaded', function() {
    const editBtn = document.getElementById('edit-profile-btn');
    const editFormContainer = document.getElementById('edit-profile-form');
    const cancelBtn = document.getElementById('cancel-edit');
    const form = document.getElementById('fatgram-edit-form');

    if (editBtn) {
        // Mostrar formulario
        editBtn.addEventListener('click', () => {
            editFormContainer.style.display = 'block';
            editBtn.style.display = 'none';
        });

        // Cancelar
        cancelBtn.addEventListener('click', () => {
            editFormContainer.style.display = 'none';
            editBtn.style.display = 'inline-block';
        });

        // Enviar datos
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('action', 'edit_user_profile');
            formData.append('first_name', document.getElementById('edit-first-name').value);
            formData.append('last_name', document.getElementById('edit-last-name').value);
            formData.append('description', document.getElementById('edit-description').value);

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Actualizar textos en la página sin recargar
                    document.getElementById('display-name-text').innerText = data.data.new_name;
                    document.getElementById('profile-bio').innerText = data.data.new_bio;
                    
                    // Ocultar formulario
                    editFormContainer.style.display = 'none';
                    editBtn.style.display = 'inline-block';
                    alert('¡Perfil actualizado!');
                }
            });
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const editBtn = document.getElementById('edit-profile-btn');
    const formContainer = document.getElementById('edit-profile-form-container');
    const cancelBtn = document.getElementById('cancel-edit');
    const editForm = document.getElementById('fatgram-edit-form');

    // Verificación de que los elementos existen
    if (editBtn && formContainer) {
        
        editBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log("Botón Editar pulsado"); // Esto aparecerá en la consola (F12)
            formContainer.style.display = 'block';
            editBtn.style.display = 'none';
        });

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                formContainer.style.display = 'none';
                editBtn.style.display = 'inline-block';
            });
        }

        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData();
                formData.append('action', 'edit_user_profile');
                formData.append('first_name', document.getElementById('edit-first-name').value);
                formData.append('last_name', document.getElementById('edit-last-name').value);
                formData.append('description', document.getElementById('edit-description').value);

                // fatgram_data es la variable que configuramos en functions.php
                fetch(fatgram_data.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("¡Perfil actualizado!");
                        location.reload(); 
                    } else {
                        alert("Error: " + data.data);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.querySelector('.profile-dropdown');
    const trigger = document.querySelector('.dropdown-trigger');

    if (trigger) {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('is-active');
            
            // Si usas esta clase, añade en CSS: .profile-dropdown.is-active .dropdown-content { display: block; }
        });
    }

    // Cerrar si se hace clic fuera
    document.addEventListener('click', function() {
        if(dropdown) dropdown.classList.remove('is-active');
    });
});