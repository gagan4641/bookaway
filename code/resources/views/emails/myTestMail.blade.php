<h3>Hi <?php echo $data['name']; ?>,</h3> 
<p>Welcome to APAA </p>

<p>Id - <?php echo $data['id']; ?></p>
<p>Password - <?php echo eci_decrypt($data['password']); ?></p>