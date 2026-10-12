# Upgrade-Hinweise

<!-- TOC -->
* [2.3.x → 2.4.0](#23x--240)
* [Legacy-Branch (ohne Composer) → aktuelle Version](#legacy-branch-ohne-composer--aktuelle-version)
<!-- TOC -->

## 2.3.x → 2.4.0

### Keine Breaking Changes

Alle bestehenden Aufrufe funktionieren ohne Anpassung. Der neue Parameter `$postal_code` ist bei allen geänderten Methoden optional mit dem Standardwert `''`.

### Neue Funktion: PLZ-basierte Drittgebiet-Erkennung

Umsatzsteuerliche Sondergebiete innerhalb von EU-Ländern (z. B. Helgoland, Kanarische Inseln, Livigno) werden als Drittgebiet behandelt, sofern die PLZ der Liefer- bzw. Leistungsadresse übergeben wird.

Neue Methode: `is_excluded_postal_zone(string $country_code, string $postal_code): bool`, siehe [Steuern & OSS](docs/taxes.md#plz-sonderzone-prüfen-drittgebiet).

Neuer optionaler letzter Parameter `string $postal_code = ''` bei:

- `is_european_member()`
- `get_needed_voucher_booking_id()`
- `get_needed_tax_type()`

### Was du in deiner Integration prüfen solltest

Suche in deinem Code nach Aufrufen dieser drei Methoden und ergänze die PLZ als letzten Parameter:

```php
// Vorher (weiterhin gültig):
$lexware->get_needed_voucher_booking_id($taxrate, $countryCode, $date, $hasVatId, $isB2B, $isPhysical);

// Nachher (mit PLZ, empfohlen):
$postalCode = $address['zip'] ?? '';
$lexware->get_needed_voucher_booking_id($taxrate, $countryCode, $date, $hasVatId, $isB2B, $isPhysical, $postalCode);
```

Die PLZ stammt aus der **Lieferadresse** des Kunden (Ort der Lieferung bzw. Leistungserbringung), nicht aus der Rechnungsadresse.
Ohne PLZ verhält sich die Methode wie in 2.3.x.

## Legacy-Branch (ohne Composer) → aktuelle Version

Der Umstieg vom Branch `php-7.4-legacy-no-composer` enthält Breaking Changes.
Mit den folgenden Schritten hebst du deinen Code auf die aktuelle Version, am einfachsten per „Suchen / Ersetzen“.

### 1) Sourcen via Composer laden

Siehe Abschnitt [Composer](README.md#composer).

### 2) Namespaces nutzen

Es gibt zwei Klassen im Namespace `Baebeca`, die du global einbinden kannst:

```php
use \Baebeca\LexwareApi;
use \Baebeca\LexwareException;
```

Dann sind die Änderungen:

- `new lexoffice_client(` → `new LexwareApi(`
- `catch (lexoffice_exception ` → `catch (LexwareException `

Ohne globales `use`:

- `new lexoffice_client(` → `new \Baebeca\LexwareApi(`
- `catch (lexoffice_exception ` → `catch (\Baebeca\LexwareException `

### 3) Präfix der Exception-Message anpassen

Wenn du `$e->getMessage()` auswertest:

- `lexoffice-php-api:` → `LexwareApi:`

### 4) Methode für Exception-Details

- `->get_error()` → `->getError()`

### 5) Geänderte Texte in Exception-Messages

Das Wort „lexoffice“ ist jeweils durch „lexware“ ersetzt:

- `action not possible due a lexoffice contract issue` → `action not possible due a lexware contract issue`
- `Authenticated but insufficient scope or insufficient access rights in lexoffice` → `Authenticated but insufficient scope or insufficient access rights in lexware`
- `missing OSS configuration in lexoffice account` → `missing OSS configuration in lexware account`

### 6) Optional: lokale Variablen umbenennen

Viele Projekte nutzen Variablen wie `$lexoffice->` oder `$lxo->`. Wir empfehlen in diesem Schritt eine Umbenennung, z. B.:

- `$lexoffice = new ` → `$lexware = new `
- `$lexoffice->` → `$lexware->`
