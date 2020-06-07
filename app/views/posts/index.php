<?php flash('post_message'); 

flash('adminErr_msg'); 

flash('settings_msg'); 

flash('otheruserSettings_err');

?>

<div class="row mb-3">

    <div class="col-md-6">

        <h1>Posts</h1>

    </div>

    <div class="col-md-6">

        <a href="<?=URLROOT; ?>posts/add" class="btn btn-primary float-right">

            <i class="fas fa-pencil-alt"></i>

            <span class="ml-1">Add post</span>

        </a>

    </div>

    <div class="col-md-12">

        <?=empty($data['posts']) ? '<h3 class="my-3">You don\'t have any posts yet, create some or check out others <a href="'.URLROOT.'" class="ml-auto btn btn-primary">Home page</a></h3>' : ''; ?>

    </div>

</div>

<?php foreach($data['posts'] as $post): ?>

    <div class="card card-body mb-3">

        <h2 class="card-title">

            <?=$post->title; ?>

        </h2>

        <div class="bg-light p-2 mb-3 d-flex justify-content-between">

            <span>Written by <span class="text-primary"><?=$post->firstName.' '.$post->lastName; ?></span></span>

            <span><?php $created_at = strtotime($post->created_at); 

                    $created_at = date("F j, Y &#8226; H:i", $created_at);

                    echo $created_at;

                ?></span>

        </div>

        <div class="card-text"><?=$post->body; ?></div>

        <a href="<?=URLROOT; ?>posts/show/<?=$post->id; ?>" class="btn btn-dark">More</a>

    </div>

<?php endforeach; ?>