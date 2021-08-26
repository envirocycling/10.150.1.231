$(window).load(function () {
				$(".txt_en").hide();
				$(".txt_enadd").hide();
				$(".enable").hide();
				$(".disable").click(function(){
					alert("Button is Disabled. Can't do this Action");
				});
				$("span").click(function() {
				
				var ID = $(this).attr('id');
				var myId = ID.split("_");
				var expense = Number($('#exval_' + myId[1]).val());
				var addfund = Number($('#addval_' + myId[1]).val());
				var status = $('#hidden-status_' + myId[1]).val();
				var tot_fund = expense + addfund;
				var ofr = Number($("#ofr").val());
				var count3 = Number($("#hidden-count1").val());
				
					/*if(addfund == 0 || expense == 0){
						$("#disbutton_" + myId[1]).hide(100);
						$("#enbutton_" + myId[1]).show(300);
					}if(expense == 0){
						$("#expense_" + myId[1]).hide(100);
						$("#ex_" + myId[1]).show(300);
					}if(addfund == 0){
						$("#addfund_" + myId[1]).hide(100);
						$("#add_" + myId[1]).show(300);
					}*/
					if(myId[0] == 'mbal'){
						if(count3 == '0'){
						var count = Number($('#hidden-count').val()) + 1;
						$('#hidden-count1').val("1");
						
						$('#hidden-count').val(count);
						$("#mbal_" + myId[1]).hide(100);
						$("#bal_" + myId[1]).show(300);
						$("#disbutton_" + myId[1]).hide(100);
						$("#enbutton1_" + myId[1]).show(300);
						$(".fund_available").attr("disabled",true);
						$("#fund_available1").attr("disabled",true);
						}
						
					}else{
					if(myId[0] != 'cancelbal'){
						if(myId[0] != 'okbal'){
							if(count3 == '0'){
						var count = Number($('#hidden-count').val()) + 1;
						$('#hidden-count1').val("1");
						
						
						$('#hidden-count').val(count);
						$("#disbutton_" + myId[1]).hide(100);
						$("#enbutton_" + myId[1]).show(300);
						$("#expense_" + myId[1]).hide(100);
						$("#ex_" + myId[1]).show(300);
						$("#addfund_" + myId[1]).hide(100);
						$("#add_" + myId[1]).show(300);
						$(".fund_available").attr("disabled",true);
						$("#fund_available1").attr("disabled",true);
						if(myId[0] != 'cancel'){
							var  ofr2 = ofr - tot_fund;
							$("#ofr").val(ofr2);
						}
						
					}
					}
					}
					}
					if(myId[0] == 'cancelbal'){
						var c_expense = Number($('#hidden-expense_' + myId[1]).val());
						var c_mbal = Number($('#hidden-mbal_' + myId[1]).val());
						var count1 = Number($('#hidden-count').val()) - 1;
						var re_val = (c_mbal - c_expense).toFixed(2); 
							$('#hidden-count').val(count1);
							$('#hidden-count1').val("0");
						
						$("#bal_" + myId[1] ).hide(200);
						$("#mbalval_" + myId[1]).val(c_mbal);
						$("#mbal_" + myId[1]).html(c_mbal);
						$("#reval_" + myId[1]).html(re_val);
						$("#mbal_" + myId[1]).show(200);
						$("#disbutton_" + myId[1]).show(100);
						$("#enbutton1_" + myId[1]).hide(300);
						
						if(count1 == 0){
						$(".fund_available").attr("disabled",false);
						$("#fund_available1").attr("disabled",false);
						}
						
						var sau = '6';
                                                var pamp = '7';
						var cai = '2';
						var cal = '3';
						var cav = '4';
						var kay = '5';
						var man = '12';
						var pas = '9';
						var urd = '10';
						
						var mbal_pamp = Number($('#mbalval_' + pamp).val());
						var mbal_sau = Number($('#mbalval_' + sau).val());
						var mbal_cai = Number($('#mbalval_' + cai).val());
						var mbal_cal = Number($('#mbalval_' + cal).val());
						var mbal_cav = Number($('#mbalval_' + cav).val());
						var mbal_kay = Number($('#mbalval_' + kay).val());
						var mbal_man = Number($('#mbalval_' + man).val());
						var mbal_pas = Number($('#mbalval_' + pas).val());
						var mbal_urd = Number($('#mbalval_' + urd).val());
						
						var tot_mbal = (mbal_sau + mbal_cai + mbal_cal + mbal_cav + mbal_kay + mbal_man + mbal_pas + mbal_urd + mbal_pamp).toFixed(2);
						
						$('#t_mbal').html(tot_mbal);
						
					}else if(myId[0] == 'okbal'){
						var h_mbal = $('#hidden-mbal_' + myId[1]).val();
						var expense = $('#hidden-expense_' + myId[1]).val();
						var mbal = $('#mbalval_' + myId[1]).val();
						var count1 = Number($('#hidden-count').val()) - 1;
							$('#hidden-count').val(count1);
							$('#hidden-count1').val("0");
						
						$("#bal_" + myId[1] ).hide(200);
						$("#mbalval_" + myId[1]).val(mbal);
						$("#mbal_" + myId[1]).html(mbal);
						$('#hidden-mbal_' + myId[1]).val(mbal);
						$("#mbal_" + myId[1]).show(200);
						$("#disbutton_" + myId[1]).show(100);
						$("#enbutton1_" + myId[1]).hide(300);
						
						if(count1 == 0){
						$(".fund_available").attr("disabled",false);
						$("#fund_available1").attr("disabled",false);
						}
						
					}else if(myId[0] == 'cancel'){
						var sau = '6';
						var cai = '2';
						var cal = '3';
						var cav = '4';
						var kay = '5';
						var man = '12';
						var pas = '9';
						var urd = '10';
						var pamp = '7';
						
						$('#hidden-count1').val("0");
						
						var c_expense = $('#hidden-expense_' + myId[1]).val();
						var c_addfund = $('#hidden-addfund_' + myId[1]).val();
						var c_remaining1 = $('#reval_' + myId[1]).html().replace(/\,/g,"");
						var c_remaining = Number(c_remaining1);
						var c_totfund = $('#hidden-totfund_' + myId[1]).val();
						var counts = Number($('#hidden-count').val()) - 1;
						//alert(c_remaining);
						var c = counts;
							$('#hidden-count').val(counts);
						
						$("#expense_" + myId[1] ).hide(200);
						$("#exval_" + myId[1]).val(c_expense);
						$("#reval_" + myId[1]).html(c_remaining);
						$("#totval_" + myId[1]).html(c_totfund);
						$("#ex_" + myId[1]).hide(200);
						$("#add_" + myId[1]).hide(200);
						$("#addval_" + myId[1]).val(c_addfund);
						$(".txt_dis").show(200);
						$("#disbutton_" + myId[1]).show(200);
						$("#enbutton_" + myId[1]).hide(200);
						$("#expense_" + myId[1]).show(200);
						$("#addfund_" + myId[1]).show(200);
						if(c == 0){
						$(".fund_available").attr("disabled",false);
						$("#fund_available1").attr("disabled",false);
						}
						var ofr1 = tot_fund + ofr;
						$("#ofr").val(ofr1);
						
					
					var tot_req_sauyo = $("#totval_" + sau).html();
					var tot_req_sauyo1  = tot_req_sauyo.replace(/\,/g,"");
					var tot_req_sauyo2  = Number(tot_req_sauyo1);
                                        
                                        var tot_req_pamp = $("#totval_" + pamp).html();
					var tot_req_pamp1  = tot_req_pamp.replace(/\,/g,"");
					var tot_req_pamp2  = Number(tot_req_pamp1);
					
					var tot_req_cai = $("#totval_" + cai).html();
					var tot_req_cai1  = tot_req_cai.replace(/\,/g,"");
					var tot_req_cai2  = Number(tot_req_cai1);
					
					var tot_req_cal = $("#totval_" + cal).html();
					var tot_req_cal1  = tot_req_cal.replace(/\,/g,"");
					var tot_req_cal2  = Number(tot_req_cal1);
					
					var tot_req_cav = $("#totval_" + cav).html();
					var tot_req_cav1  = tot_req_cav.replace(/\,/g,"");
					var tot_req_cav2  = Number(tot_req_cav1);
					
					var tot_req_kay = $("#totval_" + kay).html();
					var tot_req_kay1  = tot_req_kay.replace(/\,/g,"");
					var tot_req_kay2  = Number(tot_req_kay1);
					
					var tot_req_man = $("#totval_" + man).html();
					var tot_req_man1  = tot_req_man.replace(/\,/g,"");
					var tot_req_man2  = Number(tot_req_man1);
					
					var tot_req_pas = $("#totval_" + pas).html();
					var tot_req_pas1  = tot_req_pas.replace(/\,/g,"");
					var tot_req_pas2  = Number(tot_req_pas1);
					
					var tot_req_urd = $("#totval_" + urd).html();
					var tot_req_urd1  = tot_req_urd.replace(/\,/g,"");
					var tot_req_urd2  = Number(tot_req_urd1);
					
					var t_req = (tot_req_sauyo2 + tot_req_cai2 + tot_req_cal2 + tot_req_cav2 + tot_req_kay2 + tot_req_man2 + tot_req_pas2 + tot_req_urd2 + tot_req_pamp2).toFixed(2);
					$("#t_fund" ).html(t_req);
					
					var tot_fund_sauyo = Number($("#addval_" + sau).val());
					var tot_fund_urd = Number($("#addval_" + urd).val());
					var tot_fund_pas = Number($("#addval_" + pas).val());
					var tot_fund_man = Number($("#addval_" + man).val());
					var tot_fund_kay = Number($("#addval_" + kay).val());
					var tot_fund_cav = Number($("#addval_" + cav).val());
					var tot_fund_cal = Number($("#addval_" + cal).val());
					var tot_fund_cai = Number($("#addval_" + cai).val());
					var tot_fund_pamp= Number($("#addval_" + pamp).val());
					
					
					
					var t_funds = (tot_fund_urd + tot_fund_pas + tot_fund_man + tot_fund_kay + tot_fund_cav + tot_fund_cal + tot_fund_cai + tot_fund_sauyo + tot_fund_pamp).toFixed(2);
					$("#t_fundadd").html(t_funds);
					
						
						var mbal_sau = Number($('#mbalval_' + sau).val());
						var mbal_cai = Number($('#mbalval_' + cai).val());
						var mbal_cal = Number($('#mbalval_' + cal).val());
						var mbal_cav = Number($('#mbalval_' + cav).val());
						var mbal_kay = Number($('#mbalval_' + kay).val());
						var mbal_man = Number($('#mbalval_' + man).val());
						var mbal_pas = Number($('#mbalval_' + pas).val());
						var mbal_urd = Number($('#mbalval_' + urd).val());
						var mbal_pamp = Number($('#mbalval_' + pamp).val());
						
					 	var mbal = Number($('#mbalval_' + myId[1]).val());
						var expense = Number($('#exval_' + myId[1]).val());
						var remaining = (mbal - expense).toFixed(2);
						//var tot_mbal = (mbal_sau + mbal_cai + mbal_cal + mbal_cav + mbal_kay + mbal_man + mbal_pas + mbal_urd).toFixed(2);
						
						$('#reval_' + myId[1]).html(remaining);
						
						var reval_pamp1 = $('#reval_' + pamp).html().replace(/\,/g,"");
						var reval_pamp = Number(reval_pamp1);
                                                var reval_sau1 = $('#reval_' + sau).html().replace(/\,/g,"");
						var reval_sau = Number(reval_sau1);
						var reval_cai1 = $('#reval_' + cai).html().replace(/\,/g,"");
						var reval_cai = Number(reval_cai1);
						var reval_cal1 = $('#reval_' + cal).html().replace(/\,/g,"");
						var reval_cal = Number(reval_cal1);
						var reval_cav1 = $('#reval_' + cav).html().replace(/\,/g,"");
						var reval_cav = Number(reval_cav1);
						var reval_kay1 = $('#reval_' + kay).html().replace(/\,/g,"");
						var reval_kay = Number(reval_kay1);
						var reval_man1 = $('#reval_' + man).html().replace(/\,/g,"");
						var reval_man = Number(reval_man1);
						var reval_pas1 = $('#reval_' + pas).html().replace(/\,/g,"");
						var reval_pas = Number(reval_pas1);
						var reval_urd1 = $('#reval_' + urd).html().replace(/\,/g,"");
						var reval_urd = Number(reval_urd1);
						
						var tot_reval = (reval_sau + reval_cai + reval_cal + reval_cav + reval_kay + reval_man + reval_pas + reval_urd + reval_pamp).toFixed(2);
						
						$('#t_reval').html(tot_reval);
						
						
					var ex_sau1 = $('#exval_' + sau).val().replace(/\,/g,"");
						var ex_sau = Number(ex_sau1);
						var ex_cai1 = $('#exval_' + cai).val().replace(/\,/g,"");
						var ex_cai = Number(ex_cai1);
						var ex_cal1 = $('#exval_' + cal).val().replace(/\,/g,"");
						var ex_cal = Number(ex_cal1);
						var ex_cav1 = $('#exval_' + cav).val().replace(/\,/g,"");
						var ex_cav = Number(ex_cav1);
						var ex_kay1 = $('#exval_' + kay).val().replace(/\,/g,"");
						var ex_kay = Number(ex_kay1);
						var ex_man1 = $('#exval_' + man).val().replace(/\,/g,"");
						var ex_man = Number(ex_man1);
						var ex_pas1 = $('#exval_' + pas).val().replace(/\,/g,"");
						var ex_pas = Number(ex_pas1);
						var ex_urd1 = $('#exval_' + urd).val().replace(/\,/g,"");
						var ex_urd = Number(ex_urd1);
						var ex_pamp1 = $('#exval_' + pamp).val().replace(/\,/g,"");
						var ex_pamp = Number(ex_pamp1);
						
						var tot_ex = (ex_urd + ex_pas + ex_man + ex_kay + ex_cav + ex_cal + ex_cai + ex_sau + ex_pamp).toFixed(2);
						
						$('#t_ex').html(tot_ex);
						
						
					}else if(myId[0] == 'ok'){
						var count2 = Number($('#hidden-count').val()) - 1;
						var c2 = count2;
						$('#hidden-count').val(c2);
						$('#hidden-count1').val("0");

						$("#expense_" + myId[1]).html(expense);
						$("#exval_" + myId[1]).val(expense);
						$("#addfund_" + myId[1]).html(addfund);
						$("#addval_" + myId[1]).val(addfund);
						$("#disbutton_" + myId[1]).show(100);
						$("#enbutton_" + myId[1]).hide(300);
						$("#expense_" + myId[1]).show(100);
						$("#ex_" + myId[1]).hide(300);
						$("#addfund_" + myId[1]).show(100);
						$("#add_" + myId[1]).hide(300);
						$('#hidden-expense_' + myId[1]).val(expense);
						$('#hidden-addfund_' + myId[1]).val(addfund);
						$('#hidden-totfund_' + myId[1]).val(tot_fund);
						if(c2 == 0){
						$(".fund_available").attr("disabled",false);
						$("#fund_available1").attr("disabled",false);
						var ofr1 = tot_fund + ofr;
						$("#ofr").val(ofr1);
						}	
						
					var sau = '6';
					var cai = '2';
					var cal = '3';
					var cav = '4';
					var kay = '5';
					var man = '12';
					var pas = '9';
					var urd = '10';
					var pamp = '7';
					
				 	var ofr3 = Number($("#ofr").val());
					
					var tot_req_sauyo = $("#totval_" + sau).html();
					var tot_req_sauyo1  = tot_req_sauyo.replace(/\,/g,"");
					var tot_req_sauyo2  = Number(tot_req_sauyo1);
                                        
                                        var tot_req_pamp = $("#totval_" + sau).html();
					var tot_req_pamp1  = tot_req_pamp.replace(/\,/g,"");
					var tot_req_pamp2  = Number(tot_req_pamp1);
					
					var tot_req_cai = $("#totval_" + cai).html();
					var tot_req_cai1  = tot_req_cai.replace(/\,/g,"");
					var tot_req_cai2  = Number(tot_req_cai1);
					
					var tot_req_cal = $("#totval_" + cal).html();
					var tot_req_cal1  = tot_req_cal.replace(/\,/g,"");
					var tot_req_cal2  = Number(tot_req_cal1);
					
					var tot_req_cav = $("#totval_" + cav).html();
					var tot_req_cav1  = tot_req_cav.replace(/\,/g,"");
					var tot_req_cav2  = Number(tot_req_cav1);
					
					var tot_req_kay = $("#totval_" + kay).html();
					var tot_req_kay1  = tot_req_kay.replace(/\,/g,"");
					var tot_req_kay2  = Number(tot_req_kay1);
					
					var tot_req_man = $("#totval_" + man).html();
					var tot_req_man1  = tot_req_man.replace(/\,/g,"");
					var tot_req_man2  = Number(tot_req_man1);
					
					var tot_req_pas = $("#totval_" + pas).html();
					var tot_req_pas1  = tot_req_pas.replace(/\,/g,"");
					var tot_req_pas2  = Number(tot_req_pas1);
					
					var tot_req_urd = $("#totval_" + urd).html();
					var tot_req_urd1  = tot_req_urd.replace(/\,/g,"");
					var tot_req_urd2  = Number(tot_req_urd1);
					
					var av = Number($("#avbl_fund").val());
					var tot_ft_sau = (av * (tot_req_sauyo2/ofr3)).toFixed(2);
					var tot_ft_cai = (av * (tot_req_cai2/ofr3)).toFixed(2);
					var tot_ft_cal = (av * (tot_req_cal2/ofr3)).toFixed(2);
					var tot_ft_cav = (av * (tot_req_cav2/ofr3)).toFixed(2);
					var tot_ft_kay = (av * (tot_req_kay2/ofr3)).toFixed(2);
					var tot_ft_man = (av * (tot_req_man2/ofr3)).toFixed(2);
					var tot_ft_pas = (av * (tot_req_pas2/ofr3)).toFixed(2);
					var tot_ft_urd = (av * (tot_req_urd2/ofr3)).toFixed(2);
					var tot_ft_pamp = (av * (tot_req_pamp2/ofr3)).toFixed(2);
					
					$("#totft_" + sau).html(tot_ft_sau);
					$("#totft_" + cai).html(tot_ft_cai);
					$("#totft_" + cal).html(tot_ft_cal);
					$("#totft_" + cav).html(tot_ft_cav);
					$("#totft_" + kay).html(tot_ft_kay);
					$("#totft_" + man).html(tot_ft_man);
					$("#totft_" + pas).html(tot_ft_pas);
					$("#totft_" + urd).html(tot_ft_urd);
					$("#totft_" + pamp).html(tot_ft_pamp);			
										
					}
				});
			 });
			 
			 
			 
			 function c(id){
			 	var myId = id.split("_");
				var sau = '6';
					var cai = '2';
					var cal = '3';
					var cav = '4';
					var kay = '5';
					var man = '12';
					var pas = '9';
					var urd = '10';
                                        var pamp = '7';
							 
				 if(myId[0] == 'exval'){
				 	
				 	var maitaining = Number($('#mbalval_' + myId[1]).val());
					var expense = Number($('#exval_' + myId[1]).val());
					var remaining = (maitaining - expense).toFixed(2);
					//var remaining_split = remaining.toString();
					//var re_split = remaining_split.split(".");
					/*if(remainin_split[0] != ''){
						remaining = (remaining1).toFixed(2);
					}else{
						remaining = (remaining1);
					}*/
					//alert(re_split[1]);
					$('#reval_' + myId[1]).html(remaining);
					var expense = Number($('#exval_' + myId[1]).val());
					var addfund = Number($('#addval_' + myId[1]).val());
					var totreq = (expense + addfund).toFixed(2);
						$('#totval_' + myId[1]).html(totreq); 
						
						var sau = '6';
						var cai = '2';
						var cal = '3';
						var cav = '4';
						var kay = '5';
						var man = '12';
						var pas = '9';
						var urd = '10';
						var pamp = '7';
						
						var reval_sau1 = $('#reval_' + sau).html().replace(/\,/g,"");
						var reval_sau = Number(reval_sau1);
                                                var reval_pamp1 = $('#reval_' + pamp).html().replace(/\,/g,"");
						var reval_pamp = Number(reval_pamp1);
						var reval_cai1 = $('#reval_' + cai).html().replace(/\,/g,"");
						var reval_cai = Number(reval_cai1);
						var reval_cal1 = $('#reval_' + cal).html().replace(/\,/g,"");
						var reval_cal = Number(reval_cal1);
						var reval_cav1 = $('#reval_' + cav).html().replace(/\,/g,"");
						var reval_cav = Number(reval_cav1);
						var reval_kay1 = $('#reval_' + kay).html().replace(/\,/g,"");
						var reval_kay = Number(reval_kay1);
						var reval_man1 = $('#reval_' + man).html().replace(/\,/g,"");
						var reval_man = Number(reval_man1);
						var reval_pas1 = $('#reval_' + pas).html().replace(/\,/g,"");
						var reval_pas = Number(reval_pas1);
						var reval_urd1 = $('#reval_' + urd).html().replace(/\,/g,"");
						var reval_urd = Number(reval_urd1);
						
						var tot_reval = (reval_sau + reval_cai + reval_cal + reval_cav + reval_kay + reval_man + reval_pas + reval_urd + reval_pamp).toFixed(2);
						
						$('#t_reval').html(tot_reval);
						
					var tot_req_sauyo = $("#totval_" + sau).html();
					var tot_req_sauyo1  = tot_req_sauyo.replace(/\,/g,"");
					var tot_req_sauyo2  = Number(tot_req_sauyo1);
                                        
                                        var tot_req_pamp = $("#totval_" + pamp).html();
					var tot_req_pamp1  = tot_req_pamp.replace(/\,/g,"");
					var tot_req_pamp2  = Number(tot_req_pamp1);
					
					var tot_req_cai = $("#totval_" + cai).html();
					var tot_req_cai1  = tot_req_cai.replace(/\,/g,"");
					var tot_req_cai2  = Number(tot_req_cai1);
					
					var tot_req_cal = $("#totval_" + cal).html();
					var tot_req_cal1  = tot_req_cal.replace(/\,/g,"");
					var tot_req_cal2  = Number(tot_req_cal1);
					
					var tot_req_cav = $("#totval_" + cav).html();
					var tot_req_cav1  = tot_req_cav.replace(/\,/g,"");
					var tot_req_cav2  = Number(tot_req_cav1);
					
					var tot_req_kay = $("#totval_" + kay).html();
					var tot_req_kay1  = tot_req_kay.replace(/\,/g,"");
					var tot_req_kay2  = Number(tot_req_kay1);
					
					var tot_req_man = $("#totval_" + man).html();
					var tot_req_man1  = tot_req_man.replace(/\,/g,"");
					var tot_req_man2  = Number(tot_req_man1);
					
					var tot_req_pas = $("#totval_" + pas).html();
					var tot_req_pas1  = tot_req_pas.replace(/\,/g,"");
					var tot_req_pas2  = Number(tot_req_pas1);
					
					var tot_req_urd = $("#totval_" + urd).html();
					var tot_req_urd1  = tot_req_urd.replace(/\,/g,"");
					var tot_req_urd2  = Number(tot_req_urd1);
					
					var t_req = (tot_req_sauyo2 + tot_req_cai2 + tot_req_cal2 + tot_req_cav2 + tot_req_kay2 + tot_req_man2 + tot_req_pas2 + tot_req_urd2 + tot_req_pamp2).toFixed(2);
					$("#t_fund" ).html(t_req);
					
					
					
						var ex_sau1 = $('#exval_' + sau).val().replace(/\,/g,"");
						var ex_sau = Number(ex_sau1);
						var ex_cai1 = $('#exval_' + cai).val().replace(/\,/g,"");
						var ex_cai = Number(ex_cai1);
						var ex_cal1 = $('#exval_' + cal).val().replace(/\,/g,"");
						var ex_cal = Number(ex_cal1);
						var ex_cav1 = $('#exval_' + cav).val().replace(/\,/g,"");
						var ex_cav = Number(ex_cav1);
						var ex_kay1 = $('#exval_' + kay).val().replace(/\,/g,"");
						var ex_kay = Number(ex_kay1);
						var ex_man1 = $('#exval_' + man).val().replace(/\,/g,"");
						var ex_man = Number(ex_man1);
						var ex_pas1 = $('#exval_' + pas).val().replace(/\,/g,"");
						var ex_pas = Number(ex_pas1);
						var ex_urd1 = $('#exval_' + urd).val().replace(/\,/g,"");
						var ex_urd = Number(ex_urd1);
						var ex_pamp1 = $('#exval_' + pamp).val().replace(/\,/g,"");
						var ex_pamp = Number(ex_pamp1);
						
						var tot_ex = (ex_urd + ex_pas + ex_man + ex_kay + ex_cav + ex_cal + ex_cai + ex_sau + ex_pamp).toFixed(2);
						
						$('#t_ex').html(tot_ex);
						//alert(ex_urd1);
					
				 }else if(myId[0] == 'mbalval'){
					 	var sau = '6';
						var cai = '2';
						var cal = '3';
						var cav = '4';
						var kay = '5';
						var man = '12';
						var pas = '9';
						var urd = '10';
						var pamp = '7';
						
						var mbal_pamp = Number($('#mbalval_' + pamp).val());
                                                var mbal_sau = Number($('#mbalval_' + sau).val());
						var mbal_cai = Number($('#mbalval_' + cai).val());
						var mbal_cal = Number($('#mbalval_' + cal).val());
						var mbal_cav = Number($('#mbalval_' + cav).val());
						var mbal_kay = Number($('#mbalval_' + kay).val());
						var mbal_man = Number($('#mbalval_' + man).val());
						var mbal_pas = Number($('#mbalval_' + pas).val());
						var mbal_urd = Number($('#mbalval_' + urd).val());
						
					 	var mbal = Number($('#mbalval_' + myId[1]).val());
						var expense = Number($('#exval_' + myId[1]).val());
						var remaining = (mbal - expense).toFixed(2);
						var tot_mbal = (mbal_sau + mbal_cai + mbal_cal + mbal_cav + mbal_kay + mbal_man + mbal_pas + mbal_urd + mbal_pamp).toFixed(2);
						
						$('#reval_' + myId[1]).html(remaining);
						$('#t_mbal').html(tot_mbal);
						
						var reval_sau1 = $('#reval_' + sau).html().replace(/\,/g,"");
						var reval_sau = Number(reval_sau1);
                                                var reval_pamp1 = $('#reval_' + pamp).html().replace(/\,/g,"");
						var reval_pamp = Number(reval_pamp1);
						var reval_cai1 = $('#reval_' + cai).html().replace(/\,/g,"");
						var reval_cai = Number(reval_cai1);
						var reval_cal1 = $('#reval_' + cal).html().replace(/\,/g,"");
						var reval_cal = Number(reval_cal1);
						var reval_cav1 = $('#reval_' + cav).html().replace(/\,/g,"");
						var reval_cav = Number(reval_cav1);
						var reval_kay1 = $('#reval_' + kay).html().replace(/\,/g,"");
						var reval_kay = Number(reval_kay1);
						var reval_man1 = $('#reval_' + man).html().replace(/\,/g,"");
						var reval_man = Number(reval_man1);
						var reval_pas1 = $('#reval_' + pas).html().replace(/\,/g,"");
						var reval_pas = Number(reval_pas1);
						var reval_urd1 = $('#reval_' + urd).html().replace(/\,/g,"");
						var reval_urd = Number(reval_urd1);
						
						var tot_reval = (reval_sau + reval_cai + reval_cal + reval_cav + reval_kay + reval_man + reval_pas + reval_urd + reval_pamp).toFixed(2);
						
						$('#t_reval').html(tot_reval);
						
				}else if(myId[0] == 'addval'){
				 	var expense = Number($('#exval_' + myId[1]).val());
					var addfund = Number($('#addval_' + myId[1]).val());
					var totreq = (expense + addfund).toFixed(2);
						$('#totval_' + myId[1]).html(totreq); 
						
					var tot_req_sauyo = $("#totval_" + sau).html();
					var tot_req_sauyo1  = tot_req_sauyo.replace(/\,/g,"");
					var tot_req_sauyo2  = Number(tot_req_sauyo1);
                                        
                                        var tot_req_pamp = $("#totval_" + pamp).html();
					var tot_req_pamp1  = tot_req_pamp.replace(/\,/g,"");
					var tot_req_pamp2  = Number(tot_req_pamp1);
					
					var tot_req_cai = $("#totval_" + cai).html();
					var tot_req_cai1  = tot_req_cai.replace(/\,/g,"");
					var tot_req_cai2  = Number(tot_req_cai1);
					
					var tot_req_cal = $("#totval_" + cal).html();
					var tot_req_cal1  = tot_req_cal.replace(/\,/g,"");
					var tot_req_cal2  = Number(tot_req_cal1);
					
					var tot_req_cav = $("#totval_" + cav).html();
					var tot_req_cav1  = tot_req_cav.replace(/\,/g,"");
					var tot_req_cav2  = Number(tot_req_cav1);
					
					var tot_req_kay = $("#totval_" + kay).html();
					var tot_req_kay1  = tot_req_kay.replace(/\,/g,"");
					var tot_req_kay2  = Number(tot_req_kay1);
					
					var tot_req_man = $("#totval_" + man).html();
					var tot_req_man1  = tot_req_man.replace(/\,/g,"");
					var tot_req_man2  = Number(tot_req_man1);
					
					var tot_req_pas = $("#totval_" + pas).html();
					var tot_req_pas1  = tot_req_pas.replace(/\,/g,"");
					var tot_req_pas2  = Number(tot_req_pas1);
					
					var tot_req_urd = $("#totval_" + urd).html();
					var tot_req_urd1  = tot_req_urd.replace(/\,/g,"");
					var tot_req_urd2  = Number(tot_req_urd1);
					
					var t_req = (tot_req_sauyo2 + tot_req_cai2 + tot_req_cal2 + tot_req_cav2 + tot_req_kay2 + tot_req_man2 + tot_req_pas2 + tot_req_urd2 + tot_req_pamp2).toFixed(2);
					$("#t_fund" ).html(t_req);
					
					var tot_fund_sauyo = Number($("#addval_" + sau).val());
					var tot_fund_urd = Number($("#addval_" + urd).val());
					var tot_fund_pas = Number($("#addval_" + pas).val());
					var tot_fund_man = Number($("#addval_" + man).val());
					var tot_fund_kay = Number($("#addval_" + kay).val());
					var tot_fund_cav = Number($("#addval_" + cav).val());
					var tot_fund_cal = Number($("#addval_" + cal).val());
					var tot_fund_cai = Number($("#addval_" + cai).val());
					var tot_fund_pamp = Number($("#addval_" + pamp).val());
					
					
					
					var t_funds = (tot_fund_urd + tot_fund_pas + tot_fund_man + tot_fund_kay + tot_fund_cav + tot_fund_cal + tot_fund_cai + tot_fund_sauyo + tot_fund_pamp).toFixed(2);
					$("#t_fundadd").html(t_funds);
					
				 }else{
				 	var sau = '6';
					var cai = '2';
					var cal = '3';
					var cav = '4';
					var kay = '5';
					var man = '12';
					var pas = '9';
					var urd = '10';
					var pamp = '7';
					
				 	var ofr3 = Number($("#ofr").val());
					
					var tot_req_sauyo = $("#totval_" + sau).html();
					var tot_req_sauyo1  = tot_req_sauyo.replace(/\,/g,"");
					var tot_req_sauyo2  = Number(tot_req_sauyo1);
                                        
                                        var tot_req_pamp = $("#totval_" + pamp).html();
					var tot_req_pamp1  = tot_req_pamp.replace(/\,/g,"");
					var tot_req_pamp2  = Number(tot_req_pamp1);
					
					var tot_req_cai = $("#totval_" + cai).html();
					var tot_req_cai1  = tot_req_cai.replace(/\,/g,"");
					var tot_req_cai2  = Number(tot_req_cai1);
					
					var tot_req_cal = $("#totval_" + cal).html();
					var tot_req_cal1  = tot_req_cal.replace(/\,/g,"");
					var tot_req_cal2  = Number(tot_req_cal1);
					
					var tot_req_cav = $("#totval_" + cav).html();
					var tot_req_cav1  = tot_req_cav.replace(/\,/g,"");
					var tot_req_cav2  = Number(tot_req_cav1);
					
					var tot_req_kay = $("#totval_" + kay).html();
					var tot_req_kay1  = tot_req_kay.replace(/\,/g,"");
					var tot_req_kay2  = Number(tot_req_kay1);
					
					var tot_req_man = $("#totval_" + man).html();
					var tot_req_man1  = tot_req_man.replace(/\,/g,"");
					var tot_req_man2  = Number(tot_req_man1);
					
					var tot_req_pas = $("#totval_" + pas).html();
					var tot_req_pas1  = tot_req_pas.replace(/\,/g,"");
					var tot_req_pas2  = Number(tot_req_pas1);
					
					var tot_req_urd = $("#totval_" + urd).html();
					var tot_req_urd1  = tot_req_urd.replace(/\,/g,"");
					var tot_req_urd2  = Number(tot_req_urd1);
					
					var av = Number($("#avbl_fund").val());
					var tot_ft_sau = (av * (tot_req_sauyo2/ofr3)).toFixed(2);
					var tot_ft_cai = (av * (tot_req_cai2/ofr3)).toFixed(2);
					var tot_ft_cal = (av * (tot_req_cal2/ofr3)).toFixed(2);
					var tot_ft_cav = (av * (tot_req_cav2/ofr3)).toFixed(2);
					var tot_ft_kay = (av * (tot_req_kay2/ofr3)).toFixed(2);
					var tot_ft_man = (av * (tot_req_man2/ofr3)).toFixed(2);
					var tot_ft_pas = (av * (tot_req_pas2/ofr3)).toFixed(2);
					var tot_ft_urd = (av * (tot_req_urd2/ofr3)).toFixed(2);
					var tot_ft_pamp = (av * (tot_req_pamp2/ofr3)).toFixed(2);
					
					$("#totft_" + sau).html(tot_ft_sau);
					$("#totft_" + cai).html(tot_ft_cai);
					$("#totft_" + cal).html(tot_ft_cal);
					$("#totft_" + cav).html(tot_ft_cav);
					$("#totft_" + kay).html(tot_ft_kay);
					$("#totft_" + man).html(tot_ft_man);
					$("#totft_" + pas).html(tot_ft_pas);
					$("#totft_" + urd).html(tot_ft_urd);
					$("#totft_" + pamp).html(tot_ft_pamp);
					//var my = $("#totft_" + urd).html();
					
				}
				
			 }
			 
			 function save(){
					var av = Number($("#avbl_fund").val());
					var myDate = $("#myDate").val()
					var sau = '6';
					var cai = '2';
					var cal = '3';
					var cav = '4';
					var kay = '5';
					var man = '12';
					var pas = '9';
					var urd = '10';
					var pamp = '7';
					var time = $("#hidden-time").val();
				
				var mes = confirm('Do you want to Proceed ?');
						
				if(mes == true){
						
						if(av != '' && av >= 1){
							var ex_sau = $("#expense_" + sau).html();
							var ex_cai = $("#expense_" + cai).html();
							var ex_cav = $("#expense_" + cav).html();
							var ex_kay = $("#expense_" + kay).html();
							var ex_cal = $("#expense_" + cal).html();
							var ex_man = $("#expense_" + man).html();
							var ex_pas = $("#expense_" + pas).html();
							var ex_urd = $("#expense_" + urd).html();
							var ex_pamp = $("#expense_" + pamp).html();
						
							var totreq_sau = $('#totval_' + sau).html().replace(",","");
							var totreq_cai = $('#totval_' + cai).html().replace(",","");
							var totreq_cav = $('#totval_' + cav).html().replace(",","");
							var totreq_kay = $('#totval_' + kay).html().replace(",","");
							var totreq_cal = $('#totval_' + cal).html().replace(",","");
							var totreq_man = $('#totval_' + man).html().replace(",","");
							var totreq_pas = $('#totval_' + pas).html().replace(",","");
							var totreq_urd = $('#totval_' + urd).html().replace(",","");
							var totreq_pamp = $('#totval_' + pamp).html().replace(",","");
							
							var totmbal_sau = $('#mbal_' + sau).html();
							var totmbal_cai = $('#mbal_' + cai).html();
							var totmbal_cav = $('#mbal_' + cav).html();
							var totmbal_kay = $('#mbal_' + kay).html();
							var totmbal_cal = $('#mbal_' + cal).html();
							var totmbal_man = $('#mbal_' + man).html();
							var totmbal_pas = $('#mbal_' + pas).html();
							var totmbal_urd = $('#mbal_' + urd).html();
							var totmbal_pamp = $('#mbal_' + pamp).html();
						
							var addfund_sau = $("#addfund_" + sau).html();
							var addfund_cai = $("#addfund_" + cai).html();
							var addfund_cal = $("#addfund_" + cal).html();
							var addfund_cav = $("#addfund_" + cav).html();
							var addfund_kay = $("#addfund_" + kay).html();
							var addfund_man = $("#addfund_" + man).html();
							var addfund_pas = $("#addfund_" + pas).html();
							var addfund_urd = $("#addfund_" + urd).html();
							var addfund_pamp = $("#addfund_" + pamp).html();
						
							var val_sau = $("#totft_" + sau).html();
							var val_cai = $("#totft_" + cai).html();
							var val_cal = $("#totft_" + cal).html();
							var val_cav = $("#totft_" + cav).html();
							var val_kay = $("#totft_" + kay).html();
							var val_man = $("#totft_" + man).html();
							var val_pas = $("#totft_" + pas).html();
							var val_urd = $("#totft_" + urd).html();
							var val_pamp = $("#totft_" + pamp).html();
					
							var myData = '2=' + val_cai + '&3=' + val_cal + '&4=' + val_cav + '&5=' + val_kay + '&6=' + val_sau+ '&7=' + val_pamp + '&12=' + val_man + '&9=' + val_pas + '&10=' + val_urd + '&av=' + av + '&tr_6=' + totreq_sau + '&tr_2=' + totreq_cai + '&tr_7=' + totreq_pamp + '&tr_4=' + totreq_cav + '&tr_5=' + totreq_kay + '&tr_3=' + totreq_cal + '&tr_12=' + totreq_man + '&tr_9=' + totreq_pas + '&tr_10=' + totreq_urd + '&af_6=' + addfund_sau + '&af_7=' + addfund_pamp + '&af_2=' + addfund_cai + '&af_3=' + addfund_cal + '&af_4=' + addfund_cav + '&af_5=' + addfund_kay + '&af_12=' + addfund_man + '&af_9=' + addfund_pas + '&af_10=' + addfund_urd + '&ex_2=' + ex_cai + '&ex_7=' + ex_pamp + '&ex_3=' + ex_cal + '&ex_4=' + ex_cav + '&ex_5=' + ex_kay + '&ex_6=' + ex_sau + '&ex_12=' + ex_man + '&ex_9=' + ex_pas + '&ex_10=' + ex_urd + '&totmbal_10=' + totmbal_urd + '&totmbal_7=' + totmbal_pamp  + '&totmbal_9=' + totmbal_pas  + '&totmbal_12=' + totmbal_man + '&totmbal_5=' + totmbal_kay + '&totmbal_4=' + totmbal_cav + '&totmbal_3=' + totmbal_cal + '&totmbal_2=' + totmbal_cai + '&totmbal_6=' + totmbal_sau + '&time=' + time + '&myDate=' + myDate;
						
							$.ajax({
								type:'POST',
								url: 'exec/fund_process.php',
								data: myData,
								cache: false
							});
							
							alert("Successful.");
							location.replace("fund_transfer.php");
							
							
						}else{
							alert("Please Enter Available Fund.");
						}
				}else{
						return false;
					}
				}