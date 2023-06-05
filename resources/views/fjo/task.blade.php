@extends('layouts.app')
@section('title', 'View Today Jobs')
<style>
.form-control{
	width: 184px !important;
}

.input-group > .form-control{
	position: !important;
	flex: 0 0 auto !important;
}
</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-cyan large">{{ __('Jobs - Customer') }}</div>
					<nav class="navbar navbar-expand-lg navbar-light bg-light">
					 
					  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
						<div class="navbar-nav">
						  <a class="nav-item nav-link " href="{!!route("fjo.show",["fjo"=>$record->getKey()])!!}">Summary <span class="sr-only">(current)</span></a>
						  <a class="nav-item nav-link " href="{!!route("fjo.customer",["id"=>$record->getKey()])!!}">Customer</a>
						  <a class="nav-item nav-link active" href="{!!route("fjo.task",["id"=>$record->getKey()])!!}">Task</a>
						</div>
					  </div>
					</nav>
				@if(!empty($record->svod_datefrom) && $record->svod_Status == 'New')
				<div class="card-body border border-secondary">
					<button type="button" id="savebut"> Save </button>
					<button type="button" id="editbut"> Edit </button>
				</div>
				@endif
				<div class="card-body border border-secondary">
					<div class="table-responsive">
                        <table class="table table-bordered" id="FJODatatable">
                            <thead class="bg-primary text-white">
                            <tr>
								<th scope="col">@lang("Toner")</th>
                                <th scope="col">@lang("%Empty")</th>
                                <th scope="col">@lang("Spare Toner")</th>
                            </tr>
                            </thead>
                            <tbody>
												
                                <tr>
									<td scope="row">
                                       @lang("K-BLACK")                            
                                    </td>
									<td scope="row">
                                        <div class="font-weight-bold">
										
										<input type="text" id="Svod_TonerEmptyB" value="{!! $record->svod_TonerEmptyB ?? null !!}" class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_TonerEmptyB!!}-->
                                        </div>
                            
                                    </td>
                                    <td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_SpareTonerB" value="{!! $record->svod_SpareTonerB ?? null !!}" class="form-control input-md" disabled/>
                                        <!--!! $record->Svod_SpareTonerB !!}-->
                                        </div>
                            
                                    </td>
                                </tr>
								<tr>
									<td scope="row">
                                       @lang("C-CYAN")                            
                                    </td>
									<td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_TonerEmptyC" value="{!! $record->svod_TonerEmptyC ?? null !!}" class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_TonerEmptyC !!}-->
                                        </div>
                            
                                    </td>
                                    <td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_SpareTonerC" value="{!! $record->svod_SpareTonerC ?? null !!}" class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_SpareTonerC !!}-->
                                        </div>
                            
                                    </td>
                                </tr>
								<tr>
									<td scope="row">
                                       @lang("M-MAGENTA")                            
                                    </td>
									<td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_TonerEmptyM" value="{!! $record->svod_TonerEmptyM ?? null !!}"  class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_TonerEmptyM !!}-->
                                        </div>
                            
                                    </td>
                                    <td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_SpareTonerM" value="{!! $record->svod_SpareTonerM ?? null !!}" class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_SpareTonerM !!}-->
                                        </div>
                            
                                    </td>
                                </tr>
								<tr>
									<td scope="row">
                                       @lang("Y-YELLOW")                            
                                    </td>
									<td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_TonerEmptyY" value="{!! $record->svod_TonerEmptyY ?? null !!}"  class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_TonerEmptyY !!}-->
                                        </div>
                            
                                    </td>
                                    <td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_SpareTonerY" value="{!! $record->svod_SpareTonerY ?? null !!}" class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_SpareTonerY !!}-->
                                        </div>
                            
                                    </td>
                                </tr>
						
                            </tbody>
                        </table>
                    </div>
				</div>
				<div class="card-body border border-secondary">
					<div class="table-responsive">
                        <table class="table table-bordered" id="FJODatatable">
                            <thead class="bg-primary text-white">
                            <tr>
								<th scope="col">@lang("Reading")</th>
                                <th scope="col">@lang("Before")</th>
                                <th scope="col">@lang("After")</th>
                            </tr>
                            </thead>
                            <tbody>
												
                                <tr>
									<td scope="row">
                                       @lang("METER 1")                            
                                    </td>
									<td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_Meter1Before" value="{!! $record->svod_Meter1Before ?? null !!}" class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_Meter1Before!!}-->
                                        </div>
                            
                                    </td>
                                    <td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_Meter1After"  value="{!! $record->svod_Meter1After ?? null !!}"  class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_Meter1After !!}-->
                                        </div>
                            
                                    </td>
                                </tr>
								<tr>
									<td scope="row">
                                       @lang("METER 2")                            
                                    </td>
									<td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_Meter2Before"  value="{!! $record->svod_Meter2Before ?? null !!}" class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_Meter2Before !!}-->
                                        </div>
                            
                                    </td>
                                    <td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_Meter2After" value="{!! $record->svod_Meter2After ?? null !!}" class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_Meter2After!!}-->
                                        </div>
                            
                                    </td>
                                </tr>
								<tr>
									<td scope="row">
                                       @lang("METER 3")                            
                                    </td>
									<td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_Meter3Before" value="{!! $record->svod_Meter3Before ?? null !!}" class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_Meter3Before!!}-->
                                        </div>
                            
                                    </td>
                                    <td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_Meter3After" value="{!! $record->svod_Meter3After ?? null !!}" class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_Meter3After !!}-->
                                        </div>
                            
                                    </td>
                                </tr>
								<tr>
									<td scope="row">
                                       @lang("METER 4")                            
                                    </td>
									<td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_Meter4Before" value="{!! $record->svod_Meter4Before ?? null !!}" class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_Meter4Before !!}-->
                                        </div>
                            
                                    </td>
                                    <td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_Meter4After" value="{!! $record->svod_Meter4After ?? null !!}"  class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_Meter4After !!}-->
                                        </div>
                            
                                    </td>
                                </tr>
								<tr>
									<td scope="row">
                                       @lang("TESTED PRINT")                            
                                    </td>
									<td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_TestedPrintB" value="{!! $record->svod_TestedPrintB ?? null !!}"  class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_TestedPrintB !!}-->
                                        </div>
                            
                                    </td>
                                    <td scope="row">
                                        <div class="font-weight-bold">
										<input type="text" id="Svod_TestedPrintA" value="{!! $record->svod_TestedPrintA ?? null !!}" class="form-control input-md" disabled/>
                                        <!--{!! $record->Svod_TestedPrintA !!}-->
                                        </div>
                            
                                    </td>
                                </tr>
						
                            </tbody>
                        </table>
                    </div>
				</div>
				
				@if(!empty($soitems))
				<div class="card-body">
                    @foreach($soitems as $soitem)
					
					<div class="card-body border border-secondary">
						<div>Product Code : {!! $soitem->svit_productid !!}</div>
						<div>Description : {!! $soitem->svit_description !!}</div>
						<div>Quantity :  {!! number_format($soitem->svit_quantity) !!}</div>
						<button class="btn btn-delete btn-secondary" deleteid="{!! $soitem->svit_ServiceOrderItemId !!}">Delete</button>
					</div>
					@endforeach
                </div>
				@endif
				
				<div class="card-body border border-secondary">
				
					<div style="justify-content: space-between; display:flex"><div class="font-weight-bold">Type of Call : </div> <input type="text" id="Svod_Type" value="{!! $record->svod_type ?? null !!}" disabled/></div>
					<div style="justify-content: space-between; display:flex"><div class="font-weight-bold">Service Type : </div>
					
					<select class="form-control form-control-sm" name="svod_ServiceType" id="svod_ServiceType" disabled style="justify-content: space-between;">
						@foreach ($selections as $key => $value)
						<option value="{{ $key }}" {{ ($key == $record->svod_ServiceType) ? 'selected' : '' }}> 
						  {{ $value }} 
						</option>
						@endforeach

				
					</select></div>
                    
					<div style="justify-content: space-between; display:flex"><div class="font-weight-bold">Job Carried Out : </div> <input type="text" id="Svod_Note" value="{!! $record->svod_note  ?? null !!}" disabled/></div>
					<div style="justify-content: space-between; display:flex"><div class="font-weight-bold">Replacement of Parts : </div>  <input type="text" id="Svod_PartsReplacement" value="{!! $record->svod_PartsReplacement  ?? null !!}" disabled/></div>
					<div style="justify-content: space-between; display:flex"><div class="font-weight-bold">Remarks : </div> <input type="text" id="Svod_Remarks" value="{!! $record->svod_remarks  ?? null !!}" disabled/></div>
					
					
					
					
					
                </div>
				
				<div class="card-body border border-secondary">
					<div id="ssaProduct" style="justify-content: space-between; display:flex; flex-wrap: wrap;">
							<div class="font-weight-bold">{!! __('columns.prod_code') !!} : </div>
							<div>
							<div class='input-group'>
								<input type="text"  class="form-control input-ssa" id="productid_text" name="productid_text" value="{{ ($productid ?? 'invalid') !== 'invalid' ? (($productid ?? null) == null ? '' : ($productid_text ?? null)) : "" }}" />
								<input id="productid" name="productid" class="hidden-ssa" type="hidden" value="{{ $productid ?? null }}" />
								<span class="search-ssa"><img alt="search" src="{{ asset('icon/searchbut.svg?v=1.0.0') }}"></span>
							</div>
							</div>
					</div>
					<div id="ssaProduct" style="justify-content: space-between; display:flex; flex-wrap: wrap;">
							<div class="font-weight-bold">Quantity : </div>  
							<input type="text" id="Quantity" style="width:220px"/>
					</div>
					<button id="additem" class="btn btn-secondary" class="btn btn-outline-secondary">{{ __('Add Item') }}</button>
				</div>
				
				
                <div class="card-body">
					<a href="{!!route("fjo.customer",["id"=>$record->getKey()])!!}" class="btn btn-outline-secondary">{{ __('Back') }}</a>
                </div>
            </div>
			
			
			
        </div>
    </div>
