<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Laundry
 *
 * @property int $id
 * @property string $invoice_number
 * @property string $invoice_paper_number
 * @property string $department
 * @property int $payment_id
 * @property int|null $warehousestore_id
 * @property int|null $customer_id
 * @property string|null $discount_type
 * @property float|null $discount_amount
 * @property string $status
 * @property float $sub_total
 * @property float $total_amount_paid
 * @property float $total_profit
 * @property float $total_cost
 * @property float $vat
 * @property float $vat_amount
 * @property int|null $created_by
 * @property int|null $last_updated_by
 * @property int|null $voided_by
 * @property Carbon $invoice_date
 * @property Carbon $sales_time
 * @property string|null $void_reason
 * @property Carbon|null $date_voided
 * @property Carbon|null $void_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User|null $user
 * @property Customer|null $customer
 * @property Payment $payment
 * @property Warehousestore|null $warehousestore
 * @property Collection|LaundryItem[] $laundry_items
 *
 * @package App\Models
 */
class Laundry extends Model
{
	protected $table = 'laundries';

	protected $casts = [
		'payment_id' => 'int',
		'warehousestore_id' => 'int',
		'customer_id' => 'int',
		'discount_amount' => 'float',
		'sub_total' => 'float',
		'total_amount_paid' => 'float',
		'total_profit' => 'float',
		'total_cost' => 'float',
		'vat' => 'float',
		'vat_amount' => 'float',
		'created_by' => 'int',
		'last_updated_by' => 'int',
		'voided_by' => 'int'
	];

	protected $dates = [
		'invoice_date',
		'sales_time',
		'date_voided',
		'void_time'
	];

	protected $fillable = [
		'invoice_number',
		'invoice_paper_number',
		'department',
		'payment_id',
		'warehousestore_id',
		'customer_id',
		'discount_type',
		'discount_amount',
		'status',
		'sub_total',
		'total_amount_paid',
		'total_profit',
		'total_cost',
		'vat',
		'vat_amount',
		'created_by',
		'last_updated_by',
		'voided_by',
		'invoice_date',
		'sales_time',
		'void_reason',
		'date_voided',
		'void_time'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'voided_by');
	}

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

	public function payment()
	{
		return $this->belongsTo(Payment::class);
	}

	public function warehousestore()
	{
		return $this->belongsTo(Warehousestore::class);
	}

	public function laundry_items()
	{
		return $this->hasMany(LaundryItem::class);
	}

    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function last_updated()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    public static function createInvoice(Request  $request) : Laundry
    {

        $data = self::calculateInvoice($request);

        $invoice = Laundry::create(
            [
                'invoice_number' => time(),
                'invoice_paper_number' =>time(),
                'department' => getActiveStore()->name,
                'warehousestore_id' => getActiveStore()->id,
                'total_amount_paid' => 0,
                'customer_id' => $request->get('customer_id') ?? 1,
                'discount_type' => 'None',
                'payment_id' => NULL,
                'discount_amount' => 0,
                'status' => $request->get('status'),
                'sub_total' => $data['sub_total'],
                'total_profit' => $data['sub_total'],
                'total_cost' => 0,
                'vat' => 0,
                'vat_amount' => 0,
                'created_by' => auth()->id(),
                'last_updated_by' => auth()->id(),
                'invoice_date' => $request->get('date'),
                'sales_time' => Carbon::now()->toDateTimeLocalString(),
            ]
        );

        $invoice->laundry_items()->saveMany($data['items']);

        return $invoice;
    }


    public static function updateInvoice(Request $request, $invoice) : Laundry
    {
        $data = self::calculateInvoice($request);

        $invoice->laundry_items()->delete();

        $payment_id = $invoice->payment_id;

        $invoice->payment_id = NULL;

        $invoice->total_amount_paid = 0;

        $invoice->update();

        if($payment_id != NULL) {
            $payment = Payment::find($payment_id);

            $payment->delete();
        }

        $invoice->fill([
            'total_amount_paid' => 0,
            'customer_id' => $request->get('customer_id') ?? 1,
            'discount_type' => 'None',
            'payment_id' => NULL,
            'discount_amount' => 0,
            'status' => $request->get('status'),
            'sub_total' => $data['sub_total'],
            'total_profit' => $data['sub_total'],
            'total_cost' => 0,
            'vat' => 0,
            'vat_amount' => 0,
            'last_updated_by' => auth()->id(),
            //'invoice_date' => $request->get('date'),
            //'sales_time' => Carbon::now()->toDateTimeLocalString(),
        ])->save();

        $invoice->laundry_items()->saveMany($data['items']);

        return $invoice;
    }

    public static function calculateInvoice(Request  $request)
    {
        $laundryItems = [];
        $items = json_decode($request->get('data'),true);
        $total = 0;
        foreach ($items as $item)
        {
            $clothServiceMapper = ClothServiceMapper::where(['cloth_id' => $item['id'],'laundry_service_id' => $item['type']])->first();
            $total += $clothServiceMapper->price * $item['qty'];

            $laundryItems[] = new LaundryItem( [
                'cloth_service_mapper_id' => $clothServiceMapper->id,
                'warehousestore_id' => getActiveStore()->id,
                'department' => getActiveStore()->name,
                'quantity' => $item['qty'],
                'customer_id' => $request->get('customer_id') ?? 1,
                'status' => $request->get('status'),
                'added_by' => auth()->id(),
                'invoice_date' => $request->get('date'),
                'store' =>getActiveStore()->name,
                'sales_time' => Carbon::now()->toDateTimeLocalString(),
                'selling_price' => $clothServiceMapper->price,
                'profit' => $clothServiceMapper->profit,
                'total_selling_price' => $clothServiceMapper->price * $item['qty'],
                'discount_type' => "None",
                "discount_amount" => 0,
            ]);

        }


        return ['sub_total' => $total, 'items' => $laundryItems];
    }
}
