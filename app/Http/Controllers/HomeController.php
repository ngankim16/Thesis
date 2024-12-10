<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Thêm dòng này
use Illuminate\Support\Facades\Hash; // Thêm dòng này
session_start();
class HomeController extends Controller
{
    public function index()
    {
        $hoidongs = DB::table('hoi_dong_danh_gia')->get();

        return view('welcome');
        
    }
    
    public function show_dashboard_sv()
    {
        $total_sinhvien = DB::table('sinh_vien')->count();
        $total_giangvien = DB::table('giang_vien')->count();
        $total_hoidong = DB::table('hoi_dong_danh_gia')->count();

        return view('sinhvien.dashboard_sinh_vien',compact('total_sinhvien','total_giangvien','total_hoidong'));
    }

    public function show_dashboard()
    {
        $total_sinhvien = DB::table('sinh_vien')->count();
        $total_giangvien = DB::table('giang_vien')->count();
        $total_hoidong = DB::table('hoi_dong_danh_gia')->count();
        $lichBaoVeDates = DB::table('buoi_bao_ve')->pluck('NGAY_BV')->toArray(); 
        $hoidongs = DB::table('hoi_dong_danh_gia')->get();
        
    // Kiểm tra hội đồng cần duyệt cho giảng viên
    $hoidongsChuaDuyet = DB::table('gom')
    ->where('MA_GV', session('MA_GV')) // Lọc theo giảng viên đang đăng nhập
    ->whereNull('DUYET_THAM_GIA') // Những hội đồng chưa duyệt
    ->get();

// Nếu có hội đồng chưa duyệt, thêm thông báo
if ($hoidongsChuaDuyet->isNotEmpty()) {
    session()->flash('thongbao', 'Bạn có hội đồng cần duyệt!');
}
        return view('admin.dashboard',compact('total_sinhvien','total_giangvien','total_hoidong','lichBaoVeDates','hoidongs'));
    }
    
    public function dangnhap(Request $request)
    {
        $email_gv = $request->email_gv;
        $matkhau_gv = $request->matkhau_gv;
    
        // Kiểm tra trong bảng giảng viên
        $giangVien = DB::table('giang_vien')->where('EMAIL_GV', $email_gv)->where('MATKHAU_GV', $matkhau_gv)->first();
    
        if ($giangVien) {
            // Lưu thông tin giảng viên vào session
            Session::put('MA_GV', $giangVien->MA_GV);
            Session::put('HOTEN_GV', $giangVien->HOTEN_GV);
          
    
            // Kiểm tra lịch bảo vệ sắp tới cho giảng viên
            return $this->checkUpcomingScheduleForGiangVien($giangVien->MA_GV);
        } else {
            // Kiểm tra trong bảng sinh viên
            $sinhVien = DB::table('sinh_vien')->where('EMAIL_SV', $email_gv)->where('MK_SV', $matkhau_gv)->first();
    
            if ($sinhVien) {
                // Lưu thông tin sinh viên vào session
                Session::put('MA_SV', $sinhVien->MA_SV);
                Session::put('HOTEN_SV', $sinhVien->HOTEN_SV);
            
    
                // Kiểm tra lịch bảo vệ sắp tới cho sinh viên
                return $this->checkUpcomingScheduleForSinhVien($sinhVien->MA_SV);
            } else {
                // Gửi thông báo lỗi nếu email hoặc mật khẩu không đúng
                Session::put('error', 'Sai email hoặc mật khẩu. Vui lòng thử lại!');
                return Redirect::to('/login');
            }
        }
    }
    
    


    // Kiểm tra lịch bảo vệ cho giảng viên
    private function checkUpcomingScheduleForGiangVien($ma_gv)
    {
        $upcomingSchedule = DB::table('to_chuc')
            ->join('chitiet_lichbv', 'to_chuc.MA_BV', '=', 'chitiet_lichbv.MA_BV')
            ->join('buoi_bao_ve', 'chitiet_lichbv.MA_BV', '=', 'buoi_bao_ve.MA_BV')
            ->join('sinh_vien', 'chitiet_lichbv.MA_SV', '=', 'sinh_vien.MA_SV')
            ->join('giang_vien', 'sinh_vien.MA_GV', '=', 'giang_vien.MA_GV')
            ->where('giang_vien.MA_GV', $ma_gv)  // Kiểm tra giảng viên
            ->whereDate('buoi_bao_ve.NGAY_BV', '>=', now())  // Kiểm tra lịch bảo vệ sắp tới
            ->orderBy('buoi_bao_ve.NGAY_BV', 'asc')
            ->first();

        if ($upcomingSchedule) {
            // Lưu thông tin lịch bảo vệ vào session
            Session::put('schedule_alert', [
                'time' => $upcomingSchedule->GIO_BATDAU_BV,
                'date' => $upcomingSchedule->NGAY_BV,
                'room' => $upcomingSchedule->MA_PH,
            ]);

            // Hiển thị thông báo cho giảng viên
            return redirect('dashboard')->with('thongbao', 'Bạn sắp có lịch bảo vệ lúc ' . $upcomingSchedule->GIO_BATDAU_BV . ', ngày ' . $upcomingSchedule->NGAY_BV . ', phòng ' . $upcomingSchedule->MA_PH);
        } 
            // Nếu không có lịch bảo vệ sắp tới
            return redirect('/dashboard');
        
    }

    // Kiểm tra lịch bảo vệ cho sinh viên
    private function checkUpcomingScheduleForSinhVien($ma_sv)
    {
        $upcomingSchedule = DB::table('to_chuc')
            ->join('chitiet_lichbv', 'to_chuc.MA_BV', '=', 'chitiet_lichbv.MA_BV')
            ->join('buoi_bao_ve', 'chitiet_lichbv.MA_BV', '=', 'buoi_bao_ve.MA_BV')
            ->join('sinh_vien', 'chitiet_lichbv.MA_SV', '=', 'sinh_vien.MA_SV')
            ->join('giang_vien', 'sinh_vien.MA_GV', '=', 'giang_vien.MA_GV')
            ->where('sinh_vien.MA_SV', $ma_sv)  // Kiểm tra sinh viên
            ->whereDate('buoi_bao_ve.NGAY_BV', '>=', now())  // Kiểm tra lịch bảo vệ sắp tới
            ->orderBy('buoi_bao_ve.NGAY_BV', 'asc')
            ->first();
    
        if ($upcomingSchedule) {
            // Lưu thông tin lịch bảo vệ vào session
            Session::put('schedule_alert', [
                'time' => $upcomingSchedule->GIO_BATDAU_BV,
                'date' => $upcomingSchedule->NGAY_BV,
                'room' => $upcomingSchedule->MA_PH,
            ]);
    
            // Hiển thị thông báo cho sinh viên
            return redirect('/dashboard_sinh_vien')->with('thongbao', 'Bạn sắp có lịch bảo vệ lúc ' . $upcomingSchedule->GIO_BATDAU_BV . ', ngày ' . $upcomingSchedule->NGAY_BV . ', phòng ' . $upcomingSchedule->MA_PH);
        }
    
        // Không có thông báo nào được trả về nếu không có lịch bảo vệ
        return redirect('/dashboard_sinh_vien');
    }

    public function logout(Request $request){
    Auth::logout();
    return redirect('/');
    }



}