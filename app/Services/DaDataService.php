<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use MoveMoveIo\DaData\Enums\Language;
use MoveMoveIo\DaData\Facades\DaDataAddress;

class DaDataService
{

    public function standardizeAddress(string $address): array
    {
        try {
            $response = DaDataAddress::standardization($address);

            // Логирование ответа для отладки
            Log::info('DaData response:', ['response' => $response]);

            // Проверка, если адрес пустой или не содержит результатов
            if (empty($response) || !isset($response[0]['result']) || is_null($response[0]['result'])) {
                return ['error' => $this->getErrorDetails($response[0])];
            }

            return $response[0];
        } catch (\Exception $e) {
            // Логирование ошибки
            Log::error('DaData error:', ['error' => $e->getMessage()]);
            return ['error' => 'Ошибка при обращении к DaData: ' . $e->getMessage()];
        }
    }

    private function getErrorDetails(array $response): array
    {
        // Обязательные поля
        $requiredFields = [
            'city' => 'Город',
            'street' => 'Улица',
            'house' => 'Дом',
            'postal_code' => 'Почтовый индекс'
        ];

        $errorDetails = [];

        // Проверяем обязательные поля
        foreach ($requiredFields as $key => $label) {
            if (empty($response[$key])) {
                $errorDetails[$key] = $label . ' не указан или не распознан';
            }
        }

        // Проверяем необязательные поля
        if (isset($response['flat']) && empty($response['flat'])) {
            $errorDetails['flat'] = 'Квартира не указана или не распознана';
        }

        if (isset($response['entrance']) && empty($response['entrance'])) {
            $errorDetails['entrance'] = 'Подъезд не указан или не распознан';
        }

        // Формируем ответ с деталями ошибки
        $errorMessage = 'Адрес не распознан. Проблемные части: ' . implode(', ', array_values($errorDetails));

        return [
            'error' => $errorMessage,
            'details' => $errorDetails
        ];
    }


    public function getAddressSuggestions(string $query, int $count = 5): array
    {
        try {
            $suggestions = DaDataAddress::prompt($query, $count, Language::RU);

            return $suggestions;
        } catch (\Exception $e) {
            \Log::error('Error getting address suggestions:', ['error' => $e->getMessage()]);
            return ['error' => 'Ошибка получения подсказок адреса: ' . $e->getMessage()];
        }
    }

}
