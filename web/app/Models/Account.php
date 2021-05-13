<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['client_id', 'currency', 'balance'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactionsReceived(): HasMany
    {
        return $this->hasMany(Transaction::class, 'receiver', 'id');
    }

    /**
     * @return HasMany
     */
    public function transactionsSent(): HasMany
    {
        return $this->hasMany(Transaction::class, 'sender', 'id');
    }
}
