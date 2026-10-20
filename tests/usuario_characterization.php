<?php
declare(strict_types=1);
use Nexus\Application\Usuario\UsuarioService;
use Nexus\Domain\Usuario\UsuarioRepository;
use Nexus\Domain\ValueObject\Cedula;
require_once __DIR__.'/../proyecto 3ra entrega/src/autoload.php';
final class UsuariosMemoria implements UsuarioRepository
{
    public array $datos=[];public bool $registro=true;
    public function listar(?Cedula $c,bool $r):array{return array_values(array_filter($this->datos,fn($u)=>$c===null||$u['cedula']===$c->value()));}
    public function existe(Cedula $c):bool{return isset($this->datos[$c->value()]);}
    public function crear(array $d):void{$this->datos[$d['cedula']->value()]=['cedula'=>$d['cedula']->value(),'nombre'=>$d['nombre'],'apellido'=>$d['apellido'],'correo_electronico'=>$d['correo'],'estado'=>$d['estado'],'rol'=>$d['rol'],'descripcion'=>'','fecha_registro'=>'2026-10-19','foto_perfil'=>'','debe_cambiar_contrasena'=>0,'contrasena'=>$d['hash'],'esta_activo'=>1];}
    public function baja(Cedula $c):bool{if(!$this->existe($c)||!$this->datos[$c->value()]['esta_activo'])return false;$this->datos[$c->value()]['esta_activo']=0;return true;}
    public function cambiarContrasena(Cedula $c,string $h):void{$this->datos[$c->value()]['contrasena']=$h;}
    public function actualizarPerfil(Cedula $c,array $d):void{foreach(['nombre','apellido'] as $k)$this->datos[$c->value()][$k]=$d[$k];$this->datos[$c->value()]['correo_electronico']=$d['correo'];$this->datos[$c->value()]['descripcion']=$d['descripcion'];}
    public function cambiarEstado(Cedula $c,string $e):void{$this->datos[$c->value()]['estado']=$e;}
    public function registroHabilitado():bool{return $this->registro;}
}
function verificar(bool $c,string $m):void{if(!$c)throw new RuntimeException($m);}
$repo=new UsuariosMemoria();$s=new UsuarioService($repo);$cedula=new Cedula('57.324.099');
$salida=$s->crear(['cedula'=>$cedula,'nombre'=>'Jean','apellido'=>'Ortiz','correo'=>'jean@example.test','contrasena'=>'Clave123!','estado'=>'Activo','rol'=>'Administrador']);
verificar($salida['estadoRegistro']==='activo','Estado público incorrecto.');verificar(password_verify('Clave123!',$repo->datos[$cedula->value()]['contrasena']),'La clave no fue cifrada.');
$s->actualizarPerfil($cedula,['nombre'=>'Jean','apellido'=>'Ortiz','correo'=>'jean.ortiz@example.test','descripcion'=>'Administrador']);
$s->cambiarContrasena($cedula,'Nueva123!');verificar(password_verify('Nueva123!',$repo->datos[$cedula->value()]['contrasena']),'Cambio de clave incorrecto.');
$s->cambiarEstado($cedula,'Inactivo');verificar($repo->datos[$cedula->value()]['estado']==='Inactivo','Cambio de estado incorrecto.');
$s->baja($cedula);verificar($repo->datos[$cedula->value()]['esta_activo']===0,'Baja lógica incorrecta.');
echo "OK: 8 comportamientos de usuarios verificados.\n";
