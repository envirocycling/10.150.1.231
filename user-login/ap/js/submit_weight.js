$(document).ready(function()
    {
        $("#enter").live('click',function()
        {
            var priority_no=$("#priority_no").val();
            var hours=$("#hours").val();
            var minutes=$("#minutes").val();
            var time=$("#time").val();
            var plate_number=$("#plate_number").val();
            var supplier_name=$("#supplier_name").val();
            var material_type=$("#material_type").val();
            var weight=$("#weight").val();
            alert('prio'+priority_no);
            var dataString = 'id='+ ID +'&supplier_id='+zero_val+'&supplier_name='+one_val+'&classification='+two_val+'&owner='+three_val+'&owner_contact='+four_val+'&street='+five_val+'&municipality='+six_val+'&province='+seven_val;
            if(one_val.length>0 || two_val.length>0 || three_val.length>0 || four_val.length>0 || five_val.length>0 || six_val.length>0 || seven_val.length>0)
            {
                $.ajax({
                    type: "POST",
                    url: "live_edit_ajax.php",
                    data: dataString,
                    cache: false,
                    success: function(e)
                    {

                        $("#one_"+ID).html(one_val);
                        $("#two_"+ID).html(two_val);
                        $("#three_"+ID).html(three_val);
                        $("#four_"+ID).html(four_val);
                        $("#five_"+ID).html(five_val);
                        $("#six_"+ID).html(six_val);
                        $("#seven_"+ID).html(seven_val);

                        e.stopImmediatePropagation();

                    }
                });
            }
        });
    });