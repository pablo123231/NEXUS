# Guía de implementación: correcciones funcionales y de interfaz

## 1. Objetivo

Corregir los defectos observados en autenticación, tablero Kanban, incidencias, inventario, planillas TA, cuenta, administración, servicios, préstamos, categorías y fotografías de perfil sin alterar innecesariamente la arquitectura vigente ni romper los contratos consumidos por el frontend.

## 2. Principios de trabajo

1. Mantener el modelo actual y aplicar cambios compatibles y acotados.
2. Corregir primero la fuente de datos o regla de negocio y después su presentación.
3. Centralizar reglas compartidas para evitar que distintos módulos vuelvan a divergir.
4. Conservar controles de permisos en backend aunque la interfaz oculte acciones.
5. No eliminar datos históricos. Las bajas seguirán siendo lógicas y reversibles cuando corresponda.
6. Verificar cada corrección con pruebas automáticas, revisión estática y recorridos funcionales por rol.
7. Documentar decisiones, compatibilidad, riesgos y evidencia de prueba.

## 3. Alcance y orden de implementación

### Fase A — Diagnóstico reproducible

- Mapear cada pantalla con su endpoint, servicio y repositorio.
- Identificar las reglas de filtrado por rol y estado.
- Revisar el esquema y los datos semilla relacionados con usuarios, categorías, equipos, préstamos, servicios y planillas.
- Confirmar qué mensajes utilizan el componente común de notificaciones y cuáles escriben directamente en el DOM.

### Fase B — Correcciones funcionales prioritarias

#### B.1 Roles, Kanban y planillas TA

- Crear una única fuente de responsables asignables: cuentas activas con rol `Administrador` o `Tecnico_Asistente`.
- Usarla tanto al crear como al editar tareas.
- Hacer que la planilla TA parta del plantel activo y use una unión opcional con la planilla, de modo que una persona aparezca aunque todavía no posea registros.
- Mantener restricciones del servidor: un identificador manipulado desde el navegador no debe permitir asignar a un solicitante.

#### B.2 Incidencias y comentarios

- Reorganizar el editor de comentario dentro de la acción de resolución o rechazo.
- Guardar acción y comentario de manera inequívoca.
- Tras una respuesta exitosa, reemplazar el editor por la visualización del comentario guardado y bloquear reenvíos duplicados.
- Mantener mensajes claros para errores recuperables.

#### B.3 Inventario y traslados

- Sustituir la categoría libre de edición por un selector alimentado por el catálogo compartido.
- Retirar la cantidad del formulario y del contrato de edición; la cantidad existente se preservará.
- Reparar el selector rápido de traslado para listar todas las secciones activas salvo la actual.
- Validar en backend que categoría y sección existan y estén habilitadas.

#### B.4 Servicios reversibles

- Mostrar los servicios inactivos en administración con una acción `Dar de alta`.
- Reutilizar la operación de alta lógica para reactivarlos y actualizar inmediatamente la lista disponible para solicitudes.

#### B.5 Catálogo único de categorías

Decisión adoptada: `categoria_tecnologica` será el catálogo canónico compartido.

- Administración creará, editará, desactivará y reactivará categorías tecnológicas.
- Alta y edición de equipos consumirán exclusivamente ese catálogo.
- Préstamos mostrarán las mismas categorías activas y, al seleccionar una, ofrecerán únicamente equipos activos y disponibles asociados con ella.
- `categoria_prestamo` quedará como compatibilidad histórica de base de datos, sin ser una segunda fuente funcional. No se eliminarán tablas ni registros en esta intervención.
- Los endpoints conservarán respuestas JSON consistentes (`status`, `message`, `data`).

#### B.6 Fotografía de perfil

- Mantener límite de tamaño, nombres aleatorios, extensiones permitidas y comprobación real de imagen.
- Usar GD cuando esté disponible para decodificar y regenerar la imagen.
- Incorporar una ruta compatible y controlada para instalaciones sin GD, evitando que una dependencia opcional inutilice la función.
- Devolver mensajes diferenciados para archivo inválido y fallo de infraestructura.

