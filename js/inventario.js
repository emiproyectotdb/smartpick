// inventario.js
const API_BASE = 'http://localhost/proyecto/smartpick/api/';

document.addEventListener('DOMContentLoaded', function() {
    checkSession();
    loadZones();
    loadInventory();
    
    // Búsqueda y filtros
    document.getElementById('searchProduct').addEventListener('input', filterInventory);
    document.getElementById('filterZone').addEventListener('change', filterInventory);
});

async function loadZones() {
    try {
        const response = await fetch(`${API_BASE}inventario.php?action=zones`);
        const data = await response.json();
        
        if (data.success) {
            const container = document.getElementById('zoneCards');
            container.innerHTML = data.data.map(zone => `
                <div class="col-md-4 mb-3">
                    <div class="zone-card ${zone.Nombre.toLowerCase()}">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">${zone.Nombre}</h6>
                            <span class="badge ${getStockBadge(zone.stock_percent)}">
                                ${zone.stock_percent}%
                            </span>
                        </div>
                        <p class="text-muted small mb-2">${zone.Descripcion || ''}</p>
                        <div class="stock-bar">
                            <div class="progress-fill ${getStockClass(zone.stock_percent)}" 
                                 style="width: ${zone.stock_percent}%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small>${zone.stock_actual || 0} productos</small>
                            <small>Capacidad: ${zone.capacidad || 100}</small>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error cargando zonas:', error);
    }
}

async function loadInventory() {
    try {
        const response = await fetch(`${API_BASE}inventario.php?action=products`);
        const data = await response.json();
        
        if (data.success) {
            window.inventoryData = data.data;
            renderInventory(data.data);
        }
    } catch (error) {
        console.error('Error cargando inventario:', error);
    }
}

function renderInventory(products) {
    const tbody = document.getElementById('inventoryTable');
    tbody.innerHTML = products.map(product => `
        <tr>
            <td>${product.ID_producto}</td>
            <td>${product.Descripcion || product.Nombre}</td>
            <td>${product.Tipo || '-'}</td>
            <td>${product.Categoria || '-'}</td>
            <td>
                <span class="fw-bold ${product.Stock_actual < product.Stock_minimo ? 'text-danger' : ''}">
                    ${product.Stock_actual}
                </span>
            </td>
            <td>${product.Stock_minimo}</td>
            <td>${product.ubicacion || '-'}</td>
            <td>
                <span class="badge ${product.Stock_actual < product.Stock_minimo ? 'bg-danger' : 'bg-success'}">
                    ${product.Stock_actual < product.Stock_minimo ? 'Bajo Stock' : 'Normal'}
                </span>
            </td>
        </tr>
    `).join('');
}

function filterInventory() {
    const search = document.getElementById('searchProduct').value.toLowerCase();
    const zone = document.getElementById('filterZone').value;
    
    if (!window.inventoryData) return;
    
    let filtered = window.inventoryData;
    
    if (search) {
        filtered = filtered.filter(p => 
            (p.Descripcion || '').toLowerCase().includes(search) ||
            (p.Tipo || '').toLowerCase().includes(search)
        );
    }
    
    if (zone) {
        filtered = filtered.filter(p => 
            (p.zona_nombre || '').toLowerCase() === zone.toLowerCase()
        );
    }
    
    renderInventory(filtered);
}

function getStockBadge(percent) {
    if (percent < 30) return 'bg-danger';
    if (percent < 60) return 'bg-warning';
    return 'bg-success';
}

function getStockClass(percent) {
    if (percent < 30) return 'low';
    if (percent < 60) return 'medium';
    return 'high';
}