@extends('admin-layout')

@section('admin_content')
<div class="container py-5">
        <h2 class="text-center mb-4">Thêm Hội Đồng</h2>

        <form action="{{ route('hoidong.save_hoidong')}}" method="POST" class="form-style">
                {{ csrf_field() }}

                <div class="row">
                        <!-- Mã Hội Đồng -->


                        <!-- Ngày Tạo -->

                </div>

                <div class="row">
                        <!-- Chủ Tịch Hội Đồng -->
                        <div class="col-md-6 mb-3">
                                <label for="CHU_TICH_HD">Chủ Tịch Hội Đồng</label>
                                <select class="form-control select2" id="CHU_TICH_HD" name="CHU_TICH_HD">
                                        <option value="">-- Chọn giảng viên --</option>
                                        @foreach ($giangViens as $giangVien)
                                        <option value="{{ $giangVien->MA_GV }}">{{ $giangVien->HOTEN_GV }}</option>
                                        @endforeach
                                </select>
                        </div>

                        <!-- Phó Chủ Tịch Hội Đồng -->
                        <div class="col-md-6 mb-3">
                                <label for="PHO_CHU_TICH_HD">Phó Chủ Tịch Hội Đồng</label>
                                <select class="form-control select2" id="PHO_CHU_TICH_HD" name="PHO_CHU_TICH_HD">
                                        <option value="">-- Chọn giảng viên --</option>
                                        @foreach ($giangViens as $giangVien)
                                        <option value="{{ $giangVien->MA_GV }}">{{ $giangVien->HOTEN_GV }}</option>
                                        @endforeach
                                </select>
                        </div>
                </div>
                <div class="row">
                        <!-- Chủ Tịch Hội Đồng -->
                        <div class="col-md-6 mb-3">
                                <label for="THUKY_HD">Thư Ký Hội Đồng:</label>
                                <select class="form-control select2" id="THUKY_HD" name="THUKY_HD" required>
                                        <option value="">-- Chọn giảng viên --</option>
                                        @foreach ($giangViens as $giangVien)
                                        <option value="{{ $giangVien->MA_GV }}">{{ $giangVien->HOTEN_GV }}</option>
                                        @endforeach
                                </select>
                        </div>

                </div>


                <!-- Danh sách Sinh Viên Bảo Vệ -->
                <div class="row">
                        <div class="col-md-12 mb-4">
                                <label for="sinhVien">Danh sách sinh viên:</label>
                                <select class="form-control" id="sinhVien" name="sinhViens[]" multiple required>
                                        @foreach ($sinhViens as $sinhVien)
                                        <option value="{{ $sinhVien->MA_DT }}">{{ $sinhVien->HOTEN_SV }} -
                                                {{ $sinhVien->TEN_DT }}</option>
                                        @endforeach
                                </select>
                                <small class="form-text text-muted">Giữ Ctrl/Cmd để chọn nhiều sinh viên.</small>
                        </div>
                </div>

                <div class="text-center mt-4">
                        <button type="submit" class="btn btn-custom-primary">Cập Nhật</button>
                        <a href="{{ route('hoidong.index') }}" class="btn btn-custom-secondary">Hủy</a>
                </div>

        </form>
</div>

<script>
$.noConflict();
jQuery(document).ready(function($) {
        $('.select2').select2();
});
</script>
<style>
/* Style cho form */
.form-style {
        background-color: #f9f9f9;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: auto;
}

/* Style cho các nhãn */
.form-group label {
        font-weight: bold;
        color: #333;
}

/* Tiêu đề form */
h2.text-center {
        color: #007bff;
        font-weight: 700;
}

/* Nút Cập Nhật */
.btn-custom-primary {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
        font-size: 1.1rem;
        padding: 10px 30px;
        border-radius: 5px;
        transition: background-color 0.3s, transform 0.2s;
        margin-right: 10px;
}

.btn-custom-primary:hover {
        background-color: #0056b3;
        transform: scale(1.05);
}

/* Nút Hủy */
.btn-custom-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
        font-size: 1.1rem;
        padding: 10px 30px;
        border-radius: 5px;
        transition: background-color 0.3s, transform 0.2s;
}

.btn-custom-secondary:hover {
        background-color: #5a6268;
        transform: scale(1.05);
}
</style>
@endsection