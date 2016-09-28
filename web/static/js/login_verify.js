// JavaScript Document
$(function(){
	$("#login-form").submit(function(){
		
		var $ok=true;
		var $username=$.trim($("#username").val());
		var $pass=$.trim($("#pass").val());
		if($pass==""){
				$("#pass").val("").attr("placeholder","密码不能为空");
				$ok=false;
		}
		
		if($(this).hasClass("register")){
			
			var $nicename=$.trim($("#nicename").val());
			var $pass2=$.trim($("#pass2").val());
			var $email=$.trim($("#email").val());
			
			if($nicename==""||$username==""||$pass2==""||$email=="")
				$ok=false;
				
			var reg=/^[a-zA-Z_][a-zA-Z0-9_]{4,19}$/;
			if($username.length<5){
				$("#username").val("").attr("placeholder","至少由5个字符组成");
				$ok=false;
			}else{
				if(!reg.test($username)){
					$("#username").val("").attr("placeholder","由数字、字母、下划线组成。不能以数字开头");
					$ok=false;
				}
			}
			
			if($pass2!=$pass){
				
				$("#pass2").val("").attr("placeholder","两次密码不匹配");
				$ok=false;
			}
			
			reg=/^\w{3,}@\w+(\.\w+)+$/;
			if(!reg.test($email)){
				$("#email").val("").attr("placeholder","格式不正确");
				$ok=false;
			}		
		}else{
			
			if($username==""){
				$("#username").val("");
				$ok=false;
			}
			
			var reg=/^[a-zA-Z_][a-zA-Z0-9_]{4,16}$/;
			if(!reg.test($username)){
								
				reg=/^\w{3,}@\w+(\.\w+)+$/;
				
				if(!reg.test($username)){
					$("#username").val("").attr("placeholder","格式不正确");					
					$ok=false;
				}
			}
		}
		return $ok;
	});
})