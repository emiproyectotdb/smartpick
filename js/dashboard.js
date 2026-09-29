// dashboard.js
const API_BASE = 'http://localhost/proyecto/smartpick/api/';

document.addEventListener('DOMContentLoaded', function() {
    checkSession();
    loadDashboardStats();
    loadRecentOrders();
});

async function loadDashboardStats() {
    try {
        const response = await fetch(`${API_BASE}dashboard.php?action=stats`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('totalPedidos').textContent = data.data.total_pedidos || 0;
            document.getElementById('pedidosCompletados').textContent = data.data.pedidos_completados || 0;
            document.getElementById('pedidosPendientes').textContent = data.data.pedidos_pendientes || 0;
            document.getElementById('productosEnZona').textContent = data.data.productos_en_zona || 0;
        }
    } catch (error) {
        console.error('Error cargando estadísticas:', error);
    }
}

async function loadRecentOrders() {
    try {
        const response = await fetch(`${API_BASE}dashboard.php?action=recent_orders`);
        const data = await response.json();
        
        if (data.success) {
            const tbody = document.getElementById('recentOrders');
            tbody.innerHTML = data.data.map(order => `
                <tr>
                    <td>#${order.ID_pedido}</td>
                    <td>${order.cliente_nombre || 'Cliente'}</td>
                    <td>${formatDate(order.Fecha_ingresado)}</td>
                    <td>
                        <span class="badge ${getStatusBadge(order.Estado)}">
                            ${order.Estado}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="viewOrder(${order.ID_pedido})">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }
    } catch (error) {
        console.error('Error cargando pedidos recientes:', error);
    }
}

function getStatusBadge(status) {
    switch(status?.toLowerCase()) {
        case 'completado':
            return 'bg-success';
        case 'pendiente':
            return 'bg-warning';
        case 'en progreso':
            return 'bg-info';
        default:
            return 'bg-secondary';
    }
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function viewOrder(id) {
    alert(`Ver pedido #${id} (funcionalidad en desarrollo)`);
}