<?php

if (!isset($_SESSION['usuario'])) {

    header("Location: index.php?ruta=login");
    exit;
}


$usuario = $_SESSION['usuario'];


$mensaje =
    $_SESSION['mensaje_pedido'] ?? null;


$error =
    $_SESSION['error_pedido'] ?? null;


unset($_SESSION['mensaje_pedido']);
unset($_SESSION['error_pedido']);


$estado =
    strtolower($pedido['Estado']);


$claseEstado =
    'estado-pendiente';


$estado =
    strtolower($pedido['Estado']);


$claseEstado =
    'estado-pendiente';


if (
    $estado === 'preparando' ||
    $estado === 'en proceso'
) {

    $claseEstado =
        'estado-proceso';

} elseif (
    $estado === 'listo_para_validar'
) {

    $claseEstado =
        'estado-validar';

} elseif (
    $estado === 'entregado' ||
    $estado === 'completado' ||
    $estado === 'retirado'
) {

    $claseEstado =
        'estado-completado';
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

    <title>
        Pedido #<?= $pedido['ID_pedido'] ?> | SmartPick
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
        href="css/ver-pedido.css"
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

                <h1>
                    PEDIDO #<?= $pedido['ID_pedido'] ?>
                </h1>

                <p>
                    Detalle y asignación del pedido
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



        <section class="detalle-contenido">


            <a
                href="index.php?ruta=pedidos"
                class="volver"
            >

                <i class="bi bi-arrow-left"></i>

                Volver a pedidos

            </a>



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



            <div class="grid-detalle">


                <div class="tarjeta">


                    <div class="encabezado-tarjeta">

                        <i class="bi bi-receipt"></i>

                        <div>

                            <h2>
                                INFORMACIÓN DEL PEDIDO
                            </h2>

                            <p>
                                Datos generales
                            </p>

                        </div>

                    </div>


                    <div class="datos-grid">


                        <div class="dato">

                            <span>ID PEDIDO</span>

                            <strong>
                                #<?= $pedido['ID_pedido'] ?>
                            </strong>

                        </div>


                        <div class="dato">

                            <span>FECHA DE INGRESO</span>

                            <strong>

                                <?= date(
                                    'd/m/Y',
                                    strtotime(
                                        $pedido['Fecha_ingresado']
                                    )
                                ) ?>

                            </strong>

                        </div>


                        <div class="dato">

                            <span>ESTADO</span>

                            <strong
                                class="estado <?= $claseEstado ?>"
                            >

                                <?= htmlspecialchars(
                                    $pedido['Estado']
                                ) ?>

                            </strong>

                        </div>


                        <div class="dato">

                            <span>FECHA DE ENTREGA</span>

                            <strong>

                                <?php

                                if ($pedido['Fecha_entregado']) {

                                    echo date(
                                        'd/m/Y',
                                        strtotime(
                                            $pedido['Fecha_entregado']
                                        )
                                    );

                                } else {

                                    echo '-';
                                }

                                ?>

                            </strong>

                        </div>


                    </div>


                </div>



                <div class="tarjeta">


                    <div class="encabezado-tarjeta">

                        <i class="bi bi-person"></i>

                        <div>

                            <h2>
                                CLIENTE
                            </h2>

                            <p>
                                Responsable del pedido
                            </p>

                        </div>

                    </div>


                    <div class="cliente-detalle">

                        <div class="avatar-cliente">

                            <i class="bi bi-person"></i>

                        </div>


                        <div>

                            <strong>

                                <?= htmlspecialchars(
                                    $pedido['Cliente']
                                ) ?>

                            </strong>


                            <span>

                                CI:
                                <?= htmlspecialchars(
                                    $pedido['CI_cliente']
                                ) ?>

                            </span>


                            <?php if ($pedido['Mail_cliente']): ?>

                                <small>

                                    <?= htmlspecialchars(
                                        $pedido['Mail_cliente']
                                    ) ?>

                                </small>

                            <?php endif; ?>


                        </div>

                    </div>


                </div>


            </div>



            <!-- ======================================
                 PRODUCTOS
            ======================================= -->

            <div class="tarjeta tarjeta-productos">


                <div class="encabezado-tarjeta">

                    <i class="bi bi-box-seam"></i>

                    <div>

                        <h2>
                            PRODUCTOS DEL PEDIDO
                        </h2>

                        <p>
                            Mercadería solicitada
                        </p>

                    </div>

                </div>


                <div class="tabla-responsive">

                    <table class="tabla-productos">


                        <thead>

                            <tr>

                                <th>CÓDIGO</th>

                                <th>PRODUCTO</th>

                                <th>CATEGORÍA</th>

                                <th>CANTIDAD</th>

                                <th>STOCK</th>

                            </tr>

                        </thead>


                       <tbody>

<?php foreach ($productosPedido as $producto): ?>

    <tr>

        <td>
            #<?= $producto['ID_producto'] ?>
        </td>

        <td>

            <strong>

                <?= htmlspecialchars(
                    $producto['Descripcion']
                ) ?>

            </strong>

        </td>

        <td>

            <?= htmlspecialchars(
                $producto['Categoria']
            ) ?>

        </td>

        <td>
            <?= $producto['Cantidad'] ?>
        </td>

        <td>
            <?= $producto['Stock_actual'] ?>
        </td>

    </tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>


<?php

$esAdministrador =
    strtolower(
        $_SESSION['usuario']['rol']
    ) === 'administrador';


$estaListo =
    strtoupper(
        $pedido['Estado']
    ) === 'LISTO_PARA_VALIDAR';

?>


<?php if ($esAdministrador && $estaListo): ?>


    <div class="validacion-pedido">


        <div class="validacion-info">

            <i class="bi bi-shield-check"></i>

            <div>

                <strong>
                    PEDIDO LISTO PARA VALIDAR
                </strong>

                <span>
                    Todos los productos fueron retirados
                    durante el picking.
                </span>

            </div>

        </div>


        <form
            action="index.php?ruta=validar-pedido"
            method="POST"
            onsubmit="return confirm('¿Confirmar que el pedido fue revisado y entregado?');"
        >

            <input
                type="hidden"
                name="id_pedido"
                value="<?= (int)$pedido['ID_pedido'] ?>"
            >


            <button
                type="submit"
                class="btn-validar-pedido"
            >

                <i class="bi bi-check-circle"></i>

                VALIDAR Y FINALIZAR

            </button>


        </form>


    </div>


<?php endif; ?>


<!-- ======================================
     OPERARIO
======================================= -->

<div class="tarjeta">



            <!-- ======================================
                 OPERARIO
            ======================================= -->

            <div class="tarjeta">


                <div class="encabezado-tarjeta">

                    <i class="bi bi-person-gear"></i>

                    <div>

                        <h2>
                            ASIGNACIÓN DE OPERARIO
                        </h2>

                        <p>
                            Responsable del picking
                        </p>

                    </div>

                </div>



                <?php if ($pedido['Operario']): ?>

                    <div class="operario-actual">

                        <i class="bi bi-person-check"></i>

                        <div>

                            <span>
                                OPERARIO ASIGNADO
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    $pedido['Operario']
                                ) ?>

                            </strong>

                        </div>

                    </div>

                <?php endif; ?>



                <?php
                if (
    in_array(
        strtolower($pedido['Estado']),
        [
            'pendiente',
            'pendiente_retiro',
            'preparando'
        ],
        true
    )
):
                ?>

                    <form
                        action="index.php?ruta=asignar-operario"
                        method="POST"
                        class="form-asignacion"
                    >


                        <input
                            type="hidden"
                            name="id_pedido"
                            value="<?= $pedido['ID_pedido'] ?>"
                        >


                        <div class="campo-operario">

                            <label>
                                Seleccionar operario
                            </label>


                            <select
                                name="ci_operario"
                                required
                            >

                                <option value="">
                                    Seleccione un operario
                                </option>


                                <?php foreach ($operarios as $operario): ?>

                                    <option
                                        value="<?= htmlspecialchars(
                                            $operario['CI_operario']
                                        ) ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $operario['Nombre_completo']
                                        ) ?>

                                    </option>

                                <?php endforeach; ?>


                            </select>

                        </div>


                        <button
                            type="submit"
                            class="btn-asignar"
                        >

                            <i class="bi bi-person-check"></i>

                            <?= $pedido['Operario']
                                ? 'REASIGNAR'
                                : 'ASIGNAR OPERARIO'
                            ?>

                        </button>


                    </form>

                <?php endif; ?>


            </div>


        </section>


    </main>


</div>


</body>

</html>