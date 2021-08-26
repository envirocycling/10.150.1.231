<?php require './config/query_builder_tipco.php'; 

$data = fetch("SELECT *,scale.status as status1,supplier.status as status2 FROM scale INNER JOIN supplier ON scale.supplier_id=supplier.supplier_id WHERE (scale.company_id='1' or scale.company_id='2' or scale.company_id='5') and scale.status!='DELETED' and scale.date>='2020/08/01' and scale.date<='2020/08/03' and supplier.owner='EFI'", null);

dd_pretty($data);

?>