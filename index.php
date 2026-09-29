<?php

// =============================================
// CONFIGURACIÓN DE SESIÓN
// =============================================

// La cookie de sesión dura solamente mientras
// el navegador permanezca abierto.
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

require('conexion.php');
require('modelos/Usuario.php');
require('modelos/UsuarioRepositorio.php');
require('modelos/DashboardRepositorio.php');
require('modelos/PedidosRepositorio.php');
require('modelos/ClienteRepositorio.php');
require('modelos/ProductoRepositorio.php');
require('modelos/OperarioRepositorio.php');
require('modelos/PickingRepositorio.php');
require('modelos/ZonaRepositorio.php');
require('modelos/PaqueteRepositorio.php');

// =====================================
// REPOSITORIO
// =====================================

$usuarioRepositorio = new UsuarioRepositorio($conexion);

$dashboardRepositorio = new DashboardRepositorio($conexion);

$pedidoRepositorio = new PedidoRepositorio($conexion);

$clienteRepositorio = new ClienteRepositorio($conexion);

$productoRepositorio = new ProductoRepositorio($conexion);

$operarioRepositorio = new OperarioRepositorio($conexion);

$pickingRepositorio = new PickingRepositorio($conexion);

$zonaRepositorio = new ZonaRepositorio($conexion);

$paqueteRepositorio = new PaqueteRepositorio($conexion);

// =====================================
// RUTA
// =====================================

$ruta = $_GET['ruta'] ?? 'inicio';





// =====================================
// ROUTER + CONTROLADOR
// =====================================

switch ($ruta) {


    // ---------------------------------
    // LANDING
    // ---------------------------------

    case 'inicio':

    require('vistas/index.php');

    break;



    // ---------------------------------
    // LOGIN
    // ---------------------------------

    case 'login':

        // Si se envió el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ci = trim($_POST['usuario'] ?? '');

            $contrasena = $_POST['contrasena'] ?? '';


            // Buscar usuario en la base
            $usuario = $usuarioRepositorio->buscarPorCI($ci);


            // Validar usuario y contraseña
            if (
                $usuario !== null &&
                $usuario->contrasena === $contrasena
            ) {

                // Guardar datos en sesión
                $_SESSION['usuario'] = [

                    'ci' => $usuario->ci,

                    'nombre' => $usuario->nombre,

                    'mail' => $usuario->mail,

                    'rol' => $usuario->rol

                ];


                // Ir al dashboard
                header(
                    "Location: index.php?ruta=dashboard"
                );

                exit;
            }


            // Si usuario o contraseña son incorrectos
            header(
                "Location: index.php?ruta=login&error=credenciales"
            );

            exit;
        }


        // Si simplemente entramos al login
        require_once __DIR__ . '/vistas/login.php';

        break;



    // ---------------------------------
    // DASHBOARD
    // ---------------------------------

    case 'dashboard':

    // Si no inició sesión
    if (!isset($_SESSION['usuario'])) {

        header("Location: index.php?ruta=login");

        exit;
    }


    // =====================================
    // DATOS DEL DASHBOARD
    // =====================================

    $stockTotal =
        $dashboardRepositorio->obtenerStockTotal();

    $pedidosPendientes =
        $dashboardRepositorio->obtenerPedidosPendientes();

    $cantidadOperarios =
        $dashboardRepositorio->obtenerCantidadOperarios();

    $stockBajo =
        $dashboardRepositorio->obtenerStockBajo();

    $pedidosRecientes =
        $dashboardRepositorio->obtenerPedidosRecientes();


    require_once __DIR__ . '/vistas/dashboard.php';

    break;

    // ---------------------------------
// CLIENTES
// ---------------------------------

case 'clientes':

    if (!isset($_SESSION['usuario'])) {

        header("Location: index.php?ruta=login");
        exit;
    }

    if (
        strtolower($_SESSION['usuario']['rol'])
        !== 'administrador'
    ) {

        http_response_code(403);

        echo "No tiene permisos para gestionar clientes.";
        exit;
    }


    $clientes =
        $clienteRepositorio->obtenerTodos();


    require_once
        __DIR__ . '/vistas/clientes.php';

    break;


