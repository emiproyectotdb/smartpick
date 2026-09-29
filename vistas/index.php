<?php
// ==========================================
// SMARTPICK - LANDING PAGE
// ==========================================

// Por ahora estos datos son de prueba.
// Más adelante los vamos a obtener desde MySQL.

$capacidad = 85;
$recepcionados = 12;
$pendientes = 5;
$despachados = 8;

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SmartPick | Logística Inteligente</title>

    <!-- BOOTSTRAP -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- GOOGLE FONTS -->
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- ICONOS BOOTSTRAP -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- CSS PROPIO -->
    <link rel="stylesheet" href="css/styles.css">

</head>


<body>


<!-- ==========================================
     NAVBAR
=========================================== -->

<nav class="navbar navbar-expand-lg navbar-dark fixed-top smart-navbar">

    <div class="container">

        <a class="navbar-brand" href="index.php">
            <img src="img/logoSinFondo.png" class="navbar-logo" width="154" height="54">
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSmartPick">

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="navbarSmartPick">


            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link" href="#inicio">
                        INICIO
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#proyecto">
                        PROYECTO
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#funcionalidades">
                        FUNCIONALIDADES
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#galeria">
                        GALERÍA
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#contacto">
                        CONTACTO
                    </a>
                </li>

            </ul>


            <a href="/proyecto/smartpick/index.php?ruta=login" class="btn btn-login">

                <i class="bi bi-box-arrow-in-right"></i>

                INICIAR SESIÓN

            </a>

        </div>

    </div>

</nav>



<!-- ==========================================
     HERO / INICIO
=========================================== -->

<section id="inicio" class="hero-section">

    <div class="container-fluid p-0">

        <div class="row g-0 align-items-stretch">


            <!-- TEXTO -->

            <div class="col-lg-6 hero-content">

                <div class="hero-text">

                    <span class="hero-label">
                        SMARTPICK
                    </span>

                    <h1>
                        LOGÍSTICA<br>
                        INTELIGENTE,<br>
                        DE PRECISIÓN
                    </h1>

                    <p>
                        Optimizamos la gestión de tu almacén
                        integrando zonas, ubicaciones y pedidos
                        en una sola plataforma.
                    </p>

                    <p class="hero-secondary">
                        Más control, menos errores, mayor eficacia.
                    </p>


                    <div class="hero-buttons">

                        <a
                            href="#proyecto"
                            class="btn btn-primary-smart">

                            CONOCER MÁS

                        </a>

                        <a
                            href="#contacto"
                            class="btn btn-outline-smart">

                            CONTACTO

                        </a>

                    </div>

                </div>

            </div>



            <!-- IMAGEN -->

            <div class="col-lg-6 hero-image">

                <img src="img/hero2.png" height="700" width="800">
                
            </div>


        </div>

    </div>

</section>



<!-- ==========================================
     ESTADÍSTICAS DEL DEPÓSITO
=========================================== -->

<section
    id="datos"
    class="stats-section">

    <div class="container">

        <div class="row text-center">


            <!-- CAPACIDAD -->

            <div class="col-6 col-lg-3 stat-item">

                <div class="stat-circle stat-red">

                    <i class="bi bi-box-seam"></i>

                </div>

                <h3>
                    <?php echo $capacidad; ?>%
                </h3>

                <p>
                    CAPACIDAD OCUPADA
                </p>

            </div>



            <!-- RECEPCIONADOS -->

            <div class="col-6 col-lg-3 stat-item">

                <div class="stat-circle stat-cyan">

                    <i class="bi bi-box-arrow-in-down"></i>

                </div>

                <h3>
                    <?php echo $recepcionados; ?>
                </h3>

                <p>
                    RECEPCIONADOS HOY
                </p>

            </div>



            <!-- PENDIENTES -->

            <div class="col-6 col-lg-3 stat-item">

                <div class="stat-circle stat-blue">

                    <i class="bi bi-clock-history"></i>

                </div>

                <h3>
                    <?php echo $pendientes; ?>
                </h3>

                <p>
                    PEDIDOS PENDIENTES
                </p>

            </div>



            <!-- DESPACHADOS -->

            <div class="col-6 col-lg-3 stat-item">

                <div class="stat-circle stat-purple">

                    <i class="bi bi-truck"></i>

                </div>

                <h3>
                    <?php echo $despachados; ?>
                </h3>

                <p>
                    DESPACHADOS HOY
                </p>

            </div>


        </div>

    </div>

