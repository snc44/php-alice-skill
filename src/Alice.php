<?

include 'links.php';
include 'regexp_patterns.php';

class Alice{

    function getAnswer($question){

        $tts = '';
        $text = '';
        $linkBtn = null;

        if(preg_match(GROUP_BY_REG,$question)){
            $tts = 'Исп+ользуйте запр+ос с группир+овкой и ф+ункцией sum';
            $text = 'Используйте запрос с группировкой и функцией SUM';
            $linkBtn = GROUP_BY_LINK;
        }
        elseif(preg_match(VARIABLES_REG,$question)){
            $tts = 'Перем+енные в запр+осах эскью+эль явл+яются ос+обенностью диал+екта май эскью+эль. Вм+есто них м+ожно исп+ользовать станд+артные или общепр+инятые ср+едства язык+а, наприм+ер 
            подзапр+осы.';
            $text = 'Переменные в запросах SQL являются ос+обенностью диалекта MySQL. Вместо них можно использовать стандартные или общепринятые средства языка, например 
            подзапросы.';
            $text = str_replace('+','',$tts);
            $linkBtn = array(VARIABLES_LINK,VARIABLES_LINK_CTE);
        }
        elseif(preg_match(VARCHAR_PK_REG,$question)){
            $tts = 'Тип д+анных столбц+а не вли+яет на в+ыбор огранич+ения Primary Key. Здесь важн+а уник+альность и отс+утствие неопредел+енных знач+ений - null.';
            $text = str_replace('+','',$tts);
        }
        elseif(preg_match(TRIGGERS_REG,$question)){
            $tts = 'Для этого м+огут исп+ользоваться тр+иггеры, в д+анном сл+учае тр+иггер на обновл+ение. С+интаксис тр+иггера м+ожет зав+исеть от сист+емы упрал+ения б+азами д+анных';
            $text = 'Для этого могут использоваться триггеры, в данном случае триггер на обновление. Синтаксис триггера может зависеть от СУБД.';
            $linkBtn = TRIGGERS_SQL_LINK;
        }
        elseif(preg_match(SELECT_FROM_SELECT_REG,$question)){
            $tts = 'Исп+ользуйте подзапр+ос в предлож+ении from.';
            $text = 'Используйте подзапрос в предложении FROM.';
            $linkBtn = SELECT_FROM_SELECT_LINK;
        }
        elseif(preg_match(JOIN_REG,$question)){
            $tts = 'Теорет+ически кол+ичество соедин+яемых таблиц ничем не ограничено. Существ+уют р+азные т+ипы соедин+ения, наиб+олее распростран+енным из кот+орых явл+яется соедин+ение по предик+ату join. К+аждая сл+едующая табл+ица соедин+яется с результ+атом предыд+ущих соедин+ений. Об+ычно соедин+ение выполн+яется по вн+ешнему ключ+у, хот+я это не обяз+ательно.';
            $text = str_replace('+','',$tts);
            $linkBtn = JOIN_LINK;
        }
        elseif(preg_match(ORDER_BY_REG,$question)){
            $tts = 'Восп+ользуйтесь предлож+ением order by.';
            $text = 'В предложении ORDER BY после имени соответствующего столбца напишите DESC. Для сортировки по возрастанию можно написать ASC (используется по умолчанию).';
            $linkBtn = ORDER_BY_LINK;
        }
        elseif(preg_match(FOREIGN_KEY_REG,$question)){
            $tts = 'Вн+ешний ключ - это огранич+ение, обесп+ечивающее сс+ылочную ц+елостность д+анных. В ч+астности, он+о запрещ+ает появл+ение в подчин+ённой табл+ице знач+ений, которых нет в основн+ой.';
            $text = str_replace('+','',$tts);
            $linkBtn = FOREIGN_KEY_LINK;
        }
        elseif(preg_match(HAVING_REG,$question)){
            $tts = 'Фильтр+ацию знач+ений агрег+атных ф+ункций нельз+я в+ыполнить с п+омощью предлож+ения where. Для +этого исп+ользуется предлож+ение having.';
            $text = 'Фильтрацию значений агрегатных функций нельзя выполнить с помощью предложения WHERE. Для этого используется предложение HAVING.';
            $linkBtn = HAVING_LINK;
        }
        elseif(preg_match(IN_EXISTS_INTER_REG,$question)){
            $tts = 'Здесь м+ожно исп+ользовать н+есколько при+ёмов, наприм+ер';
            $text = 'Здесь можно использовать несколько приемов, например:';
            $linkBtn = array(IN_LINK,EXISTS_LINK,INTERSECT_LINK);
        }
        elseif(preg_match(SQL_DEFINITION_REG,$question)){
            $tts = 'sql - декларат+ивный язык программ+ирования, примен+яемый для созд+ания, модифик+ации и управл+ения д+анными в реляци+онной б+азе д+анных.';
            $text = str_replace('+','',$tts);
            $linkBtn = SQL_DEFINITION_LINK;
        }
        elseif(preg_match(HELLO_REG,$question)){
            $tts = 'Здр+авствуйте';
            $text = str_replace('+','',$tts);
        }
        elseif(preg_match(WHERE_REG,$question)){
            $tts = 'Исп+ользуйте предлож+ение where';
            $text = 'Используйте предложение where';
            $linkBtn = WHERE_LINK;
        }
        elseif(preg_match(AGREGATE_REG,$question)){
            $tts = 'Отв+еты на так+ие вопр+осы м+ожно получ+ить при п+омощи агрег+атных функций.';
            $text = str_replace('+','',$tts);
            $linkBtn = AGREGATE_LINK;
        }
        elseif(preg_match(UNION_REG,$question)){
            $tts = 'Восп+ользуйтесь предлож+ением union. Опер+ация объедин+ения прив+одит к появл+ению в результ+ирующем наб+оре - всех строк к+аждого из запр+осов.';
            $text = 'Воспользуйтесь предложением UNION. Операция объединения приводит к появлению в результирующем наборе всех строк каждого из запросов.';
            $linkBtn = UNION_LINK;
        }
        elseif(preg_match(PRIMARY_KEY_REG,$question)){
            $tts = 'Перв+ичный ключ - это знач+ение, кот+орое уник+ально для к+аждой з+аписи в табл+ице.';
            $text = $text = str_replace('+','',$tts);
            $linkBtn = PRIMARY_KEY_LINK;
        }
        else{
            $tts = 'Опиш+ите вопр+ос подр+обнее или попр+обуйте перефраз+ировать.';
            $text = str_replace('+','',$tts);
        }

        $answer = array('tts'=>$tts,'text'=>$text,'link'=>$linkBtn);
        return $answer;
    }

}

?>