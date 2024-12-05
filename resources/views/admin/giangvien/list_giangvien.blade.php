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
                                <table class="table table-hover text-center" id="list">

                                        <thead>
                                                <tr>
                                                        <th class="text-center">#</th>
                                                        <th>Mã giảng viên</th>
                                                        <th>Avt</th>
                                                        <th>Họ tên</th>
                                                        <th>Email</th>
                                                        <th>Số điện thoại</th>
                                                        <th>Ngày sinh</th>
                                                        <th>Action</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                @foreach($giang_vien as $index => $giang_vien)
                                                <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td class="text-center"><b>{{ $giang_vien -> MA_GV }}</b></td>
                                                        </td>
                                                        <td>
                                                                <img src="{{ asset('font-end/img/' . $giang_vien->AVT_GV) }}"
                                                                        alt="Hình ảnh giáo viên"
                                                                        style="width:70px; height: auto;">
                                                        </td>

                                                        <td><b>{{$giang_vien -> HOTEN_GV}}</td>
                                                        <td><b>{{ $giang_vien->EMAIL_GV	 }}</b></td>
                                                        <td><b>{{ $giang_vien->SDT_GV}}</b></td>
                                                        <td><b>{{ $giang_vien->NGAYSINH_GV	}}</b></td>
                                                        <td class="text-center">
                                                                <div class="btn-group">
                                                                        <a href="{{route('giangvien.edit_gv',$giang_vien->MA_GV)}}"
                                                                                class="edit">
                                                                                <i class="fas fa-edit"></i>
                                                                        </a>
                                                                        <a href="javascript:void(0)"
                                                                                onclick="if(confirm('Bạn có chắc chắn muốn xóa giảng viên này?')) { document.getElementById('delete-form-{{ $giang_vien->MA_GV }}').submit(); }"
                                                                                class="delete">
                                                                                <form id="delete-form-{{ $giang_vien->MA_GV }}"
                                                                                        action="{{ route('giangvien.delete_gv', $giang_vien->MA_GV) }}"
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
        <style>
        table td {
                vertical-align: middle !important;
        }
        </style>
        <script>
        jQuery(document).ready(function($) {
                $('#list').DataTable();
        });
        </script>

        @endsection