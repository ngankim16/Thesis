@extends('admin-layout')
@section('admin_content')
<div class="col-lg-12">
        <div class="card card-outline">
                <div class="card-header">
                        <div class="card-tools">
                                <a class="btn btn-primary" href="{{route('detai.add_dt')}}">Add</a>
                        </div>
                </div>
                <div class="card-body">
                        @if (Session::has('thongbao'))
                        <div class="alert alert-success" id="thongbao">
                                {{ Session::get('thongbao') }}
                        </div>
                        @endif

                        <script>
                        // Sau 3 giây (3000 milliseconds), thông báo sẽ tự động biến mất
                        setTimeout(function() {
                                $('#thongbao').fadeOut('slow');
                        }, 3000);
                        </script>
                        <div class="table-responsive">
                                <table class="table table-striped table-bordered  table-hover" id="list">

                                        <thead>
                                                <tr>
                                                        <th class="text-center">#</th>
                                                        <th>Mã đề tài</th>
                                                        <th class="text-center">Tên đề tài</th>
                                                        <th>Ngày nộp</th>
                                                        <th>Action</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                @foreach($de_tai as $index => $de_tai)
                                                <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td class="text-center"><b>{{ $de_tai->MA_DT }}</b></td>
                                                        <td class="text-center"><b>{{ $de_tai->TEN_DT }}</td>
                                                        <td class="text-center"><b>{{ $de_tai->NGAYNOP }}</b></td>
                                                        <td class="text-center">
                                                                <div class="btn-group">
                                                                        <a href="{{ route('detai.edit_dt', $de_tai->MA_DT) }}"
                                                                                class="edit">
                                                                                <i class="fas fa-edit"></i>
                                                                        </a>
                                                                        <a href="javascript:void(0)"
                                                                                onclick="if(confirm('Bạn có chắc chắn muốn xóa đề tài này?')) { document.getElementById('delete-form-{{ $de_tai->MA_DT }}').submit(); }"
                                                                                class="delete">
                                                                                <form id="delete-form-{{ $de_tai->MA_DT }}"
                                                                                        action="{{ route('detai.delete_dt', $de_tai->MA_DT) }}"
                                                                                        method="POST"
                                                                                        style="display: none;">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                </form>
                                                                                <i class="fas fa-trash"></i>
                                                                        </a>
                                                                </div>
                                                        </td>
                                                </tr>
                                                @endforeach
                                        </tbody>

                                </table>
                        </div>
                </div>
        </div>
</div>
<style>
table td {
        vertical-align: middle !important;
}
</style>
<script>
$.noConflict();
jQuery(document).ready(function($) {
        $('#list').DataTable();
});
</script>
@endsection