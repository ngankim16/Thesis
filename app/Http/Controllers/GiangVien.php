<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Redirect; 

use Illuminate\Support\Facades\Mail; 
use App\Mail\LichBaoVeMail;
use Session;
class GiangVien extends Controller
{
    
    public function add_gv(){
        return view('admin.giangvien.addgiangvien');
    }
    public function list_gv(){
        $giang_vien = DB:: table('giang_vien')->get();


        return view('admin.giangvien.list_giangvien',compact('giang_vien'));
    }
    public function save_gv(Request $request)
    {
        $data = array();
        $data['MA_GV'] = $request -> ma_gv;
        $data['AVT_GV'] = $request ->avt_gv;
        $data['HOTEN_GV'] = $request -> hoten_gv;
        $data['EMAIL_GV'] = $request -> email_gv;
        $data['SDT_GV'] = $request ->sdt_gv;
        $data['NGAYSINH_GV'] =$request->ngaysinh_gv;  
        
        DB::table('giang_vien')->insert($data);
    
        // Đặt thông báo thành công và chuyển hướng người dùng
        Session::put('message', 'Thêm sinh viên thành công!');
        return redirect()->route('giangvien.list_gv')->with('thongbao','Thêm sinh viên thành công');
 // Điều này sẽ sử dụng GET để truy cập vào /list_student

    }
    public function delete_gv($MA_GV) {
        DB::table('giang_vien')->where('MA_GV',$MA_GV)->delete();
        return redirect()->route('giangvien.list_gv')->with('thongbao','Xóa sinh viên thành công');
    }

    public function edit_gv($MA_GV) {
        $giang_vien = DB::table('giang_vien')->where('MA_GV',$MA_GV)->first();
        return view('admin.giangvien.edit_giangvien', compact('giang_vien'));
    }

