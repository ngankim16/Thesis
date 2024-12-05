<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student;
use App\Http\Controllers\baoveluanvan;
use App\Http\Controllers\GiangVien;
use App\Http\Controllers\DetaiController;
use App\Http\Controllers\LichBaoVeController;
use App\Http\Controllers\SinhVien;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/dashboard_sinh_vien',[HomeController::class,'show_dashboard_sv']);
//Route hiển thị giao diện đăng nhập
Route::get('/login', [HomeController::class, 'index'])->name('login'); 

// Route xử lý đăng nhập
Route::post('/login_dashboard', [HomeController::class, 'dangnhap']); 

// Route dashboard được bảo vệ
// Route::get('/dashboard', [HomeController::class, 'show_dashboard'])->middleware('auth');
Route::get('/dashboard', [HomeController::class, 'show_dashboard']);

Route::get('/logout',[HomeController::class,'logout']);


Route::group(['prefix'=>'student','as'=>'student.'],function(){
        Route::get('/addstudent', [Student::class, 'add_st'])->name('add_st');  
        Route::post('/save_student', [Student::class, 'save_st'])->name('save_st');  
        Route::get('/list_student', [Student::class, 'list_st'])->name('list_st');  
        Route::put('/update_student/{MA_SV}', [Student::class, 'update_st'])->name('update_st');
        Route::delete('/delete_student/{MA_SV}', [Student::class, 'delete_st'])->name('delete_st');

        Route::get('/edit_student/{MA_SV}', [Student::class, 'edit_st'])->name('edit_st');  
});

Route::group(['prefix'=>'detai','as'=>'detai.'],function(){
        Route::get('/add_detai',[DetaiController::class,'add_dt'])->name('add_dt');
        Route::get('/list_detai', [DetaiController::class, 'list_dt'])->name('list_dt');  
        Route::post('/save_detai', [DetaiController::class, 'save_dt'])->name('save_dt'); 
        Route::get('/edit_detai/{MA_DT}', [DetaiController::class, 'edit_dt'])->name('edit_dt');
        Route::put('/update_detai/{MA_DT}', [DetaiController::class, 'update_dt'])->name('update_dt');
        Route::delete('/delete-detai/{MA_DT}', [DetaiController::class, 'delete_dt'])->name('delete_dt');


});
Route::group(['prefix'=>'giangvien','as'=>'giangvien.'],function(){
        Route::get('/addgiangvien', [GiangVien::class, 'add_gv'])->name('add_gv');
        Route::post('/save_giangvien',[GiangVien::class,'save_gv'])->name('save_gv');//xu lý luu  
        Route::get('/list_giangvien', [GiangVien::class, 'list_gv'])->name('list_gv');     
        Route::delete('/delete_giangvien/{MA_GV}',[GiangVien::class,'delete_gv'])->name('delete_gv');    
        Route::get('/edit_gv/{MA_GV}',[GiangVien::class,'edit_gv'])->name('edit_gv');
        Route::put('/update_gv/{MA_GV}',[GiangVien::class,'update_gv'])->name('update_gv');   
       
    // Đường dẫn đến dashboard của giảng viên  
    Route::get('/dashboard', [GiangVien::class, 'showDashboard'])->name('dashboard');  

    Route::post('/duyet-tat-ca-hoi-dong', [GiangVien::class, 'duyetTatCaHoiDong'])->name('duyetTatCaHoiDong');


//     // Đường dẫn cho giảng viên duyệt hội đồng  
//     Route::post('/duyet-hoidong/{ma_hd}', [GiangVien::class, 'duyetHoiDong'])->name('duyetHoiDong');  
        Route::post('/duyet-hoidong/{maHd}', [GiangVien::class, 'duyetHoiDong'])->name('duyetHoiDong');
        Route::get('/duyet_hd',[GiangVien::class,'duyet_hoidong'])->name('duyet_hoidong');
});  

Route::group(['prefix' => 'hoidong', 'as' => 'hoidong.'], function () {
        Route::get('/tao-hoi-dong', [baoveluanvan::class, 'taoHoiDong'])->name('taoHoiDong');
        Route::get('/hoidong', [baoveluanvan::class, 'index'])->name('index');
        Route::delete('/delete_hoidong/{MA_HD}', [baoveluanvan::class, 'delete_hd'])->name('delete_hd');
        Route::get('/edit_hoidong/{MA_HD}', [baoveluanvan::class, 'edit_hd'])->name('edit_hd');
        Route::put('/update_hoidong/{MA_HD}', [baoveluanvan::class, 'update_hd'])->name('update_hd');
        Route::get('/details/{MA_HD}', [baoveluanvan::class, 'getDetail'])->name('details');
        Route::get('/hoidongshow', [baoveluanvan::class, 'showHoidong'])->name('show');  
        Route::get('/xem-hoi-dong-danh-gia', [baoveluanvan::class, 'showHoiDongDanhGia'])->name('hoidong_danhgia');
       
        Route::get('/hoidong/in-phieu/{ma_hd}', [baoveluanvan::class, 'inPhieuSinhVien'])->name('in_phieu');

        Route::get('/hoidong/bien-ban/{ma_hd}', [baoveluanvan::class, 'inBienBanSinhVien'])->name('bien_ban');
        Route::delete('/hoidong/delete-all', [baoveluanvan::class, 'deleteAll'])->name('delete_all');

        Route::get('/add-hoidong',[baoveluanvan::class,'addhoidong'])->name('add_hoidong');
        Route::post('/save-hoidong', [baoveluanvan::class, 'savehoidong'])->name('save_hoidong');


        Route::get('/hoidong/danh-gia/{ma_hd}',[baoveluanvan::class, 'inPhieuDanhGia'])->name('danh_gia');
    });
    
Route::group(['prefix'=>'lichbaove','as'=>'lichbaove.'],function(){
        Route::get('/list_lichbaove',[LichBaoVeController::class,'list_lbv'])->name('list_lbv');
        Route::get('/add_lichbaove',[LichBaoVeController::class,'add_lbv'])->name('add_lbv');
        Route::post('/save_listbaove',[LichBaoVeController::class,'save_lbv'])->name('save_lbv');
        Route::get('/buoi-bao-ve/{ma_bv}', [LichBaoVeController::class, 'showBuoiBaoVe']);
        Route::get('/detail_bv/{ma_bv}', [LichBaoVeController::class, 'getSinhVienByBuoiBaoVe']);
        Route::post('/check-availability', [LichBaoVeController::class, 'checkAvailability']);

// Route để xóa buổi bảo vệ
        Route::delete('/delete_lich/{ma_bv}', [LichBaoVeController::class, 'destroy'])->name('destroy');


// Route để chỉnh sửa buổi bảo vệ
        Route::get('/lichbaove/edit/{ma_bv}', [LichBaoVeController::class, 'edit'])->name('edit');
        Route::put('/update_lich/{ma_bv}',[LichBaoVeController::class,'update'])->name('update');

        Route::delete('/lichbaove/delete-all', [LichBaoVeController::class, 'deleteAll'])->name('delete_all');
});

Route::group(['prefix'=>'sinhvien','as'=>'sinhvien.'],function(){
        Route::get('/hoidong', [SinhVien::class, 'index'])->name('index');
        Route::get('/details/{MA_HD}', [SinhVien::class, 'getDetail'])->name('details');
        Route::get('/list_lichbaovesv',[SinhVien::class,'list_lbv'])->name('list_lbv');
});