<?php

if (!isset($_SESSION['usuario'])) {

    header("Location: index.php?ruta=login");
    exit;
}


$usuario =
    $_SESSION['usuario'];


$error =
    $_SESSION['error_entrega'] ?? null;


unset($_SESSION['error_entrega']);

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
        Gestionar entrega | SmartPick
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
        href="css/nuevo-pedido.css"
    >

</head>


<body>


<div class="dashboard">


    <!-- ========================================
         SIDEBAR
    ========================================= -->

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



    <!-- ========================================
         CONTENIDO
    ========================================= -->

    <main class="main-content">


        <header class="topbar">


            <div class="titulo-dashboard">

                <h1>
                    GESTIONAR ENTREGA
                </h1>

                <p>
                    Retiro de paquetes y productos de almacén
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



        <section class="entrega-contenido">


            <a
                href="index.php?ruta=pedidos"
                class="volver"
            >

                <i class="bi bi-arrow-left"></i>

                Volver a pedidos

            </a>



            <?php if ($error): ?>

                <div class="mensaje-error">

                    <i
                        class="bi bi-exclamation-circle"
                    ></i>

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>



            <form
                action="index.php?ruta=guardar-entrega"
                method="POST"
                id="formEntrega"
            >


                <!-- =================================
                     CLIENTE
                ================================== -->

                <div class="tarjeta-entrega">


                    <div class="titulo-seccion">

                        <div class="icono-seccion">

                            <i
                                class="bi bi-person-search"
                            ></i>

                        </div>


                        <div>

                            <h2>
                                1. CLIENTE
                            </h2>

                            <p>
                                Busque por nombre o número
                                de cédula.
                            </p>

                        </div>

                    </div>


                    <div
                        class="buscador-contenedor"
                    >

                        <i
                            class="bi bi-search"
                        ></i>


                        <input
                            type="text"
                            id="buscarCliente"
                            placeholder="Ej: 51000001 o Juan Pérez"
                            autocomplete="off"
                        >

                    </div>


                    <div
                        id="resultadosClientes"
                        class="resultados-busqueda"
                    ></div>


                    <input
                        type="hidden"
                        name="ci_cliente"
                        id="ciCliente"
                    >


                    <div
                        id="clienteSeleccionado"
                        class="cliente-seleccionado oculto"
                    ></div>


                </div>



                <!-- =================================
                     PAQUETES
                ================================== -->

                <div
                    class="tarjeta-entrega"
                    id="seccionPaquetes"
                >


                    <div class="titulo-seccion">

                        <div class="icono-seccion">

                            <i
                                class="bi bi-box2"
                            ></i>

                        </div>


                        <div>

                            <h2>
                                2. PAQUETES ALMACENADOS
                            </h2>

                            <p>
                                Los paquetes pertenecientes
                                al cliente aparecerán
                                automáticamente.
                            </p>

                        </div>

                    </div>


                    <div
                        id="listaPaquetes"
                        class="lista-paquetes"
                    >

                        <div class="estado-vacio">

                            <i
                                class="bi bi-person-check"
                            ></i>

                            <p>
                                Seleccione primero un cliente.
                            </p>

                        </div>

                    </div>


                </div>



                <!-- =================================
                     PRODUCTOS ALMACÉN
                ================================== -->

                <div class="tarjeta-entrega">


                    <div class="titulo-seccion">

                        <div class="icono-seccion">

                            <i
                                class="bi bi-cart3"
                            ></i>

                        </div>


                        <div>

                            <h2>
                                3. PRODUCTOS DE ALMACÉN
                            </h2>

                            <p>
                                El cliente puede solicitar
                                productos además de retirar
                                sus paquetes.
                            </p>

                        </div>

                    </div>



                    <div
                        class="buscador-contenedor"
                    >

                        <i
                            class="bi bi-search"
                        ></i>


                        <input
                            type="text"
                            id="buscarProducto"
                            placeholder="Buscar por nombre, categoría o código..."
                            autocomplete="off"
                        >

                    </div>



                    <div
                        id="resultadosProductos"
                        class="resultados-productos"
                    ></div>



                    <h3 class="subtitulo">

                        PRODUCTOS AGREGADOS

                    </h3>


                    <div
                        id="productosSeleccionados"
                        class="productos-seleccionados"
                    >

                        <div
                            class="estado-vacio"
                            id="sinProductos"
                        >

                            <i
                                class="bi bi-cart-x"
                            ></i>

                            <p>
                                No hay productos agregados.
                            </p>

                        </div>

                    </div>


                </div>



                <!-- =================================
                     RESUMEN
                ================================== -->

                <div class="tarjeta-resumen">


                    <div>

                        <strong>
                            ENTREGA
                        </strong>

                        <p>
                            Puede incluir paquetes propios,
                            productos de almacén o ambos.
                        </p>

                    </div>


                    <button
                        type="submit"
                        class="btn-generar"
                    >

                        <i
                            class="bi bi-check-lg"
                        ></i>

                        GENERAR ENTREGA

                    </button>


                </div>


            </form>


        </section>


    </main>


