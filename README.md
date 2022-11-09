## Блог (backend)

#### PHP, Laravel, mysql

login: `test@test.ru`  
password: `password`

[Demo](https://laravel.com/docs/routing).

#### [Блог Frontend](https://laravel.com/docs/routing)

### Порядок установки 

---

#### Если вы используете Docker

- После клонирования репоситория выполнить команду `docker compose up`

---

#### Если вы НЕ используете Docker

- После клонирования репоситория, в корневой папке переименуйте файл `.env.example` в `.env`

- Выпоните в консоле комманды:
  - `php artisan key:generate`  
  - `php artisan storage:link`  
  - `php artisan migrate:fresh --seed`  
  - `php artisan jwt:secret`
  
- Настройте сервер, чтоб он был доступен по пути `http://blog-vue.local`

