@extends('layouts.app')
@section('title', __('table.fjoheader'))
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-cyan large">{{ __('Job Order Listing') }}</div>

                <div class="card-body border-bottom">
                    <form role="form" action="{{ URL::current() }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="svod_date">{!! __('columns.svod_date') !!}</label>
                                        <input type="text" class="form-control input-datepicker" name="svod_date" id="svod_date" placeholder="{!! __('columns.svod_date') !!}" value="{!! $data['svod_date'] ?? null !!}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="svod_custname">{!! __('columns.svod_custname') !!}</label>
                                        <input type="text" class="form-control" name="svod_custname" id="svod_custname" placeholder="{!! __('columns.svod_custname') !!}" value="{!! $data['svod_custname']  ?? null !!}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="svod_Secterr">{!! __('columns.svod_Secterr') !!}</label>
                                        <select class="form-control" name="svod_Secterr" id="svod_Secterr">
                                            <option value="">-- None --</option>
                                            @foreach($selections as $key => $value)
                                            <option value="{!! $key !!}" {!! ($data['svod_Secterr'] ?? null) == $key ? " selected" : ""!!}>{!! $value !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> {!! __('label.search') !!}</button>
                                <button type="button" class="btn ml-2 btn-form-clear"><i class="fas fa-eraser"></i> {!! __('label.clear') !!}</button>
								<!--<a href="{!!route("fjo.index")!!}" class="btn ml-2 btn-form-clear"><i class="fas fa-eraser"></i> {!! __('label.clear') !!}</a>-->
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    @foreach($records as $record)
					<a href="{!!route("fjo.show",["fjo"=>$record->getKey()])!!}">
					<div class="card-body border border-secondary">
						<div>Service Order Number: {!! $record->svod_Name !!}</div>
						<div>Territory : {!! optional($record->territory)->Terr_Caption !!}</div>
						<div>Date & Time :  {!! optional($record->svod_date)->format("d/m/Y H:i:s") !!}</div>
						<div>Company Name / Person In Charge :  {!! optional($record->Company)->Comp_Name !!} /  {!! optional($record->Person)->Pers_LastName !!}</div>
						<div>Contact Number : {!! optional($record->Contact)->Pers_PhoneNumber !!} </div>
						<div>Location Address : {!! optional($record->Address)->Addr_Address1 !!} </div>
						<div>Internal Remarks : {!! $record->svod_InternalRemark !!}</div>
						<div>Status : {!! $record->svod_Status !!}</div>

					</div></a>
				@endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<!--<script src="{{ asset('js/bom.js?v=1.1.0') }}"></script>-->
<script src="{!! route("javascript.ssa", ["u" => "ajax-ssa"]) !!}"></script>
<script>
$(function(){
	/*
	$("#FJODatatable").DataTable(
		
		
	);
    var ssaOptions = {
        connection: "sqlsrv",
        model: 'Person',
        column: ["Pers_LastName"],
        caption: "Pers_LastName",
        value: "Pers_PersonId",
        where: "Pers_PersonId IN (SELECT svod_personid from ServiceOrder where svod_deleted is null)"
    };
    $("#ssaPerson").SetSSA(ssaOptions);
    $("#ssaProductCategory").SetSSA({
        connection: "accpac",
        model: 'ICCATG',
        column: ["CATEGORY", "DESC"],
        caption: "DESC",
        value: "CATEGORY",
        where: ""
    });*/
});

</script>
@endsection