</div>



<script>


// ====================================================
// VARIABLES
// ====================================================

const buscarCliente =
    document.getElementById(
        'buscarCliente'
    );


const resultadosClientes =
    document.getElementById(
        'resultadosClientes'
    );


const ciCliente =
    document.getElementById(
        'ciCliente'
    );


const clienteSeleccionado =
    document.getElementById(
        'clienteSeleccionado'
    );


const listaPaquetes =
    document.getElementById(
        'listaPaquetes'
    );


const buscarProducto =
    document.getElementById(
        'buscarProducto'
    );


const resultadosProductos =
    document.getElementById(
        'resultadosProductos'
    );


const productosSeleccionados =
    document.getElementById(
        'productosSeleccionados'
    );


const sinProductos =
    document.getElementById(
        'sinProductos'
    );



let productosAgregados = {};



// ====================================================
// BUSCAR CLIENTE
// ====================================================

let temporizadorCliente;


buscarCliente.addEventListener(
    'input',
    function () {

        clearTimeout(
            temporizadorCliente
        );


        const texto =
            this.value.trim();


        if (texto.length < 2) {

            resultadosClientes.innerHTML = '';

            return;
        }


        temporizadorCliente =
            setTimeout(

                async function () {

                    try {

                        const respuesta =
                            await fetch(
                                'index.php?ruta=buscar-clientes&q='
                                +
                                encodeURIComponent(texto)
                            );


                        const clientes =
                            await respuesta.json();


                        mostrarClientes(
                            clientes
                        );


                    } catch (error) {

                        console.error(error);

                    }

                },

                300
            );

    }
);



function mostrarClientes(clientes)
{

    resultadosClientes.innerHTML = '';


    if (clientes.length === 0) {

        resultadosClientes.innerHTML = `

            <div class="sin-resultados">

                No se encontraron clientes.

            </div>

        `;

        return;
    }


    clientes.forEach(cliente => {

        const item =
            document.createElement(
                'button'
            );


        item.type = 'button';

        item.className =
            'resultado-cliente';


        item.innerHTML = `

            <div>

                <strong>
                    ${escaparHTML(
                        cliente.Nombre_completo
                    )}
                </strong>

                <span>
                    CI:
                    ${escaparHTML(
                        cliente.CI_cliente
                    )}
                </span>

            </div>

            <i class="bi bi-chevron-right"></i>

        `;


        item.addEventListener(
            'click',
            function () {

                seleccionarCliente(
                    cliente
                );

            }
        );


        resultadosClientes.appendChild(
            item
        );

    });

}



// ====================================================
// SELECCIONAR CLIENTE
// ====================================================

function seleccionarCliente(cliente)
{

    ciCliente.value =
        cliente.CI_cliente;


    buscarCliente.value =
        cliente.Nombre_completo;


    resultadosClientes.innerHTML = '';


    clienteSeleccionado.classList.remove(
        'oculto'
    );


    clienteSeleccionado.innerHTML = `

        <div class="cliente-avatar">

            <i class="bi bi-person"></i>

        </div>

        <div>

            <strong>
                ${escaparHTML(
                    cliente.Nombre_completo
                )}
            </strong>

            <span>
                CI:
                ${escaparHTML(
                    cliente.CI_cliente
                )}
            </span>

            ${
                cliente.Mail
                ?
                `<small>
                    ${escaparHTML(
                        cliente.Mail
                    )}
                </small>`
                :
                ''
            }

        </div>

    `;


    cargarPaquetes(
        cliente.CI_cliente
    );

}



// ====================================================
// CARGAR PAQUETES
// ====================================================

async function cargarPaquetes(ci)
{

    listaPaquetes.innerHTML = `

        <div class="estado-vacio">

            <i
                class="bi bi-arrow-repeat girando"
            ></i>

            <p>
                Buscando paquetes...
            </p>

        </div>

    `;


    try {

        const respuesta =
            await fetch(
                'index.php?ruta=paquetes-cliente&ci='
                +
                encodeURIComponent(ci)
            );


        const paquetes =
            await respuesta.json();


        listaPaquetes.innerHTML = '';


        if (paquetes.length === 0) {

            listaPaquetes.innerHTML = `

                <div class="estado-vacio">

                    <i
                        class="bi bi-box-seam"
                    ></i>

                    <p>
                        Este cliente no tiene
                        paquetes pendientes
                        de retiro.
                    </p>

                </div>

            `;

            return;
        }


        paquetes.forEach(paquete => {

            const elemento =
                document.createElement(
                    'label'
                );


            elemento.className =
                'paquete-item';


            elemento.innerHTML = `

                <input
                    type="checkbox"
                    name="paquetes[]"
                    value="${paquete.ID_pedido}"
                >

                <div class="paquete-check">

                    <i
                        class="bi bi-check-lg"
                    ></i>

                </div>


                <div class="paquete-info">

                    <strong>

                        ${escaparHTML(
                            paquete.Descripcion
                        )}

                    </strong>


                    <span>

                        Pedido
                        #${paquete.ID_pedido}

                        ·

                        ${escaparHTML(
                            paquete.Categoria
                        )}

                    </span>

                </div>


                <div class="estado-paquete">

                    ${escaparHTML(
                        paquete.Estado
                    )}

                </div>

            `;


            listaPaquetes.appendChild(
                elemento
            );

        });


    } catch (error) {

        listaPaquetes.innerHTML = `

            <div class="estado-vacio">

                <p>
                    No fue posible cargar
                    los paquetes.
                </p>

            </div>

        `;

    }

}



