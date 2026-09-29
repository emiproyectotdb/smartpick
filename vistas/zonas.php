<?php

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?ruta=login");
    exit;
}

$usuario = $_SESSION['usuario'];

$esAdministrador =
    strtolower($usuario['rol']) === 'administrador';


    $zonaSeleccionada = null;

if ($zonaDestino > 0) {

    foreach ($zonas as $zona) {

        if ((int)$zona['ID_zona'] === $zonaDestino) {
            $zonaSeleccionada = $zona;
            break;
        }

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

    <title>Mapa del depósito | SmartPick</title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <link
        rel="stylesheet"
        href="css/dashboard.css"
    >

    <link
        rel="stylesheet"
        href="css/zonas.css"
    >

</head>

<body>

<div class="dashboard-layout">

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



            <a href="index.php?ruta=zonas" class="menu-item activo">
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


    <!-- CONTENIDO -->
   <main class="main-content mapa-contenido">

    <!-- CABECERA -->
    <div class="mapa-header">

        <div>
            <span class="mapa-etiqueta">
                CENTRO LOGÍSTICO
            </span>

            <h1>MAPA DEL DEPÓSITO</h1>

            <p>
                Ubicación de zonas, estanterías y recorrido
                operativo dentro del depósito.
            </p>
        </div>

        <div class="deposito-activo">
            <span></span>
            DEPÓSITO OPERATIVO
        </div>

    </div>

<?php if ($zonaSeleccionada): ?>

    <div class="guia-picking">

        <div class="guia-picking-icono">
            <i class="bi bi-geo-alt-fill"></i>
        </div>

        <div class="guia-picking-destino">

            <span>DESTINO DE PICKING</span>

            <strong>
                <?= htmlspecialchars($zonaSeleccionada['Nombre']) ?>
            </strong>

            <small>
                Estante <?= htmlspecialchars($zonaSeleccionada['Estante']) ?>
                ·
                <?= htmlspecialchars($zonaSeleccionada['Nivel']) ?>
            </small>

        </div>

        <div class="guia-picking-ruta">

            <span>RUTA SUGERIDA</span>

            <div>
                <b>Recepción</b>

                <i class="bi bi-chevron-right"></i>

                <b>Pasillo principal</b>

                <i class="bi bi-chevron-right"></i>

                <b>
                    <?= htmlspecialchars($zonaSeleccionada['Nombre']) ?>
                </b>
            </div>

        </div>

    </div>

<?php endif; ?>

    <!-- PLANO -->
    <section class="plano-deposito">

        <!-- RECEPCIÓN -->
        <div class="area-recepcion">

            <i class="bi bi-truck"></i>

            <span>RECEPCIÓN</span>

            <small>
                Ingreso de mercadería
            </small>

            <div class="flecha-recepcion">
                <i class="bi bi-arrow-down"></i>
            </div>

            <?php if ($zonaSeleccionada): ?>

    <div class="marca-inicio">
        <i class="bi bi-person-walking"></i>
        INICIO
    </div>

<?php endif; ?>

        </div>


        <!-- PASILLO VERTICAL IZQUIERDO -->
        <div class="pasillo-vertical pasillo-izquierdo">

            <span></span>

            <i class="bi bi-arrow-down"></i>

            <span></span>

        </div>


        <!-- ========================
             ZONAS
        ========================= -->

        <div class="zona-plano zona-a
            <?= $zonaDestino === 1 ? 'zona-objetivo' : '' ?>">

            <div class="zona-titulo">
                <span>ZONA A</span>

                <?php if ($zonaDestino === 1): ?>
                    <b>DESTINO</b>
                <?php endif; ?>
            </div>

            <strong>ALIMENTOS</strong>

            <div class="estanterias">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <small>A1 · Nivel 1</small>

        </div>


        <div class="zona-plano zona-b
            <?= $zonaDestino === 2 ? 'zona-objetivo' : '' ?>">

            <div class="zona-titulo">
                <span>ZONA B</span>

                <?php if ($zonaDestino === 2): ?>
                    <b>DESTINO</b>
                <?php endif; ?>
            </div>

            <strong>BEBIDAS</strong>

            <div class="estanterias">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <small>B1 · Nivel 1</small>

        </div>


        <div class="zona-plano zona-c
            <?= $zonaDestino === 3 ? 'zona-objetivo' : '' ?>">

            <div class="zona-titulo">
                <span>ZONA C</span>

                <?php if ($zonaDestino === 3): ?>
                    <b>DESTINO</b>
                <?php endif; ?>
            </div>

            <strong>CONGELADOS</strong>

            <div class="estanterias">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <small>C1 · Nivel 1</small>

        </div>


        <!-- PASILLO CENTRAL -->
        <div class="pasillo-principal
    <?= $zonaSeleccionada ? 'pasillo-activo' : '' ?>">

            <span class="linea-pasillo"></span>

            <div>
                <i class="bi bi-arrow-right"></i>
                PASILLO PRINCIPAL
            </div>

            <span class="linea-pasillo"></span>

        </div>

        


        <div class="zona-plano zona-d
            <?= $zonaDestino === 4 ? 'zona-objetivo' : '' ?>">

            <div class="zona-titulo">
                <span>ZONA D</span>

                <?php if ($zonaDestino === 4): ?>
                    <b>DESTINO</b>
                <?php endif; ?>
            </div>

            <strong>REFRIGERADOS</strong>

            <div class="estanterias">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <small>D1 · Nivel 1</small>

        </div>


        <div class="zona-plano zona-e
            <?= $zonaDestino === 5 ? 'zona-objetivo' : '' ?>">

            <div class="zona-titulo">
                <span>ZONA E</span>

                <?php if ($zonaDestino === 5): ?>
                    <b>DESTINO</b>
                <?php endif; ?>
            </div>

            <strong>FRÁGILES</strong>

            <div class="estanterias">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <small>E1 · Nivel 2</small>

        </div>


        <div class="zona-plano zona-f
            <?= $zonaDestino === 6 ? 'zona-objetivo' : '' ?>">

            <div class="zona-titulo">
                <span>ZONA F</span>

                <?php if ($zonaDestino === 6): ?>
                    <b>DESTINO</b>
                <?php endif; ?>
            </div>

            <strong>VOLUMINOSOS</strong>

            <div class="estanterias">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <small>F1 · Nivel 1</small>

        </div>


        <!-- ZONA G -->
        <div class="zona-plano zona-g
            <?= $zonaDestino === 7 ? 'zona-objetivo' : '' ?>">

            <div class="zona-titulo">
                <span>ZONA G</span>

                <?php if ($zonaDestino === 7): ?>
                    <b>DESTINO</b>
                <?php endif; ?>
            </div>

            <strong>DEPÓSITO GENERAL</strong>

            <div class="estanterias estanterias-g">
                <span></span>
                <span></span>
            </div>

            <small>G1 · Nivel 2</small>

        </div>


        <!-- ÁREA DE MANIOBRAS -->
        <div class="area-maniobras">

            <i class="bi bi-box-seam"></i>

            <div>
                <strong>ÁREA DE MANIOBRAS</strong>
                <span>Circulación interna</span>
            </div>

        </div>


        <!-- DESPACHO -->
        <div class="area-despacho">

            <i class="bi bi-truck-flatbed"></i>

            <span>DESPACHO</span>

            <small>
                Salida de mercadería
            </small>

            <div class="flecha-despacho">
                <i class="bi bi-arrow-down"></i>
            </div>

        </div>

    </section>


    <!-- LEYENDA -->
    <div class="leyenda-mapa">

        <div>
            <span class="leyenda-color color-a"></span>
            Zona A · Alimentos
        </div>

        <div>
            <span class="leyenda-color color-b"></span>
            Zona B · Bebidas
        </div>

        <div>
            <span class="leyenda-color color-c"></span>
            Zona C · Congelados
        </div>

        <div>
            <span class="leyenda-color color-d"></span>
            Zona D · Refrigerados
        </div>

        <div>
            <span class="leyenda-color color-e"></span>
            Zona E · Frágiles
        </div>

        <div>
            <span class="leyenda-color color-f"></span>
            Zona F · Voluminosos
        </div>

        <div>
            <span class="leyenda-color color-g"></span>
            Zona G · General
        </div>

    </div>

</main>

</div>

</body>
</html>