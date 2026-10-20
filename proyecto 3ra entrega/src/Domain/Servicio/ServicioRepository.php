<?php
declare(strict_types=1);
namespace Nexus\Domain\Servicio;
use Nexus\Domain\ValueObject\Cedula;
interface ServicioRepository{public function listar(Cedula $u,bool $g):array;public function usuarioExiste(Cedula $u):bool;public function crear(array $d,Cedula $u):int;public function baja(int $id):bool;public function cambiarEstado(int $id,string $e):bool;}
