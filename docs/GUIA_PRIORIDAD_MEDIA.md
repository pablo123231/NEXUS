# Guia interna de implementacion - prioridad media

## Objetivo

Completar las mejoras de mantenibilidad y experiencia que no requieren cambiar el modelo de negocio. La prioridad especial de esta etapa es que toda pantalla funcional pueda utilizarse en español o ingles sin perder textos, accesibilidad ni caracteres UTF-8 como `ñ`, tildes, `¿` y `¡`.

## Alcance

1. Consolidar el mecanismo de internacionalizacion existente y evitar traducciones parciales.
2. Ofrecer cambio de idioma en pantallas publicas y privadas.
3. Traducir textos estaticos, contenido generado y errores recibidos desde la API.
4. Mantener `lang`, `data-idioma` y estados accesibles sincronizados.
5. Extraer codigo inline cuando sea compatible con la politica CSP prevista.
6. Automatizar controles de paridad ES/EN, codificacion UTF-8 y ausencia de caracteres corruptos.
7. Actualizar evidencia, decisiones y documentacion sin alterar reglas funcionales.

## Estrategia

- El idioma predeterminado es español y se conserva en `localStorage` bajo la clave `idioma`.
- Solo se admiten `es` y `en`; cualquier otro valor vuelve a español.
- Las claves son estables y no contienen caracteres dependientes de una codificacion concreta.
- Los valores de ambos idiomas deben ser no vacios y tener paridad uno a uno.
- Los nombres propios, identificadores, correos, datos escritos por usuarios y codigos no se traducen.
- Los valores internos enviados al servidor permanecen estables; solo cambia su presentacion.
- Todos los archivos fuente se mantienen en UTF-8 y la base continua en `utf8mb4`.

## Fases

### A. Auditoria automatizada

- Inventariar claves declaradas y utilizadas.
- Detectar valores vacios, claves ausentes y texto corrupto.
- Verificar que las pantallas funcionales carguen el nucleo de idioma.
- Comprobar el cambio reversible español -> ingles -> español.

### B. Cobertura publica

- Agregar selector visible en inicio, acceso, registro y recuperacion.
- Incorporar recuperacion de contraseña al nucleo comun de interfaz.
- Sincronizar el atributo `lang` y conservar la seleccion entre paginas.

### C. Contenido dinamico

- Traducir mensajes de red y errores conocidos mediante el mismo diccionario.
- Traducir los resultados generados despues de una llamada asincrona.
- Mantener sin traduccion los datos ingresados o devueltos por usuarios.

### D. Calidad del español

- Corregir `ñ`, tildes y signos necesarios en los textos visibles.
- Evitar conversiones automaticas que puedan modificar nombres o datos reales.
- Verificar especificamente `Contraseña`, `Español`, `Mañana`, `sección`, `técnico` y `sesión`.

### E. Regresion y publicacion

- Ejecutar sintaxis PHP/JavaScript, pruebas funcionales y auditoria i18n.
- Probar navegacion publica en ambos idiomas y al menos un flujo autenticado por perfil.
- Documentar resultados, pendientes manuales y publicar un commit trazable.

## Puertas de calidad

- Cero claves declaradas solo en un idioma.
- Cero traducciones vacias usadas por la interfaz.
- Cero secuencias de texto corrupto (`Ã`, `Â` o `�`) en fuentes funcionales.
- Selector operativo y persistente en recorridos publicos y privados.
- La interfaz vuelve correctamente al español despues de mostrarse en ingles.
- Ningun cambio de idioma altera valores enviados a la API o datos persistidos.

## Limites

No se traducen datos de negocio escritos por personas, nombres de equipos, lugares, comentarios ni contenido historico. No se cambian endpoints, roles, estados internos o columnas. Cualquier cambio de esos elementos requiere consulta previa.

## Resultado implementado

- Los diccionarios quedaron equilibrados en 371 claves no vacias para español e ingles.
- Las 21 pantallas funcionales cargan el nucleo comun; `test-api.html` permanece como utilidad tecnica excluida.
- Inicio, acceso, registro y recuperacion ofrecen selector ES/EN aun sin sesion iniciada; las pantallas privadas conservan el selector del menu de cuenta.
- Recuperacion de contraseña dejo de usar codigo inline y traduce tambien los resultados creados despues de consultar la API.
- Los errores de transporte y los errores frecuentes del servidor pasan por el traductor antes de mostrarse.
- Se corrigieron textos españoles visibles y se comprobaron especificamente `Español`, `Contraseña`, `Mañana`, `Sección`, `técnico`, `sesión` y `cédula`.
- La auditoria valida paridad, usos, archivos UTF-8, `utf8mb4` en SQL/PDO y JSON Unicode.
- El recorrido visual ES -> EN -> ES se comprobo en recuperacion, acceso, portada y registro.

La traduccion deliberadamente no modifica valores persistidos ni contenido libre escrito por las personas. La prueba final del despliegue institucional continua pendiente de acceso LAN.