### Fase C — Correcciones de interfaz

#### C.1 Login

- Eliminar por completo el bloque azul de advertencia solicitado, sin dejar espacio residual.

#### C.2 Mensajes temporales

- Centralizar notificaciones transitorias.
- Los mensajes de éxito y error no persistentes se ocultarán automáticamente tras un intervalo fijo y accesible.
- Los mensajes que requieran una decisión explícita se mantendrán hasta que el usuario actúe.

#### C.3 Cuenta

- Eliminar actividad reciente, activación de segundo factor y fecha de registro.
- Conservar cambio de contraseña en una tarjeta clara y proporcionada.
- Mejorar el bloque de solicitud/cambio de rol sin cambiar su flujo de aprobación.

#### C.4 Panel de administración

- Reorganizar jerarquía, espaciado, tarjetas, títulos, filtros y acciones.
- Mantener intactas las funciones y permisos existentes.
- Asegurar comportamiento correcto en pantallas pequeñas.

#### C.5 Solicitante

- Revisar la construcción del inicio, navegación y permisos para que el perfil solicitante no reciba componentes reservados a técnicos o administradores.
- Probar módulos autorizados y ausencia de acciones prohibidas.

### Fase D — Compatibilidad y migración

- Evitar una normalización estructural riesgosa.
- Si se requieren índices, valores o datos de compatibilidad, incorporarlos de forma idempotente a `database.sql`.
- No depender de migraciones externas para una instalación nueva.
- Preservar datos históricos y claves vigentes.

### Fase E — Verificación

#### Pruebas automáticas

- Ejecutar la suite completa existente.
- Añadir pruebas de caracterización para responsables, plantilla completa, categorías compartidas, edición sin cantidad, reactivación de servicios y almacenamiento de imágenes.
- Validar sintaxis PHP y JavaScript.

#### Pruebas funcionales por rol

- Administrador: crear/editar tareas asignadas a técnicos; administrar categorías; editar/trasladar equipos; reactivar servicios; consultar planillas; gestionar incidencias.
- Técnico: figurar como responsable y en planillas; acceder solo a acciones permitidas.
- Solicitante: iniciar sesión, navegar, solicitar préstamo/servicio e informar incidencia sin contaminación de interfaz administrativa.

#### Criterios de aceptación

- Cada defecto descrito es reproducible antes o está respaldado por su causa identificada.
- La corrección funciona después de recargar y en una sesión nueva.
- No aparecen errores de consola, respuestas HTML inesperadas en APIs ni escrituras parciales.
- Los permisos se cumplen también llamando directamente al endpoint.
- La suite automática termina satisfactoriamente; cualquier prueba dependiente de infraestructura queda identificada por separado.

## 4. Riesgos y mitigaciones

- **Datos antiguos con categorías no catalogadas:** mostrarlos como valor histórico y permitir su normalización al editar; no borrar automáticamente.
- **Instalaciones sin GD:** aplicar validación estricta alternativa y documentar que GD sigue siendo la opción preferida.
- **Usuarios sin planilla previa:** presentarlos con valores vacíos o cero, creando/actualizando su fila recién al guardar.
- **Doble envío de comentarios:** deshabilitar controles durante la solicitud y sustituir el formulario al confirmar.
- **Divergencia entre interfaces:** consumir catálogos y responsables desde endpoints comunes, no desde listas incrustadas.

## 5. Registro de decisiones

- Se adopta un catálogo tecnológico único porque evita duplicaciones y permite que inventario y préstamos utilicen la misma clasificación sin una migración destructiva.
- La cantidad del equipo no se modifica desde edición; queda reservada al alta y a operaciones de inventario explícitas.
- Los mensajes de validación serán temporales, pero las advertencias que bloqueen una decisión no desaparecerán automáticamente.
- Las bajas de servicios y categorías serán reversibles.

