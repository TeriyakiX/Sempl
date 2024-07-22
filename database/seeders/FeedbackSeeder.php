<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
{
    public function run()
    {
        // Создание вопросов
        $questions = [
            'Какую главную особенность вы ищете в ополаскивателе для рта?',
            'Какой вкус вам предпочитается?',
            'Как вы оцениваете упаковку продукта?'
        ];

        foreach ($questions as $question) {
            DB::table('feedback_questions')->insert([
                'question' => $question,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Получение ID вопросов
        $questionIds = DB::table('feedback_questions')->pluck('id')->toArray();

        // Ответы для каждого вопроса
        $answers = [
            $questionIds[0] => ['Без спирта', 'Мелкодисперсный', 'С освежающим эффектом'],
            $questionIds[1] => ['Мятный', 'Цитрусовый', 'Травяной'],
            $questionIds[2] => ['Удобная', 'Экологичная', 'Долговечная']
        ];

        foreach ($answers as $questionId => $answerList) {
            foreach ($answerList as $answer) {
                DB::table('feedback_answers')->insert([
                    'question_id' => $questionId,
                    'answer' => $answer,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
