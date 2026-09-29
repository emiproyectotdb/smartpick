// perfil.js
const API_BASE = 'http://localhost/proyecto/smartpick/api/';

document.addEventListener('DOMContentLoaded', function() {
    checkSession();
    loadProfile();
    setupProfileForm();
    setupPhotoUpload();
});

async function loadProfile() {
    const user = JSON.parse(sessionStorage.getItem('user'));
    if (!user) return;
    
    try {
        const response = await fetch(`${API_BASE}perfil.php?ci=${user.CI || user.ci}`);
        const data = await response.json();
        
        if (data.success) {
            const profile = data.data;
            
            // Llenar formulario
            document.getElementById('profileCI').value = profile.CI;
            document.getElementById('profileFullName').value = profile.Nombre_completo;
            document.getElementById('profilePhone').value = profile.Telefono || '';
            document.getElementById('profileEmail').value = profile.Mail || '';
            document.getElementById('profileAddress').value = profile.Direccion || '';
            document.getElementById('profileName').textContent = profile.Nombre_completo;
            document.getElementById('profileRole').textContent = profile.rol;
            
            // Foto de perfil
            if (profile.foto_perfil) {
                document.getElementById('profileImage').src = profile.foto_perfil;
            }
        }
    } catch (error) {
        console.error('Error cargando perfil:', error);
    }
}

function setupProfileForm() {
    document.getElementById('profileForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const user = JSON.parse(sessionStorage.getItem('user'));
        const formData = {
            CI: document.getElementById('profileCI').value,
            Nombre_completo: document.getElementById('profileFullName').value,
            Telefono: document.getElementById('profilePhone').value,
            Mail: document.getElementById('profileEmail').value,
            Direccion: document.getElementById('profileAddress').value
        };
        
        try {
            const response = await fetch(`${API_BASE}perfil.php`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Actualizar sesión
                const updatedUser = { ...user, ...formData };
                sessionStorage.setItem('user', JSON.stringify(updatedUser));
                
                alert('Perfil actualizado correctamente');
                loadProfile();
            } else {
                alert(data.message || 'Error al actualizar perfil');
            }
        } catch (error) {
            console.error('Error actualizando perfil:', error);
            alert('Error al conectar con el servidor');
        }
    });
}

function setupPhotoUpload() {
    document.getElementById('uploadPhoto').addEventListener('change', async function(e) {
        const file = this.files[0];
        if (!file) return;
        
        const user = JSON.parse(sessionStorage.getItem('user'));
        const formData = new FormData();
        formData.append('foto', file);
        formData.append('CI', user.CI || user.ci);
        
        try {
            const response = await fetch(`${API_BASE}perfil.php`, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Foto actualizada correctamente');
                loadProfile();
                updateUserUI({ ...user, foto_perfil: data.foto_url });
            } else {
                alert(data.message || 'Error al actualizar foto');
            }
        } catch (error) {
            console.error('Error subiendo foto:', error);
            alert('Error al conectar con el servidor');
        }
    });
}