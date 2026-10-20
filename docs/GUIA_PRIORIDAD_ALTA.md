# Guia interna de implementacion - prioridad alta

## Objetivo

Completar los criterios de alta prioridad de la rubrica final sin alterar de manera accidental el alcance acordado ni degradar las funciones verificadas durante la prioridad critica. El trabajo se realiza exclusivamente sobre `proyecto 3ra entrega` del repositorio privado `Implementacion-de-Rubrica`.

## Criterios de rubrica incluidos

1. Funcionalidad y reglas de negocio de punta a punta.
2. Frontend, experiencia de usuario, semantica y adaptacion a escritorio y movil.
3. Validacion doble, situaciones invalidas y permisos por perfil.
4. Base de datos, tercera forma normal, integridad, MER y datos de prueba.
5. Evidencia reproducible de las operaciones principales.

El despliegue institucional permanece preparado pero pendiente de acceso LAN. La atribucion de commits a cada integrante es una actividad humana: no se falsificaran autores ni contribuciones.

## Principios de trabajo

- Preservar los contratos HTTP y la interfaz salvo que una correccion sea necesaria para la rubrica.
- Probar el comportamiento antes y despues de cada cambio.
- Aplicar primero cambios compatibles; consultar antes de migraciones destructivas o cambios de alcance.
- No usar la documentacion para ocultar fallas: cada afirmacion debe tener evidencia ejecutable.
- Mantener `database.sql` como instalador unico y reproducible.
- No incorporar blacklist ni planillas de clase.

## Diagnostico inicial

### Fortalezas ya verificadas

- Las 22 pantallas declaran viewport y 21 usan `main`; `test-api.html` es una herramienta tecnica y no una pantalla funcional.
- Existen puntos de quiebre para 1265, 1024, 768, 720, 640 y 380 pixeles.
- Los formularios principales incluyen etiquetas y controles requeridos.
- El backend aplica sesiones, roles, CSRF, cuerpos JSON y consultas preparadas.
- `database.sql` crea 27 tablas, 31 claves foraneas y catalogos con nombres unicos.

### Brechas detectadas

- Falta una prueba de regresion por perfil que cubra tambien rechazos y restricciones, no solo caminos exitosos.
- La validacion HTML y JavaScript no esta inventariada campo por campo contra la validacion del servidor.
- Debe comprobarse visualmente cada flujo principal en anchos de escritorio, tableta y telefono.
- Existe una imagen sin atributo `alt` verificable y deben auditarse nombres accesibles, foco y mensajes dinamicos.
- Hay doce usos de atributos o manejadores inline que deben justificarse o extraerse cuando interfieran con CSP y mantenibilidad.
- El esquema conserva redundancias entre `prestamo.cedula` y `solicita_prestamo`, y entre `servicio.cedula` y `solicita_servicio`.
- Categorias, tipos y recursos se guardan parcialmente como texto aun cuando existen catalogos o entidades relacionadas.
- No existe dentro del repositorio un MER actualizado y versionado contra las 27 tablas actuales.
- El historial solo muestra un autor generico; la rubrica exige al menos un commit real de cada integrante.

## Fase A - Matriz de reglas y validaciones

1. Crear una matriz para las 83 funciones con perfil permitido, precondicion, regla, entrada valida, entrada invalida, codigo HTTP y efecto en datos.
2. Vincular cada fila con endpoint, servicio, repositorio y caso de prueba.
3. Identificar estados imposibles, fechas anteriores, recursos inactivos, duplicados y accesos directos sin permiso.
4. Incorporar pruebas automatizadas negativas sin reutilizar datos productivos.

Puerta de calidad: todas las familias funcionales deben tener al menos un caso valido, uno invalido y uno de permiso rechazado.

## Fase B - Frontend y experiencia de usuario

1. Auditar semantica, jerarquia de encabezados, etiquetas, nombres accesibles y textos alternativos.
2. Comprobar navegacion por teclado, foco visible y anuncios de errores mediante regiones vivas.
3. Alinear restricciones HTML con el servidor: requerido, longitud, tipo, rango, fecha y patron.
4. Unificar mensajes de validacion sin cambiar el lenguaje visual existente.
5. Ensayar las pantallas funcionales en 1440, 1024, 768, 390 y 360 pixeles.
6. Corregir desbordes, controles inaccesibles y tablas sin alternativa movil.

Puerta de calidad: ninguna funcion debe desaparecer ni quedar fuera de pantalla en los anchos definidos.

