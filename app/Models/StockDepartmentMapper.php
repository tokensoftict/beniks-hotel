<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StockDepartmentMapper
 *
 * @property int $id
 * @property int $stock_id
 * @property int $warehousestore_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class StockDepartmentMapper extends Model
{
	protected $table = 'stock_department_mappers';

	protected $casts = [
		'stock_id' => 'int',
		'warehousestore_id' => 'int'
	];

	protected $fillable = [
		'stock_id',
		'warehousestore_id'
	];

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }


    public function warehouse()
    {
        return $this->belongsTo(Warehousestore::class, 'warehousestore_id');
    }

}
