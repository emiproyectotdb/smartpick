// auth.js
// Función para verificar sesión
function checkSession() {
    const user = JSON.parse(sessionStorage.getItem('user'));
    if (!user) {
        // Si no hay sesión y no estamos en login, redirigir
        if (!window.location.pathname.includes('Login.html') && !window.location.pathname.includes('index.html')) {
            window.location.href = 'login.html';
        }
        return null;
    }
    
    // Actualizar UI si hay usuario
    updateUserUI(user);
    return user;
}

// Función para actualizar la interfaz con datos del usuario
function updateUserUI(user) {
    // Actualizar nombre en elementos con id userName
    document.querySelectorAll('#userName').forEach(el => {
        if (el) el.textContent = user.Nombre_completo || user.nombre_completo;
    });
    
    // Actualizar avatar
    document.querySelectorAll('#userAvatar').forEach(el => {
        if (el) {
            if (user.foto_perfil) {
                el.innerHTML = `<img src="${user.foto_perfil}" alt="Avatar" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">`;
            } else {
                el.innerHTML = `<i class="bi bi-person-fill"></i>`;
            }
        }
    });
}

// Función para cerrar sesión
function logout() {
    sessionStorage.removeItem('user');
    window.location.href = 'login.html';
}

// Verificar sesión al cargar cualquier página
document.addEventListener('DOMContentLoaded', function() {
    checkSession();
});