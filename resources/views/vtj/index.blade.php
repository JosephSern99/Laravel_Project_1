@extends('layouts.app')
@section('title', 'View Today Jobs')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-cyan large">{{ __('Appointment Listing') }}</div>
				
				@foreach($records as $record)
				<a href="{!!route("fjo.show",["fjo"=>$record->getKey()])!!}">
				<div class="card-body border border-secondary">
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
@endsection
