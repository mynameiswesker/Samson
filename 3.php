<?php
    namespace Test3\Classes{

        class newBase{
            static private $count = 0;//создается переменная на уровне класса доступ к которой возможен только внутри класса (self::) счетчик
            static private $arSetName = [];//создается переменная на уровне класса доступ к которой возможен только внутри класса(self::) массив вложенных имен
            private $name;//переменная доступная только в рамке класса this-> должна быть объявлена до манипуляций с ней

                /**
                    * @param string $name ($name должна быть типом string)
                */

            public function __construct(int $name = 0){//конструктор принимает параметр name по дефолту =0 число тип int
                if(empty($name)){//если $name = пустое значение : 0,[],'' то (наверно должно быть !empty($name) если не пусто)
                    while(array_search(self::$count, self::$arSetName) != false){//увеличивать счетчик на 1 каждый раз , пока в пустом массиве не найдется ключ со значением счетчика = $count
                        ++self::$count;//увеличить count на 1 
                    }
                    $name = self::$count;//если name(переменная конструктора) не пустое или цикл пройден то name = count (если name чему то равент то приравнять его count = 0)
                }
                $this->name = strval($name);// ИЗМЕНЕНИЯ!!!!!!! //в переменную name записываем значение переменной контруктора и преобразуем в тип string как сказано в комментарии
                self::$arSetName[] = $this->name;//добавить в конец (всегда пустого) массива значение name(зачем нужен массив если он всегда пуст или в нем 1 значение)
            }

            /**
            * @return string  возвращать строку
            */

            public function getName(): string{//преобразует переменную name класса newBase из int в string и возвращает
                return '*' . $this->name  . '*';//возвращает строку "*name*" пример: "*123*"
            }

            protected $value;//закрыта для внешнего кода , доступна для производственных классов

            /**
             * @param mixed $value любой тип
             */

            public function setValue($value)
            {
                $this->value = $value;//сеттер получает значение и сохраняет в классе 
            }

//Вспомогательные функции для проверки состояния переенных в классе//////////////////////////      
/////////////////////////////////////////////////////////////////////////////////////////////
            public function showarSetName(){               ///////////////////////////////////
                return self::$arSetName;                   ///////////////////////////////////
            }                                              ///////////////////////////////////
///////////////////////////////////                        ///////////////////////////////////
                                                          ///////////////////////////////////
/////////////////////////////////                         ///////////////////////////////////
            public function showThisValue(){              ///////////////////////////////////
                return $this->value;                      ///////////////////////////////////
            }                                             ///////////////////////////////////
///////////////////////////////////                       ///////////////////////////////////
                                                          ///////////////////////////////////
/////////////////////////////////                         ///////////////////////////////////
            public function showThisName(){               ///////////////////////////////////
                return $this->name;                       ///////////////////////////////////
            }                                             ///////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
            /**
            * @return string возвращает строку
            */

            public function getSize(){
                $size = strlen(serialize($this->value));//size = длина строки строкового представления вводимого в сетторе value
                return strlen($size) + $size;//возвращает сумму = значение длины переменной size (integer) после сериализации + значение  size 
            }

            public function __sleep(){
                return ['value'];//недопустимо возвращать имена закрытых свойств
            }

            /**
            * @return string
            */

            public function getSave(): string{
                //value - string
                //sizeof(value) - int
                //name - string
                $value = serialize($value);//сериализует $value = null т.к нужно $this->value (но работает и так , обрабатывает NULL)
                return $this->name . ':' . sizeof($value) . ':' . $value;//возвращает канкатенацию $this->name : длину(строки=1) для value : value = NULL
            }

            /**
            * @return newBase
            */

            static public function load(string $value) : newBase{
                $arValue = explode(':', $value); // массив с разделителем : '12:34:567' = ['12','34','567'];
                return (new newBase(intval($arValue[0])))// ИЗМЕНЕНИЕ!!!!! $arValue[0] = первый элемент массива тип строка (необходимо преобразовать в int)
                ->setValue(unserialize(substr($value, strlen($arValue[0]) + 1//сохраняет в this->value расконвертированный отрезок строки (который не нуждается в расконвертации)
                + strlen($arValue[1]) + 1), $arValue));//2ой аргумент а именно arValue[1] должен быть массивом, а не строкой 
            }//в итоге возвращается null , а должен экземпляр

        }      
        
    }

    //Проверял работу класса newBase :
$elemNewBase = new Test3\Classes\newBase(123);
echo "//Класс :";
echo "</br>";
var_dump($elemNewBase);
echo "</br>";
echo "//Работа функции getName :"."</br>";
var_dump($elemNewBase->getName());
echo "</br>";
echo "//Значение this->name :"."</br>";
var_dump($elemNewBase->showThisName());
echo "</br>";
echo "//Значение self::arSetName :"."</br>";
var_dump($elemNewBase->showarSetName());
echo "</br>";
echo "Ввели значение value = '55'"."</br>";
$elemNewBase->setValue('55');
echo "//Значение this->value :"."</br>";
var_dump($elemNewBase->showThisValue());
echo "</br>";
echo "//Выполнение функции getSize() возвращает тип int :"."</br>";
var_dump($elemNewBase->getSize());
echo "</br>";
echo "//Выполнение функции getSave() возвращает тип string :"."</br>";
var_dump($elemNewBase->getSave());
echo "</br>";
echo "//Выполнение функции load() :"."</br>";
var_dump($elemNewBase->load('12:34:567'));  
    
?>
