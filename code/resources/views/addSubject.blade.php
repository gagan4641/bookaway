@extends('layouts.app')
@section('content')
<!-- <link href="{{ asset('pick/timepicki.css') }}" rel="stylesheet">
<script src="{{ asset('pick/timepicki.js') }}"></script> -->
<div class="">
	<div class="page-title">
		@if(Session::has('addSubjectErr'))
			<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('addSubjectErr') }}</p>
			<?php Session::forget('addSubjectErr'); ?>
		@endif
		<div class="title_left">
			<h3>EDIT SUBJECT</h3>
		</div>
	</div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><small>Edit already added Subject</small></h2>
					<ul class="nav navbar-right panel_toolbox">
						<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
					</ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" role="form" method="POST" action="{{url('saveSubject') }}">
                    {{ csrf_field() }}
						<div class="form-group{{ $errors->has('subjectName') ? ' has-error' : '' }}">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="subjectName">
								Subject Name <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="subjectName" id="subjectName" required="required" class="form-control col-md-7 col-xs-12" value="{{ old( 'subjectName') }}" >
								@if ($errors->has('subjectName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('subjectName') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								<button type="submit" class="btn btn-success">Submit</button>
							</div>
						</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection