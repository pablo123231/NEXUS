<?php
declare(strict_types=1);

use Nexus\Application\Incidencia\IncidenciaService;
use Nexus\Domain\Incidencia\EstadoIncidencia;
use Nexus\Domain\Incidencia\IncidenciaRepository;
use Nexus\Domain\ValueObject\Cedula;

require_once __DIR__ . '/../proyecto 3ra entrega/src/autoload.php';

final class RepositorioIncidenciasEnMemoria implements IncidenciaRepository
{
    public array $filas=[]; public array $usuarios=['57324099'=>true]; private int $secuencia=0;
    public function listar(Cedula $u,bool $g): array {return array_values(array_filter($this->filas,fn($f)=>$f['esta_activa']&&($g||$f['cedula_crea']===$u->value())));}
    public function crear(array $d,Cedula $c): int {$id=++$this->secuencia;$this->filas[$id]=array_merge(['id_ticket'=>$id,'fecha_de_solicitud'=>'2026-10-19','fecha_cierre'=>null,'estado'=>'Pendiente','diagnostico'=>null,'solucion'=>null,'comentario_resolucion'=>null,'cedula_crea'=>$c->value(),'cedula_responde'=>null,'esta_activa'=>1,'nombre_crea'=>'Jean','apellido_crea'=>'Ortiz','nombre_responde'=>null,'apellido_responde'=>null],['categoria'=>$d['categoria'],'descripcion'=>$d['descripcion'],'titulo'=>$d['titulo'],'lugar'=>$d['lugar'],'id_equipo'=>$d['idEquipo']]);return $id;}
    public function existe(int $id): bool {return isset($this->filas[$id]);}
    public function usuarioExiste(Cedula $c): bool {return isset($this->usuarios[$c->value()]);}
    public function equipoExiste(int $id): bool {return $id===1;}
    public function baja(int $id): void {$this->filas[$id]['esta_activa']=0;$this->filas[$id]['estado']='Cerrado';}
    public function comentar(int $id,string $c): bool {if(!in_array($this->filas[$id]['estado'],['Resuelto','Cerrado'],true))return false;$this->filas[$id]['comentario_resolucion']=$c;return true;}
    public function actualizarDiagnostico(int $id,?string $d,?string $s): void {$this->filas[$id]['diagnostico']=$d;$this->filas[$id]['solucion']=$s;}
    public function tomar(int $id,Cedula $t): void {$this->filas[$id]['cedula_responde']=$t->value();$this->filas[$id]['estado']='En proceso';}
    public function cambiarEstado(int $id,EstadoIncidencia $e,Cedula $t,string $d='',string $s=''): void {$this->filas[$id]['estado']=$e->value();$this->filas[$id]['cedula_responde']=$t->value();if($d!=='')$this->filas[$id]['diagnostico']=$d;if($s!=='')$this->filas[$id]['solucion']=$s;}
}

function comprobar(bool $condicion,string $mensaje): void {if(!$condicion)throw new RuntimeException($mensaje);}function rechazaIncidencia(callable $accion,string $mensaje):void{try{$accion();}catch(\Nexus\Shared\DomainException){return;}throw new RuntimeException($mensaje);}
$repo=new RepositorioIncidenciasEnMemoria();$servicio=new IncidenciaService($repo);$jean=new Cedula('5.732.409-9');
rechazaIncidencia(fn()=>$servicio->crear(['categoria'=>'','titulo'=>'Monitor sin imagen','lugar'=>'','descripcion'=>'El monitor no muestra señal.','idEquipo'=>null],$jean),'Aceptó una incidencia sin categoría ni ubicación.');
$id=$servicio->crear(['categoria'=>'Hardware','titulo'=>'Monitor sin imagen','lugar'=>'Laboratorio 1','descripcion'=>'El monitor no muestra señal.','idEquipo'=>1],$jean);
comprobar($id===1,'El alta no devolvió el identificador esperado.');
$servicio->tomar($id,$jean);comprobar($repo->filas[$id]['estado']==='En proceso','La toma no cambió el estado.');
$servicio->estado($id,EstadoIncidencia::desdeCliente('Resuelto'),$jean,'Cable desconectado','Se reconectó el cable.');
$servicio->comentar($id,'El equipo quedó operativo.');
$salida=$servicio->listar($jean,true);
comprobar($salida[0]['estado']==='Resuelto','La presentación cambió el estado esperado.');
comprobar($salida[0]['comentarioResolucion']==='El equipo quedó operativo.','No se conservó el comentario.');
$servicio->baja($id);comprobar($repo->filas[$id]['esta_activa']===0,'La baja lógica no desactivó la incidencia.');
echo "OK: 8 comportamientos de incidencias verificados.\n";
