<?php

if (!isset($_SESSION['usuario'])) {

    header("Location: index.php?ruta=login");
    exit;
}


$usuario =
    $_SESSION['usuario'];

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Picking | SmartPick
    </title>


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
        href="css/picking.css"
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
        href="index.php?ruta=productos"
        class="menu-item"
    >
        <i class="bi bi-box-seam"></i>
        <span>Productos</span>
    </a>

<?php endif; ?>

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

<a href="index.php?ruta=zonas" class="menu-item active">
    <i class="bi bi-geo-alt"></i>
    <span>Zonas</span>
</a>

            <a
                href="index.php?ruta=pedidos"
                class="menu-item"
            >

                <i class="bi bi-clipboard-check"></i>

                <span>Pedidos</span>

            </a>


            <a
                href="index.php?ruta=picking"
                class="menu-item activo"
            >

                <i class="bi bi-signpost-split"></i>

                <span>Picking</span>

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

                <h1>
                    PICKING
                </h1>

                <p>
                    Pedidos asignados para preparación
                </p>

            </div>


            <div class="usuario-header">


                <div class="usuario-icono">

                    <i class="bi bi-person"></i>

                </div>


                <div class="usuario-info">

                    <strong>

                        <?= htmlspecialchars(
                            $usuario['nombre']
                        ) ?>

                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $usuario['rol']
                        ) ?>

                    </span>

                </div>


            </div>


        </header>



        <section class="picking-contenido">


            <div class="encabezado-picking">


                <div>

                    <span>
                        OPERACIÓN
                    </span>

                    <h2>
                        PEDIDOS PARA PREPARAR
                    </h2>

                    <p>
                        Seleccione un pedido para visualizar
                        la ruta de picking y las ubicaciones.
                    </p>

                </div>


            </div>



            <?php if (empty($pedidosPicking)): ?>


                <div class="sin-picking">


                    <i
                        class="bi bi-check2-circle"
                    ></i>


                    <h3>
                        NO HAY PEDIDOS PENDIENTES
                    </h3>


                    <p>
                        No hay pedidos asignados
                        para preparar.
                    </p>


                </div>


            <?php else: ?>


                <div class="grid-pedidos">


                    <?php foreach ($pedidosPicking as $pedido): ?>


                        <article class="pedido-picking">


                            <div class="pedido-icono">

                                <i
                                    class="bi bi-box-seam"
                                ></i>

                            </div>



                            <div class="pedido-info">

                                <span>
                                    PEDIDO
                                </span>


                                <h3>

                                    #<?= $pedido['ID_pedido'] ?>

                                </h3>


                                <p>

                                    <?= htmlspecialchars(
                                        $pedido['Cliente']
                                    ) ?>

                                </p>


                                <small>

                                    Ingreso:

                                    <?= date(
                                        'd/m/Y',
                                        strtotime(
                                            $pedido['Fecha_ingresado']
                                        )
                                    ) ?>

                                </small>

                            </div>



                            <div class="pedido-estado">

                                PREPARANDO

                            </div>



                            <a
                                href="index.php?ruta=hoja-picking&id=<?= $pedido['ID_pedido'] ?>"
                                class="btn-iniciar"
                            >

                                <i
                                    class="bi bi-signpost-2"
                                ></i>

                                INICIAR PICKING

                            </a>


                        </article>


                    <?php endforeach; ?>


                </div>


            <?php endif; ?>


        </section>


    </main>


</div>


</body>

</html>