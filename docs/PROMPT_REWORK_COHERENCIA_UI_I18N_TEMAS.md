# Prompt guía para el rework de coherencia de interfaz, idioma y temas

## Mandato

Actuá como arquitecto frontend, especialista en internacionalización, accesibilidad y testing de NEXUS. Corregí de raíz —sin parches basados en frases visibles— la mezcla de idiomas, la aplicación inconsistente del tema, el selector vacío de traslado de equipos, la identificación innecesaria en el filtro de autores, la presentación desacorde de Seguridad de la cuenta y el doble borde de Registro. Trabajá sobre `proyecto 3ra entrega`, conservá los contratos y datos existentes cuando sea posible, y sincronizá la solución aprobada con NEXUS, Implementación de Rúbrica y `C:\xampp\htdocs\proyecto 3ra entrega`.

No consideres terminado el trabajo por corregir únicamente las pantallas mencionadas. Cada solución debe ser central, reutilizable y verificable en las 22 pantallas. Antes de una migración de base de datos o un cambio incompatible de API, detenerse y consultar. Para cambios internos compatibles, avanzar, documentar y probar.

## Resultado esperado

1. Ninguna pantalla puede mezclar español e inglés después de seleccionar un idioma.
2. Todo componente estático o dinámico debe traducirse a partir de una clave estable.
3. Claro y oscuro deben aplicarse de manera uniforme, incluida Cuenta, modales y controles nativos.
4. El traslado desde la lista de equipos debe mostrar destinos activos distintos de la ubicación actual.
5. El filtro Autor debe mostrar solamente nombres, pero filtrar por una identidad interna estable.
6. Seguridad de la cuenta debe integrarse visualmente con Cuenta.
7. Los campos de Registro deben mostrar un único borde y un único foco.

## Diagnóstico que debe preservarse

### Internacionalización

El sistema actual crea un índice inverso desde los textos de los diccionarios, busca claves comparando el contenido visible y recorre nodos para traducirlos. También contiene reemplazos manuales de frases variables. Este mecanismo falla cuando:

- un nodo se vuelve a procesar después de haber sido traducido;
- dos claves comparten una traducción;
- JavaScript inserta contenido luego del primer recorrido;
- el dato de dominio y su etiqueta visible usan el mismo texto;
- una función genera una frase que no coincide exactamente con el catálogo;
- se intenta volver de inglés a español sin conservar la clave original.

El Kanban evidencia el problema: sus estados se usan simultáneamente como valores de dominio, comparadores y etiquetas visibles. La cabecera se construye con el valor crudo, mientras otras partes son traducidas por el recorrido del DOM.

### Tema

Aunque el atributo `data-tema` y los tokens básicos existen, Cuenta contiene selectores `.account-redesign ...` con colores oscuros literales que se aplican incluso cuando `data-tema="claro"`. Esto convierte al tema en una combinación de estado global y excepciones locales contradictorias.

### Traslado desde la lista

El modal se crea e inicializa cuando el selector todavía contiene solo la opción inicial. Al abrirlo se actualiza el `<select>` nativo, pero el componente visual personalizado previamente generado puede conservar la lista anterior. Además, las secciones se capturan una sola vez al cargar la página y pueden quedar obsoletas.

### Autor

La API construye `autor` como `nombre (cedula)` y la interfaz utiliza esa misma cadena como etiqueta y valor del filtro. Presentación e identidad no están separadas.

### Registro

`.regla-diseno-069` aplica borde al contenedor `.auth-input-shell` y las reglas de `input/select` aplican otro borde al control hijo. El resultado es un borde doble y dos tratamientos de foco.

## Decisión arquitectónica de internacionalización

### Alternativas evaluadas

1. **Continuar ampliando el traductor por comparación de frases:** descartada. Aumenta el catálogo, pero conserva ambigüedad, reprocesamiento y dependencia del texto original.
2. **Mantener un motor propio, pero exclusivamente por claves:** viable y liviano, aunque obliga a implementar fallback, interpolación, plurales, namespaces y auditoría.
3. **Adoptar i18next local y fijado por versión:** seleccionada. Proporciona claves, fallback, interpolación, namespaces y resolución consistente. Debe almacenarse dentro del proyecto para no depender de Internet en la LAN.

