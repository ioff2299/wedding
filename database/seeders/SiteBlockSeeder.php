<?php

namespace Database\Seeders;

use App\Models\SiteBlock;
use App\Support\WeddingFormDefaults;
use Illuminate\Database\Seeder;

class SiteBlockSeeder extends Seeder
{
    public function run(): void
    {
        $blocks = [
            [
                'key'   => 'hero',
                'title' => 'Главный блок',
                'content' => [
                    'bride_name'   => 'Мария',
                    'groom_name'   => 'Александр',
                    'tagline'      => 'We are getting married!',
                    'date'         => '25/06/2026',
                    'save_date_text' => 'SAVE THE DATE',
                    'intro_text'   => 'Мы верим, что счастье становится полным, когда им делишься с самыми близкими людьми.',
                ],
            ],
            [
                'key'   => 'between_text',
                'title' => 'Текстовый блок',
                'content' => [
                    'text' => 'Совсем скоро начнется важный и радостный день нашей семьи. Будем счастливы разделить его с вами.',
                ],
                'is_visible' => true,
            ],
            [
                'key'   => 'location',
                'title' => 'Локация',
                'content' => [
                    'venues' => [
                        [
                            'time'     => '13:00',
                            'name'     => 'Отель «Название»',
                            'address'  => 'г. Город, ул. Название, д. 1',
                            'map_link' => '#',
                        ],
                        [
                            'time'     => '17:00',
                            'name'     => 'Ресторан «Sicilia»',
                            'address'  => 'г. Город, ул. Название, д. 2',
                            'map_link' => '#',
                        ],
                    ],
                    'map_address'      => '',
                    'map_lat'          => null,
                    'map_lng'          => null,
                    'location_images'  => [],
                ],
            ],
            [
                'key'   => 'timing',
                'title' => 'Тайминг',
                'content' => [
                    'events' => [
                        [
                            'time'        => '13:00',
                            'title'       => 'Сбор гостей',
                            'description' => 'У гостиницы «Название»',
                        ],
                        [
                            'time'        => '13:30 – 14:30',
                            'title'       => 'Церемония бракосочетания',
                            'description' => '',
                        ],
                        [
                            'time'        => '',
                            'title'       => '',
                            'description' => 'После церемонии пройдёт фотосессия. Вы сможете присоединиться нам и насладиться свободным временем.',
                        ],
                        [
                            'time'        => '17:00',
                            'title'       => 'Праздничный ужин',
                            'description' => 'Ресторан «Sicilia»',
                        ],
                        [
                            'time'        => '',
                            'title'       => '',
                            'description' => 'Если у вас не получится присутствовать на церемонии, мы с пониманием отнесёмся к этому и будем рады видеть вас в ресторане ♡',
                        ],
                    ],
                ],
            ],
            [
                'key'   => 'dress_code',
                'title' => 'Дресс-код',
                'content' => [
                    'intro'       => 'Дорогие гости!',
                    'description' => "Будем рады, если вы поддержите цветовую палитру нашего праздника.\n\nДля мужчин будет достаточно классического костюма.",
                    'outro'       => 'Спасибо за то, что помогаете сделать наш день особенно красивым.',
                    'colors'      => ['#7C3D2A', '#C4A882', '#5C6B4A', '#E8DDD0', '#B5A89A', '#3D2B1F'],
                ],
            ],
            [
                'key'   => 'details',
                'title' => 'Детали',
                'content' => [
                    'wishes_text' => "Пожалуйста, не дарите нам цветы — сразу после свадьбы мы отправляемся в путешествие и не успеем ими насладиться.\n\nМы очень ценим Вашу заботу и внимание и будем рады любому подарку.",
                    'gift_text'   => 'Не важно в какой конверт — мы его узнаем.',
                ],
            ],
            [
                'key'   => 'form',
                'title' => 'Анкета гостя',
                'content' => [
                    'submit_deadline' => '10/06/2026',
                    'closing_text'    => 'Ждём Вас ♡',
                    'closing_subtext' => 'С любовью, Ваши жених и невеста',
                    'form_options'    => WeddingFormDefaults::formOptions(),
                    'question_labels' => WeddingFormDefaults::questionLabels(),
                ],
            ],
        ];

        foreach ($blocks as $block) {
            SiteBlock::updateOrCreate(
                ['key' => $block['key']],
                $block
            );
        }
    }
}
