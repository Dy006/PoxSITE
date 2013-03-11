<?php include "menu.php" ;?>

<script type="text/javascript">tab_change_focus("li_3","1")</script>

<div class="row">
    <div class="span2">
        <ul class="nav nav-list">
            <li class="nav-header"><?php echo getText1('programs.title.nav.1'); ?></li>
            <?php get_html_program(); ?>
            <li class="nav-header"><?php echo getText1('programs.title.nav.2'); ?></li>
            <?php get_html_web(); ?>
            <li class="nav-header"><?php echo getText1('programs.title.nav.3'); ?></li>
            <li><a href="programs.php"><?php echo getText1('programs.title.nav.3'); ?></a></li>
        </ul>
    </div>

    <div class="span10">
        <?php
        if($_GET['p'] != NULL)
        {
            if($_GET['a'] != NULL)
            {
                get_update_list_program($_GET['p'],$_GET['page']);
            }
            else
            {
                get_program_info($_GET['p']);
                get_comment($_GET['p'],$_GET['page']);
            }
        }
        else
        {
            get_all_programs();
        }?>
    </div>
</div>

<?php show_footer(); ?>
