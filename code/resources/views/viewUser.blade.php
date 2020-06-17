@extends('layouts.app')
@section('content')
<div class="">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
			<div class="x_title">
				<h2>{{$usersDetail->fname}} {{$usersDetail->lname}}<small>(User Deails)</small></h2>
				<ul class="nav navbar-right panel_toolbox">
					<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
		        <div class="" role="tabpanel" data-example-id="togglable-tabs">
					<ul id="myTab1" class="nav nav-tabs bar_tabs right" role="tablist">
						<li role="presentation" class=""><a href="#tab_content11" id="home-tabb" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Other Information</a>
						<li role="presentation" class="active"><a href="#tab_content33" role="tab" id="profile-tabb3" data-toggle="tab" aria-controls="profile" aria-expanded="false">Profile</a>
						</li>
					</ul>
					<div id="myTabContent2" class="tab-content">


						<div role="tabpanel" class="tab-pane fade" id="tab_content11" aria-labelledby="home-tab">
							<h2>Country</h2>
							<p>{{ $usersDetail->contName }}</p>
							<br>
							<h2>State</h2>
							<p>{{ $usersDetail->stateName }}</p>
							<br>
							<h2>City</h2>
							<p>{{ $usersDetail->cityName }}</p>
							<br>
							<h2>Address</h2>
							<p>{{ $usersDetail->address }}</p>
							<br>
							<h2>Created At</h2>
							<p>{{ $usersDetail->created_at }}</p>
							<br>
						</div>
						<div role="tabpanel" class="tab-pane fade active in" id="tab_content33" aria-labelledby="profile-tab">
							@if($usersDetail->imagesUser)
								<img width="200px" src="{{ asset('users/') }}/<?php echo $usersDetail->imagesUser; ?>"/>
							@else
								<img width="200px" src="{{ asset('users/') }}/nopic.png">
							@endif
							<div class="x_content">
								<table class="" style="width:100%">
									<tr>
										<th style="width:15%;">
											<h4><b>Name</b></h4>
										</th>
										<td>
											<p>{{$usersDetail->fname}} {{$usersDetail->lname}}</p>
										</td>
									</tr>
									<tr>
										<th style="width:15%;">
											<h4><b>Email</b></h4>
										</th>
										<td>
											<p>{{ $usersDetail->email }}</p>
										</td>
									</tr>
									<tr>
										<th style="width:15%;">
											<h4><b>School</b></h4>
										</th>
										<td>
											<p>{{ $usersDetail->schoolName }}</p>
										</td>
									</tr>
									<tr>
										<th style="width:15%;">
											<h4><b>Mobile</b></h4>
										</th>
										<td>
											<p>{{ $usersDetail->mobile }}</p>
										</td>
									</tr>
									<tr>
										<th style="width:15%;">
											<h4><b>Status</b></h4>
										</th>
										<td>
											<p><?php if($usersDetail->enabled==1){ echo "Enabled"; }else{ echo "Disabled"; } ?></p>
										</td>
									</tr>
								</table>
							</div>
						</div>
		        	</div>
		   		</div>
	    	</div>
	    </div>
	</div>
	<div class="clearfix"></div>
</div>
@endsection