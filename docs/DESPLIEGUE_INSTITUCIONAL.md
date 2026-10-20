# Preparación del despliegue institucional

La ejecución en el servidor institucional queda pendiente del acceso por LAN, según la decisión del equipo. Este documento deja preparado el procedimiento verificable.

## Requisitos

- Apache 2.4 con `mod_rewrite` y cabeceras habilitadas.
- PHP 8.0 o superior con PDO MySQL, GD, mbstring y fileinfo.
- MariaDB 10.4 o MySQL compatible.
- Red LAN privada; HTTPS es recomendable, pero no obligatorio para este despliegue cerrado.
- Directorio `uploads/perfiles` escribible por el proceso web y no ejecutable.

## Instalación

1. Publicar el contenido de `proyecto 3ra entrega` fuera de cualquier versión anterior.
2. Crear una base vacía con codificación `utf8mb4`.
3. Importar únicamente `database.sql`; los archivos de `database old` son históricos y no deben ejecutarse.
4. Copiar `config.example.php` como `config.php` y completar conexión local del servidor.
5. Comprobar que `config.php` no sea servido ni versionado.
6. Verificar permisos del directorio de fotografías.
7. Acceder desde otra máquina de la LAN mediante la IP o nombre interno y comprobar las cabeceras CSP, anti-clickjacking y no-sniff.

## Acceso HTTP dentro de la LAN

NEXUS admite HTTP cuando las direcciones observadas directamente por PHP para el cliente (`REMOTE_ADDR`) y el servidor (`SERVER_ADDR`) pertenecen a rangos privados: `10/8`, `172.16/12`, `192.168/16`, enlace local o IPv6 privada. También conserva el acceso local por `localhost`.

No se confía en `X-Forwarded-For` para habilitar esta modalidad. Una conexión que tenga cualquiera de sus extremos fuera de los rangos privados continúa necesitando HTTPS y recibe estado 403. `.htaccess` ya no fuerza una redirección a un puerto 443 que puede no existir; la decisión se realiza en PHP con las direcciones reales de la conexión.

Esto permite desplegar los archivos y la base sin solicitar cambios en Apache. Debe accederse desde otra estación mediante una dirección como `http://192.168.x.x/ruta-del-proyecto/`.

HTTP no cifra contraseñas, cookies ni códigos de segundo factor. La excepción solo es aceptable bajo el alcance declarado: servidor sin exposición exterior y clientes dentro de la LAN institucional. Si posteriormente el sistema se publica fuera de esa red, deberá utilizarse HTTPS antes de habilitar el acceso.

## Smoke test pendiente en LAN

- Inicio y cierre de sesión de administrador, técnico y solicitante.
- CSRF válido e inválido.
- Alta y resolución de incidencia, servicio y préstamo.
- Recuperación de contraseña y primer acceso con credencial temporal.
- Alta, edición, traslado y baja lógica de equipo.
- Movimiento Kanban hacia `En revision` y edición de prioridad.
- Consulta conjunta de planillas por técnico.
- Carga de fotografía JPG/PNG y rechazo de contenido inválido.
- Confirmación de que un técnico no ve acciones reservadas a solicitante o administrador.

## Criterio de cierre

El despliegue solo se considerará finalizado cuando todos los puntos anteriores pasen desde un equipo de la LAN y el commit desplegado coincida con la etiqueta final del repositorio.
