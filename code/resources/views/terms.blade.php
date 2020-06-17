<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{ $terms->title }}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  
  <style>
  h3   {color: #504F57;
  text-align:center;
  padding:10px;}
  h4   {color: #504F57;}
  p   {color: #504F57;
  text-align:justify}
  </style>
</head>
<body>

<div class="container" style="margin-bottom: 35px;">
  <h3 >{{ $terms->title }}</h3>
  <br>
  <?php echo $terms->content; ?>
</body>
</html>
