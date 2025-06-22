FROM php:8.2-cli

RUN apt-get update && docker-php-ext-install sockets

WORKDIR /app
COPY udp_server.php .

CMD ["php", "udp_server.php"]

