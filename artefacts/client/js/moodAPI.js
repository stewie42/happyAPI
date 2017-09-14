var moodAPI = (function () {
		var host	= 'http://0.0.0.0:8084';
		var hostWithPrefix	= `${host}/api/v1`;
		var userId,token, lastMood, name;

		function init() {
			if(localStorage.getItem('jwt_token') == null)
				return Promise.resolve(undefined);
			
			this.token = JSON.parse(localStorage.getItem('jwt_token')).token;
			this.userId = JSON.parse(localStorage.getItem('jwt_token')).userId;
			this.name = JSON.parse(localStorage.getItem('jwt_token')).name;

        	return fetch(hostWithPrefix, { 
					method: 'HEAD',
					headers: {'Authorization': `Bearer ${this.token}`}})
				.then(response => response.ok);
		 }

		function login(username, password) {
			return fetch(`${host}/login_check`, { 
					method: 'POST', 
					headers: { 'Content-Type': 'application/x-www-form-urlencoded'}, 
					body: `_username=${username}&_password=${password}`})
				.then(response => { if(response.ok) return response.json();})
				.then(json => json);
		}

		function postMood(mood,explanation) {

			return fetch(`${hostWithPrefix}/moods`, { 
					method: 'POST',
					headers: {'Authorization': `Bearer ${this.token}`,
							 'Content-Type': 'application/ld+json'},
					body: JSON.stringify({
							'mood': parseInt(mood),
				 	  	   'explanation': explanation,
			  		       'user': `${hostWithPrefix}/users/${userId}`
					})})
				.then(response => response.json())
				.then(json => { if(json.ok == false) { lastMood = json.lastId; } 
									return json;});
		}
		
		function updateMood(mood,explanation) {

			return fetch(`${hostWithPrefix}/moods/${lastMood}`, { 
					method: 'PUT',
					headers: {'Authorization': `Bearer ${token}`,
							 'Content-Type': 'application/ld+json'},
					body: JSON.stringify({
							'mood': parseInt(mood),
				 	  	   'explanation': explanation,
			  		       'user': `${hostWithPrefix}/users/${userId}`
					})})
				.then(response => response.json())
				.then(json => json);
		}

		function deleteMood(id){
			 return fetch(`${hostWithPrefix}/moods/${id}`, { 
					method: 'DELETE',
					headers: {'Authorization': `Bearer ${token}`,
							 'Content-Type': 'application/ld+json'}})
				.then(response => response)
		}

		function getLastMood(){
			return fetch(`${hostWithPrefix}/users/${userId}/last`, { 
				method: 'GET',
				headers: {'Authorization': `Bearer ${token}`,
						 'Content-Type': 'application/ld+json'}})
			.then(response => response.json())
			.then(json => json);
		}

		function deleteLastMood(){
			return getLastMood()
					.then(result => Promise.all([result, deleteMood(result.id)]))
					.then(results => results[1] );
		}

		function getUserName() {
			return fetch(`${hostWithPrefix}/users/${userId}/last`, { 
				method: 'GET',
				headers: {'Authorization': `Bearer ${token}`,
						 'Content-Type': 'application/ld+json'}})
			.then(response => response.json())
			.then(json => json);
		}

		return {
				init:		init,
				login:		login,
				postMood:	postMood,
				updateMood: updateMood,
				deleteMood: deleteMood,
				deleteLastMood: deleteLastMood,


				set token (value) { token = value; },
        		get token () { return token; },
				
				set userId (value) { userId = value; },
        		get userId () { return userId; },

				set lastMood (value) { this.lastMood = value; },
        		get lastMood () { return lastMood; }
		}
})();
