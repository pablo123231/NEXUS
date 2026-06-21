# SGRSI

## Descripción

SGRSI es un portal web de gestión de recursos y servicios de tecnología de la información. La interfaz reúne el inventario institucional, la atención de solicitudes, los préstamos de equipamiento, las incidencias técnicas y la organización del trabajo técnico-administrativo.

## Funcionalidad

- Página pública de presentación, registro e inicio de sesión.
- Gestión de usuarios, sesiones, perfiles y permisos según rol.
- Panel principal con acceso a los módulos habilitados para cada usuario.
- Inventario organizado por secciones, equipos, imágenes e historial de cambios.
- Registro y seguimiento de préstamos de recursos.
- Registro y seguimiento de solicitudes de servicio e incidencias técnicas.
- Planilla técnico-administrativa con tareas, estados y calendario.
- Edición de datos de cuenta y fotografía de perfil.
- Interfaz en español e inglés, con temas claro y oscuro.
- Persistencia local de los datos y preferencias de sesión en el navegador.

## Tecnologías utilizadas

- HTML5.
- CSS3.
- JavaScript sin frameworks.
- Web Storage API mediante `localStorage` y `sessionStorage`.
- Lucide Icons para la iconografía de la interfaz.
- Recursos gráficos en JPEG y SVG embebido.

## Estructura del proyecto

```text
.
├── README.md
└── PROYECTO/
    ├── css/
    │   └── global.css
    ├── images/
    │   └── header.jpeg
    ├── js/
    │   ├── app.js
    │   ├── componentes.js
    │   ├── datos.js
    │   └── traducciones.js
    ├── index.html
    ├── login.html
    ├── register.html
    ├── home.html
    ├── cuenta.html
    ├── inventario.html
    ├── equipos.html
    ├── equipo-detalle.html
    ├── secciones.html
    ├── seccion-detalle.html
    ├── imagenes.html
    ├── prestamos.html
    ├── solicitudes.html
    ├── soporte.html
    ├── historial.html
    ├── planilla-ta.html
    ├── vacios.html
    └── proyecto
```
