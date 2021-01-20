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

/*     function convertString($a, $b){
        $invert_str = strrev($b);//инвертируемая строка
        $length = strlen($b);//длина искомой подстроки
        $count = 0;//количество равных совпадений
        $new_str = $a;//перебираемая трока для работы

        //ведем поиск количества совпадений
        while(gettype(strpos($new_str,$b)) == 'integer'){//если не найдет совпадения то тип будет boolean->false иначе integer->true
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

               return $part1.$part2.$part3.$part4;
               
               
            }
        }
        return $a;
    } */

    //var_dump(convertString('acdecdeerrcde','cde'));

    //Подзадание №1 с помощью регул выражения

    function convertString($a,$b){
        $invert_str = strrev($b);//инвертируемая строка
        $count = substr_count($a,$b);//кол-во вхождений
        $length = strlen($b);//длина искомой подстроки
        $array_indexes = [];//массив индексов найденных вхождений

        $regular = "//";//регулярное выражение должно выглядеть как "/123/"
        $regular = substr_replace($regular,$b,1,0);//для этого впишем 2 слеша в начало и конец для переменной $b -> "/$b/" -> "/123/"

        if($count >= 2){
            preg_match_all($regular,$a,$matches, PREG_OFFSET_CAPTURE);
            //var_dump($matches);

            foreach ($matches[0] as $key_match => $value_match) {
                //var_dump($value_match[1]);
                array_push($array_indexes,$value_match[1]);//все индексы найденных вхождений записать в массив $array_indexes
            }

            $secondIndex = $array_indexes[1];//позиция 2ого вхождения

            $a = substr_replace($a,'',$secondIndex,$length);//удалили из строки нужное кол-во цифр с позиции 2ого вхождения
            $a = substr_replace($a,$invert_str,$secondIndex,0);//вставили инвертируемую строку на позицию 2ого вхождения 

            //var_dump(substr_replace('123456789','',3,4));//удалили из строки 4 цифры с позиции 3
            //var_dump(substr_replace('123789','456',3,0));//вставили в строку на позицию 4 строку 456

            var_dump($a);
        }

        return $a;

    }

    //convertString("123456123098","123");


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
            if(!array_key_exists($b,$a[$i])){
                throw new Exception("Ошибка, ключ {$b} в массиве с индексом $i не найден");
            }
        }

        usort($a, function($array_a,$array_b) use($b){
            return($array_a[$b] - $array_b[$b]);
        });

        return $a;

   }

   //var_dump(mySortForKey($arr,"b"));

 //Задание №3
 
   //Вспомогательная функция для экранирования
    function clearTrash($str){
        $arr_trash = [".",",","[","]","'","@","#","$","%","^","&","*","_","-","`","<",">","/","?","*","!","{","}"];
        $trimmed_str = str_replace($arr_trash,"",trim($str));
        return $trimmed_str;
    }

   function importXml($a){
        $xml = simplexml_load_file($a);

        $host = 'localhost';
        $user = 'root';
        $password = 'root';
        $db_name = 'test_samson';

        $link = mysqli_connect($host, $user, $password, $db_name)
            or die(mysqli_error($link));
        
        foreach($xml->{'Товар'} as $product){//Название товара и его код -> таблица a_product
            //echo $product['Код']." ".$product["Название"]."</br>";

            $code = $product['Код'];//код продукта
            $name_product = $product["Название"];//название продукта

            //экранируем переменные
            $code = clearTrash($code);
            $name_product = clearTrash($name_product);

            $query = "INSERT INTO `a_product` (`id`, `code`, `name`) VALUES (NULL, '$code', '$name_product')";//записали в таблицу a_product
            
            $result = mysqli_query($link,$query)
                or die(mysqli_error($link));

                $ID = $link->insert_id;//id созданного продукта в таблице a_product

            foreach($product->{'Цена'} as $price){//Цена товара и его тип -> таблица a_price
                //echo $price." ".$price['Тип']."</br>";

                $price_product = $price;//цена товара
                $type_price = $price['Тип'];//тип цены

                //экранируем переменные
                $price_product = clearTrash($price_product);
                $type_price = clearTrash($type_price);

                $query = "INSERT INTO `a_price` (`id_product`, `type_price`, `price`) VALUES ('$ID', '$type_price', '$price_product')";//записали в таблицу a_price
            
                $result = mysqli_query($link,$query)
                    or die(mysqli_error($link));
            }

            foreach ($product->{'Свойства'} as $property) {//название свойства и его значение -> a_property
                foreach($property as $val){

                    $value_property = $val;//значение свойства
                    $name_property = $val->getName();//название свойства

                    //экранируем переменные
                    $value_property = clearTrash($value_property);
                    $name_property = clearTrash($name_property);

                    //echo $name_property." : ".$value_property."</br>";

                    $query = "INSERT INTO `a_property` (`id_product`, `name`, `value`) VALUES ('$ID', '$name_property', '$value_property')";//записали в таблицу a_property
            
                    $result = mysqli_query($link,$query)
                        or die(mysqli_error($link));
                }
            }

            foreach ($product->{'Разделы'} as $category) {//название категории и код продукта -> a_category
                foreach($category as $val_category){
                    //echo $val_category."</br>";

                    $name_category = $val_category;//название категории
                    //$code -> код продукта

                    //экранируем переменные
                    $name_category = clearTrash($name_category);

                    $query = "INSERT INTO `a_category` (`id`, `code`, `name`) VALUES ('$ID', '$code', '$name_category')";//записали в таблицу a_category
            
                    $result = mysqli_query($link,$query)
                        or die(mysqli_error($link));
                }
            }
        }

        mysqli_close($link);

   }

   //importXml('file.xml');

