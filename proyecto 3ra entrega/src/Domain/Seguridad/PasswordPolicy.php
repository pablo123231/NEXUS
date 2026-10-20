<?php
declare(strict_types=1);
namespace Nexus\Domain\Seguridad;
interface PasswordPolicy{public function validar(string $password):void;}
