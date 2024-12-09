<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Redirect; 
use Session;
use Illuminate\Support\Facades\Log; // Thêm dòng này để import Log
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser;
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
        $data['MA_DT'] = $request-> tendetai;
        $data['MA_GV'] = $request->hoten_gv;
        $data['Hoc_ky'] = $request->hoc_ky;
        $data['Nam_hoc'] = $request->nam_hoc;
        DB::table('sinh_vien')->insert($data);
        return redirect()->route('student.list_st')->with('thongbao','Thêm sinh viên thành công');

    }
    public function add_st(Request $request)
    {
        $de_tai = DB::table('de_tai')->orderby('Ma_DT', 'desc')->get();
        $giang_vien = DB::table('giang_vien')->orderby('Ma_GV', 'desc')->get();
        return view('admin.student.addstudent')->with('de_tai', $de_tai)->with('giang_vien', $giang_vien);
    }
  
    public function list_st(Request $request) {
        $query = DB::table('sinh_vien');
    
        if ($request->has('hoc_ky') && $request->hoc_ky != '') {
            $query->where('HOC_KY', $request->hoc_ky);
        }
    
        if ($request->has('nam_hoc') && $request->nam_hoc != '') {
            $query->where('NAM_HOC', $request->nam_hoc);
        }
    
        $sinh_vien = $query->get();
        $de_tai = DB::table('de_tai')->get(); 
        $giang_vien = DB::table('giang_vien')->get();
    
        return view('admin.student.list_student', compact('sinh_vien', 'de_tai', 'giang_vien'));
    }
    
    
    public function edit_st($MA_SV) {
        $sinh_vien = DB::table('sinh_vien')->where('MA_SV', $MA_SV)->first();
        $de_tai = DB::table('de_tai')->get(); 
        $giang_vien = DB::table('giang_vien')->get(); 
    
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
    
   
//------Hàm import file word và pdf-----///
   
        public function import(Request $request)
        {
            $request->validate([
                'file' => 'required|mimes:pdf,docx,doc',
            ]);
    
            $filePath = $request->file('file')->getRealPath();
            $fileExtension = $request->file('file')->getClientOriginalExtension();
    
            try {
                $cellDataArray = [];
    
                if ($fileExtension === 'pdf') {
                    // Xử lý file PDF
                    $parser = new Parser();
                    $pdf = $parser->parseFile($filePath);
                    $text = $pdf->getText();
                    $lines = explode("\n", $text);
    
                    foreach ($lines as $line) {
                        $cellDataArray[] = array_map('trim', explode(',', $line));
                    }
                } else {
                    // Xử lý file Word
                    $phpWord = IOFactory::load($filePath);
                    $sections = $phpWord->getSections();
    
                    foreach ($sections as $section) {
                        $elements = $section->getElements();
    
                        foreach ($elements as $element) {
                            if ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                                foreach ($element->getRows() as $rowIndex => $row) {
                                    if ($rowIndex === 0) continue;
    
                                    $cellData = [];
                                    foreach ($row->getCells() as $cell) {
                                        $cellText = '';
                                        foreach ($cell->getElements() as $cellElement) {
                                            if (method_exists($cellElement, 'getText')) {
                                                $cellText .= $cellElement->getText() . " ";
                                            }
                                        }
                                        $cellData[] = trim($cellText);
                                    }
                                    $cellDataArray[] = $cellData;
                                }
                            }
                        }
                    }
                }
    
                DB::beginTransaction();
                $importedCount = 0;
                $skippedCount = 0;
                $errorDetails = [];
    
                foreach ($cellDataArray as $cellData) {
                    if (count($cellData) >= 4) {
                        try {
                            $deTai = DB::table('de_tai')
                                ->where('TEN_DT', $cellData[2])
                                ->first();
    
                            if (!$deTai) {
                                $lastCode = DB::table('de_tai')
                                    ->where('MA_DT', 'LIKE', 'DT%')
                                    ->orderBy('MA_DT', 'desc')
                                    ->value('MA_DT');
    
                                $newCode = $lastCode 
                                    ? 'DT' . str_pad((int)filter_var($lastCode, FILTER_SANITIZE_NUMBER_INT) + 1, 2, '0', STR_PAD_LEFT)
                                    : 'DT01';
    
                                DB::table('de_tai')->insert([
                                    'MA_DT' => $newCode,
                                    'TEN_DT' => $cellData[2],
                                ]);
    
                                $maDeTai = $newCode;
                            } else {
                                $maDeTai = $deTai->MA_DT;
                            }
    
                            $tenGV = trim(preg_replace('/\s+/', ' ', $cellData[3]));
                            $gv = DB::table('giang_vien')
                                ->where('hoten_gv', 'LIKE', '%' . $tenGV . '%')
                                ->first();
    
                            if (!$gv) {
                                $skippedCount++;
                                $errorDetails[] = [
                                    'type' => 'Giảng viên không tồn tại',
                                    'data' => $cellData,
                                    'gv' => $tenGV
                                ];
                                Log::warning("Giảng viên không tồn tại: {$tenGV}");
                                continue;
                            }
    
                            $existingSinhVien = DB::table('sinh_vien')
                                ->where('MA_SV', $cellData[0])
                                ->first();
    
                            if ($existingSinhVien) {
                                $skippedCount++;
                                $errorDetails[] = [
                                    'type' => 'Sinh viên đã tồn tại',
                                    'data' => $cellData
                                ];
                                Log::warning("Sinh viên đã tồn tại: {$cellData[0]}");
                                continue;
                            }
    
                            DB::table('sinh_vien')->insert([
                                'MA_SV' => $cellData[0],
                                'HOTEN_SV' => $cellData[1],
                                'MA_GV' => $gv->MA_GV,
                                'MA_DT' => $maDeTai,
                            ]);
    
                            $importedCount++;
    
                        } catch (\Exception $e) {
                            $skippedCount++;
                            $errorDetails[] = [
                                'type' => 'Lỗi xử lý',
                                'data' => $cellData,
                                'error' => $e->getMessage()
                            ];
                            Log::error("Lỗi xử lý cho sinh viên: {$cellData[0]}, Lỗi: {$e->getMessage()}");
                        }
                    }
                }
    
                DB::commit();
    
                if (!empty($errorDetails)) {
                    Log::warning('Các bản ghi bị bỏ qua', $errorDetails);
                }
    
                return redirect()->route('student.list_st')
                    ->with('success', "Nhập dữ liệu thành công! Đã nhập {$importedCount} bản ghi, bỏ qua {$skippedCount} bản ghi.");
    
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Lỗi import: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Có lỗi xảy ra trong quá trình nhập dữ liệu: ' . $e->getMessage());
            }
        }
    
    
    
public function up()
{

    return view('admin.student.upload');
    
}
}