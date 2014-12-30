mooAutoObject.initTree=function(){
	$$('.tog').each(function(el){
		//var treeSlide=new Fx.Slide(el.getProperty('rel'));
		el.addEvent('click',function(e){
			var togImgSrc=el.getProperty('src');
			if(togImgSrc.contains('minus')){
				el.setProperty('src','image/plus.gif');
				$(el.getProperty('rel')).fade();
				(function(){$(el.getProperty('rel')).setStyle('display','none');}).delay(500);
			}else{
				el.setProperty('src','image/minus.gif');
				$(el.getProperty('rel')).fade('in');
				$(el.getProperty('rel')).setStyle('display','block');
			}
			//e.stop();
			//treeSlide.toggle();
		});
	});
}