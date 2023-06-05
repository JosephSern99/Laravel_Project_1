@extends('layouts.app')
@section('title', 'View Today Jobs')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-cyan large">{{ __('Jobs Order') }}</div>
				<div class="card-body border border-secondary" id="signature">
					<div class="table-responsive" >
					    
                    </div>
				</div>
				
				<div class="card-body border border-secondary">
					<div class="table-responsive">
				
						<div> Name : *  <input type="text"  value="{!! $record->svod_custname  ?? null !!}" id="svod_custname"/></div>
                       
                    </div>
				</div>
	
				
                <div class="card-body">
					<a href="{!!route("fjo.show",["fjo"=>$record->getKey()])!!}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>&nbsp
					@if(empty($record->svod_sign))
					<button id="clear" class="btn btn-secondary" class="btn btn-outline-secondary">{{ __('Clear') }}</button>&nbsp
					@endif
					<button id="finish" class="btn btn-secondary" class="btn btn-outline-secondary">{{ __('Finish') }}</button>
                </div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')



<script>
	


	
	var sign = "{!! $record->svod_sign !!}"

	$(function(){

	
	$("#clear").on("click", function() {
		const context = canvas.getContext('2d');
		context.clearRect(0, 0, canvas.width, canvas.height);	
		}
		
	);
	
	
	
	if(sign!=""){
		$("#signature").append("<img class='signimg' src='data:image/png;base64,"+sign+"'>");
		
		$("#finish").on("click", function() { 
		
			location.href='{!!route("fjo.show",["fjo"=>$record->getKey()])!!}';
			
		})

	}
	
	
	else if(sign=="") {
	$("#signature").append('<div id="dvSign" style=" width: 496px; height: 300px;">' +
		'<canvas id="canvas" width="496" height="300"></canvas></div>');
		var canvas = document.getElementById("canvas");
		var signaturePad = new SignaturePad(canvas,{
				backgroundColor : "rgb(255,255,255)"
			}); 
			
	$("#finish").on("click", function() {
	
			
	
			var Pic = document.getElementById("canvas").toDataURL("image/png",1);
			Pic = Pic.replace(/^data:image\/(png|jpg);base64,/, "");
			
			if ($("#svod_custname").val() == ""){
				alert("Please enter your name");
			}
			else{
			$.ajax({
				url: "{!!route("fjo.finish",["id"=>$record->getKey()])!!}",
				type: "post", //or get
				data: { Pic: Pic , "Svod_CustName":$("#svod_custname").val()},
				dataType: "text", //if the returned values need to be in json format
				success: function(_data){
					location.href='{!!route("fjo.show",["fjo"=>$record->getKey()])!!}';
				}, //what to do if success 
				error: function(_xhr, _stt, _st){
					
					alert("Error on Signing");

				}, //what to do if error
				complete: function(){

				} //what to do if complete (either success or error)
			})
			}
		
		}
		
		);
		
		
	}
	
	
		
 });

</script>
@endsection