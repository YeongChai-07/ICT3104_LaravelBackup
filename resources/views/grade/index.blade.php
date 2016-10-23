@extends('layouts.Layout')

@section('title','View Module')

@section('content')
<style>
textarea { width:250px !important; height:100px !important; }
</style>

<div class="generalHeader">
    View Module
</div>
<body>
   
    @if(Session::has('error_message'))
        <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
        {{Session::forget('error_message')}}
    @endif
    <br/>
 <div class="row">
    <div class="col-md-12 col-sm-12">
        <br><br>
        <table width="100%" cellpadding="5" cellspacing="5" id="modulesList" border="1"  class="table table-striped table-bordered dt-responsive" >
            <thead>

                <tr><th>S/N</th><th>Module Name</th><th>Module Description</th><th width="40%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($modules as $key=>$module)
                <tr>
                <td>{{   $module->id }}</td>
                <td>{{  $module->modulename }}</td>
                <td> {{ $module->description }}</td>
                <td>

                @if($today > $module->editdate)
                  @if(strcmp($role,'hod') == 0)
                      @if($today > $module->freezedate)
                        <a class="btn btn-info" href="{{  $module->id }}/publish" >Publish Grades</a>
                      @else
                        <a class="btn btn-info" href="{{  $module->id }}/approval">Approve Recommendations</a>
                      @endif
                  @endif
                @else
                  <a class="btn btn-info" href="{{  $module->id }}/managegrade">Manage Grade</a>
                @endif

                </td>
                </tr>  
                @endforeach
            </tbody>
        </table>
        {!! $modules->render() !!}
    </div>
</div>   

</body>

@stop


<script type="text/javascript">
    //Pop up
function checkDelete(){
    return confirm('Are you sure?');
}
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
@include('layouts.datatables')
<script type="text/javascript">
  $(function($) {
  
  $('#modulesList').DataTable( {
  aaSorting : [[1, 'asc']],
    responsive: true,
  'paging':false,
  "bStateSave": true,
  "iCookieDuration": 365*60*60*24
});
  
  });
</script>