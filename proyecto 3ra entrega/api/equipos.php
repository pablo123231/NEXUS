<?php
declare(strict_types=1);
use Nexus\Application\Inventario\InventarioService;use Nexus\Infrastructure\Persistence\PdoInventarioRepository;
require_once __DIR__.'/common.php';exigirMetodo(['GET','POST','PUT','PATCH','DELETE']);autorizarEndpoint('equipos');$servicio=new InventarioService(new PdoInventarioRepository($pdo));
ejecutar(function()use($servicio):void{$m=$_SERVER['REQUEST_METHOD'];if($m==='GET')responder($servicio->equipos());$d=cuerpoJson();
    if($m==='POST'){$cantidad=filter_var($d['cantidad']??1,FILTER_VALIDATE_INT);$id=$servicio->altaEquipo(['nombre'=>texto($d['nombre']??'',100),'tipo'=>texto($d['tipo']??'Otro',60),'observaciones'=>texto($d['observaciones']??'',65535),'marca'=>texto($d['marca']??'',60),'cantidad'=>$cantidad===false?0:(int)$cantidad,'seccionId'=>entero($d['seccionId']??null,'seccionId'),'estado'=>texto($d['estado']??'Activo',20)]);responder(['ok'=>true,'id'=>(string)$id],201);}
    $id=entero($d['id']??null,'id');if(in_array($m,['PUT','PATCH'],true)){if(texto($d['accion']??'',30)==='editar'){$servicio->editarEquipo($id,['nombre'=>texto($d['nombre']??'',100),'tipo'=>texto($d['tipo']??'',60),'marca'=>texto($d['marca']??'',60),'observaciones'=>texto($d['observaciones']??'',65535),'estado'=>texto($d['estado']??'',30),'seccionId'=>entero($d['seccionId']??null,'seccionId')]);responder(['ok'=>true]);}responder($servicio->trasladarEquipo($id,entero($d['seccionId']??null,'seccionId')));}responder($servicio->bajaEquipo($id));
});
