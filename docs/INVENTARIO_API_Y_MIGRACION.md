# Inventario de API y estado de migración

Fecha de corte: 2026-10-19. Fuente: código de `proyecto 3ra entrega/api` y política `autorizarEndpoint()`.

| Recurso | Métodos | Acceso principal | Capa POO real | Prueba automatizada |
|---|---|---|---|---|
| incidencias | GET, POST, PUT, PATCH, DELETE | lectura autenticada; alta Solicitante/Admin; gestión Técnico/Admin | Sí | Sí |
| usuarios | GET, POST, PUT, PATCH, DELETE | perfil propio; administración y altas/bajas Admin | Sí | Sí |
| préstamos | GET, POST, PUT, PATCH, DELETE | lectura autenticada; alta Solicitante/Admin; gestión Técnico/Admin | Sí | Sí |
| servicios | GET, POST, PUT, PATCH, DELETE | lectura autenticada; alta Solicitante/Admin; gestión Técnico/Admin | Sí | Sí |
| equipos | GET, POST, PUT, PATCH, DELETE | lectura autenticada; cambios Técnico/Admin | Sí | Sí |
| ubicaciones | GET, POST, PUT, PATCH, DELETE | lectura autenticada; cambios Técnico/Admin | Sí | Sí |
| tareas | GET, POST, PUT, PATCH, DELETE | lectura autenticada; cambios Técnico/Admin | Sí | Sí |
| recuperación | GET, POST, PUT, PATCH | creación/consulta pública; tratamiento Técnico/Admin | Sí | Sí |
| seguridad de cuenta | GET, POST | usuario autenticado | Sí | Sí |
| solicitudes de registro | GET, POST, PUT, PATCH | alta pública; tratamiento Admin | Sí | Sí |
| cambios de rol | GET, POST, PUT, PATCH | solicitud autenticada; tratamiento Admin | Sí | Sí |
| reportes de error | GET, POST, PUT, PATCH | alta pública; tratamiento Técnico/Admin | Sí | Sí |
| planilla TA | GET, PUT, PATCH | Técnico/Admin | Sí | Sí |
| catálogos | GET, POST, PUT, PATCH, DELETE | lectura autenticada; cambios Admin | Sí | Sí |
| configuración | GET, PUT, PATCH | lectura autenticada; cambios Admin | Sí | Sí |
| historial | GET | autenticado | Sí | Sí |
| foto de perfil | POST | autenticado | Sí | Sí |
| sesión | GET | autenticado | Adaptador sin reglas de negocio | Cubierto por regresión HTTP |
| cierre de sesión | POST | autenticado | Adaptador de seguridad | Cubierto por regresión HTTP |

## Contrato vigente

Todas las respuestas mantienen `{status, message, data}`. Las excepciones de dominio se traducen en `api/common.php` sin filtrar detalles de PDO. La migración conserva rutas, métodos y nombres JSON; cualquier cambio de contrato requiere consulta previa.

## Evidencia actual

- `tests/incidencia_characterization.php`: alta, toma, resolución, comentario, presentación y baja lógica.
- `tests/usuario_characterization.php`: alta, hash de contraseña, presentación, edición, cambio de contraseña, estado y baja lógica.
- `tests/recuperacion_characterization.php`: creación idempotente, toma, resolución, rechazo, hash coincidente y entrega de credencial de un solo uso.
- `tests/solicitudes_identidad_characterization.php`: registro inactivo, hash, aprobación/activación, rechazo, solicitud de cualquier rol y resolución administrativa.
- `tests/seguridad_cuenta_characterization.php`: alta, confirmación, desactivación y restablecimiento 2FA; cierre remoto de sesiones; cambio de contraseña y actividad.
- `tests/operaciones_centrales_characterization.php`: préstamos múltiples y límite temporal, estados, comentarios y bajas; alta, atención, finalización y baja de servicios.
- `tests/inventario_characterization.php`: alta, edición integral, mantenimiento, traslado y baja de equipos; alta, renombrado y baja de ubicaciones con traslado obligatorio.
- `tests/trabajo_characterization.php`: alta, edición, dificultad, asignación, movimientos Kanban incluida revisión, eliminación y visibilidad conjunta de planillas TA.
- `tests/administracion_characterization.php`: reporte público y resolución; altas, cambios y bajas de catálogos; configuraciones permitidas e historial combinado.
- `tests/perfil_characterization.php`: reemplazo de foto, persistencia de referencia y eliminación controlada de la imagen anterior.
- La sintaxis de todos los archivos migrados fue comprobada con el intérprete PHP de XAMPP.
- `database.sql` se importó en una instancia MariaDB aislada: creó 27 tablas. La suite PDO ejecutó 18 operaciones integradas y limpió sus datos. La instalación principal dañada no fue alterada.
