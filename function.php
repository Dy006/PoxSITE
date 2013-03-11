<?php session_start();

function getText1($textID)
{
    $lang = GetUserLangage();
    $lang_file = $lang . ".lang";
    $page = file_get_contents($lang_file);
    $text_recherche = eregi("<".$textID.">(.*)</".$textID.">",$page,$regs); //on isole le titre
    $text = $regs[1];
    return $text;
}

function GetUserLangage()
{
    if ($_SESSION['language'] != NULL )
    {
        if ($_SESSION['language'] == "fr")
        {
            $language = "fr";
        }
        else
        {
            $language = "en";
        }
    }
    else
    {
        $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $language = $language{0}.$language{1};
		
        $_SESSION['language'] = $language;
    }

    return $language;
}

function bdd_connect_base()
{
    try
    {
        $bdd_base = new PDO('mysql:host=mysql2.alwaysdata.com;dbname=pox_base', 'pox_site', 'topsecret');
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    return $bdd_base;
}

function bdd_connect_site()
{
    try
    {
        $bdd_site = new PDO('mysql:host=mysql2.alwaysdata.com;dbname=pox_site', 'pox_site', 'topsecret');
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    return $bdd_site;
}

function get_list_contact_news()
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT friendwith FROM contact WHERE pseudmd = ? ORDER BY id') or die(mysql_error());
    $req->execute(array($_SESSION['pseudo']));

    while ($donnees = $req->fetch())
    {
        $listcontact .= 'OR pseudo = "' . $donnees['friendwith'] . '" ';
    }

    return $listcontact;
}

function get_last_news($page)
{
    $listcontact = get_list_contact_news();

    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT COUNT(*) AS id FROM event WHERE pseudo = ? ' . $listcontact . ' ORDER BY id DESC') or die(mysql_error());
    $req->execute(array($_SESSION['pseudo']));

    while ($donnees = $req->fetch())
    {
        $total = $donnees['id'];
    }

    $messagesParPage=4;

    $nombreDePages=ceil ($total/$messagesParPage);

    if ($page)
    {
        $pageActuelle=intval ($page);

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

    $req = $bdd->prepare('SELECT * FROM event WHERE pseudo = ? ' . $listcontact . ' ORDER BY id DESC LIMIT '.$premiereEntree.', '.$messagesParPage.'') or die(mysql_error());
    $req->execute(array($_SESSION['pseudo']));

    while ($donnees = $req->fetch())
    {
        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        if($donnees['pseudo'] == $_SESSION['pseudo'])
        {
            if($donnees['type'] == 'newaccount')
            {
                echo '<div class="bs-docs-example">
                        <h4>Pox :</h4>
                        <p class="txt-example label">' . $date . '</p>
                        <p>' . getText1('account.text.17') . '.</p>
                    </div>';
            }
            if($donnees['type'] == 'newcontact')
            {
                echo '<div class="bs-docs-example">
                        <h4>' . getText1('account.text.12') . ' :</h4>
                        <p class="txt-example label">' . $date . '</p>
                        <p>' . getText1('account.text.15') . ' ' . $donnees['titre'] . ' ' . getText1('account.text.16') . '.</p>
                      </div>';
            }
            if($donnees['type'] == 'changemsgperso')
            {
                echo '<div class="bs-docs-example">
                        <h4>' . getText1('account.text.11') . ' :</h4>
                        <p class="txt-example label">' . $date . '</p>
                        <p>' . $donnees['message'] . '</p>
                      </div>';
            }
            if($donnees['type'] == 'express')
            {
                echo '<div class="bs-docs-example">
                        <h4>' . getText1('account.text.11') . ' :</h4>
                        <p class="txt-example label">' . $date . '</p>
                        <p>' . $donnees['message'] . '</p>
                      </div>';
            }
        }
        else
        {
            if($donnees['type'] == 'newcontact')
            {
                echo '<div class="bs-docs-example">
                        <h4>' . getText1('account.text.12') . ' :</h4>
                        <p class="txt-example label">' . $date . '</p>
                        <p>' . $donnees['pseudo'] . ' ' . getText1('account.text.14') . ' : ' . $donnees['titre'] . '.</p>
                      </div>';
            }
            if($donnees['type'] == 'changemsgperso')
            {
                echo '<div class="bs-docs-example">
                        <h4>' . $donnees['pseudo'] . ' ' . getText1('account.text.13') . ' :</h4>
                        <p class="txt-example label">' . $date . '</p>
                        <p>' . $donnees['message'] . '</p>
                      </div>';
            }
            if($donnees['type'] == 'express')
            {
                echo '<div class="bs-docs-example">
                        <h4>' . $donnees['pseudo'] . ' ' . getText1('account.text.13') . ' :</h4>
                        <p class="txt-example label">' . $date . '</p>
                        <p>' . $donnees['message'] . '</p>
                      </div>';
            }
        }
    }

    echo  '<center><div class="pagination">
  <ul><li><a href="account.php?page=1">' . getText1('pagination.text.1') . '</a></li>';
    for ($i=1; $i<=$nombreDePages; $i++)
    {
        if ($i==$pageActuelle)
        {
            echo  '<li class="active"><a>'.$i.'</a></li>';
        }
        else  //Sinon...
        {
            echo  '<li><a href="account.php?page=' . $i . '">'.$i.'</a></li>';
        }
    }
    $pageend = $i - 1;
    echo  '<li><a href="account.php?page=' . $pageend . '">' . getText1('pagination.text.2') . '</a></li></ul>
</div></center>';

    $req->closeCursor();

    echo '<script src="tutorial/ckeditor_basic/ckeditor.js"></script>
          <form action="bdd.php?a=addexpress" method="post">
            <p>
                <textarea id="editor1" name="message" style="width: 930px;"></textarea>
                <script type="text/javascript">
                    CKEDITOR.replace( "editor1" );
                </script>
            </p>
            <center>
                <input type="submit" name="valid" value="' . getText1('account.button.5') . '" class="btn btn-primary"/>
            </center>
    </form>';
}

function get_inv_contact()
{
    echo '<table class="table">
            <thead>
            <tr>
                <th>' . getText1('account.contact.text.1') . '</th>
                <th>' . getText1('account.contact.text.2') . '</th>
                <th>' . getText1('account.contact.text.3') . '</th>
            </tr>
            </thead>';

    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT * FROM event WHERE titre = ? ORDER BY id') or die(mysql_error());
    $req->execute(array($_SESSION['pseudo']));

    while ($donnees = $req->fetch())
    {
        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<tr><td><div class="btn-group"><a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" style="width: 100px;">' . $donnees['pseudo'] . ' <span class="caret"></span></a>
        <ul class="dropdown-menu">
                            <li>
                                <form action="bdd.php?a=addcontact" method="post">
                                    <input type="hidden" name="contact" value="' . $donnees['pseudo'] . '"/>
                                    <input type="submit" name="valid" value="' . getText1('account.contact.button.2') . '" class="btn btn-primary"/>
                                </form>
                            </li>
                            <li>
                                <form action="bdd.php?a=deleteinvcontact" method="post">
                                    <input type="hidden" name="contact" value="' . $donnees['pseudo'] . '"/>
                                    <input type="submit" name="valid" value="' . getText1('account.contact.button.3') . '" class="btn btn-primary"/>
                                </form>
                            </li>
                            </ul></div></td><td>' . $donnees['message'] . '</td><td>' . $date . '</td></tr>';
    }

    $req->closeCursor();

    echo '</table>';
}

function get_number_inv_contact()
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT * FROM event WHERE titre = ? ORDER BY id') or die(mysql_error());
    $req->execute(array($_SESSION['pseudo']));

    $number_inv_cont = NULL;

    while ($donnees = $req->fetch())
    {
        $number_inv_cont++;
    }

    if($number_inv_cont != NULL)
    {
        echo ' <span class="label label-important">' . $number_inv_cont . '</span>';
    }

    $req->closeCursor();
}

function get_list_contact_pox()
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT pseudo FROM user') or die(mysql_error());
    $req->execute();

    while ($donnees = $req->fetch())
    {
        echo '<option>' . $donnees['pseudo'] . '</option>';
    }

    $req->closeCursor();
}

function get_contact()
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT friendwith FROM contact WHERE pseudmd = ?') or die(mysql_error());
    $req->execute(array($_SESSION['pseudo']));

    $nombre = 0;

    while ($donnees = $req->fetch())
    {
        $nombre++;

        get_info_contact($nombre,$donnees['friendwith']);
    }

    $req->closeCursor();
}

function get_info_contact($nombre,$contact)
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT * FROM user WHERE pseudo = ?') or die(mysql_error());
    $req->execute(array($contact));

    echo '<div class="accordion" id="accordion2">';

    while ($donnees = $req->fetch())
    {
        echo '<div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse' . $nombre . '">
                            ' . $donnees['pseudo'] . '</a>
                    </div>
                    <div id="collapse' . $nombre . '" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <strong>' . getText1('account.text.7') . ' : </strong>' . $donnees['msg_perso'] . '</br>';

        if($donnees['email_p'] == '1')
        {
            echo '<strong>' . getText1('account.text.3') . ' : </strong>' . $donnees['email'] . '</br>';
        }
        else
        {
            echo '<strong>' . getText1('account.text.3') . ' : </strong>' . getText1('account.text.9') . '</br>';
        }

        echo '<strong>' . getText1('account.text.10') . ' : </strong>' . $donnees['country'] . '</br>';

        if($donnees['age_p'] == '1')
        {
            echo '<strong>' . getText1('account.text.4') . ' : </strong>' . $donnees['age'] . '</br>';
        }
        else
        {
            echo '<strong>' . getText1('account.text.4') . ' : </strong>' . getText1('account.text.9') . '</br>';
        }

        echo '</br>
              <div class="btn-group">
                            <a class="btn" href="?type=mails&a=new&contact=' . $donnees['pseudo'] . '"><i class="icon-envelope"></i></a>
                            <a class="btn" href="?a=deletecontact&contact=' . $donnees['pseudo'] . '"><i class="icon-trash"></i></a>
              </div>
                        </div>
                    </div>';
    }

    echo '</div>';

    $req->closeCursor();
}

