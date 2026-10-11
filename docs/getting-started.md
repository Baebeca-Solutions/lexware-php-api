# Erste Schritte

## Lexware Office API-Schlüssel erstellen

Erstelle in deinem [Lexware Office Account](https://www.baebeca.de/lexware) einen API-Schlüssel:

Einstellungen → Erweiterungen → Lexware Office Public API → „Schlüssel neu erstellen“

## Installation

Siehe [README.md](../README.md#composer).

## Klasse einbinden

```php
<?php
require __DIR__.'/vendor/autoload.php';
use \Baebeca\LexwareApi;
use \Baebeca\LexwareException;

$lexware = new LexwareApi([
    'api_key' => 'my-api-key'
]);
```

### Startparameter

| Parameter    | Typ    | Standard | Beschreibung                                                                                  |
|--------------|--------|----------|-----------------------------------------------------------------------------------------------|
| `api_key`    | string | –        | API-Schlüssel (Pflicht)                                                                       |
| `callback`   | string | –        | Standard-Callback-URL für Webhooks von Lexware Office, siehe [Events / Webhooks](webhooks.md) |
| `ssl_verify` | bool   | `true`   | Ob die SSL-Verbindung zu Lexware Office validiert werden soll                                 |

## Fehlerbehandlung

Alle Methoden sollten stets in einem `try`/`catch`-Block verwendet werden.
Die Klasse `LexwareException` liefert über `$e->getError()` – sofern vorhanden – weitere Details zur Anfrage und zur API-Antwort.

Beispiel: Abfrage einer nicht existierenden Rechnung

```php
try {
    $invoice = $lexware->get_invoice('7f0f0f7f-dd61-4bf7-a9f7-a67b0530c7e9');
}
catch (LexwareException $e) {
    echo $e->getMessage();
    // LexwareApi: Requested resource does no exist (anymore)

    print_r($e->getError());
    /*
    Array (
        [HTTP Status] => 404
        [Requested URI] => https://api.lexware.io/v1/invoices/7f0f0f7f-dd61-4bf7-a9f7-a67b0530c7e9
        [Requested Payload] =>
        [Response] => stdClass Object ( ... Fehlerantwort von Lexware Office ... )
    )
    */
}
```

## Rate-Limit

Lexware Office erlaubt 2 Anfragen pro Sekunde, siehe [API Rate Limits](https://developers.lexware.io/docs/#api-rate-limits).

Das Rate-Limit wird automatisch erkannt. Der Request wird nach einer Pause von „Sekunden × Anzahl Versuche“ erneut abgesetzt.
Das Verhalten lässt sich parametrisieren:

```php
$lexware->configure_rate_limit(bool $repeat = true, int $seconds_to_sleep = 1, int $max_tries = 10): void
```

Zusätzlich kann eine Callback-Funktion hinterlegt werden, die bei jedem getroffenen Rate-Limit ausgelöst wird.
Sie erhält einen `bool`-Parameter, ob der Aufruf erneut versucht wird.

```php
$lexware->configure_rate_limit_callable(?callable $callback = null): void
```
