# CLAUDE.md

SKVote: einfaches Abstimmungs-Tool (Umfragen anlegen, abstimmen, Ergebnisse als Chart) in klassischem PHP mit MySQL.

## Tech-Stack

- PHP (prozedural, ohne Framework), `mysqli`
- MySQL/MariaDB, Datenbank `abstimm_tool` (Tabellen u. a. `settings_simple`, `simple_result`)
- Eigenes CSS (`master.css`, `shift-2017_grid.css`)

## Ausführen

Lokal über einen PHP-fähigen Webserver (XAMPP o. ä.) oder `php -S localhost:8000` im Repo-Root. Eine MySQL-Datenbank `abstimm_tool` muss existieren. Die Verbindung steht in `db-connect.php`.

Es gibt keine Tests. Änderungen im Browser prüfen und `php -l <datei>` für Syntaxchecks nutzen.

## Struktur

- `db-connect.php`: DB-Verbindung, wird per `include` eingebunden
- `simple-voting/`: Seiten für Einstellungen, Abstimmung, Übersichten und Charts

## Sicherheit (wichtig)

Der bestehende Code baut SQL-Queries durch String-Verkettung mit `$_GET`/`$_POST` und ist damit anfällig für SQL-Injection. Für jeden Code, den du anfasst oder neu schreibst:

- **Immer Prepared Statements** (`$mysqli->prepare()` + `bind_param()`), nie Request-Werte in SQL-Strings einsetzen.
- **Ausgaben escapen**: alles aus DB oder Request mit `htmlspecialchars($v, ENT_QUOTES, 'UTF-8')` ausgeben.
- **Eingaben validieren**: IDs mit `filter_input(INPUT_GET, 'umfrageid', FILTER_VALIDATE_INT)`.
- Keine DB-Fehlermeldungen (`$mysqli->error`) an Nutzer ausgeben, sondern loggen (`error_log`) und eine generische Meldung zeigen.
- Zustandsändernde Aktionen nur per POST, möglichst mit CSRF-Token.
- Zugangsdaten nicht im Code hart verdrahten, sondern über Umgebungsvariablen oder eine nicht eingecheckte Konfigurationsdatei.
- `$mysqli->set_charset('utf8mb4')` nach dem Verbindungsaufbau.

Bestehende Lücken nicht ungefragt großflächig umbauen, aber darauf hinweisen. Wenn du eine Datei ohnehin änderst, die betroffenen Queries dort mit absichern.

## Konventionen

- PSR-12 als Stilrichtlinie für neuen Code (4 Leerzeichen, `<?php` ohne schließendes `?>` in reinen PHP-Dateien).
- Logik (DB-Zugriff, Verarbeitung) oben in der Datei, HTML-Ausgabe danach; im Template nur Ausgabe mit `<?= htmlspecialchars(...) ?>`.
- `include`/`require` mit `__DIR__`: `require __DIR__ . '/../db-connect.php';`
- Strikte Vergleiche (`===`, `!==`).
- HTML: semantisch, `lang="de"`, UTF-8.

## Arbeitsweise

- Lies vor dem Schreiben und folge den bestehenden Mustern.
- Nur die gestellte Aufgabe lösen, keine ungefragten Refactorings.
- Commits nach Conventional Commits (`feat:`, `fix:`, `refactor:`, `docs:`, `chore:`).
