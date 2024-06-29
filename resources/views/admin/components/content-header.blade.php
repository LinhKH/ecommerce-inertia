<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0 mr-2 text-dark d-inline-block">{{$title}}</h1>
                {{$add_btn}}
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @foreach($breadcrumb as $key => $value)
                        <li class="breadcrumb-item"><a href="{{url($value)}}">{{$key}}</a></li>
                    @endforeach
                    <li class="breadcrumb-item active">{{$active}}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>