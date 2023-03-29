
# laravel-8-with-php-7.4

### Passo a passo para instalação
Clone Repositório
```sh
git clone laravel.git my-project
cd my-project/
```


Crie o Arquivo .env
```sh
cd example-project/
cp .env.example .env
```


Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME=Medicos
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=172.26.0.2
DB_PORT=5432
DB_DATABASE=medicos_db
DB_USERNAME=root
DB_PASSWORD=123123

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```


Suba os containers do projeto
```sh
docker-compose up -d
```


Acessar o container
```sh
docker-compose exec laravel_8 bash
```


Instalar as dependências do projeto
```sh
composer install
```


Gerar a key do projeto Laravel
```sh
php artisan key:generate
```


Acesse o projeto
[http://localhost:8000](http://localhost:8000)
