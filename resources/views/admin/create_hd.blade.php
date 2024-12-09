@extends('admin-layout')
@section('admin_content')
<div class="col-lg-12">

        <form action="{{ route('hoidong.taoHoiDong') }}" method="POST" class="form-style">
                @csrf
                <div class="form-group">
                        <label for="hoc_ky">Học kỳ:</label>
                        <input type="number" id="hoc_ky" name="hoc_ky" class="form-control" required>
                </div>
                <div class="form-group">
                        <label for="nam_hoc">Năm học:</label>
                        <input type="text" id="nam_hoc" name="nam_hoc" class="form-control" required>
                </div>
                <div class="text-center mt-4">
                        <button type="submit" class="btn btn-info" style="width:100px; text-align:center">Tạo</button>
                        <div class="text-center mt-4">
        </form>

</div>

<style>
.form-style {
        background-color: #f9f9f9;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: auto;
}
</style>
@endsection