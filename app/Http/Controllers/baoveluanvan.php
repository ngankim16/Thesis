<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Redirect; 
use App\Models\HoiDongDanhGia;

use Barryvdh\DomPDF\Facade\Pdf;

class baoveluanvan extends Controller
{
    public function list_hd(){
        return view('admin.list_hoidong');
    }
    public function taoHoiDong()
    {
        // Kiểm tra nếu đã có hội đồng được tạo trước đó
        $existing = DB::table('duoc_danh_gia')->exists();
        if ($existing) {
            return redirect()->back()->with('thongbao', 'Hội đồng đã được tạo trước đó.');
        }
    
        // Lấy danh sách giảng viên và sinh viên
        $giangViens = DB::table('giang_vien')->get();
        $sinhViens = DB::table('sinh_vien')->get();
    
        // Kiểm tra nếu không có đủ giảng viên hoặc sinh viên
        if ($giangViens->isEmpty() || $sinhViens->isEmpty()) {
            return redirect()->back()->with('thongbao', 'Không đủ giảng viên hoặc sinh viên để tạo hội đồng.');
        }
    
        // Danh sách để theo dõi số lần đảm nhận vai trò của giảng viên
        $vaiTroGiangVien = [];
        foreach ($giangViens as $giangVien) {
            $vaiTroGiangVien[$giangVien->MA_GV] = [
                'so_lan' => 0, // Tổng số lần đảm nhận vai trò
            ];
        }
    
        // Nhóm sinh viên theo giảng viên hướng dẫn
        $nhomSinhVien = $sinhViens->groupBy('MA_GV');
    
        foreach ($nhomSinhVien as $maGVHuongDan => $sinhViensTrongNhom) {
            // Loại giảng viên hướng dẫn ra khỏi danh sách Chủ tịch và Phó Chủ tịch
            $giangViensChucVu = $giangViens->filter(function ($gv) use ($maGVHuongDan) {
                return $gv->MA_GV != $maGVHuongDan;
            });
    
            // Chọn Chủ tịch (chỉ chọn một lần cho giảng viên hướng dẫn này)
            $chuTich = $this->selectGiangVien($giangViensChucVu, $vaiTroGiangVien);
            $vaiTroGiangVien[$chuTich->MA_GV]['so_lan']++;
    
            // Cập nhật danh sách giảng viên cho Phó Chủ tịch (không thể là Chủ tịch hoặc Giảng viên Hướng dẫn)
            $giangViensChucVu = $giangViensChucVu->filter(function ($gv) use ($chuTich) {
                return $gv->MA_GV != $chuTich->MA_GV;
            });
    
            // Chọn Phó Chủ tịch
            $phoChuTich = $this->selectGiangVien($giangViensChucVu, $vaiTroGiangVien);
            $vaiTroGiangVien[$phoChuTich->MA_GV]['so_lan']++;
    
            // Giảng viên hướng dẫn làm Thư ký (giữ họ làm thư ký)
            $thuKy = $giangViens->firstWhere('MA_GV', $maGVHuongDan);
            $vaiTroGiangVien[$thuKy->MA_GV]['so_lan']++;
    
            // Lưu thông tin Chủ tịch, Phó Chủ tịch và Thư ký chung cho nhóm này
            foreach ($sinhViensTrongNhom as $sinhVien) {
                // Tạo mã hội đồng mới
                $lastHoiDong = DB::table('hoi_dong_danh_gia')->orderBy('MA_HD', 'desc')->first();
                $newMaHD = $lastHoiDong ? 'HD' . str_pad((int)substr($lastHoiDong->MA_HD, 2) + 1, 3, '0', STR_PAD_LEFT) : 'HD001';
    
                // Tạo hội đồng đánh giá
                DB::table('hoi_dong_danh_gia')->insert([
                    'MA_HD' => $newMaHD,
                    'CHU_TICH_HD' => $chuTich->MA_GV,
                    'PHO_CHU_TICH_HD' => $phoChuTich->MA_GV,
                    'THUKY_HD' => $thuKy->MA_GV,
                    'NGAY_TAO' => now(),
                ]);
    
                // Lưu giảng viên với vai trò vào bảng gom
                DB::table('gom')->insert(['MA_HD' => $newMaHD, 'MA_GV' => $chuTich->MA_GV, 'DUYET_THAM_GIA' => NULL]);
                DB::table('gom')->insert(['MA_HD' => $newMaHD, 'MA_GV' => $phoChuTich->MA_GV, 'DUYET_THAM_GIA' => NULL]);
                DB::table('gom')->insert(['MA_HD' => $newMaHD, 'MA_GV' => $thuKy->MA_GV, 'DUYET_THAM_GIA' => NULL]);
    
                // Lưu thông tin sinh viên vào hội đồng
                DB::table('duoc_danh_gia')->insert(['MA_HD' => $newMaHD, 'MA_DT' => $sinhVien->MA_DT]);
            }
        }
    
        return redirect()->back()->with('thongbao', 'Tạo hội đồng thành công.');
    }
    
