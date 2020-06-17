@extends('layouts.app')
@section('content')
<!-- <link href="{{ asset('pick/timepicki.css') }}" rel="stylesheet">
<script src="{{ asset('pick/timepicki.js') }}"></script> -->
<div class="">
	<div class="page-title">
		@if(Session::has('changeFeesSussess'))
			<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('changeFeesSussess') }}</p>
			<?php Session::forget('changeFeesSussess'); ?>
		@endif
		<div class="title_left">
			<h3>Update Fees</h3>
		</div>
	</div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><small>Change add book fees</small></h2>
					<ul class="nav navbar-right panel_toolbox">
						<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
					</ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" role="form" method="POST" action="{{url('updateFees') }}">
                    {{ csrf_field() }}
						<div class="form-group{{ $errors->has('bookFees') ? ' has-error' : '' }}">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="bookFees">
								Add Book Fees <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="bookFees" id="bookFees" class="form-control col-md-7 col-xs-12" value="{{ old( 'bookFees', $fees->amount_fees) }}" >
								@if ($errors->has('bookFees'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('bookFees') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								<button type="submit" class="btn btn-success">Update</button>
							</div>
						</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection