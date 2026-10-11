# Erste Schritte

## Lexware Office API-Schlüssel erstellen

Erstelle in deinem [Lexware Office Account](https://www.baebeca.de/lexware) einen API-Schlüssel:

Einstellungen → Erweiterungen → Lexware Office Public API → „Schlüssel neu erstellen“

## Klasse einbinden

Lade [`lexoffice-php-api.php`](../lexoffice-php-api.php) in dein Projekt und binde die Datei ein:

```php
<?php
require_once(__DIR__.'/lexoffice-php-api.php');

$lexoffice = new lexoffice_client([
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
Die Klasse `lexoffice_exception` liefert über `$e->getError()` (Alias `$e->get_error()`) – sofern vorhanden – weitere Details zur Anfrage und zur API-Antwort.

Beispiel: Abfrage einer nicht existierenden Rechnung

```php
try {
    $invoice = $lexoffice->get_invoice('7f0f0f7f-dd61-4bf7-a9f7-a67b0530c7e9');
}
catch (lexoffice_exception $e) {
    echo $e->getMessage();
    // lexoffice-php-api: Requested resource does no exist (anymore)

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
$lexoffice->configure_rate_limit(bool $repeat = true, int $seconds_to_sleep = 1, int $max_tries = 10): void
```

Zusätzlich kann eine Callback-Funktion hinterlegt werden, die bei jedem getroffenen Rate-Limit ausgelöst wird.
Sie erhält einen `bool`-Parameter, ob der Aufruf erneut versucht wird.

```php
$lexoffice->configure_rate_limit_callable(callable $callback = null): void
```
