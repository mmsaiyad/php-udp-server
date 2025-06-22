<?
$host = "0.0.0.0";
$port = 8080;

/*
$pid = shell_exec("ps aux");
echo $pid;

exec("kill 10239");

$pid = shell_exec("ps aux");
echo $pid;
*/

// Reduce errors
error_reporting(~E_WARNING);

// Create a UDP socket
if(!($sock = socket_create(AF_INET, SOCK_DGRAM, 0)))
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    die("Couldn't create socket: [$errorcode] $errormsg \n");
}

echo "Socket created \n";

// Bind the source address
if( !socket_bind($sock, $host , $port) )
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);
    die("Could not bind socket : [$errorcode] $errormsg \n");
}

echo "Socket bind OK \n";

// Array to store connected clients
$clients = array();

// Do some communication, this loop can handle multiple clients
while(1)
{
    echo "Waiting for data ... \n";
    
    // Receive some data
    $r = socket_recvfrom($sock, $buf, 512, 0, $remote_ip, $remote_port);
    
    if($r === false) {
        echo "Error receiving data\n";
        continue;
    }
    
    echo "$remote_ip : $remote_port -- " . $buf . "\n";
    
    // Add new client to the list if not already present
    $client_key = "$remote_ip:$remote_port";
    if(!in_array($client_key, $clients)) {
        $clients[] = $client_key;
        echo "New client connected: $client_key\n";
    }
    
    // Broadcast the received message to all connected clients
    foreach($clients as $client) {
        list($client_ip, $client_port) = explode(':', $client);
        
        // Don't send back to the sender
        if($client_ip == $remote_ip && $client_port == $remote_port) {
            //continue;
        }
        
        $sent = socket_sendto($sock, $buf, strlen($buf), 0, $client_ip, $client_port);
        if($sent === false) {
            echo "Failed to send to $client\n";
        } else {
            echo "Broadcasted to $client\n";
        }
    }
}

// Close the socket
socket_close($sock);
?>
