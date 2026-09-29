<?php

class DashboardRepositorio
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }


    // ==========================================
    // STOCK TOTAL
    // ==========================================

    public function obtenerStockTotal()
    {
        $sql = "
            SELECT COALESCE(SUM(Stock_actual), 0) AS total
            FROM producto
        ";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'];
    }


    // ==========================================
    // PEDIDOS PENDIENTES
    // ==========================================

    public function obtenerPedidosPendientes()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM pedido
            WHERE LOWER(Estado) = 'pendiente'
        ";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'];
    }


    // ==========================================
    // CANTIDAD DE OPERARIOS
    // ==========================================

    public function obtenerCantidadOperarios()
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM operario
            WHERE LOWER(Rol) = 'operario'
        ";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'];
    }


    // ==========================================
    // PRODUCTOS CON STOCK BAJO
    // ==========================================

    public function obtenerStockBajo()
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM Producto
        WHERE UPPER(Tipo) = 'ALMACEN'
          AND Stock_actual <= Stock_minimo
    ";

    $stmt = $this->conexion->prepare($sql);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return (int)($resultado['total'] ?? 0);
}


    // ==========================================
    // ÚLTIMOS PEDIDOS
    // ==========================================

    public function obtenerPedidosRecientes()
    {
        $sql = "
            SELECT
                ID_pedido,
                Fecha_ingresado,
                Estado
            FROM pedido
            ORDER BY ID_pedido DESC
            LIMIT 5
        ";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}