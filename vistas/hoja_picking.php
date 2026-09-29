<?php

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?ruta=login");
    exit;
}

$usuario = $_SESSION['usuario'];

if (empty($hojaPicking)) {
    echo "No se encontraron productos para este pedido.";
    exit;
}

$primerItem = $hojaPicking[0];

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
        Picking #<?= $primerItem['ID_pedido'] ?> | SmartPick
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

<a href="index.php?ruta=zonas" class="menu-item">
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


        <!-- TOPBAR -->

        <header class="topbar">


            <div class="titulo-dashboard">

                <h1>
                    HOJA DE PICKING
                </h1>

                <p>
                    Pedido #<?= $primerItem['ID_pedido'] ?>
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



        <section class="picking-contenido">


            <!-- VOLVER -->

            <a
                href="index.php?ruta=picking"
                class="volver"
            >

                <i class="bi bi-arrow-left"></i>

                Volver a picking

            </a>



            <!-- DATOS DEL PEDIDO -->

            <div class="cabecera-hoja">


                <div>

                    <span>
                        CLIENTE
                    </span>

                    <strong>
                        <?= htmlspecialchars($primerItem['Cliente']) ?>
                    </strong>

                </div>


                <div>

                    <span>
                        CI
                    </span>

                    <strong>
                        <?= htmlspecialchars($primerItem['CI_cliente']) ?>
                    </strong>

                </div>


                <div>

                    <span>
                        ESTADO
                    </span>

                    <strong class="badge-preparando">
                        <?= htmlspecialchars($primerItem['Estado']) ?>
                    </strong>

                </div>


            </div>



            <!-- RUTA -->

            <div class="ruta-titulo">


                <i class="bi bi-signpost-split"></i>


                <div>

                    <h2>
                        RUTA DE PICKING
                    </h2>

                    <p>
                        Los productos se muestran ordenados
                        por zona y estante.
                    </p>

                </div>


            </div>



            <!-- LISTA DE PRODUCTOS -->

            <div class="lista-picking">


                <?php

                $numero = 1;

                foreach ($hojaPicking as $item):

                    $confirmado =
                        (int)$item['Confirmado'] === 1;

                ?>


                    <div
                        class="item-picking <?= $confirmado ? 'item-confirmado' : '' ?>"
                        id="producto-<?= (int)$item['ID_producto'] ?>"
                    >


                        <!-- NÚMERO -->

                        <div class="numero-picking">


                            <?php if ($confirmado): ?>

                                <i class="bi bi-check-lg"></i>

                            <?php else: ?>

                                <?= $numero ?>

                            <?php endif; ?>


                        </div>



                        <!-- PRODUCTO -->

                        <div class="producto-picking">


                            <span>
                                PRODUCTO
                            </span>


                            <strong>

                                <?= htmlspecialchars(
                                    $item['Descripcion']
                                ) ?>

                            </strong>


                            <small>

                                Código:
                                #<?= (int)$item['ID_producto'] ?>

                                ·

                                <?= htmlspecialchars(
                                    $item['Categoria'] ?? ''
                                ) ?>

                            </small>


                        </div>



                        <!-- UBICACIÓN -->

                        <div class="ubicacion-picking">


                            <div>

                                <span>
                                    ZONA
                                </span>

                                <strong>

                                    <?= htmlspecialchars(
                                        $item['Zona']
                                        ?? 'Sin asignar'
                                    ) ?>

                                </strong>

                            </div>


                            <div>

                                <span>
                                    NIVEL
                                </span>

                                <strong>

                                    <?= htmlspecialchars(
                                        $item['Nivel']
                                        ?? '-'
                                    ) ?>

                                </strong>

                            </div>


                            <div>

                                <span>
                                    ESTANTE
                                </span>

                                <strong>

                                    <?= htmlspecialchars(
                                        $item['Estante']
                                        ?? '-'
                                    ) ?>

                                </strong>

                            </div>

                            <?php if (!empty($item['ID_zona'])): ?>

    <a
        href="index.php?ruta=zonas&destino=<?= (int)$item['ID_zona'] ?>"
        class="btn-ver-mapa"
    >

        <i class="bi bi-map"></i>

        VER EN MAPA

    </a>