</div>



				
@endsection
@section("script")
<script src="{!! route("javascript.ssa", ["u" => "ajax-ssa"]) !!}"></script>

<script>
	$(".btn-delete").on("click",function() {
	let _this = $(this), _deleteid= _this.attr("deleteid") || "";
		
		confirm = confirm("Confirm delete?");
		
		if(confirm){
			$.ajax({
				url: "{!! route("fjo.delete") !!}",
				type: "post", //or get
				data: {
				"id" : _deleteid
				},
				success: function(_data){
					alert(_data.success);
					location.reload();
				}, //what to do if success
				error: function(_xhr, _stt, _st){

					alert("Error on delete.");

				}, //what to do if error
				complete: function(){

				} //what to do if complete (either success or error)
			});
		}
	});	
	$(function(){
	
		var ssaOptions = {
        connection: "sqlsrv",
        model: 'NewProduct',
        column: ["prod_name", "prod_productfamilyid", "prod_code"],
        caption: "prod_code",
        value: "Prod_ProductID",
        where: "Prod_ProductID IN (SELECT Prod_ProductID FROM NewProduct WHERE prod_productfamilyid=13)"
		};
		$("#ssaProduct").SetSSA(ssaOptions);
		
		
		$("#additem").on("click", function() {
		$.ajax({
				url: "{!!route("fjo.add",["id"=>$record->getKey()])!!}",
				type: "post", //or get
				data: { 
				
				"svit_quantity" : $("#Quantity").val() , 
				"svit_product":$("#productid").val(),
				"Inva_secterr": "{!! $record->svod_Secterr !!}"
				
				
				},
				dataType: "json", //if the returned values need to be in json format
				success: function(_data){
					
					$("#Quantity").val('');
					$("#productid").val('');
					$("#productid_text").val('');
					alert(_data.success);
					location.reload();
				}, //what to do if success 
				error: function(_xhr, _stt, _st){
					
					alert("Error on Adding Item");

				}, //what to do if error
				complete: function(){

				} //what to do if complete (either success or error)
			})
		
		});
		
	
	
		$("#editbut").on("click", function() {
			$("#Svod_TonerEmptyB").attr('disabled',false);
			$("#Svod_SpareTonerB").attr('disabled',false);
			$("#Svod_TonerEmptyC").attr('disabled',false);
			$("#Svod_SpareTonerC").attr('disabled',false);
			$("#Svod_TonerEmptyM").attr('disabled',false);
			$("#Svod_SpareTonerM").attr('disabled',false);
			$("#Svod_TonerEmptyY").attr('disabled',false);
			$("#Svod_SpareTonerY").attr('disabled',false);
			$("#Svod_Meter1Before").attr('disabled',false);
			$("#Svod_Meter1After").attr('disabled',false);
			$("#Svod_Meter2Before").attr('disabled',false);
			$("#Svod_Meter2After").attr('disabled',false);
			$("#Svod_Meter3Before").attr('disabled',false);
			$("#Svod_Meter3After").attr('disabled',false);
			$("#Svod_Meter4Before").attr('disabled',false);
			$("#Svod_Meter4After").attr('disabled',false);
			$("#Svod_TestedPrintB").attr('disabled',false);
			$("#Svod_TestedPrintA").attr('disabled',false);
			
			$("#Svod_Type").attr('disabled',false);
			$("#svod_ServiceType").attr('disabled',false);
			$("#Svod_Note").attr('disabled',false);
			$("#Svod_PartsReplacement").attr('disabled',false);
			$("#Svod_Remarks").attr('disabled',false);

			

				
		});
		
		$("#savebut").on("click", function() {
			$.ajax({
				url: "{!!route("fjo.save",["id"=>$record->getKey()])!!}",
				type: "post", //or get
				data: {
					"Svod_TonerEmptyB":$("#Svod_TonerEmptyB").val(),
					"Svod_SpareTonerB":$("#Svod_SpareTonerB").val(),
					"Svod_TonerEmptyC":$("#Svod_TonerEmptyC").val(),
					"Svod_SpareTonerC":$("#Svod_SpareTonerC").val(),
					"Svod_TonerEmptyM":$("#Svod_TonerEmptyM").val(),
					"Svod_SpareTonerM":$("#Svod_SpareTonerM").val(),
					"Svod_TonerEmptyY":$("#Svod_TonerEmptyY").val(),
					"Svod_SpareTonerY":$("#Svod_SpareTonerY").val(),
					"Svod_Meter1Before":$("#Svod_Meter1Before").val(),
					"Svod_Meter1After":$("#Svod_Meter1After").val(),
					"Svod_Meter2Before":$("#Svod_Meter2Before").val(),
					"Svod_Meter2After":$("#Svod_Meter2After").val(),
					"Svod_Meter3Before":$("#Svod_Meter3Before").val(),
					"Svod_Meter3After":$("#Svod_Meter3After").val(),
					"Svod_Meter4Before":$("#Svod_Meter4Before").val(),
					"Svod_Meter4After":$("#Svod_Meter4After").val(),
					"Svod_TestedPrintB":$("#Svod_TestedPrintB").val(),
					"Svod_TestedPrintA":$("#Svod_TestedPrintA").val(),
			
					"Svod_Type":$("#Svod_Type").val(),
					"Svod_ServiceType":$("#svod_ServiceType option:selected").val(),
					"Svod_Note":$("#Svod_Note").val(),
					"Svod_PartsReplacement":$("#Svod_PartsReplacement").val(),
					"Svod_Remarks":$("#Svod_Remarks").val()
					

				},
				dataType: "json", //if the returned values need to be in json format
				success: function(_data){
					$("#Svod_TonerEmptyB").text(_data.Svod_TonerEmptyB);
					$("#Svod_TonerEmptyB").attr('disabled',true);
					$("#Svod_SpareTonerB").attr('disabled',true);
					$("#Svod_TonerEmptyC").attr('disabled',true);
					$("#Svod_SpareTonerC").attr('disabled',true);
					$("#Svod_TonerEmptyM").attr('disabled',true);
					$("#Svod_SpareTonerM").attr('disabled',true);
					$("#Svod_TonerEmptyY").attr('disabled',true);
					$("#Svod_SpareTonerY").attr('disabled',true);
					$("#Svod_Meter1Before").attr('disabled',true);
					$("#Svod_Meter1After").attr('disabled',true);
					$("#Svod_Meter2Before").attr('disabled',true);
					$("#Svod_Meter2After").attr('disabled',true);
					$("#Svod_Meter3Before").attr('disabled',true);
					$("#Svod_Meter3After").attr('disabled',true);
					$("#Svod_Meter4Before").attr('disabled',true);
					$("#Svod_Meter4After").attr('disabled',true);
					$("#Svod_TestedPrintB").attr('disabled',true);
					$("#Svod_TestedPrintA").attr('disabled',true);
					
					$("#Svod_Type").attr('disabled',true);
					$("#svod_ServiceType").attr('disabled',true);
					$("#Svod_Note").attr('disabled',true);
					$("#Svod_PartsReplacement").attr('disabled',true);
					$("#Svod_Remarks").attr('disabled',true);
				

				}, //what to do if success 
				error: function(_xhr, _stt, _st){
					if (_xhr.status == 500) {
						alert("Input Error");
					}
				}, //what to do if error
				complete: function(){

				} //what to do if complete (either success or error)
			})
			
		});
		
	});
</script>	
@endsection