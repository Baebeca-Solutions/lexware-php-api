# Belege (Buchhaltung)

## Beleg anlegen

```php
$lexoffice->create_voucher(array $data): object
```

`$data` im [Lexware Office Format](https://developers.lexware.io/docs/#vouchers-endpoint-create-a-voucher).
Die passende Buchungskategorie liefert [`get_needed_voucher_booking_id()`](taxes.md#buchungskategorie-bestimmen).

## Beleg abfragen

```php
$lexoffice->get_voucher(string $uuid): object
```

## Belege abfragen

```php
$lexoffice->get_vouchers(
    string $type = 'invoice,creditnote,orderconfirmation',
    string $state = 'draft,open,paid,paidoff,voided,accepted,rejected',
    string $archived = 'both',
    string $date_from = '',
    string $date_to = ''
): array
```

| Parameter    | Beschreibung                                 |
|--------------|----------------------------------------------|
| `$type`      | Ein oder mehrere Belegtypen, kommagetrennt   |
| `$state`     | Ein oder mehrere Status, kommagetrennt       |
| `$archived`  | `both`, `true` oder `false`                  |
| `$date_from` | Belegdatum ab einschließlich (`YYYY-MM-DD`)  |
| `$date_to`   | Belegdatum bis einschließlich (`YYYY-MM-DD`) |

Die Methode lädt alle Seiten. Ab 10.000 Treffern teilt sie die Abfrage automatisch in kleinere Zeiträume auf.

## Zahlungen eines Belegs abfragen

```php
$lexoffice->get_voucher_payments(string $uuid): object
```

## Belegdatei hochladen (nicht zugeordnet)

```php
$lexoffice->upload_file(string $file): object
```

Lädt eine Datei (PDF, JPG, PNG oder XML, max. 5 MB) hoch, ohne sie einem Beleg zuzuordnen. Rückgabe ist die Datei-ID von Lexware Office.

```php
$lexoffice->upload_file(__DIR__.'/invoice.pdf');

// stdClass Object (
//     [id] => 221fd5ed-5547-4bd3-b7c2-9796c1a0e4a6
// )
```

## Belegdatei hochladen (zugeordnet)

```php
$lexoffice->upload_voucher(string $uuid, string $file): object
```

Lädt eine Datei hoch und ordnet sie dem Beleg `$uuid` zu. Rückgabe ist die Datei-ID von Lexware Office.

## Belegdateien herunterladen

```php
$lexoffice->get_voucher_files(string $uuid, string $filename_prefix): array
```

Speichert alle Dateien des Belegs unter `$filename_prefix` mit fortlaufender Nummer und Dateiendung. Rückgabe ist ein Array der Dateinamen.
Bei einer E-Rechnung werden die XML-Datei und die von Lexware Office erzeugte PDF-Ansicht gespeichert.

```php
$lexoffice->get_voucher_files($uuid, '/files/IN_1234');

// array (
//     [0] => /files/IN_1234_1.jpg
//     [1] => /files/IN_1234_2.png
//     [2] => /files/IN_1234_3.pdf
// )
```
