<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    protected $table = 'subdistricts';

    protected $primaryKey = 'subdistrict_id';
    public $incrementing = false;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'district_id',
        'name_th',
        'name_en',
        'lat',
        'lng',
        'zipcode'
    ];

    // 🔗 relation: ตำบล → อำเภอ
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'district_id');
    }
}