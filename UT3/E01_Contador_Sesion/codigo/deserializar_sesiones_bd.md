## Deserializar Sesiones en BD

SELECT
    id,
    CONVERT(FROM_BASE64(payload) USING utf8mb4) AS contenido
FROM sessions;



