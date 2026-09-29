<?php

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?ruta=login");
    exit;
}

$usuario = $_SESSION['usuario'];

$mensaje =
    $_SESSION['mensaje_producto'] ?? null;

$error =
    $_SESSION['error_producto'] ?? null;

unset($_SESSION['mensaje_producto']);
unset($_SESSION['error_producto']);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Productos | SmartPick</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="css/dashboard.css"
    >

    <link
        rel="stylesheet"
        href="css/productos.css"
    >

</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="sidebar-logo">

            <img
                src="img/logoSinFondo.png"
                alt="SmartPick"
            >

        </div>


        <nav class="sidebar-menu">

            <a
                href="index.php?ruta=dashboard"
                class="menu-item"
            >
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>


            <a
                href="index.php?ruta=productos"
                class="menu-item activo"
            >
                <i class="bi bi-box-seam"></i>
                <span>Productos</span>
            </a>


            <a href="index.php?ruta=zonas" class="menu-item active">
    <i class="bi bi-geo-alt"></i>
    <span>Zonas</span>
</a>


            <?php if (
    strtolower($_SESSION['usuario']['rol'])
    === 'administrador'
): ?>

    <a
        href="index.php?ruta=inventario"
        class="menu-item"
    >
        <i class="bi bi-boxes"></i>
        <span>Inventario</span>
    </a>

<?php endif; ?>


            <a
                href="index.php?ruta=clientes"
                class="menu-item"
            >
                <i class="bi bi-person-vcard"></i>
                <span>Clientes</span>
            </a>


            <a
                href="index.php?ruta=pedidos"
                class="menu-item"
            >
                <i class="bi bi-clipboard-check"></i>
                <span>Pedidos</span>
            </a>


            <a href="#" class="menu-item">
                <i class="bi bi-people"></i>
                <span>Operarios</span>
            </a>


            <a
                href="index.php?ruta=picking"
                class="menu-item"
            >
                <i class="bi bi-signpost-2"></i>
                <span>Picking</span>
            </a>


            <a href="#" class="menu-item">
                <i class="bi bi-bar-chart"></i>
                <span>Reportes</span>
            </a>

        </nav>


        <div class="sidebar-footer">

            <a
                href="index.php?ruta=logout"
                class="menu-item cerrar-sesion"
            >
                <i class="bi bi-box-arrow-left"></i>
                <span>Cerrar sesión</span>
            </a>

        </div>

    </aside>


    <!-- CONTENIDO -->

    <main class="main-content">

        <header class="topbar">

            <div class="titulo-dashboard">

                <h1>PRODUCTOS</h1>

                <p>
                    Gestión del catálogo de productos
                </p>

            </div>


            <div class="usuario-header">

                <div class="usuario-icono">
                    <i class="bi bi-person"></i>
                </div>

                <div class="usuario-info">

                    <strong>
                        <?= htmlspecialchars($usuario['nombre']) ?>
                    </strong>

                    <span>
                        <?= htmlspecialchars($usuario['rol']) ?>
                    </span>

                </div>

            </div>

        </header>


        <section class="productos-contenido">


            <?php if ($mensaje): ?>

                <div class="mensaje-exito">

                    <i class="bi bi-check-circle"></i>

                    <?= htmlspecialchars($mensaje) ?>

                </div>

            <?php endif; ?>


            <?php if ($error): ?>

                <div class="mensaje-error">

                    <i class="bi bi-exclamation-circle"></i>

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>


            <!-- CABECERA -->

            <div class="productos-cabecera">

                <div>

                    <span class="etiqueta-seccion">
                        CATÁLOGO
                    </span>

                    <h2>
                        PRODUCTOS DE ALMACÉN
                    </h2>

                    <p>
                        Administrá los productos disponibles,
                        su stock y ubicación.
                    </p>

                </div>


                <div class="acciones-productos">

    <a
        href="index.php?ruta=nuevo-producto"
        class="btn-nuevo-producto"
    >
        <i class="bi bi-plus-lg"></i>
        NUEVO PRODUCTO
    </a>


    <button
        type="button"
        class="btn-modificar-producto"
        id="btnModificarProducto"
        disabled
    >
        <i class="bi bi-pencil"></i>
        MODIFICAR PRODUCTO
    </button>

</div>

            </div>


            <!-- BUSCADOR -->

            <div class="productos-filtros">

                <div class="buscador">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        id="buscarProducto"
                        placeholder="Buscar por producto, categoría o ubicación..."
                    >

                </div>

            </div>


            <!-- TABLA -->

            <div class="panel-productos">

                <div class="tabla-responsive">

                    <table class="tabla-productos">

                        <thead>

    <tr>

        <th></th>
        <th>ID</th>
        <th>PRODUCTO</th>
        <th>CATEGORÍA</th>
        <th>STOCK</th>
        <th>STOCK MÍN.</th>
        <th>UBICACIÓN</th>
        <th>ESTADO</th>

    </tr>

