FROM php:8.2-apache
RUN apt-get update && apt-get install -y \
    libonig-dev \
  && docker-php-ext-install pdo_mysql mysqli
# apacheのrewriteモジュールを有効化
RUN  a2enmod rewrite
# libraryのインストール時に必要
RUN apt-get install -y zip
# composerの導入、最新をインストールするようにしているが問題があったらバージョンを固定する
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# テストのライブラリで必要
RUN apt-get -y install libzip-dev
RUN docker-php-ext-install zip
