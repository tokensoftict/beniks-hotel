<?php

namespace App\Http\Controllers\PurchaseOrders;

use App\Classes\Settings;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\CreditPaymentLog;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodTable;
use App\Models\Supplier;
use App\Models\SupplierCreditPaymentHistory;
use App\Models\Warehousestore;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder as Po;

class PurchaseOrder extends Controller
{

    protected $settings;

    public function __construct(Settings $_settings){
        $this->settings = $_settings;
    }


    public function index(){
        $data['title'] = 'List Purchase Orders';
        $data['purchase_orders'] = Po::with(['supplier','purchase_order_items','user','created_user'])->where('date_created',date('Y-m-d'))->orderBy('id','DESC')->get();
        return setPageContent('purchaseorder.list', $data);
    }


    public function create(){
        $data['title'] = 'New Purchase Order';
        $data['suppliers'] = Supplier::where('status',1)->get();
        $data['porder'] = new Po();
        $data['stores'] = Warehousestore::all();
        return setPageContent('purchaseorder.form', $data);
    }


    public function store(Request $request){

        return Po::createPurchaseOrder($request);
    }

    public function show($id){
        $data['title'] = 'View Purchase Order';
        $data['porder'] = Po::with(['supplier','purchase_order_items','user','created_user'])->find($id);
        $data['settings'] = $this->settings->store();
        return setPageContent('purchaseorder.show', $data);
    }

    public function destroy($id){
        $po =  Po::find($id);

        foreach ($po->purchase_order_items()->get() as $item){
            $item->stockbatch()->delete();
        }

        $po->delete();

        return redirect()->route('purchaseorders.index')->with('success','Purchase Order has been deleted successfully!');
    }



    public function daily(Request $request){
        if($request->get('date')){
            $data['date'] = $request->get('date');
            $data['status'] = $request->get('status');
        }else{
            $data['date'] = date('Y-m-d');
            $data['status'] = "COMPLETE";
        }
        $data['title'] = 'Daily Purchase Orders';
        $data['purchase_orders'] = Po::with(['supplier','purchase_order_items','user','created_user'])->where('date_created',$data['date'])->orderBy('id','DESC')->get();
        return setPageContent('purchaseorderlist.daily', $data);
    }

    public function monthly(Request $request){
        if($request->get('from') && $request->get('to')){
            $data['from'] = $request->get('from');
            $data['to'] = $request->get('to');
            $data['status'] = $request->get('status');
        }else{
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-t');
            $data['status'] = "COMPLETE";
        }
        $data['title'] = 'Monthly Purchase Orders';
        $data['purchase_orders'] = Po::with(['supplier','purchase_order_items','user','created_user'])->whereBetween('date_created',[$data['from'],$data['to']])->orderBy('id','DESC')->get();
        return setPageContent('purchaseorderlist.monthly', $data);
    }


    public function edit($id){
        $data['title'] = 'Edit Purchase Order';
        $data['porder'] = Po::with(['supplier','purchase_order_items','user','created_user'])->findorfail($id);
        $data['settings'] = $this->settings->store();
        $data['suppliers'] = Supplier::where('status',1)->get();
        $data['stores'] = Warehousestore::all();
        return setPageContent('purchaseorder.form', $data);
    }


    public function update(Request $request, $id)
    {
        return Po::updatePurchaseOrder($id,$request);
    }

    public function markAsComplete(Request $request, $id){
        $po = Po::find($id);
        return $po->complete();
    }



    public function credit_report(Request $request){
        if($request->get('from') && $request->get('to')){
            $data['from'] = $request->get('from');
            $data['to'] = $request->get('to');
        }else{
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-t');
        }

        $history = SupplierCreditPaymentHistory::where('amount','<',0)
            ->whereBetween('payment_date',[$data['from'],$data['to']])->orderBy('id','DESC')->get();

        $data['title'] = "Supplier Credit Report";
        $data['histories'] = $history;
        return setPageContent('purchaseorder.credit_report',$data);
    }

    public function payment_report(Request $request){
        if($request->get('from') && $request->get('to')){
            $data['from'] = $request->get('from');
            $data['to'] = $request->get('to');
        }else{
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-t');
        }

        $history = SupplierCreditPaymentHistory::where('amount','>',0)
            ->whereBetween('payment_date',[$data['from'],$data['to']])->orderBy('id','DESC')->get();

        $data['title'] = "Supplier Payment Report";
        $data['histories'] = $history;
        return setPageContent('purchaseorder.payment_report',$data);
    }



    public function balance_sheet(Request $request){
        $data['customers'] = Supplier::all();
        $customer_id = 0;
        if($request->get('from') && $request->get('to')){
            $data['from'] = $request->get('from');
            $data['to'] = $request->get('to');
            $data['customer_id'] = $request->get('customer_id');
        }else{
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-t');
            if($data['customers']->first()){
                $customer_id = $data['customers']->first()->id;
            }else{
                $customer_id = 0;
            }
            $data['customer_id'] = $customer_id;
        }

        $data['opening'] = SupplierCreditPaymentHistory::where('supplier_id' ,$data['customer_id'])->where('payment_date','<', $data['from'])->sum('amount');

        $data['histories'] = SupplierCreditPaymentHistory::where('supplier_id', $data['customer_id'])->whereBetween('payment_date',[ $data['from'], $data['to']])->get();

        $data['title'] = "Balance Sheet";

        return setPageContent('purchaseorder.balance_sheet',$data);
    }


    public function add_payment(Request $request){
        if($request->getMethod() == "POST"){

            SupplierCreditPaymentHistory::create(
                [
                    'user_id' => \auth()->id(),
                    'supplier_id' =>$request->customer_id,
                    'purchase_order_id' => NULL,
                    'payment_method_id' => $request->payment_method,
                    'payment_info' => "",
                    'amount' => $request->amount,
                    'payment_date' =>$request->payment_date,
                ]
            );

            return redirect()->route('purchaseorders.add_payment')->with('success','Payment has been added successfully!');

        }

        $data['title'] = "Add Supplier Payment";
        $data['payments'] = PaymentMethod::where('status',1)->where('id','<>',4)->get();
        $data['banks'] = BankAccount::where('status',1)->get();
        $data['customers'] = Supplier::all();
        return setPageContent('purchaseorder.add_payment',$data);
    }


}
