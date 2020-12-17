<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
//Подзадание №1/////////////////////////////////////////////////////////

    function convertString($a, $b){
        $invert_str = strrev($b);//инвертируемая строка
        $length = strlen($b);//длина искомой подстроки
        $count = 0;//количество равных совпадений
        $new_str = $a;//перебираемая трока для работы

        //ведем поиск количества совпадений
        while(gettype(strpos($new_str,$b)) == 'integer'){//если не найдет совпадения то тип будет boolean->false иначе integer
            $position = strpos($new_str,$b);//позиция найденной подстроки
            //вырезаем 1ый найденный элемент из строки
            $part1 = substr($new_str,0,$position);//первая часть перед совпадающей строкой
            //вырезаем 2ой найденный элемент из строки
            $part2 = substr($new_str,$position+$length);//вторая часть после совпадающей строки
            $new_str = $part1.$part2;//новая строка
            $count++;

            if($count >= 2){
                //4 части для склеивания

                //1ая - часть строки до первого совпадения включительно

                //2ая подчасть - все после первой части но с инвертируемой подстрокой найденной на первой позиции (на 2ой после склеивания с 1 частью (по условию задания))
                //2я подчасть делится на 3:
                    //2ч - до первого совпадения
                    //3ч - инвертируемая строка
                    //4ч - после инверт строки

               $part1 = substr($a,0,strpos($a,$b)+$length);//1часть

               $sub_part2 = substr($a,strpos($a,$b)+$length);//2ая подчасть для дальнейшего редактирования
               $part2 = substr($sub_part2,0,strpos($sub_part2,$b));//2ая часть

               $part3 = $invert_str;//3 часть

               $part4 = substr($sub_part2,strlen($part2)+strlen($part3));//4 часть

               return $part1.$part2.$part3.$part4;//склеиваем все части
               
               
            }
        }
        return 'совпадений меньше 2';//совпадения
    }

//   var_dump(convertString('acdecdecde','cde'));


    //Подзадание №2/////////////////////////////////////////////////////////
      $arr = [
        ['a'=>9,'b'=>1],
        ['a'=>15,'b'=>3],
        ['a'=>11,'b'=>2],
        ['a'=>12,'b'=>4],
        ['a'=>13,'b'=>10]
    ];

   function mySortForKey($a,$b){

        for($i=0; $i<count($a); $i++){
            try {
                if(!array_key_exists($b,$a[$i])){
                   throw new Exception("Ошибка, ключ {$b} в массиве с индексом $i не найден");
                }
            } catch (Exception $e) {
                return $e->getMessage();
                die();
            }
        }

        usort($a, function($array_a,$array_b) use($b){
            return($array_a[$b] - $array_b[$b]);
        });

        return $a;

   }

   //var_dump(mySortForKey($arr,"b"));


    ?>
</body>
</html>