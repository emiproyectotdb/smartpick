<?php

class ProductoRepositorio
{
    private $conexion;


    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }


    public function obtenerTodos()
    {
        $sql = "
            SELECT
                ID_producto,
                Tipo,
                Descripcion,
                Stock_actual,
                Stock_minimo,
                Categoria

            FROM Producto

            ORDER BY Descripcion ASC
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function buscarProductosAlmacen($texto)
    {
        $sql = "
            SELECT
                ID_producto,
                Tipo,
                Descripcion,
                Stock_actual,
                Stock_minimo,
                Categoria

            FROM Producto

            WHERE UPPER(Tipo) = 'ALMACEN'

            AND (
                Descripcion LIKE :busqueda
                OR Categoria LIKE :busqueda
                OR CAST(ID_producto AS CHAR) LIKE :busqueda
            )

            ORDER BY Descripcion ASC

            LIMIT 20
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            ':busqueda' => '%' . $texto . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function buscarPorId($id)
    {
        $sql = "
            SELECT
                ID_producto,
                Tipo,
                Descripcion,
                Stock_actual,
                Stock_minimo,
                Categoria

            FROM Producto

            WHERE ID_producto = :id

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
// OBTENER PRODUCTOS DE ALMACÉN
// ==================================================

public function obtenerProductosAlmacen()
{
    $sql = "
        SELECT
            p.ID_producto,
            p.Descripcion,
            p.Tipo,
            p.Categoria,
            p.Stock_actual,
            p.Stock_minimo,
            z.ID_zona,
            z.Nombre AS Zona,
            z.Nivel,
            z.Estante
        FROM Producto p

        LEFT JOIN Esta e
            ON e.ID_producto = p.ID_producto

        LEFT JOIN Zona z
            ON z.ID_zona = e.ID_zona

        WHERE UPPER(p.Tipo) = 'ALMACEN'

        ORDER BY p.Descripcion ASC
    ";

    $stmt = $this->conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// ==================================================
// CREAR PRODUCTO DE ALMACÉN
// ==================================================

public function crearProductoAlmacen(
    $descripcion,
    $categoria,
    $stockActual,
    $stockMinimo,
    $idZona
) {
    try {

        $this->conexion->beginTransaction();

        // ==================================================
// VERIFICAR QUE EL PRODUCTO NO EXISTA
// ==================================================

$descripcionNormalizada =
    preg_replace('/\s+/', ' ', trim($descripcion));


$sqlExiste = "
    SELECT
        ID_producto,
        Descripcion
    FROM Producto
    WHERE UPPER(Tipo) = 'ALMACEN'
      AND LOWER(TRIM(Descripcion)) =
          LOWER(TRIM(:descripcion))
    LIMIT 1
";

$stmtExiste =
    $this->conexion->prepare($sqlExiste);

$stmtExiste->execute([
    ':descripcion' => $descripcionNormalizada
]);

$productoExistente =
    $stmtExiste->fetch(PDO::FETCH_ASSOC);


if ($productoExistente) {

    throw new Exception(
        "El producto \""
        . $productoExistente['Descripcion']
        . "\" ya está registrado. "
        . "Si necesitás cambiar sus datos, utilizá Modificar producto."
    );
}

        // Verificar que exista la zona
        $sqlZona = "
            SELECT ID_zona
            FROM Zona
            WHERE ID_zona = :zona
            LIMIT 1
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


        // Crear producto
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
                'ALMACEN',
                :descripcion,
                :stock_actual,
                :stock_minimo,
                :categoria
            )
        ";

        $stmtProducto =
            $this->conexion->prepare($sqlProducto);

        $stmtProducto->execute([
            ':descripcion' => $descripcionNormalizada,
            ':stock_actual' => $stockActual,
            ':stock_minimo' => $stockMinimo,
            ':categoria' => $categoria
        ]);

        $idProducto =
            (int)$this->conexion->lastInsertId();


        // Asignar ubicación
        $sqlEsta = "
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

        $stmtEsta =
            $this->conexion->prepare($sqlEsta);

        $stmtEsta->execute([
            ':producto' => $idProducto,
            ':zona' => $idZona
        ]);


        $this->conexion->commit();

        return $idProducto;


    } catch (Exception $e) {

        if ($this->conexion->inTransaction()) {
            $this->conexion->rollBack();
        }

        throw $e;
    }
}

// ==================================================
// OBTENER PRODUCTO DE ALMACÉN POR ID
// ==================================================

public function obtenerProductoAlmacenPorId($idProducto)
{
    $sql = "
        SELECT
            p.ID_producto,
            p.Descripcion,
            p.Categoria,
            p.Stock_actual,
            p.Stock_minimo,
            e.ID_zona,
            z.Nombre AS Zona,
            z.Nivel,
            z.Estante

        FROM Producto p

        LEFT JOIN Esta e
            ON e.ID_producto = p.ID_producto

        LEFT JOIN Zona z
            ON z.ID_zona = e.ID_zona

        WHERE p.ID_producto = :id
          AND UPPER(p.Tipo) = 'ALMACEN'

        LIMIT 1
    ";

    $stmt =
        $this->conexion->prepare($sql);

    $stmt->execute([
        ':id' => $idProducto
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// ==================================================
// MODIFICAR PRODUCTO
// ==================================================

public function modificarProductoAlmacen(
    $idProducto,
    $descripcion,
    $categoria,
    $stockMinimo,
    $idZona
) {
    try {

        $this->conexion->beginTransaction();


        $descripcionNormalizada =
            preg_replace(
                '/\s+/',
                ' ',
                trim($descripcion)
            );


        // Verificar producto
        $sqlProducto = "
            SELECT ID_producto
            FROM Producto
            WHERE ID_producto = :id
              AND UPPER(Tipo) = 'ALMACEN'
            LIMIT 1
            FOR UPDATE
        ";

        $stmtProducto =
            $this->conexion->prepare($sqlProducto);

        $stmtProducto->execute([
            ':id' => $idProducto
        ]);

        if (!$stmtProducto->fetch()) {

            throw new Exception(
                "El producto no existe."
            );
        }


        // Verificar que otro producto no tenga
        // la misma descripción
        $sqlDuplicado = "
            SELECT
                ID_producto,
                Descripcion

            FROM Producto

            WHERE UPPER(Tipo) = 'ALMACEN'

              AND LOWER(TRIM(Descripcion)) =
                  LOWER(TRIM(:descripcion))

              AND ID_producto <> :id

            LIMIT 1
        ";

        $stmtDuplicado =
            $this->conexion->prepare(
                $sqlDuplicado
            );

        $stmtDuplicado->execute([
            ':descripcion' =>
                $descripcionNormalizada,

            ':id' =>
                $idProducto
        ]);


        if ($stmtDuplicado->fetch()) {

            throw new Exception(
                "Ya existe otro producto con ese nombre."
            );
        }


        // Verificar zona
        $sqlZona = "
            SELECT ID_zona
            FROM Zona
            WHERE ID_zona = :zona
            LIMIT 1
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


        // Actualizar datos
        $sqlActualizar = "
            UPDATE Producto

            SET
                Descripcion = :descripcion,
                Categoria = :categoria,
                Stock_minimo = :stock_minimo

            WHERE ID_producto = :id
              AND UPPER(Tipo) = 'ALMACEN'
        ";

        $stmtActualizar =
            $this->conexion->prepare(
                $sqlActualizar
            );

        $stmtActualizar->execute([
            ':descripcion' =>
                $descripcionNormalizada,

            ':categoria' =>
                $categoria,

            ':stock_minimo' =>
                $stockMinimo,

            ':id' =>
                $idProducto
        ]);


        // Actualizar ubicación
        $sqlEsta = "
            INSERT INTO Esta
                (ID_producto, ID_zona)

            VALUES
                (:producto, :zona)

            ON DUPLICATE KEY UPDATE
                ID_zona = VALUES(ID_zona)
        ";

        $stmtEsta =
            $this->conexion->prepare($sqlEsta);

        $stmtEsta->execute([
            ':producto' => $idProducto,
            ':zona' => $idZona
        ]);


        $this->conexion->commit();


    } catch (Exception $e) {

        if ($this->conexion->inTransaction()) {
            $this->conexion->rollBack();
        }

        throw $e;
    }
}

// ==================================================
// OBTENER INVENTARIO DE ALMACÉN
// ==================================================

public function obtenerInventarioAlmacen()
{
    $sql = "
        SELECT
            p.ID_producto,
            p.Descripcion,
            p.Categoria,
            p.Stock_actual,
            p.Stock_minimo,
            z.Nombre AS Zona,
            z.Nivel,
            z.Estante

        FROM Producto p

        LEFT JOIN Esta e
            ON e.ID_producto = p.ID_producto

        LEFT JOIN Zona z
            ON z.ID_zona = e.ID_zona

        WHERE UPPER(p.Tipo) = 'ALMACEN'

        ORDER BY p.Descripcion ASC
    ";

    $stmt = $this->conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// ==================================================
// REPONER STOCK
// ==================================================

public function reponerStock($idProducto, $cantidad)
{
    if ($cantidad <= 0) {
        throw new Exception(
            "La cantidad a reponer debe ser mayor a cero."
        );
    }

    try {

        $this->conexion->beginTransaction();


        // Bloqueamos el producto mientras se actualiza
        $sqlProducto = "
            SELECT
                ID_producto,
                Descripcion,
                Stock_actual
            FROM Producto
            WHERE ID_producto = :id
              AND UPPER(Tipo) = 'ALMACEN'
            LIMIT 1
            FOR UPDATE
        ";

        $stmtProducto =
            $this->conexion->prepare($sqlProducto);

        $stmtProducto->execute([
            ':id' => $idProducto
        ]);

        $producto =
            $stmtProducto->fetch(PDO::FETCH_ASSOC);


        if (!$producto) {
            throw new Exception(
                "El producto seleccionado no existe."
            );
        }


        // Sumamos la reposición al stock existente
        $sqlActualizar = "
            UPDATE Producto
            SET Stock_actual = Stock_actual + :cantidad
            WHERE ID_producto = :id
        ";

        $stmtActualizar =
            $this->conexion->prepare($sqlActualizar);

        $stmtActualizar->execute([
            ':cantidad' => $cantidad,
            ':id' => $idProducto
        ]);


        $nuevoStock =
            (int)$producto['Stock_actual']
            + $cantidad;


        $this->conexion->commit();


        return [
            'producto' => $producto['Descripcion'],
            'stock_anterior' => (int)$producto['Stock_actual'],
            'cantidad' => $cantidad,
            'stock_nuevo' => $nuevoStock
        ];


    } catch (Exception $e) {

        if ($this->conexion->inTransaction()) {
            $this->conexion->rollBack();
        }

        throw $e;
    }
}

// ==================================================
// OBTENER PAQUETES EN DEPÓSITO
// ==================================================

public function obtenerPaquetesDeposito()
{
    $sql = "
        SELECT
            p.ID_producto,
            p.Descripcion,
            p.Categoria,

            pe.ID_pedido,
            pe.Fecha_ingresado,
            pe.Estado,

            per.CI,
            per.Nombre_completo AS Cliente,

            z.Nombre AS Zona,
            z.Nivel,
            z.Estante

        FROM Producto p

        INNER JOIN Contiene c
            ON c.ID_producto = p.ID_producto

        INNER JOIN Pedido pe
            ON pe.ID_pedido = c.ID_pedido

        INNER JOIN Recibe_entrega re
            ON re.ID_pedido = pe.ID_pedido

        INNER JOIN Cliente cl
            ON cl.CI_cliente = re.CI_cliente

        INNER JOIN Persona per
            ON per.CI = cl.CI_cliente

        LEFT JOIN Esta e
            ON e.ID_producto = p.ID_producto

        LEFT JOIN Zona z
            ON z.ID_zona = e.ID_zona

        WHERE UPPER(p.Tipo) = 'DEPOSITO'

          AND UPPER(pe.Estado) NOT IN (
              'RETIRADO',
              'ENTREGADO'
          )

        ORDER BY
            pe.Fecha_ingresado DESC,
            pe.ID_pedido DESC
    ";

    $stmt =
        $this->conexion->prepare($sql);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}