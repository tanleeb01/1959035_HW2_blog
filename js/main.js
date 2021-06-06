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