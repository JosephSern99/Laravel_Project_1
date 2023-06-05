<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FJOHeader;
use App\Models\FJODetail;
use App\Models\Territories;
use App\Models\CustomCaptions;
use App\Models\ServiceOrderItem;
use App\Models\InventoryAdjustment;
use App\Models\NewProduct;

/*
use App\Models\MOHeader;
use App\Models\MODetail;

use App\Models\ICITEM;
use App\Models\ICUNIT;*/

use DB;
use Illuminate\Support\Facades\Log; // This is logging function (optional)

class FJOHeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $response)
    {
        $data = $response->input();

        $records = FJOHeader::query();

        $name = $response->input("svod_Name");
        /*$personid = $response->input("personid");
		$personid_text = $response->input("personid_text");
        $prod_productfamilyid = $response->input("prod_productfamilyid");
        $prod_productfamilyid_text = $response->input("prod_productfamilyid_text");*/
		
		$selections = Territories::get()->pluck("Terr_Caption", "Terr_TerritoryID");

        if(count($data) > 0){
            if(!empty($name)){
                $records->where("svod_Name", "LIKE", "%" . $name . "%");
            }
			if(!empty($data['svod_date'])){
                $records->whereDate("svod_date",\Carbon\Carbon::createFromFormat("d/m/Y", $data["svod_date"])->format("Y-m-d"));
            }
			if(!empty($data['svod_custname'])){
                $records->where("svod_custname","LIKE", "%" . $data['svod_custname']. "%");
            }
			if(!empty($data['svod_Secterr'])){
                $records->where("svod_Secterr",$data['svod_Secterr']);
            }
			
        }else{
            $records->whereNotNull("svod_Deleted");
        }

        $records = $records->orderBy("svod_Name")->get();
		
				
		

        return view("fjo.index", compact("data","records", "name", "selections"));
		
		
		
		//return view("bom.index", compact("records", "name", "productid", "productid_text", "prod_productfamilyid", "prod_productfamilyid_text"));
    }
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
		$record = FJOHeader::find($id);
		
		return view("fjo.show", compact("record"));
    }
	
	public function customer($id)
    {
        //
		$record = FJOHeader::find($id);
		
		return view("fjo.customer", compact("record"));
    }
	
	public function task($id, Request $response)
    {
        //
		$record = FJOHeader::find($id);
		$productid = $response->input("productid");
        $productid_text = $response->input("productid_text");
        
		$soitems = ServiceOrderItem::where("svit_serviceorderid", $id)->get();
		
		
		$selections = CustomCaptions::where("Capt_Family", "svod_ServiceType")->orderBy("Capt_Order")->pluck("Capt_US", "Capt_Code");
		return view("fjo.task", compact("record","selections", "productid", "productid_text", "soitems"));
    }
	
	public function add($id, Request $request)
    {	
		try{
		
        DB::beginTransaction();
		$data = $request->input();
		
		$cost = 0;
		$costget = DB::table('inventorycontrol')->where('invc_prodcode', $data['svit_product'])->where('invc_secterr', $data["Inva_secterr"])->first();
		
		
		if($costget != [])
		{
			$cost = $costget->invc_unitcost;
		}
		else{
			$cost = 1;
		}
		
		
		$prod = DB::table('NewProduct')->where('Prod_ProductID', $data['svit_product'])->first();
 
		
		$soitem = new ServiceOrderItem;
		
		$soitem->svit_productfamilyid = $prod->prod_productfamilyid;
		$soitem->svit_description =  $prod->prod_name;
		$soitem->svit_productid = $data["svit_product"];
		$soitem->svit_quantity = $data["svit_quantity"];
		$soitem->svit_unitprice = $cost;
		$soitem->svit_serviceorderid = $id;
		DB::commit();
		$soitem->save();
		
		
		$recordid = 0; $results = false;
		
		for($i = 0; $i < 5; $i++){

			DB::beginTransaction();

			//try{
				$getRecordId = DB::select("DECLARE @nextid as INT; EXEC @nextid = eware_get_identity_id 'InventoryAdjustment';SELECT @nextid as nextID;");
				$recordid = $getRecordId[0]->nextID;

				$newia = new InventoryAdjustment;
				$newia->inva_invadjustmentid = $recordid;
				$newia->inva_prodcode = $data["svit_product"];
				$newia->inva_secterr = $data["Inva_secterr"];
				$newia->inva_quantity = $data["svit_quantity"];
				$newia->inva_unitcost = $cost;
				$newia->inva_transactiontype = "OutStock";
				$newia->inva_transactiondate = now();
				$newia->save();
				autotransactionrefno("InventoryAdjustment", $recordid);
				DB::commit(); 

				$results = true;
				break;

			//}catch(\Exception $ex){
				//DB::rollback(); // equivalent to ROLLBACK TRAN in SQL
				//Log::error($ex); // Log the error (optional)
			//}
		}
		
		if($results){
			$result = ["success"=>"Succesfully added item"];

			return response()->json($result);
		}
		}catch(\Exception $ex){
			Log::error($ex);
		}

    }
	
	
	
	
	
	public function finish($id)
    {
        $data = request()->input();
		$record = FJOHeader::find($id);
		$record->Svod_Status = 'Completed';
		$record->Svod_sign = $data["Pic"];
		$record->svod_custname = $data["Svod_CustName"];
		$record->save();
		
		return "";
		
    }
	
	
	public function delete()
	{
		
		$id = request()->input("id");
		DB::delete('delete from ServiceOrderItem where svit_ServiceOrderItemId =?',[$id]);
		
		$result = ["success"=>"Succesfully deleted item"];

		return response()->json($result);
	}

	
	
	public function checkin($id)
    {
        //
		$record = FJOHeader::find($id);
		$now = now();
		$record->svod_datefrom = $now;
		$record->save();
		
		$result = ["now"=>$now->format("d/m/Y H:i:s")];
		
		
		
		return response()->json($result);
    }
	
	public function checkout($id)
    {
        
		$record = FJOHeader::find($id);
		$now = now();
		$record->svod_dateto = $now;
		$record->save();
		
		$result = ["now"=>$now->format("d/m/Y H:i:s")];

		return response()->json($result);
    }
	
	public function save($id)
    {
        $data = request()->input();
		$record = FJOHeader::find($id);
		$record->Svod_TonerEmptyB = $data["Svod_TonerEmptyB"];
		$record->Svod_SpareTonerB = $data["Svod_SpareTonerB"];
		$record->Svod_TonerEmptyC = $data["Svod_TonerEmptyC"];
		$record->Svod_SpareTonerC = $data["Svod_SpareTonerC"];
		$record->Svod_TonerEmptyM = $data["Svod_TonerEmptyM"];
		$record->Svod_SpareTonerM = $data["Svod_SpareTonerM"];
		$record->Svod_TonerEmptyY = $data["Svod_TonerEmptyY"];
		$record->Svod_SpareTonerY = $data["Svod_SpareTonerY"];
		$record->Svod_Meter1Before = $data["Svod_Meter1Before"];
		$record->Svod_Meter1After = $data["Svod_Meter1After"];
		$record->Svod_Meter2Before = $data["Svod_Meter2Before"];
		$record->Svod_Meter2After = $data["Svod_Meter2After"];
		$record->Svod_Meter3Before = $data["Svod_Meter3Before"];
		$record->Svod_Meter3After = $data["Svod_Meter3After"];
		$record->Svod_Meter4Before = $data["Svod_Meter4Before"];
		$record->Svod_Meter4After = $data["Svod_Meter4After"];
		$record->Svod_TestedPrintB = $data["Svod_TestedPrintB"];
		$record->Svod_TestedPrintA = $data["Svod_TestedPrintA"];
		
		$record->Svod_Type = $data["Svod_Type"];
		$record->svod_ServiceType = $data["Svod_ServiceType"];
		$record->Svod_Note = $data["Svod_Note"];
		$record->Svod_PartsReplacement = $data["Svod_PartsReplacement"];
		$record->Svod_Remarks = $data["Svod_Remarks"];

		$record->save();
		
		$result = ["svod_toneremptyb"=>$record->Svod_TonerEmptyB];

		return response()->json($result);
    }
	
	
	public function signature($id)
    {
        //
		$record = FJOHeader::find($id);
		
		
		
		return view("fjo.signature", compact("record"));
    }
	
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	
}
