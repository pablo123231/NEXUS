# Rúbrica 2.0 — reevaluación posterior al rework

## 1. Identificación de la evaluación

- Proyecto evaluado: NEXUS / SGRSI.
- Versión examinada: `proyecto 3ra entrega`.
- Commit de corte: `6a26fbc80a15fe9f0a458bbd4c76ca75f56d7946`.
- Fecha de corte: 20 de octubre de 2026.
- Rúbrica de referencia: `Rubrica_final_ITI_FullStack_2026.pdf`.
- Carácter del resultado: autoevaluación técnica basada en evidencia; la calificación oficial corresponde al equipo docente.

## 2. Metodología y límites

La evaluación contrasta cada criterio de la rúbrica con código, esquema SQL, documentación, historial Git y pruebas repetibles de la versión de corte. Se distinguen tres niveles de evidencia:

1. **Implementado:** existe una solución identificable en el código o en el esquema.
2. **Verificado localmente:** existe una prueba automática, una importación limpia o una comprobación reproducible en XAMPP/MariaDB.
3. **Pendiente de demostración:** requiere inspección visual completa, participación humana o acceso a la LAN institucional.

Durante esta reevaluación se ejecutaron nuevamente las suites disponibles. Aprobaron 129 comportamientos de aplicación e interfaz, la auditoría estática de 22 pantallas, la correspondencia de 27 tablas y 31 claves foráneas, 18 operaciones PDO sobre una instalación limpia y la auditoría de 371 claves bilingües. Esto no equivale a afirmar que las 83 funciones fueron recorridas manualmente de punta a punta en producción.

## 3. Resultado ejecutivo

| N.º | Criterio | Peso | Puntaje estimado | Estado |
|---:|---|---:|---:|---|
| 1 | Funcionalidad y reglas de negocio | 25 | **21,5** | Alto, con cierre manual pendiente |
| 2 | Frontend y experiencia de usuario | 10 | **8,0** | Bueno, falta verificación multidispositivo completa |
| 3 | Validación y seguridad | 15 | **13,5** | Muy bueno |
| 4 | Arquitectura | 10 | **8,0** | Bueno, convive con legado |
| 5 | Programación orientada a objetos | 10 | **8,0** | Buena aplicación práctica |
| 6 | Base de datos y datos de prueba | 10 | **7,0** | Importable, pero no acredita 3FN estricta ni los tres perfiles |
| 7 | Despliegue | 5 | **0,5** | Preparado, no ejecutado en el servidor institucional |
| 8 | Repositorio | 5 | **2,5** | Privado y ordenado; atribución individual incumplida |
| 9 | Documentación | 5 | **3,5** | Amplia, pero debe consolidarse y alinearse con la versión final |
| 10 | Pruebas | 5 | **4,0** | Evidencia sólida local; falta ejecución final/producción |
|  | **Total estimado** | **100** | **76,5 / 100** | **Aprobación probable, entrega aún no cerrada** |

El resultado no debe interpretarse como “76,5 % de las funciones”. Es una estimación ponderada del cumplimiento demostrable de la rúbrica. La calidad interna es superior a la preparación formal de la entrega: los mayores riesgos actuales son de despliegue, trazabilidad y evidencia.

## 4. Evaluación detallada

### 4.1 Funcionalidad y reglas de negocio — 21,5/25

**Evidencia favorable**

- El alcance vigente cubre incidencias, préstamos, servicios, inventario, usuarios, registro, roles, recuperación, seguridad de cuenta, Kanban, planillas TA, reportes, catálogos, configuración, historial y perfil.
- Los adaptadores delegan en servicios y repositorios; las operaciones conservan cambios en MariaDB.
- Se prueban rechazos por campos vacíos, fechas inválidas, estados, roles y recursos no disponibles.
- Las últimas correcciones incorporan responsables técnicos en tareas, normalización de roles, panel administrativo, comentarios, categorías, planillas y restricciones por perfil.
- Se mantienen las exclusiones acordadas: blacklist y planillas de clase.

**Brecha**

- No existe todavía un acta final de ejecución manual que recorra las 83 funciones sobre la misma versión de entrega.
- La suite automatizada reduce el riesgo, pero no sustituye por completo la demostración de interfaz, backend y persistencia para cada flujo.
- La base instalable no trae un solicitante activo, por lo que la demostración limpia de los tres perfiles requiere crear o aprobar uno previamente.

**Cierre requerido**

Ejecutar una matriz final por rol con camino válido, rechazo relevante y comprobación de persistencia; registrar fecha, versión, resultado y evidencia mínima.

### 4.2 Frontend y experiencia de usuario — 8/10

**Evidencia favorable**

- Las 22 pantallas auditadas poseen viewport, título y estructura principal; las imágenes funcionales tienen texto alternativo.
- La interfaz dispone de español e inglés, 371 claves equivalentes y control de UTF-8 para `ñ`, tildes y signos.
- Existen puntos de quiebre y reglas responsivas para escritorio, tableta y móvil.
- Los mensajes dinámicos principales usan regiones vivas y existe foco visible.
- Las correcciones recientes atacan problemas reales de perfiles, formularios, selección de técnicos y estados visibles.