function get_contact_mails()
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT * FROM contact ORDER BY id') or die(mysql_error());
    $req->execute();

    while ($donnees = $req->fetch())
    {
        echo '<option>' . $donnees['friendwith'] . '</option>';
    }

    $req->closeCursor();
}

function get_mails($page)
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT COUNT(*) AS id FROM mails WHERE receiver = :receiver OR sender = :sender AND id_answer = NULL ORDER BY id DESC') or die(mysql_error());
    $req->execute(array('receiver' => $_SESSION['pseudo'],
                        'sender' => $_SESSION['pseudo']));

    while ($donnees = $req->fetch())
    {
        $total = $donnees['id'];
    }

    $messagesParPage=5;

    $nombreDePages=ceil ($total/$messagesParPage);

    if ($page)
    {
        $pageActuelle=intval ($page);

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

    $req = $bdd->prepare('SELECT * FROM mails WHERE receiver = :receiver OR sender = :sender ORDER BY id DESC LIMIT '.$premiereEntree.', '.$messagesParPage.'') or die(mysql_error());
    $req->execute(array('receiver' => $_SESSION['pseudo'],
                        'sender' => $_SESSION['pseudo']));

    echo '<table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>';

    while ($donnees = $req->fetch())
    {
        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        if($donnees['id_answer'] == NULL)
        {
            if($donnees['sender'] != $_SESSION['pseudo'] && $donnees['read_mail'] != '1')
            {
                echo '<tr class="success">';
            }
            else
            {
                echo '<tr>';
            }

            $result = get_answer_information_mails($donnees['id']);

            echo '<td><a href="?type=mails&id=' . $donnees['id'] . '" class="btn disabled" style="width: 100px;">' . $donnees['sender'] . '</a></td>
                  <td>' . $result . '</td>
                  <td>' . $donnees['object'] . '</td>
                  <td>' . $date . '</td>
                  <td>
                    <form method="POST" action="bdd.php?a=deletemails">
                        <input type="hidden" name="id" value="' . $donnees['id'] . '"/>
                        <button type="submit" class="btn"><i class="icon-remove"></i></button>
                    </form>
                  </td>
                  </tr>';
        }
    }

    echo '</table>';

    $req->closeCursor();

    $_SESSION['number_page'] = $nombreDePages;
    $_SESSION['current_page'] = $pageActuelle;

}

function get_answer_information_mails($id)
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT read_mail FROM mails WHERE id = :id AND receiver = :receiver') or die(mysql_error());
    $req->execute(array('id' => $id,
                        'receiver' => $_SESSION['pseudo']));

    $number_notread_answers = 0;
    $number_read_mail = 0;

    while ($donnees = $req->fetch())
    {
        $number_notread_answers++;
        $read_mail = $read_mail + $donnees['read_mail'];
    }

    if($number_notread_answers != 0)
    {
        if($read_mail > 0)
        {
            $start = '<strong>';
            $end = '</strong>';
        }
        else
        {
            $start = '<em>';
            $end = '</em>';
        }

        $final = $start . '(' . $number_notread_answers . ')' . $end;

        return $final;
    }

    $req->closeCursor();
}

function number_page_mails()
{
    $nombreDePages = $_SESSION['number_page'];
    $pageActuelle = $_SESSION['current_page'];

    echo  '<center><a href="?type=mails" class="btn"><i class="icon-refresh"></i></a><div class="pagination">
  <ul><li><a href="account.php?type=mails&page=1">' . getText1('pagination.text.1') . '</a></li>';
    for ($i=1; $i<=$nombreDePages; $i++)
    {
        if ($i==$pageActuelle)
        {
            echo  '<li class="active"><a>'.$i.'</a></li>';
        }
        else  //Sinon...
        {
            echo  '<li><a href="account.php?type=mails&page=' . $i . '">'.$i.'</a></li>';
        }
    }
    $pageend = $i - 1;
    echo  '<li><a href="account.php?type=mails&page=' . $pageend . '">' . getText1('pagination.text.2') . '</a></li></ul>
           </div></center>';
}

function get_number_mails()
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT * FROM mails WHERE receiver = ? ORDER BY id') or die(mysql_error());
    $req->execute(array($_SESSION['pseudo']));

    $number_new_mail = 0;

    while ($donnees = $req->fetch())
    {
        if($donnees['read_mail'] != '1')
        {
            $number_new_mail++;
        }
    }

    if($number_new_mail != 0)
    {
        echo ' <span class="label label-important">' . $number_new_mail . '</span>';
    }

    $req->closeCursor();
}

function get_info_mails($id)
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT * FROM mails WHERE id = ?') or die(mysql_error());
    $req->execute(array($id));

    $donnees = $req->fetch();

    if($donnees['sender'] == $_SESSION['pseudo'] || $donnees['receiver'] == $_SESSION['pseudo'])
    {
        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<h1>Objet : ' . $donnees['object'] . '</h1>
              <div id="breadcrumb">
                <a href="?type=mails" title="Go to subject list" class="tip-bottom"><i class="icon-home"></i>Inbox</a>
                <a href="?type=mails&id=' . $donnees['id'] . '" class="current">' . $donnees['object'] . '</a>
              </div>
              </br>
              <div class="alert alert-info">
                <h4>' . $donnees['sender'] . '</h4>
                ' . $donnees['body'] . '</br><em>Le ' . $date . '</em>
              </div>';

        get_info_mails_rep($id,$donnees['sender']);
    }
    else
    {
        echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=mails";</script>';
    }

    $req->closeCursor();
}

function get_info_mails_rep($id,$contact)
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT * FROM mails WHERE id_answer = ? ORDER BY id') or die(mysql_error());
    $req->execute(array($id));

    while ($donnees = $req->fetch())
    {
        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '</br>
              <h4>' . $donnees['object'] . '</h4>';

        if($donnees['receiver'] == $_SESSION['pseudo'])
        {
            if($donnees['read_mail'] != '1')
            {
                echo '<div class="alert alert-success">';
            }
            else
            {
                echo '<div class="well">';
            }
        }
        else
        {
            echo '<div class="well">';
        }

        echo '<h4>' . $donnees['sender'] . '</h4>
                ' . $donnees['body'] . '</br>
                <div class="pull-right">
                    <em>Le ' . $date . '</em>
                </div>
              </div>';
    }

    $req->closeCursor();

    echo '<script src="tutorial/ckeditor_basic/ckeditor.js"></script>
          <form name="formulaire" enctype="application/x-www-form-urlencoded" method="post" action="bdd.php?a=answermails">
                <input type="hidden" name="id" value="' . $id . '">
                <input type="hidden" name="receiver" value="' . $contact . '"/>
                <input type="text" name="object" style="width: 900px;" placeholder="' . getText1('account.mails.text.2') . '">
                <p>
                    <textarea id="editor1" name="body"></textarea>
                    <script type="text/javascript">
                        CKEDITOR.replace( "editor1" );
                    </script>
                </p>
                <center>
                    <div class="btn-group">
                        <input name="Submit" value="' . getText1('account.mails.button.1') . '" type="submit" class="btn btn-primary">
                        <a href="?type=mails&id=' . $id . '" class="btn"><i class="icon-refresh"></i></a>
                        <form method="POST" action="bdd.php?a=deletemails">
                        <input type="hidden" name="id" value="' . $id . '"/>
                        <button type="submit" class="btn"><i class="icon-remove"></i></button>
                    </form>
                    </div>
                </center>
            </form>';

    get_mail_read($id);
}

function get_mail_read($id)
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT receiver FROM mails WHERE id = ? ORDER BY id') or die(mysql_error());
    $req->execute(array($id));

    while ($donnees = $req->fetch())
    {
        if($donnees['receiver'] == $_SESSION['pseudo'])
        {
            $req = $bdd->prepare('UPDATE mails SET read_mail = "1" WHERE id = ?');
            $req->execute(array($id));
        }
    }

    $req->closeCursor();

    $req = $bdd->prepare('SELECT receiver FROM mails WHERE id_answer = ? ORDER BY id') or die(mysql_error());
    $req->execute(array($id));

    while ($donnees = $req->fetch())
    {
        if($donnees['receiver'] == $_SESSION['pseudo'])
        {
            $req = $bdd->prepare('UPDATE mails SET read_mail = "1" WHERE id_answer = ?');
            $req->execute(array($id));
        }
    }

    $req->closeCursor();
}

