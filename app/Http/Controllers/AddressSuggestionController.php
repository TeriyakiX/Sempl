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
        $filteredSuggestions = array_map(function ($suggestion) {
            return [
                "value" => $suggestion['data']['value'],
                'city' => $suggestion['data']['city'],
                'street_with_type' => $suggestion['data']['street_with_type'],
                'house' => $suggestion['data']['house'] ?? null,
            ];
        }, $suggestions['suggestions']); // Note the access to 'suggestions' key

        return response()->json(['suggestions' => $filteredSuggestions]);
    }
}