La documentación de i18next recomienda claves no basadas en lenguaje natural, fallback explícito y recursos separados. No cargar `latest` ni depender de CDN.

### Modelo objetivo

- Guardar recursos en `locales/es/*.json` y `locales/en/*.json`.
- Separar namespaces: `common`, `navigation`, `tasks`, `inventory`, `account`, `auth`, `errors` y los módulos necesarios.
- Inicializar i18next una sola vez antes de renderizar la aplicación.
- Exponer `i18n.t(key, params)` como único punto de traducción.
- Para HTML estático usar `data-i18n="account.security.title"` y atributos específicos como `data-i18n-placeholder`.
- Para HTML dinámico llamar a `i18n.t()` dentro de cada función de renderizado.
- Prohibir el recorrido general que intenta reconocer frases y prohibir reemplazos `.replace()` para traducir mensajes.
- Escapar por separado los datos de usuario; una traducción nunca habilita HTML arbitrario.
- Al cambiar idioma: guardar preferencia, ejecutar `changeLanguage`, actualizar `lang` y volver a renderizar los componentes activos.
- Mantener español como fallback durante la migración, pero hacer fallar la auditoría si una clave no existe en inglés.

### Estados y datos de dominio

Los valores funcionales no deben traducirse. Definir códigos estables:

| Dominio | Código | Clave visible |
| --- | --- | --- |
| Tarea | `pending` | `tasks.status.pending` |
| Tarea | `in_progress` | `tasks.status.inProgress` |
| Tarea | `in_review` | `tasks.status.inReview` |
| Tarea | `completed` | `tasks.status.completed` |
| Prioridad | `low` | `tasks.priority.low` |
| Prioridad | `medium` | `tasks.priority.medium` |
| Prioridad | `high` | `tasks.priority.high` |
| Prioridad | `critical` | `tasks.priority.critical` |

Durante la transición, crear adaptadores en la frontera de la API para convertir los valores históricos (`En revision`, `Critica`, etc.) a códigos. El Kanban compara códigos y traduce únicamente al renderizar. Nunca debe comparar “Under review” con “En revision”.

### Contenido administrable

- No traducir nombres libres introducidos por usuarios.
- Servicios y categorías necesitan identidad estable y etiquetas localizadas para que una alta futura no requiera editar JavaScript.
- Esta migración de datos debe mantenerse como fase separada y requiere confirmación previa, según la guía evolutiva existente.

## Decisión arquitectónica de temas

### Alternativas evaluadas

1. **Agregar más selectores oscuros por pantalla:** descartada porque reproduce las contradicciones actuales.
2. **Duplicar una hoja clara y otra oscura:** descartada por divergencia y carga de mantenimiento.
3. **Un atributo raíz y tokens semánticos:** seleccionada. CSS Custom Properties constituyen el contrato visual y los componentes no conocen colores concretos.

### Modelo objetivo

- Unificar el atributo como `data-theme="light|dark|system"`; retirar gradualmente `data-tema`.
- Ejecutar un script mínimo en `<head>`, antes de la hoja CSS, que determine la preferencia guardada o `prefers-color-scheme` y coloque el atributo antes del primer pintado.
- Declarar `<meta name="color-scheme" content="light dark">` y actualizarlo con el tema efectivo.
- Definir tokens semánticos en `:root` y sobrescribir solo valores en `[data-theme="dark"]`.
- Utilizar `color-scheme: light` o `dark` para que inputs, selects y scrollbars nativos acompañen al tema.
- Eliminar colores literales y la regla que fuerza `.account-redesign .account-security-panel` a oscuro.
- Usar `matchMedia('(prefers-color-scheme: dark)')` solamente cuando la preferencia sea `system`.
- Escuchar el evento `storage` para sincronizar pestañas.
- Centralizar `setTheme`, `getThemePreference`, `getEffectiveTheme` y `subscribeTheme`; ninguna página vuelve a implementar esta lógica.

