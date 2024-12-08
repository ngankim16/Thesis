<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeTai extends Model {  
    protected $table = 'de_tai'; // Tên bảng trong cơ sở dữ liệu  
    protected $primaryKey = 'MA_DT';
    protected $fillable = ['MA_DT', 'TEN_DT']; // Các thuộc tính có thể được gán  
    public $timestamps = false;
}  