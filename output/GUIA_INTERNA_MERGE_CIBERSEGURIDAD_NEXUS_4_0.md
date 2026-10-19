# Guía interna de merge de ciberseguridad — NEXUS 4.0

## 1. Objetivo

Integrar en una nueva carpeta `proyecto 2da entrega 4.0` las mejoras de ciberseguridad incluidas en `NEXUS-3ra-entrega.zip`, tomando como base funcional exclusiva a `proyecto 2da entrega 3.0`. La versión 3.0 debe permanecer intacta y la 4.0 debe conservar su alcance, sus contratos de API y su comportamiento visible, salvo cambios indispensables para incorporar controles de seguridad.

## 2. Principios de trabajo

1. No sobrescribir la versión 3.0.
2. No copiar en bloque la tercera entrega sobre la base actual.
3. Revisar cada cambio de seguridad antes de integrarlo.
4. Mantener el contrato JSON `{status, message, data}` y los métodos HTTP implementados en 3.0.
5. Mantener roles, permisos, CSRF, sesiones y flujos funcionales ya verificados.
6. Aplicar el mínimo cambio capaz de incorporar cada mejora.
7. No ejecutar scripts de firewall, respaldo, migración o ataque sin revisar previamente su alcance y sus efectos.
8. Toda modificación de base de datos debe quedar documentada en una migración idempotente.
9. No incluir secretos, credenciales locales, respaldos ni archivos de configuración sensibles en Git.
10. No publicar la 4.0 hasta completar las pruebas de regresión y seguridad.

## 3. Fases del merge

### Fase A — Inventario y análisis

- Extraer el ZIP en un directorio temporal controlado.
- Identificar la carpeta de la tercera entrega y generar un inventario de archivos.
- Leer la documentación de seguridad y clasificar los controles propuestos.
- Comparar por separado configuración, autenticación, sesiones, APIs, JavaScript, base de datos y operaciones.
- Detectar archivos idénticos, archivos nuevos y archivos con conflictos semánticos.
- Registrar para cada mejora: propósito, riesgo mitigado, dependencias, impacto funcional y decisión de integración.

### Fase B — Diseño de integración

- Definir qué controles se incorporan sin cambios.
- Definir qué controles deben adaptarse a la arquitectura 3.0.
- Rechazar o aislar controles inseguros, redundantes, no portables o ajenos al alcance de la aplicación.
- Diseñar una única inicialización de seguridad compatible con todos los endpoints.
- Consolidar las tablas y columnas nuevas en una migración repetible.
- Definir degradación segura cuando una dependencia opcional no esté configurada.

### Fase C — Construcción de 4.0

- Copiar `proyecto 2da entrega 3.0` a `proyecto 2da entrega 4.0`.
- Integrar primero la infraestructura común de seguridad.
- Integrar después autenticación, recuperación de cuenta y segundo factor.
- Adaptar endpoints y páginas sin romper los contratos existentes.
- Incorporar controles de cabeceras, sesiones, validación, auditoría, carga de archivos y limitación de solicitudes cuando correspondan.
- Mantener scripts administrativos peligrosos fuera del acceso web o protegerlos de manera explícita.
- Añadir documentación de instalación y migración específica de la 4.0.

### Fase D — Verificación estática

- Validar sintaxis de todos los archivos PHP y JavaScript modificados.
- Buscar credenciales, secretos y rutas locales accidentales.
- Buscar SQL construido con entradas no parametrizadas.
- Buscar salidas sin codificación, sesiones iniciadas de forma inconsistente y endpoints sin control de rol o CSRF.
- Verificar que ningún script operativo sea accesible públicamente sin autorización.

### Fase E — Pruebas funcionales y de seguridad

- Instalar o actualizar la base local mediante la migración 4.0.
- Probar login correcto, incorrecto, cierre de sesión y bloqueo o limitación de intentos.
- Probar sesiones, permisos y accesos por rol.
- Probar recuperación de contraseña por ticket y su resolución/rechazo.
- Probar segundo factor si forma parte efectiva del merge, incluyendo activación, validación, recuperación y desactivación.
- Repetir CRUD y flujos críticos de ubicaciones, equipos, incidencias, tareas, servicios y préstamos.
- Verificar CSRF, métodos HTTP no permitidos, respuestas JSON y códigos HTTP.
- Verificar cabeceras de seguridad, cookies y protección de archivos sensibles.
- Confirmar que las páginas principales cargan y que no aparecen errores de JavaScript o PHP.

### Fase F — Criterios de aceptación

La versión 4.0 se considera aprobada únicamente si:

- conserva las funciones verificadas en 3.0;
- incorpora controles de seguridad comprobables;
- no expone secretos ni utilidades administrativas;
- mantiene respuestas JSON consistentes;
- no presenta errores de sintaxis ni fallos HTTP inesperados;
- las migraciones pueden ejecutarse más de una vez sin dañar datos;
- la carpeta 3.0 permanece sin cambios.

### Fase G — Publicación

- Revisar el diff final y excluir archivos temporales o locales.
- Añadir a Git únicamente `proyecto 2da entrega 4.0` y la documentación acordada que corresponda.
- Crear un commit descriptivo.
- Subir la rama actual al remoto configurado.
- Informar el resultado de pruebas, las decisiones de merge, las limitaciones conocidas y el identificador del commit.

## 4. Manejo de conflictos y ambigüedades

- Si una mejora contradice el comportamiento solicitado por el usuario, prevalece el comportamiento solicitado.
- Si existen dos implementaciones del mismo control, se conserva la que resulte compatible con la arquitectura 3.0 y reduzca más riesgos verificables.
- Si una decisión cambia funciones visibles, datos existentes o reglas de negocio, se detiene esa parte y se consulta al usuario.
- Si una mejora requiere configuración externa no disponible, se integra de forma documentada y con un modo seguro que no impida las pruebas locales.
- Si un script puede alterar el sistema operativo, la red o datos fuera de la aplicación, no se ejecuta como parte del merge.

## 5. Evidencias requeridas

- Matriz de comparación y decisiones de integración.
- Resultado de validación de sintaxis.
- Resultado de pruebas HTTP y funcionales.
- Resultado de controles de seguridad relevantes.
- Diff final revisado.
- Commit y confirmación del push.
