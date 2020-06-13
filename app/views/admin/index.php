<?php flash('admin_message'); ?>

<?php flash('adminpost_message'); ?>

<?php flash('admincategory_message'); 
?>

  <div class="row">


  <div class="col-md-3 mb-4">

    <div class="nav flex-column nav-pills" id="myTab" role="tablist" aria-orientation="vertical">

      <a class="nav-link active menu" id="users-tab" data-toggle="pill" href="#users" role="tab" aria-controls="users" aria-selected="true">Manage Users</a>

      <a class="nav-link menu" id="posts-tab" data-toggle="pill" href="#posts" role="tab" aria-controls="posts" aria-selected="false">Manage Posts</a>

      <a class="nav-link menu" id="categories-tab" data-toggle="pill" href="#categories" role="tab" aria-controls="categories" aria-selected="false">Manage Categories</a>

      <!-- <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a> -->

    </div>
    <div class="nav flex-column mt-5">
      <h5 class="border-bottom border-primary">Page visists in the last 24h</h5>
    <ul class="list-group">
      <?php foreach ($data['pages'] as $value) : ?>
        <li class="list-group-item d-flex justify-content-between"><span><?=$value->name?></span><span data-page="<?=$value->name?>" class="visits"><i class="fas fa-sync-alt"></i></span></li>
        
      <?php endforeach; ?>
    </ul>
    <button type="button" class="mt-2 btn btn-primary" id="visits">Show Visits</button>
    <button type="button" class="mt-2 btn btn-secondary" id="visitsPause">Pause Counting</button>
    </div>
    <div class="nav flex-column mt-5">
    <button type="button" class="btn btn-info" id="loggedIn">
      Currently Logged In <span class="badge badge-light"><i id="logger" class="fas fa-sync-alt"></i><span id="numberOfUsers"></span></span>
    </button>
    </div>
  </div>

  <div class="col-md-9">

    <div class="tab-content" id="myTabContent">

      <div class="tab-pane fade show active table-responsive" id="users" role="tabpanel" aria-labelledby="users">

          <table class="table table-dark table-hover">

            <thead class="thead-dark">

              <tr class="text-center">

                <th>#</th>

                <th>Name</th>

                <th>Email</th>

                <th>Role</th>

                <th>Actions</th>

              </tr>

            </thead>

            <tbody>

                <?php foreach($data['users'] as $user): ?>

                <tr class="text-center">

                  <th scope="row"><?=$user->id; ?></th>

                  <td><?=$user->firstName; ?></td>

                  <td><?=$user->email; ?></td>

                  <td><?=$user->name; ?></td>

                  <td>

                      <a href="<?=URLROOT; ?>users/settings/<?=$user->id; ?>" class="btn btn-dark"><i class="fas fa-user-edit"></i> Edit</a>

                      <a href="<?=URLROOT; ?>admin/delete/user/<?=$user->id; ?>" class="btn btn-danger"><i class="fas fa-user-minus"></i> Delete</a>

                  </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

          </table>

          <div>

            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#addUserModal">

              <i class="fas fa-pencil-alt"></i>

              <span class="ml-1">Add user</span>

            </a>

          </div>

      </div>

      <div class="tab-pane fade" id="posts" role="tabpanel" aria-labelledby="posts">

          <table class="table table-dark table-hover">

            <thead class="thead-dark">

              <tr class="text-center">

                <th>#</th>

                <th>Title</th>

                <th>Category</th>

                <th>Author</th>

                <th>Actions</th>

              </tr>

            </thead>

            <tbody>

              

                <?php foreach($data['posts'] as $post): ?>

                <tr class="text-center">

                  <th scope="row"><?=$post->id; ?></th>

                  <td><?=$post->title; ?></td>

                  <td><?=$post->name; ?></td>

                  <td><?=$post->firstName; ?></td>

                  <td>

                      <a href="<?=URLROOT; ?>posts/edit/<?=$post->id; ?>" class="btn btn-dark"><i class="fas fa-user-edit"></i> Edit</a>

                      <a href="<?=URLROOT; ?>admin/delete/post/<?=$post->id; ?>" class="btn btn-danger"><i class="fas fa-user-minus"></i> Delete</a>

                  </td>

                <?php endforeach; ?>

              <tr>

            </tbody>

          </table>

          <div>

            <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#addPostModal">

              <i class="fas fa-pencil-alt"></i>

              <span class="ml-1">Add post</span>

            </a>

          </div>

      </div>

      <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories">

        <table class="table table-dark table-hover">

          <thead class="thead-dark">

            <tr class="text-center">

              <th>#</th>

              <th>Name</th>

              <th>Actions</th>

            </tr>

          </thead>

          <tbody>

            

              <?php foreach($data['categories'] as $category): ?>

              <tr class="text-center">

                <th scope="row"><?=$category->id; ?></th>

                <td><?=$category->name; ?></td>

                <td>

                    <a href="<?=URLROOT; ?>admin/editcategory/<?=$category->id; ?>" data-name="<?=$category->name; ?>" data-id="<?=$category->id; ?>" data-toggle="modal" data-target="#updateCategoryModal" class="btn btn-dark" id="edit"><i class="fas fa-user-edit"></i> Edit</a>

                    <a href="<?=URLROOT; ?>admin/delete/category/<?=$category->id; ?>" class="btn btn-danger"><i class="fas fa-user-minus"></i> Delete</a>

                </td>

              </tr>

              <?php endforeach; ?>

          </tbody>

        </table>

        <div>

          <a href="<?=URLROOT; ?>admin/addcategory" class="btn btn-primary float-right" data-toggle="modal" data-target="#addCategoryModal">

            <i class="fas fa-pencil-alt"></i>

            <span class="ml-1">Add category</span>

          </a>

        </div>

      </div>

    </div>

  </div>

