const BASE_URL = "http://restaurants-review.test/";
$(document).ready(function(){
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
    
});
