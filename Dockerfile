FROM php:7.4-apache
MAINTAINER  Attila Szeremi <attila+webdev@szeremi.com>

RUN apt-get update && apt-get install -y \
  # For installing node
  curl \
  wget \
  gnupg \

  # For composer
  libzip-dev \
  zip

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - && \
  apt-get update && \
  apt-get install -y nodejs && \
  node --version && \
  npm --version

# PHP extensions
RUN docker-php-ext-install \
  pdo_mysql \
  zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

COPY composer.json .
COPY composer.lock .

# These directories need to exist if we composer install without hooks/scripts.
RUN mkdir -p database/seeders
RUN mkdir -p database/factories

# Do not run hooks, because they require the project files to already be there.
# We want to be able to avoid complete (slow) composer installs in the Dockerfile with caching if possible.
RUN composer install --no-scripts

COPY package.json .
COPY package-lock.json .

RUN npm install

COPY resources resources
COPY webpack.mix.js .

COPY . .

RUN mkdir -p bootstrap/cache && chmod a+rwx bootstrap/cache

RUN [ \
 "/bin/bash", \
 "-c", \
  "mkdir -p storage/framework/{cache,sessions,views} && chmod -R a+rwx storage/framework/{cache,sessions,views}" \
]

# This time, optimize and run hooks as well.
RUN composer install --optimize-autoloader

RUN php artisan view:cache

# This needs to happen after caching the views for purgeCSS.
RUN npm run production

# The parent directory of the sqlite database must be writable.
# https://github.com/wallabag/wallabag/issues/1845#issuecomment-205726683
RUN chmod a+rw database/

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Change Apache document root.
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin
RUN a2enmod rewrite headers

CMD ["bin/start.sh"]
