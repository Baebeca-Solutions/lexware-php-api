# lexware-php-api (ehemals lexoffice-php-api)
PHP 8 Client für [office.lexware.de](https://www.awin1.com/cread.php?awinmid=13787&awinaffid=635216&ued=https%3A%2F%2Foffice.lexware.de) API (ehemals lexoffice.de)

- [Github](https://github.com/Baebeca-Solutions/lexware-php-api)
- [Wiki (Dokumenation)](https://wiki.baebeca.de/index.php?title=lexware-php-api)
- [Projektseite](https://www.baebeca.de/softwareentwicklung/lexware-php-client/)
- [Packagist](https://packagist.org/packages/baebeca/lexware-php-api)
- [Lexware Office API-Dokumentation](https://developers.lexware.io)

[![Lexware Office - Technologie Partner](https://www.baebeca.de/wp-content/uploads/2024/09/Lexware-Office_TP_Badge_rgb-1-300x199.png)](https://www.awin1.com/cread.php?awinmid=13787&awinaffid=635216&ued=https%3A%2F%2Foffice.lexware.de)

## Composer
 
### CLI
```composer require baebeca/lexware-php-api:^2.0```

### composer.json
```json
{
  "require": {
    "baebeca/lexware-php-api": "^2.0",
  }
}
```

## Nutzung

```PHP
<?php
require __DIR__.'/vendor/autoload.php';
use \Baebeca\LexwareApi;
use \Baebeca\LexwareException;

$lexware = new LexwareApi([
    'api_key' => 'my-api-key'
]);
```
- [Wiki (Dokumenation)](https://wiki.baebeca.de/index.php?title=lexware-php-api)

## Error Handling

```PHP
<?php 
// catch errors
try {
    $invoices = $lexware->get_last_invoices(-5);
}
catch (LexwareException $e) {
    var_dump($e->getMessage());
    print_r($e->getError());
}
```

- [Wiki (Dokumenation)](https://wiki.baebeca.de/index.php?title=lexware-php-api)

## Support
An wen kann ich mich wenden, wenn ich Probleme oder Fragen habe?<br>
Für diese Frage ist entscheidend, ob du eine Nutzungslizenz erworben hast oder die freie Variante benutzt.

* Wenn eine Nutzungslizenz vorhanden ist, kannst du jederzeit ein Ticket bei uns öffnen:
  * E-Mail: support@baebeca.de
  * Telefon: 02261-8161691
* Wenn du die freie Version benutzt, kannst du einen Github [issue](https://github.com/Baebeca-Solutions/lexware-php-api/issues) öffnen.

## Lizenz
Dieses Projekt steht unter der [GNU AGPLv3 Lizenz](./LICENSE_DE.txt).

Du darfst den Code frei verwenden, verändern und weiterverbreiten, **sofern deine Software ebenfalls quelloffen und veröffentlicht wird** (AGPLv3-konform).

---

### Kommerzielle / Closed-Source Nutzung

Die Verwendung in **kommerziellen** oder **Closed-Source**-Projekten ist **nicht ohne eine kommerzielle Lizenz gestattet**.

Bitte kontaktiere uns unter **support@baebeca.de**, um eine Lizenz zu erwerben.  
Siehe dazu alle Infos: [Kommerzielle Lizenzbedingungen](./LICENSE-commercial_DE.md)