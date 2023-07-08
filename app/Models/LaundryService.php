<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LaundryService
 * 
 * @property int $id
 * @property string $laundry_service_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ClothServiceMapper[] $cloth_service_mappers
 *
 * @package App\Models
 */
class LaundryService extends Model
{
	protected $table = 'laundry_services';

	protected $fillable = [
		'laundry_service_name'
	];

	public function cloth_service_mappers()
	{
		return $this->hasMany(ClothServiceMapper::class);
	}
}
