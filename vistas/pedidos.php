<?php

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?ruta=login");
    exit;
}

$usuario = $_SESSION['usuario'];

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Pedidos | SmartPick</title>

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
        href="/proyecto/smartpick/css/dashboard.css"
    >

    <link
        rel="stylesheet"
        href="/proyecto/smartpick/css/pedidos.css"
    >

</head>

<body>


<div class="dashboard">


    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="sidebar-logo">

            <img
                src="/proyecto/smartpick/img/logoSinFondo.png"
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

            <?php if (
    strtolower($_SESSION['usuario']['rol'])
    === 'administrador'
): ?>

    <a
        href="index.php?ruta=clientes"
        class="menu-item"
    >
        <i class="bi bi-person-vcard"></i>
        <span>Clientes</span>
    </a>

<?php endif; ?>

<?php if (
    strtolower($_SESSION['usuario']['rol'])
    === 'administrador'
): ?>

    <a
        href="index.php?ruta=ingresar-paquete"
        class="menu-item"
    >
        <i class="bi bi-box-arrow-in-down"></i>
        <span>Ingresar paquete</span>
    </a>

<?php endif; ?>

            <?php if (
    strtolower($_SESSION['usuario']['rol'])
    === 'administrador'
): ?>

    <a
        href="index.php?ruta=productos"
        class="menu-item"
    >
        <i class="bi bi-box-seam"></i>
        <span>Productos</span>
    </a>

<?php endif; ?>


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
                href="index.php?ruta=pedidos"
                class="menu-item activo"
            >

                <i class="bi bi-clipboard-check"></i>

                <span>Pedidos</span>

            </a>


            <a href="#" class="menu-item">

                <i class="bi bi-people"></i>

                <span>Operarios</span>

            </a>


            <a href="index.php?ruta=picking" class="menu-item">

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

                <h1>PEDIDOS</h1>

                <p>
                    Gestión de órdenes y preparación de mercadería
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



        <section class="pedidos-contenido">


            <div class="pedidos-cabecera">

                <div>

                    <span class="etiqueta-seccion">
                        GESTIÓN LOGÍSTICA
                    </span>

                    <h2>
                        LISTADO DE PEDIDOS
                    </h2>

                    <p>
                        Consultá los pedidos registrados y su estado actual.
                    </p>

                </div>


                <a
                    href="index.php?ruta=nuevo-pedido"
                    class="btn-nuevo-pedido"
                >

                    <i class="bi bi-plus-lg"></i>

                    NUEVO PEDIDO

                </a>

            </div>



            <!-- FILTROS -->

            <div class="pedidos-filtros">


                <div class="buscador">

                    <i class="bi bi-search"></i>

                    <input
                        type="text" id="buscarPedido"
                        placeholder="Buscar pedido..."
                    >

                </div>


                <select id="filtroEstado">
                    <option value="">Todos los estados</option>

                    <option value="pendiente">
                        Pendiente
                    </option>

                    <option value="preparando">
                        Preparando
                    </option>

                    <option value="entregado">
                        Entregado
                    </option>

                    <option value="ingresado">
                        Ingresado
                    </option>

                    <option value="almacenado">
                        Almacenado
                    </option>

                    <option value="retirado">
                        Retirado
                    </option>

                    <option value="listo_para_validar">
                        Listo para validar
                    </option>

                    <option value="PENDIENTE_RETIRO">
                        Pendiente de retiro
                    </option>
                </select>


            </div>



            <!-- TABLA -->

            <div class="panel-pedidos">

                <div class="tabla-responsive">

                    <table class="tabla-pedidos">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>FECHA DE INGRESO</th>

                                <th>ESTADO</th>

                                <th>FECHA DE ENTREGA</th>

                                <th>ACCIONES</th>

                            </tr>

                        </thead>


                        <tbody>

<?php if (!empty($pedidos)): ?>


    <?php foreach ($pedidos as $pedido): ?>


        <tr
    class="fila-pedido"
    data-id="<?= $pedido['ID_pedido'] ?>"
    data-estado="<?= strtolower(htmlspecialchars($pedido['Estado'])) ?>"
    data-fecha="<?= htmlspecialchars($pedido['Fecha_ingresado']) ?>"
