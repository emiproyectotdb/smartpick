<?php

class Usuario
{
    public $ci;
    public $nombre;
    public $mail;
    public $contrasena;
    public $rol;

    public function __construct(
        $ci,
        $nombre,
        $mail,
        $contrasena,
        $rol
    ) {
        $this->ci = $ci;
        $this->nombre = $nombre;
        $this->mail = $mail;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
    }
}