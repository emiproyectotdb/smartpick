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

    <title>Dashboard | SmartPick</title>


    <!-- BOOTSTRAP ICONS -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- GOOGLE FONTS -->
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- CSS -->
    <link
        rel="stylesheet"
        href="/proyecto/smartpick/css/dashboard.css"
    >

</head>


<body>


<div class="dashboard">


    <!-- ==========================================
         SIDEBAR
    =========================================== -->

    <aside class="sidebar">


        <!-- LOGO -->

        <div class="sidebar-logo">

            <img
                src="/proyecto/smartpick/img/logoSinFondo.png"
                alt="SmartPick"
            >

        </div>



        <!-- NAVEGACIÓN -->

        <nav class="sidebar-menu">


            <a
                href="index.php?ruta=dashboard"
                class="menu-item activo"
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
                class="menu-item"
            >

                <i class="bi bi-clipboard-check"></i>

                <span>Pedidos</span>

            </a>



            <a
                href="#"
                class="menu-item"
            >

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



            <a
                href="#"
                class="menu-item"
            >

                <i class="bi bi-bar-chart"></i>

                <span>Reportes</span>

            </a>


        </nav>



        <!-- LOGOUT -->

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



    <!-- ==========================================
         CONTENIDO PRINCIPAL
    =========================================== -->

    <main class="main-content">


        <!-- HEADER -->

        <header class="topbar">


            <div class="titulo-dashboard">

                <h1>DASHBOARD</h1>

                <p>
                    Gestión general del centro logístico
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


                <i class="bi bi-chevron-down"></i>


            </div>


        </header>



        <!-- ======================================
             SALUDO
        ======================================= -->

        <section class="bienvenida">

            <div>

                <span class="etiqueta">
                    PANEL DE CONTROL
                </span>

                <h2>
                    Bienvenido,
                    <?= htmlspecialchars($usuario['nombre']) ?>
                </h2>

                <p>
                    Desde aquí podés supervisar las operaciones
                    generales de SmartPick.
                </p>

            </div>


            <div class="bienvenida-icono">

                <i class="bi bi-box-seam"></i>

            </div>

        </section>



        <!-- ======================================
             MÉTRICAS
        ======================================= -->

        <section class="metricas">


            <article class="tarjeta-metrica">

                <div class="metrica-icono">

                    <i class="bi bi-boxes"></i>

                </div>


                <div>

                    <span>Stock total</span>

                    <h3> <?= htmlspecialchars($stockTotal) ?> </h3>

                    <small>
                        Unidades almacenadas
                    </small>

                </div>

            </article>



            <article class="tarjeta-metrica">

                <div class="metrica-icono">

                    <i class="bi bi-clock-history"></i>

                </div>


                <div>

                    <span>Pedidos pendientes</span>

                    <h3> <?= htmlspecialchars($pedidosPendientes) ?> </h3>

                    <small>
                        Esperando asignación
                    </small>

                </div>

            </article>



            <article class="tarjeta-metrica">

                <div class="metrica-icono">

                    <i class="bi bi-person-workspace"></i>

                </div>


                <div>

                    <span>Operarios</span>

                    <h3> <?= htmlspecialchars($cantidadOperarios) ?> </h3>

                    <small>
                        Registrados en el sistema
                    </small>

                </div>

            </article>



            <article class="tarjeta-metrica">

                <div class="metrica-icono alerta">

                    <i class="bi bi-exclamation-triangle"></i>

                </div>


                <div>

                    <span>Stock bajo</span>

                    <h3> <?= htmlspecialchars($stockBajo) ?> </h3>

                    <small>
                        Productos a reponer
                    </small>

                </div>

            </article>


        </section>



        <!-- ======================================
             CONTENIDO INFERIOR
        ======================================= -->

        <section class="dashboard-grid">


            <!-- PEDIDOS -->

            <div class="panel-dashboard">


                <div class="panel-header">

                    <div>

                        <h3>PEDIDOS RECIENTES</h3>

                        <p>
                            Estado general de los últimos pedidos
                        </p>

                    </div>


                    <a href="#">
                        Ver todos
                    </a>

                </div>



                <div class="tabla-responsive">

                    <table>

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Ingreso</th>

                                <th>Estado</th>

                                <th>Acción</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php if (!empty($pedidosRecientes)): ?>

                            <?php foreach ($pedidosRecientes as $pedido): ?>

                            <tr>

                                <td>
                                    #<?= htmlspecialchars($pedido['ID_pedido']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pedido['Fecha_ingresado']) ?>
                                </td>

                                <td>

                                <?php

                                    $estado = strtolower($pedido['Estado']);

                                    $claseEstado = '';

                                    if ($estado === 'pendiente') {

                                        $claseEstado = 'estado-pendiente';

                                    } elseif ($estado === 'en proceso') {

                                        $claseEstado = 'estado-proceso';

                                    } elseif (
                                        $estado === 'completado' ||
                                        $estado === 'entregado'
                                    ) {

                                        $claseEstado = 'estado-completado';

                                    }

                                ?>

                                    <span class="estado <?= $claseEstado ?>">

                                        <?= htmlspecialchars($pedido['Estado']) ?>

                                    </span>

                                </td>


                                <td>

                                    <a
                                        href="#"
                                        class="btn-ver"
                                    >

                                    <i class="bi bi-eye"></i>

                                    Ver

                                    </a>

                                </td>

                            </tr>

                            <?php endforeach; ?>


                            <?php else: ?>

                            <tr>

                                <td
                                    colspan="4"
                                    class="tabla-vacia"
                                >

                                No existen pedidos registrados.

                                </td>

                            </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>


            </div>



            <!-- ACCIONES -->

            <div class="panel-dashboard acciones-panel">


                <div class="panel-header">

                    <div>

                        <h3>ACCIONES RÁPIDAS</h3>

                        <p>
                            Operaciones frecuentes
                        </p>

                    </div>

                </div>


                <div class="acciones">


                    <a
                        href="index.php?ruta=nuevo-pedido"
                        class="accion"
                    >

                        <i class="bi bi-plus-circle"></i>

                        <div>

                            <strong>
                                Nuevo pedido
                            </strong>

                            <span>
                                Generar orden
                            </span>

                        </div>

                    </a>



                    <a href="#" class="accion">

                        <i class="bi bi-box-seam"></i>

                        <div>

                            <strong>
                                Nuevo producto
                            </strong>

                            <span>
                                Agregar al catálogo
                            </span>

                        </div>

                    </a>



                    <a href="#" class="accion">

                        <i class="bi bi-geo-alt"></i>

                        <div>

                            <strong>
                                Gestionar zonas
                            </strong>

                            <span>
                                Configurar depósito
                            </span>

                        </div>

                    </a>



                    <a href="#" class="accion">

                        <i class="bi bi-person-plus"></i>

                        <div>

                            <strong>
                                Nuevo usuario
                            </strong>

                            <span>
                                Administrar operarios
                            </span>

                        </div>

                    </a>


                </div>


            </div>


        </section>


    </main>


</div>


</body>

</html>