# Kontakte

## Kontakt abfragen

```php
$lexware->get_contact(string $uuid): object
```

## Alle Kontakte abfragen

```php
$lexware->get_contacts_all(): array
```

## Kontakt suchen

```php
$lexware->search_contact(array $filters, bool $wildcards = false): object
```

Mehrere Filter werden mit UND verknüpft, leere Filter ignoriert.

| Filter     | Beschreibung                   |
|------------|--------------------------------|
| `email`    | mindestens 3 Zeichen           |
| `name`     | mindestens 3 Zeichen           |
| `number`   | Kunden- oder Lieferantennummer |
| `customer` | `true`/`false`                 |
| `vendor`   | `true`/`false`                 |

Lexware Office sucht stets nach „enthält“: Eine Suche nach `hans@hans.de` findet auch `hans@hans.de.google.com`.

Mit `$wildcards = true` gelten `_` (ein Zeichen) und `%` (beliebig viele Zeichen) als Platzhalter, andernfalls als normale Zeichen.

```php
$lexware->search_contact([
    'email' => '',
    'name' => 'John Doe',
    'number' => '',
    'customer' => '',
    'vendor' => '',
]);
```

## Kontakt anlegen

```php
$lexware->create_contact(array $data): object
```

`$data` im [Lexware Office Format](https://developers.lexware.io/docs/#contacts-endpoint-create-a-contact).

Vor dem Senden bereinigt die Methode die Daten:

- Leerer Firmenname, Vor- oder Nachname wird durch einen Platzhalter ersetzt (z. B. `-- ohne Firmenname --`).
- Eine ungültige USt-IdNr. (`company.vatRegistrationId`) wird entfernt, siehe [`valid_vat_id()`](taxes.md#ust-idnr-formal-prüfen).
- Mehrere Telefonnummern in einem Feld (getrennt durch `,` oder „oder“) werden aufgeteilt.

```php
$lexware->create_contact([
    'version' => 0,
    'roles' => [
        'customer' => [
            'number' => '',
        ],
    ],
    'company' => [
        'name' => 'Baebeca Solutions GmbH',
        'street' => 'Genklerhardt 6',
        'zip' => '51647',
        'city' => 'Gummersbach',
        'countryCode' => 'DE',
        'contactPersons' => [
            [
                'salutation' => 'Herr',
                'firstName' => 'Jon',
                'lastName' => 'Doe',
                'emailAddress' => 'support@baebeca.de',
                'phoneNumber' => '022619202930',
            ],
        ],
    ],
    'addresses' => [
        'billing' => [
            [
                'street' => 'Genklerhardt 6',
                'zip' => '51647',
                'city' => 'Gummersbach',
                'countryCode' => 'DE',
            ],
        ],
        'shipping' => [
            [
                'street' => 'Genklerhardt 6',
                'zip' => '51647',
                'city' => 'Gummersbach',
                'countryCode' => 'DE',
            ],
        ],
    ],
    'emailAddresses' => [
        'business' => [
            'support@baebeca.de',
        ],
    ],
    'phoneNumbers' => [
        'business' => [
            '022619202930',
        ],
    ],
    'note' => '',
]);
```

## Kontakt aktualisieren

```php
$lexware->update_contact(string $uuid, array|object $data): object
```

`$data` im [Lexware Office Format](https://developers.lexware.io/docs/#contacts-endpoint-update-a-contact), inklusive der aktuellen `version`. Die Daten werden wie bei `create_contact()` bereinigt.
