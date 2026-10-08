<?php

class PaqueteRepositorio
{
    private $conexion;


    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }


    // ==================================================
    // REGISTRAR PAQUETE EN DEPÓSITO
    // ==================================================

    public function crear(
        $ciCliente,
        $descripcion,
        $categoria,
        $idZona
    ) {
        try {

            $this->conexion->beginTransaction();


            // ==========================================
            // 1. VALIDAR CLIENTE
            // ==========================================

            $sqlCliente = "
                SELECT CI_cliente
                FROM Cliente
                WHERE CI_cliente = :ci
                LIMIT 1
                FOR UPDATE
            ";

            $stmtCliente =
                $this->conexion->prepare($sqlCliente);

            $stmtCliente->execute([
                ':ci' => $ciCliente
            ]);

            if (!$stmtCliente->fetch()) {

                throw new Exception(
                    "El cliente seleccionado no existe."
                );
            }


            // ==========================================
            // 2. VALIDAR ZONA
            // ==========================================

            $sqlZona = "
                SELECT ID_zona
                FROM Zona
                WHERE ID_zona = :zona
                LIMIT 1
                FOR UPDATE
            ";

            $stmtZona =
                $this->conexion->prepare($sqlZona);

            $stmtZona->execute([
                ':zona' => $idZona
            ]);

            if (!$stmtZona->fetch()) {

                throw new Exception(
                    "La ubicación seleccionada no existe."
                );
            }


            // ==========================================
            // 3. CREAR PRODUCTO / PAQUETE
            // ==========================================

            $sqlProducto = "
                INSERT INTO Producto
                (
                    Tipo,
                    Descripcion,
                    Stock_actual,
                    Stock_minimo,
                    Categoria
                )
                VALUES
                (
                    'DEPOSITO',
                    :descripcion,
                    1,
                    0,
                    :categoria
                )
            ";

            $stmtProducto =
                $this->conexion->prepare($sqlProducto);

            $stmtProducto->execute([
                ':descripcion' => $descripcion,
                ':categoria' => $categoria
            ]);

            $idProducto =
                (int)$this->conexion->lastInsertId();


            // ==========================================
            // 4. CREAR PEDIDO DEL PAQUETE
            // ==========================================

            $sqlPedido = "
                INSERT INTO Pedido
                (
                    Fecha_entregado,
                    Fecha_ingresado,
                    Estado
                )
                VALUES
                (
                    NULL,
                    CURDATE(),
                    'ALMACENADO'
                )
            ";

            $stmtPedido =
                $this->conexion->prepare($sqlPedido);

            $stmtPedido->execute();

            $idPedido =
                (int)$this->conexion->lastInsertId();


            // ==========================================
            // 5. ASOCIAR CLIENTE
            // ==========================================

            $sqlRecibe = "
                INSERT INTO Recibe_entrega
                (
                    ID_pedido,
                    CI_cliente
                )
                VALUES
                (
                    :pedido,
                    :cliente
                )
            ";

            $stmtRecibe =
                $this->conexion->prepare($sqlRecibe);

            $stmtRecibe->execute([
                ':pedido' => $idPedido,
                ':cliente' => $ciCliente
            ]);


            // ==========================================
            // 6. ASOCIAR PAQUETE AL PEDIDO
            // ==========================================

            $sqlContiene = "
                INSERT INTO Contiene
                (
                    ID_pedido,
                    ID_producto,
                    Cantidad
                )
                VALUES
                (
                    :pedido,
                    :producto,
                    1
                )
            ";

            $stmtContiene =
                $this->conexion->prepare($sqlContiene);

            $stmtContiene->execute([
                ':pedido' => $idPedido,
                ':producto' => $idProducto
            ]);


            // ==========================================
            // 7. ASIGNAR UBICACIÓN
            // ==========================================

            $sqlUbicacion = "
                INSERT INTO Esta
                (
                    ID_producto,
                    ID_zona
                )
                VALUES
                (
                    :producto,
                    :zona
                )
            ";

            $stmtUbicacion =
                $this->conexion->prepare($sqlUbicacion);

            $stmtUbicacion->execute([
                ':producto' => $idProducto,
                ':zona' => $idZona
            ]);


            $this->conexion->commit();


            return [
                'id_pedido' => $idPedido,
                'id_producto' => $idProducto
            ];


        } catch (Exception $e) {

            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }
}