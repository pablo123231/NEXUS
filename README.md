# NEXUS / SGRSI — Implementación de Rúbrica

Sistema de Gestión de Recursos y Servicios de Informática del ITI CEPT. La versión evaluable y mantenida se encuentra en `proyecto 3ra entrega`; las carpetas anteriores se conservan únicamente como historial de entregas.

## Estado actual

- Repositorio privado de trabajo: `jeaan8/Implementacion-de-Rubrica`.
- Backend PHP 8 con arquitectura por capas: adaptadores HTTP, aplicación, dominio e infraestructura.
- Contrato JSON uniforme: `status`, `message` y `data`.
- Autenticación con sesiones, CSRF, limitación de intentos y TOTP obligatorio para administradores.
- Instalador único de base de datos: `proyecto 3ra entrega/database.sql`.
- 129 comportamientos de caracterización, 18 operaciones PDO, auditoría estática de 22 pantallas y regresión HTTP autenticada.
- Interfaz bilingüe español/inglés con selector persistente, 371 claves equivalentes y control automático de UTF-8.
- Despliegue institucional preparado y pendiente de ejecución desde la LAN.

## Requisitos

- Apache 2.4, PHP 8.0 o superior y MySQL/MariaDB.
- Extensiones PHP: PDO MySQL, GD, mbstring y fileinfo.
- HTTPS para el despliegue institucional.

## Instalación local

1. Clonar este repositorio dentro del directorio público de XAMPP.
2. Crear una base vacía con codificación `utf8mb4`.
3. Importar únicamente `proyecto 3ra entrega/database.sql`.
4. Copiar `proyecto 3ra entrega/config.example.php` como `config.php` y completar la conexión.
5. Iniciar Apache y MySQL.
6. Abrir `proyecto 3ra entrega/login.html` desde la URL local configurada.

Los archivos de `database old` son históricos y no deben importarse después de `database.sql`.

## Estructura principal

```text
proyecto 3ra entrega/
  api/                 adaptadores HTTP y autorización
  src/Application     casos de uso
  src/Domain          contratos, estados y objetos de valor
  src/Infrastructure  repositorios PDO y adaptadores técnicos
  seguridad/          sesión, CSRF, TOTP y controles defensivos
  js/                 interfaz cliente
  database.sql        instalación completa
tests/                pruebas de caracterización e integración
docs/                 decisiones, inventario, despliegue y evidencia
```

## Documentación

- `docs/GUIA_IMPLEMENTACION_RUBRICA.md`: plan y puertas de calidad.
- `docs/RUBRICA_2_0_REEVALUACION.md`: calificación posterior al rework, brechas y plan de cierre.
- `docs/GUIA_PRIORIDAD_MEDIA.md`: internacionalización, UTF-8 y criterios de cierre de prioridad media.
- `docs/INVENTARIO_API_Y_MIGRACION.md`: endpoints, métodos, roles y estado POO.
- `docs/DECISIONES_TECNICAS.md`: decisiones de arquitectura y seguridad.
- `docs/PRUEBAS_AUTOMATIZADAS.md`: evidencia reproducible.
- `docs/modelo_datos/MODELO_RELACIONAL.md`: esquema vigente, integridad y deuda de normalización.
- `docs/modelo_datos/MER_actual.mmd`: fuente editable del MER actual.
- `docs/DESPLIEGUE_INSTITUCIONAL.md`: instalación y smoke test LAN.
- `docs/Casos_de_prueba_caja_blanca_template.docx`: 83 casos adaptados a la plantilla oficial.

## Equipo

- Desarrolladores: Jean Ortiz y Pablo Mecoll.
- Testers: Josefina Prieto y Thiago Rodriguez.
