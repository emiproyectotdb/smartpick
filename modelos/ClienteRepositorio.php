<?php

class ClienteRepositorio
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
                c.CI_cliente,
                p.Nombre_completo,
                p.Telefono,
                p.Mail,
                p.Direccion
            FROM Cliente c

            INNER JOIN Persona p
                ON p.CI = c.CI_cliente

            ORDER BY p.Nombre_completo ASC
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function buscar($texto)
    {
        $sql = "
            SELECT
                c.CI_cliente,
                p.Nombre_completo,
                p.Telefono,
                p.Mail,
                p.Direccion
            FROM Cliente c

            INNER JOIN Persona p
                ON p.CI = c.CI_cliente

            WHERE
                c.CI_cliente LIKE :busqueda
                OR p.Nombre_completo LIKE :busqueda

            ORDER BY p.Nombre_completo ASC

            LIMIT 10
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            ':busqueda' => '%' . $texto . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function buscarPorCI($ci)
    {
        $sql = "
            SELECT
                c.CI_cliente,
                p.Nombre_completo,
                p.Telefono,
                p.Mail,
                p.Direccion
            FROM Cliente c

            INNER JOIN Persona p
                ON p.CI = c.CI_cliente

            WHERE c.CI_cliente = :ci

            LIMIT 1
        ";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            ':ci' => $ci
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==================================================
// CREAR CLIENTE
// ==================================================

public function crear(
    $ci,
    $nombre,
    $telefono,
    $mail,
    $direccion
) {
    try {

        $this->conexion->beginTransaction();

        // Verificar que la CI no exista
        $sqlExiste = "
            SELECT CI
            FROM Persona
            WHERE CI = :ci
            LIMIT 1
        ";

        $stmtExiste =
            $this->conexion->prepare($sqlExiste);

        $stmtExiste->execute([
            ':ci' => $ci
        ]);

        if ($stmtExiste->fetch()) {

            throw new Exception(
                "Ya existe una persona registrada con esa CI."
            );
        }


        // Verificar mail si fue ingresado
        if ($mail !== '') {

            $sqlMail = "
                SELECT CI
                FROM Persona
                WHERE Mail = :mail
                LIMIT 1
            ";

            $stmtMail =
                $this->conexion->prepare($sqlMail);

            $stmtMail->execute([
                ':mail' => $mail
            ]);

            if ($stmtMail->fetch()) {

                throw new Exception(
                    "Ya existe una persona registrada con ese correo."
                );
            }
        }


        // Crear Persona
        $sqlPersona = "
            INSERT INTO Persona
            (
                CI,
                Nombre_completo,
                Telefono,
                Mail,
                Direccion,
                Contrasena,
                Ultimo_acceso
            )
            VALUES
            (
                :ci,
                :nombre,
                :telefono,
                :mail,
                :direccion,
                '',
                NULL
            )
        ";

        $stmtPersona =
            $this->conexion->prepare($sqlPersona);

        $stmtPersona->execute([
            ':ci' => $ci,
            ':nombre' => $nombre,
            ':telefono' => $telefono !== '' ? $telefono : null,
            ':mail' => $mail !== '' ? $mail : null,
            ':direccion' => $direccion !== '' ? $direccion : null
        ]);


        // Crear Cliente
        $sqlCliente = "
            INSERT INTO Cliente
            (
                CI_cliente
            )
            VALUES
            (
                :ci
            )
        ";

        $stmtCliente =
            $this->conexion->prepare($sqlCliente);

        $stmtCliente->execute([
            ':ci' => $ci
        ]);


        $this->conexion->commit();


    } catch (Exception $e) {

        if ($this->conexion->inTransaction()) {
            $this->conexion->rollBack();
        }

        throw $e;
    }
}
}