<?php
declare(strict_types=1);namespace Nexus\Domain\Perfil;interface AlmacenImagen{public function almacenar(array $archivo):string;public function eliminarAnterior(?string $archivo):void;}
