<?php

namespace App\Http\Controllers\PaymentReport;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodTable;
use App\Models\Warehousestore;
use Illuminate\Http\Request;

class PaymentReportController extends Controller
{

    public function daily_payment_reports(Request $request)
    {
        if($request->get('date')){
            $data['date'] = $request->get('date');
        }else{
            $data['date'] = dailyDate();
        }
        $data['title'] = "Daily Payment Report";
        $data['payments'] = Payment::with(['warehousestore','customer','user','payment_method_tables','invoice'])->where('warehousestore_id',getActiveStore()->id)->where('payment_date', $data['date'])->orderBy('id','DESC')->get();
        return setPageContent('paymentreport.daily_payment_reports',$data);
    }

    public function monthly_payment_reports(Request $request)
    {
        if($request->get('from') && $request->get('to')){
            $data['from'] = $request->get('from');
            $data['to'] = $request->get('to');
        }else{
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-t');
        }

        $data['title'] = "Monthly Payment Report";

        $data['payments'] = Payment::with(['warehousestore','customer','user','payment_method_tables','invoice'])->where('warehousestore_id',getActiveStore()->id)->whereBetween('payment_date', [$data['from'],$data['to']])->orderBy('id','DESC')->get();
        return view('paymentreport.monthly_payment_reports',$data);
    }


    public function monthly_payment_reports_by_customer(Request $request)
    {
        if($request->get('from') && $request->get('to')){
            $data['from'] = $request->get('from');
            $data['to'] = $request->get('to');
            $data['customer'] = $request->get('customer');
        }else{
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-t');
            $data['customer'] = 1;
        }
        $data['customers'] = Customer::all();
        $data['title'] = "Monthly Payment Report By Customer";

        $data['payments'] = Payment::with(['warehousestore','customer','user','payment_method_tables','invoice'])->where('customer_id',$data['customer'])->where('warehousestore_id',getActiveStore()->id)->whereBetween('payment_date', [$data['from'],$data['to']])->orderBy('id','DESC')->get();
        return setPageContent('paymentreport.monthly_payment_reports_by_customer',$data);
    }




    public function monthly_payment_report_by_method(Request $request)
    {
        if($request->get('from') && $request->get('to')){
            $data['from'] = $request->get('from');
            $data['to'] = $request->get('to');
            $data['payment_method'] = $request->get('payment_method');
        }else{
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-t');
            $data['payment_method'] = 1;
        }
        $data['payments'] = PaymentMethodTable::with(['warehousestore','payment','customer','user','payment_method','invoice'])->where('payment_method_id', $data['payment_method'])->where('warehousestore_id',getActiveStore()->id)->whereBetween('payment_date', [$data['from'],$data['to']])->orderBy('id','DESC')->get();
        $data['title'] = "Monthly Payment Report By Payment Method";
        $data['pmthods'] = PaymentMethod::all();
        $data['customers'] = Customer::all();
        return setPageContent('paymentreport.monthly_payment_reports_by_method',$data);
    }

    public function monthly_payment_report_by_method_by_customer(Request $request)
    {
        if($request->get('from') && $request->get('to')){
            $data['from'] = $request->get('from');
            $data['to'] = $request->get('to');
            $data['payment_method'] = $request->get('payment_method');
            $data['customer'] = $request->get('customer');
        }else{
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-t');
            $data['payment_method'] = 1;
            $data['customer'] = 1;
        }
        $data['payments'] = PaymentMethodTable::with(['warehousestore','payment','customer','user','payment_method','invoice'])->where('customer_id', $data['customer'])->where('payment_method_id', $data['payment_method'])->where('warehousestore_id',getActiveStore()->id)->whereBetween('payment_date', [$data['from'],$data['to']])->orderBy('id','DESC')->get();
        $data['title'] = "Monthly Payment Report By Customer And Payment Method";
        $data['pmthods'] = PaymentMethod::all();
        $data['customers'] = Customer::all();
        return setPageContent('paymentreport.monthly_payment_reports_by_method_by_customer',$data);
    }



    public function payment_analysis(Request $request)
    {
        if($request->get('date')){
            $data['date'] = $request->get('date');
        }else{
            $data['date'] = dailyDate();
        }
        $data['title'] = "Payment Analysis";

        $data['payment_methods'] = PaymentMethod::all();

        return setPageContent('paymentreport.payment_analysis',$data);
    }


    public function income_analysis(Request $request)
    {
        if($request->get('from') && $request->get('to')){
            $data['from'] = $request->get('from');
            $data['to'] = $request->get('to');
            $data['department'] = $request->get('department');
        }else{
            $dpt_key = array_keys(config('app.departments.'.config('app.store')));
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-t');
            $data['department'] = config('app.departments.'.config('app.store'))[$dpt_key[0]];
        }

        $data['expenses'] = Expense::with(['expenses_type','user'])->whereBetween('expense_date',[ $data['from'], $data['to']])->get();
        $data['payments'] = PaymentMethodTable::with(['warehousestore','payment','customer','user','payment_method','invoice'])->where('warehousestore_id',getActiveStore()->id)->whereBetween('payment_date', [$data['from'],$data['to']])->orderBy('id','DESC')->get();

        $data['title'] = "Income Analysis";
        $data['depts'] = config('app.departments.'.config('app.store'));
        return setPageContent('paymentreport.income_analysis',$data);
    }



    public function income_analysis_by_department(Request $request)
    {
        if($request->get('from') && $request->get('to')){
            $data['from'] = $request->get('from');
            $data['to'] = $request->get('to');
            $data['department'] = $request->get('department');
        }else{
            $data['from'] = date('Y-m-01');
            $data['to'] = date('Y-m-t');
            $data['department'] = 2;
        }

        $data['expenses'] = Expense::with(['expenses_type','user'])->where('department', getActiveStore(false, $data['department'])->name)->whereBetween('expense_date',[ $data['from'], $data['to']])->get();

        $data['payments'] = PaymentMethodTable::with(['warehousestore','payment','customer','user','payment_method','invoice'])->where('warehousestore_id',$data['department'])->whereBetween('payment_date', [$data['from'],$data['to']])->orderBy('id','DESC')->get();

        $data['title'] = "Income Analysis By Department";
        $data['depts'] = Warehousestore::all();
        return view('paymentreport.income_analysis_by_department',$data);
    }

}