## Plan de ejecución

### Fase 0 — Protección y línea base

1. Confirmar que los repositorios no tienen cambios rastreados ajenos.
2. Ejecutar la suite completa y conservar resultados.
3. Inventariar textos visibles, claves, colores literales, atributos de tema y renderizadores dinámicos.
4. Crear pruebas que reproduzcan los seis defectos antes de modificar comportamiento.

### Fase 1 — Núcleo i18n

1. Incorporar una versión fijada de i18next como archivo local.
2. Crear recursos JSON ES/EN por namespace.
3. Implementar inicialización, fallback, interpolación y cambio de idioma.
4. Reemplazar el traductor inverso y el escaneo indiscriminado por claves explícitas.
5. Crear helpers seguros para texto, atributos y opciones de select.
6. Migrar primero componentes compartidos y después cada pantalla.
7. Hacer que el cambio de idioma provoque un render controlado, no una mutación oportunista.

### Fase 2 — Kanban y dominios

1. Introducir códigos estables para estados y prioridades.
2. Adaptar respuesta y escritura de tareas sin romper registros existentes.
3. Renderizar cabeceras, tarjetas, formularios, mensajes y selectores con claves.
4. Verificar creación, edición, drag and drop y reapertura en ambos idiomas.

### Fase 3 — Núcleo de temas

1. Crear el script de arranque temprano.
2. Migrar a `data-theme` y tokens semánticos.
3. Eliminar excepciones de Cuenta que fuerzan colores oscuros.
4. Auditar fondos, bordes, textos, iconos, foco, estados y modales.
5. Verificar cambio inmediato, persistencia, recarga y varias pestañas.

### Fase 4 — Traslado desde Equipos

1. Obtener las secciones activas cada vez que se abre el modal.
2. Excluir por ID la sección actual.
3. Crear una única función `setSelectOptions(select, options, selected)`.
4. Hacer que el selector personalizado exponga `refresh` o que se destruya y reconstruya después de modificar el nativo.
5. Deshabilitar Confirmar cuando no existe destino o no hay valor seleccionado.
6. Mostrar mensaje específico cuando solo existe una sección activa.
7. Probar que el traslado actualiza tabla, detalle e historial con autor.

### Fase 5 — Filtro Autor

1. Cambiar el contrato de historial a `autorId` y `autorNombre`.
2. Conservar el nombre capturado en el evento para no perder atribución histórica.
3. Construir opciones con `value=autorId` y texto `autorNombre`.
4. Filtrar por `autorId`, no por el nombre visible.
5. Si dos personas comparten nombre, mostrar dos opciones iguales visualmente tal como se pidió, manteniendo IDs diferentes internamente.
6. Mantener “Sistema” para registros anteriores sin autor.

### Fase 6 — Seguridad de la cuenta

1. Integrar el bloque dentro del mismo ancho, jerarquía y ritmo de Cuenta.
2. Usar encabezado, descripción, formulario y acciones consistentes con los paneles existentes.
3. Limitar el ancho de campos sin dejar grandes espacios vacíos.
4. Alinear botón y mensajes, mantener foco visible y ayudas de contraseña.
5. Usar únicamente tokens para claro y oscuro.
6. Comprobar escritorio, tablet y móvil.

### Fase 7 — Registro

1. Elegir al wrapper como único propietario del borde, radio y fondo.
2. Aplicar `border: 0`, fondo transparente y sombra nula a `input/select` internos.
3. Trasladar el foco a `.auth-input-shell:focus-within`.
4. Asegurar que el selector personalizado siga el mismo contrato.
5. Verificar normal, hover, foco, error, autofill, claro y oscuro.

### Fase 8 — Limpieza y compatibilidad

1. Retirar índices inversos, traducción por frases y reemplazos manuales cuando no tengan consumidores.
2. Eliminar tokens duplicados y colores literales reemplazados.
3. Versionar CSS/JS para invalidar caché.
4. No modificar contenido libre ni datos históricos sin necesidad.
5. Documentar todo adaptador temporal y su condición de retiro.

