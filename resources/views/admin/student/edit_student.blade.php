@extends('admin-layout')
@section('admin_content')

<div class="col-lg-12">
        <div class="card card-outline">
                <div class="card-body">
                        <form action="{{ route('student.update_st', $sinh_vien->MA_SV) }}" method="POST"
                                id="manage-staff">
                                {{ csrf_field() }}
                                @method('PUT')
                                <input type="hidden" name="id">

                                <div class="row">
                                        <div class="col-md-12">
                                                <div id="msg" class=""></div>

                                                <div class="row">
                                                        <div class="col-sm-6 form-group ">
                                                                <label for="" class="control-label">Mã sv</label>
                                                                <input type="text" name="ma_sv" id=""
                                                                        class="form-control "
                                                                        value="{{  $sinh_vien->MA_SV}}" required>
                                                        </div>
                                                        <div class="col-sm-6 form-group ">
                                                                <label for="" class="control-label">Tên</label>
                                                                <input type="text" name="hoten_sv" id=""
                                                                        class="form-control "
                                                                        value="{{$sinh_vien->HOTEN_SV}}" required>
                                                        </div>
                                                </div>


                                                <div class="row">
                                                        <div class="col-sm-6 form-group">
                                                                <label for="" class="control-label">Tên đề tài</label>

                                                                <select name="tendetai" id="ma_dt"
                                                                        class="form-control select2">
                                                                        @foreach($de_tai as $dt)
                                                                        <!-- Thay đổi từ $de_tai thành $dt -->
                                                                        <option value="{{ $dt->MA_DT }}"
                                                                                {{ $dt->MA_DT == $sinh_vien->MA_DT ? 'selected' : '' }}>
                                                                                {{ $dt->TEN_DT }}
                                                                        </option>
                                                                        @endforeach
                                                                </select>

                                                        </div>

                                                        <div class=" col-sm-6 form-group ">
                                                                <label for="" class=" control-label">Email</label>
                                                                <input type="email" name="email_sv" id=""
                                                                        class="form-control "
                                                                        value="{{  $sinh_vien->EMAIL_SV}}" required>
                                                        </div>
                                                </div>

                                                <div class="row">
                                                        <div class="col-sm-6 form-group ">
                                                                <label for="" class="control-label">Số điện
                                                                        thoại</label>
                                                                <input type="text" name="sdt_sv" id=""
                                                                        class="form-control "
                                                                        value="{{$sinh_vien->SDT_SV}}" required>
                                                        </div>
                                                </div>

                                                <div class="row">
                                                        <div class="col-sm-6 form-group ">
                                                                <label for="" class="control-label">Lớp</label>
                                                                <input type="text" name="lop_sv" id=""
                                                                        class="form-control "
                                                                        value="{{$sinh_vien->LOP_SV}}" required>
                                                        </div>
                                                        <div class="col-sm-6 form-group">
                                                                <label for="" class="control-label">Tên Giảng
                                                                        Viên</label>

                                                                <select name="tengiangvien" id="ma_gv"
                                                                        class="form-control  select2">

                                                                        @foreach($giang_vien as $giang_vien)

                                                                        <option value="{{$giang_vien->MA_GV}}">
                                                                                {{ $giang_vien->HOTEN_GV }}</option>

                                                                        @endforeach
                                                                </select>

                                                        </div>
                                                </div>
                                                <!-- <div class="col-sm-6 form-group">
                                                        <label for="imageUpload" class="label"
                                                                style="padding-top=20px;">Upload
                                                                Hình
                                                                Ảnh</label>
                                                        <input type="file" name="avt_sv" id="imageUpload" class="pre"
                                                                accept="image/*" value="{{$sinh_vien->AVT_SV}}"
                                                                required>

                                                </div> -->

                                        </div>
                                </div>
                                <div class="row">
                                        <div class="col-md-6">
                                                <button type="submit" name="submit" class="btn btn-primary"
                                                        form="manage-staff">Save</button>

                                                <button class="btn btn-secondary"
                                                        href="{{ route('student.list_st') }}">Cancel</button>
                                        </div>
                                </div>
                        </form>




                </div>
        </div>

        @endsection