    private function selectGiangVien($giangViens, &$vaiTroGiangVien) {
        // Chọn giảng viên có số lần đảm nhận vai trò ít nhất
        return $giangViens->sortBy(function ($gv) use ($vaiTroGiangVien) {
            return $vaiTroGiangVien[$gv->MA_GV]['so_lan'];
        })->first();
    }
    
    

    
    private function randomSelect($array, $exclude = null)
    {
        // Nếu có giảng viên được loại trừ, loại bỏ khỏi danh sách
        if ($exclude) {
            $array = array_diff($array, [$exclude]);
        }
        return $array[array_rand($array)];
    }
    
  
    
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
    
        return view('admin.hoidong', compact('hoiDongs'));
    }

       public function delete_hd($MA_HD)
       {
           try {
         
            DB::table('to_chuc')->where('MA_HD', $MA_HD)->delete();
            DB::table('gom')->where('MA_HD', $MA_HD)->delete();
               DB::table('duoc_danh_gia')->where('MA_HD', $MA_HD)->delete();
            DB::table('hoi_dong_danh_gia')->where('MA_HD', $MA_HD)->delete();
   

               return redirect()->route('hoidong.index')->with('thongbao', 'Xóa hội đồng thành công!');
           } catch (\Exception $e) {
               // Nếu có lỗi, trả về thông báo lỗi
               return redirect()->back()->with('error', 'Lỗi khi xóa hội đồng: ' . $e->getMessage());
           }
       }
   
       
    //    public function edit_hd($MA_HD)
    //    {

    //        $hoi_dong = DB::table('hoi_dong_danh_gia')->where('MA_HD', $MA_HD)->first();
       
    //        // Lấy thông tin Thư Ký (không thể chỉnh sửa)
    //        $thu_ky = DB::table('giang_vien')->where('MA_GV', $hoi_dong->THUKY_HD)->first();
       
    //        // Đếm số lần giảng viên đã giữ chức vụ Chủ Tịch và Phó Chủ Tịch
    //        $usageCount = DB::table('hoi_dong_danh_gia')
    //            ->select('CHU_TICH_HD', 'PHO_CHU_TICH_HD', DB::raw('COUNT(*) as count'))
    //            ->groupBy('CHU_TICH_HD', 'PHO_CHU_TICH_HD')
    //            ->get();
       
    //        // Xử lý đếm số lần giảng viên đã làm Chủ Tịch và Phó Chủ Tịch
    //        $usageMap = [];
    //        foreach ($usageCount as $usage) {
    //            $usageMap[$usage->CHU_TICH_HD] = isset($usageMap[$usage->CHU_TICH_HD]) ? $usageMap[$usage->CHU_TICH_HD] + 1 : 1;
    //            $usageMap[$usage->PHO_CHU_TICH_HD] = isset($usageMap[$usage->PHO_CHU_TICH_HD]) ? $usageMap[$usage->PHO_CHU_TICH_HD] + 1 : 1;
    //        }
       
    //        // Tìm số lần ít nhất mà một giảng viên giữ chức vụ
    //        $minUsage = min($usageMap);
       
    //        // Lọc ra những giảng viên đã giữ chức vụ nhiều hơn số lần tối thiểu
    //        $excluded_ids = array_keys(array_filter($usageMap, function ($count) use ($minUsage) {
    //            return $count > $minUsage;
    //        }));
       
    //        // Lọc ra giảng viên đang làm thư ký
    //        $excluded_ids[] = $hoi_dong->THUKY_HD; // Giảng viên thư ký không được làm Chủ tịch hoặc Phó Chủ tịch
       
    //        // Lấy danh sách giảng viên không thuộc excluded_ids (chưa giữ chức vụ nhiều hơn hoặc là thư ký)
    //        $giang_viens = DB::table('giang_vien')
    //            ->whereNotIn('MA_GV', $excluded_ids)
    //            ->get();
       
    //        // Lấy danh sách sinh viên bảo vệ thuộc hội đồng này
    //        $sinh_vien_bv = DB::table('duoc_danh_gia')
    //            ->join('sinh_vien', 'duoc_danh_gia.MA_DT', '=', 'sinh_vien.MA_DT')
    //            ->join('de_tai', 'sinh_vien.MA_DT', '=', 'de_tai.MA_DT')
    //            ->select('sinh_vien.MA_SV', 'sinh_vien.HOTEN_SV', 'de_tai.TEN_DT')
    //            ->where('duoc_danh_gia.MA_HD', $MA_HD)
    //            ->get();
       
    //        return view('admin.edit_hoidong', compact('hoi_dong', 'giang_viens', 'sinh_vien_bv', 'thu_ky'));
    //    }
       


    public function edit_hd($MA_HD)
    {
        $lichBaoVe = DB::table('chitiet_lichbv')
        ->join('buoi_bao_ve', 'chitiet_lichbv.MA_BV', '=', 'buoi_bao_ve.MA_BV')
        ->join('to_chuc', 'buoi_bao_ve.MA_BV', '=', 'to_chuc.MA_BV') // Kết nối bảng to_chuc
        ->where('to_chuc.MA_HD', $MA_HD) // Sử dụng MA_HD từ bảng to_chuc
        ->select('buoi_bao_ve.NGAY_BV', 'chitiet_lichbv.GIO_BAT_DAU')
        ->first();
    
    
        if (!$lichBaoVe) {
            return redirect()->back()->withErrors([
                'message' => 'Không tìm thấy thông tin lịch bảo vệ cho hội đồng này.'
            ]);
        }
    
        $hoi_dong = DB::table('hoi_dong_danh_gia')->where('MA_HD', $MA_HD)->first();
        $giang_viens = DB::table('giang_vien')->get();
        
        // Lấy thông tin buổi bảo vệ đầu tiên (nếu có)
        // $buoi_bao_ve = DB::table('buoi_bao_ve')->where('MA_HD', $MA_HD)->first();
        // $chitiet_lich = DB::table('chitiet_lichbv')->get();
        // Lấy thông tin Thư Ký (không thể chỉnh sửa)
        $thu_ky = DB::table('giang_vien')->where('MA_GV', $hoi_dong->THUKY_HD)->first();
       
        // Lấy danh sách sinh viên bảo vệ thuộc hội đồng này
        $sinh_vien_bv = DB::table('duoc_danh_gia')
            ->join('sinh_vien', 'duoc_danh_gia.MA_DT', '=', 'sinh_vien.MA_DT')
            ->join('de_tai', 'sinh_vien.MA_DT', '=', 'de_tai.MA_DT')
            ->select('sinh_vien.MA_SV', 'sinh_vien.HOTEN_SV', 'de_tai.TEN_DT')
            ->where('duoc_danh_gia.MA_HD', $MA_HD)
            ->get();
           
        return view('admin.edit_hoidong', compact('hoi_dong', 'thu_ky', 'giang_viens', 'sinh_vien_bv','lichBaoVe'));
    }
    public function update_hd(Request $request, $MA_HD)
    {
   
        $request->validate([
            'CHU_TICH_HD' => 'required',
            'PHO_CHU_TICH_HD' => 'required',
            'NGAY_TAO' => 'required|date',
        ]);
    
       
        $lichBaoVe = DB::table('chitiet_lichbv')
            ->join('buoi_bao_ve', 'chitiet_lichbv.MA_BV', '=', 'buoi_bao_ve.MA_BV')
            ->join('to_chuc', 'buoi_bao_ve.MA_BV', '=', 'to_chuc.MA_BV')
            ->where('to_chuc.MA_HD', $MA_HD)
            ->select('buoi_bao_ve.NGAY_BV', 'chitiet_lichbv.GIO_BAT_DAU')
            ->first();
    
        if (!$lichBaoVe) {
            return redirect()->back()->withErrors([
                'message' => 'Không tìm thấy thông tin lịch bảo vệ cho hội đồng này.'
            ]);
        }
    
        
        $conflicts = DB::table('buoi_bao_ve')
            ->join('to_chuc', 'buoi_bao_ve.MA_BV', '=', 'to_chuc.MA_BV')
            ->join('hoi_dong_danh_gia', 'to_chuc.MA_HD', '=', 'hoi_dong_danh_gia.MA_HD')
            ->join('chitiet_lichbv', 'buoi_bao_ve.MA_BV', '=', 'chitiet_lichbv.MA_BV')
            ->where('buoi_bao_ve.NGAY_BV', $lichBaoVe->NGAY_BV)
            ->where(function ($query) use ($request) {
                $query->where('hoi_dong_danh_gia.CHU_TICH_HD', $request->CHU_TICH_HD)
                    ->orWhere('hoi_dong_danh_gia.PHO_CHU_TICH_HD', $request->PHO_CHU_TICH_HD);
            })
            ->where(function ($query) use ($lichBaoVe) {
                // Check for time conflict in the schedule
                $query->where('chitiet_lichbv.GIO_BAT_DAU', $lichBaoVe->GIO_BAT_DAU);
            })
            ->where('to_chuc.MA_HD', '!=', $MA_HD) 
            ->exists();
    
        if ($conflicts) {
            return redirect()->back()->withErrors([
                'message' => 'Lịch của giảng viên trùng vào ngày ' . $lichBaoVe->NGAY_BV . ' lúc ' . $lichBaoVe->GIO_BAT_DAU
            ]);
        }
    
        
        DB::table('hoi_dong_danh_gia')->where('MA_HD', $MA_HD)->update([
            'CHU_TICH_HD' => $request->CHU_TICH_HD,
            'PHO_CHU_TICH_HD' => $request->PHO_CHU_TICH_HD,
            'NGAY_TAO' => $request->NGAY_TAO
        ]);
    
        return redirect()->route('hoidong.index')->with('thongbao', 'Cập nhật hội đồng thành công!');
    }
    
    
    
