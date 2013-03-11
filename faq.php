<?php include "menu.php"; ?>

<script type="text/javascript">tab_change_focus("li_8","1")</script>

<?php
$bdd = bdd_connect_site();

$req = $bdd->prepare('SELECT * FROM faq WHERE langue = ? ORDER by id DESC') or die(mysql_error());
$req->execute(array($_SESSION['language']));

echo '<center><h1>FAQ</h1><div class="accordion" id="accordion2">';

$nombre = 0;

while ($donnees = $req->fetch())
{
    $nombre++;

    echo '<div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse' . $nombre . '">
                            ' . $donnees['question'] . '
                        </a>
                    </div>
                    <div id="collapse' . $nombre . '" class="accordion-body collapse">
                        <div class="accordion-inner">
                            ' . $donnees['text'] . '
                        </div>
                    </div>
                </div>';
}

echo '</div></center>';

$req->closeCursor();

show_footer(); ?>