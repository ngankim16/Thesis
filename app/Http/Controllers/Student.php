<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Redirect; 
use Session;
use App\Models\GiangVien;
use App\Models\DeTai;
use App\Models\SinhVien;
session_start();
class Student extends Controller
{
    public function save_st(Request $request)
    {

        $data = array();
        $data['MA_SV'] = $request -> ma_sv;
        $data['HOTEN_SV'] = $request -> hoten_sv;
        $data['LOP_SV'] = $request -> lop_sv;
        $data['EMAIL_SV'] = $request->email_sv;  
        $data['SDT_SV'] = $request->sdt_sv;
        $data['AVT_SV'] = $request ->avt_sv;
        $data['MA_DT'] = $request-> tendetai;
        $data['MA_GV'] = $request->hoten_gv;

        // Lưu dữ liệu vào bảng 'sinh_vien'
        DB::table('sinh_vien')->insert($data);
        // Đặt thông báo thành công và chuyển hướng người dùng
        return redirect()->route('student.list_st')->with('thongbao','Thêm sinh viên thành công');
 // Điều này sẽ sử dụng GET để truy cập vào /list_student

    }
    public function add_st(Request $request)
    {
        // Lấy danh sách sinh vien
        $de_tai = DB::table('de_tai')->orderby('Ma_DT', 'desc')->get();
        $giang_vien = DB::table('giang_vien')->orderby('Ma_GV', 'desc')->get();
        // Truyền biến sinh_vien tới view
        return view('admin.student.addstudent')->with('de_tai', $de_tai)->with('giang_vien', $giang_vien);
    }
  

    public function list_st() {
        // Lấy danh sách sinh viên từ bảng 'sinh_vien'
        $sinh_vien = DB::table('sinh_vien')->get();
        
        // Lấy danh sách đề tài từ bảng 'de_tai'
    $de_tai = DB::table('de_tai')->get(); // Lấy tất cả đề tài
    
    // Lấy danh sách giảng viên từ bảng 'giang_vien'
    $giang_vien = DB::table('giang_vien')->get(); // Lấy tất cả giảng viên
        
        // Trả về view kèm theo danh sách sinh viên
        return view('admin.student.list_student', compact('sinh_vien', 'de_tai', 'giang_vien'));
    }
    
    public function edit_st($MA_SV) {
        $sinh_vien = DB::table('sinh_vien')->where('MA_SV', $MA_SV)->first();
        // Lấy danh sách đề tài và giảng viên
        $de_tai = DB::table('de_tai')->get(); // Lấy tất cả đề tài
        $giang_vien = DB::table('giang_vien')->get(); // Lấy tất cả giảng viên
    
        return view('admin.student.edit_student', compact('sinh_vien', 'de_tai', 'giang_vien')); // Thêm $de_tai và $giang_vien
    }
    
    public function update_st(Request $request, $MA_SV) {  
        $sinh_vien = DB::table('sinh_vien')->where('MA_SV', $MA_SV)->first();  
        if (!$sinh_vien) {  
            return redirect()->back()->with('error', 'Sinh viên không tồn tại');  
        }  
        $data = [
            'HOTEN_SV' => $request->hoten_sv,
            'EMAIL_SV' => $request->email_sv,
            'SDT_SV' => $request->sdt_sv,
            'LOP_SV' => $request->lop_sv,
            'MA_DT' => $request->tendetai,
            'MA_GV' => $request->tengiangvien
        ];

        // Cập nhật thông tin sinh viên  
        DB::table('sinh_vien')->where('MA_SV', $MA_SV)->update($data);  
    
        return redirect()->route('student.list_st')->with('thongbao', 'Cập nhật thành công');  
    }

    public function delete_st($MA_SV) {
        DB::table('sinh_vien')->where('MA_SV',$MA_SV)->delete();
        return redirect()->route('student.list_st')->with('thongbao','Xóa đề tài thành công');
    }
    
    
}

  