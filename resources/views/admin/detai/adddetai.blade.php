@extends('admin-layout')
@section('admin_content')

<div class="col-lg-8 mx-auto">
        <div class="card card-outline">
                <div class="card-body">
                        <h4 class="text-center mb-4">Thêm Đề Tài Mới</h4>
                        <form action="{{ route('detai.save_dt') }}" method="POST" id="manage-staff">
                                {{ csrf_field() }}

                                <div class="form-group">
                                        <label for="ma_dt" class="control-label">Mã đề tài</label>
                                        <input type="text" name="ma_dt" class="form-control" value="{{ $newCode }}"
                                                readonly>
                                </div>

                                <div class="form-group">
                                        <label for="ten_dt" class="control-label">Tên đề tài</label>
                                        <input type="text" name="ten_dt" class="form-control" required>
                                </div>

                                <!-- <div class="form-group">
                                        <label for="ngaynop" class="control-label">Ngày nộp</label>
                                        <input type="date" name="ngaynop" class="form-control" required>
                                </div> -->

                                <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary">Thêm</button>
                                        <a class="btn btn-secondary" href="{{ route('detai.list_dt') }}">Cancel</a>
                                </div>
                        </form>
                </div>
        </div>
</div>
<style>
/* Định dạng cho thẻ card */
.card {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Tiêu đề form */
h4.text-center {
        color: #007bff;
        font-weight: 800;
        margin-bottom: 1rem;
}

/* Label */
.form-group label {
        font-weight: bold;
        color: #333;
}

/* Căn chỉnh form */
.col-lg-8 {
        background-color: #f9f9f9;
        padding: 2rem;
        border-radius: 8px;
}

/* Định dạng nút "Thêm" */
.btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        font-size: 1rem;
        padding: 10px 30px;
        transition: background-color 0.3s, transform 0.2s;
}

.btn-primary:hover {
        background-color: #0056b3;
        transform: scale(1.05);
}

/* Định dạng nút "Cancel" */
.btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        font-size: 1rem;
        padding: 10px 30px;
        transition: background-color 0.3s, transform 0.2s;
}

.btn-secondary:hover {
        background-color: #5a6268;
        transform: scale(1.05);
}

/* Căn chỉnh các nút ở giữa */
.text-center .btn {
        margin: 10px 10px;
}
</style>
@endsection