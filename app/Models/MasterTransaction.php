<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTransaction extends Model
{
    use HasFactory;

    protected $table = 'master_transactions';

    // Specify the fields that are mass assignable
    protected $fillable = [
        'transaction_no',
        'total',
    ];
  public function transactions() {
    return $this->hasMany(Transaction::class, 'master_transaction_id', 'transaction_no');
    }
}