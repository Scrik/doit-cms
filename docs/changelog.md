История версий
==============
0.25.2
------
Класс `Upload` для простой загрузки файлов  изображений.

Для самого простого использования:
	
	$filename = d()->Upload->save();

В переменной `$filename` будет находиться адрес к файлу (в случае картинки - готовой к созданию превью). По-умолчанию пустит только картинки и распространённые файлы.

Параметр `upload` для формы, указывающий `enctype` для загрузки файлов.

*09.03.2014*

0.25.1.1
--------
Вывод текущей версии в админке (только для developer, подвал и страница обновления).

*08.03.2014*

0.25.1
------
Оптимизирован код ActiveRecord, убраны некоторые лишние проверки, которые никогда не будут вызваны.

Заведена история версий, которая будет использоваться для определения текущей версии системы (для обновления).

*08.03.2014*