// ---------------------------------
// NUEVO CLIENTE
// ---------------------------------

case 'nuevo-cliente':

    if (!isset($_SESSION['usuario'])) {

        header("Location: index.php?ruta=login");
        exit;
    }


    if (
        strtolower($_SESSION['usuario']['rol'])
        !== 'administrador'
    ) {

        http_response_code(403);

        echo "No tiene permisos para registrar clientes.";
        exit;
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $ci =
            trim($_POST['ci'] ?? '');

        $nombre =
            trim($_POST['nombre'] ?? '');

        $telefono =
            trim($_POST['telefono'] ?? '');

        $mail =
            trim($_POST['mail'] ?? '');

        $direccion =
            trim($_POST['direccion'] ?? '');


        if ($ci === '' || $nombre === '') {

            $_SESSION['error_cliente'] =
                "La CI y el nombre son obligatorios.";

            header(
                "Location: index.php?ruta=nuevo-cliente"
            );

            exit;
        }


        if (
            $mail !== '' &&
            !filter_var($mail, FILTER_VALIDATE_EMAIL)
        ) {

            $_SESSION['error_cliente'] =
                "El correo electrónico no es válido.";

            header(
                "Location: index.php?ruta=nuevo-cliente"
            );

            exit;
        }


        try {

            $clienteRepositorio->crear(
                $ci,
                $nombre,
                $telefono,
                $mail,
                $direccion
            );


            $_SESSION['mensaje_cliente'] =
                "Cliente registrado correctamente.";


            header(
                "Location: index.php?ruta=clientes"
            );

            exit;


        } catch (Exception $e) {

            $_SESSION['error_cliente'] =
                $e->getMessage();


            header(
                "Location: index.php?ruta=nuevo-cliente"
            );

            exit;
        }
    }


    require_once
        __DIR__ . '/vistas/nuevo_cliente.php';

    break;

    // ---------------------------------
    // PEDIDOS
    // ---------------------------------

    case 'pedidos':

    // Traer pedidos desde la base
    $pedidos =
        $pedidoRepositorio->obtenerTodos();


    require_once __DIR__ . '/vistas/pedidos.php';

    break;

    case 'nuevo-pedido':

    if (!isset($_SESSION['usuario'])) {

        header("Location: index.php?ruta=login");
        exit;
    }


    require_once
        __DIR__ . '/vistas/nuevo_pedido.php';

    break;

    case 'buscar-clientes':

    if (!isset($_SESSION['usuario'])) {

        http_response_code(401);

        echo json_encode([
            'error' => 'Sesión no válida'
        ]);

        exit;
    }


    header('Content-Type: application/json');


    $texto =
        trim($_GET['q'] ?? '');


    if ($texto === '') {

        echo json_encode([]);

        exit;
    }


    $clientes =
        $clienteRepositorio->buscar($texto);


    echo json_encode($clientes);

    exit;

    case 'paquetes-cliente':

    if (!isset($_SESSION['usuario'])) {

        http_response_code(401);

        echo json_encode([
            'error' => 'Sesión no válida'
        ]);

        exit;
    }


    header('Content-Type: application/json');


    $ci =
        trim($_GET['ci'] ?? '');


    if ($ci === '') {

        echo json_encode([]);

        exit;
    }


    $paquetes =
        $pedidoRepositorio
            ->obtenerPaquetesCliente($ci);


    echo json_encode($paquetes);

    exit;

    case 'buscar-productos':

    if (!isset($_SESSION['usuario'])) {

        http_response_code(401);

        echo json_encode([
            'error' => 'Sesión no válida'
        ]);

        exit;
    }


    header('Content-Type: application/json');


    $texto =
        trim($_GET['q'] ?? '');


    if ($texto === '') {

        echo json_encode([]);

        exit;
    }


    $productos =
        $productoRepositorio
            ->buscarProductosAlmacen($texto);


    echo json_encode($productos);

    exit;

    case 'guardar-entrega':

    if (!isset($_SESSION['usuario'])) {

        header("Location: index.php?ruta=login");
        exit;
    }


    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

        header(
            "Location: index.php?ruta=nuevo-pedido"
        );

        exit;
    }


    $ciCliente =
        trim($_POST['ci_cliente'] ?? '');


    $idsProductos =
        $_POST['producto_id'] ?? [];


    $cantidades =
        $_POST['cantidad'] ?? [];


    $paquetesSeleccionados =
        $_POST['paquetes'] ?? [];


    // ==========================================
    // VALIDAR CLIENTE
    // ==========================================

    if ($ciCliente === '') {

        $_SESSION['error_entrega'] =
            "Debe seleccionar un cliente.";


        header(
            "Location: index.php?ruta=nuevo-pedido"
        );

        exit;
    }


    // ==========================================
    // ARMAR PRODUCTOS DE ALMACÉN
    // ==========================================

    $productosPedido = [];


    foreach ($idsProductos as $indice => $idProducto) {

        $idProducto =
            (int)$idProducto;


        $cantidad =
            (int)($cantidades[$indice] ?? 0);


        if (
            $idProducto > 0 &&
            $cantidad > 0
        ) {

            $productosPedido[] = [

                'id_producto' =>
                    $idProducto,

                'cantidad' =>
                    $cantidad

            ];
        }
    }


    // ==========================================
    // DEBE HABER ALGO PARA ENTREGAR
    // ==========================================

    if (
        empty($productosPedido) &&
        empty($paquetesSeleccionados)
    ) {

        $_SESSION['error_entrega'] =
            "Debe seleccionar al menos un paquete o producto.";


        header(
            "Location: index.php?ruta=nuevo-pedido"
        );

        exit;
    }


    try {


        // ==========================================
        // 1. RETIRAR PAQUETES DEL CLIENTE
        // ==========================================

        if (!empty($paquetesSeleccionados)) {

            $pedidoRepositorio
                ->retirarPaquetesCliente(
                    $ciCliente,
                    $paquetesSeleccionados
                );
        }


        // ==========================================
        // 2. CREAR PEDIDO DE PRODUCTOS DE ALMACÉN
        // ==========================================

        if (!empty($productosPedido)) {

            $idPedidoNuevo =
                $pedidoRepositorio
                    ->crearPedidoAlmacen(
                        $ciCliente,
                        $productosPedido
                    );
        }


        // ==========================================
        // MENSAJE
        // ==========================================

        if (
    !empty($paquetesSeleccionados) &&
    !empty($productosPedido)
) {

    $_SESSION['mensaje_exito'] =
        "Retiro de paquete solicitado y pedido de almacén generado correctamente.";

} elseif (!empty($paquetesSeleccionados)) {

    $_SESSION['mensaje_exito'] =
        "Retiro de paquete solicitado correctamente.";

} else {

    $_SESSION['mensaje_exito'] =
        "Pedido de almacén generado correctamente.";
}


        header(
            "Location: index.php?ruta=pedidos"
        );

        exit;


    } catch (Exception $e) {

        $_SESSION['error_entrega'] =
            $e->getMessage();


        header(
            "Location: index.php?ruta=nuevo-pedido"
        );

        exit;
    }

    break;

    case 'ver-pedido':

    if (!isset($_SESSION['usuario'])) {

        header("Location: index.php?ruta=login");
        exit;
    }


    $idPedido =
        (int)($_GET['id'] ?? 0);


    if ($idPedido <= 0) {

        header("Location: index.php?ruta=pedidos");
        exit;
    }


    $pedido =
        $pedidoRepositorio
            ->obtenerDetalle($idPedido);


    if (!$pedido) {

        http_response_code(404);

        echo "Pedido no encontrado.";

        exit;
    }


    $productosPedido =
        $pedidoRepositorio
            ->obtenerProductosPedido(
                $idPedido
            );


    $operarios =
        $operarioRepositorio
            ->obtenerOperarios();


    require_once
        __DIR__ . '/vistas/ver_pedido.php';

    break;

    case 'asignar-operario':

    if (!isset($_SESSION['usuario'])) {

        header("Location: index.php?ruta=login");
        exit;
    }


    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

        header("Location: index.php?ruta=pedidos");
        exit;
    }


    $idPedido =
        (int)($_POST['id_pedido'] ?? 0);


    $ciOperario =
        trim($_POST['ci_operario'] ?? '');


    if (
        $idPedido <= 0 ||
        $ciOperario === ''
    ) {

        $_SESSION['error_pedido'] =
            "Debe seleccionar un operario.";

        header(
            "Location: index.php?ruta=ver-pedido&id="
            . $idPedido
        );

        exit;
    }


    $operario =
        $operarioRepositorio
            ->buscarPorCI($ciOperario);


    if (!$operario) {

        $_SESSION['error_pedido'] =
            "El operario seleccionado no es válido.";

        header(
            "Location: index.php?ruta=ver-pedido&id="
            . $idPedido
        );

        exit;
    }


    try {

        $pedidoRepositorio
            ->asignarOperario(
                $idPedido,
                $ciOperario
            );


        $_SESSION['mensaje_pedido'] =
            "Operario asignado correctamente.";


    } catch (Exception $e) {

        $_SESSION['error_pedido'] =
            $e->getMessage();
    }


    header(
        "Location: index.php?ruta=ver-pedido&id="
        . $idPedido
    );

    exit;

    // ---------------------------------
    // PICKING
    // ---------------------------------

    case 'picking':

    if (!isset($_SESSION['usuario'])) {

        header("Location: index.php?ruta=login");
        exit;
    }


    $ciOperario =
        $_SESSION['usuario']['ci'];


    $pedidosPicking =
        $pickingRepositorio
            ->obtenerPedidosOperario(
                $ciOperario
            );


    require_once
        __DIR__ . '/vistas/picking.php';

    break;

    // ---------------------------------
    // HOJA DE PICKCING
    // ---------------------------------

    case 'hoja-picking':

    if (!isset($_SESSION['usuario'])) {

        header("Location: index.php?ruta=login");
        exit;
    }


    $idPedido =
        (int)($_GET['id'] ?? 0);


    if ($idPedido <= 0) {

        header(
            "Location: index.php?ruta=picking"
        );

        exit;
    }


    $ciOperario =
        $_SESSION['usuario']['ci'];


    $hojaPicking =
        $pickingRepositorio
            ->obtenerHojaPicking(
                $idPedido,
                $ciOperario
            );


    if (empty($hojaPicking)) {

        http_response_code(404);

        echo "
            El pedido no existe,
            no está en preparación
            o no está asignado a este operario.
        ";

        exit;
    }


    require_once
        __DIR__ . '/vistas/hoja_picking.php';

    break;

    case 'confirmar-picking':

    if (!isset($_SESSION['usuario'])) {

        http_response_code(401);

        header('Content-Type: application/json');

        echo json_encode([
            'ok' => false,
            'mensaje' => 'Sesión no válida.'
        ]);

        exit;
    }


    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

        http_response_code(405);

        header('Content-Type: application/json');

        echo json_encode([
            'ok' => false,
            'mensaje' => 'Método no permitido.'
        ]);

        exit;
    }


    header('Content-Type: application/json');


    $idPedido =
        (int)($_POST['id_pedido'] ?? 0);


    $idProducto =
        (int)($_POST['id_producto'] ?? 0);


    $ciOperario =
        $_SESSION['usuario']['ci'];


    if (
        $idPedido <= 0 ||
        $idProducto <= 0
    ) {

        http_response_code(400);

        echo json_encode([
            'ok' => false,
            'mensaje' =>
                'Datos inválidos.'
        ]);

        exit;
    }


    try {


        $resultado =
            $pickingRepositorio
                ->confirmarProducto(
                    $idPedido,
                    $idProducto,
                    $ciOperario
                );


        echo json_encode([
            'ok' => true,
            'terminado' =>
                $resultado['terminado']
        ]);


    } catch (Exception $e) {


        http_response_code(400);


        echo json_encode([

            'ok' => false,

            'mensaje' =>
                $e->getMessage()

        ]);
    }


    exit;

    case 'validar-pedido':

    if (!isset($_SESSION['usuario'])) {

        header(
            "Location: index.php?ruta=login"
        );

        exit;
    }


    // SOLO ADMINISTRADOR

    if (
        strtolower(
            $_SESSION['usuario']['rol']
        ) !== 'administrador'
    ) {

        http_response_code(403);

        echo "No tiene permisos para validar pedidos.";

        exit;
    }


    if (
        $_SERVER['REQUEST_METHOD']
        !== 'POST'
    ) {

        header(
            "Location: index.php?ruta=pedidos"
        );

        exit;
    }


    $idPedido =
        (int)($_POST['id_pedido'] ?? 0);


    if ($idPedido <= 0) {

        $_SESSION['error_pedido'] =
            "Pedido inválido.";

        header(
            "Location: index.php?ruta=pedidos"
        );

        exit;
    }


    try {


        $estadoFinal =
    $pedidoRepositorio
        ->validarPedido(
            $idPedido
        );


if ($estadoFinal === 'RETIRADO') {

    $_SESSION['mensaje_pedido'] =
        "Paquete #$idPedido validado y retirado correctamente.";

} else {

    $_SESSION['mensaje_pedido'] =
        "Pedido #$idPedido validado y entregado correctamente.";
}


    } catch (Exception $e) {


        $_SESSION['error_pedido'] =
            $e->getMessage();

    }


    header(
        "Location: index.php?ruta=ver-pedido&id="
        . $idPedido
    );

    exit;

    // ---------------------------------
