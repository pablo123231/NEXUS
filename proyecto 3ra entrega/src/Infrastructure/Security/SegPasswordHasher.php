<?php
declare(strict_types=1);
namespace Nexus\Infrastructure\Security;
use Nexus\Domain\Seguridad\PasswordHasher;
final class SegPasswordHasher implements PasswordHasher
{
    public function hash(string $plain):string{return segHashContrasena($plain);}
    public function verify(string $plain,string $hash):bool{return segEsHash($hash)&&password_verify($plain,$hash);}
}
