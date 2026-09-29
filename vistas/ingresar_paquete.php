<?php

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?ruta=login");
    exit;
}

$usuario = $_SESSION['usuario'];

$mensaje =
    $_SESSION['mensaje_paquete'] ?? null;

$error =
    $_SESSION['error_paquete'] ?? null;

unset($_SESSION['mensaje_paquete']);
unset($_SESSION['error_paquete']);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Ingresar paquete | SmartPick</title>

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
        href="css/paquetes.css"
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


            <?php if (
                strtolower($usuario['rol'])
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


            <?php if (
                strtolower($usuario['rol'])
                === 'administrador'
            ): ?>

                <a
                    href="index.php?ruta=ingresar-paquete"
                    class="menu-item activo"
                >
                    <i class="bi bi-box-arrow-in-down"></i>
                    <span>Ingresar paquete</span>
                </a>

            <?php endif; ?>


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

                <h1>INGRESAR PAQUETE</h1>

                <p>
                    Registro y almacenamiento de mercadería
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


        <section class="paquetes-contenido">


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


            <div class="encabezado-pagina">

                <div>

                    <span class="etiqueta-seccion">
                        RECEPCIÓN
                    </span>

                    <h2>
                        NUEVO PAQUETE
                    </h2>

                    <p>
                        Registrá el paquete, su propietario
                        y la ubicación donde será almacenado.
                    </p>

                </div>

            </div>


            <form
                action="index.php?ruta=ingresar-paquete"
                method="POST"
                class="formulario-paquete"
            >


                <!-- ========================== -->
                <!-- CLIENTE -->
                <!-- ========================== -->

                <div class="bloque-formulario">

                    <div class="titulo-bloque">

                        <div class="numero-paso">
                            1
                        </div>

                        <div>

                            <span>PROPIETARIO</span>

                            <h3>
                                Seleccionar cliente
                            </h3>

                        </div>

                    </div>


                    <div class="campo">

                        <label for="ci_cliente">
                            CLIENTE *
                        </label>


                        <select
                            name="ci_cliente"
                            id="ci_cliente"
                            required
                        >

                            <option value="">
                                Seleccionar cliente...
                            </option>


                            <?php foreach ($clientes as $cliente): ?>

                                <option
                                    value="<?= htmlspecialchars(
                                        $cliente['CI_cliente']
                                    ) ?>"
                                >

                                    <?= htmlspecialchars(
                                        $cliente['Nombre_completo']
                                    ) ?>

                                    —

                                    CI <?= htmlspecialchars(
                                        $cliente['CI_cliente']
                                    ) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>


                        <small>
                            Si el cliente todavía no está registrado,
                            primero debe crearse desde Clientes.
                        </small>

                    </div>

                </div>


                <!-- ========================== -->
                <!-- PAQUETE -->
                <!-- ========================== -->

                <div class="bloque-formulario">

                    <div class="titulo-bloque">

                        <div class="numero-paso">
                            2
                        </div>

                        <div>

                            <span>PAQUETE</span>

                            <h3>
                                Datos del paquete
                            </h3>

                        </div>

                    </div>


                    <div class="form-grid">


                        <div class="campo">

                            <label for="descripcion">
                                DESCRIPCIÓN *
                            </label>

                            <input
                                type="text"
                                name="descripcion"
                                id="descripcion"
                                placeholder="Ej: Caja térmica mediana"
                                maxlength="150"
                                required
                            >

                            <small>
                                Descripción que permita identificar
                                físicamente el paquete.
                            </small>

                        </div>


                        <div class="campo">

                            <label for="categoria">
                                CATEGORÍA *
                            </label>

                            <select
                                name="categoria"
                                id="categoria"
                                required
                            >

                                <option value="">
                                    Seleccionar...
                                </option>

                                <option value="General">
                                    General
                                </option>

                                <option value="Frágil">
                                    Frágil
                                </option>

                                <option value="Congelado">
                                    Congelado
                                </option>

                                <option value="Voluminoso">
                                    Voluminoso
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                <!-- ========================== -->
                <!-- UBICACIÓN -->
                <!-- ========================== -->

                <div class="bloque-formulario">

                    <div class="titulo-bloque">

                        <div class="numero-paso">
                            3
                        </div>

                        <div>

                            <span>ALMACENAMIENTO</span>

                            <h3>
                                Ubicación física
                            </h3>

                        </div>

                    </div>


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


                        <small>
                            Esta ubicación será utilizada luego
                            para generar la hoja de picking.
                        </small>

                    </div>


                    <div
                        class="resumen-ubicacion"
                        id="resumenUbicacion"
                    >

                        <i class="bi bi-geo-alt"></i>

                        <div>

                            <span>UBICACIÓN SELECCIONADA</span>

                            <strong id="textoUbicacion">
                                Ninguna ubicación seleccionada
                            </strong>

                        </div>

                    </div>

                </div>


                <!-- BOTONES -->

                <div class="acciones-formulario">

                    <a
                        href="index.php?ruta=dashboard"
                        class="btn-cancelar"
                    >
                        CANCELAR
                    </a>


                    <button
                        type="submit"
                        class="btn-guardar"
                    >

                        <i class="bi bi-box-arrow-in-down"></i>

                        INGRESAR PAQUETE

                    </button>

                </div>

            </form>

        </section>

    </main>

</div>


<script>

const selectorZona =
    document.getElementById('id_zona');

const textoUbicacion =
    document.getElementById('textoUbicacion');


selectorZona.addEventListener(
    'change',
    function () {

        const opcion =
            this.options[this.selectedIndex];

        if (this.value === '') {

            textoUbicacion.textContent =
                'Ninguna ubicación seleccionada';

            return;
        }

        textoUbicacion.textContent =
            opcion.textContent
                .replace(/\s+/g, ' ')
                .trim();

    }
);

</script>

</body>

</html>