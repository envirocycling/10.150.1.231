
<link rel="stylesheet" type="text/css" href="../css/layout.css" media="screen" />
<script src="../js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.core.min.js"></script>
<script src="../js/setup.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        setupLeftMenu();
        $('.datatable').dataTable();
        setSidebarHeight();
    });
</script>
<style>
    #example{
        border-width:50%;
        font-size: 13px;
    }

</style>
<?php
include 'config.php';
if (isset($_GET['ic_id'])) {
    mysql_query("UPDATE users SET status='deleted' WHERE user_id='".$_GET['ic_id']."'");
}
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="20">ID</th>
            <th class="data">Ic Name</th>
            <th class="data">First Name</th>
            <th class="data">Last Name</th>
            <th class="data">Initial</th>
            <th class="data">Action</th>';
echo '</tr>
        </thead>';
$sql_user = mysql_query("SELECT * FROM users WHERE usertype='3' and status!='deleted'");
while ($rs_user = mysql_fetch_array($sql_user)) {
    echo "<tr class='data'>";
    echo "<td class='data'>" . $rs_user['user_id'] . "</td>";
    echo "<td class='data'>" . $rs_user['username'] . "</td>";
    echo "<td class='data'>" . ucfirst($rs_user['firstname']) . "</td>";
    echo "<td class='data'>" . ucfirst($rs_user['lastname']) . "</td>";
    echo "<td class='data'>" . $rs_user['initial'] . "</td>";
    echo "<td class='data'>";
    ?>
<a href="query_ic.php?ic_id=<?php echo $rs_user['user_id']; ?>" onclick="return confirm('Are you sure you want to delete?')"><button>Delete</button></a>
    <?php
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
?>