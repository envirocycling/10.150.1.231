<link rel="stylesheet" href="css/cssmenu.css" />
<script src="js/jsmenu.js"></script>


<div id='cssmenu'>
    <ul>
        <li><a href='index.php'>Home</a></li>
        <li class='active'><a href='#'>Transaction</a>
            <ul>
                <li><a href='query_receiving.php'>Receiving</a>
                    <!--                    <ul>
                                            <li><a href='#'>Sub Product</a></li>
                                            <li><a href='#'>Sub Product</a></li>
                                        </ul>-->
                </li>
                <li><a href='query_outgoing.php'>Outgoing</a>
                    <!--                    <ul>
                                            <li><a href='#'>Sub Product</a></li>
                                            <li><a href='#'>Sub Product</a></li>
                                        </ul>-->
                </li>
                <li><a href='query_tipco.php'>Tipco Receiving</a>
                    <!--                    <ul>
                                            <li><a href='#'>Sub Product</a></li>
                                            <li><a href='#'>Sub Product</a></li>
                                        </ul>-->
                </li>
            </ul>
        </li>
        <li class='active'><a href='#'>Reports</a>
            <ul>
                <li><a><span class="menu">Employee Advances</span></a>
                    <ul>
                        <li><a href='report_employee_advances.php'><span class="menu">Report 1</span></a></li>
                        <li><a href='report_employee_summary.php'><span class="menu">Summary</span></a></li>                            
                    </ul>
                </li>
                <?php
                if ($_SESSION['ic_id'] == 50) {
                    ?>	
                    <li><a href='digi_payment.php'>Digi Payment</a></li><?php } ?>
                <li><a href='delivery_performance_reports.php'>Deliver Performance</a>
                    <!--                    <ul>
                                            <li><a href='#'>Sub Product</a></li>
                                            <li><a href='#'>Sub Product</a></li>
                                        </ul>-->
                </li>
                <li><a href='inventory_reports.php'>Inventory</a>
                    <!--                    <ul>
                                            <li><a href='#'>Sub Product</a></li>
                                            <li><a href='#'>Sub Product</a></li>
                                        </ul>-->
                </li>
                <li><a href='report_supplier_advances.php'><span class="menu">Supplier Advances</span></a>
                    <ul>
                        <li><a href='report_supplier_advances.php'><span class="menu">Report 1</span></a></li>
                        <li><a href='report_supplier_summary.php'><span class="menu">Summary</span></a></li>                            
                    </ul>
                </li>
                <li><a href='#'>Summary of Check Prepared</a>
                    <ul>
                        <li><a href='summary_of_check_prepared.php?cheque=1'><span class="menu">Format I</span></a>
                        </li>
                        <li><a href='summary_of_check_prepared.php?cheque=2'><span class="menu">Format II</span></a>
                        </li>
                        <li><a href='summary_of_check_prepared.php?cheque=3'><span class="menu">Format III</span></a>
                        </li>
                    </ul>
                </li>
                <li><a href='report_fundtransfer.php'>Fund Transfer</a></li>

            </ul>
        </li>
        <li><a>Pricing</a>
            <ul>
                <li><a href="suppliers.php">Supplier</a></li>
                <li><a href="price_client.php">Client</a></li>
            </ul>
        </li>
        <?php
        if ($_SESSION['class'] == 'coop') {
            ?>
            <li><a href='#'><span class="menu">Loan Coop</span></a>
                <ul>
                    <li><a href='adv_form.php'><span class="menu">Submit</span></a></li>
                    <li><a href='adv_list.php'><span class="menu">View List</span></a></li>                            
                    <li><a href='report_supplier_advancesCoop.php'><span class="menu">Report Per Supplier</span></a></li>                            
                </ul>
            </li>
            <?php
        }
        if ($_SESSION['ic_id'] == 53) {
            ?>		


            <li class='active'><a href='#'>Fund Transfer</a>
                <ul>
                    <li><a href='fund_process.php'>Process Fund Transfer</a></li>
                    <li><a href='fund_add.php'>Addtional Request</a></li>
                    <li><a href='fund_transfer.php'>View Fund Transfer</a></li>
                    <li><a href='fund_weekly_expense.php'>Weekly Expense</a></li>
                    <li><a href='fund_cutoff.php'>Cutt Off</a></li>

                </ul>
            </li>
            <li class='active'><a href='#'><span class="menu">Advances</span></a>
                <ul>
                    <li><a href='employee_advances_form.php'><span class="menu">Employee Submit Request</span></a>
                    </li>
                    <li><a href='employee_advances_list.php'><span class="menu">Employee Advances List</span></a>
                    </li>
                </ul>
            </li>

        <?php } ?>
    </ul>
</div>


<!--<br>
<h3><a href="index.php">HOME</a> | <a href="query_receiving.php">RECEIVING</a> | <a href="query_outgoing.php">DELIVERY</a> | <a href="suppliers.php">SUPPLIER</a></h3>
<br>
<hr>-->