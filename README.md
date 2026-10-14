# osd84/discord-bundle

Bundle Symfony qui envoie des alertes dans un salon Discord par webhook.
Une seule classe, `curl` de PHP, aucune dépendance HTTP en plus.

*Symfony bundle that posts alerts to a Discord channel through a webhook. One class, PHP curl, no extra HTTP client.*

## Installation

```bash
composer require osd84/discord-bundle
```

Sans Symfony Flex, déclarer le bundle dans `config/bundles.php` :

```php
Osd84\DiscordBundle\Osd84DiscordBundle::class => ['all' => true],
```

## Configuration

```yaml
# config/packages/osd84_discord.yaml
osd84_discord:
    webhook: '%env(DISCORD_WEBHOOK)%'
```

Webhook vide : aucun message n'est envoyé, `send()` rend `false`. Pratique en dev et en test.
Le webhook est un secret : il reste dans la config de chaque appli (`.env.local`, `.env.local.php`), jamais dans le code.

## Utilisation

```php
use Osd84\DiscordBundle\Discord;

public function __construct(private Discord $discord) {}

$this->discord->send('[ALERTE] compte bloque : admin@exemple.fr');
```

- `send()` rend `true` si Discord a accepté le message, `false` sinon.
- Elle ne lève jamais d'exception : un Discord injoignable ne fait pas tomber l'appli. Délai maximum : 5 secondes.
- Un message de plus de 2000 caractères (limite de Discord) est coupé.

## Tests

```bash
composer install
php vendor/bin/phpunit
```

## Licence

MIT.
