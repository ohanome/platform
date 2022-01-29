# ohano: Platform

Repository für die Hauptplattform. Dieses Repository soll dabei helfen, zu verstehen wie die Website aufgebaut ist. Wir erlauben es nicht den Code zu kopieren oder zu bearbeiten, wenn keine ausdrückliche Erlaubnis vorliegt.

**Inhalt**
1. [Setup](#setup)
2. [Mitwirkung](#mitwirkung)
3. [Branches](#branches)
4. [Deployment](#deployment)
5. [Theme](#theme)
6. [License (en)](#license)

## Setup

- Führe `composer install` aus um alle Abhängigkeiten zu installieren
- Kopiere die Datei `.env.example` zu `.env` und trage dort deine lokalen MySQL-Daten ein
- Navigiere in der Konsole zu `web/themes/custom/ohano-main` und führe `npm install` aus, dies kompiliert den SCSS-Code des Hauptthemes
- Importiere das Datenbank-Abbild, das dir vorliegt (mehr dazu unter [Mitwirkung](#mitwirkung))
- Logge dich unter `/user/login` ein

## Mitwirkung

Du kannst jederzeit an diesem Projekt als Contributor mitarbeiten (hierfür kannst du das bereitgestellte Datenbank-Abbild unter [/db/base.sql.gz](./db/base.sql.gz) nutzen, Username und Passwort ist jeweils `ohano`), allerdings ist das Deployment und die Planung der Features dem Developer-Team von ohano vorbehalten. Um Teil des Teams zu werden, komme einfach auf unseren [Discord](https://discord.gg/JQTFQy9RvC) und wende dich an das Team um mehr über den Bewerbungsprozess zu erfahren.

Nur als Teammitglied bekommst du die oben erwähnte ausdrückliche Erlaubnis und das Admin-Passwort sowie Zugangsdatem zum Server auf den das ganze deployed wird.

Wenn du Teammitglied bist, gelten ein paar Regeln für das Bearbeiten des Codes:

- Halte dich an den Drupal coding standard. Dies kannst du am einfachsten machen, indem du immer mal wieder zwischendurch `bin/phpcs` ausführst. Das Script prüft gegen die Standards "Drupal" und "DrupalPractice".
- Teste deinen Code. Schreibe für jeden Code, den du schreibst entsprechende Tests, genauso wenn du ungetesteten Code findest. Du kannst dich hier an bereits geschriebenen Tests orientieren oder aber an Tests die im Core oder Contrib-Modulen liegen. Laufen lassen kannst du sie Lokal entweder mit `bin/test` oder deinem eigenen Setup.
- Custom entities haben in jedem Fall Getter und Setter für jedes Feld, das erleichtert das Auslesen und ändern von Werten.

### Branches

- `main` - Der Hauptbranch der auch Live ist.
- `beta` - Abbild des Beta-Systems.
- `alpha` - Abbild des Alpha-Systems.
- `feature/*` - Feature-Branches welche für jeden Issue mit Tag `todo` angelegt werden.
- `fix/*` - Diese Branches werden für Issues angelegt die das Tag `fixme` haben.

Branches werden (abseits von `main`, `beta` und `alpha`) immer nach dem Issue benannt. Issues bekommen neben der Kategorisierung wie `todo` oder `fixme` noch ein Feature-Tag welches beschreibt, welches Feature betroffen ist, beispielsweise `user-profile`. Ein Issue mit dem Namen "Profilbild kann nicht hochgeladen werden" würde dann zum Branchnamen `fix/user-profile--profile-image` werden. Gleiches gilt auch für Feature-Branches.

Wenn ein Feature gänzlich neu ist, wird der Branch nur `feature/{FEATURE_NAME}` heißen.

Unser Workflow sieht wie folgt aus:

`feature/*`/`fix/*` > `alpha` > `beta` > `main`

### Commits

Zu allem was wir machen muss es ein Issue geben. Commits sollen daher die folgende Form haben:
```text
[ISSUE_ID]: [Beschreibung was geändert wurde auf Englisch]
```

Englisch einfach nur weil es einfach ist diese README perspektivisch zu übersetzen, aber nicht Commits.

## Deployment

Das Deployment wird mittels Deployer gehandhabt. Hierzu reicht der Befehl
```shell
$ vendor/bin/dep deploy <env>
```

Als `env` wird dann entweder `alpha`, `beta` oder `main` gewählt.

Vor dem Deployment musst du allerdings noch die passenden Daten in der Datei `deploy.php` angeben, das machst du am einfachsten indem du die Datei [deploy.php.dist](./deploy.php.dist) kopierst und die mit `#!` markierten Stellen ausfüllst.

## Theme

Sämtlicher CSS-Code der im Ordner `./web/themes/custom/ohano-[THEME]/css` liegt wird nicht versioniert, da wir SCSS verwenden. Um dein geschriebenes SCSS zu CSS zu kompilieren kannst du
```bash
npm run scss
```
ausführen. Dies kompiliert das SCSS einmal.
Alternativ kannst du auch
```bash
npm run scss-watch
```
ausführen, diese Befehl "horcht" auf Änderungen im SCSS-Code und kompiliert beim Speichern der Datei.

Beide Befehle musst du im Ordner `web/themes/custom/ohano-[THEME]` ausführen.

## License

Copyright 2022 ohano and its contributors.

We do not grant permission to distribute this software for private or commercial or any other use. We do not grant permission to duplicate code, modify it, merge, publish sublicense and/or sell copies of the software.
