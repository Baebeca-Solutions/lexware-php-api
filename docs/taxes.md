# Steuern & OSS

Alle Methoden erwarten Ländercodes zweistellig (ISO 3166-1 alpha-2) und Datumswerte als Unix-Timestamp des Rechnungs- bzw. Belegdatums.

<!-- TOC -->
* [Kleinunternehmer](#kleinunternehmer)
* [EU – Innergemeinschaftlicher Raum](#eu--innergemeinschaftlicher-raum)
* [Steuerart bestimmen](#steuerart-bestimmen)
* [Buchungskategorie bestimmen](#buchungskategorie-bestimmen)
* [Mögliche Steuersätze abfragen](#mögliche-steuersätze-abfragen)
* [Steuersatz prüfen](#steuersatz-prüfen)
* [USt-IdNr. formal prüfen](#ust-idnr-formal-prüfen)
* [One-Stop-Shop (OSS)](#one-stop-shop-oss)
* [Buchungskategorien](#buchungskategorien)
<!-- TOC -->

## Kleinunternehmer

```php
$lexoffice->is_tax_free_company(): bool
```

## EU – Innergemeinschaftlicher Raum

```php
$lexoffice->is_european_member(string $country_code, int $date): bool
```

## Steuerart bestimmen

```php
$lexoffice->get_needed_tax_type(string $customer_country_code, string $vat_id, bool $physical_good, int $timestamp): string
```

Liefert den Wert für `taxConditions.taxType` eines Verkaufsbelegs: `net`, `intraCommunitySupply`, `thirdPartyCountryDelivery` oder `thirdPartyCountryService`.

| Parameter                | Beschreibung                                    |
|--------------------------|-------------------------------------------------|
| `$customer_country_code` | Land des Kunden                                 |
| `$vat_id`                | USt-IdNr. des Kunden, leer wenn nicht vorhanden |
| `$physical_good`         | `true` für Waren, `false` für Dienstleistungen  |
| `$timestamp`             | Rechnungsdatum                                  |

## Buchungskategorie bestimmen

```php
$lexoffice->get_needed_voucher_booking_id(float $taxrate, string $country_code, int $date, bool $euopean_vatid, bool $b2b_business, bool $physical_good = true): string
```

Liefert die ID der Buchungskategorie für [`create_voucher()`](vouchers.md#beleg-anlegen), inklusive OSS-Kategorien. Für den Beleg-Import mit OSS reicht diese Methode aus.

| Parameter        | Beschreibung                                            |
|------------------|---------------------------------------------------------|
| `$taxrate`       | Verwendeter Steuersatz, wird auf Gültigkeit geprüft     |
| `$country_code`  | Rechnungsland                                           |
| `$date`          | Rechnungsdatum                                          |
| `$euopean_vatid` | Ob die USt-IdNr. des Kunden vorliegt                    |
| `$b2b_business`  | `true` für Geschäftskunden, `false` für Endkunden       |
| `$physical_good` | `true` für Waren, `false` für digitale Dienstleistungen |

## Mögliche Steuersätze abfragen

```php
$lexoffice->get_taxrates(string $country_code, int $date, bool $return_unsorted = false): array
```

```php
// $return_unsorted = false
['default' => 19, 'reduced' => [7], 'nullrate' => false]

// $return_unsorted = true
[19, 7]
```

## Steuersatz prüfen

```php
$lexoffice->check_taxrate(float $taxrate, string $country_code, int $date): bool
```

## USt-IdNr. formal prüfen

```php
$lexoffice->valid_vat_id(string $vat_id): bool
```

Prüft Länderpräfix und Länge der USt-IdNr. Eine Online-Prüfung der Gültigkeit findet nicht statt.

## One-Stop-Shop (OSS)

Hintergrund: [One-Stop-Shop EU-Regelung in Lexware Office](https://help.lexware.de/de-form/articles/5207137-one-stop-shop-eu-regelung-belege-an-privatpersonen-im-eu-ausland)

### OSS-Einstellung prüfen

```php
$lexoffice->is_oss_needed(string $country_code, int $date): string|false
```

| Rückgabe        | Bedeutung                            |
|-----------------|--------------------------------------|
| `false`         | Kein OSS nötig                       |
| `'origin'`      | Deutsche Steuersätze verwenden       |
| `'destination'` | Steuersätze des Ziellandes verwenden |

Fehlt die OSS-Konfiguration im Lexware Office Account, wirft die Methode eine `lexoffice_exception`.

### OSS-Buchungskategorie bestimmen

```php
$lexoffice->get_oss_voucher_category(string $country_code, int $date, int $booking_category = 1, float $taxrate = 0): string
```

`$booking_category`: `1` = Fernverkauf (Waren), `2` = Elektronische Dienstleistung.
Die zugehörigen Kategorie-IDs stehen in der Methode in [`lexoffice-php-api.php`](../lexoffice-php-api.php).

## Buchungskategorien

Die vollständige Liste der Buchungskategorien deines Accounts liefert der Lexware Office Endpoint [Posting Categories](https://developers.lexware.io/docs/#posting-categories-endpoint).
Jede Kategorie hat den Typ `income` (Ausgangsbelege, eingehender Geldfluss) oder `outgo` (Eingangsbelege, ausgehender Geldfluss).
