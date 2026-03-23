<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Transaction model for Alam Sari Properti
 * Handles property transactions with 5% agent commission
 *
 * @property int $id
 * @property string $type
 * @property int $amount
 * @property string|null $description
 * @property string $transaction_date
 * @property int|null $user_id
 * @property int|null $service_id
 * @property int|null $agent_id
 * @property string|null $agent_name
 * @property float $agent_commission
 * @property float $commission_rate
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Transaction extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'type',
		'amount',
		'description',
		'transaction_date',
		'user_id',
		'service_id',
		'agent_id',
		'agent_name',
		'agent_commission',
		'commission_rate',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'amount' => 'integer',
		'transaction_date' => 'date',
		'agent_commission' => 'decimal:2',
		'commission_rate' => 'decimal:2',
	];

	/**
	 * Get the user who made the transaction.
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Get the service related to the transaction.
	 */
	public function service(): BelongsTo
	{
		return $this->belongsTo(Service::class);
	}

	/**
	 * Get the agent who handled the transaction.
	 */
	public function agent(): BelongsTo
	{
		return $this->belongsTo(User::class, 'agent_id');
	}
}
