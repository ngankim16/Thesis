@extends('admin-layout')

@section('admin_content')
<style>
.card {
        max-width: 800px;
        margin: auto;
}
</style>
<div class="container mt-5">
        <div class="card">
                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-center"
                        style="border-top-left-radius: .5rem; border-top-right-radius: .5rem; padding: 1rem; background: #74b9ff;">
                        <h4 class="mb-0 font-weight-bold">Thêm Lịch Bảo Vệ</h4>
                </div>

                <div class="card-body">
                        <form action="{{ route('lichbaove.save_lbv') }}" method="post">
                                @csrf
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                        <ul>
                                                @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                                @endforeach
                                        </ul>
                                </div>
                                @endif

                                <div class="form-group">
                                        <label for="MA_BV">Mã Buổi Bảo Vệ</label>
                                        <input type="text" name="MA_BV" class="form-control" value="{{ $newCode }}"
                                                readonly>
                                </div>


                                <div class="row">
                                        <div class="form-group col-md-6">
                                                <label for="NGAY_BV">Ngày Bảo Vệ</label>
                                                <input type="date" min="<?= date('Y-m-d') ?>" name="NGAY_BV"
                                                        class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                                <label for="GIO_BATDAU_BV">Giờ Bắt Đầu</label>
                                                <input type="time" name="GIO_BATDAU_BV" class="form-control">
                                        </div>
                                </div>



                                <div class="form-group">
                                        <label for="sinh_vien[]">Sinh Viên</label>
                                        <select name="sinh_vien[]" class="form-control" multiple>
                                                @foreach($sinh_vien as $sv)
                                                <option value="{{ $sv->MA_SV }}">{{ $sv->HOTEN_SV }}</option>
                                                @endforeach
                                        </select>
                                        <small class="form-text text-muted">Giữ Ctrl (hoặc Cmd trên Mac) để chọn nhiều
                                                sinh viên.</small>
                                </div>

                                <div class="text-center">
                                        <button type="submit" class="btn btn-primary"
                                                style="padding: 10px 30px;">Lưu</button>
                                </div>
                        </form>
                </div>
        </div>
</div>

<script>
$.noConflict();
jQuery(document).ready(function($) {
        $('.select2').select2();
});
</script>

@endsection