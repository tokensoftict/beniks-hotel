<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cloth
 * 
 * @property int $id
 * @property string $cloth_name
 * @property string $cloth_description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ClothServiceMapper[] $cloth_service_mappers
 *
 * @package App\Models
 */
class Cloth extends Model
{
	protected $table = 'cloths';

	protected $fillable = [
		'cloth_name',
		'cloth_description'
	];

	public function cloth_service_mappers()
	{
		return $this->hasMany(ClothServiceMapper::class);
	}
}
