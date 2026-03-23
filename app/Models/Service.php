<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Service model for Alam Sari Properti
 * Now used for property assets management
 *
 * @property int $id
 * @property string $name
 * @property string $property_type
 * @property string|null $location
 * @property int $price
 * @property string $status
 * @property string|null $description
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Service extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
        'name',
        'property_type',
        'location',
        'price',
        'status',
        'description',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
	    'price' => 'integer',
	];
}
