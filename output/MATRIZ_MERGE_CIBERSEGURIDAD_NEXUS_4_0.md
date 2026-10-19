# Matriz de decisiones del merge NEXUS 4.0

| Área | Fuente de seguridad | Decisión en 4.0 | Motivo |
|---|---|---|---|
| Base funcional | Proyecto 3ra entrega | Conservar 3.0 | El ZIP deriva de una base anterior y omite módulos vigentes. |
| Sesión segura | `seguridad/bootstrap.php` | Integrar y adaptar | Agrega vencimiento, rotación, huella y versión de sesión. |
| Contrato JSON | Respuestas `{error: ...}` | Adaptar a `{status,message,data}` | Mantener el contrato uniforme ya implementado. |
| Fuerza bruta | Intentos por cuenta/IP | Integrar | Reduce ataques de credenciales y evita evasión por formato. |
| TOTP | Login y panel de cuenta | Integrar | Mitiga robo de contraseña y phishing. |
| Auditoría | Eventos en BD y archivo | Integrar | Permite detectar accesos y acciones de seguridad. |
| Recuperación | No presente en el ZIP | Conservar 3.0 y endurecer | Debe seguir funcionando por tickets e invalidar sesiones al cambiar la clave. |
| Roles y módulos nuevos | No presentes en el ZIP | Conservar 3.0 | Evita regresiones funcionales. |
| Fotos | Recodificación GD | Integrar y adaptar a sesión | Elimina cargas políglotas y evita modificar otra cuenta. |
| CSP sin scripts internos | Scripts externalizados del ZIP | Integración parcial documentada | La 3.0 tiene veinte páginas con scripts internos y funciones ausentes en el ZIP. |
| HTTPS y cabeceras | `.htaccess` | Integrar con localhost exceptuado | Endurece producción sin impedir pruebas XAMPP locales. |
| DDoS | Límites, modo ataque, Apache y Cloudflare | Integrar; scripts externos no ejecutados | Los controles de aplicación son verificables; firewall y Apache dependen del despliegue. |
| Respaldo e integridad | Scripts operativos | Incluir sin ejecutar | Requieren rutas, credenciales y destinos definidos por el administrador. |
| Base de datos | Migración de seguridad | Consolidar en `migration_4_0.sql` | Evita cambios de esquema en tiempo de ejecución y conserva datos. |
