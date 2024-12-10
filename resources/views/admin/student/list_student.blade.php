@extends('admin-layout')
@section('admin_content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<div class="col-lg-12">
        <div class="card card-outline">


                <div class="card-tools card-tools text-right">
                        <a class="btn btn-primary" href="{{route('student.add_st')}}">Add</a>
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



                        <div class="container mt-4">
                                <form class="d-flex justify-content-start mb-3" method="GET"
                                        action="{{ route('student.list_st') }}">
                                        <!-- Năm học -->
                                        <div class="me-3">
                                                <label for="nam_hoc" class="form-label">Năm học:</label>
                                                <select class="form-select form-select-sm" id="nam_hoc" name="nam_hoc"
                                                        onchange="location = this.value;">
                                                        <!-- Kiểm tra giá trị của nam_hoc trong request để giữ trạng thái đã chọn -->
                                                        <option value="{{ route('student.list_st', ['hoc_ky' => request('hoc_ky', 1), 'nam_hoc' => '2024']) }}"
                                                                @if(request('nam_hoc')=='2024' ) selected @endif>
                                                                2024</option>
                                                        <option value="{{ route('student.list_st', ['hoc_ky' => request('hoc_ky', 1), 'nam_hoc' => '2025']) }}"
                                                                @if(request('nam_hoc')=='2025' ) selected @endif>
                                                                2025</option>
                                                        <!-- Thêm các tùy chọn năm học nếu cần -->
                                                </select>
                                        </div>

                                        <span class="mx-2"></span>

                                        <!-- Học kỳ -->
                                        <div class="me-3">
                                                <label for="hoc_ky" class="form-label">Học kỳ:</label>
                                                <select class="form-select form-select-sm" id="hoc_ky" name="hoc_ky"
                                                        onchange="location = this.value;">
                                                        <!-- Kiểm tra giá trị của hoc_ky trong request để giữ trạng thái đã chọn -->
                                                        <option value="{{ route('student.list_st', ['hoc_ky' => 1, 'nam_hoc' => request('nam_hoc', '2024-2025')]) }}"
                                                                @if(request('hoc_ky')==1) selected @endif>Học kỳ 1
                                                        </option>
                                                        <option value="{{ route('student.list_st', ['hoc_ky' => 2, 'nam_hoc' => request('nam_hoc', '2024-2025')]) }}"
                                                                @if(request('hoc_ky')==2) selected @endif>Học kỳ 2
                                                        </option>
                                                        <!-- Thêm các tùy chọn học kỳ nếu cần -->
                                                </select>
                                        </div>
                                </form>
                        </div>


                        <div class="table-responsive">
                                <table class="table  table-striped tabe-hover" id="list">

                                        <thead>
                                                <tr>
                                                        <th class="text-center" style="width: 50px;">#</th>
                                                        <th style="width: 100px;">Mã sv</th>
                                                        <th style="width: 150px;">Họ tên</th>
                                                        <th class="ten-de-tai" style="width: 250px;">Tên đề tài</th>
                                                        <th style="width: 150px;">Tên giảng viên</th>
                                                        <th style="width: 200px;">Email</th>
                                                        <th style="width: 100px;">Lớp</th>
                                                        <th> Năm Học</th>
                                                        <th>Học Kỳ </th>
                                                        <th style="width: 100px;">Action</th>

                                                </tr>
                                        </thead>
                                        <tbody>
                                                @foreach($sinh_vien as $index => $sv)


                                                <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td><b>{{ $sv->MA_SV }}</b></td>

                                                        <td><b>{{ $sv->HOTEN_SV }}</td>
                                                        <td>
                                                                <b>{{ $de_tai->where('MA_DT', $sv->MA_DT)->first()->TEN_DT ?? 'N/A' }}</b>
                                                        </td>
                                                        <td>
                                                                <b>{{ $giang_vien->firstWhere('MA_GV', $sv->MA_GV)->HOTEN_GV ?? 'N/A' }}</b>

                                                        </td>
                                                        <td><b>{{$sv->EMAIL_SV}}</b></td>
                                                        <td><b>{{ $sv->LOP_SV	}}</b></td>
                                                        <td><b>{{ $sv->Nam_hoc	}}</b></td>
                                                        <td><b>{{ $sv->Hoc_ky	}}</b></td>
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
$.noConflict();
jQuery(document).ready(function($) {
        $('#list').DataTable();

});
</script>

@endsection