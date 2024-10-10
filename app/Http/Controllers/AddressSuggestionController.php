<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MoveMoveIo\DaData\Enums\Language;
use MoveMoveIo\DaData\Facades\DaDataAddress;

class AddressSuggestionController extends Controller
{

    public function suggest(Request $request)
    {
        $query = $request->input('query');
        $count = $request->input('count', 5); // Количество подсказок, по умолчанию 5

        if (empty($query)) {
            return response()->json(['error' => 'Запрос не может быть пустым'], 400);
        }
        // Получаем подсказки из DaData
        $suggestions = DaDataAddress::prompt($query, $count, Language::RU);

        // Фильтруем поля
        $filteredSuggestions = array_map(function ($suggestion) {
            return [
                'value' => $suggestion['value'],
                'unrestricted_value' => $suggestion['unrestricted_value'],
                'postal_code' => $suggestion['data']['postal_code'],
                'city' => $suggestion['data']['city'],
                'street' => $suggestion['data']['street'],
                'geo_lat' => $suggestion['data']['geo_lat'],
                'geo_lon' => $suggestion['data']['geo_lon'],
            ];
        }, $suggestions);

        return response()->json(['suggestions' => $filteredSuggestions]);

    }
}
