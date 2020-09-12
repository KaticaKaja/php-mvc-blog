const BASE_URL = "http://techtalk.test/";
$(document).ready(function () {
    $('#myTab a').click(function(){
        // console.log($(this).prop('href'));
    });
    tinymce.init({
        selector: '#postBody',
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        },
        height: 300,
        plugins: "advlist anchor autolink codesample fullscreen help image imagetools lists link media noneditable preview searchreplace table template visualblocks wordcount",
        toolbar: "insertfile a11ycheck undo redo | bold italic | forecolor backcolor | template codesample | alignleft aligncenter alignright alignjustify | bullist numlist | link tinydrive"
    });

        var ratedIndex = -1;
        function resetStarColors(){
            $('.fa-star').css('color','black');
        }
        $('.fa-star').mouseover(function(){
            resetStarColors(); 
            var currentStar = parseInt($(this).data('index'));
            for(var i=0; i<=currentStar; i++){
                $('.fa-star:eq('+i+')').css('color', '#007bff');
            }
        });

        $('.fa-star').mouseleave(function(){
            resetStarColors();
            if(ratedIndex!=-1){
                for(var i=0; i<=ratedIndex; i++){
                    $('.fa-star:eq('+i+')').css('color', '#007bff');
                }
            }
        });

        $('.fa-star').on('click', function(){
            ratedIndex = parseInt($(this).data('index'));
            ratePost(ratedIndex);
        });
        $(document).on('click','#catTag',function(e){
            window.location=BASE_URL;
        });

        $(document).on('click','.category', function(e){
            e.preventDefault();
            var catId = parseInt($(this).data('catid'));
            var catName = ($(this)).data('catname');
            $.ajax({
                url: BASE_URL+"home/filterpagination",
                type: 'POST',
                data: {
                    categoryId : catId
                },
                success: function(response){ 
                    response = JSON.parse(response);
                    var html = "";
                    var htmlPages = "";
                    response.posts.forEach(post => {
                        d = new Date (post.created_at);
                        months = [
                            'January',
                            'February',
                            'March',
                            'April',
                            'May',
                            'June',
                            'July',
                            'August',
                            'September',
                            'October',
                            'November',
                            'December'
                          ];
                        month = months[d.getMonth()];
                        year = d.getFullYear();
                       html+=`<div class="col-md-6 text-lg-left">
                                <div class="post">
                                    <a class="post-img" href="${BASE_URL}posts/show/${post.id}"><img height="350" src="${post.img_src}" alt="${post.title}">
                                    <div class="post-body">
                                        <div class="post-category">
                                            <h5 class="post-category">${post.name}</h5>
                                        </div>
                                        <h3 class="post-title">${post.title}</h3>
                                        <ul class="post-meta">
                                            <li>${post.firstName} &#8226;</li>
                                            <li>${month} ${d.getDate()}, ${year} &#8226; ${d.getHours()}:${(d.getMinutes()<10?'0':'') + d.getMinutes()}</li>
                                        </ul>
                                    </div>
                                    </a>
                                </div>
                            </div>`; 
                    });
                    htmlPages=`<li class="page-item left uja"><a class="page-link" href="#">&lt;</a></li>`;
                                for(let page = 1; page <= response.pages; page++){
                                    if(page==1){
                                        htmlPages+=`<li class="page-item page active"><a class="page-link" href="#">${page}</a></li>`;
                                    }
                                    else{
                                        htmlPages+=`<li class="page-item page"><a class="page-link" href="#">${page}</a></li>`;
                                    }
                                    
                                }
                        
                    htmlPages+=`<li class="page-item right"><a class="page-link" href="#">&gt;</a></li>`
                    $('.pagination').hide();
                    $("#categoryTag").html(`<button type="button" class="btn btn-outline-primary" id="catTag">${catName} <i class="fas fa-times"></i></button>`);
                    $('#posts').html(html);
                    //$('.pagination').html(htmlPages);
                    
                },
                error: function(xhr, status, error){
                    console.log(xhr.error, xhr.status);
                }
    
            });
        })
        $(document).on('click', '.pagination li a', function(e){
            e.preventDefault();
            var allLinks = document.querySelectorAll('.pagination .page');
            if($(this).parent().hasClass('page')){
                $('.pagination li').removeClass('active');
                pagination(e.target.text);
                $(this).parent().addClass('active');
            }
            else if($(this).parent().hasClass('left')){
                allLinks.forEach(link=>{
                    if(link.classList.contains('active')){
                        link.classList.remove('active');
                        (link.firstChild.text != '1') ? (link.previousElementSibling.classList.add('active'), pagination(link.previousElementSibling.firstChild.text)) : link.classList.add('active');
                    }
                });   
            }
            else if($(this).parent().hasClass('right')){
                var allLinksLenght = allLinks.length;
               for(var link = 0; link < allLinksLenght; link++){
                     if(allLinks[link].classList.contains('active') && allLinks[link].nextElementSibling.classList.contains('page')){
                        allLinks[link].classList.remove('active');
                        allLinks[link].nextElementSibling.classList.add('active')
                        pagination(allLinks[link].nextElementSibling.firstChild.text);
                        break;
                     }
                }
            }
            
        });
        /*SEARCH*/
        $(document).on('keyup', '#search', function(){
            var searched = $("#search").val();
            if(searched == ''){
                $(".searched").hide();
                $('.main-content').show();
            }
            else{
                $('.main-content').hide();
                search(searched);
                $(".searched").show();
            }
        });

        // REGISTRACIJA
        // $(document).on('click', '#regUser', function(){
        //     addUser('users', 'register');
        // });


        $(document).on('click', '#addPost', function(){
            addPost();
        });
        $(document).on('click', '#addcategory', function(){
            addCategory();
        });
        $(document).on('click', '#edit', function(){
            var name = $(this).data('name');
            var id = $(this).data('id');
            var inputName = $("#catName").val(name);

            $(document).on('click', '#updateCategory', function(){
                editCategory(id, inputName[0].value);
            });
        });

        $(document).on('click','#adminAddU',function(){

            addUser('admin', 'adduser');
            
        });
        $(document).on('click', '.publish', function(){
            if($(this).text() == 'Publish'){
                $(this).html('Unpublish');
                $(this).css('font-size', '0.95rem');
                var data = {
                    'postId' : $(this).data('id'),
                    'publishValue' : 1
                };
                publishPost(data);
            }else{
                $(this).html('Publish');
                $(this).css('font-size', '1rem');
                var data = {
                    'postId' : $(this).data('id'),
                    'publishValue' : 0
                };
                publishPost(data);
            }
        });
});


