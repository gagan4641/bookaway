<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bookaway</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="{{ asset('adminApaa/data/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('adminApaa/data/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('adminApaa/data/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ asset('adminApaa/data/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
  
    <!-- bootstrap-progressbar -->
    <link href="{{ asset('adminApaa/data/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{ asset('adminApaa/data/vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('adminApaa/data/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('adminApaa/data/build/css/custom.min.css') }}" rel="stylesheet">

    <link href="{{ asset('adminApaa/css/maps/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

    <script src="{{ asset('pickTime/timedropper.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('pickTime/timedropper.css') }}"> 
    
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body class="nav-md">
  <div id="app">
    <div class="container body">
      <div class="main_container">
          <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                  <a href="{{url('/')}}" class="site_title"><i class="fa fa-paw"></i> <span>BOOKAWAY</span></a>
                </div>

                <div class="clearfix"></div>
                <!-- menu profile quick info -->
                <div class="profile clearfix">
                  <div class="profile_pic">
                    <img src="{{ asset('adminApaa/images/img.jpg') }}" alt="..." class="img-circle profile_img">
                  </div>
                  <div class="profile_info">
                    <span>Welcome,</span>
                    <h2>{{ Auth::user()->fname }} {{ Auth::user()->lname }}</h2>
                  </div>
                </div>
                <!-- /menu profile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                  <div class="menu_section">
                    <h3>General</h3>
                    <ul class="nav side-menu">

                      <li><a href="{{url('/').'/userList'}}"><i class="fa fa-users"></i> User Management</a></li>

                      <li>
                        <a><i class="fa fa-sitemap"></i> School Management <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="{{url('/').'/schools'}}">Schools</a></li>
                          <li><a href="{{url('/').'/addSchool'}}">Add School</a></li>
                          <li><a href="{{url('/').'/assignSubjects'}}">Assign Subjects</a></li>
                        </ul>
                      </li>

                      <li>
                        <a><i class="fa fa-sitemap"></i> Subject Management <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="{{url('/').'/subjects'}}">Subjects</a></li>
                          <li><a href="{{url('/').'/addSubject'}}">Add Subject</a></li>
                        </ul>
                      </li>
                      <li>
                        <a><i class="fa fa-sitemap"></i> Book Management <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="{{url('/').'/books'}}">Books</a></li>
                        </ul>
                      </li>
                      <li>
                        <a><i class="fa fa-sitemap"></i> Reports <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="{{url('/').'/payments'}}">Payments</a></li>
                          <li><a href="{{url('/').'/changeFees'}}">Change Fees</a></li>
                        </ul>
                      </li>

                      <li><a href="{{url('/').'/termsConditions'}}"><i class="fa fa-users"></i> Terms and Conditions</a></li>

                    </ul>
                  </div>
                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                  <a data-toggle="tooltip" data-placement="top" title="Settings">
                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                  </a>
                  <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                    <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                  </a>
                  <a data-toggle="tooltip" data-placement="top" title="Lock">
                    <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                  </a>
                  <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                  </a>
                </div>
                <!-- /menu footer buttons -->
              </div>
            </div>
            <!-- top navigation -->
            <div class="top_nav">
              <div class="nav_menu">
                <nav>
                  <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                  </div>
                  <ul class="nav navbar-nav navbar-right">
                    @if (Auth::guest())
                      <li class="">
                        <a href="javascript:;" class="user-profile">
                          <img src="images/img.jpg" alt="">Login
                          <span class=" fa fa-angle-down"></span>
                        </a>
                      </li>
                    @else
                      <li class="">
                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{{ Auth::user()->fname }} {{ Auth::user()->lname }}
                          <span class=" fa fa-angle-down"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-usermenu pull-right">
                          <li>
                            <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();"> Logout </a>
                          </li>
                        </ul>
                      </li>
                      
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          {{ csrf_field() }}
                      </form>
                    @endif
                  </ul>
                </nav>
              </div>
            </div>
            <!-- /top navigation -->
          <div class="right_col" role="main">
            <div class="innerDivMain">

        @yield('content')
              </div>
          </div>
          <footer>
              <div class="pull-right">
              Bookaway - admin panel by <a href="http://codecomposer.co.in/">Code Composers</a>
              </div>
              <div class="clearfix"></div>
          </footer> 
        </div>
      </div>
    </div>
  </div>
  <!-- jQuery -->
  <script src="{{ asset('adminApaa/data/vendors/jquery/dist/jquery.min.js') }}"></script>
  <!-- Bootstrap -->
  <script src="{{ asset('adminApaa/data/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
  <!-- FastClick -->
  <script src="{{ asset('adminApaa/data/vendors/fastclick/lib/fastclick.js') }}"></script>
  <!-- NProgress -->
  <script src="{{ asset('adminApaa/data/vendors/nprogress/nprogress.js') }}"></script>
  <!-- Chart.js -->
  <script src="{{ asset('adminApaa/data/vendors/Chart.js/dist/Chart.min.js') }}"></script>
  <!-- gauge.js -->
  <script src="{{ asset('adminApaa/data/vendors/gauge.js/dist/gauge.min.js') }}"></script>
  <!-- bootstrap-progressbar -->
  <script src="{{ asset('adminApaa/data/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
  <!-- iCheck -->
  <script src="{{ asset('adminApaa/data/vendors/iCheck/icheck.min.js') }}"></script>
  <!-- Skycons -->
  <script src="{{ asset('adminApaa/data/vendors/skycons/skycons.js') }}"></script>
  <!-- Flot -->
  <script src="{{ asset('adminApaa/data/vendors/Flot/jquery.flot.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/Flot/jquery.flot.pie.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/Flot/jquery.flot.time.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/Flot/jquery.flot.stack.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/Flot/jquery.flot.resize.js') }}"></script>
  <!-- Flot plugins -->
  <script src="{{ asset('adminApaa/data/vendors/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/flot.curvedlines/curvedLines.js') }}"></script>
  <!-- DateJS -->
  <script src="{{ asset('adminApaa/data/vendors/DateJS/build/date.js') }}"></script>
  <!-- JQVMap -->
  <script src="{{ asset('adminApaa/data/vendors/jqvmap/dist/jquery.vmap.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="{{ asset('adminApaa/data/vendors/moment/min/moment.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

  <!-- Custom Theme Scripts -->
  <script src="{{ asset('adminApaa/data/build/js/custom.min.js') }}"></script>
  <script src="{{ asset('adminApaa/js/datepicker/daterangepicker.js') }}"></script>
  <script src="{{ asset('adminApaa/js/moment/moment.min.js') }}"></script>


  <!-- Tables -->
  <script src="{{ asset('adminApaa/data/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/jszip/dist/jszip.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/pdfmake/build/pdfmake.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/pdfmake/build/vfs_fonts.js') }}"></script>
  <!-- Tables -->


  <!-- bootstrap-wysiwyg -->
  <script src="{{ asset('adminApaa/data/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/jquery.hotkeys/jquery.hotkeys.js') }}"></script>
  <script src="{{ asset('adminApaa/data/vendors/google-code-prettify/src/prettify.js') }}"></script>
  <!-- jQuery Tags Input -->
  <script src="{{ asset('adminApaa/data/vendors/jquery.tagsinput/src/jquery.tagsinput.js') }}"></script>
  <!-- Switchery -->
  <script src="{{ asset('adminApaa/data/vendors/switchery/dist/switchery.min.js') }}"></script>
  <!-- Select2 -->
  <script src="{{ asset('adminApaa/data/vendors/select2/dist/js/select2.full.min.js') }}"></script>
  <!-- Parsley -->
  <script src="{{ asset('adminApaa/data/vendors/parsleyjs/dist/parsley.min.js') }}"></script>
  <!-- Autosize -->
  <script src="{{ asset('adminApaa/data/vendors/autosize/dist/autosize.min.js') }}"></script>
  <!-- jQuery autocomplete -->
  <script src="{{ asset('adminApaa/data/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js') }}"></script>
  <!-- starrr -->
  <script src="{{ asset('adminApaa/data/vendors/starrr/dist/starrr.js') }}"></script>
  <!-- Custom Theme Scripts -->

  <!-- Scripts 
  <script src="{{ asset('js/app.js') }}"></script> -->
</body>

<script type="text/javascript">
  $(document).ready(function(){

    $("#addExcTripButtonId").click(function(){
      $("#addExcTripButtonId").hide();
      $("#addExcTripFormId").show();
    });

    $("#cancelExcTripFormId").click(function(){
      $("#addExcTripButtonId").show();
      $("#addExcTripFormId").hide();
    });

    $(".delExcTripCrossClass").click(function(){
      var answer=confirm('Do you want to delete this page?');
      if(answer){ return true; } else { return false; }
    });


    $("#addProgMainPageButtonId").click(function(){
      $("#addProgMainPageButtonId").hide();
      $("#addProgMainPageFormId").show();
    });

    $("#cancelProgMainPageFormId").click(function(){
      $("#addProgMainPageButtonId").show();
      $("#addProgMainPageFormId").hide();
    });

    $(".delProgMainPageCrossClass").click(function(){
      var answer=confirm('Do you want to delete this page?');
      if(answer){ return true; } else { return false; }
    });


    $("#addFoldButtonId").click(function(){
      $("#addFoldButtonId").hide();
      $("#addFoldFormId").show();
    });

    $("#cancelFoldFormId").click(function(){
      $("#addFoldButtonId").show();
      $("#addFoldFormId").hide();
    });

    $(".delFolderCrossClass").click(function(){
      var answer=confirm('Do you want to delete this row?');
      if(answer){ return true; } else { return false; }
    });

  });
</script>


</html>




