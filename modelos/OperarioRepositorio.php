<?php

class OperarioRepositorio
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }


    public function obtenerOperarios()
    {
        $sql = "
            SELECT
                o.CI_operario,
                o.Rol,
                p.Nombre_completo,
                p.Mail,
                p.Telefono

            FROM Operario o

            INNER JOIN Persona p
                ON p.CI = o.CI_operario

            WHERE LOWER(o.Rol) IN ('operario', 'administrador')

            ORDER BY p.Nombre_completo ASC
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function buscarPorCI($ci)
{
    $sql = "
        SELECT
            o.CI_operario,
            o.Rol,
            p.Nombre_completo

        FROM Operario o

        INNER JOIN Persona p
            ON p.CI = o.CI_operario

        WHERE o.CI_operario = :ci

        AND LOWER(o.Rol) IN (
            'operario',
            'administrador'
        )

        LIMIT 1
    ";

    $stmt = $this->conexion->prepare($sql);

    $stmt->execute([
        ':ci' => $ci
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}