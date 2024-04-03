@extends('layouts.app')

@section('subtitle', 'user')
@section('content_header_title', 'user')
@section('content_header_subtitle', 'Create')

@section('content_body')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
    <div class="container">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Buat User</h3>
            </div>

            <form method="post" action="../user">
            @csrf {{ csrf_field() }}
            <div class="card-body">
                <div class="form-group">
                    <label for="level_id">ID User</label>
                    <select class="form-control" name="level_id">
                        <option value=1>Admin 1</option>
                        <option value=2>Admin 2</option>
                        <option value=3>Admin 3</option>
                        <option value=4>Admin 4</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror " id="username" name="username" placeholder="Masukkan Username">
                    @error('username')
                           <div class="alert alert-danger">
                                 {{ $message }}
                           </div>
                            @enderror
                </div>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror " id="nama" name="nama" placeholder="Masukkan Nama">
                    @error('nama')
                           <div class="alert alert-danger">
                                 {{ $message }}
                           </div>
                            @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror " name="password" id="password" placeholder="Masukkan Password">
                    @error('password')
                           <div class="alert alert-danger">
                                 {{ $message }}
                           </div>
                            @enderror
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        </div>
    </div>
    @endsection