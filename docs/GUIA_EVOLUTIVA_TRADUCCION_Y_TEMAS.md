# Guía evolutiva de traducción y temas de NEXUS

## Propósito

Esta guía funciona como prompt operativo y criterio de aceptación para evolucionar la internacionalización y los temas visuales de NEXUS sin depender de reemplazos manuales de texto. Debe aplicarse a toda función nueva y a toda modificación de interfaz.

## Prompt rector reutilizable

> Actuá como responsable de arquitectura frontend, accesibilidad e internacionalización de NEXUS. Antes de modificar código, inventariá textos, catálogos dinámicos, mensajes del servidor, estados, formatos regionales y colores afectados. Separá estrictamente contenido, idioma y presentación. No uses el texto visible como identificador, no traduzcas contenido escrito libremente por usuarios y no dupliques reglas por idioma o tema. Implementá claves estables de traducción, parámetros nombrados, fallback verificable y tokens semánticos de diseño. Los catálogos administrables deben conservar un código estable y ofrecer etiquetas localizadas con fallback; agregar un servicio o categoría no debe exigir editar JavaScript. Los componentes deben consumir tokens, no colores literales. Conservá compatibilidad durante la transición, documentá decisiones y migraciones, y verificá español, inglés, tema claro, tema oscuro, teclado, contraste, persistencia, contenido dinámico y ausencia de claves sin resolver. Si una decisión cambia datos existentes, contratos públicos o significado funcional, detenete y solicitá confirmación antes de migrar.

## Principios obligatorios

1. Una clave técnica estable identifica cada mensaje; el texto visible nunca es la clave.
2. El idioma se resuelve en una única capa y se conserva como preferencia del usuario.
3. El tema se resuelve con variables semánticas; ningún módulo mantiene una copia clara y otra oscura.
4. Los datos creados por usuarios se muestran tal como fueron escritos.
5. Los catálogos administrables separan identidad y presentación localizada.
6. Español es el idioma base y fallback; inglés debe tener cobertura equivalente.
7. UTF-8 (`utf8mb4` en base de datos) es obligatorio para conservar ñ, tildes y símbolos.
8. Un cambio incompleto no se oculta con traducción automática silenciosa.

## Arquitectura objetivo de traducción

### Textos propios de la interfaz

- Usar claves jerárquicas y estables, por ejemplo `tasks.form.priority`, `loans.empty.availableEquipment` y `common.actions.save`.
- Exponer una función única `i18n.t(clave, parametros)` y atributos explícitos como `data-i18n`.
- Soportar interpolación nombrada: `i18n.t('tasks.assignedTo', { name })`.
- Mantener archivos de catálogo separados por idioma con exactamente el mismo conjunto de claves.
- Registrar en desarrollo claves faltantes y mostrar el fallback español en producción.
- Traducir títulos, etiquetas, ayudas, placeholders, validaciones, avisos, estados y `aria-label`.
- Formatear fechas, números y horas con `Intl`, nunca concatenando formatos regionales manualmente.

### Datos dinámicos y catálogos administrables

Los nombres libres —título de una tarea, comentario o descripción— no se traducen. Servicios, categorías y otros catálogos sí requieren un modelo administrable:

- Identificador interno inmutable: `id`.
- Código estable y único: por ejemplo `support_network`.
- Etiqueta en español obligatoria: `nombre_es`.
- Etiqueta en inglés opcional durante la transición: `nombre_en`.
- Fallback: idioma solicitado, luego español, luego código.
- Estado de habilitación separado de las etiquetas.

La API objetivo debe devolver identidad y etiquetas, por ejemplo:

```json
{
  "id": 12,
  "code": "support_network",
  "labels": { "es": "Soporte de red", "en": "Network support" },
  "enabled": true
}
```

El cliente selecciona la etiqueta correspondiente al idioma actual. Por ello, crear un servicio nuevo desde Administración lo vuelve visible inmediatamente sin editar un diccionario JavaScript. El formulario administrativo debe permitir completar ambos idiomas y señalar si se está usando fallback.

### Mensajes del backend

- El backend debe responder códigos de error estables, no obligar al frontend a reconocer frases.
- Contrato objetivo: `status`, `messageKey`, `data` y parámetros opcionales.
- Durante la transición puede conservarse `message` para clientes antiguos.
- Las reglas de negocio permanecen en el servidor; el frontend solo localiza su presentación.

## Arquitectura objetivo de temas

### Tokens semánticos

Definir una sola colección de nombres funcionales: `--color-bg-page`, `--color-bg-surface`, `--color-text-primary`, `--color-text-muted`, `--color-border`, `--color-action-primary`, `--color-danger`, `--color-success`, `--shadow-dialog` y equivalentes. Cada tema asigna valores a esos tokens.

