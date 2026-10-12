# Account

## Account abfragen

```php
$lexoffice->get_profile(): object
```

Das Ergebnis wird pro Instanz zwischengespeichert, weitere Aufrufe lösen keinen erneuten API-Request aus.

Beispiel-Rückgabe:

```json
{
  "organizationId": "42b1fbdd-c4e0-4bb4-8c20-1d3d0b9432e8",
  "companyName": "Testfirma GmbH",
  "created": {
    "userName": "Frau Erika Musterfrau",
    "userEmail": "erika.musterfrau@testfirma.de",
    "date": "2017-01-03T13:15:45+0100"
  },
  "connectionId": "3dea098a-fae5-4458-a85c-f97965966c25",
  "features": [
    "cashbox"
  ],
  "subscriptionStatus": "active",
  "taxType": "net",
  "isSmallBusiness": false
}
```
