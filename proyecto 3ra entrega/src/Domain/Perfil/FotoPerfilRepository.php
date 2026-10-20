<?php
declare(strict_types=1);namespace Nexus\Domain\Perfil;use Nexus\Domain\ValueObject\Cedula;interface FotoPerfilRepository{public function fotoActual(Cedula $c):?string;public function guardar(Cedula $c,string $archivo):void;}
