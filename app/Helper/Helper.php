<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

if (!function_exists('autotransactionrefno')) {
	function autotransactionrefno($tbl="",$id=0){
		$query = "select * from Custom_Tables where bord_name='".$tbl."'";
		$record = DB::select(DB::raw($query));
	
	
		
		
		if(count($record) > 0 ){
			$refno="";
			$idfield=$record[0]->Bord_IdField;
			$prefix=$record[0]->Bord_Prefix;
			$rfstt="select";
			$rfstt .= " isnull(refn_prefix,'')'prefix',";
			$rfstt .= " refn_length'rnlength',";
			$rfstt .= " right((";
			$rfstt .= " convert(nvarchar(10),dbo.getlength(refn_length))+";
			$rfstt .= " convert(nvarchar(10),(select COUNT(*)from ".$tbl." where ".$prefix."_Deleted is null))";
			$rfstt .= " ),refn_length)'TOTAL',";
			$rfstt .= " (select isnull(user_initial,'') from users where user_userid='".auth()->user()->getKey()."')'USER',";
			$rfstt .= " refn_format,right(year(GETDATE()),2)'YY',right('0'+convert(nvarchar(50),month(GETDATE())),2)'MM'";
			$rfstt .= " from referenceNumber where refn_deleted is null and refn_entity=".$record[0]->{"Bord_TableId"};
			$rf=DB::select(DB::raw($rfstt));
			
			
			
			if(count($rf) > 0){
				$format= $rf[0]->{"refn_format"};
				if(strpos($format,"#Y") !== false) { $format=str_replace("#Y",$rf[0]->{"YY"}, $format); }
				if(strpos($format,"#M")  !== false){ $format=str_replace("#M",$rf[0]->{"MM"}, $format); }
				if(strpos($format,"#U") !== false){ $format=str_replace("#U",$rf[0]->{"USER"}, $format); }
				if(strpos($format,"#RN") !== false){ $format=str_replace("#RN",$rf[0]->{"TOTAL"}, $format); }
				
				
				if(!empty($rf[0]->{"prefix"})){
					$refno=$rf[0]->{"prefix"}.$format;
				}else{
					$refno=$format;
				}
				
			}
			DB::table($tbl)
				->where($idfield, $id)
				->update(["".$prefix."_transactionID" => $refno]);
		}
	}
}









if (!function_exists('dateformat')) {
    function dateformat($value = "")
    {
        if(empty($value)){
            return null;
        }else{
            return \Carbon\Carbon::createFromFormat(config("custom.DATE_FORMAT"), $value)->format("Y-m-d");
        }
    }
}

if (!function_exists('dateparse')) {
    function dateparse($value = "")
    {
        if(empty($value)){
            return null;
        }else{
            return \Carbon\Carbon::parse($value);
        }
    }
}

if (!function_exists('dateInt')) {
    function dateInt($value = "")
    {
        if(empty($value)){
            return null;
        }else{
            return \Carbon\Carbon::createFromFormat(config("custom.DATE_FORMAT"), $value)->format("Ymd");
        }
    }
}

if(!function_exists('num')){
    function num($value = 0, $dp = 2){
        return number_format(round($value, $dp), 2) ;
    }
}

if (!function_exists('getQtyOnHand')) {
    function getQtyOnHand($itemno, $uom = "", $headerid = "", $includeCompleted = true, $excludeRaw = true)
    {
        $itemno = trim($itemno);
        $icitem = \App\Models\ICITEM::getItem()->selectItem()->where(DB::raw("LTRIM(RTRIM(ICITEM.ITEMNO))"), $itemno)->first();
        $conversion = 1;

        if(!empty($uom)){
            $icunit = \App\Models\ICUNIT::where("UNIT", $uom)->where(DB::raw("LTRIM(RTRIM(ITEMNO))"), $itemno)->first();
            if(!empty($icunit)){
                $conversion = round($icunit->CONVERSION, 2);
            }
        }
        $modetailRawQty = 0;
        if($excludeRaw){
            $modetailRaw = \App\Models\MODetail::leftJoin("MOHeader", "maod_ManufactureOrderId", "maoh_MOHeaderID")
            ->whereNull("maoh_Deleted")->whereIn("maoh_Status", ["Completed", "InProgress"])
            ->where("maod_Type", "RawMaterial")
            ->where(DB::raw("LTRIM(RTRIM(maod_ProductId))"), $itemno);

            if(!empty($headerid)){
                $modetailRaw->where("maod_ManufactureOrderId", "!=", $headerid);
            }

            $modetailRawQty = $modetailRaw->sum("maod_Qty");
        }

        $modetailCompletedQty = 0;
        if($includeCompleted){
            $modetailCompleted = \App\Models\MODetail::leftJoin("MOHeader", "maod_ManufactureOrderId", "maoh_MOHeaderID")
            ->whereNull("maoh_Deleted")->whereIn("maoh_Status", ["Completed", "InProgress"])
            ->where("maod_Type", "FinishedProduct")
            ->where(DB::raw("LTRIM(RTRIM(maod_ProductId))"), $itemno);

            if(!empty($headerid)){
                $modetailCompleted->where("maod_ManufactureOrderId", "!=", $headerid);
            }

            $modetailCompletedQty = $modetailCompleted->sum("maod_Qty");
        }


        $qtyConversion = (round(optional(optional($icitem)->iccost)->sum("QTY"), 2)
        - round(optional(optional($icitem)->iccost)->sum("SHIPQTY"), 2))  / ($conversion == 0 ? 1 : $conversion);

        $qtyonhand = $qtyConversion - round($modetailRawQty, 2) + round($modetailCompletedQty, 2);

        return round($qtyonhand, 2);
    }
}

