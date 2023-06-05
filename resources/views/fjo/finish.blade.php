@extends('layouts.app')
@section('title', 'View Today Jobs')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-cyan large">{{ __('Jobs - Summary') }}</div>
					<nav class="navbar navbar-expand-lg navbar-light bg-light">
					 
					  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
						<div class="navbar-nav">
						  <a class="nav-item nav-link active" href="{!!route("fjo.show",["fjo"=>$record->getKey()])!!}">Summary <span class="sr-only">(current)</span></a>
						  <a class="nav-item nav-link" href="{!!route("fjo.customer",["id"=>$record->getKey()])!!}">Customer</a>
						  <a class="nav-item nav-link" href="{!!route("fjo.task",["id"=>$record->getKey()])!!}">Task</a>
						</div>
					  </div>
					</nav>
				
				<div class="card-body">
					<label for="svod_datefrom">{!! __('columns.svod_datefrom') !!}</label> <span id="checkintime">{!! optional($record->svod_datefrom)->format("d/m/Y H:i:s") !!}</span>
					
				</div>
				
				<div class="card-body">
					<label for="svod_dateto">{!! __('columns.svod_dateto') !!}</label> <span id="checkouttime"> {!! optional($record->svod_dateto)->format("d/m/Y H:i:s") !!}</span>
				</div>
				
				<div class="card-body">
					<label for="svod_InternalRemark">{!! __('Internal Remarks:') !!}</label>
					{!! $record->svod_InternalRemark !!}
				</div>
				
					
                <div class="card-body">
                    <button id="checkin" class="btn btn-outline-success" {!! empty($record->svod_datefrom) && empty($record->svod_dateto) ? "" : " hidden" !!} >{{ __('CHECK IN') }}</button>&nbsp
					<button id="checkout" class="btn btn-secondary" {!! !empty($record->svod_datefrom) && empty($record->svod_dateto) ? "" : " hidden" !!}>{{ __('CHECK OUT') }}</button>&nbsp
					<!--<button id="previewjo" class="btn btn-secondary">{{ __('PREVIEW JOB ORDER') }}</button>&nbsp-->
					<a href="{!!route("fjo.customer",["id"=>$record->getKey()])!!}" class="btn btn-outline-secondary">{{ __('NEXT') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("script")
<script>

	

	$(function(){
	
	
	
	
		$("#checkin").on("click", function() {
			$.ajax({
				url: "{!!route("fjo.checkin",["id"=>$record->getKey()])!!}",
				type: "post", //or get
				data: {

				},
				dataType: "json", //if the returned values need to be in json format
				success: function(_data){
				
					$("#checkin").attr('disabled',true);
					$("#checkout").attr('disabled',false);
					$("#checkintime").text(_data.now);

				}, //what to do if success 
				error: function(_xhr, _stt, _st){
					
					alert("Error on Check In");

				}, //what to do if error
				complete: function(){

				} //what to do if complete (either success or error)
			})
		
		});
		
		
		$("#checkout").on("click", function() {
			$.ajax({
				url: "{!!route("fjo.checkout",["id"=>$record->getKey()])!!}",
				type: "post", //or get
				data: {

				},
				dataType: "json", //if the returned values need to be in json format
				success: function(_data){
				
					$("#checkin").attr('disabled',true);
					$("#checkout").attr('disabled',true);
					$("#checkouttime").text(_data.now);
					location.href='{!!route("fjo.signature",["id"=>$record->getKey()])!!}';

				}, //what to do if success 
				error: function(_xhr, _stt, _st){
					
					alert("Error on Check out");

				}, //what to do if error
				complete: function(){

				} //what to do if complete (either success or error)
			})
		
		});
		
 
	});
</script>
@endsection
