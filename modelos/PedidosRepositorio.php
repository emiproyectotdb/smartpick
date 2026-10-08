<?php

class PedidoRepositorio
{
    private $conexion;


    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }


    // ==================================================
    // LISTAR PEDIDOS
    // ==================================================

    public function obtenerTodos()
    {
        $sql = "
            SELECT
                ID_pedido,
                Fecha_ingresado,
                Fecha_entregado,
                Estado

            FROM Pedido

            ORDER BY
                COALESCE(Fecha_entregado, Fecha_ingresado) DESC,
                ID_pedido DESC
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // ==================================================
    // BUSCAR PEDIDO
    // ==================================================

    public function buscarPorId($id)
    {
        $sql = "
            SELECT
                ID_pedido,
                Fecha_ingresado,
                Fecha_entregado,
                Estado

            FROM Pedido

            WHERE ID_pedido = :id

            LIMIT 1
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ':id',
            $id,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // ==================================================
    // PAQUETES DEL CLIENTE QUE SIGUEN EN EL DEPÓSITO
    // ==================================================

    public function obtenerPaquetesCliente($ciCliente)
    {
        $sql = "
            SELECT
                pe.ID_pedido,
                pe.Fecha_ingresado,
                pe.Estado,

                pr.ID_producto,
                pr.Descripcion,
                pr.Categoria,

                c.Cantidad

            FROM Pedido pe

            INNER JOIN Recibe_entrega re
                ON re.ID_pedido = pe.ID_pedido

            INNER JOIN Contiene c
                ON c.ID_pedido = pe.ID_pedido

            INNER JOIN Producto pr
                ON pr.ID_producto = c.ID_producto

            WHERE re.CI_cliente = :ci

            AND UPPER(pr.Tipo) = 'DEPOSITO'

            AND UPPER(pe.Estado) IN (
                'INGRESADO',
                'ALMACENADO'
            )

            ORDER BY pe.Fecha_ingresado ASC
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            ':ci' => $ciCliente
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // ==================================================
    // CREAR PEDIDO DE PRODUCTOS DE ALMACÉN
    // ==================================================

    public function crearPedidoAlmacen($ciCliente, $productos)
    {
        try {

            $this->conexion->beginTransaction();


            // ------------------------------------------
            // VALIDAR STOCK
            // ------------------------------------------

            foreach ($productos as $producto) {

                $idProducto =
                    (int)$producto['id_producto'];

                $cantidad =
                    (int)$producto['cantidad'];


                if ($cantidad <= 0) {

                    throw new Exception(
                        "La cantidad debe ser mayor a cero."
                    );
                }


                $sql = "
                    SELECT
                        Descripcion,
                        Tipo,
                        Stock_actual

                    FROM Producto

                    WHERE ID_producto = :id

                    FOR UPDATE
                ";

                $stmt =
                    $this->conexion->prepare($sql);


                $stmt->bindValue(
                    ':id',
                    $idProducto,
                    PDO::PARAM_INT
                );


                $stmt->execute();


                $productoBD =
                    $stmt->fetch(PDO::FETCH_ASSOC);


                if (!$productoBD) {

                    throw new Exception(
                        "Uno de los productos no existe."
                    );
                }


                if (
                    strtoupper($productoBD['Tipo'])
                    !== 'ALMACEN'
                ) {

                    throw new Exception(
                        "El producto seleccionado no pertenece al almacén."
                    );
                }


                if (
                    $cantidad >
                    (int)$productoBD['Stock_actual']
                ) {

                    throw new Exception(
                        "No hay stock suficiente de " .
                        $productoBD['Descripcion']
                    );
                }
            }


            // ------------------------------------------
            // CREAR PEDIDO
            // ------------------------------------------

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
                    'PENDIENTE'
                )
            ";


            $stmtPedido =
                $this->conexion->prepare($sqlPedido);


            $stmtPedido->execute();


            $idPedido =
                $this->conexion->lastInsertId();


            // ------------------------------------------
            // ASOCIAR CLIENTE
            // ------------------------------------------

            $sqlCliente = "
                INSERT INTO Recibe_entrega
                (
                    ID_pedido,
                    CI_cliente
                )
                VALUES
                (
                    :id_pedido,
                    :ci_cliente
                )
            ";


            $stmtCliente =
                $this->conexion->prepare($sqlCliente);


            $stmtCliente->execute([

                ':id_pedido' =>
                    $idPedido,

                ':ci_cliente' =>
                    $ciCliente

            ]);


            // ------------------------------------------
            // AGREGAR PRODUCTOS
            // ------------------------------------------

            $sqlProducto = "
                INSERT INTO Contiene
                (
                    ID_pedido,
                    ID_producto,
                    Cantidad
                )
                VALUES
                (
                    :id_pedido,
                    :id_producto,
                    :cantidad
                )
            ";


            $stmtProducto =
                $this->conexion->prepare(
                    $sqlProducto
                );


            foreach ($productos as $producto) {

                $stmtProducto->execute([

                    ':id_pedido' =>
                        $idPedido,

                    ':id_producto' =>
                        (int)$producto['id_producto'],

                    ':cantidad' =>
                        (int)$producto['cantidad']

                ]);
            }


            $this->conexion->commit();


            return $idPedido;


        } catch (Exception $e) {

            if ($this->conexion->inTransaction()) {

                $this->conexion->rollBack();
            }

            throw $e;
        }
    }

    public function obtenerDetalle($idPedido)
{
    $sql = "
        SELECT
            pe.ID_pedido,
            pe.Fecha_ingresado,
            pe.Fecha_entregado,
            pe.Estado,

            re.CI_cliente,

            cliente.Nombre_completo AS Cliente,
            cliente.Telefono AS Telefono_cliente,
            cliente.Mail AS Mail_cliente,

            a.CI_operario,

            operario.Nombre_completo AS Operario

        FROM Pedido pe

        INNER JOIN Recibe_entrega re
            ON re.ID_pedido = pe.ID_pedido

        INNER JOIN Persona cliente
            ON cliente.CI = re.CI_cliente

        LEFT JOIN Atiende a
            ON a.ID_pedido = pe.ID_pedido

        LEFT JOIN Persona operario
            ON operario.CI = a.CI_operario

        WHERE pe.ID_pedido = :id

        LIMIT 1
    ";

    $stmt = $this->conexion->prepare($sql);

    $stmt->bindValue(
        ':id',
        $idPedido,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function obtenerProductosPedido($idPedido)
{
    $sql = "
        SELECT
            c.ID_producto,
            c.Cantidad,

            p.Descripcion,
            p.Tipo,
            p.Categoria,
            p.Stock_actual

        FROM Contiene c

        INNER JOIN Producto p
            ON p.ID_producto = c.ID_producto

        WHERE c.ID_pedido = :id

        ORDER BY p.Descripcion ASC
    ";

    $stmt = $this->conexion->prepare($sql);

    $stmt->bindValue(
        ':id',
        $idPedido,
        PDO::PARAM_INT
    );

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function asignarOperario($idPedido, $ciOperario)
{
    try {

        $this->conexion->beginTransaction();


        // Comprobar que el pedido exista
        $sqlPedido = "
            SELECT Estado
            FROM Pedido
            WHERE ID_pedido = :id
            FOR UPDATE
        ";

        $stmtPedido =
            $this->conexion->prepare($sqlPedido);

        $stmtPedido->execute([
            ':id' => $idPedido
        ]);

        $pedido =
            $stmtPedido->fetch(PDO::FETCH_ASSOC);


        if (!$pedido) {

            throw new Exception(
                "El pedido no existe."
            );
        }


        // Ver si ya tiene un operario asignado
        $sqlExiste = "
            SELECT ID_pedido
            FROM Atiende
            WHERE ID_pedido = :id
        ";

        $stmtExiste =
            $this->conexion->prepare($sqlExiste);

        $stmtExiste->execute([
            ':id' => $idPedido
        ]);


        if ($stmtExiste->fetch()) {

            // Si ya existe, reasignamos

            $sqlAsignar = "
                UPDATE Atiende

                SET CI_operario = :operario

                WHERE ID_pedido = :pedido
            ";

        } else {

            // Primera asignación

            $sqlAsignar = "
                INSERT INTO Atiende
                (
                    ID_pedido,
                    CI_operario
                )

                VALUES
                (
                    :pedido,
                    :operario
                )
            ";
        }


        $stmtAsignar =
            $this->conexion->prepare($sqlAsignar);


        $stmtAsignar->execute([

            ':pedido' =>
                $idPedido,

            ':operario' =>
                $ciOperario

        ]);


        // El pedido pasa a preparación
        $sqlEstado = "
            UPDATE Pedido

            SET Estado = 'PREPARANDO'

            WHERE ID_pedido = :id
        ";


        $stmtEstado =
            $this->conexion->prepare($sqlEstado);


        $stmtEstado->execute([
            ':id' => $idPedido
        ]);


        $this->conexion->commit();


    } catch (Exception $e) {

        if ($this->conexion->inTransaction()) {

            $this->conexion->rollBack();
        }

        throw $e;
    }
}

public function retirarPaquetesCliente($ciCliente, $idsPedidos)
{
    if (empty($idsPedidos)) {
        return;
    }

    try {

        $this->conexion->beginTransaction();

        foreach ($idsPedidos as $idPedido) {

            $idPedido = (int)$idPedido;

            // ==========================================
            // COMPROBAR QUE EL PAQUETE PERTENECE
            // AL CLIENTE Y SIGUE ALMACENADO
            // ==========================================

            $sqlValidar = "
                SELECT
                    pe.ID_pedido,
                    pe.Estado

                FROM Pedido pe

                INNER JOIN Recibe_entrega re
                    ON re.ID_pedido = pe.ID_pedido

                INNER JOIN Contiene c
                    ON c.ID_pedido = pe.ID_pedido

                INNER JOIN Producto pr
                    ON pr.ID_producto = c.ID_producto

                WHERE pe.ID_pedido = :id_pedido

                AND re.CI_cliente = :ci_cliente

                AND UPPER(pr.Tipo) = 'DEPOSITO'

                AND UPPER(pe.Estado) IN (
                    'INGRESADO',
                    'ALMACENADO'
                )

                LIMIT 1

                FOR UPDATE
            ";

            $stmtValidar =
                $this->conexion->prepare($sqlValidar);

            $stmtValidar->execute([
                ':id_pedido' => $idPedido,
                ':ci_cliente' => $ciCliente
            ]);

            $paquete =
                $stmtValidar->fetch(PDO::FETCH_ASSOC);

            if (!$paquete) {

                throw new Exception(
                    "El paquete #" .
                    $idPedido .
                    " no está disponible para solicitar su retiro."
                );
            }


            // ==========================================
            // SOLICITAR RETIRO
            // TODAVÍA NO ESTÁ ENTREGADO
            // ==========================================

            $sqlActualizar = "
                UPDATE Pedido

                SET
                    Estado = 'PENDIENTE_RETIRO',
                    Fecha_entregado = NULL

                WHERE ID_pedido = :id
            ";

            $stmtActualizar =
                $this->conexion->prepare($sqlActualizar);

            $stmtActualizar->execute([
                ':id' => $idPedido
            ]);
        }

        $this->conexion->commit();

    } catch (Exception $e) {

        if ($this->conexion->inTransaction()) {
            $this->conexion->rollBack();
        }

        throw $e;
    }
}

public function validarPedido($idPedido)
{
    try {

        $this->conexion->beginTransaction();


        // ==========================================
        // COMPROBAR PEDIDO
        // ==========================================

        $sqlPedido = "
            SELECT
                pe.ID_pedido,
                pe.Estado,

                MAX(
                    CASE
                        WHEN UPPER(pr.Tipo) = 'DEPOSITO'
                        THEN 1
                        ELSE 0
                    END
                ) AS Es_deposito,

                MAX(
                    CASE
                        WHEN UPPER(pr.Tipo) = 'ALMACEN'
                        THEN 1
                        ELSE 0
                    END
                ) AS Es_almacen

            FROM Pedido pe

            INNER JOIN Contiene c
                ON c.ID_pedido = pe.ID_pedido

            INNER JOIN Producto pr
                ON pr.ID_producto = c.ID_producto

            WHERE pe.ID_pedido = :pedido

            GROUP BY
                pe.ID_pedido,
                pe.Estado

            LIMIT 1

            FOR UPDATE
        ";

        $stmtPedido =
            $this->conexion->prepare($sqlPedido);

        $stmtPedido->execute([
            ':pedido' => $idPedido
        ]);

        $pedido =
            $stmtPedido->fetch(PDO::FETCH_ASSOC);


        if (!$pedido) {

            throw new Exception(
                "El pedido no existe."
            );
        }


        if (
            strtoupper($pedido['Estado'])
            !== 'LISTO_PARA_VALIDAR'
        ) {

            throw new Exception(
                "El pedido todavía no está listo para validar."
            );
        }


        // ==========================================
        // COMPROBAR PICKING
        // ==========================================

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


        if ((int)$resultado['pendientes'] > 0) {

            throw new Exception(
                "Todavía existen elementos sin confirmar en el picking."
            );
        }


        // ==========================================
        // DETERMINAR ESTADO FINAL
        // ==========================================

        if ((int)$pedido['Es_deposito'] === 1) {

            $estadoFinal = 'RETIRADO';

        } else {

            $estadoFinal = 'ENTREGADO';
        }


        // ==========================================
        // FINALIZAR
        // ==========================================

        $sqlActualizar = "
            UPDATE Pedido

            SET
                Estado = :estado,
                Fecha_entregado = CURDATE()

            WHERE ID_pedido = :pedido
        ";

        $stmtActualizar =
            $this->conexion->prepare($sqlActualizar);

        $stmtActualizar->execute([
            ':estado' => $estadoFinal,
            ':pedido' => $idPedido
        ]);


        $this->conexion->commit();

        return $estadoFinal;


    } catch (Exception $e) {

        if ($this->conexion->inTransaction()) {
            $this->conexion->rollBack();
        }

        throw $e;
    }
}
}
