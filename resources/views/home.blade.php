@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                <div class="row">
                    <div class="col-12">
                        <form action="#" method="POST" id="searchForm">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Advanced Search</h4>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                            
                                                <select name="search_by_hobby" id="search_by_hobby" class="form-control"> 
                                                    <option value="">--All Status--</option>
                                                @foreach($hobbies as $hobby)
                                                    <option value="{{$hobby->id}}">{{$hobby->name}}</option>
                                                @endforeach
                                                </select>
                                                
                                            </div>
                                        </div>                                    
                                        <div class="col-sm-2">
                                            <button type="submit" id="searchBtn" class="btn btn-warning waves-effect waves-light">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- end col -->
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="ajaxDatatable" class="table table-bordered dt-responsive1 nowrap table-striped" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Full Name</th>
                                            <th>Profile Pic</th>
                                            <th>Email</th>
                                            <th>Mobile Number</th>
                                            <th>Status</th>
                                            <th>Registered date and time</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>



                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function(){
    var table = $('#ajaxDatatable').DataTable({
        processing:true,
        paging:true,
        autoWidth:true,
        scrollX:true,
        serverSide:true,
        order: [[0,'desc']],
        ajax:"",
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data: 'full_name', name: 'first_name'},
            {data:'photo',name:'photo'},
            {data:'email',name:'email'},
            {data:'phone',name:'phone'},
            {data:'status',name:'status'},
            {data:'created_at',name:'created_at'},
        ],
    });
});
</script>
