# Darchoods API

## Working with this project locally
- <code>git clone https://github.com/darchoods/dh-api.git</code>
- <code>cd dh-api</code>
- <code>composer install</code>

## Working with the docker version of this project

Create the docker network that all projects will connect to (this only has to be done once)

```bash
docker network create --driver=bridge darchoods_network
```

Once the network is created run:

- <code>docker-compose up -d</code>

The API container should now be available on port `8082` of your host machine and MySQL should be available on port `33060`.

### Arbitrary Commands

- Bash into api container before the startup process

```bash
docker-compose run --entrypoint /bin/bash api
```

- When the container is running you can attach to it using the following command

```bash
docker ps | egrep dh\-api\:latest | cut -f1 -d' ' | xargs -o -I % docker exec -it % /bin/bash
```

- Run the API without triggering migrations

```bash
docker-compose up -d mysql && docker-compose run -e AUTORUN_LARAVEL_MIGRATION=false api
```

## Manually running database seeding or migrations

Seeding
- <code>php artisan db:seed</code>

Migrations
- <code>php artisan migrate --force --isolated</code>

## Manually verifying the PR checks

### Check 1. PHP Linter
- <code>vendor/bin/phplint app tests</code>

### Check 2. PHP Code Style Checker - PSR12
https://www.php-fig.org/psr/psr-12/
- <code>vendor/bin/phpcs</code>

If the CS Checker comes back with errors that it can automaticlly fix, run
- <code>vendor/bin/phpcbf</code>

and it should fix them.

## Tools used

- [asdf](https://github.com/asdf-vm/asdf) - Version manager

## Random host dependencies

Required for building php natively on Ubuntu 22.04 / Jammy

```bash
sudo apt install -y re2c plocate libcurl4 libcurl4-doc libcurl4-gnutls-dev libxml++2.6-dev libgd-dev libonig-dev libzip-dev
```
