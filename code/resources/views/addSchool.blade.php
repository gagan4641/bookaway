@extends('layouts.app')
@section('content')
<!-- <link href="{{ asset('pick/timepicki.css') }}" rel="stylesheet">
<script src="{{ asset('pick/timepicki.js') }}"></script> -->
<div class="">
	<div class="page-title">
		@if(Session::has('addSchoolErr'))
			<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('addSchoolErr') }}</p>
			<?php Session::forget('addSchoolErr'); ?>
		@endif
		<div class="title_left">
			<h3>ADD SCHOOL</h3>
		</div>
	</div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><small>add new School</small></h2>
					<ul class="nav navbar-right panel_toolbox">
						<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
					</ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" role="form" method="POST" action="{{url('saveSchool') }}">
                    {{ csrf_field() }}
						<div class="form-group{{ $errors->has('schoolName') ? ' has-error' : '' }}">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="schoolName">
								School Name <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="schoolName" id="schoolName" class="form-control col-md-7 col-xs-12" value="{{ old( 'schoolName') }}" >
								@if ($errors->has('schoolName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('schoolName') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">
								School Address
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea name="address" id="address" class="form-control col-md-7 col-xs-12">{{ old( 'address') }}</textarea>
								@if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="country">
								Country <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="country" class="form-control col-md-7 col-xs-12" id="editCountry">
									<option value="">Select Country</option>
									@foreach($countryList as $contData)
										<option value="{{ $contData->id }}">{{ $contData->name }}</option>
									@endforeach
								</select>
								@if ($errors->has('country'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('country') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="state">
								State <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="state" class="form-control col-md-7 col-xs-12" id="editState">
									<option value="">Select State</option>
								</select>
								@if ($errors->has('state'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="city">
								City <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="city" class="form-control col-md-7 col-xs-12" id="editCity">
									<option value="">Select City</option>
								</select>
								@if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
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
<script type="text/javascript">
    $(document).ready(function(){
        $("#editCountry").change(function(){
            $("#editState").empty();
            $("#editCity").empty();
            var countryId=$("#editCountry").val();
            if(countryId==""){
                $("#editState").append("<option value=''>Select State</option>");
                $("#editCity").append("<option value=''>Select State</option>");
            }else{
                var countryIdAjax, token, url, data;
                token = $('input[name=_token]').val();
                countryIdAjax = countryId;
                url = '<?php echo url("/"); ?>/getstates';
                data = {
                    countryIdAjax: countryIdAjax,
                };
                $.ajax({
                    url: url,
                    headers: {'X-CSRF-TOKEN': token},
                    data: data,
                    type: 'POST',
                    datatype: 'JSON',
                    success: function (resp) {
                        $("#editState").append("<option value=''>Select State</option>");
                        $("#editCity").append("<option value=''>Select State</option>");
                        $.each(resp.statesList, function (key, value) {
                            var stateidRes = value.id;
                            var statenameRes = value.name;
                            $("#editState").append('<option value="'+stateidRes+'">'+statenameRes+'</option>');
                        });
                    }
                });
            }
        });

        $("#editState").change(function(){
            $("#editCity").empty();
            var stateId=$("#editState").val();
            if(stateId==""){
                $("#editCity").append("<option value=''>Select city</option>");
            }else{
                var stateIdAjax, token, url, data;
                token = $('input[name=_token]').val();
                stateIdAjax = stateId;
                url = '<?php echo url("/"); ?>/getcities';
                data = {
                    stateIdAjax: stateIdAjax,
                };
                $.ajax({
                    url: url,
                    headers: {'X-CSRF-TOKEN': token},
                    data: data,
                    type: 'POST',
                    datatype: 'JSON',
                    success: function (resp) {
                        $("#editCity").append("<option value=''>Select city</option>");
                        $.each(resp.citiesList, function (key, value) {
                            var cityidRes = value.id;
                            var citynameRes = value.name;
                            $("#editCity").append('<option value="'+cityidRes+'">'+citynameRes+'</option>');
                        });
                    }
                });
            }
        });
    });
</script>
@endsection