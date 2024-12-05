<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoiDongDanhGia extends Model
{
    protected $table = 'hoi_dong_danh_gia'; // Tên bảng
    protected $primaryKey = 'MA_HD'; // Khóa chính
    public $timestamps = false; // Nếu không có timestamps

    protected $fillable = [
        'CHU_TICH_HD',
        'PHO_CHU_TICH_HD',
        'THUKy_HD',
    ];
}