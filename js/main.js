/*  Author: Grapheme Group
 *  http://grapheme.ru/
 */
 
var mt = mt || {};
mt.baseURL = 'http://house2trade/';
mt.currentURL = window.location.href;
mt.currentElement = 0;
mt.isValidEmailAddress = function(emailAddress){
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
};
mt.isValidPhone = function(phoneNumber){
	var pattern = new RegExp(/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i);
	return pattern.test(phoneNumber);
};
mt.textLineFilter = function(string){if(string != null){return string.replace(/[&,=]/,' ')}else{return '';}}
mt.setJsonRequest = function(request,functionName){$.each(request,function(index,value){$("#"+index)[functionName](value);});}
mt.formSerialize = function(objects){
	var data = '';
	$(objects).each(function(i,element){
		var value = mt.textLineFilter($(element).val());
		$(element).val(value);
		var name = $(element).attr('name');
		if(data === ''){data = name+"="+value;
		}else{data = data+"&"+name+"="+value;}
	});
	return data;
};
mt.matches_parameters = function(parameter1,parameter2){
	var param1 = new String(parameter1);
	var param2 = new String(parameter2);
	if(param1.toString() == param2.toString()){return true;}
	return false;
};
mt.exist_email = function(emailInput){
	var user_email = $(emailInput).val();
	$(emailInput).tooltip('destroy');
	if(user_email != ''){
		if(!mt.isValidEmailAddress(user_email)){$(emailInput).attr('data-original-title','Not valid email address').tooltip('show');}
		else{
			$.post(mt.baseURL+"valid/exist-email",{'parametr':user_email,'type':'email'},
				function(data){if(!data.status){$(emailInput).attr('data-original-title','Email already exist').tooltip('show');}},"json");
		}
	}
};
mt.redirect = function(path){window.location=path;}
mt.minLength = function(string,Len){if(string != ''){if(string.length < Len){return false}}return true}
mt.FieldsIsNotNumeric = function(formObject){
	var result = {};var num = 0;
	$(formObject).find("input.digital").each(function(i,element){if(($(element).val() != '') && (!$.isNumeric($(element).val()))){result[num] = $(element).attr('id');num++;}});
	$(formObject).find("input.numeric-float").each(function(i,element){if(($(element).val() != '') && (!$.isNumeric($(element).val()))){result[num] = $(element).attr('id');num++;}});
	if($.isEmptyObject(result)){return false;}else{return result;}
}
$(function(){
	$.fn.exists = function(){return $(this).length;}
	$.fn.emptyValue = function(){if($(this).val() == ''){return true;}else{return false;}}
	$.fn.ForceMaxValue = function(){
		$(this).keyup(function(){
			var value = parseInt($(this).val().trim());
			var maxValue = parseInt($(this).attr('data-max-value').trim());
			if($.isNumeric(value) && $.isNumeric(maxValue)){
				if(value > maxValue){$(this).val(maxValue)}else{$(this).val(value);}
			}
		});
		return false;
	};
	$.fn.ForceNumericOnly = function(){
		return this.each(function(){
			$(this).keydown(function(e){
				var key = e.charCode || e.keyCode || 0;
				return(key == 8 || key == 9 || key == 46 || (key >= 37 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
			});
		});
	};
	$(".none").click(function(event){event.preventDefault();});
	$("input.valid-numeric").ForceNumericOnly();
	$("input.valid-max-value").ForceMaxValue();
	$(":input.unique-email").blur(function(){mt.exist_email(this);});
	$("a.link-operation-account").click(function(){mt.currentElement = $(this).attr("data-src");});
	$("#btn-modal-confirm-user").click(function(){
		if(mt.currentElement){
			var url = $("a.link-operation-account[data-src='"+mt.currentElement+"']").attr('data-url');
			$.post(url,{'parameter':mt.currentElement},function(data){
				if(data.status){mt.redirect(data.redirect);}
			},"json");
		}
	});
	$("#search-properties").submit(function(){
		var error = true;$("#form-request").html('');
		$(this).find("input[type='text'],select").each(function(i,element){if(!$(element).emptyValue()){error = false;}});
		if(!error){
			var postdata = mt.formSerialize($("#search-properties :input"));
			$("span.wait-request").removeClass('hidden');
			$.post(mt.baseURL+"search-properties",{'postdata':postdata},function(data){if(data.status){mt.redirect(data.redirect);}else{$("#form-request").html(data.message);$("span.wait-request").addClass('hidden');}},"json");
		}else{$("#form-request").html('Not specified your search terms!');}
		return false;
	});
	$("#a-search-property").click(function(){
		$(this).remove();
		$("#div-search-property").removeClass('hidden');
	});
	
	$('#banner-slider .grid_3 .btn-submit').click(function(e){
		e.preventDefault();
		$('html, body').animate({scrollTop: 500}, 2000);
	});
});