# This docker boilerplate is bad!
Problems with vendor files not visible, problems with log files not visible in host when logs are written 
and visible in container.
If I want to delete var folder - it tells Device is busy for no reason, editor is closed.
I have changed docker files myself a bit because those problems appeared right from the start of using.

# 🐳 Docker + PHP 8.2 + MySQL + Nginx + Symfony 6.2 Boilerplate

https://github.com/ger86/symfony-docker


## Installation

* Clone this repo.

* Create the file `./.docker/.env.nginx.local` using `./.docker/.env.nginx` as template. The value of the variable `NGINX_BACKEND_DOMAIN` is the `server_name` used in NGINX.

* `cd ./docker` 
* `docker compose up -d` 
* `docker exec -it symfony_dockerized_php_1 bash`
* `composer install`
* Use the following value for the DATABASE_URL environment variable:

```
DATABASE_URL=mysql://app_user:helloworld@db:3306/app_db?serverVersion=8.0.33
```

`php bin/console doctrine:migrations:migrate --no-interaction`


## Running if you have installed:

`cd .docker`

`docker-compose up -d`

Go to localhost:80

## Making migration from entities changes

`php bin/console make:migration`

## Run migrations

`php bin/console doctrine:migrations:migrate --no-interaction`

## Enter php container:
`docker exec -it symfony_dockerized_php_1 bash`

## If vendor files exist in container but not visible in host machine
After composer install can be that case. Helped deleting vendor in host machine.
Then vendor was deleted automatically in container. And running composer install.9

# If logs contents are not visible

Helps removing volumes:
`docker composer down -v`

Then will need reinstall composer packages and run migrations.


### Todo:


### Done:
* list with states
* find why apply does not change state
* links to view
* view article (show error when state is not published)