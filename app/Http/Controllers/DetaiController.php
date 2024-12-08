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
        $lastCode = DB::table('de_tai')->orderBy('MA_DT', 'desc')->value('MA_DT');
        if ($lastCode) {
            $number = (int) filter_var($lastCode, FILTER_SANITIZE_NUMBER_INT);
            $newCode = 'DT' . str_pad($number + 1, 2, '0', STR_PAD_LEFT); 
        } else {
            $newCode = 'DT01';
        }
        return view('admin.detai.adddetai', ['newCode' => $newCode]);
    }
    
    
    public function list_dt(){
        $de_tai = DB::table('de_tai')->get();
        return view('admin.detai.list_detai', compact('de_tai'));
    }
    public function save_dt(Request $request){
        $data = array();
        $data['MA_DT'] = $request->ma_dt; 
        $data['TEN_DT'] = $request->ten_dt;
        // $data['NGAYNOP'] = $request->ngaynop;
        DB::table('de_tai')->insert($data);
    return redirect()->route('detai.list_dt')->with('thongbao', 'Thêm đề tài thành công');
    }


        public function delete_dt($MA_DT) {
            DB::table('de_tai')->where('MA_DT',$MA_DT)->delete();
            return redirect()->route('detai.list_dt')->with('thongbao','Xóa đề tài thành công');
        }
          

     public function update_dt(Request $request, $MA_DT){
        $de_tai = DB::table('de_tai')->where('MA_DT',$MA_DT)->first();
        if (!$de_tai) {
            return redirect()->back()->with('error', 'Đề tài không tồn tại');
        }
        $data = [
            'TEN_DT' => $request->ten_dt,
            'NGAYNOP' => $request->ngaynop,
        ];  
        DB::table('de_tai')->where('MA_DT', $MA_DT)->update($data);  
    
        return redirect()->route('detai.list_dt')->with('thongbao', 'Cập nhật thành công');  
    }

    public function edit_dt($MA_DT) {
        $de_tai = DB::table('de_tai')->where('MA_DT',$MA_DT)->first();
        return view('admin.detai.edit_detai', compact('de_tai'));
    }

}   