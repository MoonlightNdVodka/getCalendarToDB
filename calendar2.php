<?php

function MonthGenerator(int $numberOfDays, string $firstDayOfWeek): array
{
    $dayNames = [
        1 => 'Понедельник',
        2 => 'Вторник',
        3 => 'Среда',
        4 => 'Четверг',
        5 => 'Пятница',
        6 => 'Суббота',
        7 => 'Воскресенье',
    ];
    //в этой переменной устанавливается первый день, с которого начнется неделя
    $dayOfWeekNumber = \array_search($firstDayOfWeek, $dayNames, true);

    if ($dayOfWeekNumber === false) {
        throw new \Exception('Некорректный день!');
    }

    $month = [];

    for ($i = 1; $i <= $numberOfDays; ++$i) {
        //здесь мы уже получаем переменную, с которой начнется наш месяц
        $remainder = ($i + $dayOfWeekNumber - 1) % 7;
        $month[$i] = $dayNames[$remainder === 0 ? 7 : $remainder];
    }

    return $month;
}
