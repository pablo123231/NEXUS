<?php
declare(strict_types=1);
namespace Nexus\Domain\Inventario;
interface InventarioRepository
{
    public function listarEquipos():array;public function ubicacionExiste(int $id,bool $activa=false):bool;public function crearEquipo(array $d):int;public function editarEquipo(int $id,array $d):bool;public function trasladarEquipo(int $id,int $destino):?bool;public function bajaEquipo(int $id):?bool;
    public function esquemaUbicacionesActualizado():bool;public function listarUbicaciones():array;public function crearUbicacion(string $nombre):int;public function renombrarUbicacion(int $id,string $nombre):bool;public function bajaUbicacion(int $id,?int $destino):?array;
}
