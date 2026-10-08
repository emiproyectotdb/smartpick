<?php

require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/Usuario.php';

class UsuarioRepositorio
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function buscarPorCI($ci)
    {
        $sql = "
            SELECT
                p.CI,
                p.Nombre_completo,
                p.Mail,
                p.Contrasena,
                o.Rol
            FROM Persona p
            INNER JOIN Operario o
                ON p.CI = o.CI_operario
            WHERE p.CI = :ci
            LIMIT 1
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(':ci', $ci);

        $stmt->execute();

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            return null;
        }

        return new Usuario(
            $fila['CI'],
            $fila['Nombre_completo'],
            $fila['Mail'],
            $fila['Contrasena'],
            $fila['Rol']
        );
    }
}