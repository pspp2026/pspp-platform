<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';

    protected $primaryKey = 'province_id';
    public $incrementing = false;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'name_th',
        'name_en',
        'zone_id'
    ];

    // 🔗 relation: จังหวัด → อำเภอ
    public function districts()
    {
        return $this->hasMany(District::class, 'province_id', 'province_id');
    }
}