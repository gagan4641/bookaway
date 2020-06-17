<html>
    <body>
		<style>
			*{
				-webkit-box-sizing:border-box;
				-moz-box-sizing:border-box;
				box-sizing:border-box;
			}
			body, div, p{
				margin: 0;
				footer: 0;
			}
			a{
				text-decoration: none;
			}
		</style>
        <div style="color: #555555;width: auto; padding: 20px 20px; font-family:Arial, Helvetica, sans-serif; font-size:14px;background: #f6f6f6;">
			<div style="text-align: center;margin-bottom:40px;"><img src="" /></div>
			<p style="font-size: 14px;font-weight:600;margin-bottom: 25px;font-style:italic;">Hi <?php echo $firstname; ?>,</p>
			<p style="font-size: 14px;font-weight:600;margin-bottom: 25px;line-height: 24px;">You are receiving this email because we received a password reset request for your account.</p>
			<div style="margin-bottom: 25px;">
				<a href="<?php echo $message_content; ?>" style="background: #45d9fd;color:#fff;display:block;font-size: 14px; font-weight: 600;padding: 15px 25px;width:35%;text-align:center;text-decoration: none; margin:0 auto;">Reset Password</a>
			</div>

			<p style="font-size: 14px;font-weight:600;line-height: 24px;">Thanks,<br />Bookaway Team</p>	
        </div>
    </body>    
</html>

