window.onload = function (){
			moodAPI.init().then(result => {
			if(!result){
				window.location.replace('login.html');
		}
		});
	}

window.addEventListener("DOMContentLoaded", function(event) {
    document.getElementById('gobtn').addEventListener("click", vote);
    document.getElementById('deletebtn').addEventListener("click", deleteMood);
    document.getElementById('updatebtn').addEventListener("click", updateMood);
    document.getElementById('rejectbtn').addEventListener("click", function(){ toggleButtons(); document.getElementById("message").style.display = 'none'});
})

function vote(){
	var smiley	= document.getElementsByClassName("focus")[0];
	if(!smiley){
			var mes = document.getElementById("message");
			mes.innerHTML = "Keine Bewertung ausgewählt!";
			mes.style.display = 'block';
			return;
	}
	var id		= smiley.getAttribute("id");
	var mood	= id.substring(id.length-1, id.length);

	var explanation = document.getElementById("exp").value;

	moodAPI.postMood(mood,explanation)
				.then(result => { if(result.ok == false) { duplicateMood(); } 
							  	  else updateMessage("Bewertung abgegeben!", true);});
}

function duplicateMood(){
	updateMessage("Voting für heute bereits abgegeben! <br> Überschreiben?", false)
	toggleButtons();
}

function updateMood(){
	var smiley	= document.getElementsByClassName("focus")[0];
	var index	= smiley.getAttribute("id");
	var mood	= index.substring(index.length-1, index.length);

	var explanation = document.getElementById("exp").value;

	moodAPI.updateMood(mood, explanation).then( () => { updateMessage("Erfolgreich aktualisiert!", true); toggleButtons();});
}

function deleteMood(){
	moodAPI.deleteLastMood().then(result => { if(result.ok == false) updateMessage("Löschen nicht mehr möglich", false);
											   else updateMessage("Löschen erfolgreich", true); });
}

function setFocus(obj){
	var id = obj.getAttribute("id");
	var index = id.substring(id.length-1,id.length);

	var targetContainer = document.getElementById(`container${index}`);
	targetContainer.style.background = 'tomato';
	for(var i=1; i<=5;i++){
		if(i!=index){
			document.getElementById(`container${i}`).style.background = "white";
			if(document.getElementById(`container${i}`).classList.contains('focus'))
				document.getElementById(`container${i}`).classList.remove('focus');
		}
	}
	targetContainer.classList.add('focus');
}

function updateMessage(message, success){
	if(typeof message !== 'string')
			return;

	var messageDiv = document.getElementById("message");
	if(success == true)
		messageDiv.style.color = 'green';
	else
		messageDiv.style.color = 'red';

	messageDiv.innerHTML = message;
	messageDiv.style.display = 'block';
}

function toggleButtons(){
	var btnArr = [];
	var btns = document.getElementsByClassName("hdbtn");

	for(var i=0; i<btns.length;i++)
				btnArr.push(btns[i]);

	btnArr.push(document.getElementById("gobtn"));
	btnArr.push(document.getElementById("deletebtn"));

	for(var i=0; i<btnArr.length; i++){
		btnArr[i].style.display = window.getComputedStyle(btnArr[i],null).getPropertyValue("display") == "inline-block" ? "none" : "inline-block";
	}
}
