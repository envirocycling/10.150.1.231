<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
<script type="text/javascript" src="jsDatePick.min.1.3.js"></script>
<script type="text/javascript">
    function date1(str) {
        new JsDatePick({
            useMode: 2,
            target: str,
            dateFormat: "%Y/%m/%d"

        });
    }
    ;
</script>


<body>
    <h1>Please input criteria</h1>

    <form action="export_inventory.php" method="POST" target="export">
        <h2>   Deliveries TO: 
            <?php
            include 'config.php';
            echo "<select id='delivered_to' name='delivered_to'>";
            echo "<option value=''>All</option>";
            $sql_dt = mysql_query("SELECT * FROM delivered_to");
            while ($rs_dt = mysql_fetch_array($sql_dt)) {
                echo "<option value='" . $rs_dt['dt_id'] . "'>" . $rs_dt['name'] . "</option>";
            }
            echo "</select>";
            ?>
            <br>
            Branch: <?php
            include 'config.php';
            echo "<select id='branch' name='branch'>";
            echo "<option value=''>All</option>";
            $sql_branch = mysql_query("SELECT * FROM branches");
            while ($rs_branch = mysql_fetch_array($sql_branch)) {
                echo "<option value='" . $rs_branch['branch_id'] . "'>" . $rs_branch['branch_name'] . "</option>";
            }
            echo "</select>";
            ?>
            <br>
            From: <input type='text'  id='inputField' name='from' value="" onfocus='date1(this.id);' readonly><br>
            TO: <input type='text'  id='inputField2' name='to' value="" onfocus='date1(this.id);' readonly><br>
            <input type="submit" value="Generate Report">
            </form>
            </body>