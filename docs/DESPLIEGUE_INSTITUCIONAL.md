# Preparación del despliegue institucional

La ejecución en el servidor institucional queda pendiente del acceso por LAN, según la decisión del equipo. Este documento deja preparado el procedimiento verificable.

## Requisitos

- Apache 2.4 con `mod_rewrite` y cabeceras habilitadas.
- PHP 8.0 o superior con PDO MySQL, GD, mbstring y fileinfo.
- MariaDB 10.4 o MySQL compatible.
- HTTPS en el entorno institucional.
- Directorio `uploads/perfiles` escribible por el proceso web y no ejecutable.

## Instalación

1. Publicar el contenido de `proyecto 3ra entrega` fuera de cualquier versión anterior.
2. Crear una base vacía con codificación `utf8mb4`.
3. Importar únicamente `database.sql`; los archivos de `database old` son históricos y no deben ejecutarse.
4. Copiar `config.example.php` como `config.php` y completar conexión local del servidor.
5. Comprobar que `config.php` no sea servido ni versionado.
6. Verificar permisos del directorio de fotografías.
7. Acceder por HTTPS y comprobar las cabeceras CSP, anti-clickjacking y no-sniff.

## Acceso desde la LAN y requisito HTTPS

NEXUS permite HTTP únicamente cuando el host solicitado es `localhost`, `127.0.0.1` o un dominio `.localhost`. Para cualquier IP o nombre de la LAN, `seguridad/bootstrap.php` rechaza HTTP con estado 403 y `.htaccess` intenta redirigir la solicitud a la misma dirección mediante HTTPS. Esta protección evita enviar contraseñas, cookies de sesión y códigos de segundo factor sin cifrar.

Por lo tanto, copiar los archivos y la base de datos no completa el despliegue. El servidor o un proxy institucional debe publicar el sitio por el puerto 443 con un certificado confiable para el nombre utilizado. Las alternativas, en orden recomendado, son:

1. Solicitar al administrador institucional una URL HTTPS interna y que configure el certificado y el sitio virtual.
2. Publicar NEXUS detrás del proxy HTTPS que ya utilice la institución, conservando el host original y la información de protocolo.
3. Para una prueba temporal y aislada, usar un certificado emitido por la autoridad interna de la institución e instalar esa autoridad en los equipos de prueba.

No se recomienda desactivar la exigencia HTTPS en producción. Si la infraestructura utiliza un proxy, debe acordarse explícitamente qué cabecera informa el protocolo original y qué direcciones de proxy son confiables; NEXUS no debe confiar indiscriminadamente en cabeceras enviadas por cualquier cliente.

Antes de la próxima prueba, confirmar con infraestructura: nombre DNS, puerto, certificado, raíz pública del proyecto, versión de PHP, extensiones PDO/MySQL, ubicación de `config.php`, permisos de `seguridad/almacen` y si existe proxy inverso. Luego probar la URL HTTPS desde otra estación de la LAN.

## Smoke test pendiente en LAN

- Inicio y cierre de sesión de administrador, técnico y solicitante.
- CSRF válido e inválido.
- Alta y resolución de incidencia, servicio y préstamo.
- Recuperación de contraseña y primer acceso con credencial temporal.
- Alta, edición, traslado y baja lógica de equipo.
- Movimiento Kanban hacia `En revision` y edición de dificultad.
- Consulta conjunta de planillas por técnico.
- Carga de fotografía JPG/PNG y rechazo de contenido inválido.
- Confirmación de que un técnico no ve acciones reservadas a solicitante o administrador.

## Criterio de cierre

El despliegue solo se considerará finalizado cuando todos los puntos anteriores pasen desde un equipo de la LAN y el commit desplegado coincida con la etiqueta final del repositorio.