// INGRESAR PAQUETE
// ---------------------------------

case 'ingresar-paquete':

    if (!isset($_SESSION['usuario'])) {

        header("Location: index.php?ruta=login");
        exit;
    }


    // SOLO ADMINISTRADOR

    if (
        strtolower($_SESSION['usuario']['rol'])
        !== 'administrador'
    ) {

        http_response_code(403);

        echo "No tiene permisos para ingresar paquetes.";
        exit;
    }


    // ==========================================
    // GUARDAR PAQUETE
    // ==========================================

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $ciCliente =
            trim($_POST['ci_cliente'] ?? '');

        $descripcion =
            trim($_POST['descripcion'] ?? '');

        $categoria =
            trim($_POST['categoria'] ?? '');

        $idZona =
            (int)($_POST['id_zona'] ?? 0);


        if (
            $ciCliente === '' ||
            $descripcion === '' ||
            $categoria === '' ||
            $idZona <= 0
        ) {

            $_SESSION['error_paquete'] =
                "Debe completar todos los datos del paquete.";

            header(
                "Location: index.php?ruta=ingresar-paquete"
            );

            exit;
        }


        try {

            $resultado =
                $paqueteRepositorio->crear(
                    $ciCliente,
                    $descripcion,
                    $categoria,
                    $idZona
                );


            $_SESSION['mensaje_paquete'] =
                "Paquete ingresado correctamente. "
                . "Pedido #"
                . $resultado['id_pedido']
                . ".";


            header(
                "Location: index.php?ruta=ingresar-paquete"
            );

            exit;


        } catch (Exception $e) {

            $_SESSION['error_paquete'] =
                $e->getMessage();


            header(
                "Location: index.php?ruta=ingresar-paquete"
            );

            exit;
        }
    }


    // ==========================================
    // DATOS DEL FORMULARIO
    // ==========================================

    $clientes =
        $clienteRepositorio->obtenerTodos();

    $zonas =
        $zonaRepositorio->obtenerTodas();


    require_once
        __DIR__ . '/vistas/ingresar_paquete.php';

    break;

    // ---------------------------------
