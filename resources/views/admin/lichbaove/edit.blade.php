@extends('admin-layout')

@section('admin_content')
<div class="container mt-5">
        <div class="card">
                <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Chỉnh Sửa Buổi Bảo Vệ</h4>
                </div>
                <div class="card-body">
                        <form action="{{ route('lichbaove.update', $buoiBaoVe->MA_BV) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                        <label for="phong_hoc">Phòng Học</label>
                                        <input type="text" class="form-control" id="phong_hoc" name="phong_hoc"
                                                value="{{ $buoiBaoVe->MA_PH }}" required>
                                </div>
                                <div class="form-group">
                                        <label for="gio_bat_dau">Giờ Bắt Đầu</label>
                                        <input type="time" class="form-control" id="gio_bat_dau" name="gio_bat_dau"
                                                value="{{ $buoiBaoVe->GIO_BATDAU_BV }}" required>
                                </div>
                                <div class="form-group">
                                        <label for="ngay_bao_ve">Ngày Bảo Vệ</label>
                                        <input type="date" class="form-control" id="ngay_bao_ve" name="ngay_bao_ve"
                                                value="{{ $buoiBaoVe->NGAY_BV }}" required>
                                </div>
                                <div class="form-group">
                                        <label for="thoi_luong">Thời Lượng (phút)</label>
                                        <input type="number" class="form-control" id="thoi_luong" name="thoi_luong"
                                                value="{{ $buoiBaoVe->THOILUONG_BV }}" required>
                                </div>
                                <button type="submit" class="btn btn-success">Cập Nhật</button>
                                <a href="{{ route('lichbaove.list_lbv') }}" class="btn btn-secondary">Quay Lại</a>
                        </form>
                </div>
        </div>
</div>
@endsection