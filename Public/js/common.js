///省市区 联动 省份下的城市
function choice_province(str){
  var pv=$('.'+str+'_p').val();
  if(pv==0){
     $('.'+str+'_c').val(0);
  }else{
       $.ajax({ 
            url: get_city_url,
            type:'post',
            data: {provinceid:pv},
            dataType: 'json',
            success: function(data){
              //console.log(data);
              if(data){
                var option='<option value="0">请选择</option>';
                 $.each(data, function(i, value) {
                //this指向当前元素
                //i表示Array当前下标
                //value表示Array当前元素
                 option=option+'<option value="'+value.cityid+'">'+value.cityname+'</option>'
                 });
                 $('.'+str+'_c').html(option);
              }
            }
       });
  }
   $('.'+str+'_a').val(0);  
}
///省市区 联动 城市下的区县
function choice_city(str){
     var pv=$('.'+str+'_c').val();
  if(pv==0){
      $('.'+str+'_a').val(0);  
  }else{
       $.ajax({ 
            url: get_city_url,
            type:'post',
            data: {cityid:pv},
            dataType: 'json',
            success: function(data){
              //console.log(data);
              if(data){
                var option='<option value="0">请选择</option>';
                 $.each(data, function(i, value) {
                //this指向当前元素
                //i表示Array当前下标
                //value表示Array当前元素
                 option=option+'<option value="'+value.cityid+'">'+value.cityname+'</option>'
                 });
                 $('.'+str+'_a').html(option);
              }
            }
       });
  }
  
}
///回到顶部
 $(function () {
  //当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失
            $(window).scroll(function(){
                if ($(window).scrollTop()>100){
                    $(".bottom-to-top").fadeIn(500);
                }
                else
                {
                    $(".bottom-to-top").fadeOut(500);
                }
            });
 
            //当点击跳转链接后，回到页面顶部位置
 
            $(".bottom-to-top").click(function(){
                $('body,html').animate({scrollTop:0},500);
                return false;
            });
        });