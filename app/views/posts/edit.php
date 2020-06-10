<div class="row">

    <div class="col-md-8 mx-auto">

        <a href="<?=URLROOT?>posts/show/<?=$data['id']?>" class="btn btn-dark d-flex align-items-center mb-4">

            <span class="fa fa-arrow-circle-left"></span>

            <span class="ml-2">Back</span>

        </a>

        <div class="card card-body bg-light">

            <?php flash('post_message'); 

            ?>

            <h2>Edit post</h2>

            <p>Create a post with this form</p>

            <form action="#" method="post" enctype="multipart/form-data">

            <div class="form-group">

            <label for="title" class="font-weight-bold">Title: <sup>*</sup></label>

            <input type="text" name="title" class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid': '';?>" value="<?php echo $data['title'];?>">

            <span class="text-danger title_err"></span>

            </div>

            <div class="form-group">

            <label class="font-weight-bold" for="category">Choose a category: *</label>

            <select name="categories" id="category" class="form-control <?php echo (!empty($data['category_err'])) ? 'is-invalid': '';?>">

                <option value="0">Choose...</option>

                <?php foreach($optional['categoriesList'] as $category): ?>

                    <?php 
                        if($data['category_id'] == $category->id){
                            echo "<option selected value='$category->id'>$category->name</option>";
                        }
                        else{
                            echo "<option value='$category->id'>$category->name</option>";
                        }
                        

                    ?>

                <?php endforeach; ?>

            </select>

            <span class="text-danger category_err"></span>

            </div>

            <div class="form-group">

            <label class="font-weight-bold" for="body">Body: <sup>*</sup></label>

            <textarea id="postBody" name="body" class="form-control <?php echo (!empty($data['body_err'])) ? 'is-invalid': '';?>" ><?php echo $data['body'];?></textarea>

            <span class="text-danger body_err"></span>

            </div>

                <div class="form-group d-flex flex-column">

                    <div class="custom-file w-50">

                        <input type="file" class="custom-file-input <?php echo (!empty($data['img_err'])) ? 'is-invalid': '';?>" name="photo" id="postPhoto">

                        <label class="custom-file-label font-weight-bold" for="customFile">Change image: </label>

                        <span class="invalid-feedback"><?php echo $data['img_err']; ?></span>
                        <span class="text-danger img_err"></span>

                    </div>
                        <div><img src="<?=$data['imgSrc']; ?>" alt="<?=(empty($data['imgSrc'])) ? '' : $data['title']; ?>" class="mt-2 previewphoto" height="150"></div>
                    

                </div>

                <input type="submit" value="Submit" class="btn btn-success" id="editSubmit" data-id="<?=$data['id']?>">

            </form>

        </div>

    </div>

</div>

