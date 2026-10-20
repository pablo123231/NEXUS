# Guia de implementacion de la rubrica final

## Objetivo

Adecuar NEXUS a la rubrica ITI FullStack 2026 sin cambiar su alcance funcional ni su interfaz salvo cuando una correccion sea necesaria. La version activa es `proyecto 3ra entrega`. Las versiones anteriores permanecen como evidencia historica y no reciben cambios.

## Estado de respaldo

- Repositorio de trabajo privado: `jeaan8/Implementacion-de-Rubrica`.
- Commit inicial: `7ce45fad615c462190461d4ca3baaced98a1c065`.
- Etiqueta inmutable: `respaldo-inicial-2026-10-19`.
- El despliegue institucional queda preparado pero pendiente de acceso desde la LAN.

## Principios de implementacion

1. Conservar contratos HTTP, rutas, nombres JSON y comportamiento visible mientras se migra el interior.
2. Separar presentacion, aplicacion, dominio e infraestructura.
3. Evitar clases decorativas: cada clase debe participar en casos de uso reales.
4. Aplicar permisos y validaciones en servidor aunque el frontend oculte acciones.
5. Migrar por modulo con pruebas de caracterizacion antes y regresion despues.
6. Mantener `database.sql` como instalador unico.
7. Documentar decisiones, alternativas descartadas y evidencia de prueba.

## Arquitectura objetivo

```text
proyecto 3ra entrega/
  api/                 Adaptadores HTTP delgados
  src/
    Application/       Casos de uso y servicios de aplicacion
    Domain/
      Entity/          Objetos con identidad y comportamiento
      ValueObject/     Estados, roles, fechas e identificadores validos
      Repository/      Interfaces de persistencia
      Service/         Reglas que abarcan varias entidades
    Infrastructure/
      Persistence/     Repositorios PDO
      Security/        Adaptadores de sesion, CSRF y hashes
    Shared/            Resultado, errores y utilidades transversales
  bootstrap/           Construccion de dependencias
  tests/               Pruebas de caracterizacion, dominio e integracion
  docs/                Arquitectura, decisiones, despliegue y pruebas
```

Flujo obligatorio: `HTML/JS -> endpoint -> caso de uso -> dominio -> repositorio -> PDO`.

## Fases

### Fase 0 Inventario y caracterizacion

- Catalogar endpoints, metodos, roles, entradas, salidas y tablas.
- Registrar respuestas actuales de casos validos e invalidos.
- Identificar reglas duplicadas entre frontend y backend.
- Crear matriz de trazabilidad entre las 83 funciones, casos de uso y pruebas.

### Fase 1 Nucleo comun

- Incorporar autocarga PSR-4 sin exigir Composer en produccion.
- Crear excepciones de dominio y respuesta HTTP uniforme.
- Crear conexion PDO inyectable y transacciones reutilizables.
- Crear objetos `Cedula`, `Rol`, `EstadoCuenta` y `ResultadoOperacion`.

### Fase 2 Usuarios y autenticacion

- Migrar usuarios, registro, cambio de rol y recuperacion.
- Mantener sesiones, CSRF, TOTP, fuerza bruta y cierre de sesiones.
- Centralizar autorizacion en politicas comprobables.

### Fase 3 Operaciones centrales

- Migrar prestamos, servicios e incidencias.
- Modelar transiciones de estado y rechazar transiciones ilegales.
- Usar transacciones para disponibilidad, resolucion y bajas.

### Fase 4 Inventario y organizacion

- Migrar equipos, ubicaciones, historial, tareas y planilla TA.
- Mantener bajas logicas y trazabilidad.

### Fase 5 Frontend y CSP

- Extraer scripts internos a modulos versionados.
- Eliminar `unsafe-inline` cuando no queden dependencias.
- Unificar idioma, mensajes y validaciones accesibles.

### Fase 6 Base de datos

- Revisar tercera forma normal, claves, indices y restricciones.
- Agregar datos de prueba para administrador, tecnico y solicitante.
- Actualizar MER y modelo relacional contra el SQL real.

### Fase 7 Documentacion y pruebas

- Adaptar los casos de caja blanca al template entregado.
- Asignar Jean Ortiz y Pablo Mecoll como desarrolladores.
- Asignar Josefina Prieto y Thiago Rodriguez como testers.
- Generar un unico PDF final con arquitectura, POO, MER, instalacion, acceso, pruebas y limitaciones.
- Registrar resultado esperado y obtenido de la version final.

### Fase 8 Despliegue y cierre

- Preparar lista de configuracion del servidor institucional.
- Ejecutar despliegue y smoke test cuando exista acceso LAN.
- Crear etiqueta final y comprobar igualdad entre codigo, SQL, documentacion y servidor.

## Puertas de calidad

Cada fase requiere: sintaxis PHP y JS correcta, importacion limpia del SQL cuando aplique, pruebas de permisos, ausencia de regresiones visuales, documentacion actualizada y commit independiente. Ninguna fase se considera completa solo por compilar.

## Decisiones que requieren consulta

Se detendra el trabajo antes de: cambiar un contrato publico, eliminar una funcion, alterar datos reales, modificar el aspecto de un flujo sin necesidad funcional, cambiar reglas de roles, reducir controles de seguridad o adoptar una dependencia obligatoria nueva.
