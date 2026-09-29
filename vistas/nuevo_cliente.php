<?php

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?ruta=login");
    exit;
}

$usuario = $_SESSION['usuario'];

$error =
    $_SESSION['error_cliente'] ?? null;

unset($_SESSION['error_cliente']);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nuevo cliente | SmartPick</title>

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
        href="css/clientes.css"
    >

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
                href="index.php?ruta=clientes"
                class="menu-item activo"
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

                <h1>NUEVO CLIENTE</h1>

                <p>
                    Registro de clientes
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


        <section class="clientes-contenido">


            <a
                href="index.php?ruta=clientes"
                class="volver"
            >

                <i class="bi bi-arrow-left"></i>
                Volver a clientes

            </a>


            <?php if ($error): ?>

                <div class="mensaje-error">

                    <i class="bi bi-exclamation-circle"></i>

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>


            <div class="formulario-cliente">

                <div class="encabezado-formulario">

                    <div class="icono-formulario">

                        <i class="bi bi-person-plus"></i>

                    </div>

                    <div>

                        <span>
                            DATOS DEL CLIENTE
                        </span>

                        <h2>
                            REGISTRAR NUEVO CLIENTE
                        </h2>

                        <p>
                            Ingresá los datos del nuevo cliente.
                        </p>

                    </div>

                </div>


                <form
                    action="index.php?ruta=nuevo-cliente"
                    method="POST"
                >

                    <div class="form-grid">


                        <div class="campo">

                            <label for="ci">
                                CÉDULA *
                            </label>

                            <input
                                type="text"
                                id="ci"
                                name="ci"
                                placeholder="Ej: 51000006"
                                required
                            >

                        </div>


                        <div class="campo">

                            <label for="nombre">
                                NOMBRE COMPLETO *
                            </label>

                            <input
                                type="text"
                                id="nombre"
                                name="nombre"
                                placeholder="Nombre y apellido"
                                required
                            >

                        </div>


                        <div class="campo">

                            <label for="telefono">
                                TELÉFONO
                            </label>

                            <input
                                type="text"
                                id="telefono"
                                name="telefono"
                                placeholder="Ej: 099 123 456"
                            >

                        </div>


                        <div class="campo">

                            <label for="mail">
                                CORREO ELECTRÓNICO
                            </label>

                            <input
                                type="email"
                                id="mail"
                                name="mail"
                                placeholder="cliente@email.com"
                            >

                        </div>


                        <div class="campo campo-completo">

                            <label for="direccion">
                                DIRECCIÓN
                            </label>

                            <input
                                type="text"
                                id="direccion"
                                name="direccion"
                                placeholder="Dirección del cliente"
                            >

                        </div>


                    </div>


                    <div class="acciones-formulario">

                        <a
                            href="index.php?ruta=clientes"
                            class="btn-cancelar"
                        >
                            CANCELAR
                        </a>


                        <button
                            type="submit"
                            class="btn-guardar"
                        >

                            <i class="bi bi-check-lg"></i>

                            GUARDAR CLIENTE

                        </button>

                    </div>

                </form>

            </div>

        </section>

    </main>

</div>

</body>

</html>