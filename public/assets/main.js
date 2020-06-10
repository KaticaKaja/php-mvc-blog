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
    // window.addEventListener("beforeunload", function (e) {
    //     $.ajax({
    //         url: BASE_URL+'users/logout',
    //         type: 'POST',
    //         async: false,
    //         timeout: 2
    //     });
    // })
    $("#addSubmit").click(sendpost);
    $("#editSubmit").click(sendpost);
    $("#loginSubmit").click(login);
    $("#regUser").click(register);
    $("#postPhoto").change(photoChange);
    $("#register .form-group input").blur(clientValidation);

    function photoChange(){
        var image = document.getElementById('postPhoto').files[0];
        if(image){
            var reader = new FileReader();
            reader.onload = function(e){
               $(".previewphoto").attr('src', e.target.result);
            }
            reader.readAsDataURL(image);
        }

    }

    function clientValidation(e){
        var inputField = e.target.name;
        var inputFieldValue = e.target.value;
        var nameReg = /^([A-Z][a-z]{2,}(\s|\-)?)*$/;
        var password = $("input[name='password']").val();
        var email = $("input[name='email']").val();
        var emailReg = /^(\w+(\.|\-)?)*\@\w+(\.com|\.rs)|\.ict.edu.rs$/;
        var lName = $("input[name='lastName']").val();
        var confPasswd = $("input[name='confPasswd']").val();
        var fName = $("input[name='firstName']").val();
        
        if(inputFieldValue == ""){
            $(`.${inputField}`).html('This field is required');
        }
        else{
            if(inputField == "email"){
                if(!emailReg.test(email)){
                    $('.email').html('Bad email format.');
                }
                else{
                    $('.email').html('');
                }
            }
            if(inputField == "firstName"){
                if(!nameReg.test(fName)){
                    $('.firstName').html('Bad format.');
                }
                else{
                    $('.firstName').html('');
                }
            }
            if(inputField == "lastName"){

                if(!nameReg.test(lName)){
                    $('.lastName').html('Bad format.');
                }
                else{
                    $('.lastName').html('');
                }

            }
            if(inputField == "password"){

                if(password.length < 6){
                    $('.password').html('Password must have 6 or more characters.');
                }
                else{
                    $('.password').html('');
                }
            }
            if(inputField == "confPasswd"){
                if(confPasswd !== password){
                    $('.confPasswd').html('Passwords do not match.');
                }
                else{
                    $('.confPasswd').html('');
                }
            }
        }
        
    }
    
    function register(event){
        event.preventDefault();
        var flag = 0;        
        var spanErros = document.querySelectorAll('.text-danger');
        var inputs = document.querySelectorAll("#register .form-group input");
        spanErros.forEach(element => {
            if(element.innerText != ''){
                flag++;
            }
        });
        inputs.forEach(el => {
            if(el.value == ''){
                flag++;
            }
        });

        if(flag == 0){
            $.ajax({
                url: BASE_URL+`validation/register`,
                type: 'POST',
                dataType: 'json',
                data: {
                    firstName: $("input[name='firstName']").val(),
                    lastName: $("input[name='lastName']").val(),
                    email: $("input[name='email']").val(),
                    password: $("input[name='password']").val(),
                    confPasswd: $("input[name='confPasswd']").val(),
                },
                success: function(response){
                   if(response.msg == "success"){
                       window.location = BASE_URL+"users/login";
                   }
                    
                },
                error: function(xhr, status, error){
                    console.log(xhr.status);
                    console.log(error);
                    var errors = xhr.responseJSON;
                    if(errors.firstName_err){
                        $('.firstName').html(errors.firstName_err);
                    }
                    if(errors.lastName_err){
                        $('.lastName').html(errors.lastName_err);
                    }
                    if(errors.email_err){
                        $('.email').html(errors.email_err);
                    }
                    if(errors.password_err){
                        $('.password').html(errors.firstName_err);
                    }
                    if(errors.confPasswd_err){
                        $('.confPasswd').html(errors.confPasswd_err);
                    }
                }
            });
        }
        
    }



    function login(event){
        event.preventDefault();

        var email = $("input[name='email']").val();
        var password = $("input[name='password']").val();

        $.ajax({
            url: BASE_URL+`validation/login`,
            type: 'POST',
            dataType: 'json',
            data: {
              email : email,
              password : password  
            },
            success: function(data) {
                if(data.msg == 'success'){
                    window.location = BASE_URL+"posts";
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr);
                console.log(error);
                var errormsg = xhr.responseJSON;
                $(".email_err").text(errormsg.email_err);
                $(".password_err").text(errormsg.password_err);
            }
        });

    }

    function sendpost(event) {
        event.preventDefault();
        var button = event.target;
        var id = event.target.id;
        var postId = button.getAttribute('data-id');
        var endpoint = (id == 'editSubmit') ? `testedit/${postId}`: 'test';
        var title = $("input[name='title']").val();
        var categories = document.getElementById('category');
        var category = categories.options[categories.selectedIndex].value;
        var body = $("#postBody").val();
        var image = document.getElementById('postPhoto').files[0];
        if(image){
            var reader = new FileReader();
            reader.onload = function(e){
               $(".previewphoto").attr('src', e.target.result);
            }
            reader.readAsDataURL(image);
        }
        var podaciZaSlanje = new FormData();
        podaciZaSlanje.append('title', title);
        podaciZaSlanje.append('category', category);        
        podaciZaSlanje.append('body', body);
        podaciZaSlanje.append('image', image);

        $.ajax({
            url: BASE_URL+`posts/${endpoint}`,
            type: 'POST',
            data: podaciZaSlanje,
            contentType: false,
            processData: false,
            success: function(data) {
                if(data == 'success'){
                    window.location = BASE_URL+"posts";
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.status);
                console.log(error);
                var errormsg = xhr.responseJSON;
                $(".title_err").text(errormsg.title_err);
                $(".category_err").text(errormsg.category_err);
                $(".body_err").text(errormsg.body_err);
                $(".img_err").text(errormsg.img_err);
            }
        });
    }
    
});