// PRODUCTOS
// ---------------------------------

case 'productos':

    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php?ruta=login");
        exit;
    }

    if (
        strtolower($_SESSION['usuario']['rol'])
        !== 'administrador'
    ) {
        http_response_code(403);
        echo "No tiene permisos para gestionar productos.";
        exit;
    }


    $productos =
        $productoRepositorio
            ->obtenerProductosAlmacen();


    require_once
        __DIR__ . '/vistas/productos.php';

    break;

    // ---------------------------------
// NUEVO PRODUCTO
// ---------------------------------

case 'nuevo-producto':

    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php?ruta=login");
        exit;
    }

    if (
        strtolower($_SESSION['usuario']['rol'])
        !== 'administrador'
    ) {
        http_response_code(403);
        echo "No tiene permisos para registrar productos.";
        exit;
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $descripcion =
            trim($_POST['descripcion'] ?? '');

        $categoria =
            trim($_POST['categoria'] ?? '');

        $stockActual =
            (int)($_POST['stock_actual'] ?? 0);

        $stockMinimo =
            (int)($_POST['stock_minimo'] ?? 0);

        $idZona =
            (int)($_POST['id_zona'] ?? 0);


        if (
            $descripcion === '' ||
            $categoria === '' ||
            $idZona <= 0
        ) {

            $_SESSION['error_producto'] =
                "Debe completar todos los datos obligatorios.";

            header(
                "Location: index.php?ruta=nuevo-producto"
            );

            exit;
        }


        if (
            $stockActual < 0 ||
            $stockMinimo < 0
        ) {

            $_SESSION['error_producto'] =
                "El stock no puede tener valores negativos.";

            header(
                "Location: index.php?ruta=nuevo-producto"
            );

            exit;
        }


        try {

            $idProducto =
                $productoRepositorio
                    ->crearProductoAlmacen(
                        $descripcion,
                        $categoria,
                        $stockActual,
                        $stockMinimo,
                        $idZona
                    );


            $_SESSION['mensaje_producto'] =
                "Producto registrado correctamente. ID #"
                . $idProducto;


            header(
                "Location: index.php?ruta=productos"
            );

            exit;


        } catch (Exception $e) {

            $_SESSION['error_producto'] =
                $e->getMessage();

            header(
                "Location: index.php?ruta=nuevo-producto"
            );

            exit;
        }
    }


    $zonas =
        $zonaRepositorio->obtenerTodas();


    require_once
        __DIR__ . '/vistas/nuevo_producto.php';

    break;

    // ---------------------------------
