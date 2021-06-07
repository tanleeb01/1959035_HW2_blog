let btnClicked = false;
//hamburger
$(document).ready(function(){
	$('.navbar-toggler').click(function(){
        btnClicked = !btnClicked;
		$('#nav-icon3').toggleClass('open');    
	});
});

let prevScrollpos = window.pageYOffset;
window.onscroll = function() {
  let currentScrollPos = window.pageYOffset;
  if (prevScrollpos > currentScrollPos) {
    document.querySelector(".navbar").style.top="0";
} else {
  if (btnClicked){
    $('#nav-icon3').click();
  }
    document.querySelector(".navbar").style.top="-20vh";
}
  prevScrollpos = currentScrollPos;
}

let session = JSON.parse($.ajax({
    type: 'GET',       
    url: "func/ajax/ajax_session_list.php",
    dataType:'json',
    async:false,
    success: function(data) {
        return data;
    }
}).responseText);

document.querySelector("#search_form").addEventListener("submit",function(event){
    if (this.querySelector("input").value=='')event.preventDefault();
    this.querySelector("input").placeholder='No blank please';
})