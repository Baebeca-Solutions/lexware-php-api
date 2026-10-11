# Verkaufsbelege

Der Parameter `$data` erwartet jeweils ein Array im Format der [Lexware Office API](https://developers.lexware.io/docs/).
`$finalized = true` stellt den Beleg direkt fertig, andernfalls wird er als Entwurf angelegt.
`$precedingSalesVoucherId` verknüpft den neuen Beleg mit einem vorhergehenden Beleg (Lexware Office UUID).

<!-- TOC -->
* [Rechnungen](#rechnungen)
* [Abschlagsrechnungen](#abschlagsrechnungen)
* [Serienrechnungen](#serienrechnungen)
* [Gutschriften](#gutschriften)
* [Angebote](#angebote)
* [Auftragsbestätigungen](#auftragsbestätigungen)
* [Lieferscheine](#lieferscheine)
* [Mahnungen](#mahnungen)
* [PDFs herunterladen](#pdfs-herunterladen)
<!-- TOC -->

## Beispiel `$data`

Gilt für Rechnungen, Auftragsbestätigungen und Lieferscheine. Für Angebote kommt `expirationDate` hinzu, für Gutschriften entfallen `shippingConditions` und `paymentConditions`.

```php
[
    'voucherDate' => date(DATE_RFC3339_EXTENDED),
    'introduction' => 'Einleitungstext',
    'remark' => "Fußzeile\r\nMehrzeilig",
    'address' => [
        #'contactId' => '<id>',
        'name' => 'Frau Jane Doe',
        'street' => 'Str. 1',
        'zip' => '12345',
        'city' => 'Stadt',
        'countryCode' => 'DE',
    ],
    'lineItems' => [
        [
            'type' => 'custom',
            'name' => 'Produktname',
            'description' => 'Beschreibung',
            'quantity' => 1,
            'unitName' => 'Stück',
            'unitPrice' => [
                'currency' => 'EUR',
                'netAmount' => 10.99,
                'taxRatePercentage' => 19,
            ],
            #'discountPercentage' => 0,
        ],
    ],
    'totalPrice' => [
        'currency' => 'EUR',
        #'totalDiscountAbsolute' => 0,
        #'totalDiscountPercentage' => 0,
    ],
    'taxConditions' => [
        'taxType' => 'net',
    ],
    'shippingConditions' => [
        'shippingDate' => date(DATE_RFC3339_EXTENDED),
        'shippingType' => 'delivery',
    ],
    'paymentConditions' => [
        'paymentTermLabel' => 'Vorkasse',
        'paymentTermDuration' => 1,
    ],
]
```

## Rechnungen

### Rechnung abfragen

```php
$lexware->get_invoice(string $uuid): object
```

### Alle Rechnungen abfragen

```php
$lexware->get_invoices_all(): array
```

### Die letzten n Rechnungen abfragen

```php
$lexware->get_last_invoices(int $count): array
```

### Rechnung anlegen

```php
$lexware->create_invoice(array $data, bool $finalized = false, string $precedingSalesVoucherId = ''): object
```

[Lexware Office Formatierung](https://developers.lexware.io/docs/#invoices-endpoint-create-an-invoice)

## Abschlagsrechnungen

### Abschlagsrechnung abfragen

```php
$lexware->get_down_payment_invoice(string $uuid): object
```

## Serienrechnungen

### Serienrechnung abfragen

```php
$lexware->get_recurring_template(string $uuid): object
```

### Alle Serienrechnungen abfragen

```php
$lexware->get_recurring_templates_all(): array
```

## Gutschriften

### Gutschrift abfragen

```php
$lexware->get_creditnote(string $uuid): object
```

### Gutschrift anlegen

```php
$lexware->create_creditnote(array $data, bool $finalized = false, string $precedingSalesVoucherId = ''): object
```

`$precedingSalesVoucherId` ist die UUID der offenen Rechnung, zu der die Gutschrift erstellt wird.
[Lexware Office Formatierung](https://developers.lexware.io/docs/#credit-notes-endpoint-credit-notes-properties)

`get_credit_note()` und `create_credit_note()` sind Aliase ohne `$precedingSalesVoucherId`.

## Angebote

### Angebot abfragen

```php
$lexware->get_quotation(string $uuid): object
```

### Angebot anlegen

```php
$lexware->create_quotation(array $data, bool $finalized = false): object
```

[Lexware Office Formatierung](https://developers.lexware.io/docs/#quotations-endpoint-quotations-properties)

## Auftragsbestätigungen

### Auftragsbestätigung abfragen

```php
$lexware->get_orderconfirmation(string $uuid): object
```

### Auftragsbestätigung anlegen

```php
$lexware->create_orderconfirmation(array $data, bool $finalized = false, string $precedingSalesVoucherId = ''): object
```

[Lexware Office Formatierung](https://developers.lexware.io/docs/#order-confirmations-endpoint-order-confirmations-properties)

## Lieferscheine

### Lieferschein abfragen

```php
$lexware->get_deliverynote(string $uuid): object
```

### Lieferschein anlegen

```php
$lexware->create_delivery_note(array $data, bool $finalized = false, string $precedingSalesVoucherId = ''): object
```

[Lexware Office Formatierung](https://developers.lexware.io/docs/#delivery-notes-endpoint-delivery-notes-properties)

## Mahnungen

### Mahnung abfragen

```php
$lexware->get_dunning(string $uuid): object
```

### Mahnung anlegen

```php
$lexware->create_dunning(array $data, string $precedingSalesVoucherId): object
```

`$precedingSalesVoucherId` ist die UUID der offenen Rechnung, zu der die Mahnung erstellt wird.
[Lexware Office Formatierung](https://developers.lexware.io/docs/#dunnings-endpoint-create-a-dunning)

## PDFs herunterladen

```php
$lexware->get_pdf(string $type, string $uuid, string $filename): bool
```

| Parameter   | Beschreibung                                                                                                                                   |
|-------------|------------------------------------------------------------------------------------------------------------------------------------------------|
| `$type`     | Belegart als Endpoint-Name, z. B. `invoices`, `credit-notes`, `down-payment-invoices` (oder `downpaymentinvoice`), `dunnings` (oder `dunning`) |
| `$uuid`     | UUID des Belegs                                                                                                                                |
| `$filename` | Lokaler Dateiname für das PDF                                                                                                                  |

Bei einer E-Rechnung wird zusätzlich die XML-Datei als `$filename.xml` abgelegt.
Für Entwürfe liefert Lexware Office kein PDF, die Methode gibt dann `false` zurück.

`get_invoice_pdf(string $uuid, string $filename): bool` ist veraltet und wird in einer künftigen Version entfernt. Nutze stattdessen `get_pdf('invoices', $uuid, $filename)`.
