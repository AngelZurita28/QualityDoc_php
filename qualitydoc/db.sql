-- 1. Tabla de Documentos (Actúa como la vista de SQL Server)
-- Contiene toda la información necesaria para mostrar el archivo sin hacer JOINs complejos.
CREATE TABLE documents (
    id UUID PRIMARY KEY,                   -- Mismo ID que en SQL Server
    document_code VARCHAR(50),
    title VARCHAR(200) NOT NULL,
    description TEXT,
    file_path VARCHAR(500),
    version_number INT DEFAULT 1,
    is_latest BOOLEAN DEFAULT TRUE,
    
    -- Datos desnormalizados (traídos mediante JOIN desde SQL Server en la sincronización)
    status_name VARCHAR(50),               -- Ej. 'Aprobado', 'Publicado'
    company_id INT,                        -- ID de la compañía dueña del documento
    company_name VARCHAR(100),             -- Nombre de la compañía
    author_id INT,                         -- ID del creador
    
    -- Trazabilidad de la sincronización
    sqlserver_created_at TIMESTAMP,        -- Fecha original de creación en SQL Server
    synced_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Cuándo se copió a PostgreSQL
);

-- 2. Tabla de Auditoría (Registro de visualizaciones)
-- Registra cada vez que un usuario abre o visualiza un documento.
CREATE TABLE document_views_audit (
    id BIGSERIAL PRIMARY KEY,
    document_id UUID NOT NULL REFERENCES documents(id) ON DELETE CASCADE,
    
    -- Datos del usuario obtenidos de la API (sin tener tabla 'users')
    user_id INT NOT NULL,                  
    company_id INT,                        -- Compañía a la que pertenece el usuario
    area VARCHAR(100),                     -- Área a la que pertenece el usuario
    
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índice para búsquedas rápidas de auditoría por documento o por usuario
CREATE INDEX idx_views_document ON document_views_audit(document_id);
CREATE INDEX idx_views_user ON document_views_audit(user_id);


-- 3. Tabla de Acuses de Lectura (Validación de comprensión)
-- Registra cuando el usuario hace clic explícitamente en "He leído y comprendido".
CREATE TABLE document_read_acknowledgments (
    id BIGSERIAL PRIMARY KEY,
    document_id UUID NOT NULL REFERENCES documents(id) ON DELETE CASCADE,
    
    -- Datos del usuario obtenidos de la API
    user_id INT NOT NULL,
    company_id INT,
    area VARCHAR(100),
    
    acknowledged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Restricción: Un usuario solo puede confirmar lectura UNA VEZ por versión de documento
    CONSTRAINT uq_document_user_ack UNIQUE (document_id, user_id)
);

-- Índice para reportes de cumplimiento de lectura
CREATE INDEX idx_ack_document ON document_read_acknowledgments(document_id);
CREATE INDEX idx_ack_user ON document_read_acknowledgments(user_id);