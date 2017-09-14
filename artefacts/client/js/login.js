window.addEventListener("DOMContentLoaded",function(){
	document.getElementById("loginform").addEventListener("submit",function(ev){
		ev.preventDefault();
		var username = document.getElementById("usernamenpt").value;
		var password = document.getElementById("passwordnpt").value;
		
		moodAPI.login(username, password).then(response => { 
		if(response){
			localStorage.setItem('jwt_token',JSON.stringify({'token': response.token, 'userId': response.data.userId}));
			if(document.referrer != '')
					window.location.replace(document.referrer);
		}
		else {
			var el = document.getElementById("badLogin");
			el.innerHTML = "Login fehlgeschlagen";
			el.style.display = 'block';
		}
		});

	});
});

