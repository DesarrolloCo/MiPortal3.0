-- Script para analizar el uso y efectividad de índices en módulo de horarios
-- Ejecutar en MySQL/MariaDB para obtener estadísticas

-- =====================================================
-- 1. VERIFICAR ÍNDICES EXISTENTES EN TABLA MALLAS
-- =====================================================
SELECT
    TABLE_NAME,
    INDEX_NAME,
    GROUP_CONCAT(COLUMN_NAME ORDER BY SEQ_IN_INDEX) as COLUMNS,
    NON_UNIQUE,
    CARDINALITY
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'mallas'
GROUP BY TABLE_NAME, INDEX_NAME, NON_UNIQUE
ORDER BY TABLE_NAME, INDEX_NAME;

-- =====================================================
-- 2. ANALIZAR TAMAÑO DE TABLA MALLAS
-- =====================================================
SELECT
    table_name AS 'Tabla',
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Tamaño (MB)',
    ROUND((data_length / 1024 / 1024), 2) AS 'Datos (MB)',
    ROUND((index_length / 1024 / 1024), 2) AS 'Índices (MB)',
    table_rows AS 'Filas Estimadas'
FROM information_schema.TABLES
WHERE table_schema = DATABASE()
  AND table_name IN ('mallas', 'empleados', 'horas', 'jornadas', 'campanas')
ORDER BY (data_length + index_length) DESC;

-- =====================================================
-- 3. ESTADÍSTICAS DE USO DE HORARIOS
-- =====================================================

-- Total de horarios por estado
SELECT
    MAL_ESTADO as Estado,
    CASE
        WHEN MAL_ESTADO = 1 THEN 'Activo'
        ELSE 'Inactivo'
    END as Descripcion,
    COUNT(*) as Total,
    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM mallas)), 2) as Porcentaje
FROM mallas
GROUP BY MAL_ESTADO;

-- Distribución de horarios por mes
SELECT
    DATE_FORMAT(MAL_DIA, '%Y-%m') as Mes,
    COUNT(*) as Total_Horarios,
    COUNT(DISTINCT EMP_ID) as Empleados_Distintos,
    COUNT(DISTINCT CAM_ID) as Campanas_Distintas
FROM mallas
WHERE MAL_DIA >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
GROUP BY DATE_FORMAT(MAL_DIA, '%Y-%m')
ORDER BY Mes DESC;

-- Top 10 empleados con más horarios
SELECT
    e.EMP_NOMBRES as Empleado,
    COUNT(m.MAL_ID) as Total_Horarios,
    COUNT(DISTINCT m.MAL_DIA) as Dias_Trabajados,
    MIN(m.MAL_DIA) as Primer_Horario,
    MAX(m.MAL_DIA) as Ultimo_Horario
FROM mallas m
INNER JOIN empleados e ON e.EMP_ID = m.EMP_ID
WHERE m.MAL_DIA >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
GROUP BY e.EMP_ID, e.EMP_NOMBRES
ORDER BY Total_Horarios DESC
LIMIT 10;

-- =====================================================
-- 4. ANÁLISIS DE RENDIMIENTO DE CONSULTAS COMUNES
-- =====================================================

-- Consulta 1: Horarios de empleado por fecha (usa idx_mallas_emp_dia)
EXPLAIN SELECT * FROM mallas
WHERE EMP_ID = 1
  AND MAL_DIA BETWEEN '2026-01-01' AND '2026-01-31'
ORDER BY MAL_INICIO;

-- Consulta 2: Horarios activos por campaña (usa idx_mallas_cam_dia)
EXPLAIN SELECT * FROM mallas
WHERE CAM_ID = 1
  AND MAL_DIA = '2026-04-01'
  AND MAL_ESTADO = 1;

-- Consulta 3: Estadísticas de horarios (usa idx_mallas_dia_estado)
EXPLAIN SELECT
    MAL_DIA,
    MAL_ESTADO,
    COUNT(*) as total
