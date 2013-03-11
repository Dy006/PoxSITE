<?php session_start();

if($_SESSION['result'] == 'waitemail_resetpassword')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            ' . getText1('alert.text.14') . '
          </div>';
}
elseif($_SESSION['result'] == 'emailnotexist_resetpassword')
{
    echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>' . getText1('alert.title.1') . '</h4>
            ' . getText1('alert.text.15') . '
          </div>';
}
elseif($_SESSION['result'] == 'cryptecodenotexist_resetpassword')
{
    echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>' . getText1('alert.title.1') . '</h4>
            ' . getText1('alert.text.16') . '
          </div>';
}
elseif($_SESSION['result'] == 'deleteaccount_register')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            ' . getText1('alert.text.12') . '
          </div>';
}
else if($_SESSION['result'] == 'deleteaccount_not_register')
{
    echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>' . getText1('alert.title.1') . '</h4>
            ' . getText1('alert.text.10') . '
          </div>';
}
else if($_SESSION['result'] == 'deleteaccount_notneed_register')
{
    echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>' . getText1('alert.title.1') . '</h4>
            ' . getText1('alert.text.11') . '
          </div>';
}
else if($_SESSION['result'] == 'crypte_register')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            ' . getText1('alert.text.9') . '
          </div>';
}
else if($_SESSION['result'] == 'crypte_not_register')
{
    echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>' . getText1('alert.title.1') . '</h4>
            ' . getText1('alert.text.10') . '
          </div>';
}
else if($_SESSION['result'] == 'crypte_notneed_register')
{
    echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>' . getText1('alert.title.1') . '</h4>
            ' . getText1('alert.text.11') . '
          </div>';
}
else if($_SESSION['result'] == 'newmail_contact')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            ' . getText1('alert.text.4') . '
          </div>';
}
else if($_SESSION['result'] == 'newmail_not_contact')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>' . getText1('alert.title.1') . '</h4>
            ' . getText1('alert.text.5') . '
          </div>';
}
else if($_SESSION['result'] == 'addmails_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            Votre mail a bien été envoyé.
          </div>';
}
elseif($_SESSION['result'] == 'deletemails_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            Le mail a bien été supprimé.
          </div>';
}
elseif($_SESSION['result'] == 'answermails_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
           La réponse a bien été envoyée.
          </div>';
}
elseif($_SESSION['result'] == 'addinvcontact_true_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            La demande a bien été envoyé.
          </div>';
}
elseif($_SESSION['result'] == 'addinvcontact_samecontact_co')
{
    echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Erreur</h4>
            Vous avez déjà ce contact dans votre liste.
          </div>';
}
elseif($_SESSION['result'] == 'addinvcontact_same_co')
{
    echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Erreur</h4>
            Vous ne pouvez pas vous ajouter vous même.
          </div>';
}
elseif($_SESSION['result'] == 'deleteinvcontact_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            La demande a bien été supprimée.
          </div>';
}
elseif($_SESSION['result'] == 'addcontact_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            Le contact a bien été ajouté.
          </div>';
}
elseif($_SESSION['result'] == 'deletecontact_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            Le contact a bien été supprimé.
          </div>';
}
elseif($_SESSION['result'] == 'participerok_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            Votre demande de participation au projet Pox a bien été envoyée. Si vous vous ne recevez pas de réponse au bout de quelques semaines c\'est qu\'elle n\'a pas été acceptée.
          </div>';
}
elseif($_SESSION['result'] == 'addprogramok_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            Votre programme a bien été envoyé. Si vous vous ne recevez pas de réponse au bout de quelques semaines c\'est qu\'il n\'a pas été accepté.
          </div>';
}
elseif($_SESSION['result'] == 'addupdateprogramok_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            La mise à jour du programme a bien été publiée, nous vous faisons une entière confiance sur cette mise à jour.
          </div>';
}
elseif($_SESSION['result'] == 'acceptprogram_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            Votre programme a bien été ajouté à la liste.
          </div>';
}
elseif($_SESSION['result'] == 'updateprogramok_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            La mise à jour des informations du programme a bien été faite.
          </div>';
}
elseif($_SESSION['result'] == 'refuseprogram_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            Le programme a bien été supprimer de la liste des demandes.
          </div>';
}
elseif($_SESSION['result'] == 'addnews_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            Nous venons d\'ajouter avec succès votre actualité.
          </div>';
}
elseif($_SESSION['result'] == 'deletenews_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            Nous venons de supprimer avec succès l\'actualité.
          </div>';
}
elseif($_SESSION['result'] == 'participernotok_co')
{
    echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Erreur</h4>
            Vous avez déjà ce contact dans votre liste.
          </div>';
}
elseif($_SESSION['result'] == 'addparticipation_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            La confirmation de participation au projet Pox a bien été envoyée.
          </div>';
}
elseif($_SESSION['result'] == 'deleteparticipation_co')
{
    echo '<div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Information</h4>
            La demande de participation a bien été supprimée.
          </div>';
}
elseif($_SESSION['result'] == 'notsendmail')
{
    echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Erreur</h4>
            Il est impossible de vous envoyer un mail, retentez dans quelques minutes.
          </div>';
}
elseif($_SESSION['result'] == 'nottruemail')
{
    echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Erreur</h4>
            Votre email ou votre pseudo est incorrect.
          </div>';
}
elseif($_SESSION['result'] == 'nottruecode')
{
    echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Erreur</h4>
            Le code que vous avez rentrez est incorrect.
          </div>';
}
elseif($_SESSION['result'] == 'passwordnotsame')
{
    echo '<div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4>Erreur</h4>
            Les deux mots de passe ne sont pas identiques.
          </div>';
}

$_SESSION['result'] = NULL;
