<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationQuestionsSeeder extends Seeder
{
    public function run()
    {
        $questions = [
            'Сколько человек живет с вами?',
            'У вас есть дети до 18 лет?',
            'Какие у вас есть животные?',
            'Каков средний ежемесячный доход вашей семьи?',
            'Какой процент общего семейного дохода вы тратите на покупку косметики и товаров для дома (средства для уборки)?'
        ];

        foreach ($questions as $index => $questionText) {
            $questionId = DB::table('registration_questions')->insertGetId([
                'question' => $questionText,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $answers = [];
            switch ($questionText) {
                case 'Сколько человек живет с вами?':
                    $answers = ['1', '2', '3', '4', '5'];
                    break;
                case 'У вас есть дети до 18 лет?':
                    $answers = ['Да', 'Нет'];
                    break;
                case 'Какие у вас есть животные?':
                    $answers = ['Нет', 'Кошка', 'Собака', 'Грызуны', 'Попугай', 'Рыбки'];
                    break;
                case 'Каков средний ежемесячный доход вашей семьи?':
                    $answers = ['50-100 тыс рублей', '150-200 тыс рублей', '200-300 тыс рублей', 'Больше'];
                    break;
                case 'Какой процент общего семейного дохода вы тратите на покупку косметики и товаров для дома (средства для уборки)?':
                    $answers = ['10%', '25%', '40%', '50%', '75%'];
                    break;
            }

            foreach ($answers as $answer) {
                DB::table('registration_answers')->insert([
                    'question_id' => $questionId,
                    'answer' => $answer,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
