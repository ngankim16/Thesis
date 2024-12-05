<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Redirect; 

class SinhVien extends Controller
{
    public function index()
    {
        // Lấy danh sách hội đồng và kết hợp với bảng giảng viên để lấy tên
        $hoiDongs = DB::table('hoi_dong_danh_gia')
            ->join('giang_vien as chu_tich', 'hoi_dong_danh_gia.CHU_TICH_HD', '=', 'chu_tich.MA_GV')
            ->join('giang_vien as pho_chu_tich', 'hoi_dong_danh_gia.PHO_CHU_TICH_HD', '=', 'pho_chu_tich.MA_GV')
            ->join('giang_vien as thu_ky', 'hoi_dong_danh_gia.THUKY_HD', '=', 'thu_ky.MA_GV')
            ->select(
                'hoi_dong_danh_gia.MA_HD',
                'chu_tich.HOTEN_GV as CHU_TICH_TEN', 
                'pho_chu_tich.HOTEN_GV as PHO_CHU_TICH_TEN', 
                'thu_ky.HOTEN_GV as THUKY_TEN', 
                'hoi_dong_danh_gia.NGAY_TAO'
            )
            ->get();
    
        return view('sinhvien.hoidongsv', compact('hoiDongs'));
    }
    
public function getDetail($ma_hd)
{
    // Lấy thông tin hội đồng và các thông tin liên quan
    $hoiDong = DB::table('hoi_dong_danh_gia')
        ->join('to_chuc', 'hoi_dong_danh_gia.MA_HD', '=', 'to_chuc.MA_HD') 
        ->join('buoi_bao_ve', 'to_chuc.MA_BV', '=', 'buoi_bao_ve.MA_BV')
        ->join('phong_hoc', 'buoi_bao_ve.MA_PH', '=', 'phong_hoc.MA_PH') 
        ->leftJoin('giang_vien as chu_tich', 'hoi_dong_danh_gia.CHU_TICH_HD', '=', 'chu_tich.MA_GV') 
        ->leftJoin('giang_vien as pho_chu_tich', 'hoi_dong_danh_gia.PHO_CHU_TICH_HD', '=', 'pho_chu_tich.MA_GV') 
        ->leftJoin('giang_vien as thu_ky', 'hoi_dong_danh_gia.THUKY_HD', '=', 'thu_ky.MA_GV') 
        ->where('hoi_dong_danh_gia.MA_HD', $ma_hd) // Điều kiện tìm kiếm theo MA_HD
        ->select(
            'hoi_dong_danh_gia.*', 
            'buoi_bao_ve.MA_PH', 
            'buoi_bao_ve.NGAY_BV', 
            'phong_hoc.TEN_PH', 
            'chu_tich.HOTEN_GV as CHU_TICH_TEN', 
            'pho_chu_tich.HOTEN_GV as PHO_CHU_TICH_TEN', 
            'thu_ky.HOTEN_GV as THUKY_TEN' 
        )
        ->first(); // Lấy một bản ghi đầu tiên

    // Kiểm tra nếu không tìm thấy hội đồng
    if (!$hoiDong) {
        \Log::error('Không tìm thấy hội đồng với MA_HD: ' . $ma_hd);
        return response()->json(['error' => 'Không tìm thấy hội đồng.'], 404);
    }

    // Lấy danh sách sinh viên
    $sinhVien = DB::table('duoc_danh_gia')
    ->join('sinh_vien', 'duoc_danh_gia.MA_DT', '=', 'sinh_vien.MA_DT') 
    ->join('de_tai', 'sinh_vien.MA_DT', '=', 'de_tai.MA_DT')
    ->join('chitiet_lichbv', 'sinh_vien.MA_SV', '=', 'chitiet_lichbv.MA_SV') 
    ->join('giang_vien', 'sinh_vien.MA_GV', '=', 'giang_vien.MA_GV') 
    ->select(
        'sinh_vien.MA_SV', 
        'sinh_vien.HOTEN_SV', 
        'giang_vien.HOTEN_GV', // Thay MA_GV bằng TEN_GV
        'de_tai.TEN_DT', 
        'chitiet_lichbv.GIO_BAT_DAU', 
        'chitiet_lichbv.GIO_KET_THUC'
    )
    ->where('duoc_danh_gia.MA_HD', $ma_hd) // Thay $ma_hd bằng giá trị cụ thể
    ->get();

    // Kiểm tra nếu không có sinh viên nào
    if ($sinhVien->isEmpty()) {
        \Log::error('Không có sinh viên nào cho MA_HD: ' . $ma_hd);
        return response()->json(['error' => 'Không có sinh viên nào.'], 404);
    }

    // Trả về JSON
    return response()->json([
        'hoiDong' => $hoiDong,
        'sinhVien' => $sinhVien
    ]);
}
public function list_lbv()  
{  
    // Truy vấn danh sách buổi bảo vệ kèm theo thông tin hội đồng  
    $buoi_bao_ve = DB::table('buoi_bao_ve')  
        ->join('phong_hoc', 'buoi_bao_ve.MA_PH', '=', 'phong_hoc.MA_PH')  
        ->select(  
            'buoi_bao_ve.MA_BV',  
            'buoi_bao_ve.MA_PH',  
            'buoi_bao_ve.GIO_BATDAU_BV',  
            'buoi_bao_ve.NGAY_BV',  
            'buoi_bao_ve.THOILUONG_BV',  
            'buoi_bao_ve.SO_LUONG_BV'  
        )  
        ->get();  

    return view('sinhvien.list_lichbaovesv', compact('buoi_bao_ve'));  
}  
}