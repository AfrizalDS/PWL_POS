@extends('adminlte::page') 
 
@section('title', 'Dashboard') 
 
@section('content_header') 
    <h1>Form User</h1> 
@stop 
 
@section('content') 
            <!-- general form elements disabled -->
            <div class="card card-warning">
                <div class="card-header">
                  <h3 class="card-title">Add User</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form>
                    <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <label>Select Level</label>
                            <select class="form-control">
                              <option>Admin 1</option>
                              <option>Admin 2</option>
                              <option>Admin 3</option>
                              <option>Admin 4</option>
                              <option>Admin 5</option>
                            </select>
                          </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                          <!-- text input -->
                          <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" placeholder="input Username">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col">
                          <!-- text input -->
                          <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" placeholder="input Nama">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col">
                          <!-- text input -->
                          <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" placeholder="input Password">
                          </div>
                        </div>
                      </div>

                    <button type = "submit" class ="btn btn-success">Tambah</button> 
              </div>
@stop