<?php

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?ruta=login");
    exit;
}

$usuario = $_SESSION['usuario'];

$mensaje =
    $_SESSION['mensaje_inventario'] ?? null;

$error =
    $_SESSION['error_inventario'] ?? null;

unset($_SESSION['mensaje_inventario']);
unset($_SESSION['error_inventario']);

$totalUnidades = 0;
$productosBajoStock = 0;

$totalPaquetes =
    count($paquetesDeposito);

foreach ($inventario as $item) {

    $totalUnidades +=
        (int)$item['Stock_actual'];

    if (
        (int)$item['Stock_actual']
        <=
        (int)$item['Stock_minimo']
    ) {
        $productosBajoStock++;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Inventario | SmartPick</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/inventario.css">

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
                class="menu-item"
            >
                <i class="bi bi-box-seam"></i>
                <span>Productos</span>
            </a>


            <a href="index.php?ruta=zonas" class="menu-item active">
    <i class="bi bi-geo-alt"></i>
    <span>Zonas</span>
</a>


            <a
                href="index.php?ruta=inventario"
                class="menu-item activo"
            >
                <i class="bi bi-boxes"></i>
                <span>Inventario</span>
            </a>


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

                <h1>INVENTARIO</h1>

                <p>
                    Control de mercadería almacenada
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


        <section class="inventario-contenido">


            <!-- MENSAJES -->

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


            <!-- ENCABEZADO -->

            <div class="inventario-cabecera">

                <div>

                    <span class="etiqueta-seccion">
                        CONTROL DE STOCK
                    </span>

                    <h2>
                        INVENTARIO DE ALMACÉN
                    </h2>

                    <p>
                        Consultá existencias, alertas y ubicación
                        de los productos almacenados.
                    </p>

                </div>


                <a
                    href="index.php?ruta=ingresar-paquete"
                    class="btn-ingresar-paquete"
                >

                    <i class="bi bi-box-arrow-in-down"></i>

                    INGRESAR PAQUETE

                </a>

            </div>


            <!-- RESUMEN -->

            <div class="resumen-inventario">


                <div class="tarjeta-resumen">

                    <div class="resumen-icono">

                        <i class="bi bi-boxes"></i>

                    </div>

                    <div>

                        <span>
                            UNIDADES DE ALMACÉN
                        </span>

                        <strong>
                            <?= $totalUnidades ?>
                        </strong>

                    </div>

                </div>


                <div class="tarjeta-resumen">

                    <div class="resumen-icono">

                        <i class="bi bi-box-seam"></i>

                    </div>

                    <div>

                        <span>
                            PRODUCTOS
                        </span>

                        <strong>
                            <?= count($inventario) ?>
                        </strong>

                    </div>

                </div>


                <div class="tarjeta-resumen">

                    <div
                        class="resumen-icono
                        <?= $productosBajoStock > 0
                            ? 'alerta'
                            : ''
                        ?>"
                    >

                        <i class="bi bi-exclamation-triangle"></i>

                    </div>

                    <div>

                        <span>
                            STOCK BAJO
                        </span>

                        <strong>
                            <?= $productosBajoStock ?>
                        </strong>

                    </div>

                </div>

                <div class="tarjeta-resumen">

    <div class="resumen-icono">

        <i class="bi bi-box2"></i>

    </div>

    <div>

        <span>
            PAQUETES EN DEPÓSITO
        </span>

        <strong>
            <?= $totalPaquetes ?>
        </strong>

    </div>

</div>

            </div>


            <!-- BUSCADOR -->

            <div class="inventario-filtros">

                <div class="buscador-inventario">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        id="buscarInventario"
                        placeholder="Buscar producto, categoría o ubicación..."
                    >

                </div>


                <select id="filtroStock">

                    <option value="todos">
                        Todos
                    </option>

                    <option value="normal">
                        Stock normal
                    </option>

                    <option value="bajo">
                        Stock bajo
                    </option>

                </select>

            </div>


            <!-- TABLA -->

            <div class="panel-inventario">

                <div class="panel-titulo">

                    <div>

                        <span>EXISTENCIAS</span>

                        <h3>
                            PRODUCTOS DE ALMACÉN
                        </h3>

                    </div>

                </div>


                <div class="tabla-responsive">

                    <table class="tabla-inventario">

                        <thead>

                            <tr>

                                <th>PRODUCTO</th>
                                <th>CATEGORÍA</th>
                                <th>STOCK</th>
                                <th>MÍNIMO</th>
                                <th>UBICACIÓN</th>
                                <th>ESTADO</th>
                                <th>ACCIÓN</th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php if (!empty($inventario)): ?>

                            <?php foreach ($inventario as $item): ?>

                                <?php

                                $stock =
                                    (int)$item['Stock_actual'];

                                $minimo =
                                    (int)$item['Stock_minimo'];

                                $stockBajo =
                                    $stock <= $minimo;


                                $ubicacion =
                                    $item['Zona'] ?? '';

                                if (!empty($item['Nivel'])) {

                                    $ubicacion .=
                                        ' / Nivel '
                                        . $item['Nivel'];
                                }

                                if (!empty($item['Estante'])) {

                                    $ubicacion .=
                                        ' / Estante '
                                        . $item['Estante'];
                                }

                                if (trim($ubicacion) === '') {
                                    $ubicacion = 'Sin ubicación';
                                }


                                $busqueda =
                                    strtolower(
                                        $item['Descripcion']
                                        . ' '
                                        . ($item['Categoria'] ?? '')
                                        . ' '
                                        . $ubicacion
                                    );

                                ?>


                                <tr
                                    class="fila-inventario"
                                    data-busqueda="<?=
                                        htmlspecialchars($busqueda)
                                    ?>"
                                    data-stock="<?=
                                        $stockBajo
                                            ? 'bajo'
                                            : 'normal'
                                    ?>"
                                >


                                    <td>

                                        <div class="producto-inventario">

                                            <div class="producto-icono">

                                                <i class="bi bi-box-seam"></i>

                                            </div>

                                            <div>

                                                <strong>
                                                    <?= htmlspecialchars(
                                                        $item['Descripcion']
                                                    ) ?>
                                                </strong>

                                                <span>
                                                    #<?= (int)$item['ID_producto'] ?>
                                                </span>

                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $item['Categoria'] ?: '-'
                                        ) ?>

                                    </td>


                                    <td>

                                        <strong
                                            class="cantidad-stock
                                            <?= $stockBajo
                                                ? 'stock-alerta'
                                                : ''
                                            ?>"
                                        >
                                            <?= $stock ?>
                                        </strong>

                                    </td>


                                    <td>
                                        <?= $minimo ?>
                                    </td>


                                    <td>

                                        <span class="ubicacion">

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


                                    <td>

                                        <a
                                            href="index.php?ruta=reponer-stock&id=<?=
                                                (int)$item['ID_producto']
                                            ?>"
                                            class="btn-reponer"
                                        >

                                            <i class="bi bi-plus-circle"></i>

                                            REPONER

                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>


                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="7"
                                    class="inventario-vacio"
                                >

                                    No hay productos registrados.

                                </td>

                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- ===================================== -->
<!-- PAQUETES EN DEPÓSITO -->
<!-- ===================================== -->

<div class="panel-inventario panel-paquetes">

    <div class="panel-titulo panel-titulo-paquetes">

        <div>

            <span>ALMACENAMIENTO DE CLIENTES</span>

            <h3>
                PAQUETES EN DEPÓSITO
            </h3>

        </div>


        <div class="cantidad-paquetes">

            <i class="bi bi-box2"></i>

            <?= $totalPaquetes ?>
            almacenados

        </div>

    </div>


    <div class="filtro-paquetes">

        <div class="buscador-inventario">

            <i class="bi bi-search"></i>

            <input
                type="text"
                id="buscarPaquete"
                placeholder="Buscar paquete, cliente, CI o ubicación..."
            >

        </div>


        <select id="filtroPaqueteEstado">

            <option value="todos">
                Todos los estados
            </option>

            <option value="ingresado">
                Ingresado
            </option>

            <option value="almacenado">
                Almacenado
            </option>

            <option value="pendiente_retiro">
                Pendiente de retiro
            </option>

            <option value="preparando">
                Preparando
            </option>

            <option value="listo_para_validar">
                Listo para validar
            </option>

        </select>

    </div>


    <div class="tabla-responsive">

        <table class="tabla-inventario tabla-paquetes">

            <thead>

                <tr>

                    <th>PAQUETE</th>
                    <th>CLIENTE</th>
                    <th>CATEGORÍA</th>
                    <th>UBICACIÓN</th>
                    <th>INGRESO</th>
                    <th>ESTADO</th>
                    <th>PEDIDO</th>

                </tr>

            </thead>


            <tbody>

            <?php if (!empty($paquetesDeposito)): ?>

                <?php foreach ($paquetesDeposito as $paquete): ?>

                    <?php

                    $estado =
                        strtolower(
                            $paquete['Estado'] ?? ''
                        );


                    switch ($estado) {

                        case 'ingresado':

                            $estadoTexto =
                                'INGRESADO';

                            $estadoClase =
                                'ingresado';

                            break;


                        case 'almacenado':

                            $estadoTexto =
                                'ALMACENADO';

                            $estadoClase =
                                'almacenado';

                            break;


                        case 'pendiente_retiro':

                            $estadoTexto =
                                'PENDIENTE RETIRO';

                            $estadoClase =
                                'pendiente';

                            break;


                        case 'preparando':

                            $estadoTexto =
                                'PREPARANDO';

                            $estadoClase =
                                'preparando';

                            break;


                        case 'listo_para_validar':

                            $estadoTexto =
                                'LISTO PARA VALIDAR';

                            $estadoClase =
                                'validar';

                            break;


                        default:

                            $estadoTexto =
                                strtoupper(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $estado
                                    )
                                );

                            $estadoClase =
                                'ingresado';
                    }


                    $ubicacionPaquete =
                        $paquete['Zona'] ?? '';


                    if (!empty($paquete['Nivel'])) {

                        $ubicacionPaquete .=
                            ' / Nivel '
                            . $paquete['Nivel'];
                    }


                    if (!empty($paquete['Estante'])) {

                        $ubicacionPaquete .=
                            ' / Estante '
                            . $paquete['Estante'];
                    }


                    if (
                        trim($ubicacionPaquete)
                        === ''
                    ) {

                        $ubicacionPaquete =
                            'Sin ubicación';
                    }


                    $textoPaquete =
                        strtolower(
                            ($paquete['Descripcion'] ?? '')
                            . ' '
                            . ($paquete['Cliente'] ?? '')
                            . ' '
                            . ($paquete['CI'] ?? '')
                            . ' '
                            . ($paquete['Categoria'] ?? '')
                            . ' '
                            . $ubicacionPaquete
                        );

                    ?>


                    <tr
                        class="fila-paquete"
                        data-busqueda="<?=
                            htmlspecialchars(
                                $textoPaquete
                            )
                        ?>"
                        data-estado="<?=
                            htmlspecialchars(
                                $estado
                            )
                        ?>"
                    >


                        <!-- PAQUETE -->

                        <td>

                            <div class="producto-inventario">

                                <div class="producto-icono paquete-icono">

                                    <i class="bi bi-box2"></i>

                                </div>


                                <div>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $paquete['Descripcion']
                                        ) ?>

                                    </strong>

                                    <span>

                                        Producto
                                        #<?= (int)$paquete['ID_producto'] ?>

                                    </span>

                                </div>

                            </div>

                        </td>


                        <!-- CLIENTE -->

                        <td>

                            <div class="cliente-paquete">

                                <strong>

                                    <?= htmlspecialchars(
                                        $paquete['Cliente']
                                    ) ?>

                                </strong>

                                <span>

                                    CI
                                    <?= htmlspecialchars(
                                        $paquete['CI']
                                    ) ?>

                                </span>

                            </div>

                        </td>


                        <!-- CATEGORÍA -->

                        <td>

                            <?= htmlspecialchars(
                                $paquete['Categoria']
                                ?: '-'
                            ) ?>

                        </td>


                        <!-- UBICACIÓN -->

                        <td>

                            <span class="ubicacion">

                                <i class="bi bi-geo-alt"></i>

                                <?= htmlspecialchars(
                                    $ubicacionPaquete
                                ) ?>

                            </span>

                        </td>


                        <!-- FECHA -->

                        <td>

                            <?php

                            if (
                                !empty(
                                    $paquete['Fecha_ingresado']
                                )
                            ) {

                                echo htmlspecialchars(
                                    date(
                                        'd/m/Y',
                                        strtotime(
                                            $paquete['Fecha_ingresado']
                                        )
                                    )
                                );

                            } else {

                                echo '-';
                            }

                            ?>

                        </td>


                        <!-- ESTADO -->

                        <td>

                            <span
                                class="estado-paquete
                                <?= $estadoClase ?>"
                            >

                                <?= htmlspecialchars(
                                    $estadoTexto
                                ) ?>

                            </span>

                        </td>


                        <!-- PEDIDO -->

                        <td>

                            <a
                                href="index.php?ruta=ver-pedido&id=<?=
                                    (int)$paquete['ID_pedido']
                                ?>"
                                class="ver-pedido-inventario"
                                title="Ver pedido"
                            >

                                <i class="bi bi-eye"></i>

                                #<?= (int)$paquete['ID_pedido'] ?>

                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>


            <?php else: ?>

                <tr>

                    <td
                        colspan="7"
                        class="inventario-vacio"
                    >

                        <i class="bi bi-box2"></i>

                        No hay paquetes almacenados actualmente.

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

