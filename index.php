<!DOCTYPE html>
<html lang="en">
    <?php
        $tablename=$_SERVER["QUERY_STRING"];
        if($tablename==null)
            $tablename="employees";
        include "initial.php";
        $fields = get_table_fields($tablename);
        $types = get_table_types($tablename);
        $data = get_table_data($tablename);
    ?>
    <head>
        <title>Database Management</title>
        <meta charset="utf-8">
        <?php
            echo "<style>
             #".$tablename."{
                color: rgb(120, 186, 162); 
                font-size: 1.24em;
                }
            </style>";
        ?>

        <link rel="stylesheet" href="database.css" type="text/css">
    </head>
    <body>
        <div id="wrapper">
            <header>
                <h1>Database Management System</h1>
            </header>

            <nav>
                <ul>
                    <li> <a href="/database/home.php">HOME</a> </li>
                    <li style="margin-top: 20px"> <a href="/database/index.php">CONTROL</a> </li>
                </ul>
            </nav>

            <main>
                <div style="margin-bottom: 50px; min-height: 400px;">
                <h2>TABLE</h2>
                <div>
                    <ul>
                        <li><a href="/database/index.php?employees" id="employees">Employees</a></li>
                        <li><a href="/database/index.php?customers" id="customers">Customers</a></li>
                        <li><a href="/database/index.php?suppliers" id="suppliers">Suppliers</a></li>
                        <li><a href="/database/index.php?products" id="products">Products</a></li>
                        <li><a href="/database/index.php?purchases" id="purchases">Purchases</a></li>
                        <li><a href="/database/index.php?logs" id="logs">Logs</a></li>
                    </ul>
                </div>

                <hr>
                <?php
                show_table($tablename);
                ?>
            </div>
                    
            <hr>
            
            <h2>INSERT</h2>
            <form method="post" action="insert.php">
            <input type="hidden" name="tablename" id="tablename" value= "<?php echo $tablename?>">

            <?php showForm($tablename); ?>
            <div style="width: 170px; margin:auto; margin-top: 30px;">
                <input type="reset" name="reset" id="reset" value="Reset" class="button">
                <input type="submit" name="submit" id="submit" value="Submit" class="button">
            </div>
            </form>

            </main>
            <footer>
                Copyright &copy; 2018 Jagon
            </footer>
        </div>
    </body>

</html>