**Brecha**

- La auditoría actual es principalmente estática; no prueba que todos los modales, tablas, menús y formularios sean usables a 360, 390, 768, 1024 y 1440 px.
- Persisten componentes heredados y lógica de presentación concentrada en archivos grandes, lo que eleva el riesgo de inconsistencias.
- Debe comprobarse navegación completa por teclado, orden de foco y mensajes de error con lector de pantalla.

### 4.3 Validación y seguridad — 13,5/15

**Evidencia favorable**

- Login, logout, regeneración de sesión, versión de sesión y TOTP obligatorio para administradores.
- Hash de contraseñas, migración de valores heredados, política de contraseña y credencial temporal.
- Autorización en servidor, incluso cuando el frontend oculta acciones.
- CSRF y comprobación de origen en operaciones mutables.
- Limitación de intentos de login, registro y API.
- Consultas preparadas en repositorios PDO, errores externos genéricos y registro interno.
- Validación de MIME, tamaño y tratamiento de fotografías.

**Brecha**

- HTTPS, cabeceras finales, permisos de directorios y comportamiento detrás del servidor institucional no están comprobados.
- Conviene ejecutar una revisión manual de acceso directo a cada endpoint con los tres roles antes de cerrar.
- No se aporta aún un informe independiente de análisis dinámico o escaneo de dependencias; no es obligatorio en la rúbrica, pero reforzaría la defensa.

### 4.4 Arquitectura — 8/10

**Evidencia favorable**

- Separación reconocible entre adaptadores HTTP, aplicación, dominio, infraestructura y persistencia.
- Los endpoints principales delegan reglas y acceso a datos.
- Las consultas están encapsuladas en repositorios PDO.
- Hay autocarga y construcción explícita de dependencias sin exigir Composer en producción.

**Brecha**

- La migración convive con código heredado y funciones globales de compatibilidad.
- Parte del JavaScript y de algunas páginas sigue siendo extensa y difícil de recorrer.
- No todos los módulos poseen el mismo grado de aislamiento; la defensa debe seleccionar recorridos representativos y explicar claramente qué parte es adaptador, servicio y repositorio.

### 4.5 Programación orientada a objetos — 8/10

**Evidencia favorable**

- Servicios de aplicación, interfaces de repositorio, implementaciones PDO, objetos de valor, estados y excepciones participan realmente en los casos de uso.
- `Cedula`, `Rol` y estados impiden valores inválidos antes de persistir.
- El polimorfismo se aplica mediante contratos de repositorio y dobles en memoria frente a implementaciones PDO.
- La encapsulación facilita probar reglas sin servidor HTTP.

**Brecha**

- No todo el sistema histórico está modelado mediante entidades ricas; varios objetos se transportan aún como arreglos.
- Debe prepararse una explicación de por qué no se fuerza herencia donde no aporta valor. La rúbrica pide herencia y polimorfismo cuando estén justificados, no una jerarquía artificial.

### 4.6 Base de datos y datos de prueba — 7/10

**Evidencia favorable**

- `database.sql` instala 27 tablas, 31 claves foráneas, restricciones, índices y datos iniciales sin correcciones manuales.
- La importación limpia y 18 operaciones PDO se comprobaron nuevamente.
- Existe MER editable y modelo relacional contrastado automáticamente con el SQL.
- Las bajas lógicas, históricos y transacciones preservan trazabilidad.

**Brecha determinante**

- El propio modelo documenta que no puede afirmarse cumplimiento estricto de tercera forma normal: existen asociaciones duplicadas y atributos textuales que conviven con catálogos.
- La decisión de compatibilidad fue consciente y reduce riesgo funcional, pero la rúbrica puede descontarla.
- Los datos base incluyen un administrador y dos técnicos; no incluyen un solicitante activo. Por tanto, no permiten ejecutar inmediatamente todos los perfiles requeridos.

**Cierre de bajo riesgo**

Agregar un solicitante de demostración con hash seguro y estados mínimos representativos. Mantener declarada la deuda de 3FN; una normalización estructural a esta altura sólo debe hacerse si el docente la exige expresamente.

### 4.7 Despliegue — 0,5/5

Existe una guía completa y el sistema funciona en XAMPP local. Sin embargo, la rúbrica exige instalación y demostración en el servidor institucional. Como esa ejecución está pendiente por acceso LAN, el criterio no puede considerarse cumplido. La fracción asignada reconoce preparación, no despliegue.

Para cerrar: desplegar, configurar HTTPS y permisos, ejecutar el smoke test desde otra estación de la LAN y registrar URL, fecha, commit y responsables.

### 4.8 Repositorio — 2,5/5

**Evidencia favorable**

- Repositorio privado, estructura identificable, README, código, SQL único e informe PDF.
- Existe una etiqueta de respaldo y el historial muestra evolución técnica.

