# NEXUS / SGRSI

Sistema de Gestión de Recursos y Servicios de Informática del ITI CEPT.

Este repositorio tiene dos carpetas:

- `proyecto 1ra entrega`: el contenido original de la carpeta `PROYECTO` (primera entrega, sin cambios).
- `proyecto 2da entrega`: código actual (HTML/CSS/JS, API PHP, `database.sql` y `config.example.php`).

## Requisitos (2da entrega)

- XAMPP (Apache + PHP + MySQL/MariaDB)
- PHP 8.0 o superior
- MySQL o MariaDB

## Instalación de la 2da entrega

1. Clonar el repositorio:

   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/pablo123231/NEXUS.git NEXUS
   ```

2. Importar `proyecto 2da entrega/database.sql` en phpMyAdmin (o por consola).

3. En esa misma carpeta, copiar la configuración:

   ```bash
   cd NEXUS\proyecto 2da entrega
   copy config.example.php config.php
   ```

4. Iniciar Apache y MySQL en XAMPP.

5. Abrir `http://localhost/NEXUS/proyecto%202da%20entrega/`