function get_participe_toadd()
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT * FROM participants WHERE accept = "0"');
    $req->execute();

    while ($donnees = $req->fetch())
    {

        $date = $donnees['date'];
        $date_aff = date("d.m.Y H:i", $date);

        echo '<td><a href="#" class="btn btn-inverse disabled" style="min-width: 100px;">' . $donnees['pseudo'] . '</a></td>
                  <td>' . $donnees['language_prog'] . '</td>
                  <td>' . $donnees['ide'] . '</td>
                  <td>' . $donnees['motivation'] . '</td>
                  <td>
                    <form name="addparticipation_form" method="POST" enctype="application/x-www-form-urlencoded" action="bdd.php?a=answerparticipation">
                        <input type="hidden" name="contact" value="' . $donnees['pseudo'] . '"/>
                        <button type="submit" class="btn"><i class="icon-plus"></i> </button>
                    </form>
                  </td>
                  <td>
                    <form name="deleteparticipation_form" method="POST" enctype="application/x-www-form-urlencoded" action="bdd.php?a=deleteparticipation">
                        <input type="hidden" name="contact" value="' . $donnees['pseudo'] . '"/>
                        <button type="submit" class="btn"><i class="icon-remove"></i> </button>
                    </form>
                  </td>
                  <td>' . $date_aff . '</td></tr>';
    }
}

function get_tutorials_toadd()
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM tutorials WHERE accept = "1"');
    $req->execute();

    while ($donnees = $req->fetch())
    {

        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<td><a href="#" class="btn btn-inverse disabled" style="min-width: 100px;">' . $donnees['pseudo'] . '</a></td>
                  <td>' . $donnees['title'] . '</td>
                  <td>' . $donnees['language'] . '</td>
                  <td>' . $donnees['difficult'] . '</td>
                  <td>' . $donnees['description'] . '</td>
                  <td><a href="bdd.php?a=accepttutorial&id=' . $donnees['id'] .'"><i class="icon-plus"></i>     </a><a href="bdd.php?a=refusetutorial&id=' . $donnees['id'] .'"><i class="icon-remove"></i></a></td>
                  <td>' . $date . '</td></tr>';
    }
}

function get_programs_toadd()
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM programs WHERE accept = "1"');
    $req->execute();

    while ($donnees = $req->fetch())
    {

        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<td><a href="#" class="btn btn-inverse disabled" style="min-width: 100px;">' . $donnees['developpeur'] . '</a><a href="#" class="btn btn-inverse disabled" style="min-width: 100px;">' . $donnees['designer'] . '</a></td>
                  <td>' . $donnees['truename'] . '</td>
                  <td>' . $donnees['program'] . '</td>
                  <td>' . $donnees['category'] . '</td>
                  <td>' . $donnees['description'] . '</td>
                  <td>' . $donnees['nextnews'] . '</td>
                  <td>' . $donnees['information'] . '</td>
                  <td><a href="bdd.php?a=acceptprogram&id=' . $donnees['id'] .'"><i class="icon-plus"></i>     </a><a href="bdd.php?a=refuseprogram&id=' . $donnees['id'] .'"><i class="icon-remove"></i></a></td>
                  <td>' . $date . '</td></tr>';
    }
}

function get_participe_true()
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT participe FROM user WHERE pseudo = ?');
    $req->execute(array($_SESSION['pseudo']));

    while ($donnee = $req->fetch())
    {
        if($donnee['participe'] != '1')
        {
            echo '<script type="text/javascript">window.top.window.location.href = "account.php";</script>';
        }
    }
}

function get_option_edit_website()
{
    if($_SESSION['rang'] == 'superadmin')
    {
        echo '<li class="divider"></li>
              <li id="li_13"><a href="account.php?type=news" onclick="tab_change_focus(' . 'li_13' . ',' . '1' . ',' . '2' . '1' . ');"><i class="icon-edit"></i> ' . getText1("menu.user.4") . '</a></li>
              <li id="li_14"><a href="account.php?type=faq" onclick="tab_change_focus(' . 'li_14' . ',' . '1' . ',' . '2' . '1' . ');"><i class="icon-edit"></i> ' .  getText1("menu.user.5") . '</a></li>';
    }
}

function get_list_tutorials()
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM tutorials WHERE accept = "2" ORDER BY id DESC') or die(mysql_error());
    $req->execute();

    echo '</br>';

    echo '<ul class="thumbnails">';

    while ($donnees = $req->fetch())
    {
        echo '<li class="span3" style="min-height: 500px;">
                <div class="thumbnail">
                  <img data-src="holder.js/300x200" alt="300x200" style="width: 300px; height: 200px;" src="data:image/png;base64,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">
                  <div class="caption">
                    <h3>' . $donnees['title'] . '</h3>
                    <p>' . $donnees['description'] . '</p>
                    <p><a href="?id=' . $donnees['id'] . '" class="btn btn-primary">Read</a></p>
                    </div>
                </div>
              </li>';
    }

    echo '</ul>';

    $req->closeCursor();
}

function get_tutorial($id,$id_chapter)
{
    $bdd = bdd_connect_site();

    if($id_chapter != NULL)
    {
        $req = $bdd->prepare('SELECT * FROM tutorials WHERE id = ? ORDER BY id DESC') or die(mysql_error());
        $req->execute(array($id));

        while ($donnees = $req->fetch())
        {
            echo '<div class="container">
                    <h1>' . $donnees['title'] . '</h1>
                    <p class="muted">' . $donnees['description'] . '</p>
                  </div>
                  <div id="breadcrumb">
                    <a href="tutorials.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                    <a href="?id=' . $donnees['id'] . '">' . $donnees['title'] . '</a>';
        }

        $req->closeCursor();

        $req = $bdd->prepare('SELECT * FROM chapter_tutorials WHERE id = ? ORDER BY id DESC') or die(mysql_error());
        $req->execute(array($id_chapter));

        while ($donnees = $req->fetch())
        {
            echo '<a href="#" class="current">' . $donnees['title'] . '</a>
                  </div>
                  </br>
                  <div class="well"><h4>' . $donnees['title'] . '</h4>' . $donnees['description'] . '</div>';
        }

        $req->closeCursor();
    }
    else
    {
        $req = $bdd->prepare('SELECT * FROM tutorials WHERE id = ? ORDER BY id DESC') or die(mysql_error());
        $req->execute(array($id));

        while ($donnees = $req->fetch())
        {
            echo '<div class="container">
                    <h1>' . $donnees['title'] . '</h1>
                    <p class="muted">' . $donnees['description'] . '</p>
                  </div>
                  <div id="breadcrumb">
                    <a href="tutorials.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                    <a href="#" class="current">' . $donnees['title'] . '</a>
                  </div>';
        }

        $req->closeCursor();

        $req = $bdd->prepare('SELECT * FROM chapter_tutorials WHERE id_tutorial = ? ORDER BY id') or die(mysql_error());
        $req->execute(array($id));

        echo '<table class="table">
                    <thead>
                        <tr>
                            <th></th>
                        </tr>
                    </thead>';

        $nombre = 0;

        while ($donnees = $req->fetch())
        {
            $nombre++;

            echo '<tr><td><a href="?id=' . $id . '&id_chapter=' . $donnees['id'] . '&page=' . $nombre . '" class="btn">' . $donnees['title'] . '</td></tr>';
        }

        echo '</table>';

        $req->closeCursor();
    }
}

function get_home_tutorials()
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM tutorials WHERE pseudo = ? ORDER BY id DESC') or die(mysql_error());
    $req->execute(array($_SESSION['pseudo']));

    echo '</br>';

    echo '<ul class="thumbnails">';

    while ($donnees = $req->fetch())
    {

    }
}

function get_list_tutorials_user()
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM tutorials WHERE pseudo = ? ORDER BY id DESC') or die(mysql_error());
    $req->execute(array($_SESSION['pseudo']));

    echo '</br>';

    echo '<ul class="thumbnails">';

    while ($donnees = $req->fetch())
    {
        echo '<li class="span3">
                <div class="thumbnail" style="min-height: 500px;">
                  <img data-src="holder.js/300x200" alt="300x200" style="width: 300px; height: 200px;" src="data:image/png;base64,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">
                  <div class="caption">
                    <h3>' . $donnees['title'] . '</h3>
                    <p>' . $donnees['description'] . '</p>
                    <p><a href="?type=tutorials&t=' . $donnees['id'] . '" class="btn btn-primary">' . getText1('account.tutorials.button.3') . '</a>';

        if($donnees['accept'] == '1')
        {
            echo '<p class="text-info">' . getText1('account.tutorials.text.1') . ' : ' . getText1('account.tutorials.text.2') . '</p>';
        }
        elseif($donnees['accept'] == '2')
        {
            echo '<p class="text-success">' . getText1('account.tutorials.text.1') . ' : ' . getText1('account.tutorials.text.3') . '</p>';
        }
        elseif($donnees['accept'] == '3')
        {
            echo '<p class="text-error">' . getText1('account.tutorials.text.1') . ' : ' . getText1('account.tutorials.text.4') . '</p><a href="bdd.php?a=sendaccepttutorial&id=' . $donnees['id'] . '" class="btn">' . getText1('account.tutorials.button.6') . '</a>';
        }
        else
        {
            echo '<a href="bdd.php?a=sendaccepttutorial&id=' . $donnees['id'] . '" class="btn">' . getText1('account.tutorials.button.7') . '</a>';
        }

        echo '      </p>
                    </div>
                </div>
              </li>';
    }

    echo '</ul>';

    $req->closeCursor();
}

