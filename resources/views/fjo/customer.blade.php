@extends('layouts.app')
@section('title', 'View Today Jobs')

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
						  <a class="nav-item nav-link active" href="{!!route("fjo.customer",["id"=>$record->getKey()])!!}">Customer</a>
						  <a class="nav-item nav-link" href="{!!route("fjo.task",["id"=>$record->getKey()])!!}">Task</a>
						</div>
					  </div>
					</nav>
							
				<div class="card-body border border-secondary">
					<div>Territory : {!! optional($record->territory)->Terr_Caption !!}</div>
					<div>Service Date & Time  :  {!! optional($record->svod_date)->format("d/m/Y") !!}</div>
					<div>Company Name  {!! optional($record->Company)->Comp_Name !!}</div>
					<div>Person In Charge : {!! optional($record->Person)->Pers_LastName !!} </div>
					<div>Contact Number : {!! optional($record->Contact)->Pers_PhoneNumber !!} </div>
					<div>Machine Location Address : {!! optional($record->Address)->Addr_Address1 !!} </div>
					<div>Machine Serial : {!! optional($record->Asset)->aset_serial !!} </div>
					<div>Machine Model : {!! optional($record->Asset)->aset_description !!}</div>
				</div>
                <div class="card-body">
					<a href="{!!route("fjo.show",["fjo"=>$record->getKey()])!!}" class="btn btn-outline-secondary">{{ __('Back') }}</a>&nbsp
					<a href="{!!route("fjo.task",["id"=>$record->getKey()])!!}" class="btn btn-outline-secondary">{{ __('NEXT') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