</section>



<!-- ==========================================
     SOBRE EL PROYECTO
=========================================== -->

<section
    id="proyecto"
    class="project-section">

    <div class="container">

        <div class="row align-items-center g-5">


            <img src="img/fachada" alt="">

            <div class="col-lg-5">

                <div class="project-image">

                    <span>
                        <img src="img/fachada.png" height="400" width="900">
                    </span>

                </div>

            </div>



            <!-- INFORMACIÓN -->

            <div class="col-lg-7">

                <span class="section-label">
                    SOBRE EL PROYECTO
                </span>

                <h2>
                    SOBRE EL PROYECTO
                </h2>

                <p>

                    SmartPick nace como una solución orientada
                    a mejorar la organización y gestión de
                    depósitos.

                </p>

                <p>

                    El sistema permite controlar productos,
                    pedidos, zonas y ubicaciones, facilitando
                    el proceso de recepción, almacenamiento,
                    picking y despacho.

                </p>

                <p>

                    Nuestro objetivo es reducir errores,
                    optimizar los tiempos de trabajo y ofrecer
                    información clara sobre el estado de la
                    mercadería dentro del depósito.

                </p>


                <div class="project-features">

                    <div>

                        <i class="bi bi-check-circle-fill"></i>

                        CONTROL DE STOCK

                    </div>

                    <div>

                        <i class="bi bi-check-circle-fill"></i>

                        GESTIÓN DE PEDIDOS

                    </div>

                    <div>

                        <i class="bi bi-check-circle-fill"></i>

                        UBICACIÓN DE PRODUCTOS

                    </div>

                </div>


            </div>

        </div>

    </div>

</section>

<section id="funcionalidades" class="funcionalidades">

    <h2>¿CÓMO FUNCIONA?</h2>

    <p class="descripcion-funcionalidades">
        SmartPick nació en 2026 como respuesta a la creciente demanda de<br>
        soluciones logísticas inteligentes.
    </p>

    <div class="pasos-container">

        <div class="paso">
            <div class="numero">1</div>
            <h3>INGRESO</h3>
            <p>Recepción<br>de mercadería</p>
        </div>

        <div class="paso">
            <div class="numero">2</div>
            <h3>CLASIFICACIÓN</h3>
            <p>Definición<br>de zona</p>
        </div>

        <div class="paso">
            <div class="numero">3</div>
            <h3>UBICACIÓN</h3>
            <p>Estantería<br>y nivel</p>
        </div>

        <div class="paso">
            <div class="numero">4</div>
            <h3>PEDIDO</h3>
            <p>Generación<br>de orden</p>
        </div>

        <div class="paso">
            <div class="numero">5</div>
            <h3>PICKING</h3>
            <p>Recolección<br>asistida</p>
        </div>

        <div class="paso">
            <div class="numero">6</div>
            <h3>SALIDA</h3>
            <p>Pedido<br>completado</p>
        </div>

    </div>

</section>

<!-- =========================
     INFORMACIÓN OPERATIVA
========================= -->

