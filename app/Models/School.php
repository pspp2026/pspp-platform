<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $table = 'schools';

    protected $fillable = [
        'school_code',
        'school_name',
        'temple',
        'address1',
        'address2',
        'province_id',
        'district_id',
        'subdistrict_id',
        'zone_code',
        'website',
        'facebook',
        'phone',
        'email',
    ];

    /**
     * 🔥 Auto คำนวณ zone_code ตอน save
     */
    protected static function booted()
    {
        static::saving(function ($school) {
            $school->zone_code = self::mapZone($school->province_id);
        });
    }

    /**
     * 🔥 map จังหวัด → เขต
     */
   public static function mapZone($province_id)
    {
        $province_id = (int) $province_id;

        return match ($province_id) {

            10,11,12,13,14,15,16,18,19,73,74,75 => 1,
            70,71,72,76,77 => 2,
            60,61,62,63,64,65,66 => 3,
            67,42,39,41,43,38 => 4,
            50,51,58 => 5,
            57,56,54,55,52 => 6,
            40,46,44,45 => 7,
            47,48,49,35,34,37 => 8,
            30,31,32,33,36 => 9,
            24,25,26,27,20,21,22,23 => 12,

            default => null,
        };
    }

    /**
     * 🔗 จังหวัด
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * 🔗 อำเภอ
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * 🔗 ตำบล
     */
    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class, 'subdistrict_id');
    }

    /**
     * 🔥 รวมที่อยู่ (ป้องกัน error null)
     */
    public function getFullAddressAttribute()
    {
        return trim(
            ($this->subdistrict->name_th ?? '') . ' ' .
            ($this->district->name_th ?? '') . ' ' .
            ($this->province->name_th ?? '')
        );
    }

    /**
     * 🔥 scope filter จังหวัด
     */
    public function scopeProvince($query, $provinceId)
    {
        return $query->where('province_id', $provinceId);
    }

    /**
     * 🔥 scope filter เขต
     */
    public function scopeZone($query, $zoneCode)
    {
        return $query->where('zone_code', $zoneCode);
    }
}