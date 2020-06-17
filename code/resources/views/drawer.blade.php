@extends('layouts.app')
@section('content')

<div class="">
	@if(Session::has('deleteDrawerSuccess'))
		<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('deleteDrawerSuccess') }}</p>
		<?php Session::forget('deleteDrawerSuccess'); ?>
	@endif

	@if(Session::has('drawerAddSucc'))
		<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('drawerAddSucc') }}</p>
		<?php Session::forget('drawerAddSucc'); ?>
	@endif

  <div class="page-title">
	<div class="title_left">
		<h1>Drawer</h1>
	</div>
	<div class="title_right">
		<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
			<div class="input-group">
					<!-- <a href="{{url('/').'/delAboutListMain'}}/" class="btn btn-danger">Delete</a> -->
			</div>
		</div>
	</div>
  </div>
  <div class="clearfix"></div>
	<div class="row">
	    <div class="col-md-12 col-sm-12 col-xs-12">
		    <div class="x_panel">
				<div class="x_title">
					<h2></h2>
					<ul class="nav navbar-right panel_toolbox">
						<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="#">Settings 1</a>
								</li>
								<li><a href="#">Settings 2</a>
								</li>
							</ul>
						</li>
						<li><a class="close-link"><i class="fa fa-close"></i></a>
						</li>
					</ul>
					<div class="clearfix"></div>
				</div>
		        <div class="x_content">
		          	<div class="" role="tabpanel" data-example-id="togglable-tabs">
						<!-- <ul id="myTab1" class="nav nav-tabs bar_tabs right" role="tablist">
							<li role="presentation" class=""><a href="#tab_content11" id="home-tabb" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Add new</a></li>

							<li role="presentation" class="active"><a href="#tab_content33" role="tab" id="profile-tabb3" data-toggle="tab" aria-controls="profile" aria-expanded="false">List</a>
							</li>
						</ul> -->

						<div id="myTabContent2" class="tab-content">

							
							<div role="tabpanel" class="tab-pane fade" id="tab_content11" aria-labelledby="home-tab">

								<form enctype="multipart/form-data" method="POST" action="{{url('addRowDrawer') }}">
			                    {{ csrf_field() }}
 								
									<table class="form-table" id="customFields">
										<tr valign="top">
											<th scope="row"></th>
											<td>
												<input type="text" class="code" name="titleDrawerAdd[]" value="" placeholder="Title" required="required"/>
												<input type="file" class="code" name="ImageDrawerAdd[]" value="" required="required"/> &nbsp;


												<a href="javascript:void(0);" class="addCF">Add</a>

											</td>
										</tr>
									</table>

									<div class="form-group">
										<div class="col-md-8 col-sm-8 col-xs-8">
											<input type="submit" class="btn btn-success" value="submit">
										</div>
									</div>

			                    </form>
							</div>

							<div role="tabpanel" class="tab-pane fade active in" id="tab_content33" aria-labelledby="profile-tab">
								<table id="datatable" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>Title</th>
											<th>Icon</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php $myLoop=0; ?>
										@foreach($drawerData as $drawerDatas)
										<form enctype="multipart/form-data" method="POST" action="{{url('upDrawerRow') }}">
										<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
											<tr>
												<div class="viewRowMain viewRowMain<?php echo $myLoop; ?>" rel="<?php echo $myLoop; ?>">

													<td class="viewTdMain viewTdMain<?php echo $myLoop; ?> viewTdMainTitle<?php echo $myLoop; ?>" rel="<?php echo $myLoop; ?>">{{ $drawerDatas->titleDrawer }}</td>

													<td style="background:#2A3F54;" class="viewTdMain viewTdMain<?php echo $myLoop; ?> viewTdMainTitle<?php echo $myLoop; ?>" rel="<?php echo $myLoop; ?>">

														<?php $imgDrawer=$drawerDatas->imageDrawer; ?>
														<img width="50px" src="{{ asset('images/drawer/') }}/<?php echo $imgDrawer; ?>"/>
													</td>
												</div>
												
												<div class="editRowMain<?php echo $myLoop; ?>" rel="<?php echo $myLoop; ?>">
													<td class="editTdMain editTdMain<?php echo $myLoop; ?>" rel="<?php echo $myLoop; ?>" style="display:none;">
														<input class="editTitleInput<?php echo $myLoop; ?>" type="text" value="{{ $drawerDatas->titleDrawer }}" name="editDrawerTitle">

														<div id="titleErr<?php echo $myLoop; ?>"></div>
													</td>

													<td class="editTdMain editTdMain<?php echo $myLoop; ?>" rel="<?php echo $myLoop; ?>" style="background:#73879C; display:none;">

														<div class="editIconImgDiv editIconImgDiv<?php echo $myLoop; ?>">
															<?php $imgDrawerEdit=$drawerDatas->imageDrawer; ?>
															<img width="50px" src="{{ asset('images/drawer/') }}/<?php echo $imgDrawerEdit; ?>"/>
															<img rel="<?php echo $myLoop; ?>" class="crossImg" src="{{ asset('images/deleteIcon.png') }}">
														</div>

														<input class="editIdInput<?php echo $myLoop; ?>" type="hidden" value="{{ $drawerDatas->idDrawer }}" name="editDrawerId">

														<input class="editIconHiddenInput<?php echo $myLoop; ?>" type="hidden" value="{{ $drawerDatas->imageDrawer }}" name="editDrawerIconOld">

														<input style="display:none;" class="editIconNewInput<?php echo $myLoop; ?>" type="file" value="" name="editDrawerIconNew">


														<div id="iconErr<?php echo $myLoop; ?>"></div>
													</td>
												</div>

												<td> 
													<div class="fa-hover col-md-12 col-sm-12 col-xs-12">
														<div class="actionVisible actionVisible<?php echo $myLoop; ?>" rel="<?php echo $myLoop; ?>">

															<div class="fa-hover col-md-3 col-sm-4 col-xs-12">
																<a class="actionEditBtn actionEditBtn<?php echo $myLoop; ?>" rel="<?php echo $myLoop; ?>" href="javascript:void(0);"><i class="fa fa-edit"></i></a>
															</div>

															<div class="fa-hover col-md-3 col-sm-4 col-xs-12">
																<a class="deleteAboutListClass actionDelBtn actionDelBtn<?php echo $myLoop; ?>" rel="<?php echo $myLoop; ?>" href="{{url('/').'/delDrawer'}}/<?php echo $drawerDatas->idDrawer; ?>"><i class="fa fa-trash-o"></i></a>
															</div>

														</div>

														<div class="actionEdit actionEdit<?php echo $myLoop; ?>" rel="<?php echo $myLoop; ?>" style="display:none;">

															<input type="submit" class="btn btn-success actionUpdateBtn actionUpdateBtn<?php echo $myLoop; ?>" rel="<?php echo $myLoop; ?>" value="Update" name="submit">



															<a class="btn btn-danger actionCancelBtn actionCancelBtn<?php echo $myLoop; ?>" rel="<?php echo $myLoop; ?>" href="javascript:void(0);">Cancel</a>

														</div>
													</div>
												</td>
											</tr>
										</form>
										<?php $myLoop++; ?>
										@endforeach
									</tbody>
								</table>
							</div>
			        	</div>
			   		</div>
		        </div>
		    </div>
	    </div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){

		$(".deleteAboutListClass").click(function(){
			var answer=confirm('Do you want to delete this row?');
			if(answer){ return true; } else { return false; }
		});


		$("#addRow").click(function(){
			$("#mainTest .newRowDivAppend").clone().appendTo(".mainAppendDiv");
		});


		$(".addCF").click(function(){
		$("#customFields").append('<tr valign="top"><th scope="row"></th><td><input type="text" class="code" name="titleDrawerAdd[]" value="" placeholder="Title" required="required" /><input type="file" class="code" name="ImageDrawerAdd[]" value="" required="required"/> &nbsp; <a href="javascript:void(0);" class="remCF">Remove</a></td></tr>');
		});
		$("#customFields").on('click','.remCF',function(){
	        $(this).parent().parent().remove();
	    });
	   
		$(".actionEditBtn").click(function(){
			var edtBtnRel=$(this).attr('rel');
			var hideTd = "viewTdMain"+edtBtnRel;
			var showTd = "editTdMain"+edtBtnRel;
			var hideBtnDiv = "actionVisible"+edtBtnRel;
			var showBtnDiv = "actionEdit"+edtBtnRel;
			$("."+showTd).show();
			$("."+showBtnDiv).show();
			$("."+hideTd).hide();
			$("."+hideBtnDiv).hide();
		});

		$(".actionCancelBtn").click(function(){
			var cnclBtnRel=$(this).attr('rel');
			var showTd = "viewTdMain"+cnclBtnRel;
			var hideTd = "editTdMain"+cnclBtnRel;
			var showBtnDiv = "actionVisible"+cnclBtnRel;
			var hideBtnDiv = "actionEdit"+cnclBtnRel;
			$("."+showTd).show();
			$("."+showBtnDiv).show();
			$("."+hideTd).hide();
			$("."+hideBtnDiv).hide();
		});


		$(".crossImg").click(function(){
			var crossImgRel=$(this).attr('rel');
			var imgDiv="editIconImgDiv"+crossImgRel;
			var imgHiddenInput="editIconHiddenInput"+crossImgRel;
			var imgNewInput="editIconNewInput"+crossImgRel;
			$("."+imgDiv).empty();
			$("."+imgHiddenInput).val('');
			$("."+imgNewInput).show();
		});


		$(".actionUpdateBtn").click(function(){
			var upBtnRel=$(this).attr('rel');
			var rowId = $(".editIdInput"+upBtnRel).val();
			var rowTitle = $(".editTitleInput"+upBtnRel).val();
			var rowOldImage = $(".editIconHiddenInput"+upBtnRel).val();
			var rowNewImage = $(".editIconNewInput"+upBtnRel).val();


			$("#titleErr"+upBtnRel).empty();
			$("#iconErr"+upBtnRel).empty();


			if(rowTitle==""){
				$("#titleErr"+upBtnRel).append("Please Add Title");
				return false;
			}else{
				$("#titleErr"+upBtnRel).empty();
				if(rowOldImage=="" && rowNewImage==""){
					$("#iconErr"+upBtnRel).append("Please Add Icon");
					return false;
				}else{
					$("#titleErr"+upBtnRel).empty();
					$("#iconErr"+upBtnRel).empty();
					return true;
				}
			}
		});


		// 	var rowIdAjax, rowTitleAjax, rowFromAjax, rowToAjax, rowOldImageAjax, rowNewImageAjax, token, url, data;
	 //        token = $('input[name=_token]').val();

	 //        rowIdAjax = rowId;
	 //        rowTitleAjax = rowTitle;
	 //        rowOldImageAjax = rowOldImage;
	 //        rowNewImageAjax = rowNewImage;

	 //        url = '<?php echo url("/"); ?>/upDrawerRow';
	 //        data = {
	 //        	rowIdAjax: rowIdAjax,
	 //        	rowTitleAjax: rowTitleAjax,
	 //        	rowOldImageAjax: rowOldImageAjax,
	 //        	rowNewImageAjax: rowNewImageAjax
	 //        };


	 //        $.ajax({
	 //            url: url,
	 //            headers: {'X-CSRF-TOKEN': token},
	 //            data: data,
	 //            type: 'POST',
	 //            datatype: 'JSON',
	 //            success: function (resp) {

	 //                $.each(resp.updateDrawerResult, function (key, value) {

	 //                //--Content and Id
	 //                	var rowIdRes = value.rowIdDrawer;
	 //                	var rowTitleRes = value.rowTitleDrawer;
	 //                	var rowImgRes = value.rowImgDrawer;

	 //                	alert(rowImgRes);


	 //                //--Classes
		// 				var showTd = "viewTdMain"+upBtnRel;
		// 				var hideTd = "editTdMain"+upBtnRel;
		// 				var showBtnDiv = "actionVisible"+upBtnRel;
		// 				var hideBtnDiv = "actionEdit"+upBtnRel;
		// 				var appendTitleTd = "viewTdMainTitle"+upBtnRel;
		// 				var editTitleInput = "editTitleInput"+upBtnRel;

		// 				var editImgPreview = "editIconImgDiv"+upBtnRel;

		// 				$("."+appendTitleTd).empty();
		// 				$('.'+appendTitleTd).append(rowTitleRes);
		// 				$("."+editTitleInput).val('');
		// 				$("."+editTitleInput).val(rowTitleRes);

		// 				$("."+editImgPreview).empty();
		// 				$("."+editImgPreview).append('<img src="http://02pg.com/aapa/apaaAdmin/public/images/drawer/'+rowImgRes+'">');

		// 			//--Show/Hide
		// 				$("."+showTd).show();
		// 				$("."+showBtnDiv).show();
		// 				$("."+hideTd).hide();
		// 				$("."+hideBtnDiv).hide();
	 //                });
	 //            }
	 //        });
		// });

	});
</script>
@endsection