function get_change_tutorial($id)
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM tutorials WHERE id = :id AND pseudo = :pseudo ORDER BY id DESC') or die(mysql_error());
    $req->execute(array('id' => $id,
                        'pseudo' => $_SESSION['pseudo']));

    echo '</br>';

    echo '<ul class="thumbnails">';

    while ($donnees = $req->fetch())
    {
        echo '<div class="row">
               <li class="span3">
                <div class="thumbnail" style="min-height: 500px;">
                  <img data-src="holder.js/300x200" alt="300x200" style="width: 300px; height: 200px;" src="data:image/png;base64,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">
                  <div class="caption">
                    <h3>' . $donnees['title'] . '</h3>
                    <form name="updatetutorial_form" enctype="application/x-www-form-urlencoded" method="post" action="bdd.php?a=updatetutorial">
                        <input type="hidden" name="id" value="' . $id . '"/>
                        <textarea name="description" rows="3">' . $donnees['description'] . '</textarea>
                        </br>
                        <input name="Submit" value="Save" type="submit" class="btn btn-primary">
                    </form>';

        if($donnees['accept'] == '1')
        {
            echo '<p class="text-info">' . getText1('account.tutorials.text.1') . ' : ' . getText1('account.tutorials.text.2') . '</p>';
        }
        elseif($donnees['accept'] == '2')
        {
            echo '<p class="text-success">' . getText1('account.tutorials.text.1') . ' : ' . getText1('account.tutorials.text.3') . '</p>';
        }
        elseif($donnees['accept'] == '3')
        {
            echo '<p class="text-error">' . getText1('account.tutorials.text.1') . ' : ' . getText1('account.tutorials.text.4') . '</p><a href="bdd.php?a=sendaccepttutorial&id=' . $donnees['id'] . '" class="btn">' . getText1('account.tutorials.button.6') . '</a>';
        }
        else
        {
            echo '<a href="bdd.php?a=sendaccepttutorial&id=' . $donnees['id'] . '" class="btn">' . getText1('account.tutorials.button.7') . '</a>';
        }

        echo '      </p>
                    </div>
                </div>
              </li>';

        get_chapter_tutorial($donnees['id']);
    }

    echo '</ul>';

    $req->closeCursor();
}

function get_chapter_tutorial($id)
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM chapter_tutorials WHERE id_tutorial = ? ORDER BY id') or die(mysql_error());
    $req->execute(array($id));

    echo '</br>';

    echo '<div class="span9"><div class="accordion" id="accordion2">';

    $nombre = 0;

    while ($donnees = $req->fetch())
    {
        $nombre++;

        echo '<div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse' . $nombre . '">
                            ' . $donnees['title'] . '
                        </a>
                    </div>
                    <div id="collapse' . $nombre . '" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <script src="tutorial/ckeditor_standard/ckeditor.js"></script>
                            <form name="updatechapter_form" enctype="application/x-www-form-urlencoded" method="post" class="form-inline" action="bdd.php?a=updatechapter">
                                <input type="hidden" name="id_tutorial" value="' . $donnees['id_tutorial'] . '"/>
                                <input type="hidden" name="id" value="' . $donnees['id'] . '"/>
		                        <p>
			                        <textarea id="editor' . $nombre . '" name="description">' . $donnees['description'] . '</textarea>
			                        <script type="text/javascript">
				                        CKEDITOR.replace( "editor' . $nombre . '" );
			                        </script>
		                        </p>
		                        <p>
		                            <div class="btn-group">
			                            <input name="Submit" value="Save" type="submit" class="btn btn-primary">
			                            <a href="bdd.php?a=deletechapter&id=' . $donnees['id'] . '&id_tutorial=' . $donnees['id_tutorial'] . '" class="btn"><i class="icon-remove"></i></a>
			                        </div>
		                        </p>
	                        </form>
                        </div>
                    </div>
                </div>';

    }

    echo '    </div>
              <form name="addchapter_form" enctype="application/x-www-form-urlencoded" method="post" class="form-inline" action="bdd.php?a=addchapter">
                <input type="hidden" name="id_tutorial" value="' . $id . '"/>
                <input type="text" name="title" placeholder="">
                <input name="Submit" value="Add chapter" type="submit" class="btn btn-primary">
              </form>
        </div>
        </div>';

    $req->closeCursor();
}

function get_list_program()
{
    $bdd = bdd_connect_site();

    $like_dbb = '%' . $_SESSION['pseudo'] . '%';

    $req = $bdd->prepare('SELECT * FROM programs WHERE developpeur LIKE ? ORDER BY id DESC') or die(mysql_error());
    $req->execute(array($like_dbb));

    echo '</br>';

    echo '<ul class="thumbnails">';

    while ($donnees = $req->fetch())
    {
        echo '<li class="span3">
                <div class="thumbnail" style="min-height: 500px;">
                  <img data-src="holder.js/300x200" alt="300x200" style="width: 300px; height: 200px;" src="data:image/png;base64,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">
                  <div class="caption">
                    <h3>' . $donnees['truename'] . '</h3>
                    <p>' . $donnees['description'] . '</p>
                    <p><a href="?type=programs&p=' . $donnees['program'] . '" class="btn btn-primary">' . getText1('account.programs.button.3') . '</a>';

        if($donnees['accept'] == '1')
        {
            echo '<p class="text-info">' . getText1('account.programs.text.1') . ' : ' . getText1('account.programs.text.2') . '</p>';
        }
        elseif($donnees['accept'] == '2')
        {
            echo '<p class="text-success">' . getText1('account.programs.text.1') . ' : ' . getText1('account.programs.text.3') . '</p>';
        }
        elseif($donnees['accept'] == '3')
        {
            echo '<p class="text-error">' . getText1('account.programs.text.1') . ' : ' . getText1('account.programs.text.4') . '</p><a href="bdd.php?a=sendacceptprogram&p=' . $donnees['program'] . '" class="btn">' . getText1('account.programs.button.6') . '</a>';
        }
        else
        {
            echo '<a href="bdd.php?a=sendacceptprogram&p=' . $donnees['program'] . '" class="btn">' . getText1('account.programs.button.7') . '</a>';
        }

        echo '      </p>
                    </div>
                </div>
              </li>';
    }

    echo '</ul>';

    $req->closeCursor();
}

function get_statut_program($program)
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT accept FROM programs WHERE program = ?');
    $req->execute(array($program));

    while ($donnees = $req->fetch())
    {
        if($donnees['accept'] == '1')
        {
            echo '<p class="text-info">' . getText1('account.programs.text.1') . ' : ' . getText1('account.programs.text.2') . '</p></br><p>' . getText1('account.programs.text.7') . '</p>';
        }
        elseif($donnees['accept'] == '2')
        {
            echo '<p class="text-success">' . getText1('account.programs.text.1') . ' : ' . getText1('account.programs.text.3') . '</p></br><p>' . getText1('account.programs.text.5') . '</p>';
        }
        elseif($donnees['accept'] == '3')
        {
            echo '<p class="text-error">' . getText1('account.programs.text.1') . ' : ' . getText1('account.programs.text.4') . '</p></br><p>' . getText1('account.programs.text.6') . '</p><a href="bdd.php?a=sendacceptprogram&p=' . $program . '" class="btn">' . getText1('account.programs.button.6') . '</a>';
        }
        else
        {
            echo '<a href="bdd.php?a=sendacceptprogram&p=' . $program . '" class="btn">' . getText1('account.programs.button.7') . '</a>';
        }
    }
}

function get_program($program)
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM programs WHERE program = ?') or die(mysql_error());
    $req->execute(array($program));

    while ($donnees = $req->fetch())
    {
        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<div class="span10"><div class="well well-large">

        <h1>' . $donnees['truename'] . '</h1>

        <h3><span class="label label-info">C\'est quoi ?</span></h3>' . nl2br(htmlspecialchars($donnees['description'])) . '

        <h3><span class="label label-info">Quelques photos</span></h3>(aucune)

        <h3><span class="label label-info">Prochaines nouveautés</span></h3>' . $donnees['nextnews'] . '

        <h3><span class="label label-info">Informations</span></h3><strong>Developpeur : </strong>' . $donnees['developpeur'] . '</br><strong>Designer : </strong>' . $donnees['designer'] . '</br><strong>Langue : </strong>' . $donnees['langue'] . '</br><strong>Version : </strong>' . $donnees['version'] . '</br><strong>Posté le : </strong>' . $date . '

        <center>
            <div class="btn-group">
                <a href="programs.php?p=' . $donnees['program'] . '&a=dl" class="btn btn-small btn-primary">' . getText1('account.programs.button.4') . '</a>
                <a href="http://github.com" class="btn btn-small">' . getText1('account.programs.button.5') . '</a>
            </div>
        </center>
        </div>';
    }

    $req->closeCursor();
}

function get_program_change($program)
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM programs WHERE program = ?') or die(mysql_error());
    $req->execute(array($program));

    while ($donnees = $req->fetch())
    {
        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<div class="span10"><div class="well well-large"><form name="formulaire" enctype="application/x-www-form-urlencoded" method="post" action="bdd.php?a=updateprogram">
              <input type="hidden" name="program" value="' . $_GET['p'] . '"/>

        <h1>' . $donnees['truename'] . '</h1>

        <h3><span class="label label-info">C\'est quoi ?</span></h3><textarea name="description" style="width: 500px;" row="5">' . $donnees['description'] . '</textarea>

        <h3><span class="label label-info">Quelques photos</span></h3>(aucune)

        <h3><span class="label label-info">Prochaines nouveautés</span></h3><input type="text" name="nextnews" style="width: 500px;" value="' . $donnees['nextnews'] . '"/>

        <h3><span class="label label-info">Informations</span></h3><strong>Developpeur : </strong><input type="text" name="developpeur" style="width: 397px;" value="' . $donnees['developpeur'] . '"/></br>
                                                                   <strong>Designer : </strong><input type="text" name="designer" style="width: 397px;" value="' . $donnees['designer'] . '"/></br>
                                                                   <strong>Langue : </strong><input type="text" name="langue" style="width: 397px;" value="' . $donnees['langue'] . '"/></br>
                                                                   <p><strong>Version : </strong>' . $donnees['version'] . '</p>
                                                                   <strong>Posté le : </strong>' . $date . '
        <center><input name="Submit" value="Enregistrer les modifications" type="submit" class="btn btn-primary"></center>
        </form></div>';
    }

    $req->closeCursor();
}

function get_list_update_program($program,$page)
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT COUNT(*) AS id FROM update_prog WHERE program = ? ORDER by id DESC') or die(mysql_error());
    $req->execute(array($program));

    while ($donnees = $req->fetch())
    {
        $total = $donnees['id'];
    }

    $messagesParPage=2;

    $nombreDePages=ceil ($total/$messagesParPage);

    if ($page)
    {
        $pageActuelle=intval ($page);

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


    $req = $bdd->prepare('SELECT * FROM update_prog WHERE program = ? ORDER by id DESC LIMIT '.$premiereEntree.', '.$messagesParPage.'') or die(mysql_error());
    $req->execute(array($program));

    echo '<div class="span10"><div class="well well-large">';

    while ($donnees = $req->fetch())
    {
        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<div class="bs-docs-example">
                    <p class="txt-example label">Version ' . $donnees['version'] . ' : ' . $donnees['titre'] . '</p>
                    <p>' . nl2br(htmlspecialchars($donnees['text'])) . '</p>
                    <p class="muted">Sortie le ' . $date . '</p>
                    </div>';
    }

    echo  '<center><div class="pagination">
  <ul><li><a href="?page=1&type=programmes&a=update&p=' . $program . '">Prev</a></li>';
    for ($i=1; $i<=$nombreDePages; $i++)
    {
        if ($i==$pageActuelle)
        {
            echo  '<li class="active"><a>'.$i.'</a></li>';
        }
        else  //Sinon...
        {
            echo  '<li><a href="?page='.$i.'&type=programmes&a=update&p=' . $program . '">'.$i.'</a></li>';
        }
    }
    $pageend = $i - 1;
    echo  '<li><a href="?page=' . $pageend . '&type=programmes&a=update&p=' . $program . '">Next</a></li></ul>
</div></center>';

    echo '<form name="formulaire" enctype="multipart/form-data" method="post" action="bdd.php?a=addupdateprogram">
                        <input type="hidden" name="program" value="' . $program . '">
                        <input type="text" name="title" placeholder="Titre">
                        <input type="text" name="version" placeholder="Version">
                        <input type="file" name="file_program">
                        <textarea rows="3" name="description" placeholder="Description" style="width:728px;"></textarea>
                        <br/>
                        <input type="hidden" name="id" value="">
                        <center><input name="Submit" value="Envoyer" type="submit" class="btn btn-primary"></center>
                    </form></div>';
}

function get_list_news_account($page)
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT COUNT(*) AS id FROM news ORDER by id DESC') or die(mysql_error());
    $req->execute();

    while ($donnees = $req->fetch())
    {
        $total = $donnees['id'];
    }

    $messagesParPage=4;

    $nombreDePages=ceil ($total/$messagesParPage);

    if ($page)
    {
        $pageActuelle=intval ($page);

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
				<p>' . $donnees['text'] . '</p><a href="bdd.php?a=deletenews&id=' . $donnees['id'] . '" class="btn"><i class="icon-remove"></i></a>
				<p class="muted">' . getText1('news.text.1') . ' ' . $donnees['author'] . ' - ' . $date . '</p>
			  </div>';
    }

    echo  '<center><div class="pagination">
  <ul><li><a href="?page=1&type=news&news=' . $class . '">' . getText1('pagination.text.1') . '</a></li>';
    for ($i=1; $i<=$nombreDePages; $i++)
    {
        if ($i==$pageActuelle)
        {
            echo  '<li class="active"><a>'.$i.'</a></li>';
        }
        else  //Sinon...
        {
            echo  '<li><a href="?page='.$i.'&type=news&news=' . $class . '">'.$i.'</a></li>';
        }
    }
    $pageend = $i - 1;
    echo  '<li><a href="?page=' . $pageend . '&type=news&news=' . $class . '">' . getText1('pagination.text.2') . '</a></li></ul>
</div></center>';

    $req->closeCursor();
}

function get_list_faq()
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM faq WHERE langue = ? ORDER by id DESC') or die(mysql_error());
    $req->execute(array($_SESSION['language']));

    echo '<div class="accordion" id="accordion2">';

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
                            ' . $donnees['text'] . ' <a class="btn btn-small" href="bdd.php?a=deletefaq&id=' . $donnees['id'] . '"><i class="icon-remove"></i></a>
                        </div>
                    </div>
                </div>';
    }

    echo '</div>';

    $req->closeCursor();
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////// Forum /////////////////////////////////////////////////////////

function get_subject($id)
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM forum WHERE id = ? ORDER by id') or die(mysql_error());
    $req->execute(array($id));

    while ($donnees = $req->fetch())
    {
        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<h1>' . getText1('forum.subject.text.1') . ' : ' . $donnees['subject'] . ' ';

        if($donnees['solve'] == '1')
        {
            echo getText1('forum.subject.text.5');
        }

        echo '</h1>
              <div id="breadcrumb">
                <a href="?f=' . $donnees['forum'] . '" title="Go to subject list" class="tip-bottom"><i class="icon-home"></i>';

        if($donnees['forum'] == 'problem')
        {
            echo getText1('forum.nav.2');
        }
        elseif($donnees['forum'] == 'suggest')
        {
            echo getText1('forum.nav.3');
        }
        elseif($donnees['forum'] == 'various')
        {
            echo getText1('forum.nav.4');
        }
        else
        {
            echo getText1('forum.nav.1');
        }

        echo '</a>
                <a href="?id=' . $donnees['id'] . '" class="current">' . $donnees['subject'] . '</a>
              </div>
              </br>
              <div class="alert alert-success">
                <em><strong>' . $donnees['pseudo'] . '</strong> ' . getText1('forum.subject.text.2') . ' :</em>
                </br>
                ' . $donnees['message'] . '
                </br>
                <p><em>' . getText1('forum.subject.text.3') . ' ' . $date . '</em></p>
              </div>';
    }

    $req->closeCursor();

    get_answer($id);
}


function get_answer($id)
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM forum WHERE id_subject = ? ORDER by id') or die(mysql_error());
    $req->execute(array($id));

    $total = 0;

    while ($donnees = $req->fetch())
    {
        $total++;

        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<div class="well">
                <em><strong>' . $donnees['pseudo'] . '</strong> ' . getText1('forum.subject.text.2') . ' :</em>
                </br>
                ' . $donnees['message'] . '
                <div class="pull-right">
                    <em>' . getText1('forum.subject.text.3') . ' ' . $date . '</em>
                </div>
              </div>';
    }

    $req->closeCursor();

    echo '<script src="tutorial/ckeditor_basic/ckeditor.js"></script>
              <form action="bdd.php?a=answersubject" method="post">
                <input type="hidden" name="id" value="' . $id . '"/>
                <p>
                    <textarea id="editor1" name="message" style="width: 930px;" rows="2"></textarea>
                    <script type="text/javascript">
                        CKEDITOR.replace( "editor1" );
                    </script>
                </p>
                <div class="row">
                    <div class="span2" style="margin-left: 40px;">
                        <div class="btn-group">
                            <input type="submit" name="valid" value="' . getText1('forum.button.2') . '" class="btn btn-primary"/>
                            <a class="btn" href="?id=' . $id . '"><i class="icon-refresh"></i></a>
                        </div>
                    </div>
              </form>';

    if($_SESSION['pseudo'] != NULL)
    {
        $req = $bdd->prepare('SELECT pseudo FROM forum WHERE id = ? ORDER by id') or die(mysql_error());
        $req->execute(array($id));

        while ($donnees = $req->fetch())
        {
            if($_SESSION['pseudo'] == $donnees['pseudo'])
            {
                echo '<div class="span4 offset2">
                        <div class="btn-group">';

                $req = $bdd->prepare('SELECT solve FROM forum WHERE id = ? ORDER by id') or die(mysql_error());
                $req->execute(array($id));

                while ($donnees = $req->fetch())
                {
                    if($donnees['solve'] == '1')
                    {
                        echo '<a class="btn btn-success disabled" href="#">';
                    }
                    else
                    {
                        echo '<a class="btn btn-success" href="bdd.php?a=solvesubject&id=' . $_GET['id'] . '">';
                    }
                }
            }
            echo '<i class="icon-ok icon-white"></i></a>
                    <a class="btn btn-warning" href="account.php?type=mails&a=new"><i class="icon-exclamation-sign icon-white"></i></a>
                    <a class="btn btn-info" href="?a=setting&id=' . $id . '"><i class="icon-cog icon-white"></i></a>
                  </div>
                  </div>';
        }
    }
    else
    {?>
        <div class="span8">
            <form class="form-inline" name="ident" method="post" action="#" onsubmit="Co(this.pseudo.value, this.password.value);return false;">
                <input class="span2" type="text" name="pseudo" placeholder="<?php echo getText1('menu.signin.text.1'); ?>">
                <input class="span2" type="password" name="password" placeholder="<?php  echo getText1('menu.signin.text.2'); ?>">
                <button type="submit" class="btn btn-primary"><?php echo getText1('menu.nav.9'); ?></button>
            </form>
        </div>

    <?php
    }

    echo '<div class="pull-right"> ' . $total . ' ' . getText1('forum.subject.text.4') . '</div></div>';
}

function get_subject_list($forum,$page)
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT COUNT(*) AS id FROM forum WHERE forum = ? ORDER by language,date DESC') or die(mysql_error());
    $req->execute(array($forum));

    while ($donnees = $req->fetch())
    {
        $total = $donnees['id'];
    }

    $messagesParPage=4;

    $nombreDePages=ceil ($total/$messagesParPage);

    if ($page)
    {
        $pageActuelle=intval ($page);

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

    if($_SESSION['language'] == 'fr')
    {
        $req = $bdd->prepare('SELECT * FROM forum WHERE forum = ? ORDER by language DESC,date DESC LIMIT '.$premiereEntree.', '.$messagesParPage.'') or die(mysql_error());
        $req->execute(array($forum));
    }
    else
    {
        $req = $bdd->prepare('SELECT * FROM forum WHERE forum = ? ORDER by language,date DESC LIMIT '.$premiereEntree.', '.$messagesParPage.'') or die(mysql_error());
        $req->execute(array($forum));
    }

    echo '</br>
          <table class="table">
            <thead>
                <tr>
                    <th>' . getText1('forum.table.1') . '</th>
                    <th>' . getText1('forum.table.2') . '</th>
                    <th></th>
                    <th>' . getText1('forum.table.3') . '</th>
                </tr>
                </thead>';

    while ($donnees = $req->fetch())
    {
        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<tr><td><a class="btn disabled" style="min-width: 200px;" href="?id=' . $donnees['id'] . '">' . $donnees['subject'] . ' ';

        if($donnees['solve'] == '1')
        {
            echo getText1('forum.subject.text.5');
        }

        echo '</a></td>
            <td><span class="muted"> ' . $donnees['pseudo'] . '</span></td>';
        if ($_SESSION['pseudo'] == $donnees['pseudo']) {
            echo '<td><a class="btn btn-small" href="?a=setting&id=' . $donnees['id'] . '"><i class="icon-cog"></i></a></td>';
        }

        echo '<td>' . $date . '</td></tr>';
    }

    echo '</table>';

    $req->closeCursor();

    $_SESSION['number_page'] = $nombreDePages;
    $_SESSION['current_page'] = $pageActuelle;
}

function number_page($forum)
{
    $nombreDePages = $_SESSION['number_page'];
    $pageActuelle = $_SESSION['current_page'];

    echo  '<center><div class="pagination">
  <ul><li><a href="?page=1&c=' . $forum . '">' . getText1('pagination.text.1') . '</a></li>';
    for ($i=1; $i<=$nombreDePages; $i++)
    {
        if ($i==$pageActuelle)
        {
            echo  '<li class="active"><a>'.$i.'</a></li>';
        }
        else  //Sinon...
        {
            echo  '<li><a href="?page='.$i.'&c=' . $forum . '">'.$i.'</a></li>';
        }
    }
    $pageend = $i - 1;
    echo  '<li><a href="?page=' . $pageend . '&c=' . $forum . '">' . getText1('pagination.text.2') . '</a></li></ul>
</div></center>';
}

function get_subject_list_search($search,$page)
{
    $search_sujet = '%' . $search . '%';

    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT COUNT(*) AS id FROM forum WHERE sujet LIKE ? ORDER by langue,date DESC') or die(mysql_error());
    $req->execute(array($search_sujet));

    while ($donnees = $req->fetch())
    {
        $total = $donnees['id'];
    }

    $messagesParPage=4;

    $nombreDePages=ceil ($total/$messagesParPage);

    if ($page)
    {
        $pageActuelle=intval ($page);

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


    $req = $bdd->prepare('SELECT * FROM forum WHERE sujet LIKE ? ORDER by langue,date DESC LIMIT '.$premiereEntree.', '.$messagesParPage.'') or die(mysql_error());
    $req->execute(array($search_sujet));

    echo '</br>';

    while ($donnees = $req->fetch())
    {
        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<tr><td><a class="btn disabled" style="min-width: 200px;" href="?id=' . $donnees['id'] . '">' . $donnees['sujet'] . ' ';

        if($donnees['solve'] == '1')
        {
            echo getText1('communication.subject.text.5');
        }

        echo '</a></td>
            <td><span class="muted"> ' . $donnees['pseudo'] . '</span></td>';
        if ($_SESSION['pseudo'] == $donnees['pseudo']) {
            echo '<td><a class="btn btn-small" href="?id=' . $donnees['id'] . '&type=setting"><i class="icon-cog"></i></a></td>';
        } else {
            echo '<td>' . getText1('communication.text.1') . '</td>';
        }
        echo '<td>' . $date . '</td></tr>';
    }

    $req->closeCursor();

    $_SESSION['number_page_search'] = $nombreDePages;
    $_SESSION['current_page_search'] = $pageActuelle;
}

function number_page_search()
{
    $nombreDePages = $_SESSION['number_page'];
    $pageActuelle = $_SESSION['current_page'];

    echo  '<center><div class="pagination">
  <ul><li><a href="?page=1&type=search">' . getText1('pagination.text.1') . '</a></li>';
    for ($i=1; $i<=$nombreDePages; $i++)
    {
        if ($i==$pageActuelle)
        {
            echo  '<li class="active"><a>'.$i.'</a></li>';
        }
        else  //Sinon...
        {
            echo  '<li><a href="?page='.$i.'&type=search">'.$i.'</a></li>';
        }
    }
    $pageend = $i - 1;
    echo  '<li><a href="?page=' . $pageend . '&type=search">' . getText1('pagination.text.2') . '</a></li></ul>
</div></center>';
}

function get_setting_subject($id)
{
    $date = time();
    $dateok = $date - 60;

    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM forum WHERE id = ? ORDER BY id') or die(mysql_error());
    $req->execute(array($id));

    while ($donnees = $req->fetch())
    {
        if($donnees['pseudo'] == $_SESSION['pseudo'])
        {
            $date = $donnees['date'];
            $date = date("d.m.Y H:i", $date);

            echo '<span class="label label-info">' . getText1('forum.setting.title.1') . '</span>';

            echo '<p></br><strong>' . getText1('forum.setting.text.1') . ' : </strong>' . $donnees['subject'] . '</br>
                  <strong>' . getText1('forum.setting.text.2') . ' : </strong>' . $donnees['pseudo'] . '</br>
                  <strong>' . getText1('forum.setting.text.3') . ' : </strong>' . $date . '</br>
                  </p>';

            echo '<span class="label label-info">' . getText1('forum.setting.title.2') . '</span>';

            if($donnees['date'] < $dateok)
            {
                echo '<script src="tutorial/ckeditor_basic/ckeditor.js"></script>
                      <form action="bdd.php?a=savechangemessage" method="POST">
                        <input type="hidden" name="id" value="' . $donnees['id'] . '"/>
                        </br>
                        <p>
                            <textarea id="editor1" name="message" style="width: 930px;" rows="2">' . $donnees['message'] . '</textarea>
                            <script type="text/javascript">
                                CKEDITOR.replace( "editor1" );
                            </script>
                        </p>
                        <input type="submit" name="valid" value="' . getText1('forum.setting.button.1') . '" class="btn btn-primary"/>
                      </form>';
            }
            else
            {
                $date_subject = $donnees['date'];

                $date_final = $date_subject - $dateok;

                echo '<p></br>' . getText1('forum.setting.text.5') . ' : ' . $date_final . ' ' . getText1('forum.setting.text.6') . '</p></br>';
            }

            echo '<span class="label label-info">' . getText1('forum.setting.title.3') . '</span>';

            echo '<div class="btn-toolbar" style="margin: 0;">
                    </br>
                    <div class="btn-group">';

            if($donnees['solve'] == '1')
            {
                echo '<a class="btn btn-success disabled" href="#"><i class="icon-ok icon-white"></i></a>';
            }
            else
            {
                echo '<a class="btn btn-success" href="bdd.php?a=solvesubject&id=' . $id . '"><i class="icon-ok icon-white"></i></a>';
            }

            echo '</div><!-- /btn-group -->
                    <div class="btn-group">
                        <a class="btn btn-warning" href="account.php?type=mails&a=new"><i class="icon-exclamation-sign icon-white"></i></a>
                    </div><!-- /btn-group -->
                    <div class="btn-group">
                        <a class="btn btn-danger" href="bdd.php?a=deletesubject&id=' . $id . '"><i class="icon-trash icon-white"></i></a>
                    </div><!-- /btn-group -->
                  </div>';
        }
        else
        {
            echo '<script>window.top.window.location.href = "forum.php";</script>';
        }
    }

    $req->closeCursor();
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////// Program ///////////////////////////////////////////////////////

function get_html_program()
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT html FROM programs WHERE category = "Programs" AND accept = "2" ORDER BY id') or die(mysql_error());
    $req->execute();

    while ($donnees = $req->fetch())
    {
        echo $donnees['html'];
    }

    $req->closeCursor();
}

function get_html_web()
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT html FROM programs WHERE category = "Web" AND accept = "2" ORDER BY id') or die(mysql_error());
    $req->execute();

    while ($donnees = $req->fetch())
    {
        echo $donnees['html'];
    }

    $req->closeCursor();
}

function get_like_program($program)
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT COUNT(*) AS id FROM compteur WHERE type = ? AND likeit = "1"') or die(mysql_error());
    $req->execute(array($program));

    while ($donnees = $req->fetch())
    {
        $id_like = $donnees['id'];
    }

    $req->closeCursor();

    $req = $bdd->prepare('SELECT COUNT(*) AS id FROM compteur WHERE type = ? AND likeit = "0"') or die(mysql_error());
    $req->execute(array($program));

    while ($donnees = $req->fetch())
    {
        $id_unlike = $donnees['id'];
    }

    $req->closeCursor();

    $id_final = $id_like + $id_unlike;

    if($id_like > $id_unlike)
    {
        $like = $id_like/$id_final;
    }
    else
    {
        $like = $id_unlike/$id_final;
    }

    return $like;
}

function get_program_info($program)
{
    $bdd = bdd_connect_site();

    $like = get_like_program($program);
    $result_like_buttons = get_like_buttons($program);

    $req = $bdd->prepare('SELECT * FROM programs WHERE program = ?') or die(mysql_error());
    $req->execute(array($program));

    while ($donnees = $req->fetch())
    {
        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<div class="row"><div class="span2"><h1>' . $donnees['truename'] . '</h1></div><div class="pull-right">';

        if($like > 0.1)
        {
            echo '<i class="icon-star"></i>';
        }
        else
        {
            echo '<i class="icon-star-empty"></i>';
        }

        if($like > 0.3)
        {
            echo '<i class="icon-star"></i>';
        }
        else
        {
            echo '<i class="icon-star-empty"></i>';
        }

        if($like > 0.5)
        {
            echo '<i class="icon-star"></i>';
        }
        else
        {
            echo '<i class="icon-star-empty"></i>';
        }

        if($like > 0.7)
        {
            echo '<i class="icon-star"></i>';
        }
        else
        {
            echo '<i class="icon-star-empty"></i>';
        }

        if($like > 0.9)
        {
            echo '<i class="icon-star"></i>';
        }
        else
        {
            echo '<i class="icon-star-empty"></i>';
        }

        echo '</div></div>

        <h3><span class="label label-info">' . getText1('programs.title.1') . '</span></h3>' . nl2br(htmlspecialchars($donnees['description'])) . '

        <h3><span class="label label-info">' . getText1('programs.title.2') . '</span></h3>

        <h3><span class="label label-info">' . getText1('programs.title.3') . '</span></h3>' . $donnees['nextnews'] . '

        <h3><span class="label label-info">' . getText1('programs.title.4') . '</span></h3>

        <strong>' . getText1('programs.text.1') . ' : </strong>' . $donnees['developpeur'] . '</br>
        <strong>' . getText1('programs.text.2') . ' : </strong>' . $donnees['designer'] . '</br>
        <strong>' . getText1('programs.text.3') . ' : </strong>' . $donnees['langue'] . '</br>
        <strong>' . getText1('programs.text.4') . ' : </strong>' . $donnees['version'] . '</br>
        <strong>' . getText1('programs.text.5') . ' : </strong>' . $date . '

        <div class="row">
            <div class="span2 offset4">
                <div class="btn-group">
                    <a href="?p=' . $donnees['program'] . '&a=dl" class="btn btn-small btn-primary">' . getText1('programs.button.1') . '</a>
                    <a href="http://github.com" class="btn btn-small">' . getText1('programs.button.2') . '</a>
                </div>
            </div>
            <div class="pull-right">';

        if($result_like_buttons != 'false')
        {
            echo '<div class="btn-group">
                    <a href="bdd.php?a=addlike&p=' . $program . '"class="btn"><i class="icon-thumbs-up"></i></a>
                    <a href="bdd.php?a=addunlike&p=' . $program . '"class="btn"><i class="icon-thumbs-down"></i></a>
                  </div>';
        }
        else
        {
            echo '<div class="btn-group">
                    <a href="#"class="btn disabled"><i class="icon-thumbs-up"></i></a>
                    <a href="#"class="btn disabled"><i class="icon-thumbs-down"></i></a>
                  </div>';
        }

        echo '    </div>
              </div>';
    }

    $req->closeCursor();
}

function get_like_buttons($program)
{
    if($_SESSION['pseudo'] != NULL)
    {
        $bdd = bdd_connect_site();

        $req = $bdd->prepare('SELECT pseudo FROM compteur WHERE type = :type AND pseudo = :pseudo') or die(mysql_error());
        $req->execute(array('type' => $program,
                            'pseudo' => $_SESSION['pseudo']));

        while($donnees = $req->fetch())
        {
            if($donnees['pseudo'])
            {
                return 'false';
            }
            else
            {
                return 'true';
            }
        }

        $req->closeCursor();
    }
    else
    {
        return 'false';
    }
}

function get_comment($program,$page)
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT COUNT(*) AS id FROM comment WHERE program = ? ORDER by id DESC') or die(mysql_error());
    $req->execute(array($program));

    while ($donnees = $req->fetch())
    {
        $total = $donnees['id'];
    }

    $messagesParPage=4;

    $nombreDePages=ceil ($total/$messagesParPage);

    if ($page)
    {
        $pageActuelle=intval ($page);

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


    $req = $bdd->prepare('SELECT * FROM comment WHERE program = :program ORDER by id DESC LIMIT '.$premiereEntree.', '.$messagesParPage.'') or die(mysql_error());
    $req->execute(array('program' => $program));

    echo '</br>';

    while ($donnees = $req->fetch())
    {
        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<div class="alert alert-info"><strong>' . $donnees['pseudo'] . '</strong> ' . getText1('programs.text.6') . ' :<br/> ' . nl2br(htmlspecialchars($donnees['message'])) . '<br/>' . getText1('programs.text.7') . ' ' . $date . '</div>';
    }

    echo  '<center><div class="pagination">
  <ul><li><a href="programs.php?page=1&p=' . $program . '">' . getText1('pagination.text.1') . '</a></li>';
    for ($i=1; $i<=$nombreDePages; $i++)
    {
        if ($i==$pageActuelle)
        {
            echo  '<li class="active"><a>'.$i.'</a></li>';
        }
        else  //Sinon...
        {
            echo  '<li><a href="programs.php?page='.$i.'&p=' . $program . '">'.$i.'</a></li>';
        }
    }
    $pageend = $i - 1;
    echo  '<li><a href="programs.php?page=' . $pageend . '&p=' . $program . '">' . getText1('pagination.text.2') . '</a></li></ul>
</div></center>';

    $req->closeCursor();

    echo '<form action="bdd.php?a=addcomment" method="post" class="form-inline">
            <input type="hidden" name="program" value="' . $program . '"/>
            <input type="text" name="message" style="width: 898px;"/>
            <input type="submit" name="valid" value="' . getText1('programs.button.3') . '" class="btn btn-primary"/>
          </form>';
}

function get_update_list_program($program,$page)
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT COUNT(*) AS id FROM update_prog WHERE program = ? ORDER by id DESC') or die(mysql_error());
    $req->execute(array($program));

    while ($donnees = $req->fetch())
    {
        $total = $donnees['id'];
    }

    $messagesParPage=4;

    $nombreDePages=ceil ($total/$messagesParPage);

    if ($page)
    {
        $pageActuelle=intval ($page);

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

    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT truename FROM programs WHERE program = ?') or die(mysql_error());
    $req->execute(array($program));

    while ($donnees = $req->fetch())
    {
        echo '<div class="row"><div class="span4 offset3"><h1>' . getText1('programs.text.8') . ' ' . $donnees['truename'] . '</h1></br></div>';
    }

    $req->closeCursor();

    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT * FROM update_prog WHERE program = :program ORDER by id DESC LIMIT '.$premiereEntree.', '.$messagesParPage.'') or die(mysql_error());
    $req->execute(array('program' => $program));

    $nombre = 0;

    while ($donnees = $req->fetch())
    {
        $nombre++;

        if($nombre == '1')
        {
            echo '<div class="span2"><a class="btn btn-primary btn-large" style="margin-top: 5px;" href="program/upload/' . $donnees['name_file'] . '">' . getText1('programs.button.4') . '</a></div></div>';
        }

        $date = $donnees['date'];
        $date = date("d.m.Y H:i", $date);

        echo '<div class="bs-docs-example">
                    <p class="txt-example label">' . getText1('programs.text.4') . ' ' . $donnees['version'] . ' : ' . $donnees['titre'] . '</p>
                    <p>' . nl2br(htmlspecialchars($donnees['text'])) . '</p>
                    <div class="row">
                        <div class="span3"><p class="muted">' . getText1('programs.text.9') . ' ' . $date . '</p></div>
                        <div class="span1 offset5"><a class="btn btn-primary" href="program/upload/' . $donnees['name_file'] . '">' . getText1('programs.button.1') . '</a></div>
                    </div>
               </div></br>';
    }

    echo  '<center><div class="pagination">
  <ul><li><a href="liste.php?page=1&p=' . $program . '&a=dl">' . getText1('pagination.text.1') . '</a></li>';
    for ($i=1; $i<=$nombreDePages; $i++)
    {
        if ($i==$pageActuelle)
        {
            echo  '<li class="active"><a>'.$i.'</a></li>';
        }
        else  //Sinon...
        {
            echo  '<li><a href="liste.php?page='.$i.'&p=' . $program . '&a=dl">'.$i.'</a></li>';
        }
    }
    $pageend = $i - 1;
    echo  '<li><a href="liste.php?page=' . $pageend . '&p=' . $program . '&a=dl">' . getText1('pagination.text.2') . '</a></li></ul>
</div></center>';

    $req->closeCursor();
}