## Pruebas obligatorias

### Idioma

- Auditar igualdad exacta de claves ES/EN.
- Fallar ante texto propio visible sin clave en HTML y renderizadores JS.
- Probar cada pantalla primero en español, cambiar a inglés y volver a español sin recargar.
- Probar contenido creado después del cambio de idioma.
- Probar Kanban vacío y con tareas en los cuatro estados y cuatro prioridades.
- Confirmar que datos libres conservan su idioma original.

### Tema

- Capturas o aserciones visuales de las 22 pantallas en claro y oscuro.
- Cuenta debe cambiar realmente de fondo, texto, paneles, inputs y Seguridad.
- Recargar conserva la elección.
- `system` responde al sistema solo si no hay elección explícita.
- Formularios nativos y personalizados mantienen contraste WCAG AA y foco visible.

### Traslado

- Dos o más secciones: aparecen todos los destinos salvo el actual.
- Una sola sección: mensaje y botón deshabilitado.
- Sección creada después de cargar la página: aparece al abrir el modal.
- Traslado correcto: persistencia en DB, actualización visual e historial con autor.

### Autor

- La lista muestra nombres sin cédula.
- Seleccionar un nombre devuelve únicamente sus eventos.
- “Todos” restaura la lista.
- “Sistema” conserva eventos antiguos.
- Dos IDs con el mismo nombre no mezclan resultados.

### Diseño

- Seguridad de Cuenta consistente en ambas modalidades y tres anchos de viewport.
- Registro con un solo borde en input, select, foco, error y autofill.
- No introducir desplazamiento horizontal ni saltos de layout.

## Criterios de aceptación finales

- Cero mezcla de idiomas en las 22 pantallas y componentes cargados dinámicamente.
- Cero traducciones determinadas por comparar frases visibles.
- Cero componentes que fuercen un tema contrario al atributo raíz.
- Cero controles con borde duplicado.
- Traslado operativo desde lista y detalle.
- Filtro de autor visualmente limitado al nombre y funcionalmente basado en ID.
- Suite anterior intacta y nuevas pruebas aprobadas.
- Prueba manual completa en `C:\xampp\htdocs\proyecto 3ra entrega`.
- Cambios sincronizados y publicados solo después de superar la validación.

## Fuentes técnicas seleccionadas

- i18next, Getting started: https://www.i18next.com/overview/getting-started
- i18next, Fallback: https://www.i18next.com/principles/fallback
- i18next, Interpolation: https://www.i18next.com/translation-function/interpolation
- MDN, `color-scheme`: https://developer.mozilla.org/en-US/docs/Web/CSS/color-scheme
- MDN, `prefers-color-scheme`: https://developer.mozilla.org/en-US/docs/Web/CSS/@media/prefers-color-scheme

## Registro de implementación

Aplicado el 20/10/2026 sobre `proyecto 3ra entrega`:

- Se incorporó i18next 26.5.3 como recurso local versionado, sin dependencia de CDN ni de Internet durante el uso en la LAN.
- El núcleo mantiene compatibilidad temporal con `data-clave`, pero toda función nueva puede traducir por clave estable mediante `tClave(clave, parametros)`.
- Los estados funcionales del Kanban permanecen en español como datos de dominio y su presentación usa un mapa explícito de claves. Se agregaron `en_curso` y `completado` en ambos idiomas.
- El tema de Cuenta dejó de imponer colores oscuros literales y ahora consume los tokens semánticos globales. Sin preferencia guardada se respeta `prefers-color-scheme`; una selección manual queda persistida.
- El traslado desde la lista vuelve a consultar las secciones al abrir el modal, excluye la actual, sincroniza el selector visual y deshabilita la confirmación si no existe un destino válido.
- El historial entrega `autor` como nombre visible y `autorId` como identidad de filtrado. La cédula ya no se presenta en el selector.
- Seguridad de la cuenta se reorganizó como bloque adaptable y Registro conserva un único borde por campo.
- Se amplió la auditoría para exigir el motor local en todas las pantallas y los cuatro estados bilingües del Kanban.