## Fase C - Integridad y normalizacion

1. Construir un diccionario de datos real desde `database.sql`.
2. Clasificar claves, dependencias funcionales, redundancias y atributos multivaluados.
3. Conservar el esquema compatible por decision del equipo y documentar las redundancias heredadas.
4. Agregar restricciones de dominio e indices que no cambien contratos ni eliminen datos.
5. Validar el SQL reforzado en una base aislada antes de reemplazar el instalador.
6. Mantener `database.sql` como instalador unico y comprobar una importacion limpia.

Puerta de calidad: instalacion en base vacia, 27 tablas o el nuevo numero documentado, datos consistentes y todas las pruebas aprobadas.

## Fase D - MER y modelo relacional

1. Generar el MER conceptual con entidades, atributos, identificadores, relaciones y cardinalidades.
2. Generar el modelo relacional con tablas, PK, FK, UNIQUE, nulabilidad y dominios relevantes.
3. Versionar las fuentes editables y exportaciones legibles dentro de `docs/modelo_datos`.
4. Comparar automaticamente tablas y relaciones del documento contra `database.sql`.

Puerta de calidad: ninguna tabla ni relacion vigente puede quedar sin representar.

## Fase E - Datos y demostracion por perfiles

1. Preparar cuentas identificables para Administrador, Tecnico_Asistente y Solicitante.
2. Incluir estados representativos sin contrasenas en texto plano.
3. Crear un guion de demostracion que no dependa de datos creados manualmente durante la defensa.
4. Registrar resultados y restaurar la base de prueba despues de la ejecucion.

## Fase F - Regresion y publicacion

1. Ejecutar sintaxis PHP/JavaScript, pruebas de dominio, PDO y HTTP.
2. Ejecutar pruebas negativas, permisos y responsividad.
3. Actualizar README, evidencia, decisiones y PDF unico.
4. Publicar commits pequenos y trazables.
5. Mantener como pendiente solo aquello que realmente requiera la LAN institucional o accion humana de los integrantes.

## Decision adoptada sobre tercera forma normal

### Alternativa 1 - Normalizacion real descartada para esta entrega

- Eliminar la duplicacion entre las tablas de solicitud y las columnas directas de usuario.
- Reemplazar textos repetidos por claves a catalogos donde la identidad del concepto sea necesaria.
- Modelar los recursos de un prestamo en una tabla de detalle en lugar de `recursos_solicitados` como texto.
- Migrar datos, repositorios, consultas, pruebas, MER y documentacion.

Se descarta en esta etapa porque la migracion de datos, repositorios, contratos internos y documentos introduce una carga y un riesgo desproporcionados cerca de la entrega.

### Alternativa 2 - Compatibilidad documentada elegida

- Mantener el esquema y agregar solo restricciones, indices y documentacion de las redundancias.
- Explicar que ciertas columnas son instantaneas historicas o compatibilidad heredada.

Se reforzara el esquema existente con indices y restricciones compatibles. Las duplicaciones heredadas se declararan como deuda tecnica y no se afirmara un cumplimiento estricto de tercera forma normal donde no pueda demostrarse.

Ventaja: menor riesgo inmediato y preservacion total de funciones. Riesgo aceptado: una evaluacion estricta puede descontar parcialmente el criterio de tercera forma normal.

## Avance implementado

- Se reforzaron reglas de incidencias, servicios y tareas para rechazar campos obligatorios vacios, fechas invalidas, dificultades fuera del dominio y colores mal formados.
- Se agregaron pruebas negativas y la regresion de aplicacion alcanzo 106 comportamientos aprobados.
- `database.sql` conserva sus 27 tablas e incorpora restricciones e indices compatibles; la importacion y 18 operaciones PDO se verificaron en una base aislada.
- Se versionaron el modelo relacional y una fuente editable del MER, contrastados automaticamente contra 27 tablas y 31 claves foraneas.
- Se agregaron foco visible y regiones vivas a mensajes dinamicos principales; 22 pantallas aprobaron la auditoria estatica de estructura e imagenes.
- Permanecen como verificaciones manuales el recorrido visual en todos los anchos objetivo y la prueba final desde la LAN institucional.

## Decisiones que requieren consulta

Se detendra la implementacion antes de normalizar tablas existentes, eliminar columnas, cambiar contratos HTTP, modificar roles, eliminar datos, redisenar pantallas, inventar autores de Git o ampliar el alcance funcional.
