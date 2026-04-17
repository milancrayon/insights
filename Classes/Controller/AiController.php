<?php
declare(strict_types=1);

namespace T3element\Insights\Controller;

use Psr\Http\Message\ResponseInterface;
use T3element\Insights\Domain\Repository\ConfigRepository;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AiController
{
    protected ConfigRepository $configRepository;
    protected RequestFactory $requestFactory;

    public function __construct(
        ConfigRepository $configRepository,
        RequestFactory $requestFactory
    ) {
        $this->configRepository = $configRepository;
        $this->requestFactory = $requestFactory;
    }

    public function generateAction(): ResponseInterface
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $prompt = $input['prompt'] ?? '';

        if (empty($prompt)) {
            return new JsonResponse(['error' => 'Prompt is required'], 400);
        }

        try {
            // Get AI settings from Config model
            // We search for the first config record
            $config = $this->configRepository->findAll()->getFirst();
            if (!$config) {
                return new JsonResponse(['error' => 'AI Configuration not found. Please create a Configuration record in the Insights module.'], 404);
            }

            $aiOptions = $config->getAioptions();
            if (empty($aiOptions)) {
                return new JsonResponse(['error' => 'AI options are not configured.'], 404);
            }

            // If it's a string, attempt decodig
            if (is_string($aiOptions)) {
                $decoded = json_decode($aiOptions, true);
                if (is_string($decoded)) {
                    $decoded = json_decode($decoded, true);
                }
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $aiOptions = $decoded;
                } else {
                    // Fallback: if it's a non-JSON string, it's invalid for our needs
                    return new JsonResponse(['error' => 'Invalid AI configuration format (JSON expected).'], 500);
                }
            }

            if (!is_array($aiOptions)) {
                return new JsonResponse(['error' => 'AI configuration must be an array.'], 500);
            }

            $currentagent = null;
            $activeAi = '';
            foreach ($aiOptions as $aiop) {
                if (($aiop['active'] ?? 0) == 1) {
                    $activeAi = $aiop['agent'] ?? '';
                    $currentagent = $aiop;
                    break;
                }
            }

            if (empty($activeAi)) {
                return new JsonResponse(['error' => 'No active AI agent found in configuration.'], 404);
            }

            if ($activeAi === 'gemini') {
                $results = $this->callGemini($prompt, $currentagent);
            } else {
                $results = $this->callOpenAI($prompt, $currentagent);
            }

            return new JsonResponse(['results' => $results]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    private function callOpenAI(string $prompt, array $options): array
    {
        $apiKey = $options['apikey'] ?? '';
        if (empty($apiKey)) {
            throw new \Exception('OpenAI API Key is missing in AI Configuration.');
        }

        $url = 'https://api.openai.com/v1/chat/completions';
        $payload = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'n' => 5, // Generate 5 versions
            'temperature' => 0.7
        ];

        $response = $this->requestFactory->request($url, 'POST', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($payload)
        ]);

        if ($response->getStatusCode() !== 200) {
            $errorData = json_decode($response->getBody()->getContents(), true);
            throw new \Exception('OpenAI Error: ' . ($errorData['error']['message'] ?? 'Unknown error'));
        }

        $responseData = json_decode($response->getBody()->getContents(), true);
        $results = [];
        foreach ($responseData['choices'] as $choice) {
            $results[] = trim($choice['message']['content']);
        }

        return $results;
    }

    private function callGemini(string $prompt, array $options): array
    {
        $apiKey = $options['apikey'] ?? '';
        if (empty($apiKey)) {
            throw new \Exception('Gemini API Key is missing in AI Configuration.');
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $apiKey;
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'candidateCount' => 1,
                'maxOutputTokens' => 1024,
                'temperature' => 0.7,
            ]
        ];

        $response = $this->requestFactory->request($url, 'POST', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($payload)
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Gemini Error: ' . $response->getReasonPhrase());
        }

        $responseData = json_decode($response->getBody()->getContents(), true);
        $results = [];
        if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            $results[] = trim($responseData['candidates'][0]['content']['parts'][0]['text']);
        }

        return $results;
    }
}