<section id="informacion-operativa" class="informacion-operativa">

    <div class="container">

        <div class="info-header">
            <span class="info-etiqueta">SMARTPICK</span>

            <h2>INFORMACIÓN OPERATIVA</h2>

            <p>
                Procedimientos esenciales para trabajar de forma organizada,
                segura y eficiente dentro del centro logístico.
            </p>
        </div>


        <div class="info-grid">

            <article class="info-card">

                <div class="info-icono">
                    <i class="bi bi-box-seam"></i>
                </div>

                <h3>PROTOCOLO DE ALMACENAMIENTO</h3>

                <p>
                    Toda mercadería debe ser identificada antes de su ingreso
                    y asignada a la zona correspondiente según sus
                    características.
                </p>

                <p>
                    Las ubicaciones deben mantenerse libres de obstáculos
                    y correctamente señalizadas.
                </p>

            </article>


            <article class="info-card">

                <div class="info-icono">
                    <i class="bi bi-clipboard-check"></i>
                </div>

                <h3>GUÍA DE PICKING</h3>

                <p>
                    El operador recibe una hoja de ruta con la zona,
                    ubicación, nivel y cantidad de cada producto que debe retirar.
                </p>

                <p>
                    Cada retiro se confirma en el sistema para actualizar
                    automáticamente el stock.
                </p>

            </article>


            <article class="info-card">

                <div class="info-icono">
                    <i class="bi bi-people"></i>
                </div>

                <h3>PERFILES DEL SISTEMA</h3>

                <p>
                    El administrador gestiona productos, stock, zonas,
                    ubicaciones y pedidos.
                </p>

                <p>
                    El operador prepara los pedidos siguiendo la hoja
                    de ruta generada por el sistema.
                </p>

            </article>

        </div>

    </div>

</section>

<!-- =========================
     PICKING ASISTIDO
========================= -->

<section id="picking-asistido" class="picking-asistido">

    <div class="picking-container">

        <!-- TEXTO -->
        <div class="picking-contenido">

            <span class="picking-etiqueta">
                PICKING ASISTIDO
            </span>

            <h2>
                UNA HOJA DE RUTA<br>
                CLARA PARA<br>
                CADA PEDIDO
            </h2>

            <p>
                SmartPick genera una hoja de ruta que guía al operario
                durante la preparación del pedido, indicando qué productos
                retirar y dónde encontrarlos dentro del depósito.
            </p>

        </div>


        <!-- IMAGEN DE LA HOJA DE RUTA -->
        <div class="picking-imagen">

            <!--
            CUANDO TENGAS LA IMAGEN REAL:

            <img
                src="img/hoja-ruta.png"
                alt="Hoja de ruta generada por SmartPick"
            >
            -->

            <!-- Placeholder temporal -->
            <div class="imagen-placeholder">

                <i class="bi bi-clipboard2-check"></i>

                <span>HOJA DE RUTA</span>

                <p>
                    Vista previa del picking
                </p>

            </div>

        </div>

    </div>

</section>

<!-- =========================
     GALERÍA MULTIMEDIA
========================= -->

<section id="galeria" class="galeria">

    <div class="galeria-header">
        <span class="galeria-etiqueta">SMARTPICK EN ACCIÓN</span>
        <h2>GALERÍA MULTIMEDIA</h2>

        <p>
            Tecnología, organización y eficiencia aplicadas
            a la gestión logística.
        </p>
    </div>


    <div class="galeria-grid">

        <!-- IMAGEN 1 -->
        <div class="galeria-item">

            <img
                src="img/Empleada.jpg"
                alt="Operario realizando picking"
            >

            <div class="galeria-overlay">
                <span>OPERACIÓN</span>
                <h3>Picking asistido</h3>
                <p>Preparación eficiente de pedidos.</p>
            </div>

        </div>


        <!-- IMAGEN 2 -->
        <div class="galeria-item">

            <img
                src="img/robot.jpg"
                alt="Automatización logística"
            >

            <div class="galeria-overlay">
                <span>TECNOLOGÍA</span>
                <h3>Automatización</h3>
                <p>Tecnología aplicada al centro logístico.</p>
            </div>

        </div>


        <!-- IMAGEN 3 -->
        <div class="galeria-item">

            <img
                src="img/cinta.jpg"
                alt="Centro logístico"
            >

            <div class="galeria-overlay">
                <span>LOGÍSTICA</span>
                <h3>Organización del depósito</h3>
                <p>Control y distribución de la mercadería.</p>
            </div>

        </div>

    </div>

