# MVP-back
## Первоначальное разворачивание проекта
1. Выполнить (из корня проекта) `composer install`
2. Создать в корне проекта файл `.env.local`
3. Прописать в `.env.local` параметр для подключения к PostgreSQL (саму БД руками создавать не нужно, достаточно указать название для неё), пример: `DATABASE_URL=pgsql://postgres:@localhost:5432/lew3lup?charset=utf8`
4. Прописать в .env.local адрес, по которому будет доступно API (этот проект), например: `API_DOMAIN=http://api.lew3lup.ru`
5. Прописать в .env.local адрес, по которому будет доступен фронт, например: `FRONT_DOMAIN=http://lew3lup.ru`
6. Устновить на сервере Redis, чтобы он был доступен по адресу `localhost:6379`
7. Выполнить `php bin/console doctrine:database:create`
8. Выполнить `php bin/console doctrine:migrations:migrate --no-interaction`
9. Выполнить `php bin/console cache:clear`
10. Скачать и установить на сервере Geth (go-ethereum)
11. В отдельном скрине запустить экземпляр Geth без пиров: `geth --maxpeers 0 --http --http.api personal,eth,net,web3 --ipcdisable`
12. Сделать корневой папкой домена каталог `public` в корне проекта
## Каждое обновление проекта (по-хорошему, засунуть эту последовательность действий в CI/CD)
1. Выполнить (из корня проекта) `composer install`
2. Выполнить `php bin/console doctrine:migrations:migrate --no-interaction`
3. Выполнить `php bin/console cache:clear`