//реализация функции exportXML($a,$b)

   function exportXML($a,$b){

        //Данные для коннекта к БД
        $host = 'localhost';////////
        $user = 'root';/////////////
        $password = 'root';/////////
        $db_name = 'test_samson';///
        ////////////////////////////

        //Данные полученные с БД
        $id = 0;//id продукта всегда 1///////////////////////////////////////////////////////////////////
        $name_product = '';//////////////////////////////////////////////////////////////////////////////
        $name_category = [];//содержимое тега <Раздел> мб несколько//////////////////////////////////////
        $name_and_value_property = [];//название свойств и их значения для данного кода раздела//////////
        $price_and_typePrice = [];//Данные о цене и типах цены по данному коду раздела///////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////
        
        $link = mysqli_connect($host, $user, $password, $db_name)
            or die(mysqli_error($link));

        //получаем данные таблицы a_category
        $query_category = "SELECT * FROM a_category WHERE code = '$b' ";

        $result_category = mysqli_query($link,$query_category)
            or die(mysqli_error($link));

        if($result_category->num_rows >0){
            while($row = $result_category->fetch_assoc()){
                //var_dump($row);//вывел данные
                $id = $row['id'];//найденный id записал в константу
                array_push($name_category,$row['name']);//записали в константу содержимое тегов раздел для данного id
            }
        }
        
        //получаем данные таблицы a_property
        $query_property = "SELECT * FROM a_property WHERE id_product = '$id' ";

        $result_property = mysqli_query($link,$query_property)
            or die(mysqli_error($link));

        if($result_property->num_rows >0){
            while($row = $result_property->fetch_assoc()){
                //var_dump($row);//вывел данные
                array_push($name_and_value_property,['name'=>$row['name'],'value'=>$row['value']]);//записал данные в константу
            }
        }

        //получаем данные таблицы a_price
        $query_price = "SELECT * FROM a_price WHERE id_product = '$id' ";

        $result_price = mysqli_query($link,$query_price)
            or die(mysqli_error($link));

        if($result_price->num_rows >0){
            while($row = $result_price->fetch_assoc()){
                //var_dump($row);//вывел данные
                array_push($price_and_typePrice,['type_price'=>$row['type_price'],'price'=>$row['price']]);//записал данные в константу
            }
        }
        
        //получаем данные таблицы a_product
        $query_product = "SELECT * FROM a_product WHERE id = '$id' ";

        $result_product = mysqli_query($link,$query_product)
            or die(mysqli_error($link));

        if($result_product->num_rows >0){
            while($row = $result_product->fetch_assoc()){
                //var_dump($row);//вывел данные
                $name_product = $row['name'];
            }
        }
        
        mysqli_close($link);

//Создание xml и его экспорт в файл $a.xml

        $dom = new DOMDocument();
        $dom->load($a);
        $Products = $dom->getElementsByTagname('Товары');

        //Создаем тег <Товар>
        $product = $dom->createElement('Товар');
        $product->setAttribute('Код',$b);
        $product->setAttribute('Название',$name_product);

        //Создаем Тег <Цена>
        foreach($price_and_typePrice as $info_price){
            $price = $dom->createElement('Цена',$info_price['price']);
            $price->setAttribute('Тип',$info_price['type_price']);
            $product->appendChild($price);
        }
        unset($info_price);

        //Создаем Тег <Свойства>
        $property = $dom->createElement('Свойства');

        //Создаем тег названия_свойства и его хар-ки в теге Свойства
        foreach($name_and_value_property as $info_property){
            $name_prop = $dom->createElement($info_property['name'],$info_property['value']);
            $property->appendChild($name_prop);
        }
        unset($info_property);

        //Создаем Тег <Разделы>
        $categ = $dom->createElement('Разделы');

        //Создаем тег <Раздел> и его хар-ки в теге Разделы
        foreach($name_category as $name){
            $name_categ = $dom->createElement('Раздел',$name);
            $categ->appendChild($name_categ);
        }
        unset($name);
        
        $Products->item(0)->appendChild($product);//вставили тег <Товар> в тег <Товары>
        $dom->getElementsByTagname('Товар')->item(0)->appendChild($property);//Вставили тег <Свойства> в тег <Товар>
        $dom->getElementsByTagname('Товар')->item(0)->appendChild($categ);//Вставили тег <Разделы> в тег <Товар>

        $dom->save($a);

   }

   //exportXML('$a.xml',201);//Пример выполнения

    ?>
</body>
</html>