if (!function_exists('GetLinkedMODetail')) {
    function GetLinkedMODetail($detailid, $headerid, $level)
    {
        $html = "";
        $getLinkedItems = HasLinkedMODetail($detailid, $headerid);

        if($getLinkedItems->count() > 0){
            $level++;

            foreach($getLinkedItems as $linkedItem){
                $html .= "<div class='px-2 m-1'><div class='d-inline-block align-top'>";
                for($i = 0; $i < $level; $i++){
                    $html .= "<i class='fas fa-caret-right'></i>";
                }
                $html .= "</div>";

                $html .= "<div class='pl-1 d-inline-block align-top'>";
                $html .= "<div class='font-weight-bold'>" . $linkedItem->maod_Description . "</div>";
                $html .= "<div class='small font-italic'>";
                $html .= "<div><label class='mr-1 mb-0'>" . $linkedItem->maod_ProductId . ":</label></div>";
                $html .= "<div><label class='mr-1 mb-0'>" . __("Qty Used") . ":</label><span class='font-weight-bold'>" . num($linkedItem->maod_Qty, 2) . "</span></div>";
                $html .= "<div><label class='mr-1 mb-0'>" . __("UOM") . ":</label><span class='font-weight-bold'>" . $linkedItem->maod_uomid . "</span></div>";
                $html .= "</div></div><div class='clearfix'></div></div>";

                $html .= GetLinkedMODetail($linkedItem->getKey(), $headerid, $level);
            }
        }
        return $html;
    }
}


if (!function_exists('GetLinkedMODetailSummary')) {
    function GetLinkedMODetailSummary($detailid, $headerid, $level)
    {
        $html = "";
        $getLinkedItems = HasLinkedMODetail($detailid, $headerid);

        if($getLinkedItems->count() > 0){
            $level++;

            foreach($getLinkedItems as $linkedItem){
                $qtyonhand = \getQtyOnHand($linkedItem->maod_ProductId, $linkedItem->maod_uomid, $headerid);

                $html .= "<tr>" .
                "<td scope='row'>";
                for($i = 0; $i < $level; $i++){
                    $html .= "<i class='fas fa-caret-right'></i>";
                }
                $html .= "<span class='pl-1'>" . optional($linkedItem->icitem)->ITEMNO . "</span>";
                $html .= "</td>";
                $html .= "<td scope='row'>" . $linkedItem->maod_Description . "</td>";
                $html .= "<td scope='row'>" . $linkedItem->maod_uomid . "</td>";
                $html .= "<td scope='row' class='text-right'>";
                $html .= num($qtyonhand, 2);
                $html .= "</td>";
                $html .= "<td scope='row' class='text-right'>";
                $html .= num($linkedItem->maod_Qty, 2);
                $html .= "</td>";
                $html .= "</tr>";


                $html .= GetLinkedMODetailSummary($linkedItem->getKey(), $headerid, $level);
            }
        }
        return $html;
    }
}

