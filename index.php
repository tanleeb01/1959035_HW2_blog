<?php 
    include 'include/header.php';
    include 'func/postmanager.php';
?>

    <div class="jumbotron jumbotron-fluid" id="carousel">    
        <div class="container">
            <div class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php printCarousel();?>
                </div>
            </div>
        </div>
    </div>


    <div class="container" id="index_posts">
        <div class="row">
            <?php printIndexPost($conn);?>
        </div>

        <div class="button-2 float-right">          
            <div class="border"></div>
                <button onclick='location.href="post_search.php"' type="button" class="content">See all</button>
        </div>
    </div>

<?php include 'include/footer.php'?>