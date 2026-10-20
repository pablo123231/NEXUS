# Informe de correcciones funcionales y de interfaz

## Cambios implementados

- Se eliminó la advertencia azul del acceso.
- El inicio del solicitante dejó de ejecutar una consulta reservada al plantel técnico; esa era la causa de la pantalla incompleta o detenida.
- Kanban consume cuentas activas de administradores y técnicos/asistentes para creación y edición.
- Planilla TA ahora parte de todo el plantel activo y conserva valores predeterminados cuando una persona todavía no posee una fila de planilla.
- Los comentarios de cierre de incidencias se presentan en un bloque propio, deshabilitan el doble envío y dejan de ser editables después de guardarse.
- Los mensajes generados con el componente común se retiran automáticamente: 4,5 segundos para éxito y 6,5 segundos para información o error.
- La edición de equipos usa el catálogo de categorías y ya no permite modificar cantidad.
- El traslado rápido resincroniza el selector al cargar los destinos.
- Servicios y categorías inactivos poseen acción de reactivación.
- Se adoptó `CATEGORIA_TECNOLOGICA` como catálogo funcional compartido por equipos y préstamos. `CATEGORIA_PRESTAMO` se conserva únicamente para compatibilidad histórica.
- Se eliminó de Cuenta la fecha de registro, actividad reciente y activación visible de segundo factor. Cambio de contraseña se mantiene como función principal de seguridad.
- Se rediseñaron el acceso al cambio de rol y el panel de administración.
- La carga de foto funciona aunque GD no esté disponible. Con GD se conserva la regeneración; sin GD se validan recepción, tamaño, dimensiones y tipo real antes de guardar con nombre aleatorio.

## Decisiones de compatibilidad

- No se eliminaron tablas ni datos históricos.
- La cantidad existente de un equipo se preserva durante la edición.
- Los endpoints y permisos actuales se mantienen.
- El catálogo anterior de préstamos no se muestra ni se usa para nuevas operaciones, pero permanece en la base para no invalidar instalaciones existentes.

## Evidencia

- Sintaxis PHP verificada en 80 archivos.
- Sintaxis JavaScript modular e incrustada verificada.
- Suite de caracterización existente satisfactoria.
- Auditoría bilingüe: 371 claves, 144 usos y 21 pantallas UTF-8.
- Nueva regresión específica en `tests/correcciones_funcionales_characterization.php`.
- Integración de base de datos satisfactoria sobre `rubric_high`: instalación y 18 operaciones PDO verificadas en `127.0.0.1:3307`.
