DoIt CMS
=============

Простая система администрирования и фреймворк для быстрой и гибкой разработки web-приложений. По многим аспектам не накладывает жёстких рамок.
Позволяет выполнять классические задачи при помощи ООП или при помощи функционального подхода, либо вообще отказавшись от разделения логики представления от логики приложения.
В приоритете стоит простота системы, ясность работы, однозначность каждого шага, прозрачность подхода.

Установка
---------

Установка производится обычным копированием файлов системы в **корневую** директорию сайта. Логин и пароль доступа к базе данных указываются в файле config.php

### Требования

* PHP 5.2 и выше
* MySQL 5
* Apache 2 (для поддержки перенаправлений в .htaccess)

Для корректной работы необходима поддержка PDO. Для того, чтобы воспользоваться функциями AJAX, встроенными с предлагаемые механизмы форм, необходима подключеная библиотека jQuery.

Для запуска из других скриптов следует вызвать следующий код:
	
    include ('cms/cms.php');
    print d()->main();

Именно этот код и находится в файле index.php.

