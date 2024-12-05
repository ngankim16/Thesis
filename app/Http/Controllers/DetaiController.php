<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Redirect; 
use App\Models\DeTai;
use Session;


class DetaiController extends Controller
{
    public function add_dt()
    {
        // Lấy mã cuối cùng từ bảng
        $lastCode = DB::table('de_tai')->orderBy('MA_DT', 'desc')->value('MA_DT');
    
        // Tạo mã mới
        if ($lastCode) {
            // Lấy phần số trong mã (bỏ 'DT')
            $number = (int) filter_var($lastCode, FILTER_SANITIZE_NUMBER_INT);
            
            // Tạo mã mới với tiền tố 'DT' và phần số tăng dần
            $newCode = 'DT' . str_pad($number + 1, 2, '0', STR_PAD_LEFT); 
        } else {
            $newCode = 'DT01'; // Mã đầu tiên nếu chưa có mã nào
        }
    
        // Trả mã đề tài sang view
        return view('admin.detai.adddetai', ['newCode' => $newCode]);
    }
    
    
    public function list_dt(){
        // Lấy danh sách sinh viên từ bảng 'sinh_vien'
        $de_tai = DB::table('de_tai')->get();
    
        // Trả về view kèm theo danh sách sinh viên
        return view('admin.detai.list_detai', compact('de_tai'));
        }
        public function save_dt(Request $request)
{
    $data = array();
    $data['MA_DT'] = $request->ma_dt; // Mã đã được tạo sẵn và gửi từ form
    $data['TEN_DT'] = $request->ten_dt;
    $data['NGAYNOP'] = $request->ngaynop;

    // Lưu dữ liệu vào bảng 'de_tai'
    DB::table('de_tai')->insert($data);

    // Đặt thông báo thành công và chuyển hướng người dùng
    return redirect()->route('detai.list_dt')->with('thongbao', 'Thêm đề tài thành công');
}


        public function delete_dt($MA_DT) {
            DB::table('de_tai')->where('MA_DT',$MA_DT)->delete();
            return redirect()->route('detai.list_dt')->with('thongbao','Xóa đề tài thành công');
        }
        
        // public function delete_dt($MA_DT){
        //     $de_tai->delete($request->all());
        //     return redirect()->route('detai.list_dt')->with('thongbao','Xóa đề tài thành công');
        // }    

     public function update_dt(Request $request, $MA_DT){
        $de_tai = DB::table('de_tai')->where('MA_DT',$MA_DT)->first();
        // Kiểm tra nếu không tìm thấy đề tài
        if (!$de_tai) {
            return redirect()->back()->with('error', 'Đề tài không tồn tại');
        }
        $data = [
            'TEN_DT' => $request->ten_dt,
            'NGAYNOP' => $request->ngaynop,
        ];

        // Cập nhật thông tin sinh viên  
        DB::table('de_tai')->where('MA_DT', $MA_DT)->update($data);  
    
        return redirect()->route('detai.list_dt')->with('thongbao', 'Cập nhật thành công');  
    }

    public function edit_dt($MA_DT) {
        $de_tai = DB::table('de_tai')->where('MA_DT',$MA_DT)->first();
        return view('admin.detai.edit_detai', compact('de_tai'));
    }

    
    
        

}   