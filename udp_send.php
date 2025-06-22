<?php
$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
$message = "ACTUALIZAR";
$server_ip = '173.249.60.132';
$server_port = 8080;

socket_sendto($socket, $message, strlen($message), 0, $server_ip, $server_port);
echo "Message sent\n";
socket_close($socket);
?>