<?php endif; ?>


                        </div>



                        <!-- CANTIDAD -->

                        <div class="cantidad-picking">


                            <span>
                                RETIRAR
                            </span>


                            <strong>
                                <?= (int)$item['Cantidad'] ?>
                            </strong>


                        </div>



                        <!-- ACCIÓN -->

                        <div class="accion-picking">


                            <?php if ($confirmado): ?>


                                <span class="producto-confirmado">

                                    <i class="bi bi-check-circle-fill"></i>

                                    RETIRADO

                                </span>


                            <?php else: ?>


                                <button
                                    type="button"
                                    class="btn-confirmar-picking"
                                    data-pedido="<?= (int)$item['ID_pedido'] ?>"
                                    data-producto="<?= (int)$item['ID_producto'] ?>"
                                >

                                    <i class="bi bi-check2"></i>

                                    CONFIRMAR RETIRO

                                </button>


                            <?php endif; ?>


                        </div>


                    </div>


                <?php

                    $numero++;

                endforeach;

                ?>


            </div>


        </section>


    </main>


</div>



<!-- JAVASCRIPT -->

<script>

const botonesConfirmar =
    document.querySelectorAll(
        '.btn-confirmar-picking'
    );


botonesConfirmar.forEach(boton => {


    boton.addEventListener(
        'click',
        async function () {


            const idPedido =
                this.dataset.pedido;


            const idProducto =
                this.dataset.producto;


            const confirmar =
                window.confirm(
                    '¿Confirmar el retiro de este producto?'
                );


            if (!confirmar) {
                return;
            }


            this.disabled = true;


            const textoOriginal =
                this.innerHTML;


            this.innerHTML =
                '<i class="bi bi-hourglass-split"></i> PROCESANDO...';


            try {


                const datos =
                    new FormData();


                datos.append(
                    'id_pedido',
                    idPedido
                );


                datos.append(
                    'id_producto',
                    idProducto
                );


                const respuesta =
                    await fetch(
                        'index.php?ruta=confirmar-picking',
                        {
                            method: 'POST',
                            body: datos
                        }
                    );


                const resultado =
                    await respuesta.json();


                if (!resultado.ok) {

                    throw new Error(
                        resultado.mensaje
                        || 'No se pudo confirmar el producto.'
                    );

                }


                const fila =
                    document.getElementById(
                        'producto-' + idProducto
                    );


                fila.classList.add(
                    'item-confirmado'
                );


                const numero =
                    fila.querySelector(
                        '.numero-picking'
                    );


                numero.innerHTML =
                    '<i class="bi bi-check-lg"></i>';


                const accion =
                    fila.querySelector(
                        '.accion-picking'
                    );


                accion.innerHTML = `

                    <span class="producto-confirmado">

                        <i class="bi bi-check-circle-fill"></i>

                        RETIRADO

                    </span>

                `;


                if (resultado.terminado) {

                    mostrarFinalizacion();

                }


            } catch (error) {


                alert(error.message);


                this.disabled = false;


                this.innerHTML =
                    textoOriginal;

            }


        }
    );


});



function mostrarFinalizacion()
{

    if (
        document.querySelector(
            '.picking-completado'
        )
    ) {
        return;
    }


    const contenedor =
        document.querySelector(
            '.lista-picking'
        );


    const mensaje =
        document.createElement('div');


    mensaje.className =
        'picking-completado';


    mensaje.innerHTML = `

        <i class="bi bi-check-circle-fill"></i>

        <div>

            <strong>
                PICKING COMPLETADO
            </strong>

            <span>
                Todos los productos fueron retirados.
                El pedido quedó pendiente de validación.
            </span>

        </div>

        <a
            href="index.php?ruta=pedidos"
            class="btn-volver-pedidos"
        >
            IR A PEDIDOS
        </a>

    `;


    contenedor.appendChild(
        mensaje
    );

}

</script>


</body>

</html>