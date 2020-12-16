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

function isPrime($number)
{
        if ($number==2)
                return true;
	if ($number%2==0)//все кроме 2 , если / 2 на с без остатка - составные
		return false;
	$i=3;
	$max_factor = (int)sqrt($number);
	while ($i<=$max_factor){
		if ($number%$i == 0)//делим на нечетные числа
			return false;
		$i+=2;//увеличиваем нечетное число на которое делим
	}
	return true;
}

function findSimple($a,$b)
{
	$primes = [];
	for ($i=$a; $i<=$b; $i++){
		if (isPrime($i))
			$primes[] = $i;
	}
	return $primes;
}

//выполнение функции
//var_dump(findSimple(2,11));

///Подзадание №2//////////////////////////////////////////////////////////////////////

        function createTrapeze($a){

            //Функция возвращает массив с измененными ключами
            function createArrs($keys,$wrapp_arr){
                    $empty = [];

                for ($i=0; $i <count($wrapp_arr) ; $i++) { //кол-во подмассивов в большом массиве
                    array_push($empty,array_combine($keys,$wrapp_arr[$i]));//в каждом подмассиве поменять ключи
                }

                return $empty;
            }
            //end

                $keys = ['a','b','c'];//ключи для массивов на выходе

                //проверка на ошибки:
                if(!is_array($a)){
                    throw new Exception('Вводимые данные должны быть массивом');
                }

                foreach($a as $value){
                    if($value<0){
                        throw new Exception('Числа массива должны быть больше 0');
                    }
                }

                if(count($a) % 3 !=0){
                    throw new Exception('Количество элементов массива должно быть кратно 3');
                }
                //
                try {

                    $wrapp_arrays = array_chunk($a,3);//разделить массив на несколько подмассивов по 3 элемента
                    return createArrs($keys,$wrapp_arrays);//меняем ключи на a,b,c

                } catch (Exception $e) {
                    echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
                }

        }
        //
        $a = createTrapeze([1,2,3,4,5,6,7,8,9]);//выполнение функции createTrapeze
        //

//выполнение функции
//var_dump($a);

///Подзадание №3//////////////////////////////////////////////////////////////////////

        function squareTrapeze(&$a){//жесткая ссылка на исходный массив
            $empty = [];

            for ($i=0; $i < count($a); $i++) { 
               $little_arr = $a[$i];
               $squar = array('s'=>0.5*($little_arr['a']+$little_arr['b'])*$little_arr['c']);//сделали массив ключ-значение для площади
               $little_arr+=$squar;//записали площадь в 1 из маленьких массивов
               array_push($empty,$little_arr);//обновили большой массив
           }

           $a = $empty;
           
        }

//выполнение функции
squareTrapeze($a);
//var_dump($a);

///Подзадание №4//////////////////////////////////////////////////////////////////////

        function getSizeForLimit($a,$b){
            $max_square = 0;
            $empty = [];
            for ($i=0; $i < count($a); $i++) { 
                $little_arr = $a[$i];
                if($max_square<$little_arr['s'] && $little_arr['s']<=$b){
                    $max_square = $little_arr['s'];
                    $empty = $little_arr;
                }
            }
            return $empty;
        }

//выполнение функции
//var_dump(getSizeForLimit($a,28));

///Подзадание №5//////////////////////////////////////////////////////////////////////

        function getMin($a){
            $empty = [];
            $first_key = array_keys($a)[0];//первый элемент любого массива
            $min = $a[$first_key];
            
            foreach($a as $key=>$value){
                if($a[$key] < $min){
                    $min=$a[$key];
                }
            }

            return $min;

        }

//выполнение функции
//var_dump(getMin([12,'a'=>12,'b'=>2,3,4,10,'min'=>-10]));

///Подзадание №6//////////////////////////////////////////////////////////////////////

        function printTrapeze($a){
            $html = '';

            for ($i=0; $i < count($a); $i++) { 
                $little = $a[$i];

                $square = '<td>'.$little['s'].'</td>';

                if(fmod($little['s'],2) == 1){//проверка на четность
                    $square = '<td style='.'color:red'.'>'.$little['s'].'</td>';
                }

                $html.='<tr><td>'.$little['a'].'</td>'.'<td>'.$little['b'].'</td>'.'<td>'.$little['c'].'</td>'.$square.'</tr>';
            }

            echo 
            '<table cellspacing="0" cellpadding="5" border="1" align="center">
                <caption>Таблица размеров трапеции</caption>
                <tr>
                    <th>сторона а</th>
                    <th>сторона b</th>
                    <th>высота c</th>
                    <th>площадь s</th>
                </tr>
                '.
                   $html.
                '
            </table>';
        }

//выполнение        
//printTrapeze($a);

///Подзадание №7//////////////////////////////////////////////////////////////////////

        abstract class BaseMath{

            //Переменная в которую записываем результат вычисления функций
            public $value;

            /*Метод должн быть определены в дочерних классах*/
            abstract protected function getValue();

            /*Общий метод возвращает результат расчета для класса наследника*/
            public function exp1($a, $b, $c){
                $this->value = $a*pow($b,$c);
            }

            public function exp2($a, $b, $c){
                $this->value = pow($a/$b,$c);
            }

        }

        class ClassExp extends BaseMath{
            public function getValue(){
                print $this->value;
            }
        }

//выполнение методов класса
 //   $classExp = new ClassExp;
 //   $classExp->exp1(1,2,3);
 //   $classExp->exp2(1,2,3);
 //   $classExp->getValue();

//Подзадание №8///////////////////////////////////////////////////////////////////////////////////////////////////////////

        class F1 extends BaseMath{
            public $f;

            function __construct($a,$b,$c){
                $this->f = $a*pow($b,$c) + pow(pow($a/$c,$b)%3,min($a,$b,$c));
            }

            public function getValue(){
                print $this->f;
            }
        }

//выполнение методов класса        
//        $classF1 = new F1(1,2,3);
//        $classF1->getValue();


    ?>
</body>
</html>