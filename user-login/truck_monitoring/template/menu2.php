<link rel="stylesheet" href="css/cssmenu.css" />
<script src="js/jsmenu.js"></script>

<style>
    .menu{
        color: black;
    }
</style>
<div id='cssmenu'>
    <ul>
        <li><a href='index.php'><span class="menu">Home</span></a></li>
        
            <li class='active'><a href='#'><span class="menu">Transaction</span></a>
                <ul>
                    <li><a href='query_receiving.php'><span class="menu">Receiving</span></a>
                    </li>
                    <li><a href='query_outgoing.php'><span class="menu">Outgoing</span></a>
                    </li>
                    <li><a href='query_tipco.php'><span class="menu">Tipco Receiving</span></a>
                    </li>
                </ul>
            </li>
        
        <li class='active'><a href='#'><span class="menu">Payment</span></a>
            <ul>
                <li><a href='payment_others.php'><span class="menu">Other Payment</span></a>
                </li>
                <li><a href='payment_paid.php'><span class="menu">Cheque Payment</span></a>
                </li>
                <li><a href='payment_cancelled.php'><span class="menu">Cancelled Cheque Payment</span></a>
                </li>
                <li><a href='payment_paid_digibanker.php'><span class="menu"> Digi Payment</span></a>
                </li>
                <li><a href='payment_cancelled_digibanker.php'><span class="menu">Cancelled Digi Payment</span></a>
                </li>
            </ul>
        </li>
        <li class='active'><a href='#'><span class="menu">Advances</span></a>
            <ul>
                <li><a href='employee_advances_form.php'><span class="menu">Employee Submit Request</span></a>
                </li>
				<li><a href='adv_form.php'><span class="menu">Submit Request</span></a>
                </li>
                <li><a href='adv_list.php'><span class="menu">Advances List</span></a>
                </li>
				<li><a href='employee_advances_list.php'><span class="menu">Employee Advances List</span></a>
                </li>
            </ul>
        </li>
        <li class='active'><a href='#'><span class="menu">Reports</span></a>
            <ul>
                <li><a href='delivery_performance_reports.php'><span class="menu">Delivery Performance</span></a>
                </li>
                <li><a href='paper_buying.php'><span class="menu">Paper Buying</span></a>
                </li>
                <li><a href='report_expense.php'><span class="menu">Expense</span></a>
                </li>
                <li><a href='#'><span class="menu">Summary of Check Prepared</span></a>
                    <ul>
                        <li><a href='summary_of_check_prepared.php?cheque=1'><span class="menu">Format I</span></a>
                        </li>
                        <li><a href='summary_of_check_prepared.php?cheque=2'><span class="menu">Format II</span></a>
                        </li>
						<li><a href='summary_of_check_prepared.php?cheque=2.1'><span class="menu">Format II.I</span></a>
                        </li>
                        <li><a href='summary_of_check_prepared.php?cheque=3'><span class="menu">Format III</span></a>
                        </li>
                    </ul>
                </li>
                <li><a href='report_supplier_advances.php'><span class="menu">Supplier Advances</span></a>
            </ul>
        </li>
        <li class='active'><a href='#'><span class="menu">Maintenance</span></a>
            <ul>
                <li><a href='suppliers.php'><span class="menu">Suppliers</span></a>
                </li>
                <li><a href='update_employee.php'><span class="menu">Update Employee</span></a>
                </li>
                <li><a href='bank_accounts.php'><span class="menu">Bank Accounts</span></a>
                </li>
                <li><a href='check_range.php'><span class="menu">Cheque Range</span></a>
                </li>
            </ul>
        </li>
    </ul>
</div>


<!--<br>
<h3><a href="index.php">HOME</a> | <a href="query_receiving.php">RECEIVING</a> | <a href="query_outgoing.php">DELIVERY</a> | <a href="suppliers.php">SUPPLIER</a></h3>
<br>
<hr>-->