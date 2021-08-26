$(document).ready(function () {
    
//computation of weight   
    $('.input').keyup(function (){
        var pre_data = $(this).attr('id');
        var data = pre_data.split("_");
        var gross = Number($('#gross_' + data[1]).val());
        var tare = Number($('#tare_' + data[1]).val());
        var weight = Number(gross - tare);
        var mc = Number($('#mc_' + data[1]).val());
        var dirt = Number($('#dirt_' + data[1]).val());
        var netweight = Number(weight - (mc + dirt));
        
        if(data[0] != 'weight'){
            $('#weight_' + data[1]).val(weight);
        }
        $('#netweight_' + data[1]).val(netweight);
        //alert(netweight);
        
    });
//end computation of weight 


//submit
    $('.button').click(function () {
        var action = $(this).attr('id');
        var ctrl = Number($('#ctrl').val());
        var date = $('#date').val();
        var str_no = $('#str_no').val();
        var tr_no = $('#tr_no').val();
        var delivered_by = $('#delivered_by').val();  
        var plate_no = $('#plate_no').val();
        var delivered_to = $('#delivered_to').val();
        
        if(action == 'add'){
            var num = ctrl + 1;
            if(num <= 10){
                $('#tr_' + num).attr('hidden',false);
                $('#ctrl').val(num);
            }else {
                alert("No more rows to add. Please contact the system administrator.");
            }
        }else if(action == 'minus'){
            var num = ctrl - 1;
            if(num >= 1){ 
                $('#tr_' + ctrl).attr('hidden',true);
                $('#ctrl').val(num);
            }
        }else if(action == 'submit'){
            var a = 1;
            while(a <= ctrl){
                var wpgrade = $('#wpgrade_' + a).val();
                var bales = $('#bales_' + a).val();
                var gross = $('#gross_' + a).val();
                var tare = $('#tare_' + a).val();
                var weight = $('#weight_' + a).val();
                var mc = $('#mc_' + a).val();
                var dirt = $('#dirt_' + a).val();
                var netweight = $('#netweight_' + a).val();
                
                if(a == ctrl){
                    var end = '1';
                }
                
                var datax = 'date=' + date + '&str_no=' + str_no + '&tr_no=' + tr_no + '&delivered_by=' + delivered_by + '&plate_no=' + plate_no + '&delivered_to=' + delivered_to + '&bales=' + bales + '&wpgrade=' + wpgrade + '&gross=' + gross + '&tare=' + tare + '&weight=' + weight + '&mc=' + mc + '&dirt=' + dirt + '&netweight=' + netweight + '&end=' + end;
                 
                    $.ajax({
                        type: 'POST',
                        data: datax,
                        url: 'exec/receiving_manual.php?'
                    }).done(function(e){
                        alert("Successful." + '+' + e);
                        location.replace('receiving_encodemanual.php');
                    });
                
                a++;
            }
        }
    });
//submit end
});

    