<?php
declare(strict_types=1);
use Nexus\Application\Recuperacion\RecuperacionService;
use Nexus\Domain\ValueObject\Cedula;
use Nexus\Infrastructure\Persistence\PdoRecuperacionRepository;
use Nexus\Infrastructure\Security\SegPasswordHasher;
require_once __DIR__.'/common.php';
exigirMetodo(['GET','POST','PUT','PATCH']);
$sesionActual=autorizarEndpoint('recuperacion');
$servicio=new RecuperacionService(new PdoRecuperacionRepository($pdo),new SegPasswordHasher());
ejecutar(function()use($servicio,$sesionActual):void{
    $metodo=$_SERVER['REQUEST_METHOD'];if($metodo==='GET')responder($servicio->listar());$d=cuerpoJson();
    if($metodo==='POST'){
        $cedula=new Cedula(texto($d['cedula']??'',15));
        if(texto($d['accion']??'',20)==='consultar')responder($servicio->consultar($cedula,texto($d['codigo']??'',12)));
        $resultado=$servicio->solicitar($cedula,texto($d['correo']??'',150));responder($resultado,!empty($resultado['existente'])?200:201);
    }
    $resultado=$servicio->gestionar(entero($d['id']??null,'id'),texto($d['accion']??'',20),new Cedula((string)$sesionActual['cedula']));responder($resultado);
});
