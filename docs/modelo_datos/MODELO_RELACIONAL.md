# Modelo relacional vigente

## Alcance y criterio

Este documento describe el esquema instalable de `proyecto 3ra entrega/database.sql`. La revision de prioridad alta mantiene compatibilidad: no elimina tablas ni columnas y no modifica los contratos usados por PHP. El esquema contiene 27 tablas y 31 claves foraneas.

Convenciones: `PK` clave primaria, `FK` clave foranea, `UQ` valor unico, `NN` no admite nulos y `AI` autoincremental.

## Relaciones

| Relacion | Clave primaria | Claves foraneas | Finalidad |
|---|---|---|---|
| `usuario` | `cedula` | - | Identidad, rol, estado y seguridad de acceso. |
| `ubicacion` | `id_ubicacion` AI | - | Secciones fisicas activas o dadas de baja. |
| `equipo` | `id_equipo` AI | `id_ubicacion -> ubicacion` | Inventario informatico y cantidad disponible. |
| `incidencia` | `id_ticket` AI | `id_equipo -> equipo`; `cedula_crea`, `cedula_responde -> usuario` | Tickets, diagnostico, solucion y cierre. |
| `recuperacion_contrasena` | `id_recuperacion` AI | `id_ticket -> incidencia`; usuario solicitante y responsable | Flujo atendido de recuperacion de acceso. |
| `prestamo` | `id_prestamo` AI | `cedula -> usuario`; `id_equipo -> equipo` | Solicitud y ciclo de vida de un recurso prestado. |
| `servicio` | `id_servicio` AI | `cedula -> usuario` | Solicitud y atencion de servicios tecnicos. |
| `tareas` | `id_tarea` AI | - | Trabajo del tablero Kanban. |
| `plantilla_tecnico_asistente` | `id_plantilla` AI | `cedula -> usuario` UQ | Jornada y comentario de cada tecnico. |
| `solicitud_registro` | `id_solicitud_registro` AI | solicitante y revisor `-> usuario` | Aprobacion de cuentas nuevas. |
| `solicitud_cambio_rol` | `id_solicitud_cambio_rol` AI | solicitante y revisor `-> usuario` | Aprobacion o rechazo de roles solicitados. |
| `reporte_error` | `id_reporte_error` AI | `cedula_responsable -> usuario` | Reportes publicos y respuesta administrativa. |
| `seguridad_eventos` | `id` AI | `cedula -> usuario` | Auditoria de eventos de seguridad. |
| `seguridad_intentos_login` | `id` AI | - | Control de intentos y limitacion de acceso. |
| `configuracion_sistema` | `clave` | `actualizado_por -> usuario` | Parametros administrativos del sistema. |
| `categoria_prestamo` | `id_categoria_prestamo` AI | - | Catalogo unico de categorias de prestamo. |
| `categoria_tecnologica` | `id_categoria_tecnologica` AI | - | Catalogo unico de categorias de equipo. |
| `servicio_disponible` | `id_servicio_disponible` AI | - | Catalogo unico de servicios solicitables. |
| `historial_equipo` | `id_historial_equipo` AI | `id_equipo -> equipo` | Cambios relevantes de inventario. |
| `historial_prestamo` | `id_historial_prestamo` AI | `id_prestamo -> prestamo` | Cambios de estado de prestamos. |
| `historial_servicio` | `id_historial_servicio` AI | `id_servicio -> servicio` | Cambios de estado de servicios. |
| `historial_ubicacion` | `id_historial_ubicacion` AI | `id_ubicacion -> ubicacion` | Cambios de ubicaciones. |
| `consulta_plantilla` | (`cedula_consulta`, `cedula_tecnico`) | ambas columnas son FK | Autorizacion de consulta de planillas. |
| `consulta_ubicacion` | (`cedula`, `id_ubicacion`) | ambas columnas son FK | Relacion de consulta entre usuarios y ubicaciones. |
| `realiza_tarea` | (`cedula`, `id_tarea`) | ambas columnas son FK | Asignacion de tareas a personal. |
| `solicita_prestamo` | (`cedula`, `id_prestamo`) | ambas columnas son FK | Relacion heredada solicitante-prestamo. |
| `solicita_servicio` | (`cedula`, `id_servicio`) | ambas columnas son FK | Relacion heredada solicitante-servicio. |

## Dominios e integridad relevantes

- Roles: `Administrador`, `Tecnico_Asistente` y `Solicitante`.
- Estados de usuario: `Activo` e `Inactivo`.
- Bajas funcionales: indicadores `esta_activo` o `esta_activa`; no se eliminan registros operativos.
- Las cantidades de equipos no pueden ser negativas y los indicadores booleanos solo admiten `0` o `1`.
- Una fecha de cierre de incidencia no puede preceder a su solicitud; una devolucion no puede preceder a la solicitud del prestamo.
- Correos, nombres de catalogos y codigos de seguimiento poseen unicidad donde corresponde.
- Los indices compuestos agregados priorizan bandejas por estado, actividad, fecha y responsable sin alterar resultados.

## Evaluacion de normalizacion

La mayor parte de las relaciones operativas separa entidades, historiales y asociaciones muchos-a-muchos. Sin embargo, no se declara cumplimiento estricto de tercera forma normal por estas deudas heredadas:

1. `prestamo.cedula` duplica la asociacion representada por `solicita_prestamo`.
2. `servicio.cedula` duplica la asociacion representada por `solicita_servicio`.
3. `prestamo.categoria`, `equipo.categoria_equipo` y `servicio.tipo_de_servicio` conservan texto aunque existen catalogos relacionados conceptualmente.
4. `prestamo.recursos_solicitados` funciona como instantanea descriptiva; el recurso vigente se identifica tambien mediante `id_equipo`.

Estas redundancias se conservan deliberadamente para evitar una migracion de alto riesgo antes de la entrega. La futura normalizacion debe migrar datos, agregar tablas de detalle cuando corresponda y actualizar repositorios, pruebas, MER y documentos en una version controlada.

## Reproducibilidad

La fuente de verdad es un unico `database.sql`. Su validacion debe hacerse sobre una base vacia y comprobar: 27 tablas, claves foraneas resolubles, restricciones aceptadas, datos iniciales y pruebas de integracion aprobadas.
