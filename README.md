
# laravel-8-with-php-7.4

### Passo a passo para instalação
Clone Repositório
```sh
git clone https://github.com/adolfoaugustor/desafio-om30 desafio-om30-Adolfo
cd desafio-om30-Adolfo/
```


Crie o Arquivo .env
```sh
cp .env.example .env
```


Confirme as variáveis de ambiente do arquivo .env 
```dosini
APP_NAME=Medicos
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
# verifique o ip da maquina docker desafio-om30_postgres_1
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

Rodas scripts do banco de dados
```sh
php artisan migrate
```


Acesse o projeto
[http://localhost:8000](http://localhost:8000)
