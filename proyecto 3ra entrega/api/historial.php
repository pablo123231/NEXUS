<?php
declare(strict_types=1);use Nexus\Application\Administracion\AdministracionService;use Nexus\Infrastructure\Persistence\PdoAdministracionRepository;
require_once __DIR__.'/common.php';exigirMetodo(['GET']);autorizarEndpoint('historial');$s=new AdministracionService(new PdoAdministracionRepository($pdo));ejecutar(function()use($s):void{responder($s->historial());});