// ====================================================
// BUSCAR PRODUCTO
// ====================================================

let temporizadorProducto;


buscarProducto.addEventListener(
    'input',
    function () {

        clearTimeout(
            temporizadorProducto
        );


        const texto =
            this.value.trim();


        if (texto.length < 2) {

            resultadosProductos.innerHTML = '';

            return;
        }


        temporizadorProducto =
            setTimeout(

                async function () {

                    try {

                        const respuesta =
                            await fetch(
                                'index.php?ruta=buscar-productos&q='
                                +
                                encodeURIComponent(texto)
                            );


                        const productos =
                            await respuesta.json();


                        mostrarProductos(
                            productos
                        );


                    } catch (error) {

                        console.error(error);

                    }

                },

                300
            );

    }
);



function mostrarProductos(productos)
{

    resultadosProductos.innerHTML = '';


    if (productos.length === 0) {

        resultadosProductos.innerHTML = `

            <div class="sin-resultados">

                No se encontraron productos.

            </div>

        `;

        return;
    }


    productos.forEach(producto => {

        const div =
            document.createElement(
                'div'
            );


        div.className =
            'producto-resultado';


        div.innerHTML = `

            <div class="producto-datos">

                <strong>

                    ${escaparHTML(
                        producto.Descripcion
                    )}

                </strong>


                <span>

                    ${escaparHTML(
                        producto.Categoria
                    )}

                    · Código:
                    ${producto.ID_producto}

                </span>


                <small>

                    Stock disponible:
                    ${producto.Stock_actual}

                </small>

            </div>


            <button
                type="button"
                class="btn-agregar"
            >

                <i class="bi bi-plus-lg"></i>

                Agregar

            </button>

        `;


        div.querySelector(
            '.btn-agregar'
        )
        .addEventListener(
            'click',
            function () {

                agregarProducto(
                    producto
                );

            }
        );


        resultadosProductos.appendChild(
            div
        );

    });

}



// ====================================================
// AGREGAR PRODUCTO
// ====================================================

function agregarProducto(producto)
{

    if (
        productosAgregados[
            producto.ID_producto
        ]
    ) {

        return;
    }


    productosAgregados[
        producto.ID_producto
    ] = producto;


    if (sinProductos) {

        sinProductos.style.display =
            'none';
    }


    const div =
        document.createElement(
            'div'
        );


    div.className =
        'producto-seleccionado';


    div.dataset.id =
        producto.ID_producto;


    div.innerHTML = `

        <input
            type="hidden"
            name="producto_id[]"
            value="${producto.ID_producto}"
        >


        <div class="producto-seleccionado-info">

            <strong>

                ${escaparHTML(
                    producto.Descripcion
                )}

            </strong>

            <span>

                Stock:
                ${producto.Stock_actual}

            </span>

        </div>


        <div class="cantidad-producto">

            <label>
                Cantidad
            </label>

            <input
                type="number"
                name="cantidad[]"
                value="1"
                min="1"
                max="${producto.Stock_actual}"
                required
            >

        </div>


        <button
            type="button"
            class="btn-quitar"
        >

            <i class="bi bi-trash"></i>

        </button>

    `;


    div.querySelector(
        '.btn-quitar'
    )
    .addEventListener(
        'click',
        function () {

            delete productosAgregados[
                producto.ID_producto
            ];


            div.remove();


            if (
                Object.keys(
                    productosAgregados
                ).length === 0
            ) {

                sinProductos.style.display =
                    'flex';
            }

        }
    );


    productosSeleccionados.appendChild(
        div
    );


    resultadosProductos.innerHTML = '';

    buscarProducto.value = '';

}



// ====================================================
// VALIDACIÓN DEL FORMULARIO
// ====================================================

document
.getElementById('formEntrega')
.addEventListener(
    'submit',
    function (event) {

        if (!ciCliente.value) {

            event.preventDefault();

            alert(
                'Debe seleccionar un cliente.'
            );

            return;
        }


        const paquetes =
            document.querySelectorAll(
                'input[name="paquetes[]"]:checked'
            );


        const productos =
            document.querySelectorAll(
                'input[name="producto_id[]"]'
            );


        if (
            paquetes.length === 0 &&
            productos.length === 0
        ) {

            event.preventDefault();

            alert(
                'Debe seleccionar al menos un paquete o producto.'
            );

        }

    }
);



// ====================================================
// EVITAR HTML INSERTADO
// ====================================================

function escaparHTML(texto)
{

    const div =
        document.createElement('div');

    div.textContent =
        texto ?? '';

    return div.innerHTML;

}


</script>


</body>

</html>