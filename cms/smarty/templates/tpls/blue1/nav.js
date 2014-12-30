function L(){
	$("#draggable").css({"left":"-100px","top":"0px"});
	}
	
		$(function(){
		n=$('#draggable a').size();	
		var wh=100*n+"%";
		$('#draggable').width(wh);
		var lt=(100/n/3);
		var lt_li=lt+"%";
		$('#draggable a').width(lt_li);
		var y=0;
		var w=2;						
				if(y==-lt*w){
 						$("#leftbtn").hide();
					}	
					if(y==0){
						$("#rightbtn").hide();
					}		
			$("#leftbtn").click(function() {
				if(y!=-lt*w){
					$("#rightbtn").show();
					$("#leftbtn").show();
					console.log("y:"+y);
					y=y-lt;								
					var t=y+"%";									
					$("#draggable").css({'-webkit-transform':"translate("+t+")",'-webkit-transition':'300ms linear'} );	
					$("#draggable").css({'-moz-transform':"translate("+t+")",'-moz-transition':'300ms linear'} );
					//console.log("y:"+y);
					console.log("t:"+t);
					if(y==-lt*w){
 						$("#leftbtn").hide();
					}	
				}
			});
			
			$("#rightbtn").click(function() {
				if(y!=0){
					$("#rightbtn").show();
					$("#leftbtn").show();
					y=y+lt;
					var t=y+"%";
					$("#draggable").css({'-webkit-transform':"translate("+t+")",'-webkit-transition':'300ms linear'} );	
					$("#draggable").css({'-moz-transform':"translate("+t+")",'-moz-transition':'300ms linear'} );	
					
					if(y==0){
						$("#rightbtn").hide();
					}
				}
			});
	});	