<div class="jumbotron">
    <div class="container">
        <h1><?php echo html_entity_decode($page->title,ENT_QUOTES);?></h1>
    </div>
</div>

<!-- container -->
<div class="container">
    <?php echo html_entity_decode($page->content,ENT_QUOTES);?>
</div>
<!-- /container -->
