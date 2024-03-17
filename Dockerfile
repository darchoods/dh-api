FROM serversideup/php:8.2-fpm-nginx

WORKDIR /var/www/html

# Update base image and install basic tooling
RUN apt-get update                  \
    && apt-get upgrade -y           \
    && apt-get install -y           \
        git                         \
        gnupg2                      \
        mariadb-client              \
        vim.nox                     \
    && rm -rf /var/lib/apt/lists/*  \
    && apt-get clean

# Copy in our source
COPY --chown=webuser:webgroup . /var/www/html/

# install composer dependencies
RUN composer install \
    && mkdir -p /var/www/html/storage/logs/ \
    && touch /var/www/html/storage/logs/laravel.log \
    && chown -R webuser:webgroup /var/www/html/
