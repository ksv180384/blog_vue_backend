## Блог (backend)

#### PHP, Laravel, mysql

login: `test@test.ru`  
password: `password`

[Demo](https://site5.ksv-test.ru/).

#### [Блог Frontend](https://github.com/ksv180384/blog_vue_frontend)

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
  
- Настройте сервер, чтоб приложение было доступно по адресу `http://blog-vue.local`

