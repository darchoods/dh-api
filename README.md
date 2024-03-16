# DarchoodsAPI

## Modifying this project
- <code>git clone https://github.com/darchoods/dh-api.git</code>
- <code>cd dh-api</code>
- <code>composer install</code>

## Manually verifying the PR checks

### Check 1. PHP Linter
- <code>vendor/bin/phplint app tests</code>

### Check 2. PHP Code Style Checker - PSR12
https://www.php-fig.org/psr/psr-12/
- <code>vendor/bin/phpcs</code>

If the CS Checker comes back with errors that it can automaticlly fix, run
- <code>vendor/bin/phpcbf</code>

and it should fix them.
