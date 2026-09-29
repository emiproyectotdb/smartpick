<?php

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?ruta=login");
    exit;
}

$usuario = $_SESSION['usuario'];

$error =
    $_SESSION['error_inventario'] ?? null;

unset($_SESSION['error_inventario']);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Reponer stock | SmartPick</title>

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


    <main class="main-content">

        <header class="topbar">

            <div class="titulo-dashboard">

                <h1>REPONER STOCK</h1>

                <p>
                    Ingreso de nuevas unidades al inventario
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


            <a
                href="index.php?ruta=inventario"
                class="volver-inventario"
            >

                <i class="bi bi-arrow-left"></i>

                Volver al inventario

            </a>


            <?php if ($error): ?>

                <div class="mensaje-error">

                    <i class="bi bi-exclamation-circle"></i>

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>


            <div class="formulario-reposicion">


                <div class="reposicion-encabezado">

                    <div class="reposicion-icono">

                        <i class="bi bi-box-arrow-in-down"></i>

                    </div>


                    <div>

                        <span>
                            REPOSICIÓN DE MERCADERÍA
                        </span>

                        <h2>
                            <?= htmlspecialchars(
                                $producto['Descripcion']
                            ) ?>
                        </h2>

                        <p>
                            Producto #<?= (int)$producto['ID_producto'] ?>
                        </p>

                    </div>

                </div>


                <!-- STOCK ACTUAL -->

                <div class="stock-actual-reposicion">

                    <span>
                        STOCK ACTUAL
                    </span>

                    <strong id="stockActual">
                        <?= (int)$producto['Stock_actual'] ?>
                    </strong>

                    <small>
                        unidades disponibles
                    </small>

                </div>


                <form
                    action="index.php?ruta=reponer-stock"
                    method="POST"
                >

                    <input
                        type="hidden"
                        name="id_producto"
                        value="<?= (int)$producto['ID_producto'] ?>"
                    >


                    <div class="campo-reposicion">

                        <label for="cantidad">
                            CANTIDAD RECIBIDA *
                        </label>

                        <input
                            type="number"
                            name="cantidad"
                            id="cantidad"
                            min="1"
                            step="1"
                            placeholder="Ej: 20"
                            required
                        >

                        <small>
                            Ingresá solamente la cantidad nueva
                            que llegó al depósito.
                        </small>

                    </div>


                    <!-- PREVISUALIZACIÓN -->

                    <div class="calculo-stock">

                        <div>

                            <span>Stock actual</span>

                            <strong>
                                <?= (int)$producto['Stock_actual'] ?>
                            </strong>

                        </div>


                        <i class="bi bi-plus-lg"></i>


                        <div>

                            <span>Reposición</span>

                            <strong id="cantidadVista">
                                0
                            </strong>

                        </div>


                        <i class="bi bi-arrow-right"></i>


                        <div class="nuevo-stock">

                            <span>Nuevo stock</span>

                            <strong id="nuevoStock">

                                <?= (int)$producto['Stock_actual'] ?>

                            </strong>

                        </div>

                    </div>


                    <div class="acciones-reposicion">

                        <a
                            href="index.php?ruta=inventario"
                            class="btn-cancelar-reposicion"
                        >
                            CANCELAR
                        </a>


                        <button
                            type="submit"
                            class="btn-confirmar-reposicion"
                        >

                            <i class="bi bi-check-lg"></i>

                            CONFIRMAR REPOSICIÓN

                        </button>

                    </div>

                </form>

            </div>

        </section>

    </main>

</div>


<script>

const cantidad =
    document.getElementById('cantidad');

const cantidadVista =
    document.getElementById('cantidadVista');

const nuevoStock =
    document.getElementById('nuevoStock');

const stockActual =
    <?= (int)$producto['Stock_actual'] ?>;


cantidad.addEventListener(
    'input',
    function () {

        let valor =
            parseInt(this.value);

        if (
            isNaN(valor)
            ||
            valor < 0
        ) {
            valor = 0;
        }

        cantidadVista.textContent =
            valor;

        nuevoStock.textContent =
            stockActual + valor;

    }
);

</script>

</body>

</html>