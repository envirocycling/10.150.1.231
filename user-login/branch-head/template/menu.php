<link rel="stylesheet" href="css/cssmenu.css" />
<script src="js/jsmenu.js"></script>


<div id='cssmenu'>
    <ul>
        <li><a href='index.php'>Home</a></li>
        <li class='active'><a href='#'>Transaction</a>
            <ul>
                <li class="has-sub"><a href='query_receiving.php'>Receiving</a>
                    <ul>
                        <li><a href="receiving_encodemanual.php">Manual Encode</a></li>
                    </ul>
                </li>
                <li><a href='query_outgoing.php'>Outgoing</a>
                </li>
                <li><a href='query_tipco.php'>Tipco Receiving</a>
                </li>
            </ul>
        </li>
        <li class='active'><a href='#'><span class="menu">Advances</span></a>
            <ul>
				<li><a href='employee_advances_list.php'><span class="menu">Employee Advances List</span></a>
                </li>
                <li><a href='adv_form.php'><span class="menu">Submit Request</span></a>
                </li>
                <li><a href='adv_list.php'><span class="menu">Advances List</span></a>
                </li>
            </ul>
        </li>
		<li class='active'><a href='truck_monitoring.php'><span class="menu">Truck Monitoring</span></a>
        </li>
        </li>
        <li class='active'><a href='#'>Reports</a>
            <ul>
                <li><a href='delivery_performance_reports.php'>Deliver Performance</a>
                </li>

		<li><a href='pss_delivery_performance_reports.php'>PSS</a>
                </li>

                <li><a href='inventory_reports.php'>Inventory</a>
                </li>
                <li><a href='report_supplier_advances.php'><span class="menu">Supplier Advances</span></a>
                        <ul>
                            <li><a href='report_supplier_advances.php'><span class="menu">Report 1</span></a></li>
                            <li><a href='report_supplier_summary.php'><span class="menu">Summary</span></a></li>                            
                        </ul>
                </li>
                <li><a><span class="menu">Employee Advances</span></a>
                        <ul>
                            <li><a href='report_employee_advances.php'><span class="menu">Report 1</span></a></li>
                            <li><a href='report_employee_summary.php'><span class="menu">Summary</span></a></li>                            
                        </ul>
                    </li>
                <li><a href='receiving_status.php'>Receiving Status</a>
                </li>
		<li><a href='report_truck_monitoring.php'>Truck Monitoring</a>
                </li>
		<li><a href='./../../supplier_price_report.php'>Suppplier Price</a></li>
            </ul>
        </li>
        <li><a href='suppliers.php'>Suppliers</a></li>
        <?php
            if($_SESSION['bh_id'] == 1 || $_SESSION['bh_id'] == 63){
        ?>
         <li class='active'><a href='#'>Pricing</a>
            <ul>
                <li><a href='pricing.php'>Supplier</a>
                </li>
                <li><a href='price_client.php'>Client</a>
                </li>
            </ul>
        </li>
            <?php }?>

    </ul>
</div>


<!--<br>
<h3><a href="index.php">HOME</a> | <a href="query_receiving.php">RECEIVING</a> | <a href="query_outgoing.php">DELIVERY</a> | <a href="suppliers.php">SUPPLIER</a></h3>
<br>
<hr>-->
