<?php
require_once('calendar2.php');

//ФУНКЦИЯ ОТЛАДКИ,  отображает аргумент функции моноширинным шрифтом и со всеми пробелами между словами
function pr($s)
{
    echo '<pre>';
    print_r($s);
    echo '</pre>';
}

$conn = new mysqli("localhost", "borland", "1123", "mytestdb");
if ($conn->connect_error) {
    die("ОшибO4ка: " . $conn->connect_error);
}
////СОЗДАЕМ ЛИЧНОЕ РАСПИСАНИЕ ДЛЯ КАЛЕНДАРЯ, И ЗАЛИВАЕМ ЕГО В БАЗУ ДАННЫХ 'mytestdb' в таблицу 'mymonth'

function generatePersonalCalendar()
{
    $planCalendar = [];
    $saturdayCounter = 1;
    $sundayCounter = 1;
    //Вызываем функцию создания календаря в цикле, и забираем оттуда все значения на проверку
    foreach (MonthGenerator(41, 'Понедельник') as $date => $day) {
        //по умолчанию у нас все пустые дни: на расслабоне, чиле, т.к на этот день нет планов.
        $event = 'Пока на расслабоне, на чиле';
        //проходим по каждому дню и проверяем условия
        // для существования в этом дне определенного расписания
        if ($day === 'Понедельник') {
            $event = 'Практика по PHP';
        } elseif ($day === 'Вторник') {
            $event = 'Домашняя работа';
        } elseif ($day === 'Среда') {
            $event = 'Бассейн';
        } elseif ($day === 'Четверг') {
            $event = 'Практика по PHP';
        } elseif ($day === 'Суббота') {
            if ($saturdayCounter % 2 !== 0) {
                $saturdayCounter++;
                $event = 'Рыбалка';
            } else {
                $saturdayCounter++;
            }
        } elseif ($day === 'Воскресенье') {
            if ($sundayCounter <= 2) {
                $event = 'Поездка в деревню';
            } elseif ($sundayCounter === 3) {
                $event = 'Шашлыки с друзьями';
            } elseif ($sundayCounter === 4) {
                $event = 'Диплом';
            }
            ++$sundayCounter;
        }
        $planCalendar[] = [
            'date' => $date,
            'day' => $day,
            'event' => $event,
        ];
    }
    return $planCalendar;
}
//забираем функцию и берем элементы ф-ии в качетсве определенных строк для БД,
$i = 1;
foreach (generatePersonalCalendar() as $value) {

    $date = $value['date'];
    $day = $value['day'];
    $event = $value['event'];

    $sql = "INSERT INTO  `mymonth` (`date`,`day`, `event` ) VALUES ('$date','$day','$event')";
    if ($result = $conn->query($sql)) {
        echo "Записал ".$i." день<tr>";
        echo "</tr>";

    } else {
        echo "Ошибка: " . $conn->error;
        break;
    }
    echo '<br>';
    $i++;
}
//Проходимся по массиву и забираем из него данные, Которые записываем в поля БД поочередно.
$conn->close();
