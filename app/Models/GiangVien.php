<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiangVien extends Model
{
   protected $fillable=[
    'MA_GV',
    'HOTEN_GV',
    'EMAIL_GV',
    'NGAYSINH_GV',
    'SDT_GV',
    'MATKHAU_GV',
    'AVT_GV',
   ];
}