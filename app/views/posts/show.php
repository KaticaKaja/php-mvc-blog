<div class="row">

    <div class="col-md-12 mx-auto">

    <?php \flash('rate_msg');

        ?>

        <div class="w-100 d-flex justify-content-between mb-1">

            <?php if(($data['post']->user_id == $_SESSION['user_id']) ||  ($_SESSION['user_type'] == 1)): ?>

                <a href="<?=URLROOT; ?>posts/edit/<?=$data['post']->id; ?>" class="btn btn-dark">Edit</a>

            <?php endif; ?>

            <?php if(($data['post']->user_id == $_SESSION['user_id']) ||  ($_SESSION['user_type'] == 1)): ?>

                <a href="<?=URLROOT; ?>posts/delete/<?=$data['post']->id; ?>" class="btn btn-danger">Delete</a>

            <?php endif; ?>

        </div>

        <div class="card card-body bg-light">

            <h1 class="m-0" data-id="<?=$data['post']->id; ?>"><?=$data['post']->title; ?></h1><hr>

            <!-- <?php //if(isset($data['post']->imgSrc)): ?>

                <img src="<?//=URLROOT; ?>/public/img/<?//=$data['post']->imgSrc; ?>" alt="<?//=$data['post']->imgAlt; ?>">

            <?php //endif; ?><hr> -->

            <div class="w-50 d-flex justify-content-md-around flex-lg-row flex-sm-column">

            <?php if($_SESSION['user_id'] != $data['post']->user_id) :?>

                <?php if(isset($_SESSION['userRatedPosts'])) : ?>

            <?php if(!in_array($data['post']->id, $_SESSION['userRatedPosts'])): ?>

            <h4>Rate this post:</h4>

            <div class="stars">

            <i class="fas fa-star" data-index="0"></i>

            <i class="fas fa-star" data-index="1"></i>

            <i class="fas fa-star" data-index="2"></i>

            <i class="fas fa-star" data-index="3"></i>

            <i class="fas fa-star" data-index="4"></i>

            </div>

            <?php endif; ?>

            

            <?php if(in_array($data['post']->id, $_SESSION['userRatedPosts'])) : ?>

                <h4>You rated this post with: <?=$data['thisPostValue']?> <i class="fas fa-star text-primary"></i></h4>

            

            <?php endif; ?>

            <?php endif; ?>

            <?php if(!isset($_SESSION['userRatedPosts'])) : ?>

                <h4>Rate this post:</h4>

            <div class="stars">

            <i class="fas fa-star" data-index="0"></i>

            <i class="fas fa-star" data-index="1"></i>

            <i class="fas fa-star" data-index="2"></i>

            <i class="fas fa-star" data-index="3"></i>

            <i class="fas fa-star" data-index="4"></i>

            </div>

            <?php endif; ?>

            <?php endif; ?>

        </div>

            <?=$data['post']->body; ?>

            <div class="card-footer text-muted d-flex justify-content-between">

                <span><?php $created_at = strtotime($data['post']->created_at); 

                                    $created_at = date("F j, Y &#8226; H:i", $created_at);

                                    echo $created_at;

                                ?></span>

                <span><?=$data['user']->firstName.' '.$data['user']->lastName; ?></span>

            </div>

        </div>

    </div>

</div>

