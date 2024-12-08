<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    protected $fillable =[
    'MA_SV',
    'MA_GV',
    'MA_DT',
    'HOTEN_SV',
    'TEN_DETAI',
    'LOP_SV',
    'EMAIL_SV',
    'SDT_SV',
    'MK_SV',
    'AVT_SV'
    ];
    public function GiangVien()
    {
        return $this->belongsTo(GiangVien::class, 'MA_GV', 'MA_GV');
    }
    puBlic function DeTai(){
        return $this->belongTo(DeTai::class,'MA_DT','MA_DT');
    }
    
}