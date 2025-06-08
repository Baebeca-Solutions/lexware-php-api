# lexware-php-api (ehemals lexoffice-php-api)
PHP Client für [office.lexware.de](https://www.awin1.com/cread.php?awinmid=13787&awinaffid=635216&ued=https%3A%2F%2Foffice.lexware.de) API (ehemals lexoffice.de)
 
## Composer
Ersetze "php-x.x" mit deiner gewünschten PHP Version.
 
### CLI
```composer require baebeca/lexware-php-api:dev-php-x.x```

### composer.json
```json
{
  "require": {
    "baebeca/lexware-php-api": "dev-php-x.x",
  }
}
```

### Nutzung

```PHP
<?php

require __DIR__.'/vendor/autoload.php';
use \Baebeca\LexwareApi;
use \Baebeca\LexwareException;

$api = new LexwareApi([
    'api_key' => 'my-api-key'
]);
```

## Baebeca Solutions GmbH & Lexware Office
* [Integrationspartner](https://www.awin1.com/cread.php?awinmid=13787&awinaffid=635216&ued=https%3A%2F%2Foffice.lexware.de)
* [Softwarepartner](https://www.awin1.com/cread.php?awinmid=13787&awinaffid=635216&ued=https%3A%2F%2Foffice.lexware.de)

[![Lexware Office - Technologie Partner](https://www.baebeca.de/wp-content/uploads/2024/09/Lexware-Office_TP_Badge_rgb-1-300x199.png)](https://www.awin1.com/cread.php?awinmid=13787&awinaffid=635216&ued=https%3A%2F%2Foffice.lexware.de)

## office.lexware.de API-Dokumentation
Die offizielle Lexware Office API-Dokumentation findest du [hier](https://developers.lexware.io).

## lexware-php-api Dokumentation
Die Dokumentation mit allen Informationen findest du in unserem [Wiki](https://wiki.baebeca.de/index.php?title=lexware-php-api).<br>
Allgemeine Themen findest du auf unserer [Projektseite](https://www.baebeca.de/softwareentwicklung/lexware-php-client/).

## Support
An wen kann ich mich wenden, wenn ich Probleme oder Fragen habe?<br>
Für diese Frage ist entscheidend, ob du eine Nutzungslizenz für unsere lexware-php-api erworben hast oder die kostenlose Variante nutzt.

* Sofern eine Nutzungslizenz vorhanden ist, kannst du dich sich jederzeit gerne wie folgt an uns wenden:
  * Ticket per Mail an support@baebeca.de
  * Ticket per Telefon an 02261-8161691
* Wenn du die kostenlose Version benutzt, kannst du einen Github [issue](https://github.com/Baebeca-Solutions/lexware-php-api/issues) öffnen.

## Lizenz
Dieses Projekt steht unter der [GNU AGPLv3 Lizenz](./LICENSE_DE.txt).

Du darfst den Code frei verwenden, verändern und weiterverbreiten, **sofern deine Software ebenfalls quelloffen ist** (AGPLv3-konform).

---

### Kommerzielle / Closed-Source Nutzung

Die Verwendung in **kommerziellen** oder **Closed-Source**-Projekten ist **nicht ohne eine kommerzielle Lizenz gestattet**.

Bitte kontaktiere uns unter **support@baebeca.de**, um eine Lizenz zu erwerben.  
Siehe dazu alle Infos: [Kommerzielle Lizenzbedingungen](./LICENSE-commercial_DE.md)