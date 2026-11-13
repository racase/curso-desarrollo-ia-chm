from infradata.config import DEFAULT_DATABASE_URL, database_url


def test_default_url_is_used_when_variable_is_missing(monkeypatch):
    monkeypatch.delenv("DATABASE_URL", raising=False)
    assert database_url() == DEFAULT_DATABASE_URL


def test_default_url_is_used_when_variable_is_empty(monkeypatch):
    monkeypatch.setenv("DATABASE_URL", "")
    assert database_url() == DEFAULT_DATABASE_URL


def test_environment_variable_overrides_default(monkeypatch):
    monkeypatch.setenv("DATABASE_URL", "postgresql://user:pw@db:5432/other")
    assert database_url() == "postgresql://user:pw@db:5432/other"
