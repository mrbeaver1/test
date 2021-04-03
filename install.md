1.1 PHP 7.3
1.2 На сервере проекта должен быть установлен composer версии 1.11.99.1
1.3 Корень домена должен быть проложен к каталогу public.
Пример для nginx
root /var/www/html/public;

2.Выполнение команд из директории проекта
2.1 Устанавливаем зависимости проекта. Выполняем команду вида
/{path}/{to}/php /{path}/{to}/composer.phar install
например
/etc/php/php /usr/var/composer.phar install
После чего один из компонентов попросит установить "рецепты" для конфигурации
Do you want to execute this recipe?
     [y] Yes
     [n] No
     [a] Yes for all packages, only for the current installation session
     [p] Yes permanently, never ask again for this project
     (defaults to n): 
Ответить a
2.2 Для установки миграций на пустую БД выполнить в консоли команду
php bin/console doctrine:migrations:migrate,
WARNING! You are about to execute a database migration that could result in schema changes and data loss. Are you sure you wish to continue? (y/n)
ответить y.
