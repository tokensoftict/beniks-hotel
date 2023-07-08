<?php

namespace App\Http\Controllers\LaundryManager;

use App\Classes\Settings;
use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Laundry;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;

class LaundryController extends Controller
{
    protected $settings;

    public function __construct(Settings $_settings){
        $this->settings = $_settings;
    }


    public function draft_invoice(){

    }

    public function complete_invoice(){

    }

    public function draft(){
        $data['title'] = 'Draft Laundry Invoice List';
        $data['invoices'] = Laundry::with(['created_user','customer'])->where('warehousestore_id', getActiveStore()->id)->where('status','DRAFT')->where('invoice_date',date('Y-m-d'))->get();
        return view('laundrywash.paid-invoice',$data);
    }

    public function paid(){
        $data = [];
        $data['title'] = 'Completed Laundry Invoice List';
        $data['invoices'] = Laundry::with(['created_user','customer'])->where('warehousestore_id', getActiveStore()->id)->where('status','COMPLETE')->where('invoice_date',date('Y-m-d'))->get();
        return setPageContent('laundrywash.paid-invoice',$data);
    }

    public function index()
    {

    }

    public function create(){
        $data = [];
        $data['customers'] = Customer::all();
        $data['payments'] = PaymentMethod::all();
        $data['banks'] = BankAccount::where('status',1)->get();
        return view('laundrywash.new',$data);
    }


    public function edit($id){
        $data = [];
        $data['customers'] = Customer::all();
        $data['payments'] = PaymentMethod::all();
        $data['banks'] = BankAccount::where('status',1)->get();
        $data['laundry'] = Laundry::find($id);
        return view('laundrywash.update',$data);
    }


    public function store(Request $request){

        $invoice = Laundry::createInvoice($request);

        if($request->get('payment') !== "false" && $request->get('status') == 'COMPLETE'){

            $payment = Payment::createPayment(['invoice'=>$invoice,'payment_info'=>json_decode($request->get('payment'),true),"type"=>"Laundry"]);

            $invoice->payment_id = $payment->id;

            $invoice->total_amount_paid = $payment->total_paid;

            $invoice->update();
        }

        $success_view = view('laundrywash.success',['invoice_id'=> $invoice->id])->render();

        return json(['status'=>true,'html'=>$success_view]);
    }


    public function update($id, Request  $request){

        $invoice = Laundry::findorfail($id);

        Laundry::updateInvoice($request, $invoice);

        if($request->get('payment') !== "false" && $request->get('status') == 'COMPLETE'){

            $payment = Payment::createPayment(['invoice'=>$invoice,'payment_info'=>json_decode($request->get('payment'),true),"type"=>"Laundry"]);

            $invoice->payment_id = $payment->id;

            $invoice->total_amount_paid = $payment->total_paid;

            $invoice->update();
        }

        $success_view = view('laundrywash.success-updated',['invoice_id'=> $invoice->id])->render();

        return json(['status'=>true,'html'=>$success_view]);

    }


    public function view($id)
    {
        $data = [];
        $data['title'] = 'View Invoice';
        $data['payments'] = PaymentMethod::all();
        $data['banks'] = BankAccount::where('status',1)->get();
        $data['invoice'] = Laundry::with(['created_by','customer', 'laundry_items', 'laundry_items.cloth_service_mapper'])->findorfail($id);
        return view('laundrywash.view',$data);
    }


    public function monthly_reports(Request $request)
    {
        if($request->get('from') && $request->get('to')){
            $data['from'] = $request->get('from');
            $data['to'] = $request->get('to');
            $data['status'] = $request->get('status');
        }else{
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-t');
            $data['status'] = 'COMPLETE';
        }
        $data['title'] = "Monthly Laundry Invoice Report";
        $data['invoices'] = Laundry::with(['created_user','customer'])->where('warehousestore_id', getActiveStore()->id)->where('status', $data['status'])->whereBetween('invoice_date', [$data['from'],$data['to']])->get();
        return view('laundrywash.reports',$data);
    }


    public function user_monthly(Request $request){
        if($request->get('from') && $request->get('to')){
            $data['from'] = $request->get('from');
            $data['to'] = $request->get('to');
            $data['customer'] = $request->get('customer');
            $data['status'] = $request->get('status');
        }else{
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-t');
            $data['customer'] = 1;
            $data['status'] = 'COMPLETE';
        }
        $data['customers'] = User::where('group_id','<>',1)->get();
        $data['title'] = "Monthly User Laundry Invoice Report";
        $data['invoices'] = Laundry::with(['created_user','customer'])->where('status',$data['status'])->where('warehousestore_id',getActiveStore()->id)->where('created_by', $data['customer'])->whereBetween('invoice_date', [$data['from'],$data['to']])->get();
        return view('laundrywash.user_reports',$data);
    }


    public function complete_invoice_no_edit($id, Request $request)
    {
        $invoice = Laundry::findorfail($id);
        if($invoice->status == "COMPLETE") return redirect()->route('laundry.view',$id)->with('success','Laundry has been completed successfully!');

        $payment = Payment::createPayment(['invoice'=>$invoice,'payment_info'=>$request,"type"=>"Laundry"]);

        $invoice->payment_id = $payment->id;

        $invoice->status = "COMPLETE";

        $invoice->total_amount_paid = $payment->total_paid;

        $invoice->update();

        return redirect()->route('invoiceandsales.view',$id)->with('success','Laundry has been completed successfully!');
    }


    public function print_pos($id){
        $data = [];
        $invoice = Laundry::with(['created_by','customer','laundry_items'])->findorfail($id);
        $data['invoice'] =$invoice;
        $data['store'] =  $this->settings->store();
        $page_size = $invoice->laundry_items()->get()->count() * 17;
        $page_size += 180;
        $pdf = PDF::loadView('print.pos_laundry', $data,[],[
            'format' => [80,$page_size],
            'margin_left'          => 0,
            'margin_right'         => 0,
            'margin_top'           => 0,
            'margin_bottom'        => 0,
            'margin_header'        => 0,
            'margin_footer'        => 0,
            'orientation'          => 'P',
            'display_mode'         => 'fullpage',
            'custom_font_dir'      => '',
            'custom_font_data' 	   => [],
            'default_font_size'    => '12',
        ]);

        return $pdf->stream('document.pdf');
    }

    public function print_afour($id){
        $data = [];
        $invoice = Laundry::with(['created_by','customer','laundry_items'])->findorfail($id);
        $data['invoice'] =$invoice;
        $data['store'] =  $this->settings->store();
        $pdf = PDF::loadView("print.pos_laundry_afour",$data);
        return $pdf->stream('document.pdf');
    }

}
