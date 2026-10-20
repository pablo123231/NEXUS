<?php
declare(strict_types=1);
use Nexus\Application\Inventario\InventarioService;use Nexus\Infrastructure\Persistence\PdoInventarioRepository;
require_once __DIR__.'/common.php';exigirMetodo(['GET','POST','PUT','PATCH','DELETE']);autorizarEndpoint('ubicaciones');$servicio=new InventarioService(new PdoInventarioRepository($pdo));
ejecutar(function()use($servicio):void{$m=$_SERVER['REQUEST_METHOD'];if($m==='GET')responder($servicio->ubicaciones());$d=cuerpoJson();if($m==='POST'){$id=$servicio->altaUbicacion(texto($d['nombre']??'',100));responder(['ok'=>true,'id'=>(string)$id],201);}$id=entero($d['id']??null,'id');if(in_array($m,['PUT','PATCH'],true)){$servicio->renombrarUbicacion($id,texto($d['nombre']??'',100));responder(['ok'=>true]);}$destino=texto($d['seccionDestinoId']??'',15);responder($servicio->bajaUbicacion($id,$destino===''?null:entero($destino,'seccionDestinoId')));});
