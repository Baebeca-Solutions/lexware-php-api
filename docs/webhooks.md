# Events / Webhooks

## Event anlegen

```php
$lexoffice->create_event(string $event, string|false $callback = false): object
```

| Parameter   | Beschreibung                                                                                                                                             |
|-------------|----------------------------------------------------------------------------------------------------------------------------------------------------------|
| `$event`    | Eines der von Lexware Office [angebotenen Events](https://developers.lexware.io/docs/#event-subscriptions-endpoint-event-types), z. B. `invoice.created` |
| `$callback` | Callback-URL für dieses Event. Ohne Angabe gilt der Startparameter `callback`, siehe [Erste Schritte](getting-started.md#startparameter)                 |

## Aktives Event abfragen

```php
$lexoffice->get_event(string $uuid): object
```

## Alle aktiven Events abfragen

```php
$lexoffice->get_events_all(): object
```

## Event löschen

```php
$lexoffice->delete_event(string $uuid): bool
```

## Webhook-Anfragen empfangen

[`callback.php`](../callback.php) kann als Callback-URL hinterlegt werden.
Das Skript prüft bei jedem Aufruf die Signatur `X-Lxo-Signature` und stellt so sicher, dass die Anfrage von Lexware Office stammt.
Anschließend verteilt es die Anfrage nach `eventType`, dort ergänzt du deine eigene Verarbeitung.
