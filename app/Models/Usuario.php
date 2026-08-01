<?php
class Usuario{
public int $id;
public string $nombre;
public string $email;

public function __construct(int $id, string $nombre, string $email)
{
   $this->id = $id;
   $this->nombre = $nombre;
   $this->email = $email;
}
}