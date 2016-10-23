@extends('layouts.layout')

@section('title','Add Lecturer')

@section('content')

@if(Session::has('error_message'))
 <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
 {{Session::forget('error_message')}}
@endif
 @if(Session::has('success_message'))
  <div class="alert alert-success">{{ Session::get('success_message') }}</div>
 {{Session::forget('success_message')}}
 @endif
<div class="generalHeader">
    Add Lecturer
</div>
<?php echo Form::open(array('url' => 'admin/addlecturer', 'method' => 'post')) ?>
 <div class="form-group">
            {!!Form::label('name','Name')!!}
            {!!Form::text('name',null,array('class' => 'form-control'))!!}
        </div>
        <div class="form-group">
            {!!Form::label('email','Email')!!}
            {!!Form::text('email',null,array('class' => 'form-control', 'required' => 'required'))!!}
        </div>
		
		 <div class="form-group">
            {!!Form::label('Contact Number','contact')!!}
            {!!Form::text('contact',null, array('class' => 'form-control', 'required' => 'required'))!!}
        </div>
		
       <a href="{{URL::asset('admin/lecturer')}}" class="btn btn-primary" style="float:right;">Back to Lecturer list</a>
        {!!Form::submit('Add', array('class' => 'btn btn-success'))!!}
        {!! Form::close() !!}
@stop