const buscarInventario =
    document.getElementById('buscarInventario');

const filtroStock =
    document.getElementById('filtroStock');


function filtrarInventario() {

    const texto =
        buscarInventario.value
            .trim()
            .toLowerCase();

    const estado =
        filtroStock.value;


    document
        .querySelectorAll('.fila-inventario')
        .forEach(fila => {

            const coincideTexto =
                fila.dataset.busqueda
                    .includes(texto);

            const coincideEstado =
                estado === 'todos'
                ||
                fila.dataset.stock === estado;


            fila.style.display =
                coincideTexto && coincideEstado
                    ? ''
                    : 'none';

        });

}


buscarInventario.addEventListener(
    'input',
    filtrarInventario
);

filtroStock.addEventListener(
    'change',
    filtrarInventario
);

const buscarPaquete =
    document.getElementById('buscarPaquete');

const filtroPaqueteEstado =
    document.getElementById('filtroPaqueteEstado');


function filtrarPaquetes() {

    const texto =
        buscarPaquete.value
            .trim()
            .toLowerCase();

    const estado =
        filtroPaqueteEstado.value;


    document
        .querySelectorAll('.fila-paquete')
        .forEach(fila => {

            const coincideTexto =
                fila.dataset.busqueda
                    .includes(texto);

            const coincideEstado =
                estado === 'todos'
                ||
                fila.dataset.estado === estado;


            fila.style.display =
                coincideTexto && coincideEstado
                    ? ''
                    : 'none';

        });
}


buscarPaquete.addEventListener(
    'input',
    filtrarPaquetes
);

filtroPaqueteEstado.addEventListener(
    'change',
    filtrarPaquetes
);

</script>

</body>

</html>