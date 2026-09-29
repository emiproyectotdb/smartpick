<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Iniciar sesión | SmartPick</title>


    <!-- BEBAS NEUE + MONTSERRAT -->
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- BOOTSTRAP ICONS -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- CSS DEL LOGIN -->
    <link
        rel="stylesheet"
        href="/proyecto/smartpick/css/login.css"
    >

</head>


<body>


    <!-- ========================================
         NAVBAR
    ========================================= -->

    <header class="login-navbar">

        <a
            href="/proyecto/smartpick/logo"
            class="login-logo"
        >

            <img src="/proyecto/smartpick/img/logoSinFondo.png" width="154" height="54">

        </a>

    </header>



    <!-- ========================================
         LOGIN
    ========================================= -->

    <main class="login-page">


        <!-- OSCURECE LA IMAGEN DE FONDO -->

        <div class="login-overlay"></div>



        <!-- TARJETA -->

        <section class="login-card">


            <!-- TÍTULO -->

            <div class="login-titulo">

                <i class="bi bi-person-circle"></i>

                <h1>INICIAR SESIÓN</h1>

            </div>

            
            <!-- FORMULARIO -->

            <form
                action="/proyecto/smartpick/index.php?ruta=login"
                method="POST"
                class="form-login"
            >


                <!-- USUARIO -->

                <div class="campo-login">

                    <i class="bi bi-person"></i>

                    <input
                        type="text"
                        name="usuario"
                        id="usuario"
                        placeholder="INGRESE SU USUARIO"
                        autocomplete="username"
                        required
                    >

                </div>



                <!-- CONTRASEÑA -->

                <div class="campo-login">

                    <i class="bi bi-lock"></i>

                    <input
                        type="password"
                        name="contrasena"
                        id="contrasena"
                        placeholder="INGRESE SU CONTRASEÑA"
                        autocomplete="current-password"
                        required
                    >


                    <!-- MOSTRAR / OCULTAR CONTRASEÑA -->

                    <button
                        type="button"
                        class="mostrar-password"
                        id="mostrarPassword"
                        aria-label="Mostrar contraseña"
                    >

                        <i class="bi bi-eye"></i>

                    </button>

                </div>



                <!-- BOTÓN -->

                <button
                    type="submit"
                    class="btn-ingresar"
                >

                    <i class="bi bi-box-arrow-in-right"></i>

                    INGRESAR

                </button>


            </form>



            <!-- RECUPERAR CONTRASEÑA -->

            <a
                href="#"
                class="recuperar-password"
            >
                ¿Olvidaste tu contraseña?
            </a>


            <!-- VOLVER -->

            <a
                href="/proyecto/smartpick/"
                class="volver-inicio"
            >

                <i class="bi bi-arrow-left"></i>

                Volver al inicio

            </a>


        </section>

    </main>



    <!-- ========================================
         JAVASCRIPT
    ========================================= -->

    <script>

        const botonPassword =
            document.getElementById('mostrarPassword');

        const inputPassword =
            document.getElementById('contrasena');


        botonPassword.addEventListener('click', function () {

            if (inputPassword.type === 'password') {

                inputPassword.type = 'text';

                this.innerHTML =
                    '<i class="bi bi-eye-slash"></i>';

            } else {

                inputPassword.type = 'password';

                this.innerHTML =
                    '<i class="bi bi-eye"></i>';

            }

        });

    </script>


</body>

</html>