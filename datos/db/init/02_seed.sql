-- Synthetic data: 12 sensors in 3 sites, one reading every 5 minutes
-- from 2026-08-01 to 2026-10-30 (UTC). Fixed seed and fixed dates so that
-- everybody gets the same dataset.

SELECT setseed(0.42);

INSERT INTO sensors (code, name, kind, unit, site, installed_at) VALUES
    ('VAL-T01', 'Temperatura horno 1',        'temperature', '°C',   'Planta Valencia', '2024-03-11'),
    ('VAL-T02', 'Temperatura horno 2',        'temperature', '°C',   'Planta Valencia', '2024-03-11'),
    ('VAL-P01', 'Presión línea de vapor',     'pressure',    'bar',  'Planta Valencia', '2024-05-20'),
    ('VAL-F01', 'Caudal agua de proceso',     'flow',        'm3/h', 'Planta Valencia', '2025-01-15'),
    ('SAG-T01', 'Temperatura sala compresores', 'temperature', '°C',   'Planta Sagunto',  '2023-11-02'),
    ('SAG-P01', 'Presión compresor A',        'pressure',    'bar',  'Planta Sagunto',  '2023-11-02'),
    ('SAG-P02', 'Presión compresor B',        'pressure',    'bar',  'Planta Sagunto',  '2025-06-09'),
    ('SAG-L01', 'Nivel depósito principal',   'level',       '%',    'Planta Sagunto',  '2024-09-30'),
    ('PAT-T01', 'Temperatura nave',           'temperature', '°C',   'Almacén Paterna', '2025-02-17'),
    ('PAT-L01', 'Nivel depósito gasoil',      'level',       '%',    'Almacén Paterna', '2025-02-17'),
    ('PAT-F01', 'Caudal red contra incendios','flow',        'm3/h', 'Almacén Paterna', '2025-02-17'),
    ('PAT-P01', 'Presión red contra incendios','pressure',   'bar',  'Almacén Paterna', '2025-02-17');

-- Base value and amplitude of the daily cycle depend on the kind of sensor.
INSERT INTO readings (time, sensor_id, value)
SELECT
    t,
    s.id,
    round((
        CASE s.kind
            WHEN 'temperature' THEN 22 + s.id
            WHEN 'pressure'    THEN 6 + s.id * 0.1
            WHEN 'flow'        THEN 40 + s.id
            WHEN 'level'       THEN 60
        END
        + CASE s.kind
            WHEN 'temperature' THEN 4
            WHEN 'pressure'    THEN 0.5
            WHEN 'flow'        THEN 10
            WHEN 'level'       THEN 15
          END * sin(2 * pi() * extract(epoch FROM t) / 86400.0)
        + (random() - 0.5) * CASE s.kind
            WHEN 'temperature' THEN 1.0
            WHEN 'pressure'    THEN 0.2
            WHEN 'flow'        THEN 3.0
            WHEN 'level'       THEN 2.0
          END
    )::numeric, 2)
FROM generate_series(
         timestamptz '2026-08-01 00:00:00+00',
         timestamptz '2026-10-30 23:55:00+00',
         interval '5 minutes'
     ) AS t
CROSS JOIN sensors AS s;

-- Gaps: periods in which a sensor sent nothing.
DELETE FROM readings
WHERE sensor_id = (SELECT id FROM sensors WHERE code = 'SAG-P02')
  AND time >= '2026-09-03 08:00:00+00' AND time < '2026-09-03 14:30:00+00';

DELETE FROM readings
WHERE sensor_id = (SELECT id FROM sensors WHERE code = 'PAT-L01')
  AND time >= '2026-10-12 00:00:00+00' AND time < '2026-10-14 06:00:00+00';

DELETE FROM readings
WHERE sensor_id = (SELECT id FROM sensors WHERE code = 'VAL-F01')
  AND time >= '2026-08-20 22:15:00+00' AND time < '2026-08-20 23:05:00+00';

-- Anomalies: out-of-range values.
UPDATE readings SET value = 96.4
WHERE sensor_id = (SELECT id FROM sensors WHERE code = 'VAL-T01')
  AND time >= '2026-09-18 03:10:00+00' AND time < '2026-09-18 03:40:00+00';

UPDATE readings SET value = 0
WHERE sensor_id = (SELECT id FROM sensors WHERE code = 'SAG-P01')
  AND time >= '2026-10-05 11:00:00+00' AND time < '2026-10-05 11:20:00+00';

UPDATE readings SET value = -40
WHERE sensor_id = (SELECT id FROM sensors WHERE code = 'SAG-T01')
  AND time = '2026-08-27 16:45:00+00';

ANALYZE sensors;
ANALYZE readings;
