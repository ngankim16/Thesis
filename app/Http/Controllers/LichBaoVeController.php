<?php  

namespace App\Http\Controllers;  
use Carbon\Carbon;

use Illuminate\Http\Request;  
use Illuminate\View\View;  
use Illuminate\Support\Facades\DB;   
use Illuminate\Support\Facades\Redirect;   
use Illuminate\Support\Facades\Mail; // Sử dụng thư viện Mail
use App\Mail\LichBaoVeMail; // Nếu bạn đã tạo mail class
class LichBaoVeController extends Controller  
{  
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

        return view('admin.lichbaove.list_lichbaove', compact('buoi_bao_ve'));  
    }  

    public function add_lbv(Request $request)  
    {  
        // Lấy danh sách phòng học  
        $phong_hoc = DB::table('phong_hoc')->orderBy('MA_PH', 'desc')->get();  

        // Lấy mã buổi bảo vệ cuối cùng từ bảng  
        $lastCode = DB::table('buoi_bao_ve')->orderBy('MA_BV', 'desc')->value('MA_BV');  
        
        // Tạo mã buổi bảo vệ mới  
        if ($lastCode) {  
            $number = (int) filter_var($lastCode, FILTER_SANITIZE_NUMBER_INT);  
            $newCode = 'BV' . str_pad($number + 1, 2, '0', STR_PAD_LEFT);  
        } else {  
            $newCode = 'BV01';  
        }  

        // Lấy danh sách sinh viên  
        $sinh_vien = DB::table('sinh_vien')
        ->leftJoin('chitiet_lichbv','sinh_vien.MA_SV','=','chitiet_lichbv.MA_SV')
        ->whereNull('chitiet_lichbv.MA_SV') //chỉ lấy sinh viên chưa có trong chi tiêt lich bảo vệvệ
        ->select('sinh_vien.*')
        ->get();  

        // Trả dữ liệu về view  
        return view('admin.lichbaove.add_lichbaove', [  
            'newCode' => $newCode,  
            'phong_hoc' => $phong_hoc,  
            'sinh_vien' => $sinh_vien  
        ]);  
    }  


    public function showBuoiBaoVe($ma_bv)  
    {  
        // Truy vấn để lấy thông tin buổi bảo vệ và phòng học  
        $buoiBaoVe = DB::table('buoi_bao_ve')  
            ->join('phong_hoc', 'buoi_bao_ve.MA_PH', '=', 'phong_hoc.MA_PH')  
            ->where('buoi_bao_ve.MA_BV', $ma_bv)  
            ->select(  
                'buoi_bao_ve.MA_BV',  
                'buoi_bao_ve.NGAY_BV',  
                'buoi_bao_ve.GIO_BATDAU_BV',  
                'phong_hoc.TEN_PH'  
            )  
            ->first();  

        // Lấy danh sách sinh viên tham gia buổi bảo vệ  
        $sinhVienList = DB::table('chitiet_lichbv')  
            ->join('sinh_vien', 'chitiet_lichbv.MA_SV', '=', 'sinh_vien.MA_SV')  
            ->join('de_tai', 'sinh_vien.MA_DT', '=', 'de_tai.MA_DT')  
            ->where('chitiet_lichbv.MA_BV', $ma_bv)  
            ->select(  
                'sinh_vien.MA_SV',  
                'sinh_vien.HOTEN_SV',  
                'de_tai.TEN_DT'  
            )  
            ->get();    
        return view('admin.lichbaove.buoi_bao_ve_detail', [  
            'buoiBaoVe' => $buoiBaoVe,  
            'sinhVienList' => $sinhVienList  
        ]);  
    }  

    public function save_lbv(Request $request) {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'MA_BV' => 'required',
            'NGAY_BV' => 'required|date',
            'sinh_vien' => 'required|array',
        ]);
    
        // Khởi tạo biến
        $morning_start = '07:15';
        $morning_end = '10:10';
        $afternoon_start = '13:30';
        $afternoon_end = '16:25';
        $time_increment = 35;
        $max_sessions_per_room = 12;
    
        $current_time = $morning_start;
        $current_day = $request->input('NGAY_BV');
        $current_room = 1;
        $current_students = 0;
        $total_rooms = DB::table('phong_hoc')->count();
    
        foreach ($request->input('sinh_vien') as $ma_sv) {
            $ma_dt = DB::table('sinh_vien')->where('MA_SV', $ma_sv)->value('MA_DT');
            $ma_hd = DB::table('duoc_danh_gia')->where('MA_DT', $ma_dt)->value('MA_HD');
    
            if (!$ma_dt || !$ma_hd) {
                return redirect()->back()->withErrors(['Không tìm thấy thông tin đầy đủ cho sinh viên mã ' . $ma_sv]);
            }
    
            // Xác định thời gian bắt đầu cho sinh viên
            if ($current_students < 6) { // 6 sinh viên cho buổi sáng
                $current_time = date('H:i', strtotime('07:15 + ' . ($current_students * $time_increment) . ' minutes'));
            } else { // 7 sinh viên cho buổi chiều
                $current_time = date('H:i', strtotime('13:30 + ' . (($current_students - 6) * $time_increment) . ' minutes'));
            }
    
            // Tìm phòng còn trống cho thời gian hiện tại và ngày bảo vệ
            $availableRoom = null;
            while (is_null($availableRoom) && $current_room <= $total_rooms) {
                $availableRoom = $this->findAvailableRoom($current_time, $current_day, $current_room);
                if (is_null($availableRoom)) {
                    $current_room++;
                }
            }
    
            // Nếu không tìm thấy phòng trống, chuyển sang ngày tiếp theo
            if (is_null($availableRoom)) {
                $current_day = date('Y-m-d', strtotime('+1 day', strtotime($current_day)));
                $current_room = 1;
                $current_students = 0;
                $current_time = $morning_start;
                // Đặt lại thời gian cho sinh viên
                $current_time = $current_students < 6 ?
                    date('H:i', strtotime('07:15 + ' . ($current_students * $time_increment) . ' minutes')) :
                    date('H:i', strtotime('13:30 + ' . (($current_students - 6) * $time_increment) . ' minutes'));
            }
    
            // Nếu tìm thấy phòng trống
            if ($availableRoom) {
                $ma_ph = $availableRoom->MA_PH;
    
                // Sắp xếp cho sinh viên
                $this->scheduleStudent($ma_sv, $ma_ph, $current_time, $ma_hd, $current_day);
    
                $current_students++; // Cập nhật số sinh viên đã sắp xếp
    
                // Nếu phòng hiện tại đã đầy sinh viên
                if ($current_students >= $max_sessions_per_room) {
                    // Đặt lại đếm sinh viên cho phòng tiếp theo
                    $current_students = 0;
                    $current_room++;
                }
            }
        }
    
        return redirect()->route('lichbaove.list_lbv')->with('thongbao', 'Thêm lịch bảo vệ thành công!');
    }
    
    // Phương thức tìm phòng còn trống
    private function findAvailableRoom($current_time, $current_day, $current_room) {
        return DB::table('phong_hoc')
            ->whereNotExists(function ($query) use ($current_time, $current_day) {
                $query->select(DB::raw(1))
                    ->from('buoi_bao_ve')
                    ->whereColumn('phong_hoc.MA_PH', 'buoi_bao_ve.MA_PH')
                    ->where('buoi_bao_ve.NGAY_BV', $current_day)
                    ->whereBetween('buoi_bao_ve.GIO_BATDAU_BV', [
                        $current_time,
                        date('H:i', strtotime($current_time . ' +35 minutes'))
                    ]);
            })
            ->where('MA_PH', 'P' . str_pad($current_room, 2, '0', STR_PAD_LEFT))
            ->first();
    }
    
    // Phương thức sắp xếp cho sinh viên
    private function scheduleStudent($ma_sv, $ma_ph, $gio_bat_dau, $ma_hd, $ngay_bv) {
        // Sinh mã buổi bảo vệ duy nhất
        $lastMaBV = DB::table('buoi_bao_ve')->orderBy('MA_BV', 'desc')->first();
        $ma_bv = $lastMaBV ? 'BV' . str_pad((int)substr($lastMaBV->MA_BV, 2) + 1, 3, '0', STR_PAD_LEFT) : 'BV001';
    
        // Chèn buổi bảo vệ vào cơ sở dữ liệu
        DB::table('buoi_bao_ve')->insert([
            'MA_BV' => $ma_bv,
            'MA_PH' => $ma_ph,
            'GIO_BATDAU_BV' => $gio_bat_dau,
            'NGAY_BV' => $ngay_bv,
            'SO_LUONG_BV' => 1,
        ]);
    
        // Tính giờ kết thúc cho buổi bảo vệ
        $gio_ket_thuc = date('H:i', strtotime("+35 minutes", strtotime($gio_bat_dau)));
    
        // Chèn chi tiết buổi bảo vệ
        DB::table('chitiet_lichbv')->insert([
            'MA_BV' => $ma_bv,
            'MA_SV' => $ma_sv,
            'GIO_BAT_DAU' => $gio_bat_dau,
            'GIO_KET_THUC' => $gio_ket_thuc,
        ]);
    
        // Chèn thông tin tổ chức
        DB::table('to_chuc')->insert([
            'MA_HD' => $ma_hd,
            'MA_BV' => $ma_bv,
        ]);
    }
    

        public function deleteAll()
        {
            try {
                // Xóa tất cả dữ liệu liên quan
                DB::table('to_chuc')->delete();
                DB::table('chitiet_lichbv')->delete();
                DB::table('buoi_bao_ve')->delete();
           
                return redirect()->back()->with('success', 'Đã xóa tất cả hội đồng thành công!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa hội đồng: ' . $e->getMessage());
            }
        }
    public function getSinhVienByBuoiBaoVe($ma_bv)
{
    \Log::info("Mã Buổi Bảo Vệ: " . $ma_bv); // Ghi log giá trị $ma_bv

    $sinhVienList = DB::table('chitiet_lichbv')
      ->join('sinh_vien','chitiet_lichbv.MA_SV','=','sinh_vien.MA_SV')
      ->join('de_tai','sinh_vien.MA_DT','=','de_tai.MA_DT')
      ->join('duoc_danh_gia','de_tai.MA_DT','=','duoc_danh_gia.MA_DT')
      ->join('hoi_dong_danh_gia','duoc_danh_gia.MA_HD','=','hoi_dong_danh_gia.MA_HD')
      ->join('giang_vien AS chu_tich_hd','hoi_dong_danh_gia.CHU_TICH_HD','=','chu_tich_hd.MA_GV')
      ->join('giang_vien AS pho_chu_tich_hd','hoi_dong_danh_gia.PHO_CHU_TICH_HD','=','pho_chu_tich_hd.MA_GV')
      ->join('giang_vien AS thuky_hd','hoi_dong_danh_gia.THUKY_HD','=','thuky_hd.MA_GV')
      ->where('chitiet_lichbv.MA_BV',$ma_bv)
      ->select(
        'sinh_vien.MA_SV',
        'sinh_vien.HOTEN_SV',
        'de_tai.TEN_DT',
        'chu_tich_hd.HOTEN_GV AS TEN_CHU_TICH',
        'pho_chu_tich_hd.HOTEN_GV AS TEN_PHO_CHU_TICH',
        'thuky_hd.HOTEN_GV AS TEN_THU_KY'
      )
      ->get();

    return response()->json(['sinhVien' => $sinhVienList]);
}
public function destroy($ma_bv) {
    DB::table('to_chuc')->where('MA_BV', $ma_bv)->delete();
    DB::table('chitiet_lichbv')->where('MA_BV', $ma_bv)->delete();
    DB::table('buoi_bao_ve')->where('MA_BV', $ma_bv)->delete();

    return redirect()->route('lichbaove.list_lbv')->with('thongbao', 'Xóa thành công!');
}
    public function edit($ma_bv) {
    // Truy vấn để lấy dữ liệu cho buổi bảo vệ cần chỉnh sửa
        $buoiBaoVe = DB::table('buoi_bao_ve')->where('MA_BV', $ma_bv)->first();
    
        return view('admin.lichbaove.edit', compact('buoiBaoVe'));
    }
    
    public function update(Request $request, $ma_bv)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'phong_hoc' => 'required|string|max:255',
            'gio_bat_dau' => 'required|date_format:H:i',
            'ngay_bao_ve' => 'required|date',
            'thoi_luong' => 'required|integer|min:1',
        ]);
    
        // Cập nhật thông tin buổi bảo vệ
        DB::table('buoi_bao_ve')->where('MA_BV', $ma_bv)->update([
            'MA_PH' => $request->phong_hoc,
            'GIO_BATDAU_BV' => $request->gio_bat_dau,
            'NGAY_BV' => $request->ngay_bao_ve,
            'THOILUONG_BV' => $request->thoi_luong,
        ]);
    
        return redirect()->route('lichbaove.list_lbv')->with('success', 'Buổi bảo vệ đã được cập nhật thành công.');
    }

    
}   