</section>


<!-- =========================
     CONTACTO
========================= -->

<section id="contacto" class="contacto">

    <div class="contacto-container">

        <div class="contacto-header">
            <span class="contacto-etiqueta">HABLEMOS</span>
            <h2>CONTACTANOS</h2>
            <p>
                ¿Tenés una consulta o querés saber más sobre SmartPick?
                Escribinos y te responderemos a la brevedad.
            </p>
        </div>


        <div class="contacto-grid">

            <!-- FORMULARIO -->
            <div class="contacto-formulario">

                <form action="procesar_contacto.php" method="POST">

                    <div class="form-grupo">
                        <label for="nombre">Nombre</label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            placeholder="Tu nombre"
                            required
                        >
                    </div>


                    <div class="form-grupo">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="correo@ejemplo.com"
                            required
                        >
                    </div>


                    <div class="form-grupo">
                        <label for="telefono">Teléfono</label>
                        <input
                            type="tel"
                            id="telefono"
                            name="telefono"
                            placeholder="09X XXX XXX"
                        >
                    </div>


                    <div class="form-grupo">
                        <label for="mensaje">Mensaje</label>

                        <textarea
                            id="mensaje"
                            name="mensaje"
                            rows="6"
                            placeholder="Escribí tu consulta..."
                            required
                        ></textarea>
                    </div>


                    <button type="submit" class="btn-contacto">
                        ENVIAR MENSAJE
                        <i class="bi bi-arrow-right"></i>
                    </button>

                </form>

            </div>


            <!-- INFORMACIÓN -->
            <div class="contacto-info">

                <div class="contacto-card">

                    <h3>SMARTPICK</h3>

                    <p>
                        Plataforma de gestión logística para optimizar
                        el almacenamiento, picking y control de pedidos.
                    </p>


                    <div class="dato-contacto">
                        <i class="bi bi-envelope"></i>

                        <div>
                            <span>Email</span>
                            <a href="mailto:smartpick2026@gmail.com">
                                smartpick2026@gmail.com
                            </a>
                        </div>
                    </div>


                    <div class="dato-contacto">
                        <i class="bi bi-geo-alt"></i>

                        <div>
                            <span>Ubicación</span>
                            <p>Montevideo, Uruguay</p>
                        </div>
                    </div>


                    <div class="redes-contacto">

                        <a href="#" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>

                        <a href="#" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>

                    </div>

                </div>


                <!-- MAPA -->
                <div class="mapa-placeholder">

                    <i class="bi bi-geo-alt-fill"></i>

                    <h4>UBICACIÓN</h4>

                    <p>
                        Acá podés agregar posteriormente
                        Google Maps.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     FOOTER
========================= -->

<footer class="footer">

    <div class="footer-container">

        <!-- LOGO -->
        <div class="footer-logo">
            <img src="img/logoSinFondo.png" alt="SmartPick">
        </div>


        <!-- NAVEGACIÓN -->
        <div class="footer-links">

            <div class="footer-columna">
                <a href="#inicio">Inicio</a>
                <a href="#galeria">Galería</a>
            </div>

            <div class="footer-columna">
                <a href="#proyecto">Sobre el Proyecto</a>
                <a href="#contacto">Contacto</a>
            </div>

            <div class="footer-columna">
                <a href="#funcionalidades">Funcionalidades</a>
                <a href="#informacion-operativa">Información Operativa</a>
            </div>

        </div>

    </div>


    <!-- PARTE INFERIOR -->
    <div class="footer-bottom">

        <p>
            © 2026 SmartPick. Todos los derechos reservados.
        </p>

        <p class="footer-creditos">
            Soluciones logísticas inteligentes.
        </p>

    </div>

</footer>


<!-- BOOTSTRAP JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>


<!-- JS PROPIO -->

<script src="script.js"></script>


</body>

</html>