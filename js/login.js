// js/login.js
const API_BASE = 'http://localhost/proyecto/smartpick/api/';

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault(); // Evita que el formulario se envíe de forma tradicional
            
            // Obtener valores
            const usuario = document.getElementById('usuario').value.trim();
            const password = document.getElementById('password').value.trim();
            const errorMsg = document.getElementById('errorMsg');
            
            // Limpiar mensaje de error
            if (errorMsg) errorMsg.textContent = '';
            
            // Validar campos
            if (!usuario || !password) {
                if (errorMsg) errorMsg.textContent = 'Por favor, complete todos los campos';
                return;
            }
            
            try {
                console.log('Enviando login...', { usuario, password });
                
                const response = await fetch(`${API_BASE}login.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ usuario, password })
                });
                
                const data = await response.json();
                console.log('Respuesta del servidor:', data);
                
                if (data.success) {
                    // Guardar sesión
                    sessionStorage.setItem('user', JSON.stringify(data.user));
                    
                    // Redirigir según el rol
                    const rol = data.user.rol || 'Cliente';
                    console.log('Rol del usuario:', rol);
                    
                    if (rol === 'Operario' || rol === 'Administrador' || rol === 'admin') {
                        window.location.href = 'dashboard_operador.html';
                    } else {
                        window.location.href = 'index.html';
                    }
                } else {
                    if (errorMsg) {
                        errorMsg.textContent = data.message || 'Error al iniciar sesión';
                    }
                }
            } catch (error) {
                console.error('Error de conexión:', error);
                if (errorMsg) {
                    errorMsg.textContent = 'Error de conexión con el servidor. Asegúrate de que el servidor esté funcionando.';
                }
            }
        });
    } else {
        console.error('Formulario de login no encontrado');
    }
});