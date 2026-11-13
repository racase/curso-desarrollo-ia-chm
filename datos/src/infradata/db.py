"""Database access helpers."""

import psycopg

from infradata.config import database_url


def connect(url: str | None = None, *, connect_timeout: int = 5) -> psycopg.Connection:
    """Open a connection to the demo database.

    Use it as a context manager so the connection is always closed::

        with connect() as conn:
            conn.execute("SELECT 1")
    """
    return psycopg.connect(url or database_url(), connect_timeout=connect_timeout)
