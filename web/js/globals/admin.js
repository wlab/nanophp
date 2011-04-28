//************************************************************************
// Site JS Core - Extend with Ajax Class
//************************************************************************
NJSCORE.extend('Admin',function(){
	this.setValueAndSubmitForm = function(elmId,elmValue,formId){
		$('#'+elmId).attr('value',elmValue);
		$('#'+formId).submit();
	}
	this.applySort = function(fieldName,formId){
		$('#orderby-field').attr('value',fieldName);
		if($('#orderby-order').attr('value')=='asc'){
			this.setValueAndSubmitForm('orderby-order','desc',formId);
		} else {
			this.setValueAndSubmitForm('orderby-order','asc',formId);
		}
	}
});