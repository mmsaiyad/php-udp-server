FROM php:8.2-cli

# Install sockets extension dependencies and enable sockets
RUN apt-get update && apt-get install -y libsocket-dev && docker-php-ext-install sockets

WORKDIR /app
COPY udp_server.php .

CMD ["php", "udp_server.php"]
