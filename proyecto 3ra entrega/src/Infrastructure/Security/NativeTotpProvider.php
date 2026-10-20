<?php
declare(strict_types=1);
namespace Nexus\Infrastructure\Security;
use Nexus\Domain\Seguridad\TotpProvider;
final class NativeTotpProvider implements TotpProvider
{
    public function generar():string{return totpGenerarSecreto();}
    public function uri(string $s,string $c):string{return totpUri($s,$c);}
    public function verificar(string $s,string $c,?int $u):?int{return totpVerificar($s,$c,$u);}
}
