@extends('layouts.app')
@section('title', 'Main Menu')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-cyan large">{{ __('Main Menu') }}</div>
                <div class="card-body">
					<a href="{{ route("vtj.index") }}" class="btn btn-lg btn-primary btn-block">{{ __("View Today's Job") }}</a>
                    <a href="{{ route("fjo.index") }}" class="btn btn-success btn-lg btn-block">{{ __('Find Job Order') }}</a>
                    <a class="btn btn-secondary btn-lg btn-block text-uppercase mt-3" href="#" data-toggle="modal" data-target="#logoutModal">{!! __('label.logout') !!}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