// MODIFICAR PRODUCTO
// ---------------------------------

case 'modificar-producto':

    if (!isset($_SESSION['usuario'])) {

        header("Location: index.php?ruta=login");
        exit;
    }


    if (
        strtolower($_SESSION['usuario']['rol'])
        !== 'administrador'
    ) {

        http_response_code(403);

        echo "No tiene permisos para modificar productos.";
        exit;
    }


    $idProducto =
        (int)(
            $_POST['id_producto']
            ?? $_GET['id']
            ?? 0
        );


    if ($idProducto <= 0) {

        $_SESSION['error_producto'] =
            "Debe seleccionar un producto.";

        header(
            "Location: index.php?ruta=productos"
        );

        exit;
    }


    $producto =
        $productoRepositorio
            ->obtenerProductoAlmacenPorId(
                $idProducto
            );


    if (!$producto) {

        $_SESSION['error_producto'] =
            "El producto seleccionado no existe.";

        header(
            "Location: index.php?ruta=productos"
        );

        exit;
    }


    // GUARDAR MODIFICACIÓN

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $descripcion =
            trim($_POST['descripcion'] ?? '');

        $categoria =
            trim($_POST['categoria'] ?? '');

        $stockMinimo =
            (int)($_POST['stock_minimo'] ?? 0);

        $idZona =
            (int)($_POST['id_zona'] ?? 0);


        if (
            $descripcion === '' ||
            $categoria === '' ||
            $idZona <= 0
        ) {

            $_SESSION['error_producto'] =
                "Debe completar todos los datos obligatorios.";

            header(
                "Location: index.php?ruta=modificar-producto&id="
                . $idProducto
            );

            exit;
        }


        if ($stockMinimo < 0) {

            $_SESSION['error_producto'] =
                "El stock mínimo no puede ser negativo.";

            header(
                "Location: index.php?ruta=modificar-producto&id="
                . $idProducto
            );

            exit;
        }


        try {

            $productoRepositorio
                ->modificarProductoAlmacen(
                    $idProducto,
                    $descripcion,
                    $categoria,
                    $stockMinimo,
                    $idZona
                );


            $_SESSION['mensaje_producto'] =
                "Producto modificado correctamente.";


            header(
                "Location: index.php?ruta=productos"
            );

            exit;


        } catch (Exception $e) {

            $_SESSION['error_producto'] =
                $e->getMessage();


            header(
                "Location: index.php?ruta=modificar-producto&id="
                . $idProducto
            );

            exit;
        }
    }


    $zonas =
        $zonaRepositorio->obtenerTodas();


    require_once
        __DIR__
        . '/vistas/modificar_producto.php';

    break;

    // ---------------------------------
