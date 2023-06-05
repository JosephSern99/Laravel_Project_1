$(function(){

    $(".btn-form-clear").on("click", function(){
        var frm = $(this).closest("form");
        frm.find("input:not([type='submit']):not([type='hidden']):visible, select:visible, textarea:visible").val("");
    });

	var dtp_Selector = $(".input-datepicker");

	if(dtp_Selector.length > 0){
		dtp_Selector.datetimepicker({
			format: "DD/MM/YYYY"
		});
	}
});

$.ajaxSetup({
	headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function showOverlay(x){
    x = defaultBody(x);
    generateOverlay(x);
    x.find(">.div-overlay").removeClass("d-none");
}

function generateOverlay(x){
    x = defaultBody(x);
    x.css("position", "relative");
    if(x.find(">.div-overlay").length == 0){
        x.append('<div class="div-overlay d-none"><div class="div-overlay-loading"></div></div>');
    }
}

function hideOverlay(x){
    x = defaultBody(x);
    x.find(">.div-overlay").addClass("d-none");
}

function defaultBody(x){
    return x ? x : $("body");
}

function isArray(x){
    return Object.prototype.toString.call(x) === "[object Array]";
}

function isString(x){
    return Object.prototype.toString.call(x) === "[object String]";
}

function returnArray(x){
    return isArray(x) ? x : [];
}

function returnString(x){
    return isString(x) ? x : "";
}

function replaceHTML(x){
    return returnString(x).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function hasHistory(){
    return (window.history && window.history.replaceState);
}

function float(x, y){
    return parseFloat(x).toFixed(y);
}
