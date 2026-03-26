<?php

namespace App\Support;

class WeddingFormDefaults
{
    /**
     * @return array{attending: list<array{value: string, label: string}>, food: list<array{value: string, label: string}>, alcohol: list<array{value: string, label: string}>}
     */
    public static function formOptions(): array
    {
        return [
            'attending' => [
                ['value' => '1', 'label' => 'Да, с удовольствием'],
                ['value' => '0', 'label' => 'К сожалению, не смогу'],
            ],
            'food' => [
                ['value' => 'none', 'label' => 'Нет'],
                ['value' => 'no_meat', 'label' => 'Не ем мясо'],
                ['value' => 'no_fish', 'label' => 'Не ем рыбу'],
                ['value' => 'vegetarian', 'label' => 'Вегетарианец'],
            ],
            'alcohol' => [
                ['value' => 'red_dry', 'label' => 'Красное вино (сухое)'],
                ['value' => 'red_sweet', 'label' => 'Красное вино (полусладкое)'],
                ['value' => 'white_dry', 'label' => 'Белое вино (сухое)'],
                ['value' => 'white_sweet', 'label' => 'Белое вино (полусладкое)'],
                ['value' => 'champagne_dry', 'label' => 'Шампанское (сухое)'],
                ['value' => 'champagne_sweet', 'label' => 'Шампанское (полусладкое)'],
                ['value' => 'whiskey', 'label' => 'Виски / коньяк'],
                ['value' => 'none', 'label' => 'Не употребляю алкоголь'],
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function questionLabels(): array
    {
        return [
            'attending' => '1. Сможете ли вы присоединиться к нам?',
            'food'      => '2. Есть ли у вас предпочтения по еде?',
            'alcohol'   => '3. Какой алкоголь вы предпочитаете?',
            'allergy'   => '4. Пищевая аллергия',
        ];
    }
}
