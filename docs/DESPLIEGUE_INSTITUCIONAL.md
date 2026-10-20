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
