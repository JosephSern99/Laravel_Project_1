@extends('layouts.app')
@section('title', __('table.bomheader'))
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-cyan large">{{ __('Bill Of Material') }}</div>

                <div class="card-body border-bottom">
                    <form role="form" action="{{ URL::current() }}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="bomh_Name">{!! __('columns.bomh_Name') !!}</label>
                                        <input type="text" class="form-control" name="bomh_Name" id="bomh_Name" placeholder="{!! __('columns.bomh_Name') !!}" value="{!! $name ?? null !!}">
                                    </div>
                                    <div class="form-group col-md-4" id="ssaProduct">
                                        <label for="prod_code">{!! __('columns.prod_code') !!}</label>
                                        <div class='input-group'>
                                            <input type="text" placeholder="{!! __('columns.prod_code') !!}" class="form-control input-ssa" id="productid_text" name="productid_text" value="{{ ($productid ?? 'invalid') !== 'invalid' ? (($productid ?? null) == null ? '' : ($productid_text ?? null)) : "" }}" />
                                            <input id="productid" name="productid" class="hidden-ssa" type="hidden" value="{{ $productid ?? null }}" />
                                            <div class="input-group-append"><span class="input-group-text search-ssa"><i class="fa fa-search"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4" id="ssaProductCategory">
                                        <label for="prod_productfamilyid">{!! __('columns.prod_productfamilyid') !!}</label>
                                        <div class='input-group'>
                                            <input type="text" placeholder="{!! __('columns.prod_productfamilyid') !!}" class="form-control input-ssa" id="prod_productfamilyid_text" name="prod_productfamilyid_text" value="{{ ($prod_productfamilyid ?? 'invalid') !== 'invalid' ? (($prod_productfamilyid ?? null) == null ? '' : ($prod_productfamilyid_text ?? null)) : "" }}" />
                                            <input id="prod_productfamilyid" name="prod_productfamilyid" class="hidden-ssa" type="hidden" value="{{ $prod_productfamilyid ?? null }}" />
                                            <div class="input-group-append"><span class="input-group-text search-ssa"><i class="fa fa-search"></i></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> {!! __('label.search') !!}</button>
                                <button type="button" class="btn ml-2 btn-form-clear"><i class="fas fa-eraser"></i> {!! __('label.clear') !!}</button>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </form>
                    <form role="form" action="{!! route("bom.createorder") !!}" method="POST" class="d-none" id="frmSubmit">
                        @csrf
                    </form>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-9">
                        </div>
                        <div class="col-md-3 text-md-right">
                            <button type="button" class="btn btn-success" id="btnSave"><i class="fas fa-save"></i> @lang("Start MO")</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-primary text-white">
                            <tr>
                                <th scope="col">@lang("columns.bomh_Name")</th>
                                <th scope="col">@lang("Raw Material")</th>
                                <th scope="col">@lang("columns.prod_code")</th>
                                <th scope="col">@lang("Qty On Hand")</th>
                                <th scope="col">@lang("Qty Required")</th>
                                <th scope="col">@lang("Finished Product")</th>
                                <th scope="col">@lang("columns.prod_code")</th>
                                <th scope="col">@lang("columns.prod_productfamilyid")</th>
                                <th scope="col">@lang("Qty On Hand")</th>
                                <th scope="col">@lang("Qty Expected")</th>
                                <th scope="col"></th>
                                <th scope="col"><input type="checkbox" id="chkAll"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($records as $record)
                                @php
                                    $items = \App\Models\BOMDetail::where("bomd_BOMHeaderId", $record->getKey())
                                    ->leftJoin(config("custom.DB_ACCPAC_DATABASE") . ".dbo.ICITEM as ictem", "ictem.ITEMNO", DB::raw("bomd_ProductId collate database_default"));

                                    $rawItems = (clone $items)->where("bomd_Type", "RawMaterial")->get();
                                    $finishedProducts = $items->where("bomd_Type", "FinishedProduct");
                                    $clonefinishedProducts = (clone $finishedProducts);
                                    if(!empty($productid)){
                                        $clonefinishedProducts->where("bomd_Productid", $productid);
                                    }
                                    if(!empty($prod_productfamilyid)){
                                        $clonefinishedProducts->where("CATEGORY", $prod_productfamilyid);
                                    }

                                    if($clonefinishedProducts->count() > 0){
                                        $finishedProducts = $finishedProducts->get();
                                    }else{
                                        $finishedProducts = $finishedProducts->where("bomd_Deleted", 1)->get();
                                    }

                                    $rawItemsCount = $rawItems->count();
                                    $finishedProductsCount = $finishedProducts->count();
                                    $maxCount = max($rawItemsCount, $finishedProductsCount);

                                    /*$icitem = \App\Models\ICITEM::getItem()->selectItem();*/
                                @endphp
                                @if($rawItemsCount > 0 && $finishedProductsCount > 0)
                                @foreach(($rawItemsCount > $finishedProductsCount ? $rawItems : $finishedProducts) as $items)
                                @php
                                    $i = $loop->index; $rawItem = optional($rawItems[$i] ?? null); $finishedProduct = optional($finishedProducts[$i] ?? null);
                                @endphp
                                <tr>
                                    @if($loop->first)
                                    <td rowspan="{!! $maxCount !!}" scope="row">{!! $record->bomh_Name !!}</td>
                                    @endif
                                    <td class="border-top-0 @if(!$loop->last) border-bottom-0 @endif">
                                    <div>{!! $rawItem["bomd_Description"] !!}</div>
                                    </td>
                                    <td class="border-top-0 @if(!$loop->last) border-bottom-0 @endif">
                                        <div>{!! optional($rawItem->icitem)->ITEMNO !!}</div>
                                    </td>
                                    <td class="text-right border-top-0 @if(!$loop->last) border-bottom-0 @endif">
                                        {!! round(optional(optional($rawItem->icitem)->iccost)->sum("QTY"), 2) - round(optional(optional($rawItem->icitem)->iccost)->sum("SHIPQTY"), 2) !!}
                                    </td>
                                    <td class="text-right border-top-0 @if(!$loop->last) border-bottom-0 @endif">
                                        {!! round($rawItem["bomd_Qty"], 2) !!}
                                    </td>
                                    <td class="border-top-0 @if(!$loop->last) border-bottom-0 @endif">
                                        <div>{!! $finishedProduct["bomd_Description"] !!}</div>
                                    </td>
                                    <td class="border-top-0 @if(!$loop->last) border-bottom-0 @endif">
                                        <div>{!! optional($finishedProduct->icitem)->ITEMNO !!}</div>
                                    </td>
                                    <td class="border-top-0 @if(!$loop->last) border-bottom-0 @endif">
                                        <div>{!! optional(optional($finishedProduct->icitem)->iccatg)->CATEGORY !!}</div>
                                    </td>
                                    <td class="text-right border-top-0 @if(!$loop->last) border-bottom-0 @endif">
                                        {!! round(optional(optional($rawItem->icitem)->iccost)->sum("QTY"), 2) - round(optional(optional($rawItem->icitem)->iccost)->sum("SHIPQTY"), 2) !!}
                                    </td>
                                    <td class="text-right border-top-0 @if(!$loop->last) border-bottom-0 @endif">
                                        {!! round($finishedProducts[0]["bomd_Qty"], 2) !!}
                                    </td>
                                    @if($loop->first)
                                    <td rowspan="{!! $maxCount !!}">
                                        <div class="form-row">
                                            <div class="form-group">
                                                <span class="font-weight-bold">{!! __('columns.bomh_morequiredtemperature') !!}:</span> {!! round($record->bomh_morequiredtemperature, 2) !!}
                                            </div>
                                            <div class="form-group">
                                                <span class="font-weight-bold">{!! __('columns.bomh_morequiredtimeduration') !!}:</span> {!! round($record->bomh_morequiredtimeduration, 2) !!}
                                            </div>
                                            <div class="form-group">
                                                <span class="font-weight-bold">{!! __('columns.bomh_shelflife') !!}:</span> {!! round($record->bomh_shelflife, 2) !!}
                                            </div>
                                        </div>
                                    </td>
                                    <td rowspan="{!! $maxCount !!}"><input type="checkbox" class="checkbox" value="{!! $record->getKey() !!}"></td>
                                    @else

                                    @endif
                                </tr>
                                @endforeach
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('js/bom.js?v=1.1.0') }}"></script>
<script src="{!! route("javascript.ssa", ["u" => "ajax-ssa"]) !!}"></script>
<script>
$(function(){
    var ssaOptions = {
        connection: "accpac",
        model: 'ICITEM',
        column: ["ITEMNO", "DESC"],
        caption: "ITEMNO",
        value: "ITEMNO",
        where: "ITEMNO IN (SELECT ITEMNO FROM ICILOC WHERE [LOCATION] = '{!! config("custom.DB_ACCPAC_LOCATION") !!}') AND CATEGORY IN (SELECT CATEGORY FROM ICCATG WHERE INACTIVE != '1')"
    };
    $("#ssaProduct").SetSSA(ssaOptions);
    $("#ssaProductCategory").SetSSA({
        connection: "accpac",
        model: 'ICCATG',
        column: ["CATEGORY", "DESC"],
        caption: "DESC",
        value: "CATEGORY",
        where: ""
    });
});

</script>
@endsection
