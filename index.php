<?php
        require "/usr/share/pear/Predis/Autoloader.php";
        Predis\Autoloader::register();

        try {
                $client = new Predis\Client();
                $current_agent = $_SERVER['HTTP_USER_AGENT'];
                $client->incr($current_agent);
                $count = $client->get($current_agent);
                printf("Current user agent is: %s, and number of occurrences of this agent: %u", $current_agent, $count);
        }
        catch (Exception $e) {
                die($e->getMessage());
        }
?>