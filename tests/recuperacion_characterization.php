<?php
declare(strict_types=1);
use Nexus\Application\Recuperacion\RecuperacionService;use Nexus\Domain\Recuperacion\RecuperacionRepository;use Nexus\Domain\Seguridad\PasswordHasher;use Nexus\Domain\ValueObject\Cedula;
require_once __DIR__.'/../proyecto 3ra entrega/src/autoload.php';
final class HashPrueba implements PasswordHasher{public function hash(string $p):string{return password_hash($p,PASSWORD_BCRYPT);}public function verify(string $p,string $h):bool{return password_verify($p,$h);}}
final class RecuperacionesMemoria implements RecuperacionRepository
{
    public array $solicitudes=[];public ?string $hashUsuario=null;private int $seq=0;
    public function listar():array{return array_values($this->solicitudes);}
    public function buscarCuentaActiva(Cedula $c,string $e):?array{return $c->value()==='57324099'&&$e==='jean@example.test'?['nombre'=>'Jean','apellido'=>'Ortiz']:null;}
    public function codigoPendiente(Cedula $c):?string{foreach($this->solicitudes as $s)if($s['cedula']===$c->value()&&in_array($s['estado'],['Pendiente','En proceso'],true))return $s['codigo_seguimiento'];return null;}
    public function crearConTicket(Cedula $c,string $n,string $codigo):int{$id=++$this->seq;$this->solicitudes[$id]=['id_recuperacion'=>$id,'id_ticket'=>100+$id,'cedula'=>$c->value(),'codigo_seguimiento'=>$codigo,'estado'=>'Pendiente','contrasena_temporal'=>null,'credencial_vista'=>0,'fecha_creacion'=>'2026-10-19 10:00:00','fecha_resolucion'=>null];return 100+$id;}
    public function consultar(Cedula $c,string $codigo):?array{foreach($this->solicitudes as $s)if($s['cedula']===$c->value()&&$s['codigo_seguimiento']===$codigo)return $s;return null;}
    public function consumirCredencial(int $id):void{$this->solicitudes[$id]['credencial_vista']=1;$this->solicitudes[$id]['contrasena_temporal']=null;}
    public function buscarPendiente(int $id):?array{$s=$this->solicitudes[$id]??null;return $s&&in_array($s['estado'],['Pendiente','En proceso'],true)?$s:null;}
    public function tomar(int $id,int $ticket,Cedula $r):void{$this->solicitudes[$id]['estado']='En proceso';}
    public function resolver(int $id,array $s,Cedula $r,?string $temp,?string $hash):void{$this->solicitudes[$id]['estado']=$temp?'Resuelto':'Rechazado';$this->solicitudes[$id]['contrasena_temporal']=$temp;$this->solicitudes[$id]['fecha_resolucion']='2026-10-19 10:05:00';$this->hashUsuario=$hash;}
}
function assertR(bool $c,string $m):void{if(!$c)throw new RuntimeException($m);}
$repo=new RecuperacionesMemoria();$s=new RecuperacionService($repo,new HashPrueba());$jean=new Cedula('57324099');
$alta=$s->solicitar($jean,'jean@example.test');assertR(strlen($alta['codigo'])===12,'Código inválido.');
$repetida=$s->solicitar($jean,'jean@example.test');assertR($repetida['existente']===true,'No reutiliza solicitud pendiente.');
$s->gestionar(1,'tomar',$jean);assertR($repo->solicitudes[1]['estado']==='En proceso','No toma solicitud.');
$s->gestionar(1,'resolver',$jean);$consulta=$s->consultar($jean,$alta['codigo']);assertR(!empty($consulta['mostrarUnaVez']),'No entrega credencial una vez.');
assertR($repo->solicitudes[1]['credencial_vista']===1,'No consume credencial.');assertR(password_verify($consulta['contrasenaTemporal'],(string)$repo->hashUsuario),'La credencial entregada no coincide con el hash.');
$otra=$s->solicitar($jean,'jean@example.test');$s->gestionar(2,'rechazar',$jean);assertR($repo->solicitudes[2]['estado']==='Rechazado','No rechaza solicitud.');
echo "OK: 9 comportamientos de recuperación verificados.\n";
