// const BASE_URL = "http://restaurants-review.test/";
$(document).ready(function(){
    // alert('helou');
    $("#dugme").click(function(){
        $.ajax({
            url: BASE_URL+"home/change",
            type: 'GET',
            dataType: 'json',
            // data: {
            //     id : postForPublish.postId,
            //     publishValue : postForPublish.publishValue
            // },
            success: function(data){
                // data = JSON.parse(data);
                // if(data.msg == 'success'){
                //     window.location = BASE_URL + `admin`;
                // }
                // else{
    
                // }
                console.log('ovo je success: ',data);
                // var html = "";
                // data.forEach(post => {
                //     // console.log(post);
                //     html +=`<h2>${post.title}</h2>
                //     <p>${post.body}</p>`;                
                // });
                // $("#posts").html(html);
                
            },
            error: function(xhr, status, error){
                window.location = BASE_URL+"notfound";
                console.log(xhr.status);
                console.log(status);
                console.log(error);
            }
        });
    });

    $("#addSubmit").click(obrada);

    function obrada(event) {
        event.preventDefault();
        var title = $("input[name='title']").val();
        var categories = document.getElementById('category');
        var category = categories.options[categories.selectedIndex].value;
        var body = $("#postBody").val();
        var image = document.getElementById('postPhoto').files[0];
        var podaciZaSlanje = new FormData();
        podaciZaSlanje.append('title', title);
        podaciZaSlanje.append('category', category);        
        podaciZaSlanje.append('body', body);
        podaciZaSlanje.append('image', image);
        // console.log(podaciZaSlanje);
        $.ajax({
            url: BASE_URL+"posts/test",
            type: 'POST',
            // dataType: 'json', //zbog slike ne moze
            data: podaciZaSlanje,
            contentType: false,
            processData: false,
            success: function(data) {
                if(data == 'success'){
                    window.location = BASE_URL+"posts";
                }
                
                // console.log(data);
            },
            error: function(xhr, status, error) {
                // window.location = BASE_URL+"errorcontroller/badrequest";
                console.log(xhr.status);
                console.log(error);
                var errormsg = xhr.responseJSON;
                console.log(errormsg);
                // $(".photoErr").text(errormsg.error);
                // $(".photoErr").show();
            }
        });
    }
    
});
