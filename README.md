# Скрипт базы данных IP GeoDB

Скрипт предлагает простой и быстрый JSON-интерфейс для доступа к геолокационной базе IP-адресов России и Украины.

Функционирование скрипта основано на сервисе http://ipgeobase.ru, все обновления базы осуществляются с данного сервиса.

## Установка

* Создайте базу данных для IP-геолокации с помощью geoip.sql
* Отредактируйте api.php и cron.php, указав параметры доступа к базе данных
* Добавьте cron.php в свой crontab-файл, например, в таком виде: 
```
14 3 */5 * * php -f /home/www/mygeoip.ru/cron.php
```

## Использование

Для запроса гео-данных, используйте адрес: `http://yourdomain.com/api.php?ip=127.0.0.1`

Данные предоставляются в формате JSON.
