<?php session_start();
include "function.php";

$date = time();

if($_GET['a'] == 'deco')
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('UPDATE user SET statut = "deconnecte" WHERE pseudo = ?');
    $req->execute(array($_SESSION['pseudo']));

    session_destroy();
    echo '<script type="text/javascript">window.top.window.location.href = "index.php";</script>';
}
elseif ($_GET['a'] == 'co')
{
    $bdd = bdd_connect_base();

    if($_POST['pseudo'] == '' || $_POST['password'] == '')
    {
        echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>' . getText1('alert.title.1') . '</h4>
            ' . getText1('alert.text.6') . '
          </div>';
    }
    else
    {
        $search = '%'. $_POST['pseudo'] . '%';

        $req = $bdd->prepare('SELECT pseudo FROM user WHERE pseudo LIKE ?') or die(mysql_error());
        $req->execute(array($search));

        $nombre = 0;

        while ($donnees = $req->fetch())
        {
            $nombre++;
        }

        $req->closeCursor();

        if($nombre != 0)
        {
            $req = $bdd->prepare('SELECT * FROM user WHERE pseudo = ?') or die(mysql_error());
            $req->execute(array($_POST['pseudo']));

            $mdp_hac = sha1($_POST['password']);

            while ($donnees = $req->fetch())
            {
                if ($donnees['pseudo'] == $_POST['pseudo'] && $donnees['password'] == $mdp_hac)
                {
                    if($donnees['valid'] != 0)
                    {
                        $_SESSION['firstname'] = $donnees['firstname'];
                        $_SESSION['secondname'] = $donnees['secondname'];
                        $_SESSION['pseudo'] = $donnees['pseudo'];
                        $_SESSION['email'] = $donnees['email'];
                        $_SESSION['age'] = $donnees['age'];
                        $_SESSION['country'] = $donnees['country'];
                        $_SESSION['msg_perso'] = $donnees['msg_perso'];
                        $_SESSION['rang'] = $donnees['rang'];
                        $_SESSION['img_name'] = $donnees['img_name'];

                        $date = $donnees['date_creation'];
                        $date = date("d.m.Y H:i", $date);

                        $_SESSION['date_creation'] = $date;

                        $req = $bdd->prepare('UPDATE user SET statut = "connecte" WHERE pseudo = ?');
                        $req->execute(array($_SESSION['pseudo']));

                        echo 'true';
                    }
                    else
                    {
                        echo 'registerconfirm';
                    }
                }
                else
                {
                    echo '<div class="alert alert-error">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <h4>' . getText1('alert.title.1') . '</h4>
                                ' . getText1('alert.text.7') . ' <a class="btn btn-link" href="account.php?type=newpassword">' . getText1('alert.button.1') . '</a>
                              </div>';
                }
            }

            $req->closeCursor();
        }
        else
        {
            echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>' . getText1('alert.title.1') . '</h4>
            ' . getText1('alert.text.8') . '
          </div>';
        }
    }
}
elseif ($_GET['a'] == 'saveprofile')
{
    $bdd = bdd_connect_base();

    if($_POST['password1'] == '' && $_POST['password2'] == '')
    {
        $req = $bdd->prepare('UPDATE user SET email = :email, age = :age, country = :country, firstname = :firstname, secondname = :secondname, msg_perso = :msg_perso WHERE pseudo = :pseudo');
        $req->execute(array('email' => $_POST['email'],
                            'age' => $_POST['age'],
                            'country' => $_POST['country'],
                            'firstname' => $_POST['firstname'],
                            'secondname' => $_POST['secondname'],
                            'msg_perso' => $_POST['msg_perso'],
                            'pseudo' => $_SESSION['pseudo']));

        $_SESSION['email'] = $_POST['email'];
        $_SESSION['age'] = $_POST['age'];
        $_SESSION['country'] = $_POST['country'];
        $_SESSION['firstname'] = $_POST['firstname'];
        $_SESSION['secondname'] = $_POST['secondname'];
        $_SESSION['msg_perso'] = $_POST['msg_perso'];

        echo 'true';
    }
    else
    {
        if($_POST['password1'] == $_POST['password2'])
        {
            $MDP_HACH = sha1($_POST['password1']);

            $req = $bdd->prepare('UPDATE user SET password = :password, email = :email, age = :age, country = :country, firstname = :firstname, secondname = :secondname, msg_perso = :msg_perso WHERE pseudo = :pseudo');
            $req->execute(array('password' => $MDP_HACH,
                                'email' => $_POST['email'],
                                'age' => $_POST['age'],
                                'country' => $_POST['country'],
                                'firstname' => $_POST['firstname'],
                                'secondname' => $_POST['secondname'],
                                'msg_perso' => $_POST['msg_perso'],
                                'pseudo' => $_SESSION['pseudo']));

            $_SESSION['email'] = $_POST['email'];
            $_SESSION['age'] = $_POST['age'];
            $_SESSION['country'] = $_POST['country'];
            $_SESSION['firstname'] = $_POST['firstname'];
            $_SESSION['secondname'] = $_POST['secondname'];
            $_SESSION['msg_perso'] = $_POST['msg_perso'];

            echo 'true';
        }
        else
        {
            echo '<div class="alert alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <h4>Erreur !</h4>
                    Les deux mots de passe ne correspondent pas.
                  </div>';
        }
    }
}
elseif($_GET['a'] == 'uploadimgprofile')
{
    $bdd = bdd_connect_base();

    $dossier = 'program/img_profile/';
    $extension = strrchr($_FILES['img_profile']['name'], '.');
    $file = $_SESSION['pseudo'] . $extension;

    $req = $bdd->prepare('UPDATE user SET img_name = :img_name WHERE pseudo = :pseudo');
    $req->execute(array('img_name' => $file,
                        'pseudo' => $_SESSION['pseudo']));

    $_SESSION['img_name'] = $file;

    echo $dossier . $file;

    if(move_uploaded_file($_FILES['img_profile']['tmp_name'], $dossier . $file)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
    {
        echo 'Upload effectué avec succès !';
    }
    else //Sinon (la fonction renvoie FALSE).
    {
        echo 'Echec de l\'upload !';
    }
}
elseif($_GET['a'] == 'deleteaccount')
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('DELETE FROM user WHERE pseudo = ?') or die(mysql_error());
    $req->execute(array($_SESSION['pseudo']));

    $req = $bdd->prepare('DELETE FROM contact WHERE pseudmd = ?') or die(mysql_error());
    $req->execute(array($_SESSION['pseudo']));

    header('Location: ?a=deco');
}
elseif($_GET['a'] == 'addmails')
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('INSERT into mails (sender,receiver,object,body,date) values (:sender,:receiver,:object,:body,:date)') or die(mysql_error());
    $req->execute(array('sender' => $_SESSION['pseudo'],
                        'receiver' => $_POST['receiver'],
                        'object' => $_POST['object'],
                        'body' => $_POST['body'],
                        'date' => $date));

    $_SESSION['result'] = 'addmails_co';
    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=mails";</script>';
}
elseif($_GET['a'] == 'answermails')
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('INSERT into mails (id_answer,sender,receiver,object,body,date) values (:id_answer,:sender,:receiver,:object,:body,:date)') or die(mysql_error());
    $req->execute(array('id_answer' => $_POST['id'],
                        'sender' => $_SESSION['pseudo'],
                        'receiver' => $_POST['receiver'],
                        'object' => $_POST['object'],
                        'body' => $_POST['body'],
                        'date' => $date));

    $_SESSION['result'] = 'answermails_co';
    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=mails&id=' . $_POST['id'] . '";</script>';
}
elseif($_GET['a'] == 'deletemails')
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT * FROM mails WHERE id = ?') or die(mysql_error());
    $req->execute(array($_POST['id']));

    while ($donnees = $req->fetch())
    {
        if($donnees['sender'] == $_SESSION['pseudo'] || $donnees['receiver'] == $_SESSION['pseudo'])
        {
            $req = $bdd->prepare('DELETE FROM mails WHERE id = ?') or die(mysql_error());
            $req->execute(array($_POST['id']));

            $req = $bdd->prepare('DELETE FROM mails WHERE id_answer = ?') or die(mysql_error());
            $req->execute(array($_POST['id']));

            $_SESSION['result'] = 'deletemails_co';
            echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=mails";</script>';
        }
        else
        {
            echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=mails";</script>';
        }
    }

    $req->closeCursor();
}
elseif($_GET['a'] == 'addinvcontact')
{
    $bdd = bdd_connect_base();

    if($_POST['contact'] != $_SESSION['pseudo'])
    {
        $req = $bdd->prepare('SELECT COUNT(*) AS id FROM contact WHERE friendwith LIKE :friendwith AND pseudmd = :pseudmd') or die(mysql_error());
        $req->execute(array('friendwith' => $search,
                            'pseudmd' => $_SESSION['pseudo']));

        while ($donnees = $req->fetch())
        {
            if($donnees['id'] < 1)
            {
                $req = $bdd->prepare('INSERT into event (pseudo,type,titre,message,date) values (:pseudo,"invfriend",:titre,:message,:date)') or die(mysql_error());
                $req->execute(array('pseudo' => $_SESSION['pseudo'],
                                    'titre' => $_POST['contact'],
                                    'message' => $_POST['message'],
                                    'date' => $date));

                $_SESSION['result'] = 'addinvcontact_true_co';
                echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=contact";</script>';
            }
            else
            {
                $_SESSION['result'] = 'addinvcontact_samecontact_co';
                echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=contact";</script>';
            }
        }
    }
    else
    {
        $_SESSION['result'] = 'addinvcontact_same_co';
        echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=contact";</script>';
    }
}
elseif($_GET['a'] == 'deleteinvcontact')
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('DELETE FROM event WHERE pseudo = :pseudo AND titre = :titre') or die(mysql_error());
    $req->execute(array('pseudo' => $_POST['contact'],
                        'titre' => $_SESSION['pseudo']));

    $_SESSION['result'] = 'deleteinvcontact_co';
    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=contact";</script>';
}
elseif($_GET['a'] == 'addcontact')
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('insert into contact (pseudmd,friendwith) values (:pseudmd,:friendwith)') or die(mysql_error());
    $req->execute(array('pseudmd' => $_SESSION['pseudo'],
                        'friendwith' => $_POST['contact']));

    $req = $bdd->prepare('insert into contact (pseudmd,friendwith) values (:pseudmd,:friendwith)') or die(mysql_error());
    $req->execute(array('pseudmd' => $_POST['contact'],
                        'friendwith' => $_SESSION['pseudo']));

    $req = $bdd->prepare('INSERT into event (pseudo,type,titre,date) values (:pseudo,"newcontact",:titre,:date)') or die(mysql_error());
    $req->execute(array('pseudo' => $_SESSION['pseudo'],
                        'titre' => $_POST['contact'],
                        'date' => $date));

    $req = $bdd->prepare('DELETE FROM event WHERE pseudo = :pseudo AND titre = :titre') or die(mysql_error());
    $req->execute(array('pseudo' => $_POST['contact'],
                        'titre' => $_SESSION['pseudo']));

    $_SESSION['result'] = 'addcontact_co';
    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=contact";</script>';
}
elseif($_GET['a'] == 'deletecontact')
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('DELETE FROM contact WHERE pseudmd = :pseudmd AND friendwith = :friendwith') or die(mysql_error());
    $req->execute(array('pseudmd' => $_SESSION['pseudo'],
        'friendwith' => $_GET['contact']));

    $req = $bdd->prepare('DELETE FROM contact WHERE pseudmd = :pseudmd AND friendwith = :friendwith') or die(mysql_error());
    $req->execute(array('pseudmd' => $_GET['contact'],
        'friendwith' => $_SESSION['pseudo']));

    $_SESSION['result'] = 'deletecontact';
    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=contact";</script>';
}
elseif($_GET['a'] == 'addexpress')
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('INSERT into event (pseudo,type,message,date) values (:pseudo,"express",:message,:date)') or die(mysql_error());
    $req->execute(array('pseudo' => $_SESSION['pseudo'],
                        'message' => $_POST['message'],
                        'date' => $date));

    echo '<script type="text/javascript">window.top.window.location.href = "account.php";</script>';
}
elseif($_GET['a'] == 'addparticipation')
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('INSERT into participants (pseudo,language_prog,ide,appris,motivation,date) values (:pseudo, :language_prog, :ide, :appris, :motivation, :date)');
    $req->execute(array(
        'pseudo' => $_SESSION['pseudo'],
        'language_prog' => $_POST['language'],
        'ide' => $_POST['ide'],
        'appris' => $_POST['appris'],
        'motivation' => $_POST['motivation'],
        'date' => $date));

    $_SESSION['result'] = 'participerok_co';
    echo '<script type="text/javascript">window.top.window.location.href = "account.php";</script>';
}
elseif($_GET['a'] == 'answerparticipation')
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT email FROM user WHERE pseudo = ? ORDER BY id') or die(mysql_error());
    $req->execute(array($_POST['contact']));

    while ($donnees = $req->fetch())
    {
        $destinataire= $donnees['email'];
        $subject= "Participation au projet Pox";
        $body= 'Votre demande de participation a été acceptée. Vous pouvez maintenant poster des programmes qui passeront ensuite par une équipe de vérificatino pour être publié sur le site. L\'équipe de Pox !';

/////voici la version Mine
        $headers = "MIME-Version: 1.0\r\n";

//////ici on d�termine le mail en format texte
        $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";

////ici on d�termine l'expediteur et l'adresse de r�ponse
        $headers .= "From: POX <noreply@pox.com>\r\nReply-to : POX <noreply@pox.com>\nX-Mailer:PHP";

        if (mail($destinataire,$subject,$body,$headers))
        {
            $req = $bdd->prepare('UPDATE user SET participe = "1" WHERE pseudo = ?') or die(mysql_error());
            $req->execute(array($_POST['contact']));

            $req = $bdd->prepare('UPDATE participants SET accept = "1" WHERE pseudo = ?') or die(mysql_error());
            $req->execute(array($_POST['contact']));

            $_SESSION['result'] = 'addparticipation_co';
            echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=mails&admin=true";</script>';
        }
    }

    $req->closeCursor();
}
elseif($_GET['a'] == 'deleteparticipation')
{
    $bdd = bdd_connect_base();

    $req = $bdd->prepare('SELECT email FROM user WHERE pseudo = ? ORDER BY id') or die(mysql_error());
    $req->execute(array($_POST['contact']));

    while ($donnees = $req->fetch())
    {
        $destinataire= $donnees['email'];
        $subject= "Participation au projet Pox";
        $body= 'Votre demande de participation au projet Pox a été refusée. Vous avez certainement mal respecté les consignes demandées au début du formulaire. Vous pourrez biensûr reposter votre candidature pour participer au projet Pox !. L\'équipe de Pox !';

/////voici la version Mine
        $headers = "MIME-Version: 1.0\r\n";

//////ici on d�termine le mail en format texte
        $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";

////ici on d�termine l'expediteur et l'adresse de r�ponse
        $headers .= "From: POX <noreply@pox.com>\r\nReply-to : POX <noreply@pox.com>\nX-Mailer:PHP";

        if (mail($destinataire,$subject,$body,$headers))
        {
            $req = $bdd->prepare('DELETE FROM participants WHERE pseudo = ?') or die(mysql_error());
            $req->execute(array($_POST['contact']));

            $req = $bdd->prepare('UPDATE user SET participe = "0" WHERE pseudo = ?') or die(mysql_error());
            $req->execute(array($_POST['contact']));

            $_SESSION['result'] = 'deleteparticipation_co';
            echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=mails&admin=true";</script>';
        }
    }

    $req->closeCursor();
}
elseif($_GET['a'] == 'addtutorial')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('INSERT into tutorials (pseudo,title,language,difficult,description,date) values (:pseudo,:title,:language,:difficult,:description,:date)') or die(mysql_error());
    $req->execute(array('pseudo' => $_SESSION['pseudo'],
                        'title' => $_POST['title'],
                        'language' => $_POST['language'],
                        'difficult' => $_POST['difficult'],
                        'description' => $_POST['description'],
                        'date' => $date));

    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=tutorials";</script>';
}
elseif($_GET['a'] == 'updatetutorial')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('UPDATE tutorials SET description = :description WHERE id = :id') or die(mysql_error());
    $req->execute(array('description' => $_POST['description'],
                        'id' => $_POST['id']));

    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=tutorials&t=' . $_POST['id'] . '";</script>';
}
elseif($_GET['a'] == 'addchapter')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('INSERT into chapter_tutorials (id_tutorial,title) values (:id_tutorial,:title)') or die(mysql_error());
    $req->execute(array('id_tutorial' => $_POST['id_tutorial'],
                        'title' => $_POST['title']));

    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=tutorials&t=' . $_POST['id_tutorial'] . '";</script>';
}
elseif($_GET['a'] == 'updatechapter')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('UPDATE chapter_tutorials SET description = :description WHERE id = :id') or die(mysql_error());
    $req->execute(array('description' => $_POST['description'],
                        'id' => $_POST['id']));

    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=tutorials&t=' . $_POST['id_tutorial'] . '";</script>';
}
elseif($_GET['a'] == 'deletechapter')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('DELETE FROM chapter_tutorials WHERE id = ?') or die(mysql_error());
    $req->execute(array($_GET['id']));

    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=tutorials&t=' . $_GET['id_tutorial'] . '";</script>';
}
elseif($_GET['a'] == 'accepttutorial')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('UPDATE tutorials SET accept = "2" WHERE id = ?') or die(mysql_error());
    $req->execute(array($_GET['id']));

    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=mails&admin=true";</script>';
}
elseif($_GET['a'] == 'sendaccepttutorial')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('UPDATE tutorials SET accept = "1" WHERE id = ?') or die(mysql_error());
    $req->execute(array($_GET['id']));

    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=tutorials&t=' . $_GET['id'] . '";</script>';
}
elseif($_GET['a'] == 'refusetutorial')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('UPDATE tutorials SET accept = "3" WHERE id = ?') or die(mysql_error());
    $req->execute(array($_GET['id']));

    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=mails&admin=true";</script>';
}
elseif($_GET['a'] == 'addprogram')
{
    $bdd = bdd_connect_site();

    $developpeur = $_SESSION['pseudo'] . ',' . $_POST['developpeur'];
    $html = '<li><a href="?p=' . $_POST['program'] . '">' . $_POST['truename'] . '</a></li>';

    $req = $bdd->prepare('INSERT into programs (developpeur,designer,html,category,program,truename,description,nextnews,langue,version,date) values (:developpeur,:designer,:html,:category,:program,:truename,:description,:nextnews,:langue,:version,:date)') or die(mysql_error());
    $req->execute(array('developpeur' => $developpeur,
        'designer' => $_POST['designer'],
        'html' => $html,
        'category' => $_POST['category'],
        'program' => $_POST['program'],
        'truename' => $_POST['truename'],
        'description' => $_POST['description'],
        'nextnews' => $_POST['nextnews'],
        'langue' => $_POST['langue'],
        'version' => $_POST['version'],
        'date' => $date));

    $bdd = bdd_connect_base();

    $extension = strrchr($_FILES['file_program']['name'], '.');
    $file = $_POST['program'] . '-' . $_POST['version'] . $extension;

    $req = $bdd->prepare('INSERT into update_prog (program,titre,text,version,name_file,date) values (:program,"First version","New program.",:version,:name_file,:date)');
    $req->execute(array('program' => $_POST['program'],
                        'version' => $_POST['version'],
                        'name_file' => $file,
                        'date' => $date));

    $dossier = 'program/upload/';

    echo $dossier . $file;

    $file = strtr($file,
        'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
        'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
    $file = preg_replace('/([^.a-z0-9]+)/i', '-', $file);
    if(move_uploaded_file($_FILES['file_program']['tmp_name'], $dossier . $file)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
    {
        $_SESSION['result'] = 'addprogramok_co';
        echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=programs";</script>';
    }
    else //Sinon (la fonction renvoie FALSE).
    {
        echo 'Echec de l\'upload !';
    }
}
elseif($_GET['a'] == 'updateprogram')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('UPDATE programs SET developpeur = :developpeur, designer = :designer, description = :description, nextnews = :nextnews, langue = :langue WHERE program = :program') or die(mysql_error());
    $req->execute(array('developpeur' => $_POST['developpeur'],
                        'designer' => $_POST['designer'],
                        'description' => $_POST['description'],
                        'nextnews' => $_POST['nextnews'],
                        'langue' => $_POST['langue'],
                        'program' => $_POST['program']));

    $_SESSION['result'] = 'updateprogramok_co';
    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=programs&p=' . $_POST['program'] . '";</script>';
}

elseif($_GET['a'] == 'addupdateprogram')
{
    $bdd = bdd_connect_base();

    $extension = strrchr($_FILES['file_program']['name'], '.');
    $file = $_POST['program'] . '-' . $_POST['version'] . $extension;

    $req = $bdd->prepare('INSERT into update_prog (program,titre,text,version,name_file,date) values (:program,:titre,:text,:version,:name_file,:date)');
    $req->execute(array('program' => $_POST['program'],
                        'titre' => $_POST['title'],
                        'text' => $_POST['description'],
                        'version' => $_POST['version'],
                        'name_file' => $file,
                        'date' => $date));

    $bdd = bdd_connect_site();

    $req = $bdd->prepare('UPDATE programs SET version = :version WHERE program = :program') or die(mysql_error());
    $req->execute(array('version' => $_POST['version'],
                        'program' => $_POST['program']));

    $dossier = 'program/upload/';

    echo $dossier . $file;

    $file = strtr($file,
        'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
        'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
    $file = preg_replace('/([^.a-z0-9]+)/i', '-', $file);
    if(move_uploaded_file($_FILES['file_program']['tmp_name'], $dossier . $file)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
    {
        $_SESSION['result'] = 'addprogramok_co';
        echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=programs";</script>';
    }
    else //Sinon (la fonction renvoie FALSE).
    {
        echo 'Echec de l\'upload !';
    }
}
elseif($_GET['a'] == 'acceptprogram')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('UPDATE programs SET accept = "2" WHERE id = ?') or die(mysql_error());
    $req->execute(array($_GET['id']));

    $_SESSION['result'] = 'acceptprogram_co';
    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=mails&admin=true";</script>';
}
elseif($_GET['a'] == 'sendacceptprogram')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('UPDATE programs SET accept = "1" WHERE program = ?') or die(mysql_error());
    $req->execute(array($_GET['p']));

    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=programs&p=' . $_GET['p'] . '";</script>';
}

elseif($_GET['a'] == 'refuseprogram')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('UPDATE programs SET accept = "3" WHERE id = ?') or die(mysql_error());
    $req->execute(array($_GET['id']));

    $_SESSION['result'] = 'deleteprogram_co';
    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=mails&admin=true";</script>';
}
elseif($_GET['a'] == 'addnews')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('INSERT into news (class,title,text,author,date) values (:class,:title,:text,:author,:date)') or die(mysql_error());
    $req->execute(array('class' => $_POST['class'],
        'title' => $_POST['title'],
        'text' => $_POST['text'],
        'author' => $_SESSION['pseudo'],
        'date' => $date));


    $_SESSION['result'] = 'addnews_co';

    if($_POST['class'] == 'program')
    {
        echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=news&news=program";</script>';
    }
    else
    {
        echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=news";</script>';
    }
}
elseif($_GET['a'] == 'deletenews')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('DELETE FROM news WHERE id = ?') or die(mysql_error());
    $req->execute(array($_GET['id']));

    $_SESSION['result'] = 'deletenews_co';
    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=news";</script>';
}
elseif($_GET['a'] == 'addfaq')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('INSERT into faq (question,text,langue) values (:question,:text,:langue)') or die(mysql_error());
    $req->execute(array('question' => $_POST['question'],
                        'text' => $_POST['text'],
                        'langue' => $_SESSION['language']));


    $_SESSION['result'] = 'addfaq_co';
    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=faq";</script>';
}
elseif($_GET['a'] == 'deletefaq')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('DELETE FROM faq WHERE id = ?') or die(mysql_error());
    $req->execute(array($_GET['id']));

    $_SESSION['result'] = 'deletefaq_co';
    echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=faq";</script>';
}
elseif($_GET['a'] == 'newpassword')
{
    $bdd = bdd_connect_base();

    if($_POST['cryptecode'] != NULL)
    {
        $req = $bdd->prepare('SELECT pseudo FROM user WHERE crypte = ?') or die(mysql_error());
        $req->execute(array($_POST['cryptecode']));

        $donnees = $req->fetch();

        if($donnees['pseudo'] != NULL)
        {
            echo '<script>window.top.window.location.href = "account.php?type=newpassword&crypte=' . $_POST['cryptecode'] . '";</script>';
        }
        else
        {
            $_SESSION['result'] = 'cryptecodenotexist_resetpassword';
            echo '<script>window.top.window.location.href = "account.php?type=newpassword";</script>';
        }

        $req->closeCursor();
    }
    else
    {
        $req = $bdd->prepare('SELECT * FROM user WHERE email = ?') or die(mysql_error());
        $req->execute(array($_POST['email']));

        $donnees = $req->fetch();


        if($donnees['pseudo'] != NULL)
        {
            $destinataire= $_POST['email'];
            $subject= "Réinitialisation du mot de passe";
            $body= 'Bonjour ' . $donnees['pseudo'] . ', vous avez demandé à réinitialiser votre mot de passe. Vous devez cliquer sur ce lien : http://pox.alwaysdata.net/account.php?type=newpassword&crypte=' . $donnees['crypte'] . '. Nous vous souhaitons un bon retour parmis nous.';

/////voici la version Mine
            $headers = "MIME-Version: 1.0\r\n";

//////ici on d�termine le mail en format html
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

////ici on d�termine l'expediteur et l'adresse de r�ponse
            $headers .= "From: POX <noreply@pox.com>\r\nReply-to : POX <noreply@pox.com>\nX-Mailer:PHP";

            if (mail($destinataire,$subject,$body,$headers))
            {
                $_SESSION['result'] = 'waitemail_resetpassword';
                echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=newpassword";</script>';
            }
            else
            {
                echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=newpassword";</script>';
            }
        }
        else
        {
            $_SESSION['result'] = 'emailnotexist_resetpassword';
            echo '<script>window.top.window.location.href = "account.php?type=newpassword";</script>';
        }
    }
}
elseif($_GET['a'] == 'resetpassword')
{
    $bdd = bdd_connect_base();

    if($_POST['password1'] == '' || $_POST['password2'] == '')
    {
        echo '<div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <h4>Erreur !</h4>
                Vous devez remplir tous les champs.
              </div>';
    }
    else
    {
        if($_POST['password1'] == $_POST['password2'])
        {
            if(strlen($_POST['password1']) < 6)
            {
                echo '<div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <h4>Erreur !</h4>
                        Les mots de passe sont trop courts.
                      </div>';
            }
            else
            {
                $MDP_HACH = sha1($_POST['password1']);

                $req = $bdd->prepare('UPDATE user SET password = :password WHERE pseudo = :pseudo');
                $req->execute(array('password' => $MDP_HACH,
                    'pseudo' => $_POST['pseudo']));

                $req = $bdd->prepare('SELECT * FROM user WHERE pseudo = ?') or die(mysql_error());
                $req->execute(array($_POST['pseudo']));

                $mdp_hac = sha1($_POST['password']);

                while ($donnees = $req->fetch())
                {
                    $_SESSION['firstname'] = $donnees['firstname'];
                    $_SESSION['secondname'] = $donnees['secondname'];
                    $_SESSION['pseudo'] = $donnees['pseudo'];
                    $_SESSION['email'] = $donnees['email'];
                    $_SESSION['age'] = $donnees['age'];
                    $_SESSION['country'] = $donnees['country'];
                    $_SESSION['msg_perso'] = $donnees['msg_perso'];
                    $_SESSION['rang'] = $donnees['rang'];
                    $_SESSION['img_name'] = $donnees['img_name'];

                    $date = $donnees['date_creation'];
                    $date = date("d.m.Y H:i", $date);

                    $_SESSION['date_creation'] = $date;

                    $req = $bdd->prepare('UPDATE user SET statut = "connecte" WHERE pseudo = ?');
                    $req->execute(array($_SESSION['pseudo']));

                    echo 'true';
                }

                $req->closeCursor();
            }
        }
        else
        {
            echo '<div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <h4>Erreur !</h4>
                    Les deux mots de passe ne correspondent pas.
                  </div>';
        }
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////// Signal bug //////////////////////////////////////////

if($_GET['a'] == 'signalbug')
{
    $destinataire = "the.kiwiidu06@gmail.com";
    $subject = 'Signal bug';
	
	if($_SESSION['pseudo'] != NULL)
	{
		$body = $_SESSION['pseudo'] . ' : ' . $_POST['message'];
	}
	else
	{
		$body = 'Visiteur : ' . $_POST['message'];
	}

/////voici la version Mine
    $headers = "MIME-Version: 1.0\r\n";

//////ici on d�termine le mail en format texte
    $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";

////ici on d�termine l'expediteur et l'adresse de r�ponse
    $headers .= "From: POX <noreply@pox.com>\r\nReply-to : POX <noreply@pox.com>\nX-Mailer:PHP";

    if (mail($destinataire,$subject,$body,$headers))
    {
        echo '<script type="text/javascript">window.top.window.location.href = "index.php";</script>';
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////// Contact /////////////////////////////////////////////

if($_GET['a'] == 'contact')
{
    $destinataire="the.kiwiidu06@gmail.com";
    $subject= $_POST['objet'];
    $body= $_POST['message'];

/////voici la version Mine
    $headers = "MIME-Version: 1.0\r\n";

//////ici on d�termine le mail en format texte
    $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";

    $nom = $_POST['nom'];
    $email = $_POST['email'];

////ici on d�termine l'expediteur et l'adresse de r�ponse
    $headers .= "From: $nom <$email>\r\nReply-to : $nom <$email>\nX-Mailer:PHP";

    if (mail($destinataire,$subject,$body,$headers))
    {
        $_SESSION['result'] = 'newmail_contact';
        header('Location: contact.php');
    }
    else
    {
        $_SESSION['result'] = 'newmail_not_contact';
        header('Location: contact.php');
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////// Assistance /////////////////////////////////////////////

if ($_GET['a'] == 'addsubject')
{
    $bdd = bdd_connect_site();

    if($_POST['forum'] == 'Help' || $_POST['forum'] == 'Aide') { $forum = 'help'; }
    elseif($_POST['forum'] == 'Problems' || $_POST['forum'] == 'Problèmes') { $forum = 'problem'; }
    elseif($_POST['forum'] == 'Suggests' || $_POST['forum'] == 'Suggestions') { $forum = 'suggest'; }
    elseif($_POST['forum'] == 'Various' || $_POST['forum'] == 'Divers') { $forum = 'various'; }

    $req = $bdd->prepare('INSERT into forum (forum,subject,pseudo,message,language,date) values (:forum,:subject,:pseudo,:message,:language,:date)');
    $req->execute(array('forum' => $forum,
                        'sujet' => $_POST['subject'],
                        'pseudo' => $_SESSION['pseudo'],
                        'message' => $_POST['message'],
                        'langue' => $_SESSION['language'],
                        'date' => $date));

    header('Location: forum.php?f=' . $forum);

}
elseif ($_GET['a'] == 'answersubject')
{
    $bdd = bdd_connect_site();

    if($_SESSION['pseudo'] != NULL)
    {
        $req = $bdd->prepare('INSERT into forum (id_subject,pseudo,message,date) values (:id_subject,:pseudo,:message,:date)');
        $req->execute(array('id_subject' => $_POST['id'],
                            'pseudo' => $_SESSION['pseudo'],
                            'message' => $_POST['message'],
                            'date' => $date));
    }
    else
    {
        $req = $bdd->prepare('INSERT into forum (id_subject,pseudo,message,date) values (:id_subject,"Anonymous",:message,:date)');
        $req->execute(array('id_subject' => $_POST['id'],
                            'message' => $_POST['message'],
                            'date' => $date));
    }

    header('Location: forum.php?id=' . $_POST['id']);
}
elseif($_GET['a'] == 'solvesubject')
{
    $bdd = bdd_connect_site();

    if($_SESSION['pseudo'] != NULL && $_GET['id'] != NULL)
    {
        $req = $bdd->prepare('SELECT pseudo FROM forum WHERE id = ? ORDER BY id') or die(mysql_error());
        $req->execute(array($_GET['id']));

        $donnees = $req->fetch();

        if($donnees['pseudo'] == $_SESSION['pseudo'])
        {
            $req = $bdd->prepare('UPDATE forum SET solve = "1" WHERE id = ?') or die(mysql_error());
            $req->execute(array($_GET['id']));

            header('Location: forum.php?id=' . $_GET['id']);
        }
        else
        {
            header('Location forum.php?id=' . $_GET['id']);
        }
    }
    else
    {
        header('Location: forum.php');
    }
}
elseif($_GET['a'] == 'deletesubject')
{
    $bdd = bdd_connect_site();

    if($_SESSION['pseudo'] != NULL && $_GET['id'] != NULL)
    {
        $req = $bdd->prepare('SELECT pseudo FROM forum WHERE id = ? ORDER BY id') or die(mysql_error());
        $req->execute(array($_GET['id']));

        $donnees = $req->fetch();

        if($donnees['pseudo'] == $_SESSION['pseudo'])
        {
            $req = $bdd->prepare('DELETE FROM forum WHERE id = ?') or die(mysql_error());
            $req->execute(array($_GET['id']));

            $req = $bdd->prepare('DELETE FROM forum WHERE id_subject = ?') or die(mysql_error());
            $req->execute(array($_GET['id']));

            header('Location: forum.php');
        }
        else
        {
            header('Location forum.php');
        }
    }
    else
    {
        header('Location: forum.php');
    }
}
elseif ($_GET['a'] == 'savechangemessage')
{
    $req = $bdd->prepare('UPDATE forum SET message = :message WHERE id = :id');
    $req->execute(array('message' => $_POST['message'],
                        'id' => $_POST['id']));

    header('Location: forum.php?id=' . $_POST['id'] . '&type=setting');
}

///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////// Programs & Tutorials ////////////////////////////////

if($_GET['a'] == 'addcomment')
{
    $bdd = bdd_connect_site();

    if($_SESSION['pseudo'] != NULL)
    {
        $req = $bdd->prepare('INSERT into comment (pseudo,message,program,date) values (:pseudo,:message,:program,:date)');
        $req->execute(array('pseudo' => $_SESSION['pseudo'],
                            'message' => $_POST['message'],
                            'program' => $_POST['program'],
                            'date' => $date));
    }
    else
    {
        $req = $bdd->prepare('INSERT into comment (pseudo,message,program,date) values ("Anonymous",:message,:program,:date)');
        $req->execute(array(
            'message' => $_POST['message'],
            'program' => $_POST['program'],
            'date' => $date));
    }

    echo '<script type="text/javascript">window.top.window.location.href = "programs.php?p=' . $_POST['program'] . '";</script>';
}
elseif($_GET['a'] == 'addlike')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('INSERT into compteur (type,pseudo,likeit) values (:type,:pseudo,"1")');
    $req->execute(array('type' => $_GET['p'],
                        'pseudo' => $_SESSION['pseudo']));

    echo '<script type="text/javascript">window.top.window.location.href = "programs.php?p=' . $_GET['p'] . '";</script>';
}
elseif($_GET['a'] == 'addunlike')
{
    $bdd = bdd_connect_site();

    $req = $bdd->prepare('INSERT into compteur (type,pseudo,likeit) values (:type,:pseudo,"0")');
    $req->execute(array('type' => $_GET['p'],
                        'pseudo' => $_SESSION['pseudo']));

    echo '<script type="text/javascript">window.top.window.location.href = "programs.php?p=' . $_GET['p'] . '";</script>';
}

///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////// Registration ////////////////////////////////////////

$bdd = bdd_connect_base();

if($_GET['a'] == 'registration')
{
    $erreurs = array(); //tableau qui stocke les erreurs.

    if($_POST['pseudo'] != NULL || $_POST['password1'] != NULL || $_POST['password2'] != NULL || $_POST['email'] != NULL || $_POST['pays'] != NULL)
    {
        $req = $bdd->prepare('SELECT COUNT(*) as exist FROM user WHERE pseudo= ?');
        $req->execute(array($_POST['pseudo']));

        while($donnees = $req->fetch())
        {
            if ($donnees['exist'] > 0)
            {
                $erreurs[] = getText1('register.text.12');
            }
            else
            {
                if (!preg_match('~^[a-zA-Z0-9\._-]{4,20}$~', $_POST['pseudo']))
                {
                    $erreurs[] = getText1('register.text.13');
                }
            }
        }

        if (!preg_match('~^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$~', $_POST['email']))
        {
            $erreurs[] = getText1('register.text.14');
        }

        if (strlen($_POST['password1']) < 4)
        {
            $erreurs[] = getText1('register.text.15');
        }

        if ($_POST['password1'] != $_POST['password2'])
        {
            $erreurs[] = getText1('register.text.16');
        }

    }
    else
    {
        $erreurs[] = getText1('register.text.17');
    }


    if (count($erreurs) == 0)
    {
        $nb_min = 1;
        $nb_max = 100;
        $nombre = mt_rand($nb_min,$nb_max);

        $crypte = sha1($nombre);

        $mdp_hac = sha1($_POST['password1']);

        $req = $bdd->prepare('INSERT into user (pseudo,password,email,country,ip,crypte,date) values (:pseudo,:password,:email,:country,:ip,:crypte,:date)');
        $req->execute(array('pseudo' => $_POST['pseudo'],
                            'password' => $mdp_hac,
                            'email' => $_POST['email'],
                            'country' => $_POST['pays'],
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'crypte' => $crypte,
                            'date' => $date));

        $req = $bdd->prepare('INSERT into event (pseudo,type,date) values (:pseudo,"newaccount",:date)') or die(mysql_error());
        $req->execute(array('pseudo' => $_POST['pseudo'],
                            'date' => $date));

        $destinataire= $_POST['email'];

        if($_SESSION['language'] == 'fr')
        {
            $subject= 'Inscription sur Pox !';
            $body= 'Bonjour, vous êtes maintenant inscrit sur Pox, vous pourrez accèder à toutes les fonctionnalités présentées. Mais avant cela vous devez valider votre compte en cliquant sur ce lien : http://pox.alwaysdata.net/register.php?confirm=' . $_POST['pseudo'] . '&crypte=' . $crypte . '. Si vous ne vous êtes pas inscrit à Pox, vous pouvez supprimer ce compte en cliquant sur ce lien : http://pox.alwaysdata.net/register.php?delete=' . $_POST['pseudo'] . '&crypte=' . $crypte . '. Cordialement, l\'équipe de Pox !';
        }
        else
        {
            $subject= 'Registration on Pox !';
            $body= 'Welcome, your are now registred on Pox, but you must previous activate your account clicking on this link : http://pox.alwaysdata.net/register.php?confirm=' . $_POST['pseudo'] . '&crypte=' . $crypte . '. If you aren\'t registred on Pox, you can delete this account clicking on this link : http://pox.alwaysdata.net/register.php?delete=' . $_POST['pseudo'] . '&crypte=' . $crypte . '. Cordialement, l\'équipe de Pox !';
        }

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $headers .= "From: POX <no-reply@pox.com>\r\nReply-to : POX <no-reply@pox.com>\nX-Mailer:PHP";

        if (mail($destinataire,$subject,$body,$headers))
        {
            echo "true"; //cette valeur sera trait� par ajax est vaut dire que tt passe pour le bien
        }
    }
    else
    {
        echo '<div class="alert alert-error"><h4>' . getText1('alert.title.1') . '</h4><p>';

        for ($i = 0; $i < count($erreurs); $i++) {
            echo "- " . $erreurs[$i] . "<br />";
        }

        echo '</p></div>';
    }
}
elseif($_GET['a'] == 'verifcrypte')
{
    if($_POST['pseudo'] != NULL && $_POST['crypte'] != NULL)
    {
        $req = $bdd->prepare('SELECT crypte FROM user WHERE pseudo = ? ORDER BY id') or die(mysql_error());
        $req->execute(array($_POST['pseudo']));

        $donnees = $req->fetch();

        if($donnees['crypte'] == $_POST['crypte'])
        {
            $req = $bdd->prepare('UPDATE user SET valid = "1" WHERE pseudo = ?');
            $req->execute(array($_POST['pseudo']));

            $_SESSION['result'] = 'crypte_register';
            echo '<script type="text/javascript">window.top.window.location.href = "index.php";</script>';
        }
        else
        {
            $_SESSION['result'] = 'crypte_not_register';
            echo '<script type="text/javascript">window.top.window.location.href = "register.php?confirm=ok";</script>';
        }
    }
    else
    {
        $_SESSION['result'] = 'crypte_notneed_register';
        echo '<script type="text/javascript">window.top.window.location.href = "register.php?confirm=ok";</script>';
    }
}
elseif($_GET['a'] == 'deleteaccount')
{
    if($_POST['pseudo'] != NULL && $_POST['crypte'] != NULL)
    {
        $req = $bdd->prepare('SELECT crypte FROM user WHERE pseudo = ? ORDER BY id') or die(mysql_error());
        $req->execute(array($_POST['pseudo']));

        $donnees = $req->fetch();

        if($donnees['crypte'] == $_POST['crypte'])
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
            $_SESSION['result'] = 'deleteaccount_not_register';
            echo '<script type="text/javascript">window.top.window.location.href = "register.php?delete=ok";</script>';
        }
    }
    else
    {
        $_SESSION['result'] = 'deleteaccount_notneed_register';
        echo '<script type="text/javascript">window.top.window.location.href = "register.php?delete=ok";</script>';
    }
}