</div>

 

  <!-- ADD USER MODAL -->

  <div class="modal fade" id="addUserModal">

    <div class="modal-dialog modal-lg">

      <div class="modal-content">

        <div class="modal-header bg-dark text-white">

          <h5 class="modal-title">Add User</h5>

          <button class="close" data-dismiss="modal">

            <span>&times;</span>

          </button>

        </div>

        <div class="modal-body">

        <form id="register" action="#" method="post">

            <div class="form-group">

                    <label for="firstName">First name: <sup>*</sup></label>

                    <input type="text" name="firstName" class="form-control form-control-lg" placeholder="John">

                    <span class="firstName text-danger"></span>

                </div>

                <div class="form-group">

                    <label for="lastName">Last name: <sup>*</sup></label>

                    <input type="text" name="lastName" class="form-control form-control-lg" placeholder="Johnson">

                    <span class="lastName text-danger"></span>

                </div>

                <div class="form-group">

                    <label for="email">Email: <sup>*</sup></label>

                    <input type="text" name="email" class="form-control form-control-lg" placeholder="your_email123@gmail.com">

                    <span class="email text-danger"></span>

                </div>

                <div class="form-group">

                    <label for="password">Password: <sup>*</sup></label>

                    <input type="password" name="password" class="form-control form-control-lg" placeholder="At least 6 characters">

                    <span class="password text-danger"></span>

                </div>

                <div class="form-group">

                    <label for="confPasswd">Confirm password: <sup>*</sup></label>

                    <input type="password" name="confPasswd" class="form-control form-control-lg" placeholder="Confirm password">

                    <span class="confPasswd text-danger"></span>

                </div>

                <div class="form-group">
                    <span class="errorResult"></span>
                </div>

            </form>

        </div>

        <div class="modal-footer">

            <button form="adduser" id="adminAddUser" type="button" class="btn btn-dark send" data-page="adminAddUser">Add user</button>

        </div>

      </div>

    </div>

  </div>



  <!-- ADD POST MODAL -->

  <div class="modal fade" id="addPostModal">

    <div class="modal-dialog modal-lg">

      <div class="modal-content">

        <div class="modal-header bg-dark text-white">

          <h5 class="modal-title">Add Post</h5>

          <button class="close" data-dismiss="modal">

            <span>&times;</span>

          </button>

        </div>

        <div class="modal-body">

          <form id="postForm" action="<?php echo URLROOT; ?>posts/addpost" method="post" enctype="multipart/form-data">

              <div class="form-group">

                  <label for="title" class="font-weight-bold">Title: <sup>*</sup></label>

                  <input type="text" name="title" class="form-control">

                  <span class="text-danger title_err"></span>

              </div>

              <div class="form-group">

                  <label class="font-weight-bold" for="category">Choose a category: *</label>

                  <select name="categories" id="category" class="form-control">

                      <option value="0">Choose...</option>

                      <?php foreach($data['categories'] as $category): ?>

                          <?php 

                              echo "<option value='$category->id'>$category->name</option>";

                          ?>

                      <?php endforeach; ?>

                  </select>

                  <span class="text-danger category_err"></span>

              </div>

              <div class="form-group">

                  <label class="font-weight-bold" for="body">Body: <sup>*</sup></label>

                  <textarea id="postBody" name="body" class="form-control"></textarea>

                  <span class="text-danger body_err"></span>

              </div>

              <div class="form-group d-flex flex-column">

                  <div class="custom-file w-50">

                      <input type="file" class="custom-file-input" name="photo" id="postPhoto">

                      <label class="custom-file-label font-weight-bold" for="customFile">Add an image: </label>

                      <span class="text-danger img_err"></span>

                  </div>
                  <div>
                      <img class="previewphoto mt-2" height="150" src="" alt="">
                  </div>
              </div>
          </form>

          <!-- <div class="errorsPost mt2">

            <p class="text-danger d-none" id="postErr">All fields are required<p>

          </div> -->

        </div>

        <div class="modal-footer">

          <input type="submit" form="postForm" value="Submit" id="addSubmit" class="btn btn-success">

        </div>

      </div>

    </div>

  </div>



  <!-- ADD CATEGORY MODAL -->

  <div class="modal fade" id="addCategoryModal">

    <div class="modal-dialog modal-lg">

      <div class="modal-content">

        <div class="modal-header bg-dark text-white">

          <h5 class="modal-title">Add Category</h5>

          <button class="close" data-dismiss="modal">

            <span>&times;</span>

          </button>

        </div>

        <div class="modal-body">

          <form method="POST">

            <div class="form-group">

              <label for="title">Category name: </label>

              <input type="text" name="name" class="form-control">

            </div>

          </form>

          <div class="errorsCatAdd">

          </div>

        </div>

        <div class="modal-footer">

          <button class="btn btn-dark" id="addcategory">Add category</button>

        </div>

      </div>

    </div>

  </div>

 <!-- UPDATE CATEGORY MODAL -->

  <div class="modal fade" id="updateCategoryModal">

    <div class="modal-dialog modal-lg">

      <div class="modal-content">

        <div class="modal-header bg-dark text-white">

          <h5 class="modal-title">Update Category</h5>

          <button class="close" data-dismiss="modal">

            <span>&times;</span>

          </button>

        </div>

        <div class="modal-body">

          <form method="POST">

            <div class="form-group">

              <label for="title">Category name: </label>

              <input type="text" id="catName" name="name" class="form-control">

            </div>

          </form>

          <div class="errorsCat">

          </div>

        </div>

        <div class="modal-footer">

          <button class="btn btn-dark" id="updateCategory">Update category</button>

        </div>

      </div>

    </div>

  </div>