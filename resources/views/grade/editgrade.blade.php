@extends('layouts.layout')

@section('title','Edit Grade')

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
    Edit Grade
</div>
<?php echo Form::open(array('url' => 'grade/'.$moduleid.'/'.$gradeid.'/editgrade', 'method' => 'post')) ?>
 <div class="form-group">
            {!!Form::label('grade','Grade')!!}
            {!!Form::text('grade',$grades->grade,array('class' => 'form-control', 'required' => 'required'))!!}
        </div>
        <div class="form-group">
            {!!Form::label('recommendation','Recommendation')!!}
            @if(!isset($recommendations->recommendation))
           
             {!!Form::textarea('recommendation',null,array('class' => 'form-control'))!!}
            @else
     
             {!!Form::textarea('recommendation',$recommendations->recommendation,array('class' => 'form-control'))!!} 
            @endif
        </div>
        <div class="form-group">
            {!!Form::label('Moderation','Select Moderation')!!}
            <select id="moderation" name="moderation">

            <?php
            if(!isset($recommendations->moderation))
            {
              echo'<option value="0.0" selected>Select If applicable</option>';                      
              echo '<option value="0.1">0.1</option>';
              echo '<option value="0.2">0.2</option>';
              echo '<option value="0.3">0.3</option>';
              echo '<option value="0.4">0.4</option>';
              echo '<option value="0.5">0.5</option> ';             
            }
            else
            {
                if($recommendations->moderation == '')
                {
                  echo '<option value=0.0 selected>Select if applicable</option>';
                }
                else
                {
                  echo '<option value=0.0>Select if applicable</option>';
                }
                if($recommendations->moderation == '0.1')
                {
                  echo '<option value=0.1 selected>0.1</option>';
                }
                else
                {
                  echo '<option value=0.1>0.1</option>';
                }
                if($recommendations->moderation == '0.2')
                {
                  echo '<option value=0.2 selected>0.2</option>';
                }
                else
                {
                  echo '<option value=0.2>0.2</option>';
                }
                if($recommendations->moderation == '0.3')
                {
                  echo '<option value=0.3 selected>0.3</option>';
                }
                else
                {
                  echo '<option value=0.3>0.3</option>';
                }                
                if($recommendations->moderation == '0.4')
                {
                  echo '<option value=0.4 selected>0.4</option>';
                }
                else
                {
                  echo '<option value=0.4>0.4</option>';
                }              
                if($recommendations->moderation == '0.5')
                {
                  echo '<option value=0.5 selected>0.5</option>';
                }
                else
                {
                  echo '<option value=0.5>0.5</option>';
                } 
            }    
            ?>
            </select>


        </div>
       <a href="{{URL::asset('grade/index')}}" class="btn btn-danger" style="float:right;">Back to Grades list</a>
        {!!Form::submit('Edit Grade', array('class' => 'btn btn-success'))!!}
        {!! Form::close() !!}
@stop