function get_like_program_progress($program,$truename,$description)
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT COUNT(*) AS id FROM compteur WHERE type = ? AND likeit = "1"') or die(mysql_error());
    $req->execute(array($program));

    while ($donnees = $req->fetch())
    {
        $id_like = $donnees['id'];
    }

    $req->closeCursor();

    $req = $bdd->prepare('SELECT COUNT(*) AS id FROM compteur WHERE type = ? AND likeit = "0"') or die(mysql_error());
    $req->execute(array($program));

    while ($donnees = $req->fetch())
    {
        $id_unlike = $donnees['id'];
    }

    $req->closeCursor();

    $id_final = $id_like + $id_unlike;

    $like_like = ($id_like/$id_final)*100;
    $like_unlike = ($id_unlike/$id_final)*100;

    echo '<div class="hero-unit">
            <h1>' . $truename . '</h1>
            <p>' . $description . '</p>
            <div class="row">
                <div class="span2"><a href="?p=' . $program . '" class="btn btn-primary">' . getText1('programs.button.5') . '</a></div>
                <div class="span2 offset7">
                    <div class="progress">
                        <div class="bar bar-success" style="width: ' . $like_like . '%;"></div>
                        <div class="bar bar-danger" style="width: ' . $like_unlike . '%;"></div>
                    </div>
                </div>
            </div>
          </div>';
}

function get_all_programs()
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('SELECT * FROM programs WHERE accept = "2" ORDER BY id DESC') or die(mysql_error());
    $req->execute();

    while ($donnees = $req->fetch())
    {
        get_like_program_progress($donnees['program'],$donnees['truename'],$donnees['description']);
    }

    $req->closeCursor();
}

function activate_account($confirm,$crypte)
{
    $bdd = bdd_connect_base();

    echo '<center><h1>' . getText1('register.title.3') . '</h1><p>' . getText1('register.text.8') . '</p></br>';

    if($crypte)
    {
        $req = $bdd->prepare('SELECT pseudo FROM user WHERE crypte = ?') or die(mysql_error());
        $req->execute(array($crypte));

        $donnees = $req->fetch();

        if($donnees['pseudo'] == $confirm)
        {
            $req = $bdd->prepare('UPDATE user SET valid = "1" WHERE pseudo = ?');
            $req->execute(array($donnees['pseudo']));

            $_SESSION['result'] = 'crypte_register';
            echo '<script type="text/javascript">window.top.window.location.href = "index.php";</script>';
        }
        else
        {
            echo '<form method="post" action="bdd.php?a=verifcrypte" class="form-inline">
                    <input type="text" name="pseudo" placeholder="' . getText1('register.text.10') . '"/>
                    <input type="text" name="crypte" placeholder="' . getText1('register.text.11') . '"/>
                    <input type="submit" value="' . getText1('register.button.2') . '" class="btn btn-primary"/>
                  </form>';
        }

        $req->closeCursor();
    }
    else
    {
        $req = $bdd->prepare('SELECT pseudo FROM user WHERE pseudo = ?') or die(mysql_error());
        $req->execute(array($confirm));

        $donnees = $req->fetch();

        if($donnees['pseudo'] != NULL)
        {
            echo '<form method="post" action="bdd.php?a=verifcrypte" class="form-inline">
                    <input type="hidden" name="pseudo" placeholder="' . getText1('register.text.10') . '" value="' . $donnees['pseudo'] . '"/>
                    <input type="text" name="crypte" placeholder="' . getText1('register.text.11') . '"/>
                    <input type="submit" value="' . getText1('register.button.2') . '" class="btn btn-primary"/>
                  </form>';
        }
        else
        {
            echo '<form method="post" action="bdd.php?a=verifcrypte" class="form-inline">
                    <input type="text" name="pseudo" placeholder="' . getText1('register.text.10') . '"/>
                    <input type="text" name="crypte" placeholder="' . getText1('register.text.11') . '"/>
                    <input type="submit" value="' . getText1('register.button.2') . '" class="btn btn-primary"/>
                  </form>';
        }

        $req->closeCursor();
    }

    echo '</center>';
}

function delete_account($delete,$crypte)
{
    $bdd = bdd_connect_base();

    echo '<center><h1>' . getText1('register.title.4') . '</h1><p>' . getText1('register.text.9') . '</p></br>';

    if($crypte)
    {
        $req = $bdd->prepare('SELECT pseudo FROM user WHERE crypte = ?') or die(mysql_error());
        $req->execute(array($crypte));

        $donnees = $req->fetch();

        if($donnees['pseudo'] == $delete)
        {
            $req = $bdd->prepare('DELETE FROM user WHERE pseudo = ?') or die(mysql_error());
            $req->execute(array($_POST['pseudo']));

            $req = $bdd->prepare('DELETE FROM participants WHERE pseudo = ?') or die(mysql_error());
            $req->execute(array($_POST['pseudo']));

            $req = $bdd->prepare('DELETE FROM contact WHERE pseudmd = ?') or die(mysql_error());
            $req->execute(array($_POST['pseudo']));

            $_SESSION['result'] = 'deleteaccount_register';
            echo '<script type="text/javascript">window.top.window.location.href = "index.php";</script>';
        }
        else
        {
            echo '<form method="post" action="bdd.php?a=deleteaccount" class="form-inline">
                    <input type="text" name="pseudo" placeholder="' . getText1('register.text.10') . '"/>
                    <input type="text" name="crypte" placeholder="' . getText1('register.text.11') . '"/>
                    <input type="submit" value="' . getText1('register.button.3') . '" class="btn btn-primary"/>
                  </form>';
        }

        $req->closeCursor();
    }
    else
    {
        $req = $bdd->prepare('SELECT pseudo FROM user WHERE pseudo = ?') or die(mysql_error());
        $req->execute(array($delete));

        $donnees = $req->fetch();

        if($donnees['pseudo'] != NULL)
        {
            echo '<form method="post" action="bdd.php?a=deleteaccount" class="form-inline">
                    <input type="hidden" name="pseudo" placeholder="' . getText1('register.text.10') . '" value="' . $donnees['pseudo'] . '"/>
                    <input type="text" name="crypte" placeholder="' . getText1('register.text.11') . '"/>
                    <input type="submit" value="' . getText1('register.button.3') . '" class="btn btn-primary"/>
                  </form>';
        }
        else
        {
            echo '<form method="post" action="bdd.php?a=deleteaccount" class="form-inline">
                    <input type="text" name="pseudo" placeholder="' . getText1('register.text.10') . '"/>
                    <input type="text" name="crypte" placeholder="' . getText1('register.text.11') . '"/>
                    <input type="submit" value="' . getText1('register.button.3') . '" class="btn btn-primary"/>
                  </form>';
        }

        $req->closeCursor();
    }

    echo '</center>';
}

function number_looks()
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('INSERT into compteur (type,pseudo) values ("looks",:pseudo)');
    $req->execute(array('pseudo' => $_SERVER['REMOTE_ADDR']));

    $req = $bdd->prepare('SELECT COUNT(*) AS id FROM compteur WHERE type = "looks"') or die(mysql_error());
    $req->execute();

    while ($donnees = $req->fetch())
    {
        $looks = $donnees['id'];
    }

    $req->closeCursor();

    return $looks;
}

function show_footer()
{
    $looks = number_looks();

    echo '</div>
          <footer>
            <center>
                </br>
                <p><a class="btn-link" href="#">' . getText1('footer.button.1') . '</a> - <a class="btn-link" href="?l=en">' . getText1('footer.button.2') . '</a> - <a class="btn-link" href="?l=fr">' . getText1('footer.button.3') . '</a>'; ?>

            <div class="g-plusone" data-href="https://plus.google.com/u/0/communities/114731425950247971322"></div>

            <div class="fb-like" data-href="http://www.facebook.com/groups/159649254187800/" data-send="true" data-layout="button_count" data-width="450" data-show-faces="true"></div>
            <div id="fb-root"></div>

<script type="text/javascript">
    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();

    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-38410354-1']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>

</p>
                <?php
                echo '<p class="muted">Version 0.2.0 stable - ' .$looks . ' ' . getText1('footer.text.1') . ' - ' . getText1('footer.text.2') . ' - MaitreKiwiich & gv144</p>
            </center>
        </footer>
          </body>
          </html>';
}