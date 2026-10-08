<?php

class ZonaRepositorio
{
    private $conexion;


    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }


    // ==================================================
    // LISTAR ZONAS
    // ==================================================

    public function obtenerTodas()
    {
        $sql = "
            SELECT
                z.ID_zona,
                z.Nombre,
                z.Nivel,
                z.Estante,
                z.Descripcion,
                z.ID_deposito,
                d.Nombre AS Deposito

            FROM Zona z

            INNER JOIN Deposito d
                ON d.ID_deposito = z.ID_deposito

            ORDER BY
                d.Nombre ASC,
                z.Nombre ASC,
                z.Nivel ASC,
                z.Estante ASC
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // ==================================================
    // BUSCAR ZONA POR ID
    // ==================================================

    public function buscarPorId($idZona)
    {
        $sql = "
            SELECT
                z.ID_zona,
                z.Nombre,
                z.Nivel,
                z.Estante,
                z.Descripcion,
                z.ID_deposito,
                d.Nombre AS Deposito

            FROM Zona z

            INNER JOIN Deposito d
                ON d.ID_deposito = z.ID_deposito

            WHERE z.ID_zona = :id

            LIMIT 1
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ':id',
            $idZona,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}