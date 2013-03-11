<?php include "menu.php"; ?>

<script type="text/javascript">tab_change_focus("li_7","1")</script>

<div class="row">
    <div class="span5">
        <h1><?php echo getText1('contact.title.1'); ?></h1>
        <p><?php echo getText1('contact.text.1'); ?></p>
        <br/>
        <form name="formulaire" enctype="application/x-www-form-urlencoded" method="POST" class="form-vertical" action="bdd.php?a=contact">
            <div class="control-group">
                <label class="control-label" for="inputPrenom"><?php echo getText1('contact.field.1'); ?></label>

                <div class="controls">
                    <input type="text" name="nom" id="inputPrenom" placeholder="<?php echo getText1('contact.field.1'); ?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail"><?php echo getText1('contact.field.2'); ?></label>

                <div class="controls">
                    <input type="text" name="email" id="inputEmail" placeholder="<?php echo getText1('contact.field.2'); ?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputTitre"><?php echo getText1('contact.field.3'); ?></label>

                <div class="controls">
                    <input type="text" name="objet" id="inputTitre" placeholder="<?php echo getText1('contact.field.3'); ?>">
                </div>
            </div>
            <?php echo getText1('contact.field.4'); ?>
            <br/>
            <textarea rows="3" name="message"></textarea>
            <br/>
            <div class="btn-group">
                <input name="Submit" value="<?php echo getText1('contact.button.1'); ?>" type="submit" class="btn btn-primary">
            </div>
        </form>
    </div>
    <div class="span6 offset1">
        <h2><?php echo getText1('contact.title.2'); ?></h2>
        <p><?php echo getText1('contact.text.2'); ?></p>
        </br></br>
        <center>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="YCJ53CEXN8PCJ">
                <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
                <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
            </form>
        </center>
        </br></br>
        <h2><?php echo getText1('contact.title.3'); ?></h2>
        <p><?php echo getText1('contact.text.3'); ?></p>
    </div>
</div>

<?php show_footer(); ?>