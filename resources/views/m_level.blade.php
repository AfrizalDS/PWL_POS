@extends('adminlte::page') 
 
@section('title', 'Dashboard') 
 
@section('content_header') 
    <h1>Form Level</h1> 
@stop 
 
@section('content') 
<!-- general form elements disabled -->
            <div class="card card-warning">
                <div class="card-header">
                  <h3 class="card-title">Add level</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form method="post">
                    @csrf {{ csrf_field() }}
                    <div class="row">
                        <div class="col">
                          <!-- text input -->
                          <div class="form-group">
                            <label>Level Code</label>
                            <input type="text" class="form-control" placeholder="Input Level Code">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col">
                          <!-- text input -->
                          <div class="form-group">
                            <label>Level Name</label>
                            <input type="text" class="form-control" placeholder="Input Level Name">
                          </div>
                        </div>
                      </div>

                    <button type = "submit" class ="btn btn-success">ADD</button> 
              </div>
@stop