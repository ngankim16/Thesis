@extends('admin-layout')

@section('admin_content')
<div class="container py-5">
        <h2 class="text-center mb-4">Chỉnh Sửa Hội Đồng Đánh Giá</h2>

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                </button>
        </div>
        @endif


        <form action="{{ route('hoidong.update_hd', $hoi_dong->MA_HD) }}" method="POST" class="form-style">
                {{ csrf_field() }}
                @method('PUT')

                <div class="row">
                        <!-- Mã Hội Đồng -->
                        <div class="col-md-6 mb-3">
                                <label for="MA_HD">Mã Hội Đồng</label>
                                <input type="text" class="form-control" id="MA_HD" name="MA_HD"
                                        value="{{ $hoi_dong->MA_HD }}" readonly>
                        </div>

                        <!-- Ngày Tạo -->
                        <div class="col-md-6 mb-3">
                                <label for="NGAY_TAO">Ngày Tạo</label>
                                <input type="date" class="form-control" id="NGAY_TAO" name="NGAY_TAO"
                                        value="{{ $hoi_dong->NGAY_TAO }}">
                        </div>
                </div>

                <div class="row">
                        <!-- Chủ Tịch Hội Đồng -->
                        <div class="col-md-6 mb-3">
                                <label for="CHU_TICH_HD">Chủ Tịch Hội Đồng</label>
                                <select class="form-control select2" id="CHU_TICH_HD" name="CHU_TICH_HD">
                                        @foreach($giang_viens as $gv)
                                        <option value="{{ $gv->MA_GV }}"
                                                {{ $hoi_dong->CHU_TICH_HD == $gv->MA_GV ? 'selected' : '' }}>
                                                {{ $gv->HOTEN_GV }}
                                        </option>
                                        @endforeach
                                </select>
                        </div>

                        <!-- Phó Chủ Tịch Hội Đồng -->
                        <div class="col-md-6 mb-3">
                                <label for="PHO_CHU_TICH_HD">Ủy Viên</label>
                                <select class="form-control select2" id="PHO_CHU_TICH_HD" name="PHO_CHU_TICH_HD">
                                        @foreach($giang_viens as $gv)
                                        <option value="{{ $gv->MA_GV }}"
                                                {{ $hoi_dong->PHO_CHU_TICH_HD == $gv->MA_GV ? 'selected' : '' }}>
                                                {{ $gv->HOTEN_GV }}
                                        </option>
                                        @endforeach
                                </select>
                        </div>
                </div>
                <div class="row">
                        <!-- Chủ Tịch Hội Đồng -->
                        <div class="col-md-6 mb-3">
                                <label for="THUKY_HD">Thư Ký Hội Đồng</label>
                                <input type="text" class="form-control" id="THUKY_HD" name="THUKY_HD"
                                        value="{{ $thu_ky->HOTEN_GV }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                                <label for="GIO_BATDAU_BV">Giờ Bắt Đầu</label>
                                <input type="time" class="form-control" id="GIO_BAT_DAU" name="GIO_BAT_DAU"
                                        value="{{ old('GIO_BAT_DAU', $lichBaoVe->GIO_BAT_DAU) }}" readonly>

                        </div>
                </div>


                <div class="col-md-6 mb-3">
                        <label for="NGAY_BV">Ngày Bảo Vệ</label>
                        <input type="date" class="form-control" id="NGAY_BV" name="NGAY_BV"
                                value="{{ old('NGAY_BV', $lichBaoVe->NGAY_BV) }}" readonly>

                </div>


                <!-- Danh sách Sinh Viên Bảo Vệ -->
                <div class="row">
                        <div class="col-md-12 mb-4">
                                <label for="sinh_vien">Danh Sách Sinh Viên Bảo Vệ</label>
                                <select class="form-control select2" id="sinh_vien" name="sinh_vien[]" multiple>
                                        @foreach($sinh_vien_bv as $sv)
                                        <option value="{{ $sv->MA_SV }}" selected>{{ $sv->HOTEN_SV }} - Đề tài:
                                                {{ $sv->TEN_DT }}</option>
                                        @endforeach
                                </select>
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