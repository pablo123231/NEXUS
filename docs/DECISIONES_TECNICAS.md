# Registro de decisiones técnicas

## ADR-001 — Migración incremental por módulo

**Decisión:** mantener los endpoints como adaptadores HTTP delgados y trasladar reglas a servicios de aplicación e interfaces de repositorio.

**Motivo:** reduce el riesgo de regresión, permite comparar respuestas antes y después, y demuestra uso real de POO en lugar de clases decorativas.

**Consecuencia:** durante la transición conviven endpoints migrados y heredados. El inventario identifica explícitamente cada estado.

## ADR-002 — Compatibilidad mínima PHP 8.0

**Decisión:** no usar `enum`, propiedades `readonly` ni otras características posteriores a PHP 8.0.

**Motivo:** `README.md` declara PHP 8.0+ como requisito. Los estados y roles se implementan como objetos de valor compatibles.

## ADR-003 — Contrato HTTP estable

**Decisión:** conservar rutas, métodos, códigos y nombres de propiedades consumidos por JavaScript. Todas las respuestas continúan usando `status`, `message` y `data`.

**Motivo:** la reestructuración crítica es interna y no debe modificar la interfaz ni interrumpir funciones existentes.

## ADR-004 — Base real sin modificaciones destructivas

**Decisión:** no reparar ni reinicializar automáticamente la instancia MariaDB local dañada.

**Motivo:** existen referencias a tablespaces faltantes. Una reconstrucción podría perder datos fuera del repositorio. Las pruebas de dominio usan repositorios en memoria hasta disponer de una instancia aislada o una copia autorizada.

## ADR-005 — Recuperación transaccional y credencial de un solo uso

**Decisión:** la creación del ticket y de la solicitud, así como la resolución y el cambio de contraseña, se ejecutan dentro de transacciones del repositorio. La contraseña temporal se muestra una única vez y luego se elimina de la solicitud.

**Motivo:** evita tickets huérfanos, estados divergentes y la exposición prolongada de la credencial. El hash instalado en la cuenta se verifica contra la credencial antes de confirmar la transacción.

## ADR-006 — Aprobaciones administrativas atómicas

**Decisión:** la resolución de registros y cambios de rol bloquea la solicitud pendiente y actualiza solicitud y cuenta en una sola transacción. El rol solicitado no se aplica hasta la aprobación del administrador.

**Motivo:** evita aprobaciones dobles, cuentas activas con solicitudes pendientes y discrepancias entre el rol aprobado y el almacenado.

## ADR-007 — Proveedores de seguridad intercambiables

**Decisión:** TOTP, hash, política de contraseñas y secreto 2FA pendiente se exponen mediante interfaces de dominio. Los adaptadores actuales reutilizan las funciones de seguridad existentes y la sesión PHP.

**Motivo:** permite probar las reglas sin debilitar los controles reales, evita que el servicio dependa directamente de variables globales y conserva compatibilidad con el sistema de autenticación implementado.

## ADR-008 — Préstamos agrupados, persistencia individual

**Decisión:** una solicitud con varios equipos conserva la experiencia actual, pero se persiste como un préstamo por equipo dentro de una única transacción y se devuelven todos los identificadores.

**Motivo:** mantiene el modelo relacional existente, permite gestionar cada recurso por separado y evita altas parciales cuando uno de los equipos no puede vincularse.

## ADR-009 — Inventario e historial como unidad transaccional

**Decisión:** altas, ediciones, traslados y bajas relevantes escriben el estado del inventario y su historial en la misma transacción. Una ubicación con equipos activos solo puede darse de baja trasladándolos a otra ubicación activa.

**Motivo:** impide equipos huérfanos y garantiza que cada cambio material pueda auditarse aunque falle una escritura intermedia.

## ADR-010 — Tarea y asignación como agregado

**Decisión:** la tarea y su relación con el responsable se crean, modifican o eliminan en una única transacción. El estado `En revision` se conserva como estado propio del Kanban.

**Motivo:** evita el error previo de tareas existentes que aparecían como inexistentes durante una edición y relaciones huérfanas al reasignar o eliminar.

## ADR-011 — Catálogos limitados por lista permitida

**Decisión:** los nombres de tablas y columnas de los tres catálogos se resuelven exclusivamente desde un mapa interno; nunca desde texto arbitrario recibido por HTTP.

**Motivo:** permite reutilizar el repositorio sin introducir identificadores SQL controlables por el cliente y conserva un único contrato para categorías y servicios disponibles.

## ADR-012 — Imágenes recodificadas y almacenamiento desacoplado

**Decisión:** la foto de perfil se recodifica con GD y nunca se conserva el archivo original. El servicio solo coordina almacenamiento y referencia; el adaptador valida MIME real, tamaño y formato.

**Motivo:** conserva la protección contra archivos políglota y permite comprobar la sustitución de imágenes sin depender del sistema de archivos durante las pruebas del caso de uso.
