<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Database Management</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/database.css" type="text/css">
    </head>
    <body>
        <div id="wrapper">
            <header>
                <h1>Database Management System</h1>
            </header>

            <div id="container">
            <nav>
                <ul>
                    <li> <a href="/database/home.php">HOME</a> </li>
                    <li style="margin-top: 20px"> <a href="/database/market.html">MARKET</a> </li>
                    <li style="margin-top: 20px"> <a href="/database/index.html">CONTROL</a> </li>
                </ul>
            </nav>

            <main>
                <h2 class = "text">
                    There are still <br>
                    <?php
                        $date = new DateTime('2019-1-21');      //创建寒假时间的类对象
                        $date1 = new DateTime(date("ymd"));     //得到现在的时间的类对象
                        $interval = $date1->diff($date);        //相减得到相差天数并显示
                        echo "<span style=\"color: rgb(213, 84, 87); font-size: 1.3em\">".$interval->format('%a')."</span>";
                    ?> 
                     days<br>
                    to the winter vacation!!!
                </h2>
                <hr style="margin-top: 30px">
                <div style="width: 500px; margin: 30px auto;">
                    <img src="./image/holiday.jpg" alt="holidays image" width="500">
                </div>
                <hr>
            </main>
            </div>

            <footer>
                Copyright &copy; 2018 Jagon
            </footer>
        </div>
    </body>

</html>