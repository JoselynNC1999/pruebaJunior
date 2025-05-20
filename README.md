# Sistema de Fichaje - API REST Laravel

Sistema backend para gestionar y registrar la asistencia de empleados, desarrollado con Laravel y MySQL.

## Requisitos del Sistema

- PHP 8.0 o superior
- Composer
- MySQL
- XAMPP (opcional, para entorno de desarrollo local)

## Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/JoselynNC1999/pruebaJunior.git 
   cd src

2. **Instalar dependencias**
    composer install
    
3. **Crear archivo de configuración de base de datos (env)**

    cp .env.example .env
    php artisan key:generate

4. **Configurar base de datos en archivo .env**
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sistemafichaje
    DB_USERNAME=root
    DB_PASSWORD=

5. **Crear la base de datos: Usando phpMyAdmin**
    Abrir phpMyAdmin (http://localhost/phpmyadmin)
    Crear una nueva base de datos llamada "sistemafichaje"
    Importar la base de datos "sistemafichaje.sql"
    
6. **Iniciar el servidor de desarrollo**
    php artisan serve

7. **Estructura del Proyecto**
    src/
    ├── app/
    │   ├── Http/
    │   │   ├── Controllers/
    │   │   │   └── AsistenciaController.php    # Controlador para gestionar la asistencia
    │   ├── Models/
    │   │   ├── Empleado.php                    # Modelo de empleados
    │   │   └── RegistroAsistencia.php          # Modelo de registros de asistencia
    ├── database/
    │   ├── migrations/                         # Migraciones de la base de datos
    │   └── seeders/                            # Seeders de empleados
    │   ├── scripts/
    │   │   └── sistemafichaje.sql             # Script SQL para crear la BD y tablas
    ├── routes/
    │   └── api.php                             # Definición de rutas API
    └── README.md                               # Este archivo

8. **API Endpoints**
    1. **Registrar Entrada/Salida**
        POST /api/asistencia/fichar
    2. **Consultar Historial**
        GET /api/asistencia/historial/{empleadoId}
    3. **Consultar Resumen Mensual**
        GET /api/asistencia/resumen/{empleadoId}
        Parámetros requeridos (Query):

        año: Año a consultar (formato YYYY)
        mes: Mes a consultar (1-12)

## Justificación Técnica

1. **¿Por qué usé Laravel?**

Elegí Laravel porque ya lo estoy utilizando durante el ciclo superior de daw y me parece bastante completo y fácil de entender. Para este proyecto en particular, me ayudó mucho con:

    - La creación de modelos y controladores
    - La gestión de bases de datos
    - La creación de rutas y API endpoints
    - La autenticación y autorización de usuarios
    - La gestión de errores y excepciones
    - La creación de seeders para poblar la base de datos con datos de prueba
    - La creación de migraciones para crear la estructura de la base de datos
    - La creación de scripts SQL para crear la base de datos y tablas
    - La creación de un sistema de registro de asistencia para los empleados
    - La creación de un sistema de resumen mensual para los empleados

2. **Base de Datos**
Decidí tener dos tablas principales:
- **empleados**: con los campos id, nombre, correo y puesto.
- **registros_asistencia**: con los campos id, empleado_id, fecha, hora_entrada, hora_salida y total_horas.

Para evitar que un mismo empleado tenga más de un registro por día, agregué una restricción de unicidad con `empleado_id` y `fecha`. Así me aseguro de que solo pueda fichar una vez por día.

3. **API Endpoints**
Hice la API siguiendo el estilo REST. Estos son los endpoints que usé:

- **POST /asistencia/fichar**: Para que el empleado fiche (entrada o salida).
- **GET /asistencia/historial/{empleadoId}**: Para ver el historial de fichajes.
- **GET /asistencia/resumen/{empleadoId}**: Para ver un resumen de horas trabajadas por mes.

Usé los códigos de estado HTTP correctos (como 200 o 201) y las respuestas siempre son en formato JSON para que sea fácil de usar desde el frontend o alguna app.

4.  **Reglas de la prueba**
- Solo se puede fichar una vez por día (esto lo validé en el código y también con una restricción en la base de datos).
- La hora de salida tiene que ser después de la de entrada (comparo los horarios para asegurarme de eso).
- Para calcular las horas trabajadas, simplemente resto la hora de salida con la de entrada y convierto el resultado a horas decimales.

5.  **Manejo de Errores**
Laravel me ayudó bastante con esto. Si los datos están mal o falta algo, se devuelve un error claro. También puse validaciones en el controlador para que todo sea más seguro y el usuario sepa qué pasó si algo falla.

7.  **Seguridad**
- Usé las validaciones de Laravel para evitar inyecciones SQL.
- Configuré bien las relaciones entre las tablas para que los datos estén conectados de forma correcta.
- Siempre trabajé con Eloquent y no hice consultas SQL a mano, lo que también ayuda a mantener el proyecto más seguro.

8. **Conclusión**
El sistema implementado cumple con todos los requisitos especificados, proporcionando una API RESTful para gestionar la asistencia de empleados. La estructura es escalable y puede ser fácilmente extendida para añadir nuevas funcionalidades en el futuro.

