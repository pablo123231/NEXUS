<?php
declare(strict_types=1);
namespace Nexus\Infrastructure\Security;
use Nexus\Domain\Seguridad\PasswordPolicy;
final class SegPasswordPolicy implements PasswordPolicy{public function validar(string $p):void{segValidarContrasenaNueva($p);}}