**Brecha determinante**

- El historial local presenta 22 commits atribuidos únicamente a `Equipo NEXUS <equipo@nexus.local>`.
- La rúbrica exige que todos los integrantes posean al menos un commit. No corresponde falsificar autores ni reescribir contribuciones: cada integrante debe realizar y publicar un aporte real con su identidad configurada.
- Debe verificarse externamente que todos los docentes requeridos continúen con acceso.
- El PDF consolidado actual tiene fecha de corte anterior a las últimas correcciones; no representa todavía el commit evaluado por este informe.

### 4.9 Documentación — 3,5/5

**Evidencia favorable**

- Hay decisiones técnicas, inventario de API, guías por prioridad, modelo de datos, MER, despliegue, pruebas y 83 casos de caja blanca.
- README explica requisitos, estructura e instalación.
- Las limitaciones de normalización y despliegue se declaran sin presentarlas como resueltas.

**Brecha**

- La entrega solicita un único PDF y coherencia entre servidor, repositorio, documentación y SQL. El PDF debe regenerarse después del cierre funcional y contener esta reevaluación.
- `PRUEBAS_AUTOMATIZADAS.md` quedó desactualizado después de incorporar la suite de correcciones y debe reflejar 129 comportamientos.
- Faltan credenciales o un procedimiento inequívoco de acceso de prueba para cada perfil sin exponer contraseñas reales.

### 4.10 Pruebas — 4/5

**Evidencia favorable**

- 129 comportamientos de aplicación/interfaz aprobados.
- 18 operaciones PDO sobre base real y comprobación de 27 tablas/31 claves foráneas.
- Auditoría estática de 22 pantallas y auditoría de 371 claves bilingües.
- Se cubren entradas inválidas, reglas, estados y permisos en varias familias.
- Existen 83 casos de caja blanca adaptados al template.

**Brecha**

- Falta un registro final, fechado y ligado al commit de entrega, con resultado obtenido de una ejecución manual representativa.
- Falta repetir las pruebas críticas en el servidor institucional.
- Deben registrarse explícitamente las fallas pendientes encontradas durante esa ejecución, aunque su lista sea vacía.

## 5. Plan de cierre ordenado por prioridad

### Prioridad crítica — condiciones para considerar entregable el sistema

1. **Despliegue institucional.** Publicar el commit elegido y ejecutar el smoke test desde la LAN con administrador, técnico y solicitante.
2. **Alinear la versión.** Identificar un único commit o etiqueta final y comprobar que código, `database.sql`, PDF y servidor sean idénticos a esa versión.
3. **Atribución real.** Jean Ortiz, Pablo Mecoll, Josefina Prieto y Thiago Rodriguez deben realizar al menos un commit real, pequeño y explicable, con su propia identidad Git.
4. **Datos de demostración.** Incorporar un solicitante activo y documentar acceso de prueba seguro para los tres perfiles.
5. **Ejecución final.** Recorrer los flujos principales y sus rechazos, guardar resultados y corregir cualquier regresión antes de etiquetar.
6. **PDF único actualizado.** Regenerar el informe consolidado después de los puntos anteriores y retirar de la entrega cualquier PDF documental obsoleto o duplicado.

### Prioridad alta — maximizar la nota y reducir riesgo de defensa

1. Crear una matriz resumida de las 83 funciones con rol, precondición, operación, persistencia y evidencia.
2. Probar acceso directo permitido/denegado para todos los grupos de endpoints.
3. Completar recorrido visual en 360, 390, 768, 1024 y 1440 px.
4. Preparar cuatro recorridos de código para la defensa: autenticación, incidencia, préstamo y tarea.
5. Actualizar métricas, fechas y limitaciones en README, informe de pruebas y PDF.
6. Verificar colaboradores del repositorio privado y acceso docente antes del día de entrega.

### Prioridad media — mejoras posteriores o si queda margen seguro

1. Reducir manejadores y lógica global heredada, dividiendo los archivos de interfaz más grandes.
2. Ampliar entidades y objetos de transferencia tipados donde hoy se usan arreglos.
3. Agregar comprobaciones automáticas de autorización por endpoint y cobertura informada.
4. Mejorar la evidencia de accesibilidad con teclado y lector de pantalla.
5. Diseñar una futura migración normalizada en una rama separada, sin introducirla en la versión final sin tiempo de regresión.

## 6. Dictamen de preparación

**Estado actual: condicionado / no cerrar todavía la entrega final.**

El sistema presenta una base técnica sólida, pruebas locales repetibles y una mejora clara respecto de la evaluación inicial. No obstante, todavía no debe declararse “100 % conforme” porque cuatro requisitos explícitos permanecen abiertos: despliegue institucional, commits individuales, datos inmediatos para los tres perfiles y consolidación documental de la versión final.

Si se cierran esos puntos sin introducir regresiones, el puntaje estimado podría subir al rango de **88–92/100**. La normalización no estricta seguiría siendo el principal descuento técnico aceptado.

