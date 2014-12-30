    $('.button-reload').click(function(){
        $('#prize_rank option:first').attr('selected',true)
        window.location.reload();
    });

    userinterval = setInterval(loadUser,loadTime);

    function loadUser(){
        var url = '/index.php?g=User&m=Scene&a=loadUser';
        $.getJSON(url, {'id':id}, function(data){
            if(data.err == 0){
                $('.usercount-label b').html(data.count);
            }
            
        })
    }
    $("#prize_rank").change( function() {
        var val = $(this).val();
        var url = '/index.php?g=User&m=Scene&a=prize_data';
        $('.lottery-right').html('');
        if(val != ''){
            $.getJSON(url, {'id':id,'pid':val}, function(data){
                $('#prize_num').html(data.prize_num);
                if(data.prize_user){
                    var html = createHtml( data.prize_user);
                    $('.lottery-right').html(html);
                }  
            }); 
        }else{
            $('#prize_num').html(0);
        }
 
    }); 



    $('.button-run').click(function(){
        var prize_id = $('#prize_rank').val();
        var url = '/index.php?g=User&m=Scene&a=get_lottery';
        if(prize_id){  
            $.getJSON(url, {'id':id,'pid':prize_id}, function(data){
                if(data.err>0){     
                    clearInterval(interval);
                    alert(data.info);   
                }else{
                    interval = setInterval(function(){
                        var len = data.res.length-1;
                        var i = GetRandomNum(0,len);
                        $('#header').css('background-image','url('+data.res[i].portrait+')');
                        $('#header .nick-name').html(data.res[i].nickname);
                        
                        $('.button-run').hide();
                        $('.button-stop').show();
                    },100);    
                }    
            });  
        }else{
            alert("请先选择奖项");
        }
    });

    $('.button-stop').click(function(){
        clearInterval(interval);

        var prize_id = $('#prize_rank').val();
        var url      = '/index.php?g=User&m=Scene&a=lottery_ok';

        $.getJSON(url, {'id':id,'pid':prize_id}, function(data){
                $('#header').css('background-image','url('+data[0].portrait+')');
                $('#header .nick-name').html(data[0].nickname);
                
                $('.button-run').show();
                $('.button-stop').hide();

                var html    = createHtml(data,true);
                var right   = $('.lottery-right');

                right.html(html+right.html());
                var num = $('#prize_num').html()*1;     
                $('#prize_num').html(num-1);
        });      
    });


function GetRandomNum(Min,Max){
    var Range   = Max - Min;
    var Rand    = Math.random();
    return(Min + Math.round(Rand * Range));   
}


    function createHtml(data){
        var len     = data.length;
        var html    = '';
        var num     = $('.lottery-right').children().length;

        for (var i = len - 1; i >= 0; i--) {
            html += '<div class="result-line" style="display: block;"><div class="result-num">'+(num+i+1)+'</div><div class="user"  style="background-image: url('+data[i].portrait+');"><span class="nick-name">'+data[i].nickname+'</span></div></div>';
        };
        return html;
    }