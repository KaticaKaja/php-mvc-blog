<div class="row blog-entries">

    <div class="col-md-12 col-lg-8">

        <div class="col-md-12 p-0 m-0 d-flex justify-content-between">

            <h2>Latest Posts</h2>

            <form action="#" class="search-form order-1">

                <div class="form-group searchfrom">

                    <span class="icon fa fa-search"></span>

                    <input type="text" class="form-control" id="search" placeholder="Search posts">

                </div>

            </form>

        </div>

        <div class="searched">

            

        </div>

        <div class="main-content p-0 m-0">

            <hr class="mt-0 mb-4-lg">

            <div class="row mt-4">

                <div class="col-md-12 d-flex justify-content-center justify-content-lg-start">

                    <nav aria-label="Page navigation">

                        <ul class="pagination">

                        <li class="page-item left"><a class="page-link" href="#">&lt;</a></li>

                        <?php for($page = 1; $page <= $data['pages']; $page++): ?>

                        <li class="page-item page <?=($page ==1) ? 'active' : ''?>"><a class="page-link" href="#"><?=$page?></a></li>

                        <?php endfor; ?>

                        <li class="page-item right"><a class="page-link" href="#">&gt;</a></li>

                        </ul>

                    </nav>

                    <div class="mb-3" id="categoryTag">



                    </div>

                    

                </div>

            </div>

            <div class="row" id="posts">

            <?php foreach($data['posts'] as $post): ?>

                <div class="col-md-6 text-lg-left">

                    <div class="post">

                        <a class="post-img" href="<?=URLROOT?>posts/show/<?=$post->id?>"><img height="350" src="<?=$post->imgSrc;?>" alt="<?=$post->imgAlt?>">

                        <div class="post-body">

                            <div class="post-category">

                                <h5 class="post-category"><?=$post->name?></h5>

                            </div>

                            <h3 class="post-title"><?=$post->title?></h3>

                            <ul class="post-meta">

                                <li><?=$post->firstName; ?> &#8226;</li>

                                <li><?php $created_at = strtotime($post->created_at); 

                                    $created_at = date("F j, Y &#8226; H:i", $created_at);

                                    echo $created_at;

                                ?></li>

                            </ul>

                        </div>

                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

            </div>

        </div>

    </div>

    <div class="col-md-12 col-lg-4 sidebar">

        <div>

            <h3 class="mb-3">Categories</h3>

            <ul class="categories list-group">

            <?php foreach($data['categories'] as $category): ?>

                <li class="list-group-item"><a class="d-flex justify-content-between category" data-catname="<?=$category->name?>" data-catid="<?=$category->id?>" href="#"><span><?=$category->name?></span><span class="badge badge-primary badge-pill"><?=$category->numPosts?></span></a></li>

            <?php endforeach; ?>

            </ul>

        </div>

        <div class="mt-3">

            <h3 class="heading">Top rated posts</h3>

            <div class="popular">

                <ul class="list-group list-group-flush">

                    <?php foreach($data['topRated'] as $post) : ?>

                        

                <li class="list-group-item pl-0">

                    <a href="<?=URLROOT?>posts/show/<?=$post->id?>" class="d-flex">

                    <img src="<?=$post->imgSrc?>" class="post-imgSmall" width="200" alt="<?=$post->imgAlt?>">

                    <div class="text-center ml-4">

                    <h4><?=$post->title?></h4>

                    <span><?php $created_at = strtotime($post->created_at); 

                                $created_at = date("F j, Y H:i", $created_at);

                                echo $created_at;

                            ?></span>

                    </div>

                    </a>

                </li>

                    

                    <?php endforeach; ?>

                </ul>

            </div>

        </div>

    </div>

</div>

