-- Schema for the InfraData demo. Runs once, when the database volume is created.

CREATE EXTENSION IF NOT EXISTS timescaledb;

CREATE TABLE sensors (
    id           serial PRIMARY KEY,
    code         text NOT NULL UNIQUE,
    name         text NOT NULL,
    kind         text NOT NULL CHECK (kind IN ('temperature', 'pressure', 'flow', 'level')),
    unit         text NOT NULL,
    site         text NOT NULL,
    installed_at date NOT NULL
);

CREATE TABLE readings (
    time      timestamptz      NOT NULL,
    sensor_id integer          NOT NULL REFERENCES sensors (id),
    value     double precision NOT NULL
);

SELECT create_hypertable('readings', 'time');

CREATE INDEX readings_sensor_time_idx ON readings (sensor_id, time DESC);