</thead>


                        <tbody>

                        <?php if (!empty($productos)): ?>

                            <?php foreach ($productos as $producto): ?>

                                <?php

                                $stockActual =
                                    (int)$producto['Stock_actual'];

                                $stockMinimo =
                                    (int)$producto['Stock_minimo'];

                                $stockBajo =
                                    $stockActual <= $stockMinimo;


                                $ubicacion =
                                    $producto['Zona'] ?? '';


                                if (!empty($producto['Nivel'])) {

                                    $ubicacion .=
                                        ' / Nivel '
                                        . $producto['Nivel'];
                                }


                                if (!empty($producto['Estante'])) {

                                    $ubicacion .=
                                        ' / Estante '
                                        . $producto['Estante'];
                                }


                                if (trim($ubicacion) === '') {
                                    $ubicacion = 'Sin ubicación';
                                }


                                $textoBusqueda =
                                    strtolower(
                                        $producto['Descripcion']
                                        . ' '
                                        . ($producto['Categoria'] ?? '')
                                        . ' '
                                        . $ubicacion
                                    );

                                ?>


                                <tr
    class="fila-producto"
    data-busqueda="<?= htmlspecialchars($textoBusqueda) ?>"
>

    <!-- SELECCIONAR PRODUCTO -->
    <td>

        <input
            type="radio"
            name="producto_seleccionado"
            class="seleccionar-producto"
            value="<?= (int)$producto['ID_producto'] ?>"
        >

    </td>


    <!-- ID -->
    <td>

        <strong>
            #<?= (int)$producto['ID_producto'] ?>
        </strong>

    </td>


                                    <td>

                                        <div class="nombre-producto">

                                            <div class="producto-icono">
                                                <i class="bi bi-box-seam"></i>
                                            </div>

                                            <strong>
                                                <?= htmlspecialchars(
                                                    $producto['Descripcion']
                                                ) ?>
                                            </strong>

                                        </div>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $producto['Categoria'] ?: '-'
                                        ) ?>

                                    </td>


                                    <td>

                                        <strong
                                            class="<?=
                                                $stockBajo
                                                    ? 'stock-bajo-texto'
                                                    : ''
                                            ?>"
                                        >
                                            <?= $stockActual ?>
                                        </strong>

                                    </td>


                                    <td>
                                        <?= $stockMinimo ?>
                                    </td>


                                    <td>

                                        <span class="ubicacion-producto">

                                            <i class="bi bi-geo-alt"></i>

                                            <?= htmlspecialchars(
                                                $ubicacion
                                            ) ?>

                                        </span>

                                    </td>


                                    <td>

                                        <?php if ($stockBajo): ?>

                                            <span class="estado-stock bajo">

                                                <i class="bi bi-exclamation-triangle"></i>

                                                STOCK BAJO

                                            </span>

                                        <?php else: ?>

                                            <span class="estado-stock normal">

                                                <i class="bi bi-check-circle"></i>

                                                NORMAL

                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>


                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="8"
                                    class="tabla-vacia"
                                >

                                    <i class="bi bi-box-seam"></i>

                                    <p>
                                        No hay productos de almacén registrados.
                                    </p>

                                </td>

                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

    </main>

</div>


<script>

const buscador =
    document.getElementById('buscarProducto');


buscador.addEventListener(
    'input',
    function () {

        const texto =
            this.value
                .trim()
                .toLowerCase();

        const filas =
            document.querySelectorAll(
                '.fila-producto'
            );


        filas.forEach(fila => {

            const datos =
                fila.dataset.busqueda;

            fila.style.display =
                datos.includes(texto)
                    ? ''
                    : 'none';

        });

    }
);

const productosSeleccionables =
    document.querySelectorAll(
        '.seleccionar-producto'
    );

const btnModificar =
    document.getElementById(
        'btnModificarProducto'
    );


productosSeleccionables.forEach(
    radio => {

        radio.addEventListener(
            'change',
            function () {

                btnModificar.disabled = false;

                btnModificar.dataset.id =
                    this.value;

            }
        );

    }
);


btnModificar.addEventListener(
    'click',
    function () {

        const id =
            this.dataset.id;

        if (!id) {
            return;
        }

        window.location.href =
            'index.php?ruta=modificar-producto&id='
            + encodeURIComponent(id);

    }
);

</script>

</body>

</html>