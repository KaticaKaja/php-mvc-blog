<div class="row">

    <div class="col-md-8 mx-auto">

        <a href="<?=URLROOT; ?>posts" class="btn btn-dark d-flex align-items-center mb-4">

            <span class="fa fa-arrow-circle-left"></span>

            <span class="ml-2">Back</span>

        </a>

        <div class="card card-body bg-light">

            <?php flash('post_message'); 

            ?>

            <h2>Add post</h2>

            <p>Create a post with this form</p>

            <form action="<?php echo URLROOT; ?>posts/addpost" method="post" enctype="multipart/form-data">

                <div class="form-group">

                    <label for="title" class="font-weight-bold">Title: <sup>*</sup></label>

                    <input type="text" name="title" class="form-control">

                    <span class="text-danger title_err"></span>

                </div>

                <div class="form-group">

                    <label class="font-weight-bold" for="category">Choose a category: *</label>

                    <select name="categories" id="category" class="form-control">

                        <option value="0">Choose...</option>

                        <?php foreach($optional['categoriesList'] as $category): ?>

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

                <input type="submit" value="Submit" id="addSubmit" class="btn btn-success">

            </form>

        </div>

    </div>

</div>