function publishPost(postForPublish){
    $.ajax({
        url: BASE_URL+"admin/publish",
        type: 'POST',
        data: {
            id : postForPublish.postId,
            publishValue : postForPublish.publishValue
        },
        success: function(data){
            data = JSON.parse(data);
            if(data.msg == 'success'){
                window.location = BASE_URL + `admin`;
            }
            else{

            }
            
        },
        error: function(xhr, status, error){
            console.log(xhr.error, xhr.status);
        }
    });
}


function ratePost(ratedIndex){
    ratedValue = ratedIndex + 1;
    postId = parseInt($('.card h1').data('id'));

    $.ajax({
        url: BASE_URL+"posts/ratePost",
        type: 'POST',
        data: {
            ratedValue : ratedValue,
            id : postId
        },
        success: function(){ 
            window.location = BASE_URL + `posts/show/${postId}`;
        },
        error: function(xhr, status, error){
            console.log(xhr.error, xhr.status);
        }
    });

}

function editCategory(id,name){
    $.ajax({
        url: BASE_URL+"admin/editcategory",
        type: 'POST',
        data: {
            id : id,
            name : name
        },
        success: function(response){ 

            if(response == '' || response == undefined){
                window.location= BASE_URL+'admin';
                return;
            }
            console.log(response);
            response = JSON.parse(response);
            $(".errorsCat").html(`<span class="text-danger">${response}</span>`);
            
        },
        error: function(xhr, status, error){
            console.log(xhr.error, xhr.status);
        }
    });
}
   function addCategory(){
    $.ajax({
        url: BASE_URL+"admin/addcategory",
        type: 'POST',
        data: {
            name : $('input[name="name"]').val()
        },
        success: function(response){ 
            if(response == ''){
                
                window.location= BASE_URL+'admin';
                return;
            }
            response = JSON.parse(response);
            $(".errorsCatAdd").html(`<span class="text-danger">${response}</span>`);
            
        },
        error: function(xhr, status, error){
            console.log(xhr.error, xhr.status);
        }
    });
   }

    /*SEARCH*/
    function search(searchedThing){
        var html = '';
        $.ajax({
            url: BASE_URL+"home/search",
            type: 'POST',
            data: {
                content : searchedThing
            },
            dataType: 'json',
            success: function(data){
                html += `<div class="col-md-12">
                    <h3>You searched for: <span class="font-italic">${searchedThing}</span></h3>
                </div>
                <hr class="pt-2 pb-2">
                <div class="row mb-3">`;
        
                if(data.length == 0){
                    html+="<div class='col-md-12'><h5>There are no posts for this search. Try something else.</h5></div></div>";
                    $('.searched').html(html);
                }
                else{
                    data.forEach(post => {
                        console.log(post);
                        d = new Date (post.created_at);
                        months = [
                            'January',
                            'February',
                            'March',
                            'April',
                            'May',
                            'June',
                            'July',
                            'August',
                            'September',
                            'October',
                            'November',
                            'December'
                          ];
                        month = months[d.getMonth()];
                        year = d.getFullYear();
                        html+=`
                        <div class="col-md-6 text-lg-left">
                        <div class="post">
                            <a class="post-img" href="${BASE_URL}posts/show/${post.id}"><img height="350" src="${post.img_src}" alt="${post.title}">
                            <div class="post-body">
                                <div class="post-category">
                                    <h5 class="post-category">${post.name}</h5>
                                </div>
                                <h3 class="post-title">${post.title}</h3>
                                <ul class="post-meta">
                                    <li>${post.firstName} &#8226; </li>
                                    <li>${month} ${d.getDate()}, ${year} &#8226; ${d.getHours()}:${(d.getMinutes()<10?'0':'') + d.getMinutes()}</li>
                                </ul>
                            </div>
                            </a>
                        </div>
                    </div>`;
                    });
                    html += "</div>";
                    
                    $('.searched').html(html);
    
                }
                
            },
            error: function(xhr, status, error){
                console.log(xhr.responseText,status,error);
                if(xhr.status == 500){
                    window.location = window.location= BASE_URL+`errorcontroller/internalerror`;
                }
            }
        });
    }
    /*PAGINATION*/
    function pagination(page){
        $.ajax({
            url: BASE_URL+`home/page`,
            type: 'POST',
            data: {
                id : page
            },
            dataType: 'json',
            success: function(data){
               var html = "";
                data.forEach(post => {
                    d = new Date (post.created_at);
                    months = [
                        'January',
                        'February',
                        'March',
                        'April',
                        'May',
                        'June',
                        'July',
                        'August',
                        'September',
                        'October',
                        'November',
                        'December'
                      ];
                    month = months[d.getMonth()];
                    year = d.getFullYear();
                    html+=`<div class="col-md-6 text-lg-left">
                    <div class="post">
                        <a class="post-img" href="${BASE_URL}posts/show/${post.id}"><img height="350" src="${post.img_src}" alt="">
                        <div class="post-body">
                            <div class="post-category">
                                <h5 class="post-category">${post.name}</h5>
                            </div>
                            <h3 class="post-title">${post.title}</h3>
                            <ul class="post-meta">
                                <li>${post.firstName} &#8226; </li>
                                <li>${month} ${d.getDate()}, ${year} &#8226; ${d.getHours()}:${(d.getMinutes()<10?'0':'') + d.getMinutes()}</li>
                            </ul>
                        </div>
                        </a>
                    </div>
                </div>`;
                });
                $('#posts').html(html);

            },
            error: function(xhr, status, error){

            }
        });
    }
    
    // function addUser(controller = '', method=''){

    //         var nameReg = /^([A-Z][a-z]{2,}(\s|\-)?)*$/; 
    //         var emailReg = /^(\w+(\.|\-)?)*\@\w+(\.com|\.rs)|\.ict.edu.rs$/;
    //         var fName = $("input[name='firstName']").val();
    //         var lName = $("input[name='lastName']").val();
    //         var email = $("input[name='email']").val();
    //         var password = $("input[name='password']").val();
    //         var confPasswd = $("input[name='confPasswd']").val();
    //         var flag = 1;
    //         $(`#${method} span`).empty();
    //         if(fName == '' || lName == '' || email == '' || password == '' || confPasswd == ''){
    //             $('.errorsUser p').fadeIn('fast',function(){$(this).removeClass('d-none')});
    //             setTimeout(function(){$('.errorsUser p').fadeOut('slow',function(){$(this).addClass('d-none')})}, 1500);
    //             flag++;
    //         }
    //         else{
    //             if(!nameReg.test(fName)){
    //                 $('.fname').html('Bad format.');
    //                 flag++;
    //             }
    //             if(!nameReg.test(lName)){
    //                 $('.lname').html('Bad format.');
    //                 flag++;
    //             }
    //             if(!emailReg.test(email)){
    //                 $('.email').html('Bad email format.');
    //                 flag++;
    //             }
    //             if(password.length < 6){
    //                 $('.passwd').html('Password must have 6 or more characters.');
    //                 flag++;
    //             }
    //             if(confPasswd !== password){
    //                 $('.confpasswd').html('Passwords do not match.');
    //                 flag++;
    //             }
                
    //         }
            
    //         if(flag == 1){
    //             $.ajax({
    //                 url: BASE_URL+`${controller}/${method}`,
    //                 type: 'POST',
    //                 data: {
    //                     firstName: fName,
    //                     lastName: lName,
    //                     email: email,
    //                     password: password,
    //                     confPasswd: confPasswd,
    //                     submit: true
    //                 },
    //                 success: function(response){
    //                     console.log(response);
    //                     if(response == ""){
    //                         if(controller == 'users'){
    //                             window.location= BASE_URL+`users/login`;
    //                         }
    //                         else{
    //                             window.location= BASE_URL+`${controller}`;
    //                         }
    //                     }
    //                     else{
    //                             // response = JSON.parse(response);
    //                         for(let key in response){
    //                             if(response[key] != ''){

    //                                 $('.errorsUser').html(`<p class="text-danger">${response[key]}</p>`);
    //                             }
    //                         }
    //                     }
                        
    //                 },
    //                 error: function(xhr, status, error){
    //                     console.log(xhr.status);
    //                 }
    //             });
    //         }
    // }  

      function addPost(){

            var title = $("input[name='title']").val();
            var category = $("#category").val();
            var body = $('#postBody').val();
            var titleReg = /([A-Z][A-z0-9\s]+)/;

            if(title == '' || category == '0' || body == ''){
    
                $('.errorsPost p').fadeIn('fast',function(){$(this).removeClass('d-none')});
                setTimeout(function(){$('.errorsPost p').fadeOut('slow',function(){$(this).addClass('d-none')})}, 1100);
            }
            else if(!titleReg.test(title)){
                $('.titleErr').html("Bad format");
            }
            else{

                $('.titleErr').empty();
            $.ajax({
                url: BASE_URL+"admin/addpost",
                type: 'POST',
                data: {
                    title: title,
                    categories: category,
                    body: body
                },
                success: function(data){
                    if(data == ""){
                        window.location= BASE_URL+'admin';
                        return;
                    }
                    else{
                        console.log(data);
                        var errors = JSON.parse(data);
                        for(let key in errors){
                            if(errors != null){
                                $('.errorsPost').html(`<p class="text-danger">${errors[key]}</p>`);
                            }
                        }
                    }
                },
                error: function(xhr, status, error){

                }
            });
        }
    }