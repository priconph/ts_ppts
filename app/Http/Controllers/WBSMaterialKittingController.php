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
        $yecPoNo = $request->yec_po_no ?? '';

        if($yecPoNo != ''){ // added 11222024 BiniManoy

            // $po_number = $yeu_kitting_details[1]->yec_po;
            $kitting_details = MaterialIssuanceDetails::select('id', 'item', 'item_desc', 'usage', 'issued_qty')->distinct('item')
            ->whereIn('po', $yecPoNo)
            ->get();
    	    return response()->json(['kitting_details' => $kitting_details]);
        }else{
            // return 'else';
            $kitting_details = MaterialIssuanceDetails::select('id', 'item', 'item_desc', 'usage', 'issued_qty')->distinct('item')
            // ->where('po','like','%'. $request->po_no.'%')
            ->where('po',$request->po_no)
            ->get();
        }

        // return $yeu_kitting_details;
    	return response()->json(['kitting_details' => $kitting_details]);
    }
}
