
/****************************/
var checkIsNotNull=function(ControlId,AlertId,AlertMessage) {
    if ($(ControlId).value.trim()=='') {
        $(AlertId).set('html',AlertMessage);
        $(AlertId).setProperty('class','error');
        $(AlertId).setStyle('display','inline');
        return false;
    }else {
         $(AlertId).set('html','<img src="image/success.png" align="absmiddle" />');
         $(AlertId).setProperty('class','');
        return true;
    }
}
var checkIsNotNullOrZero=function(ControlId,AlertId,AlertMessage) {
    if ($(ControlId).value.trim()==''||$(ControlId).value==0) {
    	$(AlertId).set('html',AlertMessage);
        $(AlertId).setProperty('class','error');
        $(AlertId).setStyle('display','inline');
        return false;
    }else {
         $(AlertId).set('html','');
         $(AlertId).setProperty('class','success');
        return true;
    }
}
var checkControlContentWithRegularExpression=function(ControlId, AlertId, RegularExpression, AlertMessage) {
    if (!RegularExpression.test($(ControlId).value)) {
    	if($(AlertId)){
        $(AlertId).set('html',AlertMessage);
    	}
        return false;
    }else {
    	if($(AlertId)){
         $(AlertId).set('html','');
    	}
        return true;
    }
}
var checkValueWithRegularExpression=function(value, AlertId, RegularExpression, AlertMessage) {
    if (!RegularExpression.test(value)) {
    	if($(AlertId)){
    	$(AlertId).set('html',AlertMessage);
        $(AlertId).setProperty('class','error');
        $(AlertId).setStyle('display','inline');
    	}
        return false;
    }else {
    	if($(AlertId)){
         $(AlertId).set('html','<img src="image/success.png" align="absmiddle" />');
         $(AlertId).setProperty('class','');
    	}
        return true;
    }
}