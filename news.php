<?php include "menu.php";

echo '<script type="text/javascript">tab_change_focus("li_2","1")</script>';

echo '<center><h1>' . getText1('news.title.1') . '</h1></center>';

$bdd = bdd_connect_site();

$req = $bdd->prepare('SELECT COUNT(*) AS id FROM news ORDER by id DESC') or die(mysql_error());
$req->execute();

while ($donnees = $req->fetch())
{
    $total = $donnees['id'];
}

$messagesParPage=4;

$nombreDePages=ceil ($total/$messagesParPage);

if ($_GET['page'])
{
    $pageActuelle=intval($_GET['page']);

    if ($pageActuelle>$nombreDePages)
    {
        $pageActuelle=$nombreDePages;
    }
}
else
{
    $pageActuelle=1;
}

$premiereEntree=($pageActuelle-1)*$messagesParPage;


$req = $bdd->prepare('SELECT * FROM news ORDER by id DESC LIMIT '.$premiereEntree.', '.$messagesParPage.'') or die(mysql_error());
$req->execute();

echo '</br>';

while ($donnees = $req->fetch())
{
    $date = $donnees['date'];
    $date = date("d.m.Y H:i", $date);

    if($donnees['class'] == 'program')
    {
        $class = getText1('news.text.2');
    }
    else
    {
        $class = getText1('news.text.3');
    }

    echo '<div class="bs-docs-example">
        <p class="txt-example label">' . $class . ' : ' . $donnees['title'] . '</p>
        <p>' . $donnees['text'] . '</p>
        <p class="muted">' . getText1('news.text.1') . ' ' . $donnees['author'] . ' - ' . $date . '</p>
    </div>';
}

echo  '<center><div class="pagination">
  <ul><li><a href="?page=1">' . getText1('pagination.text.1') . '</a></li>';
for ($i=1; $i<=$nombreDePages; $i++)
{
    if ($i==$pageActuelle)
    {
        echo  '<li class="active"><a>'.$i.'</a></li>';
    }
    else  //Sinon...
    {
        echo  '<li><a href="?page='.$i.'">'.$i.'</a></li>';
    }
}
$pageend = $i - 1;
echo  '<li><a href="?page=' . $pageend . '">' . getText1('pagination.text.2') . '</a></li></ul>
</div></center>';

$req->closeCursor();

show_footer(); ?>