public function getDetail($ma_hd)
{

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
public function inPhieuSinhVien()
{
    // Lấy tất cả hội đồng
    $hoiDongs = DB::table('hoi_dong_danh_gia')
        ->join('to_chuc', 'hoi_dong_danh_gia.MA_HD', '=', 'to_chuc.MA_HD') 
        ->join('buoi_bao_ve', 'to_chuc.MA_BV', '=', 'buoi_bao_ve.MA_BV')
        ->join('phong_hoc', 'buoi_bao_ve.MA_PH', '=', 'phong_hoc.MA_PH') 
        ->leftJoin('giang_vien as chu_tich', 'hoi_dong_danh_gia.CHU_TICH_HD', '=', 'chu_tich.MA_GV') 
        ->leftJoin('giang_vien as pho_chu_tich', 'hoi_dong_danh_gia.PHO_CHU_TICH_HD', '=', 'pho_chu_tich.MA_GV') 
        ->leftJoin('giang_vien as thu_ky', 'hoi_dong_danh_gia.THUKY_HD', '=', 'thu_ky.MA_GV') 
        ->select(
            'hoi_dong_danh_gia.*', 
            'buoi_bao_ve.MA_PH', 
            'buoi_bao_ve.NGAY_BV', 
            'phong_hoc.TEN_PH', 
            'chu_tich.HOTEN_GV as CHU_TICH_TEN', 
            'pho_chu_tich.HOTEN_GV as PHO_CHU_TICH_TEN', 
            'thu_ky.HOTEN_GV as THUKY_TEN' 
        )
        ->get();  

    // Lưu trữ tất cả phiếu đánh giá
    $allPhieu = [];

    // Lấy danh sách sinh viên cho từng hội đồng
    foreach ($hoiDongs as $hoiDong) {
        $sinhViens = DB::table('duoc_danh_gia')
            ->join('sinh_vien', 'duoc_danh_gia.MA_DT', '=', 'sinh_vien.MA_DT')
            ->join('de_tai', 'sinh_vien.MA_DT', '=', 'de_tai.MA_DT')
            ->join('chitiet_lichbv', 'sinh_vien.MA_SV', '=', 'chitiet_lichbv.MA_SV')
            ->join('giang_vien as gvhd', 'sinh_vien.MA_GV', '=', 'gvhd.MA_GV')
            ->select(
                'sinh_vien.MA_SV',
                'sinh_vien.HOTEN_SV',
                'gvhd.HOTEN_GV as HOTEN_GV',
                'de_tai.TEN_DT',
                'chitiet_lichbv.GIO_BAT_DAU',
                'chitiet_lichbv.GIO_KET_THUC'
            )
            ->where('duoc_danh_gia.MA_HD', $hoiDong->MA_HD)
            ->get();

        // Thêm danh sách sinh viên vào mỗi hội đồng
        $hoiDong->sinhViens = $sinhViens;

        // Tạo phiếu cho từng sinh viên
        foreach ($sinhViens as $sinhVien) {
            // Tạo phiếu cho Chủ tịch
            $allPhieu[] = [
                'sinhVien' => $sinhVien,
                'nguoiCham' => $hoiDong->CHU_TICH_TEN,
                'chucVu' => 'Chủ tịch',
            ];
            // Tạo phiếu cho Phó Chủ tịch
            $allPhieu[] = [
                'sinhVien' => $sinhVien,
                'nguoiCham' => $hoiDong->PHO_CHU_TICH_TEN,
                'chucVu' => 'Phó Chủ tịch',
            ];
            // Tạo phiếu cho Thư ký
            $allPhieu[] = [
                'sinhVien' => $sinhVien,
                'nguoiCham' => $hoiDong->THUKY_TEN,
                'chucVu' => 'Thư ký',
            ];
        }
    }

    // Truyền tất cả phiếu vào view
    $data = ['allPhieu' => $allPhieu];

    // Tạo PDF từ view cho tất cả phiếu
    $pdf = Pdf::loadView('admin.in_phieu', $data);

    // Tải xuống PDF
    return $pdf->download("phieu_danh_gia_all.pdf");
}
public function showHoiDongDanhGia()  
{  
    // Lấy dữ liệu của hội đồng đánh giá  
    $hoiDongs = DB::table('hoi_dong_danh_gia')  
    ->join('to_chuc', 'hoi_dong_danh_gia.MA_HD', '=', 'to_chuc.MA_HD') 
    ->join('buoi_bao_ve', 'to_chuc.MA_BV', '=', 'buoi_bao_ve.MA_BV')
    ->join('phong_hoc', 'buoi_bao_ve.MA_PH', '=', 'phong_hoc.MA_PH') 
    ->leftJoin('giang_vien as chu_tich', 'hoi_dong_danh_gia.CHU_TICH_HD', '=', 'chu_tich.MA_GV') 
    ->leftJoin('giang_vien as pho_chu_tich', 'hoi_dong_danh_gia.PHO_CHU_TICH_HD', '=', 'pho_chu_tich.MA_GV') 
    ->leftJoin('giang_vien as thu_ky', 'hoi_dong_danh_gia.THUKY_HD', '=', 'thu_ky.MA_GV') 
  
    ->select(
        'hoi_dong_danh_gia.*', 
        'buoi_bao_ve.MA_PH', 
        'buoi_bao_ve.NGAY_BV', 
        'phong_hoc.TEN_PH', 
        'chu_tich.HOTEN_GV as CHU_TICH_TEN', 
        'pho_chu_tich.HOTEN_GV as PHO_CHU_TICH_TEN', 
        'thu_ky.HOTEN_GV as THUKY_TEN' 
    )
        ->get();  

    // Lấy thông tin sinh viên cho từng hội đồng  
    foreach ($hoiDongs as $hoiDong) {  
        $sinhViens = DB::table('duoc_danh_gia')  
            ->join('sinh_vien', 'duoc_danh_gia.MA_DT', '=', 'sinh_vien.MA_DT')  
            ->join('de_tai', 'sinh_vien.MA_DT', '=', 'de_tai.MA_DT')  
            ->join('chitiet_lichbv', 'sinh_vien.MA_SV', '=', 'chitiet_lichbv.MA_SV')  
            ->join('giang_vien as gvhd', 'sinh_vien.MA_GV', '=', 'gvhd.MA_GV')  
            ->select(  
                'sinh_vien.MA_SV',  
                'sinh_vien.HOTEN_SV',  
                'gvhd.HOTEN_GV as HOTEN_GV', // Tên giảng viên hướng dẫn  
                'de_tai.TEN_DT',  
                'chitiet_lichbv.GIO_BAT_DAU'
            )  
            ->where('duoc_danh_gia.MA_HD', $hoiDong->MA_HD)  
            ->get();  

        $hoiDong->sinhViens = $sinhViens;  
    }  

    return view('admin.hoidong_danhgia', compact('hoiDongs'));  
}

public function inBienBanSinhVien()
{
    // Lấy tất cả hội đồng
    $hoiDongs = DB::table('hoi_dong_danh_gia')
        ->join('to_chuc', 'hoi_dong_danh_gia.MA_HD', '=', 'to_chuc.MA_HD') 
        ->join('buoi_bao_ve', 'to_chuc.MA_BV', '=', 'buoi_bao_ve.MA_BV')
        ->join('phong_hoc', 'buoi_bao_ve.MA_PH', '=', 'phong_hoc.MA_PH') 
        ->leftJoin('giang_vien as chu_tich', 'hoi_dong_danh_gia.CHU_TICH_HD', '=', 'chu_tich.MA_GV') 
        ->leftJoin('giang_vien as pho_chu_tich', 'hoi_dong_danh_gia.PHO_CHU_TICH_HD', '=', 'pho_chu_tich.MA_GV') 
        ->leftJoin('giang_vien as thu_ky', 'hoi_dong_danh_gia.THUKY_HD', '=', 'thu_ky.MA_GV') 
        ->select(
            'hoi_dong_danh_gia.*', 
            'buoi_bao_ve.MA_PH', 
            'buoi_bao_ve.NGAY_BV', 
            'phong_hoc.TEN_PH', 
            'chu_tich.HOTEN_GV as CHU_TICH_TEN', 
            'pho_chu_tich.HOTEN_GV as PHO_CHU_TICH_TEN', 
            'thu_ky.HOTEN_GV as THUKY_TEN' 
        )
        ->get();  

    // Lấy danh sách sinh viên cho từng hội đồng
    foreach ($hoiDongs as $hoiDong) {
        $sinhViens = DB::table('duoc_danh_gia')
            ->join('sinh_vien', 'duoc_danh_gia.MA_DT', '=', 'sinh_vien.MA_DT')
            ->join('de_tai', 'sinh_vien.MA_DT', '=', 'de_tai.MA_DT')
            ->join('chitiet_lichbv', 'sinh_vien.MA_SV', '=', 'chitiet_lichbv.MA_SV')
            ->join('giang_vien as gvhd', 'sinh_vien.MA_GV', '=', 'gvhd.MA_GV')
            ->select(
                'sinh_vien.MA_SV',
                'sinh_vien.HOTEN_SV',
                'sinh_vien.LOP_SV',
                'gvhd.HOTEN_GV as HOTEN_GV',
                'de_tai.TEN_DT',
                'chitiet_lichbv.GIO_BAT_DAU',
                'chitiet_lichbv.GIO_KET_THUC'
            )
            ->where('duoc_danh_gia.MA_HD', $hoiDong->MA_HD)
            ->get();

        // Thêm danh sách sinh viên vào mỗi hội đồng
        $hoiDong->sinhViens = $sinhViens;
    }

    // Truyền tất cả hội đồng và sinh viên vào view
    $data = ['hoiDongs' => $hoiDongs];

    // Tạo PDF từ view cho tất cả hội đồng và sinh viên
    $pdf = Pdf::loadView('admin.bien_ban', $data);

    // Tải xuống PDF
    return $pdf->download("bien_ban_danh_gia.pdf");
}
public function deleteAll()
{
    try {
        // Xóa tất cả dữ liệu liên quan
        DB::table('to_chuc')->delete();
        DB::table('duoc_danh_gia')->delete();
        DB::table('gom')->delete();
        DB::table('hoi_dong_danh_gia')->delete();
   
        return redirect()->back()->with('success', 'Đã xóa tất cả hội đồng thành công!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa hội đồng: ' . $e->getMessage());
    }
}

public function addhoidong()
{
    $giangViens = DB::table('giang_vien')->get();

    // Lọc sinh viên chưa được phân hội đồng
    $sinhViens = DB::table('sinh_vien')
        ->join('de_tai', 'sinh_vien.MA_DT', '=', 'de_tai.MA_DT')
        ->leftJoin('duoc_danh_gia', 'sinh_vien.MA_DT', '=', 'duoc_danh_gia.MA_DT')
        ->whereNull('duoc_danh_gia.MA_DT') // Chỉ lấy sinh viên chưa được phân hội đồng
        ->select('sinh_vien.MA_DT', 'sinh_vien.HOTEN_SV', 'de_tai.TEN_DT')
        ->get();

    return view('admin.add_hoidong', compact('giangViens', 'sinhViens'));
}
public function savehoidong(Request $request)
{
    $request->validate([
        'CHU_TICH_HD' => 'required',
        'PHO_CHU_TICH_HD' => 'required',
        'THUKY_HD' => 'required',
        'sinhViens' => 'required|array',
    ]);

    // Tạo mã hội đồng mới
    $lastHoiDong = DB::table('hoi_dong_danh_gia')->orderBy('MA_HD', 'desc')->first();
    $newMaHD = $lastHoiDong ? 'HD' . str_pad((int)substr($lastHoiDong->MA_HD, 2) + 1, 3, '0', STR_PAD_LEFT) : 'HD001';

    // Lưu hội đồng mới
    DB::table('hoi_dong_danh_gia')->insert([
        'MA_HD' => $newMaHD,
        'CHU_TICH_HD' => $request->CHU_TICH_HD,
        'PHO_CHU_TICH_HD' => $request->PHO_CHU_TICH_HD,
        'THUKY_HD' => $request->THUKY_HD,
        'NGAY_TAO' => now(),
    ]);

    // Lưu giảng viên với vai trò vào bảng gom cho hội đồng mới
    $chuTich = $request->CHU_TICH_HD;
    $phoChuTich = $request->PHO_CHU_TICH_HD;
    $thuKy = $request->THUKY_HD;

    DB::table('gom')->insert([
        'MA_HD' => $newMaHD,
        'MA_GV' => $chuTich,
        'DUYET_THAM_GIA' => NULL, // Mặc định là NULL (chưa duyệt)
    ]);

    DB::table('gom')->insert([
        'MA_HD' => $newMaHD,
        'MA_GV' => $phoChuTich,
        'DUYET_THAM_GIA' => NULL, // Mặc định là NULL (chưa duyệt)
    ]);

    DB::table('gom')->insert([
        'MA_HD' => $newMaHD,
        'MA_GV' => $thuKy,
        'DUYET_THAM_GIA' => NULL, // Mặc định là NULL (chưa duyệt)
    ]);

    // Lưu thông tin sinh viên được đánh giá vào bảng `duoc_danh_gia`
    foreach ($request->sinhViens as $maDt) {
        DB::table('duoc_danh_gia')->insert([
            'MA_HD' => $newMaHD,
            'MA_DT' => $maDt,
        ]);
    }


    // Gửi thông báo tạo thành công
    return redirect()->back()->with('thongbao', 'Tạo hội đồng thành công và đã gửi thông báo đến giảng viên!');
}

public function inPhieuDanhGia()
{
    // Lấy dữ liệu hội đồng và sinh viên liên quan bằng cách sử dụng eager loading
    $hoiDongs = DB::table('hoi_dong_danh_gia')
        ->join('to_chuc', 'hoi_dong_danh_gia.MA_HD', '=', 'to_chuc.MA_HD')
        ->join('buoi_bao_ve', 'to_chuc.MA_BV', '=', 'buoi_bao_ve.MA_BV')
        ->join('phong_hoc', 'buoi_bao_ve.MA_PH', '=', 'phong_hoc.MA_PH')
        ->leftJoin('giang_vien as chu_tich', 'hoi_dong_danh_gia.CHU_TICH_HD', '=', 'chu_tich.MA_GV')
        ->leftJoin('giang_vien as pho_chu_tich', 'hoi_dong_danh_gia.PHO_CHU_TICH_HD', '=', 'pho_chu_tich.MA_GV')
        ->leftJoin('giang_vien as thu_ky', 'hoi_dong_danh_gia.THUKY_HD', '=', 'thu_ky.MA_GV')
        ->select(
            'hoi_dong_danh_gia.*',
            'buoi_bao_ve.MA_PH',
            'buoi_bao_ve.NGAY_BV',
            'phong_hoc.TEN_PH',
            'chu_tich.HOTEN_GV as CHU_TICH_TEN',
            'pho_chu_tich.HOTEN_GV as PHO_CHU_TICH_TEN',
            'thu_ky.HOTEN_GV as THUKY_TEN'
        )
        ->get();

    // Lấy sinh viên của mỗi hội đồng trong cùng một truy vấn
    foreach ($hoiDongs as $hoiDong) {
        $sinhViens = DB::table('duoc_danh_gia')
            ->join('sinh_vien', 'duoc_danh_gia.MA_DT', '=', 'sinh_vien.MA_DT')
            ->join('de_tai', 'sinh_vien.MA_DT', '=', 'de_tai.MA_DT')
            ->join('chitiet_lichbv', 'sinh_vien.MA_SV', '=', 'chitiet_lichbv.MA_SV')
            ->join('giang_vien as gvhd', 'sinh_vien.MA_GV', '=', 'gvhd.MA_GV')
            ->select(
                'sinh_vien.MA_SV',
                'sinh_vien.HOTEN_SV',
                'gvhd.HOTEN_GV as HOTEN_GV',
                'de_tai.TEN_DT',
                'chitiet_lichbv.GIO_BAT_DAU'
            )
            ->where('duoc_danh_gia.MA_HD', $hoiDong->MA_HD)
            ->get();

        $hoiDong->sinhViens = $sinhViens;
    }

    // Tạo PDF từ view cho tất cả phiếu
    $pdf = PDF::loadView('admin.hoidongdg', ['hoiDongs' => $hoiDongs]);

    // Tải xuống PDF
    return $pdf->download("hoidong_danhgia.pdf");
}



}