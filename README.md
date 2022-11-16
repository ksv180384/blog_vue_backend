## Блог (backend)

#### PHP 7.4, composer, Laravel, mysql

login: `test@test.ru`  
password: `password`

[Demo](https://site5.ksv-test.ru/).

#### [Блог Frontend](https://github.com/ksv180384/blog_vue_frontend)

### Порядок установки 

---

#### Если вы используете Docker

После клонирования репозитория: 
- Убедитесь, что в файле `_docker\entrypoint.sh` тип переноса сток установлен как LF.
- выполнить команду `docker compose up`
- backend будет доступен по адресу `http://localhost:8083`, `http://localhost:8083/api/v1/index`

---

#### Если вы НЕ используете Docker

#### Необходимая версия PHP 7.4
- После клонирования репозитория, в корневой папке переименуйте файл `.env.example` в `.env`
- Измените настройки подключения к БД в `.env`
- Создайте БД `blog_vue`
- Выполните в консоли команды:
  - `composer install`  
  - `php artisan key:generate`  
  - `php artisan storage:link`  
  - `php artisan migrate --seed`  
  - `php artisan jwt:secret`
  
- Настройте сервер, чтоб приложение было доступно по адресу `http://blog-vue.local`

