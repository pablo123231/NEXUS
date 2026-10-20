# Evidencia de pruebas automatizadas

Fecha de ejecución: 2026-10-20.

## Alcance

- 129 comportamientos de aplicación, dominio e interfaz ejecutados con repositorios en memoria o comprobaciones de caracterización, incluidos rechazos de entradas inválidas y las correcciones posteriores al rework.
- Importación limpia de `database.sql` en MariaDB 10.4 aislado.
- 27 tablas creadas por el instalador único.
- 18 operaciones integradas adicionales ejecutadas mediante PDO real.
- Limpieza posterior comprobada: no quedaron registros de prueba marcados con `RUBRICA-*`.
- Validación sintáctica de todos los archivos PHP de `api/` y `src/`.
- Regresión HTTP autenticada sobre servidor PHP aislado y MariaDB de prueba.
- Inicio de sesión administrativo verificado con contraseña y segundo factor TOTP.
- Contrato JSON `status`, `message` y `data` verificado en los endpoints de sesión, incidencias, préstamos, servicios, equipos, ubicaciones, tareas, planillas TA, usuarios, solicitudes de registro, cambios de rol, recuperaciones, reportes, catálogos, configuración, historial y seguridad de cuenta.
- Ciclos HTTP `POST`/`DELETE` ejecutados en incidencias, servicios, préstamos y tareas con token CSRF válido.
- Restauración final de `rubric_test` desde `database.sql`: 27 tablas, 3 usuarios base y cero registros `RUBRICA-*`.
- Paridad de 371 claves español/inglés, 147 usos declarados y 21 pantallas funcionales con núcleo de idioma.
- Persistencia del selector ES/EN comprobada en inicio, acceso, registro y recuperación.
- Codificación UTF-8 comprobada en HTML, JSON, conexión PDO y `database.sql`, incluidos `ñ` y caracteres acentuados.

## Suites

| Archivo | Cobertura principal |
|---|---|
| `administracion_characterization.php` | Reportes, catálogos, configuración e historial |
| `correcciones_funcionales_characterization.php` | Regresión de las correcciones funcionales y de interfaz posteriores al rework |
| `incidencia_characterization.php` | Ciclo de incidencias y comentarios |
| `inventario_characterization.php` | Equipos, ubicaciones, historial y bajas lógicas |
| `operaciones_centrales_characterization.php` | Préstamos y servicios |
| `perfil_characterization.php` | Sustitución segura de fotografía |
| `recuperacion_characterization.php` | Recuperación y credencial temporal |
| `seguridad_cuenta_characterization.php` | TOTP, contraseñas y sesiones |
| `solicitudes_identidad_characterization.php` | Registro y cambios de rol |
| `trabajo_characterization.php` | Kanban y planillas TA |
| `usuario_characterization.php` | Administración de usuarios |
| `integration_database.php` | Esquema real, repositorios PDO y operaciones cruzadas |
| `schema_documentation.php` | Correspondencia de 27 tablas y 31 claves foráneas con el modelo documentado |
| `frontend_static_audit.php` | Viewport, título, región principal y texto alternativo de 22 pantallas |
| `i18n_audit.js` | Paridad ES/EN, cobertura de claves, UTF-8, selectores públicos y caracteres españoles |

## Resultado

Todas las suites de dominio, aplicación, persistencia y HTTP finalizaron correctamente en el despliegue aislado. Se verificaron 129 comportamientos de caracterización, 18 operaciones PDO, 27 tablas documentadas, 31 claves foráneas y los contratos de consulta de los 17 adaptadores HTTP principales.

La comprobación visual definitiva en el servidor institucional y el ensayo desde otros equipos de la LAN continúan pendientes porque requieren acceso a esa red. Esta limitación no afecta la evidencia local, pero impide declarar completado el despliegue institucional.
