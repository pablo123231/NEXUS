# NEXUS / SGRSI

Sistema de Gestión de Recursos y Servicios de Informática del ITI CEPT. Permite pedir recursos, coordinar servicios, atender soporte, mantener inventario y organizar el trabajo técnico-administrativo.

## Requisitos

- XAMPP (Apache + PHP + MySQL/MariaDB)
- PHP 8.0 o superior
- MySQL o MariaDB

## Instalación

1. Clonar el repositorio dentro de `htdocs` de XAMPP:

   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/pablo123231/NEXUS.git PROYECTO
   ```

2. Importar `database.sql` en phpMyAdmin (o por consola). Ese archivo crea la base `proyecto` y sus tablas.

3. Copiar la configuración de ejemplo y ajustarla:

   ```bash
   copy config.example.php config.php
   ```

   En `config.php` revisá host, puerto, nombre de la base, usuario y contraseña. En XAMPP el puerto suele ser `3306`.

4. Iniciar Apache y MySQL en el panel de XAMPP.

5. Abrir `http://localhost/PROYECTO/` en el navegador.
