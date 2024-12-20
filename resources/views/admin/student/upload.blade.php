@extends('admin-layout')
@section('admin_content')

<form action="{{ route('student.word') }}" method="POST" enctype="multipart/form-data" style="max-width: 800px;">
        @csrf
        <input type="file" name="file" class="form-control" accept=".doc,.docx" required>

        <button type="submit" class="btn btn-primary">Import</button>
</form>
@if(session('success'))
<div class="alert alert-success">
        {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
        {{ session('error') }}
</div>
@endif

@endsection