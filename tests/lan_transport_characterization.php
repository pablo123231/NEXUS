<?php
declare(strict_types=1);
require_once __DIR__.'/../proyecto 3ra entrega/seguridad/bootstrap.php';
function assertLan(bool $condicion,string $mensaje):void{if(!$condicion)throw new RuntimeException($mensaje);}
$_SERVER['REMOTE_ADDR']='192.168.10.25';$_SERVER['SERVER_ADDR']='192.168.10.5';assertLan(segEsLanPrivada(),'La red 192.168 no fue reconocida como LAN privada.');
$_SERVER['REMOTE_ADDR']='10.20.30.40';$_SERVER['SERVER_ADDR']='172.16.0.8';assertLan(segEsLanPrivada(),'Los rangos privados 10/8 y 172.16/12 no fueron reconocidos.');
$_SERVER['REMOTE_ADDR']='203.0.113.20';$_SERVER['SERVER_ADDR']='192.168.10.5';assertLan(!segEsLanPrivada(),'Un cliente externo fue aceptado como LAN.');
$_SERVER['REMOTE_ADDR']='192.168.10.25';$_SERVER['SERVER_ADDR']='8.8.8.8';assertLan(!segEsLanPrivada(),'Un servidor público fue aceptado como LAN.');
$_SERVER['REMOTE_ADDR']='valor-invalido';$_SERVER['SERVER_ADDR']='192.168.10.5';assertLan(!segEsLanPrivada(),'Una dirección inválida fue aceptada como LAN.');
echo "OK: 5 escenarios de transporte HTTP-LAN verificados.\n";
