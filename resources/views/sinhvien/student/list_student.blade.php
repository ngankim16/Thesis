@extends('admin-layout')
@section('admin_content')
<div class="col-lg-12">
        <div class="card card-outline">
                <div class="card-header">
                        <div class="card-tools">
                                <a class="btn btn-primary" href="{{route('giangvien.add_gv')}}">Add</a>
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
                                <table class="table table-hover" id="list">

                                        <thead>
                                                <tr>
                                                        <th class="text-center">#</th>
                                                        <th>Mã sv</th>
                                                        <th>AVT</th>
                                                        <th>Họ tên</th>
                                                        <th class="ten-de-tai">Tên đề tài</th>
                                                        <th>Tên giảng viên </th>
                                                        <th>Email</th>
                                                        <th>Số điện thoại</th>
                                                        <th>Lớp</th>
                                                        <th>Action</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                @foreach($sinh_vien as $index => $sv)


                                                <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td><b>{{ $sv->MA_SV }}</b></td>
                                                        <td>
                                                                <img src="{{ asset('font-end/img/' . $sv->AVT_SV) }}"
                                                                        alt="Hình ảnh sinh viên"
                                                                        style="width: 90px; height: auto;">
                                                        </td>
                                                        <td><b>{{ $sv->HOTEN_SV }}</td>
                                                        <td>
                                                                <b>{{ $de_tai->where('MA_DT', $sv->MA_DT)->first()->TEN_DT ?? 'N/A' }}</b>
                                                        </td>
                                                        <td>
                                                                <b>{{ $giang_vien->firstWhere('MA_GV', $sv->MA_GV)->HOTEN_GV ?? 'N/A' }}</b>

                                                        </td>
                                                        <td><b>{{$sv->EMAIL_SV}}</b></td>
                                                        <td><b>{{ $sv->SDT_SV	 }}</b></td>
                                                        <td><b>{{ $sv->LOP_SV	}}</b></td>
                                                        <td class="text-center">
                                                                <div class="btn-group">
                                                                        <a href="{{route('student.edit_st',$sv->MA_SV)}}"
                                                                                class="edit">
                                                                                <i class="fas fa-edit"></i>
                                                                        </a>
                                                                        <a href="javascript:void(0)"
                                                                                onclick="if(confirm('Bạn có chắc chắn muốn xóa sinh viên này?')) { document.getElementById('delete-form-{{ $sv->MA_SV }}').submit(); }"
                                                                                class="delete">
                                                                                <form id="delete-form-{{ $sv->MA_SV }}"
                                                                                        action="{{ route('student.delete_st', $sv->MA_SV) }}"
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



.ten-de-tai {
        max-width: 300px;
        /* Chiều rộng tối đa cho cột tên đề tài, điều chỉnh giá trị này */
        min-width: 200px;
        /* Chiều rộng tối thiểu cho cột tên đề tài */
        word-wrap: break-word;
        /* Ngắt dòng nếu quá dài */
        white-space: normal;
        /* Cho phép xuống dòng */
}

/* Điều chỉnh chiều rộng của cột AVT và Họ tên */
td img {
        width: 90px;
        /* Chiều rộng của ảnh */
        height: auto;
        /* Tự động điều chỉnh chiều cao */
}
}
</style>
<script>
$(document).ready(function() {
        $('#list').dataTable()
})
</script>

@endsection