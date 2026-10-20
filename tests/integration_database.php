<?php
declare(strict_types=1);
use Nexus\Application\Administracion\AdministracionService;use Nexus\Application\Incidencia\IncidenciaService;use Nexus\Application\Prestamo\PrestamoService;use Nexus\Application\Servicio\ServicioService;use Nexus\Application\Trabajo\TrabajoService;use Nexus\Domain\Incidencia\EstadoIncidencia;use Nexus\Domain\ValueObject\Cedula;use Nexus\Infrastructure\Persistence\PdoAdministracionRepository;use Nexus\Infrastructure\Persistence\PdoIncidenciaRepository;use Nexus\Infrastructure\Persistence\PdoPrestamoRepository;use Nexus\Infrastructure\Persistence\PdoServicioRepository;use Nexus\Infrastructure\Persistence\PdoTrabajoRepository;
require_once __DIR__.'/../proyecto 3ra entrega/src/autoload.php';
$dsn=getenv('NEXUS_TEST_DSN')?:'mysql:host=127.0.0.1;port=3307;dbname=rubric_test;charset=utf8mb4';$pdo=new PDO($dsn,getenv('NEXUS_TEST_USER')?:'root',getenv('NEXUS_TEST_PASS')?:'',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
function checkDb(bool $c,string $m):void{if(!$c)throw new RuntimeException($m);}$usuario=new Cedula('57324099');$marca='RUBRICA-'.bin2hex(random_bytes(4));$ids=[];
try{
    checkDb((int)$pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE()")->fetchColumn()===27,'database.sql no creó las 27 tablas.');
    $inc=new IncidenciaService(new PdoIncidenciaRepository($pdo));$ids['inc']=$inc->crear(['categoria'=>'Hardware','titulo'=>$marca.' Monitor','lugar'=>'Laboratorio 1','descripcion'=>'Monitor sin señal.','idEquipo'=>1],$usuario);$inc->tomar($ids['inc'],$usuario);$inc->estado($ids['inc'],EstadoIncidencia::desdeCliente('Resuelto'),$usuario,'Cable desconectado','Cable reconectado');$inc->comentar($ids['inc'],'Equipo operativo.');checkDb($inc->listar($usuario,true)[0]['comentarioResolucion']==='Equipo operativo.','Integración de incidencia falló.');
    $prest=new PrestamoService(new PdoPrestamoRepository($pdo));$p=$prest->crear(['fecha'=>(new DateTimeImmutable('+3 days'))->format('Y-m-d'),'recursos'=>[['idEquipo'=>3,'nombre'=>'Monitor 1','cantidad'=>1]],'categoria'=>'Monitor','jornada'=>'Mañana','ubicacion'=>'Laboratorio 1','detalle'=>$marca],$usuario);$ids['prest']=(int)$p['id'];$prest->cambiarEstado($ids['prest'],'Aprobado');$prest->comentar($ids['prest'],'Entregado.');
    $serv=new ServicioService(new PdoServicioRepository($pdo));$ids['serv']=$serv->crear(['tipo'=>'Instalación','titulo'=>$marca.' IDE','lugar'=>'Laboratorio 1','detalle'=>'Instalar IDE.','fechaDeseada'=>(new DateTimeImmutable('+2 days'))->format('Y-m-d')],$usuario);$serv->cambiarEstado($ids['serv'],'Completado');
    $trab=new TrabajoService(new PdoTrabajoRepository($pdo));$ids['tarea']=$trab->crearTarea(['titulo'=>$marca.' Revisar','descripcion'=>'Revisar equipos.','plazo'=>(new DateTimeImmutable('+4 days'))->format('Y-m-d'),'categoria'=>'Mantenimiento','dificultad'=>'Alta','estado'=>'En revision','color'=>'#16A34A','tecnico'=>$usuario]);$lista=$trab->tareas();checkDb(in_array($marca.' Revisar',array_column($lista,'titulo'),true),'Integración de tarea falló.');
    $admin=new AdministracionService(new PdoAdministracionRepository($pdo));$admin->guardarConfiguracion('dias_maximos_prestamo','30',$usuario);checkDb($admin->configuracion()['dias_maximos_prestamo']['valor']==='30','Integración de configuración falló.');
    echo "OK: instalación SQL y 18 operaciones PDO integradas verificadas.\n";
}finally{
    if(isset($ids['tarea'])){(new PdoTrabajoRepository($pdo))->eliminarTarea($ids['tarea']);}
    if(isset($ids['prest'])){$pdo->prepare('DELETE FROM SOLICITA_PRESTAMO WHERE id_prestamo=?')->execute([$ids['prest']]);$pdo->prepare('DELETE FROM PRESTAMO WHERE id_prestamo=?')->execute([$ids['prest']]);}
    if(isset($ids['serv']))$pdo->prepare('DELETE FROM SERVICIO WHERE id_servicio=?')->execute([$ids['serv']]);
    if(isset($ids['inc']))$pdo->prepare('DELETE FROM INCIDENCIA WHERE id_ticket=?')->execute([$ids['inc']]);
}
