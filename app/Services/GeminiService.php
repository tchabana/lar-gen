<?php

namespace App\Services;

use GuzzleHttp\Client;

class GeminiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10.0,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('GEMINI_API_KEY'),
            ],
        ]);
    }

    public function getMessagesFromApi(string $message)
    {
        try {
            $response = $this->client->post('/messages/send', [ // Vérifiez l'endpoint correct
                'json' => ['message' => $message],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi du message à Gemini : ' . $e->getMessage());
            return ['error' => 'Erreur de communication avec l\'API Gemini.'];
        }
    }
    public function getMessages()
    {
        try {
            $response = $this->client->get('/messages/received'); // Vérifiez l'endpoint exact de l'API.

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Générer du contenu en utilisant l'API Gemini.
     *
     * @param string $prompt Le texte de l'invite à utiliser pour générer du contenu.
     * @return array La réponse de l'API.
     */
    public function getAllMessages()
    {
        try {
            // Récupérer les messages reçus
            $received = $this->getMessages();

            // Optionnel : ajouter une méthode pour récupérer les messages envoyés
            $sent = $this->getSentMessages();

            // Fusionner les deux types de messages
            return [
                'received' => $received,
                'sent' => $sent,
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Récupérer les messages envoyés via l'API Gemini.
     * (Assurez-vous que l'API dispose de cet endpoint).
     */
    public function getSentMessages()
    {
        try {
            $response = $this->client->get('/messages/sent');

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

}
