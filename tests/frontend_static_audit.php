<?php
declare(strict_types=1);

$directorio=__DIR__.'/../proyecto 3ra entrega';
$archivos=glob($directorio.'/*.html')?:[];
if(count($archivos)!==22)throw new RuntimeException('Se esperaban 22 pantallas HTML y se encontraron '.count($archivos).'.');

$errores=[];
foreach($archivos as $archivo){
    $html=(string)file_get_contents($archivo);$nombre=basename($archivo);
    if(!preg_match('/<meta[^>]+name=["\']viewport["\']/i',$html))$errores[]="$nombre no declara viewport";
    if(!preg_match('/<title>\s*[^<]+\s*<\/title>/i',$html))$errores[]="$nombre no tiene titulo";
    if($nombre!=='test-api.html'&&!preg_match('/<main\b/i',$html))$errores[]="$nombre no contiene main";
    preg_match_all('/<img\b[^>]*>/is',$html,$imagenes);
    foreach($imagenes[0] as $imagen){if(!preg_match('/\balt\s*=\s*["\'][^"\']*["\']/i',$imagen))$errores[]="$nombre contiene una imagen sin alt";}
}

if($errores)throw new RuntimeException(implode("\n",$errores));
echo "OK: 22 pantallas verificadas por viewport, titulo, region principal y texto alternativo.\n";
