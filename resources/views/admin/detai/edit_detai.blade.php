@extends('admin-layout')
@section('admin_content')

<div class="col-lg-12">
        <div class="card card-outline">
                <div class="card-body">
                        <form action="{{route('detai.update_dt',$de_tai->MA_DT) }}" method="POST" id="manage-staff">
                                {{ csrf_field( )}}
                                @Method('PUT')
                                <input type="hidden" name="MA_DT">
                                <div class="row">
                                        <div class="col-md-12">
                                                <div id="msg" class=""></div>

                                                <div class="row">
                                                        <div class="col-sm-6 form-group ">
                                                                <label for="" class="control-label">Mã đề tài</label>

                                                                <input type="text" name="ma_dt" class="form-control"
                                                                        value="{{$de_tai->MA_DT}}" required>
                                                        </div>
                                                        <div class="col-sm-6 form-group ">
                                                                <label for="" class="control-label">Tên đề tài</label>
                                                                <input type="text" name="ten_dt" id="pre"
                                                                        value="{{$de_tai->TEN_DT}}"
                                                                        class="form-control " required>
                                                        </div>
                                                </div>

                                                <!-- <div class="row">


                                                        <div class="col-sm-6 form-group ">
                                                                <label for="" class="control-label">Ngày nộp</label>
                                                                <input type="date" name="ngaynop" id=""
                                                                        value="{{$de_tai->NGAYNOP}}"
                                                                        class="form-control " required>
                                                        </div>
                                                </div>
 -->


                                        </div>
                                </div>
                        </form>


                        <div class="row">
                                <div class="col-md-6">
                                        <button class="btn btn-primary" form="manage-staff">Thêm</button>

                                        <button class="btn btn-secondary"
                                                href="{{route('detai.list_dt')}}">Cancel</button>
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