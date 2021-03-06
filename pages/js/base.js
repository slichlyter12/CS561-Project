//show the toast
function openToast(str){
	$('#toast').html(str);
	$('#toast').fadeIn(200);
	setTimeout(function(){
		$('#toast').fadeOut(200);
	},3000);
};

function showError(str) {
	$('#toast').removeClass('info').addClass('error');
	openToast(str);
}

function showInfo(str) {
	$('#toast').removeClass('error').addClass('info');
	openToast(str);
}

//get get parameter
function getGetParameter(name) { 
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
	var r = window.location.search.substr(1).match(reg); 
	if (r != null) return unescape(r[2]); 
	return null; 
} 

//	storage value in session
function setSession(key, value) {
	if(typeof(Storage) !== "undefined") {
		sessionStorage[key] = value;
	}
	else {
		alert("Your browser doesn't support web storage");
	}
}

//	get value in session
function getSession(key) {
	if(typeof(Storage) !== "undefined") {
		return sessionStorage[key];
	}
	else {
		alert("Your browser doesn't support web storage");
		return null;
	}
}

function redirectToTokenPage() {
  var currentURL = window.location.href;
  window.location.href = 'https://prometheus.eecs.oregonstate.edu/token?asid=6077469967005125&then=' + currentURL;
}

/**
 * get parameter value from URL
 * @param {String} key
 */
function getUrlParam(key) {
    var reg = new RegExp("(^|&)" + key + "=([^&]*)(&|$)"); // construct regexp object
    var r = window.location.search.substr(1).match(reg);  //  matach target parameter
    if (r != null) return unescape(r[2]); return null;
}