if (!function_exists('GetLinkedMODetailRow')) {
    function GetLinkedMODetailRow($detailid, $headerid, $level)
    {
        $html = "";
        $getLinkedItems = HasLinkedMODetail($detailid, $headerid);

        if($getLinkedItems->count() > 0){
            $level++;

            foreach($getLinkedItems as $linkedItem){
                $html .= "<tr class='subitem'>" .
                "<td scope='row'>";
                for($i = 0; $i < $level; $i++){
                    $html .= "<i class='fas fa-caret-right'></i>";
                }

                $qtyonhand = \getQtyOnHand($linkedItem->maod_ProductId, $linkedItem->maod_uomid, $headerid);

                $html .= "<span class='pl-1 span-product-code'>" . $linkedItem->maod_ProductId . "</span>";

                if(!empty($linkedItem->maod_refmoheaderid)){
                    $moheader = \App\Models\MOHeader::where("maoh_Status", "!=", "Cancelled")->find($linkedItem->maod_refmoheaderid);
                    if(!empty($moheader)){
                        $html .= "<div><a href='" . route("mo.show", ["mo" => $linkedItem->maod_refmoheaderid]) . "' class='btn btn-sm btn-light'>" . $moheader->maoh_Name . "</a></div>";
                    }else{
                        $html .= "<div>";
                        $html .= "<form action='" . route('mosp.create', ['id' => $linkedItem->getKey(), 'mo' => $headerid]) ."' method='post' class='inprogress form-create-mo' enctype='application/x-www-form-urlencoded'>";
                        $html .= "<input type='hidden' name='_token' value='" . csrf_token(). "' />";
                        $html .= "<button class='btn btn-sm btn-light btn-createmo' type='submit'>Create MO</button>";
                        $html .= "</form>";
                        $html .= "</div>";
                    }
                }elseif(\HasLinkedMODetail($linkedItem->getKey(), $headerid)->count() > 0){
                    $html .= "<div>";
                    $html .= "<form action='" . route('mosp.create', ['id' => $linkedItem->getKey(), 'mo' => $headerid]) . "' method='post' class='inprogress form-create-mo' enctype='application/x-www-form-urlencoded'>";
                    $html .= "<input type='hidden' name='_token' value='" . csrf_token(). "' />";
                    $html .= "<button class='btn btn-sm btn-light btn-createmo' type='submit'>Create MO</button>";
                    $html .= "</form>";
                    $html .= "</div>";
                }

                $html .= "<input type='hidden' class='detailid' value='" . $linkedItem->getKey() . "' />";
                $html .= "<input type='hidden' class='product-code' value='" . $linkedItem->maod_ProductId . "' />";
                $html .= "<input type='hidden' class='product-id' value='" . $linkedItem->maod_ProductId . "' />";
                $html .= "<input type='hidden' class='refdetailid' value='" . ($linkedItem->maod_refmodetailid ?? 0) . "' />";
                $html .= "<input type='hidden' class='bomheaderid' value='" . ($linkedItem->maod_bomheaderid ?? 0) . "' />";
                $html .= "<input type='hidden' class='bomdetailid' value='" . ($linkedItem->maod_bomdetailid ?? 0) . "' />";
                $html .= "</td>";
                $html .= "<td scope='row'>";
                $html .= "<span class='span-product-description'>" . $linkedItem->maod_Description . "</span>";
                $html .= "<input type='hidden' class='product-description' value='" . $linkedItem->maod_Description . "' />";
                $html .= "</td>";
                $html .= "<td scope='row'>";
                $html .= "<span class='span-product-uom'>" . $linkedItem->maod_uomid . "</span>";
                $html .= "<input type='hidden' class='product-uom' value='" . $linkedItem->maod_uomid . "' />";
                $html .= "</td>";
                $html .= "<td scope='row' class='text-right'>";
                $html .= "<span class='span-product-qtyonhand'>" . num($qtyonhand, 2) . "</span>";
                $html .= "<input type='hidden' class='product-qtyonhand' value='" . $qtyonhand . "' />";
                $html .= "</td>";
                $html .= "<td scope='row' class='text-right'>";
                $html .= "<input type='text' class='form-control form-control-sm product-qtyused text-right' value='" . round($linkedItem->maod_Qty, 2) . "' />";
                $html .= "</td>";
                $html .= "<td scope='row' class='text-right'><button type='menu' class='btn btn-danger btnDelete'><i class='fas fa-trash'></i></button></td>";
                $html .= "</tr>";


                $html .= GetLinkedMODetailRow($linkedItem->getKey(), $headerid, $level);
            }
        }
        return $html;
    }
}

if(!function_exists('HasLinkedMODetail')){
    function HasLinkedMODetail($detailid, $headerid){
        $getLinkedItems = \App\Models\MODetail::where("maod_ManufactureOrderId", $headerid)
        ->leftJoin(config("custom.DB_ACCPAC_DATABASE") . ".dbo.ICITEM as ictem", "ictem.ITEMNO", DB::raw("maod_ProductId collate database_default"))
        ->where("maod_Type", "RawMaterial")->where("maod_refmodetailid", $detailid)->get();

        return $getLinkedItems;
    }
}

