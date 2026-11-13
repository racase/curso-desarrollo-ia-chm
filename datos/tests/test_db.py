"""Integration tests: they need `docker compose up -d` and are skipped otherwise."""

from datetime import UTC

import psycopg
import pytest

from infradata.db import connect

pytestmark = pytest.mark.integration


@pytest.fixture(scope="module")
def conn():
    try:
        connection = connect(connect_timeout=2)
    except psycopg.OperationalError:
        pytest.skip("database not reachable; run `docker compose up -d`")
    with connection:
        yield connection


def test_seed_has_twelve_sensors(conn):
    assert conn.execute("SELECT count(*) FROM sensors").fetchone()[0] == 12


def test_readings_cover_the_expected_period(conn):
    first, last = conn.execute("SELECT min(time), max(time) FROM readings").fetchone()
    assert first.astimezone(UTC).date().isoformat() == "2026-08-01"
    assert last.astimezone(UTC).date().isoformat() == "2026-10-30"


def test_readings_is_a_hypertable(conn):
    row = conn.execute(
        "SELECT 1 FROM timescaledb_information.hypertables WHERE hypertable_name = 'readings'"
    ).fetchone()
    assert row is not None
