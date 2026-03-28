<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';

    protected $primaryKey = 'district_id';
    public $incrementing = false;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'province_id',
        'name_th',
        'name_en'
    ];

    // 🔗 relation: อำเภอ → จังหวัด
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }

    // 🔗 relation: อำเภอ → ตำบล
    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class, 'district_id', 'district_id');
    }
}