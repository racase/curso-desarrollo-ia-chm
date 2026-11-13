from infradata.__main__ import main


def test_unknown_command_shows_usage(capsys):
    assert main(["nope"]) == 2
    assert "Uso:" in capsys.readouterr().err
