@extends('admin-layout')
@section('admin_content')

<div class="col-lg-12">
        <div class="card card-outline">
                <div class="card-body">
                        <form action="{{ route('giangvien.update_gv',$giang_vien->MA_GV) }}" method="POST"
                                id="manage-staff">
                                {{ csrf_field( )}}
                                @Method('PUT')
                                <input type="hidden" name="id">
                                <div class="row">
                                        <div class="col-md-12">
                                                <div id="msg" class=""></div>

                                                <div class="row">
                                                        <div class="col-sm-6 form-group ">
                                                                <label for="" class="control-label">Mã giảng
                                                                        viên</label>
                                                                <input type="text" name="ma_gv" id=""
                                                                        class="form-control "
                                                                        value="{{$giang_vien->MA_GV}}" required>
                                                        </div>
                                                        <div class="col-sm-6 form-group ">
                                                                <label for="" class="control-label">Họ tên</label>
                                                                <input type="text" name="hoten_gv" id="pre"
                                                                        class="form-control "
                                                                        value="{{$giang_vien->HOTEN_GV}}" required>
                                                        </div>
                                                </div>

                                                <div class="row">


                                                        <div class="col-sm-6 form-group ">
                                                                <label for="" class="control-label">Email</label>
                                                                <input type="email" name="email_gv" id=""
                                                                        value="{{$giang_vien->EMAIL_GV}}"
                                                                        class="form-control ">
                                                        </div>
                                                        <div class="col-sm-6 form-group ">
                                                                <label for="" class="control-label">Ngày sinh</label>
                                                                <input type="date" name="ngaysinh_gv" id=""
                                                                        class="form-control "
                                                                        value="{{$giang_vien->NGAYSINH_GV}}" required>
                                                        </div>
                                                </div>

                                                <div class="row">
                                                        <div class="col-sm-6 form-group ">
                                                                <label for="" class="control-label">Số điện
                                                                        thoại</label>
                                                                <input type="text" name="sdt_gv" id=""
                                                                        class="form-control "
                                                                        value="{{$giang_vien->SDT_GV}}" required>
                                                        </div>
                                                        <div class="col-sm-6 form-group">
                                                                <label for="imageUpload" class="label"
                                                                        style="padding-top=20px;">Upload Hình
                                                                        Ảnh</label>
                                                                <input type="file" name="avt_gv" id="imageUpload"
                                                                        class="pre" accept="image/*"
                                                                        value="{{$giang_vien->AVT_GV}}" required>

                                                        </div>
                                                </div>

                                        </div>
                                </div>
                        </form>


                        <div class="row">
                                <div class="col-md-6">
                                        <button class="btn btn-primary" form="manage-staff">Save</button>

                                        <button class="btn btn-secondary"
                                                href="./index.php?page=staff_list">Cancel</button>
                                </div>
                        </div>
                </div>
        </div>
        <style>
        .pre {
                width: 100%;
                /* Đảm bảo input chiếm toàn bộ chiều rộng */
                height: 30px;
                /* Chiều cao cố định */
                padding: 5px;
                /* Padding bên trong */
                font-size: 14px;
                /* Kích thước chữ */
        }

        .label {
                padding-top: 10px;
                /* Thêm khoảng cách phía trên cho label */
        }
        </style>

        @endsection