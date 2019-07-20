<?php
        require "/usr/share/pear/Predis/Autoloader.php";
        Predis\Autoloader::register();
        $current_agent = $_SERVER['HTTP_USER_AGENT'];
        try {
                $client = new Predis\Client();
                $client->incr($current_agent);
        }
        catch (Exception $e) {
                die($e->getMessage());
        }
?>

<DOCTYPE>
<html>
    <head>
        <title>Homework webpage</title>
        <style>
            body {
                background-color: lightblue;
            }
        </style>
    </head>
    <body>
        <p>Welcome my homework webpage</p>
        <p>This page is meant to to show your browser's user agent string.</p>
        <p>Also, you will find a drop down list of user agent strings of users who have visited this website and how many times that user string was encountered.</p>
        <p>So you user agent string is: <?php echo $current_agent; ?></p>
        <div>
            <label for="useragent">Please choose user agent string</label>
            <select id="useragent" name="useragent">
                <?php
                    $allusragents = $client->keys('*');
                    foreach($allusragents as $item) {
                        echo "<option value='$item'>$item</option>";
                    }
                ?>
            </select>
        </div>
    </body>
</html>