    public function update_gv(Request $request, $MA_GV){
        $giang_vien = DB::table('giang_vien')->where('MA_GV',$MA_GV)->first();

        if(!$giang_vien){
            return redirect()->back()->with('error','Giảng viên không tồn tại');
        }
        $data = [
            'HOTEN_GV'=> $request->hoten_gv,
            'EMAIL_GV' => $request->email_gv,
            'NGAYSINH_GV' => $request->ngaysinh_gv,
            'SDT_GV' => $request -> sdt_gv,
            'AVT_GV'=> $request -> avt_gv
        ];
        DB::table('giang_vien')->where('MA_GV', $MA_GV)->update($data);  

        return redirect()->route('giangvien.list_gv')->with('error','Cập nhật giảng viên thành công');
    } 

    
    public function duyet_hoidong() {
        // Lấy mã giảng viên từ phiên làm việc
        $maGv = session('MA_GV');
        
        // Lấy danh sách hội đồng mà giảng viên tham gia đánh giá
        $hoidongs = DB::table('gom')
            ->join('to_chuc', 'gom.MA_HD', '=', 'to_chuc.MA_HD')
            ->join('buoi_bao_ve', 'to_chuc.MA_BV', '=', 'buoi_bao_ve.MA_BV')
            ->join('duoc_danh_gia', 'gom.MA_HD', '=', 'duoc_danh_gia.MA_HD')
            ->join('sinh_vien', 'duoc_danh_gia.MA_DT', '=', 'sinh_vien.MA_DT')
            ->where('gom.MA_GV', $maGv) // Lọc theo mã giảng viên
            ->select(
                'gom.MA_HD',
                'buoi_bao_ve.NGAY_BV',
                'buoi_bao_ve.MA_BV',
                'gom.DUYET_THAM_GIA'
            )
            ->distinct()
            ->get();
    
        // Lọc hội đồng chưa duyệt
        $hoidongChuaDuyet = $hoidongs->where('DUYET_THAM_GIA', null);
    
        // Thông báo nếu có hội đồng chưa duyệt
        if ($hoidongChuaDuyet->isNotEmpty()) {
            session()->flash('thongbao1', 'Bạn có hội đồng cần duyệt!');
        }
    
        // Lấy thông tin chi tiết sinh viên bảo vệ cho từng hội đồng
        foreach ($hoidongs as $hoiDong) {
            $sinhViens = DB::table('duoc_danh_gia')
                ->join('sinh_vien', 'duoc_danh_gia.MA_DT', '=', 'sinh_vien.MA_DT')
                ->join('de_tai', 'sinh_vien.MA_DT', '=', 'de_tai.MA_DT')
                ->join('chitiet_lichbv', 'sinh_vien.MA_SV', '=', 'chitiet_lichbv.MA_SV')
                ->join('buoi_bao_ve', 'chitiet_lichbv.MA_BV', '=', 'buoi_bao_ve.MA_BV')
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
    
        // Trả về view với danh sách hội đồng và sinh viên tham gia
        return view('admin.giangvien.duyet_hoi_dong', compact('hoidongs'));
    }
    
    public function duyetTatCaHoiDong(Request $request)
{
    // Lấy tất cả các hội đồng chưa duyệt từ bảng gom
    $hoidongs = DB::table('gom')->whereNull('DUYET_THAM_GIA')->get();

    // Kiểm tra nếu không có hội đồng nào chưa duyệt
    if ($hoidongs->isEmpty()) {
        return redirect()->back()->with('thongbao', 'Không có hội đồng nào cần duyệt.');
    }

    // Cập nhật trạng thái duyệt cho tất cả hội đồng
    foreach ($hoidongs as $hoidong) {
        DB::table('gom')
            ->where('MA_HD', $hoidong->MA_HD)
            ->update(['DUYET_THAM_GIA' => 1]);
    }

    // Gửi thông báo sau khi duyệt tất cả hội đồng
    return redirect()->back()->with('thongbao', 'Đã duyệt tất cả hội đồng.');
}

   
    public function duyetHoiDong(Request $request, $maHd)
    {
        $duyet = $request->input('duyet'); 
    
        // Cập nhật trạng thái duyệt tham gia của giảng viên
        DB::table('gom')
            ->where('MA_HD', $maHd)
            ->where('MA_GV', session('MA_GV'))
            ->update(['DUYET_THAM_GIA' => $duyet]);
    
        if ($duyet == 1) {
            // Lấy danh sách sinh viên liên quan đến hội đồng
            $sinhViens = DB::table('duoc_danh_gia')
                ->join('sinh_vien', 'duoc_danh_gia.MA_DT', '=', 'sinh_vien.MA_DT')
                ->where('duoc_danh_gia.MA_HD', $maHd)
                ->select('sinh_vien.MA_SV', 'sinh_vien.EMAIL_SV')
                ->get();
    
            // Gửi email cho từng sinh viên
            foreach ($sinhViens as $sinhVien) {
                if ($sinhVien->EMAIL_SV) {
                    $buoiBaoVe = DB::table('to_chuc')
                        ->join('buoi_bao_ve', 'to_chuc.MA_BV', '=', 'buoi_bao_ve.MA_BV')
                        ->where('to_chuc.MA_HD', $maHd)
                        ->select('buoi_bao_ve.MA_BV', 'buoi_bao_ve.GIO_BATDAU_BV', 'buoi_bao_ve.MA_PH', 'buoi_bao_ve.NGAY_BV')
                        ->first();
    
                    if ($buoiBaoVe) {
                        Mail::to($sinhVien->EMAIL_SV)->send(new LichBaoVeMail(
                            $buoiBaoVe->MA_BV,
                            $buoiBaoVe->GIO_BATDAU_BV,
                            $buoiBaoVe->MA_PH,
                            $buoiBaoVe->NGAY_BV
                        ));
                    }
                }
            }
        }
        // Trả về thông báo
        return redirect()->route('giangvien.duyet_hoidong')->with('thongbao', $duyet == 1 ? 'Bạn đã đồng ý tham gia hội đồng và đã gửi thông báo đến sinh viên!' : 'Bạn đã từ chối tham gia hội đồng.');
    }

 }          