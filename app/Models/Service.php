<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Service model for Mancraft barbershop
 *
 * @property int $id
 * @property string $name
 * @property int $price
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
        'price',
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
