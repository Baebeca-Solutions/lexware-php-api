#Übersicht
- [Übersicht - lexoffice-php-api](#-bersicht---lexoffice-php-api)
  * [Baebeca Solutions bei lexoffice.de](#baebeca-solutions-bei-lexofficede)
- [Dokumentation](#dokumentation)
  * [Klasse einbinden](#klasse-einbinden)
    + [Start Parameter](#start-parameter)
  * [Methoden](#methoden)
    + [Rechnungen](#rechnungen)
      - [Rechnung abfragen](#rechnung-abfragen)
      - [Alle Rechnungen abfragen](#alle-rechnungen-abfragen)
      - [Rechnung PDF herunterladen](#rechnung-pdf-herunterladen)
      - [Rechnung anlegen](#rechnung-anlegen)
    + [Kontakte](#kontakte)
      - [Kontakt abfragen](#kontakt-abfragen)
      - [Alle Kontakte abfragen](#alle-kontakte-abfragen)
      - [Kontakt aktualisieren](#kontakt-aktualisieren)
    + [Events / Webhooks](#events---webhooks)
      - [Event anlegen](#event-anlegen)
      - [Aktives Event abfragen](#aktives-event-abfragen)
      - [Alle aktiven Events abfragen](#alle-aktiven-events-abfragen)

<small><i><a href='http://ecotrust-canada.github.io/markdown-toc/'>Table of contents generated with markdown-toc</a></i></small>

---

# lexoffice-php-api
PHP Client für [Lexoffice](https://www.awin1.com/awclick.php?gid=368497&mid=13787&awinaffid=635216&linkid=2440770&clickref=) REST API

Wir lieben Automatisierung und die nahtlose Verzahnung von Systemen!

[Lexoffice](https://www.awin1.com/awclick.php?gid=368497&mid=13787&awinaffid=635216&linkid=2440770&clickref=) stellt seinen Kunden eine öffentliche API-Schnittstelle zur Verfügung über die 
bestehende Systeme integriert oder Prozesse automatisiert werden können. Für diesen Ansatz haben 
wir einen PHP-Client entwickelt und stellen diesen kostenfrei zur Verfügung. 

Alle Informationen weiteren findest du auf unserer [Projektseite](https://www.baebeca.de/softwareentwicklung/projekte/lexoffice-php-client/).
 
## Baebeca Solutions bei lexoffice.de
* [Public API Partner](https://www.lexoffice.de/partner/public-api/)
* [Integrationspartner](https://www.lexoffice.de/partner/public-api/integrationspartner/)

Die offizielle Lexoffice API-Dokumentation findest du [hier](https://developers.lexoffice.io/docs/).

[![Lexoffice](https://www.baebeca.de/wp-content/uploads/2019/06/lexoffice-Logo-RGB-e1560867468409.png)](https://www.awin1.com/cread.php?s=2440752&v=13787&q=368492&r=635216)

##Lizenz
Unsere Software wurde unter der "GNU Affero General Public License v3.0" Lizenz veröffentlicht. 
Dies bedeutet, dass Sie unsere Software gerne in Ihren Projekten und Produkten nutzen dürfen, 
solange Sie Ihr Projekt dann ebenso Quelloffen unter den in der Lizenz genannten Rahmenbedingungen 
zur Verfügung stellen.

Wenn Sie Ihre Lösung nicht veröffentlichen möchten, Support benötigen, individuelle 
Erweiterungen benötigen oder dieses Projekt einfach Unterstützen möchten, können Sie für einmalig 99,- netto
eine Lizenz zur Nutzung erhalten. Setzten Sie sich diesbezüglich einfach mit uns in [Verbindung]([Projektseite](https://www.baebeca.de/softwareentwicklung/projekte/lexoffice-php-client/)).

##API Account
Erstellen Sie in Ihrem [Lexoffice Account](https://www.awin1.com/awclick.php?gid=368497&mid=13787&awinaffid=635216&linkid=2440770&clickref=) einen API Key.

Einstellungen :: Erweiterungen :: lexoffice Public API :: "Schlüssel neu erstellen"

# Dokumentation

## Klasse einbinden
```php
<?php
// include the class file, check the correct folder
require_once (__DIR__.'/lexoffice-php-api.php');
   
// initiate client with your settings
$lexoffice = new lexoffice_client(array('api_key' => 'xyz'));
```
    
### Start Parameter
> api_key [string]

API Schlüssel
> callback [string]

Ihre Standard Callback URL für Webhooks von Lexoffice    
    
## Methoden

### Rechnungen

#### Rechnung abfragen
```php
$lexoffice->get_invoice( string $uuid ) : array
```
* uuid
  * Die eindeutige uuid des Eintrages der abgefragt werden soll

#### Alle Rechnungen abfragen
```php
$lexoffice->get_invoices_all() : array
```

#### Rechnung PDF herunterladen
```php
$lexoffice->get_invoice_pdf( string $uuid, string $filename) : bool
```
* uuid
  * Die eindeutige uuid des Eintrages der abgefragt werden soll
* filename
  * Der lokale Dateiname an dem das PDF abgelegt werden soll  

#### Rechnung anlegen
```php
$lexoffice->create_invoice( array $data [, bool $finalized = false ] ) : array
```
* data
  * Ein Array der Rechnungsdaten nach der nötigen [Lexoffice Formatierung](https://developers.lexoffice.io/docs/#invoices-properties)
  * Beispiel
    ```php
    array(
     'voucherDate' => substr(date('c'), 0, 19).'.000'.substr(date('c'), 19),
     'introduction' => 'Einleitungstext',
     'remark' => "Fußzeile\r\nMehrzeilig",
     'address' => array(
     	#'contactId' => '<id>',
     	'name' => 'Frau Jane Doe',
     	'street' => 'Str. 1',
     	'zip' => '12345',
     	'city' => 'Stadt',
     	'countryCode' => 'DE',
      ),
      'lineItems' => array(
      	array(
       		'type' => 'custom',
       		'name' => 'Produktname',
       		'description' => 'Beschreibung',
       		'quantity' => 1,
       		'unitName' => 'Stück',
       		'unitPrice' => array(
       			'currency' => 'EUR',
       			'netAmount' => 10.99,
           		'taxRatePercentage' => 19,
       		),
       		#'discountPercentage' => 0,
       	),
      ),
      'totalPrice' => array(
       	'currency' => 'EUR',
       	#'totalDiscountAbsolute' => 0,
       	#'totalDiscountPercentage' => 0,
      ),
      'taxConditions' => array(
      	'taxType' => 'net',
      ),
      'shippingConditions' => array(
      	'shippingDate' => $date,
      	'shippingType' => 'delivery',
      ),
      'paymentConditions' => array(
      	'paymentTermLabel' => 'Vorkasse',
      	'paymentTermDuration' => 1,
      ),
    )
    ```
* finalized
  * Entscheidet ob die Rechnung fertiggestellt werden soll.

### Kontakte

#### Kontakt abfragen
```php
$lexoffice->get_contact( string $uuid ) : array
```
* uuid
  * Die eindeutige uuid des Eintrages der abgefragt werden soll
  
#### Alle Kontakte abfragen
```php
$lexoffice->get_contacts_all() : array
```
    
#### Kontakt aktualisieren
```php
$lexoffice->update_contact( string $uuid, array $data) : array
```
* uuid
  * Die eindeutige uuid des Eintrages der abgefragt werden soll
* data
  * Ein Array der Rechnungsdaten nach der nötigen [Lexoffice Formatierung](https://developers.lexoffice.io/docs/#contact-properties)  

    
### Events / Webhooks
     
#### Event anlegen
```php
$lexoffice->create_event( string $event [, bool $callback = false ] ) : array|bool
```
* event
  * Mögliche sind alle von Lexoffice [angebeotenen Events](https://developers.lexoffice.io/docs/#event-subscriptions-endpoint-event-types).
* callback
  * Die Callback URL für diesen Aufruf. Wenn keine angegeben wird, wird die Standard URL aus der initialisierung genutzt.  
  
#### Aktives Event abfragen
```php
$lexoffice->get_event( string $uuid ) : array
```
* uuid
  * Die eindeutige uuid des Eintrages der abgefragt werden soll

#### Alle aktiven Events abfragen
```php
$lexoffice->get_events_all() : array
```

