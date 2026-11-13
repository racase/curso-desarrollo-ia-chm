"""Command line entry point: ``python -m infradata check``."""

import sys
from datetime import UTC

import psycopg

from infradata.db import connect


def check() -> int:
    """Print a summary of the dataset. Returns a process exit code."""
    try:
        with connect() as conn:
            sensors = conn.execute("SELECT count(*) FROM sensors").fetchone()[0]
            readings, first, last = conn.execute(
                "SELECT count(*), min(time), max(time) FROM readings"
            ).fetchone()
    except psycopg.Error as exc:
        print(f"No se pudo consultar la base de datos: {exc}", file=sys.stderr)
        print("¿Está levantado el contenedor? Prueba: docker compose up -d", file=sys.stderr)
        return 1

    print(f"Sensores: {sensors}")
    print(f"Lecturas: {readings}")
    print(f"Desde:    {first.astimezone(UTC):%Y-%m-%d %H:%M} UTC")
    print(f"Hasta:    {last.astimezone(UTC):%Y-%m-%d %H:%M} UTC")
    return 0


def main(argv: list[str] | None = None) -> int:
    args = sys.argv[1:] if argv is None else argv
    if args == ["check"]:
        return check()
    print("Uso: python -m infradata check", file=sys.stderr)
    return 2


if __name__ == "__main__":
    raise SystemExit(main())
