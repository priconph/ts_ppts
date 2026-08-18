<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\MaterialIssuanceDetails;
use App\Model\YeuKitting;

class WBSMaterialKittingController extends Controller
{
    //

    public function get_wbs_kitting_details(Request $request) {
    	$kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->distinct('item')->get();

		// return $kitting_details;

    	return response()->json(['kitting_details' => $kitting_details]);
    }

    public function get_wbs_kitting_details_by_po_no(Request $request) { //nmodify
        // Added by BiniManoy 10112024 (uncomment when the time is right)
        // $yeu_kitting_details = YeuKitting::select('yec_po','id','item_name','item_code','qty','usg')->distinct('product_name')
        $yeu_kitting_details = YeuKitting::select('yec_po','id','item_name','item_code','qty','usg')->distinct('product_name')
    	->where('po_no', $request->po_no)
        ->where('yec_po', '!=' ,'N/A')
    	->whereNotNull('yec_po')
    	->get();


        // $kitting_details = MaterialIssuanceDetails::select('id', 'item', 'item_desc', 'usage', 'issued_qty')->distinct('item')
        // ->where('po', $po_number)
        // ->get();

        // return gettype($yeu_kitting_details[0]->yec_po);

        // return $yeu_kitting_details[0]->yec_po;

        // if(isset($yeu_kitting_details)){}

        if(count($yeu_kitting_details) > 0){ // added 11222024 BiniManoy
            // return 'if';
            $po_number = $yeu_kitting_details[1]->yec_po;
            $kitting_details = MaterialIssuanceDetails::select('id', 'item', 'item_desc', 'usage', 'issued_qty')->distinct('item')
            ->where('po', $po_number)
            ->get();
        }else{
            // return 'else';
            $kitting_details = MaterialIssuanceDetails::select('id', 'item', 'item_desc', 'usage', 'issued_qty')->distinct('item')
            ->where('po', $request->po_no)
            ->get();
        }

        // return $yeu_kitting_details;
    	return response()->json(['kitting_details' => $kitting_details,'yeu_kitting_details'=>$yeu_kitting_details]);
    }
}
