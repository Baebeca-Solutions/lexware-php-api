# lexware-php-api (ehemals lexoffice-php-api)
PHP 8 Client für [office.lexware.de](https://www.baebeca.de/lexware) API (ehemals lexoffice.de)

> **Lizenz:** Für Open-Source-Projekte kostenlos unter der AGPLv3.
> Für kommerzielle oder Closed-Source-Software erwirbst du einfach eine [kommerzielle Lizenz](./LICENSE-commercial_DE.md): Lifetime-Lizenz für einmalig 199,- EUR netto, inklusive Support. Details unter [Lizenz](#lizenz).

- [Dokumentation](docs/README.md)
- [Upgrade-Hinweise](UPGRADE.md)
- [Projektseite](https://www.baebeca.de/softwareentwicklung/lexware-php-client/)
- [Packagist](https://packagist.org/packages/baebeca/lexware-php-api)
- [Lexware Office API-Dokumentation](https://developers.lexware.io)

[![Lexware Office - Technologie Partner](https://www.baebeca.de/wp-content/uploads/2024/09/Lexware-Office_TP_Badge_rgb-1-300x199.png)](https://www.baebeca.de/lexware)

## Versionen

| Branch                       | PHP   | Installation   | Dokumentation                                                                                                                 |
|------------------------------|-------|----------------|-------------------------------------------------------------------------------------------------------------------------------|
| `master`                     | ≥ 8.0 | Composer       | [docs/](docs/README.md)                                                                                                       |
| `php-7.4-legacy-no-composer` | 7.4   | `require_once` | [docs/ im Legacy-Branch](https://github.com/Baebeca-Solutions/lexware-php-api/blob/php-7.4-legacy-no-composer/docs/README.md) |

Die Dokumentation gehört jeweils zu ihrem Branch bzw. Tag.

## Composer

### CLI
```
composer require baebeca/lexware-php-api:~2.0
```

### composer.json
```json
{
  "require": {
    "baebeca/lexware-php-api": "~2.0"
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

try {
    $invoices = $lexware->get_last_invoices(5);
}
catch (LexwareException $e) {
    var_dump($e->getMessage());
    print_r($e->getError());
}
```

Alle Methoden, Startparameter und die Fehlerbehandlung beschreibt die [Dokumentation](docs/README.md).

## Support
An wen kann ich mich wenden, wenn ich Probleme oder Fragen habe?<br>
Für diese Frage ist entscheidend, ob du eine Nutzungslizenz erworben hast oder die freie Variante benutzt.

* Wenn eine Nutzungslizenz vorhanden ist, kannst du jederzeit ein Ticket bei uns öffnen:
  * E-Mail: support@baebeca.de
  * Telefon: 02261-8161691
* Wenn du die freie Version benutzt, kannst du einen Github [issue](https://github.com/Baebeca-Solutions/lexware-php-api/issues) öffnen.

## Lizenz
Dieses Projekt ist dual lizenziert: frei unter der [GNU AGPLv3 Lizenz](./LICENSE) oder mit einer [kommerziellen Lizenz](./LICENSE-commercial_DE.md).

Unter der AGPLv3 darfst du den Code frei verwenden, verändern und weiterverbreiten, **sofern deine Software ebenfalls quelloffen und veröffentlicht wird**.
Möchtest du deinen Quellcode nicht offenlegen, erwirbst du einfach eine kommerzielle Lizenz.

---

### Kommerzielle / Closed-Source Nutzung

Die Verwendung in **kommerziellen** oder **Closed-Source**-Projekten ist **nicht ohne eine kommerzielle Lizenz gestattet**.

Mit der kommerziellen Lizenz:

- setzt du die lexware-php-api in Closed-Source-Software und SaaS ein, ohne deinen Quellcode offenzulegen
- erhältst du [Support](#support) per E-Mail und Telefon
- bekommst du auf Anfrage individuelle Erweiterungen
- erhältst du eine Lifetime-Lizenz für einmalig 199,- EUR netto
- unterstützt du die Weiterentwicklung des Projekts

Bitte kontaktiere uns unter **support@baebeca.de**, um eine Lizenz zu erwerben.
Siehe dazu alle Infos: [Kommerzielle Lizenzbedingungen](./LICENSE-commercial_DE.md)