// INVENTARIO
// ---------------------------------

case 'inventario':

    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php?ruta=login");
        exit;
    }

    if (
        strtolower($_SESSION['usuario']['rol'])
        !== 'administrador'
    ) {
        http_response_code(403);
        echo "No tiene permisos para gestionar el inventario.";
        exit;
    }


    $inventario =
        $productoRepositorio
            ->obtenerInventarioAlmacen();

            $paquetesDeposito =
    $productoRepositorio
        ->obtenerPaquetesDeposito();


    require_once
        __DIR__ . '/vistas/inventario.php';

    break;

    // ---------------------------------
// REPONER STOCK
// ---------------------------------

case 'reponer-stock':

    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php?ruta=login");
        exit;
    }


    if (
        strtolower($_SESSION['usuario']['rol'])
        !== 'administrador'
    ) {
        http_response_code(403);
        echo "No tiene permisos para reponer stock.";
        exit;
    }


    // ==========================================
    // GUARDAR REPOSICIÓN
    // ==========================================

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $idProducto =
            (int)($_POST['id_producto'] ?? 0);

        $cantidad =
            (int)($_POST['cantidad'] ?? 0);


        if ($idProducto <= 0) {

            $_SESSION['error_inventario'] =
                "Debe seleccionar un producto.";

            header(
                "Location: index.php?ruta=inventario"
            );

            exit;
        }


        if ($cantidad <= 0) {

            $_SESSION['error_inventario'] =
                "La cantidad a reponer debe ser mayor a cero.";

            header(
                "Location: index.php?ruta=reponer-stock&id="
                . $idProducto
            );

            exit;
        }


        try {

            $resultado =
                $productoRepositorio
                    ->reponerStock(
                        $idProducto,
                        $cantidad
                    );


            $_SESSION['mensaje_inventario'] =
                $resultado['producto']
                . ": se agregaron "
                . $resultado['cantidad']
                . " unidades. Stock actual: "
                . $resultado['stock_nuevo']
                . ".";


            header(
                "Location: index.php?ruta=inventario"
            );

            exit;


        } catch (Exception $e) {

            $_SESSION['error_inventario'] =
                $e->getMessage();


            header(
                "Location: index.php?ruta=inventario"
            );

            exit;
        }
    }

    


    // ==========================================
    // MOSTRAR FORMULARIO
    // ==========================================

    $idProducto =
        (int)($_GET['id'] ?? 0);


    if ($idProducto <= 0) {

        $_SESSION['error_inventario'] =
            "Debe seleccionar un producto.";

        header(
            "Location: index.php?ruta=inventario"
        );

        exit;
    }


    $producto =
        $productoRepositorio
            ->obtenerProductoAlmacenPorId(
                $idProducto
            );


    if (!$producto) {

        $_SESSION['error_inventario'] =
            "El producto seleccionado no existe.";

        header(
            "Location: index.php?ruta=inventario"
        );

        exit;
    }


    require_once
        __DIR__ . '/vistas/reponer_stock.php';

    break;

   // ---------------------------------
// MAPA DEL DEPÓSITO
// ---------------------------------

case 'zonas':

    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php?ruta=login");
        exit;
    }

    $zonas = $zonaRepositorio->obtenerTodas();

    $zonaDestino = isset($_GET['destino'])
        ? (int) $_GET['destino']
        : 0;

    require_once __DIR__ . '/vistas/zonas.php';

    break;

    // ---------------------------------
    // CERRAR SESIÓN
    // ---------------------------------

    case 'logout':

        session_unset();

        session_destroy();


        header(
            "Location: index.php?ruta=login"
        );

        exit;



    // ---------------------------------
    // 404
    // ---------------------------------

    default:

        http_response_code(404);

        echo "<h1>Página no encontrada</h1>";

        echo "
            <a href='index.php?ruta=inicio'>
                Volver al inicio
            </a>
        ";

        break;
}