if (!function_exists('GetLinkedMODetailEdit3')) {
    function GetLinkedMODetailEdit3($data, $index, $level)
    {
        $html = "";
        if(empty($data) || (empty($index) && 0 != $index)){

        }else{
            $detailid = $data["raw-detailid"][$index];
            $level++;
            for($a = 0; $a < count($data["raw-refdetailid"]); $a++){
                if($detailid == $data["raw-refdetailid"][$a]){
                    $html .= "<tr>";
                    $html .= "<td scope='row'>";
                    for($i = 0; $i < $level; $i++){
                        $html .= "<i class='fas fa-caret-right'></i>";
                    }
                    $html .= "<span class='pl-1'>" . $data['raw-productcode'][$a] . "</span></td>";
                    $html .= "<td scope='row'>" . $data['raw-description'][$a] . "</td>";
                    $html .= "<td scope='row'>" . $data['raw-uom'][$a] . "</td>";
                    $html .= "<td scope='row' class='text-right'>" . num($data['raw-qtyonhand'][$a], 2) . "</td>";
                    $html .= "<td scope='row' class='text-right'>" . num($data['raw-qtyused'][$a], 2) . "</td>";
                    $html .= "</tr>";

                    $html .= GetLinkedMODetailEdit3($data, $a, $level);
                }
            }
        }
        return $html;

    }
}

if (!function_exists('GetLinkedMODetailEdit1')) {
    function GetLinkedMODetailEdit1($data, $index, $level)
    {
        $html = "";
        if(empty($data) || (empty($index) && 0 != $index)){

        }else{
            $detailid = $data["raw-detailid"][$index];
            $level++;
            for($a = 0; $a < count($data["raw-refdetailid"]); $a++){
                if($detailid == $data["raw-refdetailid"][$a]){
                    $html .= "<tr class='subitem'>" .
                    "<td scope='row'>";
                    for($i = 0; $i < $level; $i++){
                        $html .= "<i class='fas fa-caret-right'></i>";
                    }
                    $html .= "<span class='pl-1 span-product-code'>" . $data["raw-productcode"][$a] . "</span>";

                    //if(round($data["raw-qtyonhand"][$a], 2) <= round($data["raw-qtyused"][$a], 2)){
                        if(!empty($data["raw-detailid"][$a]) && $data["raw-detailid"][$a] > 0){
                            $modetail = \App\Models\MODetail::find($data["raw-detailid"][$a]);

                            $moheader = \App\Models\MOHeader::where("maoh_Status", "!=", "Cancelled")->find($modetail->maod_refmoheaderid);

                            if(!empty($moheader)){
                                $html .= "<div><a href='" . route("mo.show", ["mo" => $modetail->maod_refmoheaderid]) . "' class='btn btn-sm btn-light'>" . $moheader->maoh_Name . "</a></div>";
                            }elseif(\HasLinkedMODetail($modetail->getKey(), $modetail->maod_ManufactureOrderId)->count() > 0){
                                $html .= "<div><a href='" . route("mosp.create", ["id" => $modetail->getKey(), "mo" => $modetail->maod_ManufactureOrderId]) . "' class='btn btn-sm btn-light'>Create MO</a></div>";
                            }
                        }
                    //}
                    $html .= "<input type='hidden' class='detailid' value='" . $data["raw-detailid"][$a] . "' />";
                    $html .= "<input type='hidden' class='product-code' value='" . $data["raw-productcode"][$a] . "' />";
                    $html .= "<input type='hidden' class='product-id' value='" . $data["raw-productcode"][$a] . "' />";
                    $html .= "<input type='hidden' class='refdetailid' value='" . $data["raw-refdetailid"][$a] . "' />";
                    $html .= "<input type='hidden' class='bomheaderid' value='" . $data["raw-bomheaderid"][$a] . "' />";
                    $html .= "<input type='hidden' class='bomdetailid' value='" . $data["raw-bomdetailid"][$a] . "' />";
                    $html .= "</td>";
                    $html .= "<td scope='row'>";
                    $html .= "<span class='span-product-description'>" . $data["raw-description"][$a] . "</span>";
                    $html .= "<input type='hidden' class='product-description' value='" . $data["raw-description"][$a] . "' />";
                    $html .= "</td>";
                    $html .= "<td scope='row'>";
                    $html .= "<span class='span-product-uom'>" . $data["raw-uom"][$a] . "</span>";
                    $html .= "<input type='hidden' class='product-uom' value='" . $data["raw-uom"][$a] . "' />";
                    $html .= "</td>";
                    $html .= "<td scope='row' class='text-right'>";
                    $html .= "<span class='span-product-qtyonhand'>" . num($data["raw-qtyonhand"][$a], 2) . "</span>";
                    $html .= "<input type='hidden' class='product-qtyonhand' value='" . round($data["raw-qtyonhand"][$a], 2) . "' />";
                    $html .= "</td>";
                    $html .= "<td scope='row' class='text-right'>";
                    $html .= "<input type='text' class='form-control form-control-sm product-qtyused text-right' value='" . round($data["raw-qtyused"][$a], 2) . "' />";
                    $html .= "</td>";
                    $html .= "<td scope='row' class='text-right'><button type='menu' class='btn btn-danger btnDelete'><i class='fas fa-trash'></i></button></td>";
                    $html .= "</tr>";

                    $html .= GetLinkedMODetailEdit1($data, $a, $level);
                }
            }
        }
        return $html;
    }
}

