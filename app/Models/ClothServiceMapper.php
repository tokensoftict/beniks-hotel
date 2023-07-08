<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClothServiceMapper
 * 
 * @property int $id
 * @property int $laundry_service_id
 * @property int $cloth_id
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Cloth $cloth
 * @property LaundryService $laundry_service
 *
 * @package App\Models
 */
class ClothServiceMapper extends Model
{
	protected $table = 'cloth_service_mappers';

	protected $casts = [
		'laundry_service_id' => 'int',
		'cloth_id' => 'int',
		'price' => 'float'
	];

	protected $fillable = [
		'laundry_service_id',
		'cloth_id',
		'price'
	];

	public function cloth()
	{
		return $this->belongsTo(Cloth::class);
	}

	public function laundry_service()
	{
		return $this->belongsTo(LaundryService::class);
	}
}