FROM mallas
WHERE MAL_DIA BETWEEN '2026-04-01' AND '2026-04-30'
GROUP BY MAL_DIA, MAL_ESTADO;

-- =====================================================
-- 5. DETECTAR HORARIOS DUPLICADOS (POTENCIALES PROBLEMAS)
-- =====================================================
SELECT
    EMP_ID,
    MAL_DIA,
    MAL_INICIO,
    MAL_FINAL,
    COUNT(*) as Duplicados
FROM mallas
GROUP BY EMP_ID, MAL_DIA, MAL_INICIO, MAL_FINAL
HAVING COUNT(*) > 1;

-- =====================================================
-- 6. IDENTIFICAR HORARIOS CON POSIBLES CONFLICTOS
-- =====================================================
SELECT
    m1.MAL_ID as Horario_1,
    m2.MAL_ID as Horario_2,
    m1.EMP_ID,
    e.EMP_NOMBRES,
    m1.MAL_DIA,
    m1.MAL_INICIO as Inicio_1,
    m1.MAL_FINAL as Final_1,
    m2.MAL_INICIO as Inicio_2,
    m2.MAL_FINAL as Final_2
FROM mallas m1
INNER JOIN mallas m2 ON m1.EMP_ID = m2.EMP_ID
    AND m1.MAL_DIA = m2.MAL_DIA
    AND m1.MAL_ID < m2.MAL_ID
INNER JOIN empleados e ON e.EMP_ID = m1.EMP_ID
WHERE m1.MAL_ESTADO = 1
  AND m2.MAL_ESTADO = 1
  AND (
    (m1.MAL_INICIO < m2.MAL_FINAL AND m1.MAL_FINAL > m2.MAL_INICIO)
  )
LIMIT 20;

-- =====================================================
-- 7. VERIFICAR SALUD DE ÍNDICES
-- =====================================================
-- Esta consulta muestra la cardinalidad de los índices
-- Una baja cardinalidad puede indicar un índice poco efectivo
SELECT
    s.TABLE_NAME,
    s.INDEX_NAME,
    s.COLUMN_NAME,
    s.CARDINALITY,
    t.TABLE_ROWS,
    ROUND((s.CARDINALITY / t.TABLE_ROWS * 100), 2) as Selectividad_Pct
FROM information_schema.STATISTICS s
INNER JOIN information_schema.TABLES t
    ON s.TABLE_SCHEMA = t.TABLE_SCHEMA
    AND s.TABLE_NAME = t.TABLE_NAME
WHERE s.TABLE_SCHEMA = DATABASE()
  AND s.TABLE_NAME IN ('mallas', 'empleados', 'horas', 'jornadas')
  AND s.NON_UNIQUE = 1
ORDER BY s.TABLE_NAME, s.INDEX_NAME, s.SEQ_IN_INDEX;

-- =====================================================
-- 8. RECOMENDACIONES PARA MANTENIMIENTO
-- =====================================================

-- Comando para optimizar tablas (ejecutar periódicamente)
-- OPTIMIZE TABLE mallas;
-- OPTIMIZE TABLE empleados;
-- OPTIMIZE TABLE horas;
-- OPTIMIZE TABLE jornadas;

-- Comando para analizar tablas (actualiza estadísticas de índices)
-- ANALYZE TABLE mallas;
-- ANALYZE TABLE empleados;
-- ANALYZE TABLE horas;
-- ANALYZE TABLE jornadas;

-- =====================================================
-- 9. MONITOREO DE RENDIMIENTO
-- =====================================================

-- Habilitar el slow query log para identificar consultas lentas
-- SET GLOBAL slow_query_log = 1;
-- SET GLOBAL long_query_time = 1; -- queries que tomen más de 1 segundo

-- Ver queries lentas más recientes
-- SELECT * FROM mysql.slow_log ORDER BY start_time DESC LIMIT 10;