- Los componentes consumen tokens semánticos.
- No se permiten colores literales en componentes salvo gráficos o datos cuyo color sea parte del dominio.
- La selección se aplica en el elemento raíz con `data-theme="light|dark"`.
- Si no existe preferencia guardada, se respeta `prefers-color-scheme`.
- Los cambios del sistema operativo se escuchan solo mientras el usuario no haya elegido un tema explícito.
- La preferencia se guarda y se aplica antes del primer pintado para evitar destellos.
- Modales, selects, estados de foco, mensajes y contenido dinámico deben heredar los mismos tokens.

### Extensibilidad

Agregar un tema futuro debe requerir únicamente un nuevo mapa de tokens y registrarlo en la lista permitida. No debe exigir modificar cada pantalla. Todo token nuevo debe tener valor en todos los temas y fallback en `:root`.

## Plan de migración de bajo riesgo

### Fase 1 — Base verificable

1. Inventariar textos y colores literales con herramientas automáticas.
2. Introducir el servicio `i18n` con claves explícitas y fallback.
3. Introducir tokens semánticos y mapear los temas actuales.
4. Mantener temporalmente adaptadores para el sistema antiguo.
5. Incorporar pruebas de cobertura de claves y tokens.

### Fase 2 — Componentes y contratos

1. Migrar primero navegación, formularios compartidos, diálogos y mensajes.
2. Sustituir reconocimiento por texto por `messageKey` o códigos estables.
3. Eliminar progresivamente el escaneo automático y los reemplazos de frases.
4. Probar cambio de idioma y tema sin recarga y también después de reiniciar sesión.

### Fase 3 — Catálogos dinámicos

Esta fase modifica estructura y datos, por lo que requiere aprobación previa.

1. Agregar código y etiquetas localizadas a servicios, categorías tecnológicas y categorías de préstamo.
2. Migrar registros existentes conservando identificadores y relaciones.
3. Adaptar Administración y las API.
4. Aplicar fallback a registros cuya etiqueta inglesa aún no esté completa.
5. Prohibir que el frontend mantenga copias locales de esos catálogos.

### Fase 4 — Retiro de compatibilidad

1. Confirmar que no quedan consumidores del traductor basado en frases ni propiedades antiguas.
2. Retirar adaptadores y claves obsoletas.
3. Generar un informe automático de cobertura por idioma y tema.

## Regla para toda función nueva

Antes de considerar terminada una función, quien la implemente debe responder:

- ¿Todos los textos propios usan claves explícitas?
- ¿El contenido dinámico proviene de la API y tiene fallback definido?
- ¿Se preserva literalmente el contenido libre del usuario?
- ¿Los mensajes de error tienen código estable?
- ¿No se agregó ningún color de interfaz fuera de los tokens?
- ¿Funciona en español e inglés, claro y oscuro, sin recargar?
- ¿Se verificaron foco visible, contraste y lectura con teclado?
- ¿Las pruebas detectan una clave o token faltante?

## Pruebas mínimas automatizadas

1. Igualdad de claves entre catálogos ES/EN.
2. Ausencia de claves renderizadas como texto visible.
3. Interpolación segura de parámetros y escape de HTML.
4. Fallback ES para traducción faltante.
5. Elección correcta de etiquetas en catálogos dinámicos.
6. Persistencia de idioma y tema.
7. Cobertura de tokens en claro y oscuro.
8. Contraste WCAG AA para texto y controles esenciales.
9. Render de modales, tablas, selectores y alertas en las cuatro combinaciones ES/claro, ES/oscuro, EN/claro y EN/oscuro.

## Decisiones aplicadas en esta iteración

- `dificultad` fue reemplazada por `prioridad` en el contrato y la interfaz de tareas.
- Los valores válidos son `Baja`, `Media`, `Alta` y `Critica`; la presentación localizada muestra `Crítica` en español y `Critical` en inglés.
- La columna física `dificultad_tarea` se conserva temporalmente para evitar una migración destructiva; queda encapsulada por repositorio y no forma parte del contrato público.
- La API acepta transitoriamente la propiedad antigua `dificultad` al escribir, pero solo publica `prioridad` al leer.
- La migración bilingüe de catálogos dinámicos se deja diseñada, no ejecutada, porque altera datos y formularios administrativos y requiere aprobación explícita.

## Criterio de finalización de la arquitectura objetivo

El trabajo estará completo cuando agregar un texto de interfaz falle en pruebas si no tiene traducción; agregar un servicio o categoría desde Administración permita cargar sus etiquetas sin editar código; y agregar un tema solo requiera definir su mapa completo de tokens. No debe quedar lógica que traduzca comparando frases visibles ni estilos de componentes dependientes de un color literal.
