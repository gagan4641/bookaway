@extends('layouts.app')
@section('content')
<!-- <link href="{{ asset('pick/timepicki.css') }}" rel="stylesheet">
<script src="{{ asset('pick/timepicki.js') }}"></script> -->
<div class="">
	<div class="page-title">
		@if(Session::has('upSchoolErr'))
			<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('upSchoolErr') }}</p>
			<?php Session::forget('upSchoolErr'); ?>
		@endif
		<div class="title_left">
			<h3>EDIT SCHOOL</h3>
		</div>
	</div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><small>Edit already added School</small></h2>
					<ul class="nav navbar-right panel_toolbox">
						<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
					</ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" role="form" method="POST" action="{{url('updateSchool') }}">
                    {{ csrf_field() }}
                    	<input type="hidden" name="id_school" value="{{ $schoolDetails->id_school }}" >
						<div class="form-group{{ $errors->has('schoolNameEdit') ? ' has-error' : '' }}">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="schoolNameEdit">
								School Name <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="schoolNameEdit" id="schoolNameEdit" required="required" class="form-control col-md-7 col-xs-12" value="{{ old( 'schoolNameEdit', $schoolDetails->name_school) }}" >
								@if ($errors->has('schoolNameEdit'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('schoolNameEdit') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('addressEdit') ? ' has-error' : '' }}">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="addressEdit">
								School Address
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea name="addressEdit" id="addressEdit" class="form-control col-md-7 col-xs-12">{{ old( 'addressEdit', $schoolDetails->address_school) }}</textarea>
								@if ($errors->has('addressEdit'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('addressEdit') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('countryEdit') ? ' has-error' : '' }}">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="countryEdit">
								Country <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="countryEdit" class="form-control col-md-7 col-xs-12" id="editCountry">
									<option value="">Select Country</option>
									@foreach($countryList as $contData)
										<option value="{{ $contData->id }}" <?php if($contData->id == $schoolDetails->country_school){ echo "selected"; } ?>>{{ $contData->name }}</option>
									@endforeach
								</select>
								@if ($errors->has('countryEdit'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('countryEdit') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('stateEdit') ? ' has-error' : '' }}">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="stateEdit">
								State <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="stateEdit" class="form-control col-md-7 col-xs-12" id="editState">
									<option value="">Select State</option>
									@if($stateList)
									@foreach($stateList as $stateData)
										<option value="{{ $stateData->id }}" <?php if($stateData->id == $schoolDetails->state_school){ echo "selected"; } ?>>{{ $stateData->name }}</option>
									@endforeach
									@endif
								</select>
								@if ($errors->has('stateEdit'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('stateEdit') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('cityEdit') ? ' has-error' : '' }}">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cityEdit">
								City <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="cityEdit" class="form-control col-md-7 col-xs-12" id="editCity">
									<option value="">Select City</option>
									@if($cityList)
									@foreach($cityList as $cityData)
										<option value="{{ $cityData->id }}" <?php if($cityData->id == $schoolDetails->country_school){ echo "selected"; } ?>>{{ $cityData->name }}</option>
									@endforeach
									@endif
								</select>
								@if ($errors->has('cityEdit'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cityEdit') }}</strong>
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