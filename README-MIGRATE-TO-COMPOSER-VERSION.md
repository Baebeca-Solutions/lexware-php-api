Wenn du bisher eine der Branches "php-x.x-legacy-no-composer" verwendet hast gibt es einige breaking changes wenn du auf die aktuelle Version migrieren möchtst.

Mit folgenden Migrationsschritte kannst du deinen Code auf die aktuelle Version anheben.
Hier wäre in deinem Code "Suchen / Ersetzen" das Mittel der Wahl.

# 1) Sourcen via Composer laden
Siehe Abschnitt "Composer" in [README.md](./README.md)

# 2) Nutzung von Namespaces
Wir nutzen zwei Namespaces welche du global einbinden kannst. 
```PHP
use \Baebeca\LexwareApi;
use \Baebeca\LexwareException;
```
Dann sind die Änderungen:
- ```new lexoffice_client(``` => ```new LexwareApi(``` 
- ```catch (lexoffice_exception ``` => ```catch (LexwareException ```

Wenn du die Namespaces nicht global setzt sind die Änderunge:
- ```new lexoffice_client(``` => ```new \Baebeca\LexwareApi(```
- ```catch (lexoffice_exception ``` => ```catch (\Baebeca\LexwareException ```

# 3) Anpassung Exception Message
Wenn du die Message der Exception (```$e->getMessage()```) auswertest musst du wiefolgt ersetzen
- ```lexoffice-php-api:``` => ```LexwareApi:``` 

# 4) Neue Exception Detail Methode
Nutze die neue Methode um die Details zu erhalten
- ```->get_error()``` => ```->getError()``` 

# 5) Geänderter String in Exception Message
Hier wurde jeweils das Wort "lexoffice" durch "lexware" ersetzt.
- ```action not possible due a lexoffice contract issue``` => ```action not possible due a lexware contract issue```
- ```Authenticated but insufficient scope or insufficient access rights in lexoffice``` => ```Authenticated but insufficient scope or insufficient access rights in lexware```
- ```missing OSS configuration in lexoffice account``` => ```missing OSS configuration in lexware account```

# 5) Optional - Lokale Variablen
Es gibt viele Projekte die Variablen wie ```$lexoffice->``` oder ```$lxo->``` nutzen. 
Bedingt durch die Namensänderung würden wir in diesem Step direkt eine ersetzung zu 
Beispielweise ```$lexware->``` empfhelen.  

- ```$lexoffice = new ``` => ```$lexware = new ```
- ```$lexoffice->``` => ```$lexware->``` 