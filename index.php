<?php
        require "/usr/share/pear/Predis/Autoloader.php";
        Predis\Autoloader::register();
        $current_agent = $_SERVER['HTTP_USER_AGENT'];
        $selected_usragent = $_POST['useragent'];
        $useragent_touse = "";
        
        if (isset($selected_usragent)) {
            $useragent_touse = $selected_usragent;
        }
        else {
            $useragent_touse = $current_agent;
        }

        try {
                $client = new Predis\Client();
                $client->incr($current_agent);
        }
        catch (Exception $e) {
                die($e->getMessage());
        }
?>
<DOCTYPE html>
    <html>
    <head>
        <title>Homework webpage</title>
        <style>
            html { 
                background: url(pencils.jpg) no-repeat center center fixed; 
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
                font-family: Arial, Helvetica, sans-serif;
            }

            html, body {
                height: 100%;
                margin: 0;
            }

            .wrapper {
                min-height: 100%;
                margin-bottom: -100px;
            }

            .footer, .push {
                height: 100px;
            }

            .footer {
                background-color: rgba(243, 10, 10, 0.6);
                color: white;
                font-size: 16px;
                line-height: 100px;
            }

            .welcome {
                text-align: center;
                padding: 10px;
                color: white;
                font-size: 60px;
                text-shadow: 1px 1px black;
            }

            ul {
                list-style: none;
            }

            .description {
                padding: 15px;
                background-color: rgba(10, 10, 10, 0.5);
                color: white;
            }

            .theform {
                background-color: rgba(10, 10, 10, 0.5);
                color: white;
                margin-top: 50px;
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="welcome">
                <p>Welcome to the homework webpage</p>
            </div>
            <div class="description">
                <p>This webpage was built as a homework assignment. These were the steps to build it:</p>
                <ul>
                    <li>All the code lives in this repo: </li>
                    <li>A dedicated server (a build machine in azure cloud) fetches the code (ansible playbooks and the index.php)</li>
                    <li>Ansible builds a dedicated server which is used to host this webpage. The server is built in azure cloud. Server OS is CentOS linux.</li>
                    <li>Ansible installs nginx and redis database and all their dependencies.</li>
                    <li>Ansible configures nginx to host php webpage</li>
                    <li>Ansible deploys the index.php</li>
                    <li>The webpage gets visitor's user agent string and stores it into redis database incrementing the count for the same user agent string.</li>
                    <li>You can select a user agent string from the dropdown list and see how many times a particular user agent was encountered on this webpage.</li>
                </ul>
            </div>
            <div class="theform">
                <form method="post">
                    <label for="useragent">Please choose user agent string you want to get statistics for: </label>
                    <select id="useragent" name="useragent" onchange="this.form.submit()>
                        <?php
                            $allusragents = $client->keys('*');
                            foreach($allusragents as $item) {
                                if ($item === $useragent_touse) {
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
            </div>
            <div class="push"></div>
        </div>
        <footer class="footer">
            Your user agent string is: <?php echo $current_agent; ?>
        </footer>
    </body>
</html>