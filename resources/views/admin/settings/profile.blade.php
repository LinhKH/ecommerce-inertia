@extends('admin.layout')
@section('title','Profile Settings')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @component('admin.components.content-header',['breadcrumb'=>['Dashboard'=>'admin/dashboard']])
        @slot('title') Profile Settings @endslot
        @slot('add_btn') @endslot
        @slot('active') Profile Settings @endslot
    @endcomponent
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- form start -->
            <form class="form-horizontal" id="updateProfileSetting" method="POST">
            {{ csrf_field() }}
                @foreach($data as $item)
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- jquery validation -->
                    <input type="hidden" class="url" value="{{url('admin/profile-settings')}}" >
                    <!-- jquery validation -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Admin Details</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <span>Admin Name</span>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="admin_name" value="{{$item->admin_name}}"  placeholder="Enter Name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <span>Admin Email</span>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="email" class="form-control" name="admin_email" value="{{$item->admin_email}}"  placeholder="Enter Email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <span>Username</span>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="username" value="{{$item->username}}"  placeholder="Enter Username">
                                                </div>
                                            </div>
                                        </div>
                                        <input type="submit" class="btn btn-primary float-right" value="Update"/>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                @endforeach
            </form> <!-- /.form start -->

            <form class="form-horizontal" id="updateAdminPassword" method="POST">
            {{ csrf_field() }}
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- jquery validation -->
                    <input type="hidden" class="p-url" value="{{url('admin/profile-settings/change-password')}}" >
                    <!-- jquery validation -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Change Password</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <span>Old Password</span>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="password" class="form-control" name="password" placeholder="Old Password">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <span>New Password</span>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="password" class="form-control" name="new_pass" id="new-pass" placeholder="Enter New Password">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <span>Re-enter New Password</span>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="password" class="form-control" name="re_pass"  placeholder="Re-enter New Password">
                                                </div>
                                            </div>
                                        </div>
                                        <input type="submit" class="btn btn-primary float-right" value="Update"/>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </form> <!-- /.form start -->
        </div><!-- /.container-fluid -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
@stop