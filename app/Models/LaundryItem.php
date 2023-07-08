<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LaundryItem
 * 
 * @property int $id
 * @property int $laundry_id
 * @property int|null $cloth_service_mapper_id
 * @property int|null $warehousestore_id
 * @property string $department
 * @property int $quantity
 * @property int|null $customer_id
 * @property string $status
 * @property int $added_by
 * @property Carbon $invoice_date
 * @property string $store
 * @property Carbon $sales_time
 * @property float|null $selling_price
 * @property float|null $profit
 * @property float|null $total_selling_price
 * @property string|null $discount_type
 * @property float|null $discount_amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ClothServiceMapper|null $cloth_service_mapper
 * @property Customer|null $customer
 * @property Laundry $laundry
 * @property Warehousestore|null $warehousestore
 *
 * @package App\Models
 */
class LaundryItem extends Model
{
	protected $table = 'laundry_items';

	protected $casts = [
		'laundry_id' => 'int',
		'cloth_service_mapper_id' => 'int',
		'warehousestore_id' => 'int',
		'quantity' => 'int',
		'customer_id' => 'int',
		'added_by' => 'int',
		'selling_price' => 'float',
		'profit' => 'float',
		'total_selling_price' => 'float',
		'discount_amount' => 'float'
	];

	protected $dates = [
		'invoice_date',
		'sales_time'
	];

	protected $fillable = [
		'laundry_id',
		'cloth_service_mapper_id',
		'warehousestore_id',
		'department',
		'quantity',
		'customer_id',
		'status',
		'added_by',
		'invoice_date',
		'store',
		'sales_time',
		'selling_price',
		'profit',
		'total_selling_price',
		'discount_type',
		'discount_amount'
	];

	public function cloth_service_mapper()
	{
		return $this->belongsTo(ClothServiceMapper::class);
	}

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

	public function laundry()
	{
		return $this->belongsTo(Laundry::class);
	}

	public function warehousestore()
	{
		return $this->belongsTo(Warehousestore::class);
	}
}
