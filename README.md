# QualityDoc PHP Frontend

Este es el frontend principal de QualityDoc, construido con PHP y PostgreSQL.

## Instalación y Configuración

El proyecto utiliza Docker para facilitar el despliegue del entorno de desarrollo, incluyendo la base de datos PostgreSQL, Nginx y PHP-FPM.

### Prerrequisitos
*   **Docker** y **Docker Compose** instalados.
*   **Git** para clonar el repositorio.

### Pasos Rápidos

#### En Windows (PowerShell):
1.  Abre una terminal en la raíz de este proyecto.
2.  Ejecuta el script de configuración:
    ```powershell
    .\setup.ps1
    ```

#### En Linux (Bash):
1.  Abre una terminal en la raíz de este proyecto.
2.  Dale permisos y ejecuta el script:
    ```bash
    chmod +x setup.sh
    ./setup.sh
    ```

### ¿Qué hace el script?
1.  Crea un archivo `.env` con tus credenciales de PostgreSQL y la URL de la API de Login.
2.  Levanta un contenedor de **PostgreSQL** con persistencia de datos. Además, inyecta automáticamente el script `db.sql` para crear las tablas base en su primer inicio.
3.  Levanta un contenedor de **PHP-FPM** con las extensiones de base de datos necesarias instaladas.
4.  Levanta un servidor **Nginx** expuesto en el puerto 8080 que se comunica con el contenedor de PHP.

### Uso Diario

Una vez que hayas ejecutado el script de instalación (`setup`) por primera vez, **no necesitas volver a ejecutarlo**.

Para tu trabajo del día a día, utiliza los comandos estándar de Docker:

*   **Para Encender la App:**
    ```bash
    docker compose up -d
    ```
*   **Para Apagar la App:**
    ```bash
    docker compose down
    ```

La aplicación PHP estará disponible en [http://localhost:8080](http://localhost:8080).