>


            <!-- ID -->

            <td>

                <strong>
                    #<?= htmlspecialchars($pedido['ID_pedido']) ?>
                </strong>

            </td>



            <!-- FECHA INGRESO -->

            <td>

                <?php

                if (!empty($pedido['Fecha_ingresado'])) {

                    echo date(
                        'd/m/Y',
                        strtotime($pedido['Fecha_ingresado'])
                    );

                } else {

                    echo '-';

                }

                ?>

            </td>



            <!-- ESTADO -->

            <td>

                <?php

                $estado = strtolower($pedido['Estado']);

                $claseEstado = 'estado-pendiente';


                if ($estado === 'preparando') {

                    $claseEstado = 'estado-proceso';

                } elseif ($estado === 'entregado') {

                    $claseEstado = 'estado-entregado';

                } elseif ($estado === 'retirado') {

                    $claseEstado = 'estado-retirado';

                } elseif ($estado === 'almacenado') {

                    $claseEstado = 'estado-almacenado';

                } elseif ($estado === 'ingresado') {

                    $claseEstado = 'estado-ingresado';
                }elseif ($estado === 'listo_para_validar') {

                    $claseEstado = 'estado-validar';
                }

                ?>


                <span
                    class="estado <?= $claseEstado ?>"
                >

                    <?php

$estadoTexto =
    $pedido['Estado'];


if (
    strtoupper($estadoTexto)
    === 'LISTO_PARA_VALIDAR'
) {

    $estadoTexto =
        'LISTO PARA VALIDAR';
}

?>

<span class="<?= $claseEstado ?>">

    <?= htmlspecialchars($estadoTexto) ?>

</span>

                </span>

            </td>



            <!-- FECHA ENTREGA -->

            <td>

                <?php

                if (!empty($pedido['Fecha_entregado'])) {

                    echo date(
                        'd/m/Y',
                        strtotime($pedido['Fecha_entregado'])
                    );

                } else {

                    echo '-';

                }

                ?>

            </td>



            <!-- ACCIONES -->

            <td>


                <div class="acciones-tabla">


                    <a
                        href="index.php?ruta=ver-pedido&id=<?= $pedido['ID_pedido'] ?>"
                        class="btn-accion btn-ver"
                    >
                        <i class="bi bi-eye"></i>
                    </a>


                    <?php if (
                        $usuario['rol'] === 'Administrador'
                    ): ?>


                        <a
                            href="index.php?ruta=editar-pedido&id=<?= $pedido['ID_pedido'] ?>"
                            class="btn-accion btn-editar"
                            title="Editar pedido"
                        >

                            <i class="bi bi-pencil"></i>

                        </a>


                    <?php endif; ?>


                </div>


            </td>


        </tr>


    <?php endforeach; ?>


<?php else: ?>


    <tr>

        <td
            colspan="5"
            class="tabla-vacia"
        >

            <i class="bi bi-inbox"></i>

            <p>
                No hay pedidos registrados.
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

const buscarPedido =
    document.getElementById('buscarPedido');

const filtroEstado =
    document.getElementById('filtroEstado');


function filtrarPedidos()
{
    const texto =
        buscarPedido.value
            .trim()
            .toLowerCase();


    const estadoSeleccionado =
        filtroEstado.value
            .toLowerCase();


    const filas =
        document.querySelectorAll(
            '.fila-pedido'
        );


    filas.forEach(fila => {

        const id =
            fila.dataset.id
                .toLowerCase();

        const estado =
            fila.dataset.estado
                .toLowerCase();

        const fecha =
            fila.dataset.fecha
                .toLowerCase();


        const coincideBusqueda =
            texto === ''
            ||
            id.includes(texto)
            ||
            ('#' + id).includes(texto)
            ||
            estado.includes(texto)
            ||
            fecha.includes(texto);


        const coincideEstado =
            estadoSeleccionado === ''
            ||
            estado === estadoSeleccionado;


        if (
            coincideBusqueda
            &&
            coincideEstado
        ) {

            fila.style.display = '';

        } else {

            fila.style.display = 'none';
        }

    });
}


buscarPedido.addEventListener(
    'input',
    filtrarPedidos
);


filtroEstado.addEventListener(
    'change',
    filtrarPedidos
);

</script>

</body>

</html>