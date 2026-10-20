<?php
declare(strict_types=1);

$raiz=dirname(__DIR__).'/proyecto 3ra entrega/';
$pruebas=0;
function verificarCorreccion(bool $condicion,string $mensaje):void{global $pruebas;$pruebas++;if(!$condicion)throw new RuntimeException($mensaje);}
function contenido(string $ruta):string{$valor=file_get_contents($ruta);if($valor===false)throw new RuntimeException('No se pudo leer '.$ruta);return $valor;}

$login=contenido($raiz.'login.html');
verificarCorreccion(!str_contains($login,'Estás en:'),'El login conserva el bloque de dominio solicitado para eliminar.');
verificarCorreccion(!str_contains($login,'Nunca te vamos a pedir la contraseña'),'El login conserva la advertencia solicitada para eliminar.');
$loginJs=contenido($raiz.'js/paginas/login.js');
verificarCorreccion(!str_contains($loginJs,"getElementById('dominio-actual')"),'El login intenta acceder al elemento de dominio que fue eliminado.');

$home=contenido($raiz.'home.html');
verificarCorreccion(str_contains($home,'if (puedeVerKanban)'),'El inicio no protege la consulta de responsables por rol.');
verificarCorreccion(str_contains($home,'obtenerResponsablesTareaRegistrados()'),'El Kanban no carga responsables desde el plantel.');
$portal=contenido($raiz.'js/core/portal.js');
verificarCorreccion(str_contains($portal,".normalize('NFD')"),'La clasificacion de roles no normaliza tildes.');
verificarCorreccion(str_contains($portal,'function escaparAtributo'),'Falta el escape de atributos usado por administracion.');

$trabajo=contenido($raiz.'src/Infrastructure/Persistence/PdoTrabajoRepository.php');
verificarCorreccion(str_contains($trabajo,"rol IN ('Tecnico_Asistente','Administrador')"),'El plantel no contempla tecnicos y administradores.');
verificarCorreccion(str_contains($trabajo,'LEFT JOIN PLANTILLA_TECNICO_ASISTENTE'),'La planilla sigue excluyendo personal sin una fila previa.');

$detalle=contenido($raiz.'equipo-detalle.html');
verificarCorreccion(str_contains($detalle,"tipo=categoria_tecnologica"),'La edicion de equipo no consume el catalogo tecnologico.');
verificarCorreccion(!str_contains($detalle,'editar-equipo-cantidad'),'La edicion de equipo todavia permite modificar cantidad.');

$prestamos=contenido($raiz.'prestamos.html');
verificarCorreccion(str_contains($prestamos,"tipo=categoria_tecnologica"),'Prestamos no usa el catalogo compartido.');
verificarCorreccion(str_contains($prestamos,'Marcar como devuelto'),'Prestamos no ofrece la devolucion de un prestamo aprobado.');
verificarCorreccion(!str_contains($prestamos,'Marcar pendiente'),'Prestamos conserva una transicion regresiva a pendiente.');
verificarCorreccion(str_contains($prestamos,'cantidadReservada')||str_contains($prestamos,'Reservados:'),'Prestamos no informa cantidades reservadas y disponibles.');
verificarCorreccion(str_contains($prestamos,'!prestamos[i].comentarioResolucion'),'El comentario de prestamo guardado sigue siendo editable.');
$prestamoRepo=contenido($raiz.'src/Infrastructure/Persistence/PdoPrestamoRepository.php');
verificarCorreccion(str_contains($prestamoRepo,'fecha_devolucion_real'),'La devolucion no registra su fecha real.');
verificarCorreccion(str_contains($prestamoRepo,"'Solicitado'=>['Activo','Cancelado']"),'El backend no define transiciones validas de prestamo.');

$administracion=contenido($raiz.'administracion.html');
verificarCorreccion(str_contains($administracion,'data-reactivar'),'Administracion no permite reactivar catalogos.');
verificarCorreccion(!str_contains($administracion,'data-nuevo="categoria_prestamo"'),'Administracion conserva un segundo catalogo de prestamos.');

$cuenta=contenido($raiz.'cuenta.html');
verificarCorreccion(!str_contains($cuenta,'Actividad reciente'),'Cuenta conserva la actividad reciente.');
verificarCorreccion(!str_contains($cuenta,'Verificacion en dos pasos'),'Cuenta conserva la activacion visible de dos pasos.');
verificarCorreccion(!str_contains($cuenta,'cuenta-fecha-registro'),'Cuenta conserva la fecha de registro.');

$soporte=contenido($raiz.'soporte.html');
verificarCorreccion(str_contains($soporte,'resolution-comment'),'Incidencias no usa el nuevo bloque de comentario.');
verificarCorreccion(str_contains($soporte,'!tickets[i].comentarioResolucion'),'El comentario guardado sigue siendo editable.');

$inventario=contenido($raiz.'js/modules/inventario.js');
verificarCorreccion(str_contains($inventario,'const tipoReal'),'Las categorias dinamicas se siguen reemplazando por una lista fija.');

$foto=contenido($raiz.'src/Infrastructure/Storage/GdAlmacenImagen.php');
verificarCorreccion(str_contains($foto,"if(!extension_loaded('gd'))"),'La foto no contempla instalaciones sin GD.');
verificarCorreccion(str_contains($foto,'move_uploaded_file'),'La ruta alternativa de foto no almacena el archivo recibido de forma segura.');

$ui=contenido($raiz.'js/core/ui.js');
verificarCorreccion(str_contains($ui,'mensajeVersion'),'Los mensajes no tienen temporizador contra borrados cruzados.');

echo 'OK: '.$pruebas." correcciones funcionales y de interfaz verificadas.\n";
