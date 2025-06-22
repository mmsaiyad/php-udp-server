FROM php:8.2-cli

WORKDIR /app
COPY udp_server.php .

CMD ["php", "udp_server.php"]