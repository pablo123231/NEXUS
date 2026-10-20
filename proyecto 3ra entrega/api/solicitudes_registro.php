<?php
declare(strict_types=1);
use Nexus\Application\SolicitudRegistro\SolicitudRegistroService;use Nexus\Domain\ValueObject\Cedula;use Nexus\Infrastructure\Persistence\PdoSolicitudRegistroRepository;use Nexus\Infrastructure\Security\SegPasswordHasher;
require_once __DIR__.'/common.php';exigirMetodo(['GET','POST','PUT','PATCH']);$sesionActual=autorizarEndpoint('solicitudes_registro');$servicio=new SolicitudRegistroService(new PdoSolicitudRegistroRepository($pdo),new SegPasswordHasher());
ejecutar(function()use($servicio,$sesionActual):void{
    $metodo=$_SERVER['REQUEST_METHOD'];if($metodo==='GET')responder($servicio->listar());$d=cuerpoJson();
    if($metodo==='POST'){[$nombre,$apellido]=dividirNombre(texto($d['nombreCompleto']??'',161));$r=$servicio->crear(['cedula'=>new Cedula(texto($d['cedula']??'',15)),'nombre'=>$nombre,'apellido'=>$apellido,'correo'=>texto($d['correo']??'',150),'contrasena'=>texto($d['contrasena']??'',255),'rol'=>(string)($d['rol']??'')]);responder($r,201);}
    $servicio->resolver(entero($d['id']??null,'id'),texto($d['estado']??'',12),new Cedula((string)$sesionActual['cedula']));responder(['ok'=>true]);
});
