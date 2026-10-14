<?php

namespace Osd84\DiscordBundle;

/**
 * Un message dans un salon Discord, par webhook.
 * curl de PHP, pas de client HTTP en plus. Webhook vide = on ne fait rien.
 * Un Discord muet ou en erreur ne fait jamais tomber l'appli : send() rend false, c'est tout.
 */
class Discord
{
    /** Discord refuse un message de plus de 2000 caracteres. */
    public const LONGUEUR_MAX = 2000;

    private const TIMEOUT_SECONDES = 5;

    public function __construct(
        private string $webhook = '',
    ) {
    }

    public function estActif(): bool
    {
        return $this->webhook !== '';
    }

    /** true si Discord a accepte le message. */
    public function send(string $message): bool
    {
        if (!$this->estActif()) {
            return false;
        }

        $ch = curl_init($this->webhook);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['content' => mb_substr($message, 0, self::LONGUEUR_MAX)]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT_SECONDES);
        curl_exec($ch);
        $codeHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $erreur = curl_errno($ch);
        curl_close($ch);

        // Discord repond 204 (sans contenu) quand le message est publie
        return $erreur === 0 && $codeHttp >= 200 && $codeHttp < 300;
    }
}
