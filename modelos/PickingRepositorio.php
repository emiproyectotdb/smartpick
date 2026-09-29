<?php

class PickingRepositorio
{
    private $conexion;


    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }


    // =========================================
    // PEDIDOS ASIGNADOS AL OPERARIO
    // =========================================

    public function obtenerPedidosOperario($ciOperario)
    {
        $sql = "
            SELECT DISTINCT
                pe.ID_pedido,
                pe.Fecha_ingresado,
                pe.Estado,
                re.CI_cliente,
                pc.Nombre_completo AS Cliente,

                CASE
                    WHEN UPPER(p.Tipo) = 'DEPOSITO'
                    THEN 'DEPOSITO'
                    ELSE 'ALMACEN'
                END AS Tipo_pedido

            FROM Atiende a

            INNER JOIN Pedido pe
                ON pe.ID_pedido = a.ID_pedido

            INNER JOIN Recibe_entrega re
                ON re.ID_pedido = pe.ID_pedido

            INNER JOIN Persona pc
                ON pc.CI = re.CI_cliente

            INNER JOIN Contiene c
                ON c.ID_pedido = pe.ID_pedido

            INNER JOIN Producto p
                ON p.ID_producto = c.ID_producto

            WHERE a.CI_operario = :operario

            AND UPPER(pe.Estado) = 'PREPARANDO'

            ORDER BY pe.Fecha_ingresado ASC
        ";

        $stmt =
            $this->conexion->prepare($sql);

        $stmt->execute([
            ':operario' => $ciOperario
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // =========================================
    // INICIALIZAR PICKING
    // =========================================

    public function inicializarPicking($idPedido)
    {
        $sql = "
            INSERT IGNORE INTO PickingDetalle
            (
                ID_pedido,
                ID_producto,
                Confirmado
            )

            SELECT
                c.ID_pedido,
                c.ID_producto,
                0

            FROM Contiene c

            WHERE c.ID_pedido = :pedido
        ";

        $stmt =
            $this->conexion->prepare($sql);

        $stmt->execute([
            ':pedido' => $idPedido
        ]);
    }


    // =========================================
    // HOJA DE PICKING
    // =========================================

    public function obtenerHojaPicking(
        $idPedido,
        $ciOperario
    )
    {
        $this->inicializarPicking($idPedido);


        $sql = "
            SELECT
                pe.ID_pedido,
                pe.Fecha_ingresado,
                pe.Estado,

                re.CI_cliente,

                cliente.Nombre_completo AS Cliente,

                c.ID_producto,
                c.Cantidad,

                p.Descripcion,
                p.Tipo,
                p.Categoria,
                p.Stock_actual,

                e.ID_zona,

                z.Nombre AS Zona,
                z.Nivel,
                z.Estante,

                pd.Confirmado,
                pd.Fecha_confirmacion

            FROM Pedido pe

            INNER JOIN Atiende a
                ON a.ID_pedido = pe.ID_pedido

            INNER JOIN Recibe_entrega re
                ON re.ID_pedido = pe.ID_pedido

            INNER JOIN Persona cliente
                ON cliente.CI = re.CI_cliente

            INNER JOIN Contiene c
                ON c.ID_pedido = pe.ID_pedido

            INNER JOIN Producto p
                ON p.ID_producto = c.ID_producto

            LEFT JOIN Esta e
                ON e.ID_producto = p.ID_producto

            LEFT JOIN Zona z
                ON z.ID_zona = e.ID_zona

            LEFT JOIN PickingDetalle pd
                ON pd.ID_pedido = pe.ID_pedido
                AND pd.ID_producto = p.ID_producto

            WHERE pe.ID_pedido = :pedido

            AND a.CI_operario = :operario

            AND UPPER(pe.Estado) = 'PREPARANDO'

            ORDER BY
                pd.Confirmado ASC,
                z.ID_zona ASC,
                z.Estante ASC,
                p.Descripcion ASC
        ";

        $stmt =
            $this->conexion->prepare($sql);

        $stmt->execute([
            ':pedido' => $idPedido,
            ':operario' => $ciOperario
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // =========================================
    // CONFIRMAR RETIRO EN PICKING
    // =========================================

    public function confirmarProducto(
        $idPedido,
        $idProducto,
        $ciOperario
    )
    {
        try {

            $this->conexion->beginTransaction();


            // =========================================
            // VALIDAR PEDIDO Y OPERARIO
            // =========================================

            $sqlPedido = "
                SELECT
                    pe.ID_pedido,
                    pe.Estado

                FROM Pedido pe

                INNER JOIN Atiende a
                    ON a.ID_pedido = pe.ID_pedido

                WHERE pe.ID_pedido = :pedido

                AND a.CI_operario = :operario

                AND UPPER(pe.Estado) = 'PREPARANDO'

                LIMIT 1

                FOR UPDATE
            ";

            $stmtPedido =
                $this->conexion->prepare($sqlPedido);

            $stmtPedido->execute([
                ':pedido' => $idPedido,
                ':operario' => $ciOperario
            ]);

            $pedido =
                $stmtPedido->fetch(PDO::FETCH_ASSOC);


            if (!$pedido) {

                throw new Exception(
                    "El pedido no está disponible para este operario."
                );
            }


            // =========================================
            // OBTENER ELEMENTO
            // =========================================

            $sqlProducto = "
                SELECT
                    c.Cantidad,
                    p.Stock_actual,
                    p.Descripcion,
                    p.Tipo

                FROM Contiene c

                INNER JOIN Producto p
                    ON p.ID_producto = c.ID_producto

                WHERE c.ID_pedido = :pedido

                AND c.ID_producto = :producto

                LIMIT 1

                FOR UPDATE
            ";

            $stmtProducto =
                $this->conexion->prepare($sqlProducto);

            $stmtProducto->execute([
                ':pedido' => $idPedido,
                ':producto' => $idProducto
            ]);

            $producto =
                $stmtProducto->fetch(PDO::FETCH_ASSOC);


            if (!$producto) {

                throw new Exception(
                    "El elemento no pertenece al pedido."
                );
            }


            // =========================================
            // COMPROBAR PICKING
            // =========================================

            $sqlPicking = "
                SELECT
                    Confirmado

                FROM PickingDetalle

                WHERE ID_pedido = :pedido

                AND ID_producto = :producto

                LIMIT 1

                FOR UPDATE
            ";

            $stmtPicking =
                $this->conexion->prepare($sqlPicking);

            $stmtPicking->execute([
                ':pedido' => $idPedido,
                ':producto' => $idProducto
            ]);

            $detalle =
                $stmtPicking->fetch(PDO::FETCH_ASSOC);


            if (!$detalle) {

                throw new Exception(
                    "No existe el detalle de picking."
                );
            }


            if ((int)$detalle['Confirmado'] === 1) {

                throw new Exception(
                    "Este elemento ya fue confirmado."
                );
            }


            // =========================================
            // SOLO ALMACEN DESCUENTA STOCK
            // =========================================

            if (
                strtoupper($producto['Tipo'])
                === 'ALMACEN'
            ) {

                $cantidad =
                    (int)$producto['Cantidad'];

                $stock =
                    (int)$producto['Stock_actual'];


                if ($stock < $cantidad) {

                    throw new Exception(
                        "No hay stock suficiente de " .
                        $producto['Descripcion']
                    );
                }


                $sqlStock = "
                    UPDATE Producto

                    SET Stock_actual =
                        Stock_actual - :cantidad

                    WHERE ID_producto = :producto
                ";

                $stmtStock =
                    $this->conexion->prepare($sqlStock);

                $stmtStock->execute([
                    ':cantidad' => $cantidad,
                    ':producto' => $idProducto
                ]);
            }


            // =========================================
            // CONFIRMAR RETIRO
            // =========================================

            $sqlConfirmar = "
                UPDATE PickingDetalle

                SET
                    Confirmado = 1,
                    Fecha_confirmacion = NOW(),
                    CI_operario = :operario

                WHERE ID_pedido = :pedido

                AND ID_producto = :producto
            ";

            $stmtConfirmar =
                $this->conexion->prepare($sqlConfirmar);

            $stmtConfirmar->execute([
                ':operario' => $ciOperario,
                ':pedido' => $idPedido,
                ':producto' => $idProducto
            ]);


            // =========================================
            // VER SI TERMINÓ
            // =========================================

            $sqlPendientes = "
                SELECT
                    COUNT(*) AS pendientes

                FROM PickingDetalle

                WHERE ID_pedido = :pedido

                AND Confirmado = 0
            ";

            $stmtPendientes =
                $this->conexion->prepare($sqlPendientes);

            $stmtPendientes->execute([
                ':pedido' => $idPedido
            ]);

            $resultado =
                $stmtPendientes->fetch(PDO::FETCH_ASSOC);


            $terminado =
                ((int)$resultado['pendientes'] === 0);


            // =========================================
            // LISTO PARA VALIDACIÓN
            // =========================================

            if ($terminado) {

                $sqlEstado = "
                    UPDATE Pedido

                    SET Estado = 'LISTO_PARA_VALIDAR'

                    WHERE ID_pedido = :pedido
                ";

                $stmtEstado =
                    $this->conexion->prepare($sqlEstado);

                $stmtEstado->execute([
                    ':pedido' => $idPedido
                ]);
            }


            $this->conexion->commit();


            return [
                'ok' => true,
                'terminado' => $terminado,
                'tipo' => strtoupper($producto['Tipo'])
            ];


        } catch (Exception $e) {

            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }
}