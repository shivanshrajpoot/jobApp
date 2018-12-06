FROM php:7.1-apache

RUN apt-get update -y && apt-get install -y --no-install-recommends \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxpm-dev \
    libvpx-dev \
    && docker-php-ext-configure gd \
        --with-freetype-dir=/usr/lib/x86_64-linux-gnu/ \
        --with-jpeg-dir=/usr/lib/x86_64-linux-gnu/ \
        --with-xpm-dir=/usr/lib/x86_64-linux-gnu/ \
        --with-vpx-dir=/usr/lib/x86_64-linux-gnu/ \
    && docker-php-ext-install gd \
    && apt install -y mysql-client

RUN docker-php-ext-install pdo pdo_mysql bcmath exif

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

RUN export LANG=en_US.UTF-8 \
    && apt-get update \
    && apt-get -y install git \
    && apt-get -y install wget \
    && apt-get -y install nano \
    && apt-get -y install zip

RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev \
        && docker-php-ext-install zip

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid
ENV APACHE_RUN_DIR /var/run/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
RUN ln -sf /dev/stdout /var/log/apache2/access.log && \
    ln -sf /dev/stderr /var/log/apache2/error.log
RUN mkdir -p $APACHE_RUN_DIR $APACHE_LOCK_DIR $APACHE_LOG_DIR

# Enable apache mods.
#RUN a2enmod php7.1
RUN a2enmod rewrite
RUN a2enmod headers

VOLUME [ "/var/www/html/public" ]
WORKDIR /var/www/html/public

EXPOSE 80

ENTRYPOINT [ "/usr/sbin/apache2" ]
CMD ["-D", "FOREGROUND"]
