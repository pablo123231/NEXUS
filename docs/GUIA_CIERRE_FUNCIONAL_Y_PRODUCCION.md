# Guía interna de cierre funcional y preparación de producción

## Mandato de trabajo

Cerrar la versión productiva de NEXUS sin rediseñar la interfaz ni ampliar el alcance acordado. La prioridad es que cada operación visible tenga un ciclo de vida completo, reglas de transición explícitas, persistencia transaccional, permisos de servidor, disponibilidad consistente y evidencia reproducible. La documentación académica final se actualizará después de estabilizar el sistema.

## Fuente de verdad y copias

- Repositorio de publicación: `pablo123231/NEXUS`, rama `main`.
- Carpeta funcional: `proyecto 3ra entrega`.
- Repositorio técnico espejo: `jeaan8/Implementacion-de-Rubrica`.
- Copia local ejecutable: `C:\xampp\htdocs\proyecto 3ra entrega`.
- Instalador canónico: un único `database.sql`.
- Nunca versionar `config.php`, secretos del servidor, archivos subidos por usuarios ni datos temporales de pruebas.

## Principios obligatorios

1. La interfaz no define permisos: cada acción se vuelve a autorizar en el backend.
2. Toda transición de estado se valida contra el estado persistido, no contra lo que muestra el navegador.
3. Toda operación que afecte disponibilidad utiliza transacción y bloqueo de filas.
4. Una solicitud nunca puede producir stock negativo ni reservar un equipo inactivo, dado de baja o en mantenimiento.
5. Los estados terminales no pueden reabrirse accidentalmente.
6. Cada cambio relevante deja historial con fecha, estado y responsable cuando corresponda.
7. Las respuestas mantienen el contrato JSON `status`, `message`, `data` y códigos HTTP coherentes.
8. Los mensajes al usuario no exponen SQL, rutas internas ni secretos.
9. Los cambios estructurales deben funcionar tanto en una instalación limpia como sobre la base local vigente.
10. No se declara ejecutada ninguna verificación que dependa de la LAN institucional hasta realizarla allí.

## Bloque A — ciclo de vida de préstamos

### Estados y transiciones

Transiciones permitidas:

```text
Solicitado -> Activo
Solicitado -> Cancelado
Activo -> Devuelto
```

Reglas:

- `Solicitado` se presenta como `Pendiente`.
- `Activo` se presenta como `Aprobado`.
- `Cancelado` se presenta como `Denegado`.
- `Devuelto` conserva ese nombre.
- Un préstamo terminal no vuelve a un estado anterior.
- La baja administrativa sólo actúa sobre registros operativos y deja trazabilidad.

### Devolución

- Mostrar `Marcar como devuelto` únicamente para préstamos aprobados.
- La acción queda reservada a Técnico/Asistente y Administrador.
- Registrar `fecha_devolucion_real` en el servidor.
- Insertar historial de devolución.
- Liberar disponibilidad al confirmar la transacción.
- Permitir un comentario asociado; una vez guardado se muestra como texto para evitar reenvíos accidentales.

### Disponibilidad

- Cantidad total: inventario físico activo.
- Cantidad reservada: suma de préstamos `Solicitado` y `Activo` que continúan operativos.
- Cantidad disponible: `total - reservada`, nunca menor que cero.
- `Cancelado`, `Devuelto` o baja lógica liberan stock.
- El selector muestra total, reservado y disponible.
- La creación y aprobación vuelven a validar el stock en el backend bajo bloqueo.
- La interfaz se refresca después de crear, aprobar, denegar, devolver o dar de baja.

### Historial

Registrar como mínimo:

- Creación.
- Aprobación.
- Denegación.
- Devolución.
- Baja administrativa.
- Comentario agregado.

## Bloque B — controles técnicos de producción

- Conexión mediante `config.php` no versionado y usuario de base sin privilegios administrativos globales.
- `utf8mb4` en conexión y esquema.
- HTTPS obligatorio en el servidor institucional.
- PDO MySQL, GD, mbstring y fileinfo disponibles.
- Escritura limitada a los directorios estrictamente necesarios.
- Prohibir ejecución de scripts dentro de cargas de usuario.
- Ocultar errores internos al cliente y registrarlos del lado del servidor.
- Verificar cookies de sesión seguras bajo HTTPS, CSRF, TOTP y expiración.
- Usar una ruta de publicación estable, preferentemente sin espacios.

## Bloque C — datos y secretos

- Antes del despliegue real, rotar contraseñas iniciales y secreto TOTP administrativo.
- Definir cuentas de demostración para Administrador, Técnico/Asistente y Solicitante.
- No inventar ni publicar credenciales definitivas sin decisión explícita del equipo.
- Conservar datos actuales sólo si fueron autorizados para el entorno de destino.

## Pruebas obligatorias

### Automatizadas

- Crear préstamo dentro del stock.
- Rechazar cantidad superior al stock.
- Rechazar equipo inactivo o de otra categoría.
- Aprobar préstamo pendiente.
- Denegar préstamo pendiente.
- Devolver préstamo aprobado y registrar fecha real.
- Rechazar transiciones ilegales.
- Comprobar liberación de stock al denegar, devolver o dar de baja.
- Comprobar historial.
- Importar `database.sql` en una base vacía y ejecutar integración PDO.

### Manuales locales

- Recorrido completo con administrador, técnico y solicitante.
- Mensajes de cero disponibilidad.
- Actualización de cantidad sin recarga manual.
- Comentarios no reenviables accidentalmente.
- Vista de estados e historial.

### Pendientes del entorno institucional

- HTTPS y cabeceras.
- Permisos de carpetas.
- URL y rutas definitivas.
- Prueba desde otra estación de la LAN.
- Operaciones simultáneas reales.
- Comparación del commit desplegado con la etiqueta final.

## Puerta de cierre

El bloque se considera cerrado sólo cuando las pruebas automatizadas e integración SQL pasan, la copia de XAMPP coincide con el repositorio y no quedan regresiones conocidas. El despliegue institucional continuará marcado como pendiente hasta ejecutarse físicamente.
