# QualityDoc - Sistema de Gestión de Documentos

QualityDoc es una aplicación web desarrollada con arquitectura MVC (Modelo-Vista-Controlador) en PHP puro, diseñada para la visualización, control de versiones y auditoría de documentos empresariales.

## Tecnologías Utilizadas
* **Backend:** PHP 8+ (Arquitectura MVC).
* **Base de Datos:** PostgreSQL.
* **Frontend:** HTML5, CSS3 y Bootstrap 5 (vía CDN).
* **Servidor:** Apache (XAMPP).

## Estructura del Proyecto (MVC)
El proyecto está dividido lógicamente para separar la interfaz, la lógica de negocio y el acceso a datos:

* `/config/db.php`: Contiene la clase de conexión usando PDO para PostgreSQL.
* `/models/Document.php`: Maneja todas las consultas SQL (SELECT, INSERT, UPDATE). Aquí se encuentra la lógica de auditoría y acuses de lectura.
* `/controllers/DocumentController.php`: Intermediario que procesa las peticiones del usuario (vía URL o Formularios), llama al modelo y carga la vista correspondiente.
* `/views/`: Contiene los archivos de interfaz de usuario (`list.php` para el repositorio y `view.php` para el visualizador interactivo).
* `index.php`: Enrutador principal (Front Controller) que recibe todo el tráfico y dirige las acciones.

## Funcionamiento de la Auditoría y Reglas de Negocio
Para cumplir con los requerimientos de validación, el sistema implementa dos capas de trazabilidad simulando al **Usuario 1** (por defecto):

1. **Auditoría de Vistas (`document_views_audit`):** En el momento exacto en que el controlador carga la vista de un documento, se inserta un registro silencioso en la base de datos indicando qué usuario abrió el archivo y en qué fecha. Abrir el archivo no implica haberlo leído.
2. **Acuse de Lectura (`document_read_acknowledgments`):** El usuario debe presionar explícitamente el botón "He leído y comprendido" en la interfaz. Esto dispara una acción POST que registra la confirmación formal. El sistema utiliza `ON CONFLICT DO NOTHING` en SQL para evitar registros duplicados si el usuario presiona el botón múltiples veces para la misma versión del documento.

## Control de Versiones
El sistema utiliza UUIDs para identificar cada registro de forma única. Sin embargo, los documentos mantienen su relación histórica a través del campo `document_code`. La aplicación distingue la versión vigente filtrando por el campo booleano `is_latest = TRUE`, permitiendo consultar el historial de versiones deprecadas en el panel lateral del visualizador.

## Instrucciones de Instalación
1. Clonar o copiar la carpeta `qualitydoc` dentro del directorio `htdocs` de XAMPP.
2. Crear una base de datos en PostgreSQL llamada `qualitydoc`.
3. Ejecutar el script `postgre.sql` en la base de datos para generar el esquema de tablas.
4. Actualizar las credenciales de PostgreSQL (usuario y contraseña) en el archivo `config/db.php`.
5. Colocar los archivos físicos (PDFs, imágenes) en la raíz del proyecto para que el visor los detecte correctamente.
6. Acceder a la aplicación desde el navegador: `http://localhost/qualitydoc/`
