<?php include "menu.php"; ?>

<script type="text/javascript">tab_change_focus("li_6","1")</script>

<div class="row-fluid">
    <?php
    if($_GET['id'] != NULL)
    {
        if($_GET['a'] == 'setting')
        {
            get_setting_subject($_GET['id']);
        }
        else
        {
            get_subject($_GET['id']);
        }
    }
    else
    {?>
        <div class="span2">
            <div class="well" style="max-width: 340px; padding: 8px 0;">
                <ul class="nav nav-list">
                    <li class="nav-header"><?php echo getText1('forum.title.nav.1'); ?></li>
                    <li id="li_1f" class="active"><a onclick="tab_change_focus('li_1f','3');" href="?f=help"><?php echo getText1('forum.nav.1'); ?></a></li>
                    <li id="li_2f"><a onclick="tab_change_focus('li_2f','3');" href="?f=problem"><?php echo getText1('forum.nav.2'); ?></a></li>
                    <li id="li_3f"><a onclick="tab_change_focus('li_3f','3');" href="?f=suggest"><?php echo getText1('forum.nav.3'); ?></a></li>
                    <li id="li_4f"><a onclick="tab_change_focus('li_4f','3');" href="?f=various"><?php echo getText1('forum.nav.4'); ?></a></li>
                    <li class="nav-header"><?php echo getText1('forum.title.nav.2'); ?></li>
                    <?php
                    if($_SESSION['pseudo'] != NULL)
                    {
                        echo '<li id="li_5f"><a href="?a=new" onclick="tab_change_focus(' . 'li_5f' . ',' . '3' . ',' . '1' . ');">' . getText1('forum.nav.5') . '</a></li>';
                    }?>
                    <li id="li_6f"><a href="?a=search" onclick="tab_change_focus('li_6f','3');"><?php echo getText1('forum.nav.6'); ?></a></li>
                    <li class="divider"></li>
                    <li><a href="faq.php"><?php echo getText1('forum.nav.7'); ?></a></li>
                </ul>
            </div>
        </div>
        <div class="span10">

        <?php
        if($_GET['a'] == 'new')
        {
            if($_SESSION['pseudo'] != NULL)
            {
                echo '<script type=text/javascript>tab_change_focus("li_5f","3","1")</script>'; ?>

                <script src="tutorial/ckeditor_basic/ckeditor.js"></script>

                <form action="bdd.php?a=addsubject" method="POST">
                    <div class="form-inline">
                        <input type="text" name="subject" placeholder="<?php echo getText1('forum.text.1'); ?>">
                        <select name="forum">
                            <option><?php echo getText1('forum.nav.1'); ?></option>
                            <option><?php echo getText1('forum.nav.2'); ?></option>
                            <option><?php echo getText1('forum.nav.3'); ?></option>
                            <option><?php echo getText1('forum.nav.4'); ?></option>
                        </select>
                    </div>
                    </br>
                    <p>
                        <textarea id="editor1" name="message" style="width: 930px;" rows="2"></textarea>
                        <script type="text/javascript">
                            CKEDITOR.replace( "editor1" );
                        </script>
                    </p>
                    <input name="valid" value="<?php echo getText1('forum.button.1'); ?>" type="submit" class="btn btn-primary">
                </form>

            <?php
            }
            else
            {
                echo '<script>window.top.window.location.href = "forum.php";</script>';
            }
        }
        elseif($_GET['a'] == 'search')
        {
            echo '<script type=text/javascript>tab_change_focus("li_6f","3")</script>';
            echo 'search';
        }
        else
        {
            if($_GET['f'] == 'problem')
            {
                echo '<script type=text/javascript>tab_change_focus("li_2f","3")</script>';

                get_subject_list('problem',$_GET['page']);
                number_page('problem');
            }
            elseif($_GET['f'] == 'suggest')
            {
                echo '<script type=text/javascript>tab_change_focus("li_3f","3")</script>';

                get_subject_list('suggest',$_GET['page']);
                number_page('suggest');
            }
            elseif($_GET['f'] == 'various')
            {
                echo '<script type=text/javascript>tab_change_focus("li_4f","3")</script>';

                get_subject_list('various',$_GET['page']);
                number_page('various');
            }
            else
            {
                echo '<script type=text/javascript>tab_change_focus("li_1f","3")</script>';

                get_subject_list('help',$_GET['page']);
                number_page('help');
            }
        }
    }?>
    </div>
</div>

<?php show_footer(); ?>