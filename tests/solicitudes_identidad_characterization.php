<?php
declare(strict_types=1);
use Nexus\Application\SolicitudRegistro\SolicitudRegistroService;use Nexus\Application\SolicitudRol\SolicitudRolService;use Nexus\Domain\Seguridad\PasswordHasher;use Nexus\Domain\SolicitudRegistro\SolicitudRegistroRepository;use Nexus\Domain\SolicitudRol\SolicitudRolRepository;use Nexus\Domain\ValueObject\Cedula;
require_once __DIR__.'/../proyecto 3ra entrega/src/autoload.php';
final class HashIdentidad implements PasswordHasher{public function hash(string $p):string{return password_hash($p,PASSWORD_BCRYPT);}public function verify(string $p,string $h):bool{return password_verify($p,$h);}}
final class RegistroMemoria implements SolicitudRegistroRepository
{
    public array $usuarios=[];public array $solicitudes=[];public bool $habilitado=true;
    public function listar():array{return array_values($this->solicitudes);}
    public function registroHabilitado():bool{return $this->habilitado;}
    public function crearConUsuario(array $u):int{$id=count($this->solicitudes)+1;$c=$u['cedula']->value();$this->usuarios[$c]=['estado'=>'Inactivo','hash'=>$u['hash'],'rol'=>$u['rol']];$this->solicitudes[$id]=['id_solicitud_registro'=>$id,'estado_solicitud'=>'Pendiente','id_usuario_manda'=>$c,'id_usuario_responde'=>null,'nombre'=>$u['nombre'],'apellido'=>$u['apellido'],'correo_electronico'=>$u['correo'],'rol'=>$u['rol'],'responde_nombre'=>null,'responde_apellido'=>null];return $id;}
    public function resolver(int $id,string $estado,Cedula $a):bool{if(!isset($this->solicitudes[$id])||$this->solicitudes[$id]['estado_solicitud']!=='Pendiente')return false;$this->solicitudes[$id]['estado_solicitud']=$estado;$this->solicitudes[$id]['id_usuario_responde']=$a->value();if($estado==='Aprobado')$this->usuarios[$this->solicitudes[$id]['id_usuario_manda']]['estado']='Activo';return true;}
}
final class RolesMemoria implements SolicitudRolRepository
{
    public array $roles=['57324099'=>'Solicitante'];public array $solicitudes=[];
    public function listar():array{return array_values($this->solicitudes);}
    public function rolActivo(Cedula $c):?string{return $this->roles[$c->value()]??null;}
    public function tienePendiente(Cedula $c):bool{foreach($this->solicitudes as $s)if($s['cedula_solicitante']===$c->value()&&$s['estado']==='Pendiente')return true;return false;}
    public function crear(Cedula $c,string $a,string $s,string $m):int{$id=count($this->solicitudes)+1;$this->solicitudes[$id]=['id'=>$id,'cedula_solicitante'=>$c->value(),'rol_actual'=>$a,'rol_solicitado'=>$s,'motivo'=>$m,'estado'=>'Pendiente'];return $id;}
    public function resolver(int $id,string $e,Cedula $r):bool{if(!isset($this->solicitudes[$id])||$this->solicitudes[$id]['estado']!=='Pendiente')return false;$this->solicitudes[$id]['estado']=$e;if($e==='Aprobado')$this->roles[$this->solicitudes[$id]['cedula_solicitante']]=$this->solicitudes[$id]['rol_solicitado'];return true;}
}
function validarI(bool $c,string $m):void{if(!$c)throw new RuntimeException($m);}
$admin=new Cedula('11111111');$nuevo=new Cedula('22222222');$rr=new RegistroMemoria();$registro=new SolicitudRegistroService($rr,new HashIdentidad());
$alta=$registro->crear(['cedula'=>$nuevo,'nombre'=>'Josefina','apellido'=>'Prieto','correo'=>'josefina@example.test','contrasena'=>'Clave123!','rol'=>'Técnico/Asistente']);validarI($alta['solicitud']['id']==='1','Alta de registro falló.');validarI(password_verify('Clave123!',$rr->usuarios['22222222']['hash']),'Clave de registro sin hash válido.');
$registro->resolver(1,'Aprobado',$admin);validarI($rr->usuarios['22222222']['estado']==='Activo','Aprobación no activó cuenta.');
$rr2=new RegistroMemoria();$registro2=new SolicitudRegistroService($rr2,new HashIdentidad());$registro2->crear(['cedula'=>$nuevo,'nombre'=>'Josefina','apellido'=>'Prieto','correo'=>'josefina@example.test','contrasena'=>'Clave123!','rol'=>'Solicitante']);$registro2->resolver(1,'Rechazado',$admin);validarI($rr2->usuarios['22222222']['estado']==='Inactivo','Rechazo activó cuenta.');
$rolesRepo=new RolesMemoria();$roles=new SolicitudRolService($rolesRepo);$usuario=new Cedula('57324099');$id=$roles->crear($usuario,'Administrador','Necesito gestionar el sistema.');validarI($rolesRepo->solicitudes[$id]['rol_solicitado']==='Administrador','No registró el rol solicitado.');$roles->resolver($id,'Aprobado',$admin);validarI($rolesRepo->roles['57324099']==='Administrador','No aplicó rol aprobado.');
$id2=$roles->crear($usuario,'Solicitante','Volver al rol original.');$roles->resolver($id2,'Rechazado',$admin);validarI($rolesRepo->roles['57324099']==='Administrador','El rechazo modificó el rol.');
echo "OK: 10 comportamientos de solicitudes de identidad verificados.\n";
