<?php get_header(); ?>
<div class="container">
        <?php
            if (have_posts()) {
                while (have_posts()) { 
                    the_post(); ?>
                        <div class="main-post single-post">
                            <?php edit_post_link('Edit <i class="fa fa-pencil"></i>') ?>
                            <h3 class="post-title">
                                <a href="<?php the_permalink() ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <span class="post-date"><i class="fa fa-calendar fa-fw"></i> <?php the_time('F J, Y') ?> </span>
                            <span class="post-comment"><i class="fa fa-comments-o fa-fw"></i> <?php comments_popup_link('0 comments', '1 comment', '% comments', 'class-comment', 'comments disabled') ?></span>
                            <?php the_post_thumbnail('', ['class' => 'img-responsive img-thumbnail', 'title' => 'hello wordpress']) ?>
                            <div class="post-content">
                                <?php the_content() ?>
                            </div>
                            <hr>
                            <p class="post-categories">
                                <i class="fa fa-tags fa-fw"></i>
                                <?php the_category(',') ?>
                            </p>
                            <p class="post-categories">
                                <?php 
                                    if (has_tag()) {
                                        the_tags('here tag ');
                                    } else {
                                        echo "Ther's No Tags";
                                    }
                                ?>
                            </p>
                        </div>
                    <?php 
                }
            }
            echo "<div class='clearfix'></div>";?>
            <!-- =========================== START RANDOM POST =========================== -->
            <!-- START ROW +++ wp_get_post_categories() -->
            <!--
                [1] using WP_Query [good] 
                [2] get 5 articles [good]
                [3] get category id [good]
                [4] from same category [good]
                [5] exclude the main article [good]
                [6] random articles [good]
            -->
            <div class="author-page">
                <div class="row author-stats">
                    <?php
                        // Categories Ids
                        foreach(get_categories() as $category) {
                            if (in_category($category->name)) {
                                $arrCats[] = $category->cat_ID;
                            }
                        }
                        // Count Show Posts    
                        $related_posts_per_page = 3;
                    
                        // Count Categories Posts 
                        $args_count_post = array(
                          'cat' => $arrCats,
                        );
                        $the_query_count_post = new WP_Query($args_count_post);
                        $the_query_count_post->found_posts;
                    
                        // Custom Query Related Random Posts
                        $posts_arguments = array(
                            'category__in'      => $arrCats,
                            'posts_per_page'    => $related_posts_per_page,
                            'orderby'           => 'rand',
                            'post_status'       => 'publish',
                            'post__not_in'      => array(get_the_ID())
                        );
                        $related_posts = new WP_Query($posts_arguments);
                        
                        // Print Posts Content
                        if ($related_posts->have_posts()) { ?>
                            <h3 class="author-posts-title">
                                <?php if (($the_query_count_post->found_posts - 1) >= $related_posts_per_page) { ?>
                                    Random [ <?php echo $related_posts_per_page ?> ] Related posts:
                                <?php } else { ?>
                                    Related posts:
                                <?php } ?>
                            </h3>
                            <?php while ($related_posts->have_posts()) { 
                                $related_posts->the_post(); ?>
                                    <div class="author-posts">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <?php the_post_thumbnail('', ['class' => 'img-responsive img-thumbnail', 'title' => 'hello wordpress']) ?>
                                            </div>
                                            <div class="col-sm-9">
                                                <h3 class="post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
                                                <span class="post-date"><i class="fa fa-calendar fa-fw"></i> <?php the_time('F J, Y') ?> </span>
                                                <span class="post-comment"><i class="fa fa-comments-o fa-fw"></i> <?php comments_popup_link('0 comments', '1 comment', '% comments', 'class-comment', 'comments disabled') ?></span>
                                                <div class="post-content">
                                                    <?php the_excerpt() ?>
                                                    <a href="<?php the_permalink() ?>">Read More ...</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }
                        }
                        wp_reset_postdata(); ?>
                </div>
            </div>
            <!-- END ROW -->
            <!-- =========================== END RANDOM POST =========================== -->
            <!-- =========================== START AUTHOR =========================== -->

            <div class="row">
                <div class="col-md-2">
                    <?php 
                        $avatar_argument = array('class' => 'img-responsive img-thumbnail center-block');
                        echo get_avatar(get_the_author_meta('ID'), 128, '', 'user avatar', $avatar_argument);
                    ?>
                </div>
                <div class="col-md-10 author-info">
                    <h4>
                        <?php the_author_meta('first_name') ?> 
                        <?php the_author_meta('last_name') ?>  
                        (<span class="nickname"> <?php the_author_meta('nickname') ?></span>)
                    </h4>
                    <?php if (get_the_author_meta('description')) {
                        echo "<p>" . the_author_meta('description') . "</p>";
                    } else {
                        echo "theres no piography";
                    } ?>
                </div>
            </div>
            <hr>
            <p class="author-stats">
                User Posts Count: <span class="posts-count"><?php echo count_user_posts(get_the_author_meta('ID')) ?></span>,
                User Profile link: <span><?php the_author_posts_link() ?></span>
            </p>
            <!-- =========================== END AUTHOR =========================== -->
    
            <?php echo "<hr class='comment-separator' />";
    
            echo "<div class='post-pagination'>";
                if (get_previous_post_link()) {
                    previous_post_link('%link', '<i class="fa fa-chevron-left fa-fw fa-lg"></i> %title :Old Article');
                } else {
                    echo "<span>Prev</span>";
                }
                if (get_next_post_link()) {
                    next_post_link('%link', 'New Article: %title <i class="fa fa-chevron-right fa-fw fa-lg"></i>');
                } else {
                    echo "<span>Next</span>";
                }
            echo "</div><hr class='comment-separator' />";
            comments_template();
        ?>
</div>
<?php get_footer(); ?>
