# Сервис коротких ссылок

Мини-проект на **Yii2** для генерации коротких ссылок и QR-кодов.

## Установка

1. Скопируйте проект.
```bash
git clone https://github.com/poncj/links.git
cd links
composer install
```

2. Создайте базуданных

2. Создайте файл .env
```bash
cp .env.example .env
```

3. Настройе подключение к бд в .env
```
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=link_shortener
DB_USER=root
DB_PASS=
```

3. Выполните миграции
```bash
php yii migrate
```

