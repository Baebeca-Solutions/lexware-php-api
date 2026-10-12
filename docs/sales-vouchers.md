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
$lexoffice->get_invoice(string $uuid): object
```

### Alle Rechnungen abfragen

```php
$lexoffice->get_invoices_all(): array
```

### Die letzten n Rechnungen abfragen

```php
$lexoffice->get_last_invoices(int $count): array
```

### Rechnung anlegen

```php
$lexoffice->create_invoice(array $data, bool $finalized = false): object
```

[Lexware Office Formatierung](https://developers.lexware.io/docs/#invoices-endpoint-create-an-invoice)

## Abschlagsrechnungen

### Abschlagsrechnung abfragen

```php
$lexoffice->get_down_payment_invoice(string $uuid): object
```

## Serienrechnungen

### Serienrechnung abfragen

```php
$lexoffice->get_recurring_template(string $uuid): object
```

### Alle Serienrechnungen abfragen

```php
$lexoffice->get_recurring_templates_all(): array
```

## Gutschriften

### Gutschrift abfragen

```php
$lexoffice->get_creditnote(string $uuid): object
```

### Gutschrift anlegen

```php
$lexoffice->create_creditnote(array $data, bool $finalized = false, string $linked_invoice_id = ''): object
```

`$linked_invoice_id` ist die UUID der offenen Rechnung, zu der die Gutschrift erstellt wird.
[Lexware Office Formatierung](https://developers.lexware.io/docs/#credit-notes-endpoint-credit-notes-properties)

`get_credit_note()` und `create_credit_note()` sind Aliase ohne `$linked_invoice_id`.

## Angebote

### Angebot abfragen

```php
$lexoffice->get_quotation(string $uuid): object
```

### Angebot anlegen

```php
$lexoffice->create_quotation(array $data, bool $finalized = false): object
```

[Lexware Office Formatierung](https://developers.lexware.io/docs/#quotations-endpoint-quotations-properties)

## Auftragsbestätigungen

### Auftragsbestätigung abfragen

```php
$lexoffice->get_orderconfirmation(string $uuid): object
```

### Auftragsbestätigung anlegen

```php
$lexoffice->create_orderconfirmation(array $data, bool $finalized = false): object
```

[Lexware Office Formatierung](https://developers.lexware.io/docs/#order-confirmations-endpoint-order-confirmations-properties)

## Lieferscheine

### Lieferschein abfragen

```php
$lexoffice->get_deliverynote(string $uuid): object
```

### Lieferschein anlegen

```php
$lexoffice->create_delivery_note(array $data, bool $finalized = false): object
```

[Lexware Office Formatierung](https://developers.lexware.io/docs/#delivery-notes-endpoint-delivery-notes-properties)

## PDFs herunterladen

```php
$lexoffice->get_pdf(string $type, string $uuid, string $filename): bool
```

| Parameter   | Beschreibung                                                                                                      |
|-------------|-------------------------------------------------------------------------------------------------------------------|
| `$type`     | Belegart als Endpoint-Name, z. B. `invoices`, `credit-notes`, `down-payment-invoices` (oder `downpaymentinvoice`) |
| `$uuid`     | UUID des Belegs                                                                                                   |
| `$filename` | Lokaler Dateiname für das PDF                                                                                     |

Bei einer E-Rechnung wird zusätzlich die XML-Datei als `$filename.xml` abgelegt.
Für Entwürfe liefert Lexware Office kein PDF, die Methode gibt dann `false` zurück.

`get_invoice_pdf(string $uuid, string $filename): bool` ist veraltet und wird in einer künftigen Version entfernt. Nutze stattdessen `get_pdf('invoices', $uuid, $filename)`.
