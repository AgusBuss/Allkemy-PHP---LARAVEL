<?php 
class Categoria
{
public int $id;
public string $nombre;

public function __construct(int $id, string $nombre)
{
    $this->id = $id;
    $this->nombre = $nombre;
}
}