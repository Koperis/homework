<?php
        require "/usr/share/pear/Predis/Autoloader.php";
        Predis\Autoloader::register();
        $current_agent = $_SERVER['HTTP_USER_AGENT'];
        $selected_usragent = $_POST['useragent'];
        $useragent_touse = "";
        
        if (isset($selected_usragent)) {
            $useragent_touse === $selected_usragent;
        }
        else {
            $useragent_touse === $current_agent;
        }

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
        <p>Your user agent string is: <?php echo $current_agent; ?></p>
        <div>
            <form method="post">
                <label for="useragent">Please choose user agent string you want to get statistics for: </label>
                <select id="useragent" name="useragent" onchange="this.form.submit()">
                    <?php
                        $allusragents = $client->keys('*');
                        foreach($allusragents as $item) {
                            if ($item == $useragent_touse) {
                                    echo "<option value='$item' selected>$item</option>";
                                    $count = $client->get($useragent_touse);
                            }
                            else {
                                    echo "<option value='$item'>$item</option>";
                            }
                        }
                    ?>
                </select>
            </form>
            <p>Now showing statistics for user agent: <?php echo $useragent_touse; ?></p>
            <p>This user agent has been encountered on this website this many times: <?php echo $count; ?></p>
            <p>
            <?php
                echo "Current agent: $current_agent \r\n";
                echo "Selected agent: $selected_usragent \r\n";
                echo "User agent to use: $useragent_touse \r\n";
            ?>
            </p>
        </div>
    </body>
</html>