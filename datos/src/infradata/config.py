"""Runtime configuration, read from environment variables."""

import os

DEFAULT_DATABASE_URL = "postgresql://infradata:infradata@localhost:5433/infradata"


def database_url() -> str:
    """Return the connection string, taken from DATABASE_URL if it is set."""
    return os.environ.get("DATABASE_URL") or DEFAULT_DATABASE_URL
