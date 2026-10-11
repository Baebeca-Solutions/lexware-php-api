# Artikel

`$data` erwartet jeweils ein Array oder Objekt im [Lexware Office Format](https://developers.lexware.io/docs/#articles-endpoint).

## Artikel abfragen

```php
$lexware->get_article(string $uuid): object
```

## Alle Artikel abfragen

```php
$lexware->get_articles_all(): array
```

## Artikel anlegen

```php
$lexware->create_article(array|object $data): object
```

## Artikel aktualisieren

```php
$lexware->update_article(string $uuid, array|object $data): object
```

## Artikel löschen

```php
$lexware->delete_article(string $uuid): bool
```
