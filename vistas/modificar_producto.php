<?php

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?ruta=login");
    exit;
}

$usuario = $_SESSION['usuario'];

$error =
    $_SESSION['error_producto'] ?? null;

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

    <title>Modificar producto | SmartPick</title>

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

                <h1>MODIFICAR PRODUCTO</h1>

                <p>
                    Actualización del producto de almacén
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


            <a
                href="index.php?ruta=productos"
                class="volver"
            >
                <i class="bi bi-arrow-left"></i>
                Volver a productos
            </a>


            <?php if ($error): ?>

                <div class="mensaje-error">

                    <i class="bi bi-exclamation-circle"></i>

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>


            <form
                action="index.php?ruta=modificar-producto"
                method="POST"
                class="formulario-producto"
            >

                <input
                    type="hidden"
                    name="id_producto"
                    value="<?= (int)$producto['ID_producto'] ?>"
                >


                <div class="encabezado-formulario">

                    <div class="icono-formulario">

                        <i class="bi bi-pencil"></i>

                    </div>


                    <div>

                        <span>
                            PRODUCTO #<?= (int)$producto['ID_producto'] ?>
                        </span>

                        <h2>
                            MODIFICAR PRODUCTO
                        </h2>

                        <p>
                            Actualizá la información del producto seleccionado.
                        </p>

                    </div>

                </div>


                <div class="form-grid">


                    <!-- DESCRIPCIÓN -->

                    <div class="campo campo-completo">

                        <label for="descripcion">
                            DESCRIPCIÓN *
                        </label>

                        <input
                            type="text"
                            name="descripcion"
                            id="descripcion"
                            maxlength="150"
                            value="<?= htmlspecialchars(
                                $producto['Descripcion']
                            ) ?>"
                            required
                        >

                    </div>


                    <!-- CATEGORÍA -->

                    <div class="campo">

                        <label for="categoria">
                            CATEGORÍA *
                        </label>

                        <select
                            name="categoria"
                            id="categoria"
                            required
                        >

                            <?php

                            $categorias = [
                                'Alimentos',
                                'Bebidas',
                                'Congelados',
                                'Otros'
                            ];

                            ?>

                            <?php foreach ($categorias as $categoria): ?>

                                <option
                                    value="<?= htmlspecialchars($categoria) ?>"
                                    <?=
                                        strcasecmp(
                                            $producto['Categoria'] ?? '',
                                            $categoria
                                        ) === 0
                                            ? 'selected'
                                            : ''
                                    ?>
                                >

                                    <?= htmlspecialchars($categoria) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- STOCK ACTUAL -->

                    <div class="campo">

                        <label>
                            STOCK ACTUAL
                        </label>

                        <div class="stock-solo-lectura">

                            <strong>
                                <?= (int)$producto['Stock_actual'] ?>
                            </strong>

                            <span>
                                unidades disponibles
                            </span>

                        </div>

                        <small>
                            El stock actual se modifica desde
                            Reposición de stock.
                        </small>

                    </div>


                    <!-- STOCK MÍNIMO -->

                    <div class="campo">

                        <label for="stock_minimo">
                            STOCK MÍNIMO *
                        </label>

                        <input
                            type="number"
                            name="stock_minimo"
                            id="stock_minimo"
                            min="0"
                            value="<?= (int)$producto['Stock_minimo'] ?>"
                            required
                        >

                        <small>
                            Se generará una alerta cuando el stock
                            llegue a este valor.
                        </small>

                    </div>


                    <!-- UBICACIÓN -->

                    <div class="campo">

                        <label for="id_zona">
                            UBICACIÓN *
                        </label>

                        <select
                            name="id_zona"
                            id="id_zona"
                            required
                        >

                            <option value="">
                                Seleccionar ubicación...
                            </option>


                            <?php foreach ($zonas as $zona): ?>

                                <option
                                    value="<?= (int)$zona['ID_zona'] ?>"

                                    <?=
                                        (int)$producto['ID_zona']
                                        ===
                                        (int)$zona['ID_zona']
                                            ? 'selected'
                                            : ''
                                    ?>
                                >

                                    <?= htmlspecialchars(
                                        $zona['Nombre']
                                    ) ?>


                                    <?php if (!empty($zona['Nivel'])): ?>

                                        — Nivel
                                        <?= htmlspecialchars(
                                            $zona['Nivel']
                                        ) ?>

                                    <?php endif; ?>


                                    <?php if (!empty($zona['Estante'])): ?>

                                        — Estante
                                        <?= htmlspecialchars(
                                            $zona['Estante']
                                        ) ?>

                                    <?php endif; ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                </div>


                <!-- INFORMACIÓN -->

                <div class="resumen-producto">

                    <i class="bi bi-info-circle"></i>

                    <div>

                        <strong>
                            Modificación del producto
                        </strong>

                        <span>
                            Los cambios realizados aquí no alteran
                            la cantidad disponible en inventario.
                        </span>

                    </div>

                </div>


                <!-- BOTONES -->

                <div class="acciones-formulario">

                    <a
                        href="index.php?ruta=productos"
                        class="btn-cancelar"
                    >
                        CANCELAR
                    </a>


                    <button
                        type="submit"
                        class="btn-guardar"
                    >

                        <i class="bi bi-check-lg"></i>

                        GUARDAR CAMBIOS

                    </button>

                </div>

            </form>

        </section>

    </main>

</div>

</body>

</html>