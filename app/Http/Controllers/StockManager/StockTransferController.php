<?php

namespace App\Http\Controllers\StockManager;

use App\Classes\Settings;
use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\StockTransferBackup;
use App\Models\Warehousestore;
use Illuminate\Http\Request;

class StockTransferController extends Controller
{
    protected $settings;

    public function __construct(Settings $_settings){
        $this->settings = $_settings;
    }

    public function add_transfer(Request $request)
    {
        $data['from'] = NULL;
        $data['to'] =  NULL;
        $data['type'] =  NULL;
        if($request->getMethod() == "POST" && !isset($request->stock_id)){
            if($request->from == $request->to)
                return redirect()->route('stocktransfer.add_transfer')->with('error','Sorry, You can transfer stock to the store');
            else
                $data['from'] = $request->from; $data['to'] = $request->to; $data['type'] = $request->type;
        }
        else if($request->getMethod() == "POST" && isset($request->stock_id))
        {
            return StockTransfer::createStockTransfer($request);
        }
        $data['transfer'] = new StockTransfer();
        $data['transfer']->transfer_date = dailyDate();
        $data['title'] = "New Stock Transfer";
        $data['title2'] = "Today's Stock Transfer";
        $data['stores'] = Warehousestore::all();
        return setPageContent("stock.transfer.form",$data);
    }

    public function transfer_report(Request $request)
    {
        if($request->get('from') && $request->get('to')){
            $data['from']  = $request->get('from');
            $data['to']  = $request->get('to');
        }else{
            $data['from']  = date('Y-m-01');
            $data['to']  = date('Y-m-t');
        }
        $data['title'] = "Stock Transfer Report";
        $data['stores'] = Warehousestore::all();
        $data['transfers'] = StockTransfer::with(['store_to','store_from','user'])->whereBetween('transfer_date',[ $data['from'], $data['to']])->orderBy('transfer_date','DESC')->get();
        return setPageContent("stock.transfer.report",$data);
    }

    public function delete_transfer($id)
    {

        $transfer = StockTransfer::with(['store_to','store_from','user','stock_transfer_items'])->find($id);

        $from_store = getActualStore($transfer->product_type, $transfer->from);

        $to_store = getActualStore($transfer->product_type, $transfer->to);

        foreach ($transfer->stock_transfer_items as $from_transfers)
        {
            $from_transfers->delete();
        }

        $transfer->delete();

        return redirect()->route('stocktransfer.transfer_report')->with('success','Stock has been deleted successfully!');
    }


    public function show($id)
    {

        $transfer = StockTransfer::findorfail($id);

        $data['title'] = "View Stock Transfer";

        $data['transfer'] = $transfer;

        return setPageContent("stock.transfer.show",$data);
    }



    public function print_afour($transfer_id)
    {
        $transfer = StockTransfer::findorfail($transfer_id);

        $data['transfer'] = $transfer;

        $data['logo'] = $this->settings;

        return view("print.print_transfer",$data);
    }


    public function edit_transfer($transfer_id)
    {

        $transfer = StockTransfer::findorfail($transfer_id);

        $data['title'] = "Edit Stock Transfer";

        $data['title2'] = "Today's Stock Transfer";
        $data['transfer'] = $transfer;

        $data['stores'] = Warehousestore::all();

        $data['from'] = $transfer->from;
        $data['to'] =  $transfer->to;
        $data['type'] =  $transfer->type;

        $data['logo'] = $this->settings;

        return setPageContent("stock.transfer.form",$data);
    }


    public function update(Request $request, $id)
    {
        return StockTransfer::updateStockTransfer($request, $id);
    }

    public function complete($id)
    {
        $transfer = StockTransfer::find($id);
        return $transfer->complete();
    }

}
