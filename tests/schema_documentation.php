<?php
declare(strict_types=1);

$sql=(string)file_get_contents(__DIR__.'/../proyecto 3ra entrega/database.sql');
$modelo=(string)file_get_contents(__DIR__.'/../docs/modelo_datos/MODELO_RELACIONAL.md');
$mer=(string)file_get_contents(__DIR__.'/../docs/modelo_datos/MER_actual.mmd');

preg_match_all('/CREATE TABLE `?([a-z0-9_]+)`?/i',$sql,$coincidencias);
$tablas=array_values(array_unique(array_map('strtolower',$coincidencias[1])));
sort($tablas);

if(count($tablas)!==27)throw new RuntimeException('database.sql debe definir exactamente 27 tablas; se encontraron '.count($tablas).'.');

foreach($tablas as $tabla){
    if(stripos($modelo,'`'.$tabla.'`')===false)throw new RuntimeException('Falta '.$tabla.' en el modelo relacional.');
    if(stripos($mer,strtoupper($tabla))===false)throw new RuntimeException('Falta '.$tabla.' en la fuente MER.');
}

preg_match_all('/FOREIGN KEY\s*\([^)]*\)\s*REFERENCES/i',$sql,$foraneas);
if(count($foraneas[0])!==31)throw new RuntimeException('Se esperaban 31 claves foraneas documentadas.');

echo "OK: 27 tablas y 31 claves foraneas contrastadas con la documentacion.\n";
