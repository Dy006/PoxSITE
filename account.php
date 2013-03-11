<?php include "menu.php";

echo '<div id="message"></div>';

    if($_SESSION['pseudo'] != NULL)
    {
        if($_GET['type'] == 'contact')
        {?>
            <script type="text/javascript">tab_change_focus('li_11','1','2')</script>

            <h3><?php echo getText1('account.contact.title.1'); ?></h3>
            <?php get_contact(); ?>
            </br>

            <h3><?php echo getText1('account.contact.title.2'); ?></h3>
            <?php get_inv_contact(); ?>
            </br>

            <h3><?php echo getText1('account.contact.title.3'); ?></h3>
            <form method="post" action="bdd.php?a=addinvcontact" class="form-inline">
                <select class="span3" data-provide="typeahead" data-items="4">
                    <?php get_list_contact_pox(); ?>
                </select>
                <input type="text" name="message"/>
                <input type="submit" class="btn" value="<?php echo getText1('account.contact.button.2'); ?>"/></td>
            </form>

        <?php
        }
        elseif($_GET['type'] == 'mails')
        {
            echo '<script type="text/javascript">tab_change_focus("li_12","1","2")</script>'; ?>

            <div class="row">
                <div class="span2">
                    <ul class="nav nav-list nav-stacked">
                        <li class="nav-header">Mails</li>
                        <li id="li_1m" class="active"><a href="?type=mails" onclick="tab_change_focus('li_1m','7');"><?php echo getText1('account.mails.nav.1'); ?></a></li>
                        <li id="li_2m"><a href="?type=mails&a=new" onclick="tab_change_focus('li_2m','7');"><?php echo getText1('account.mails.nav.2'); ?></a></li>
                        <li class="divider"></li>
                        <?php if($_SESSION['rang'] == 'superadmin') { ?> <li id="li_3m"><a href="?type=mails&admin=true" onclick="tab_change_focus('li_3m','7','1');"><?php echo getText1('account.mails.nav.3'); ?></a></li> <?php } ?>
                        <li><a href="faq.php"><?php echo getText1('account.mails.nav.4'); ?></a></li>
                    </ul>
                </div>
                <div class="span10">
                    <?php
                    if($_GET['id'] != NULL)
                    {
                        echo '<script type="text/javascript">tab_change_focus("li_1m","7")</script>';

                        get_info_mails($_GET['id']);
                    }
                    elseif($_GET['a'] != NULL)
                    {
                        echo '<script type="text/javascript">tab_change_focus("li_2m","7")</script>'; ?>

                        <div class="row">
                            <div class="span8 offset2">
                                <script src="tutorial/ckeditor_basic/ckeditor.js"></script>
                                <form name="addmails_form" enctype="application/x-www-form-urlencoded" method="POST" action="bdd.php?a=addmails">
                                    <select name="receiver" class="span3" data-provide="typeahead" data-items="4">
                                        <?php get_contact_mails(); ?>
                                    </select>
                                    <input type="text" name="object" placeholder="<?php echo getText1('account.mails.text.2'); ?>">
                                    <p>
                                        <textarea id="editor1" name="body"></textarea>
                                        <script type="text/javascript">
                                            CKEDITOR.replace( "editor1" );
                                        </script>
                                    </p>
                                    <input name="Submit" value="<?php echo getText1('account.mails.button.1'); ?>" type="submit" class="btn btn-primary">
                                </form>
                            </div>
                        </div>

                    <?php
                    }
                    elseif($_GET['admin'] != NULL)
                    {
                        if($_SESSION['rang'] == 'superadmin')
                        {
                            echo '<script type="text/javascript">tab_change_focus("li_3m","7","1")</script>'; ?>

                            <h3>Demandes de participation</h3>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Participant</th>
                                    <th>Language(s)</th>
                                    <th>IDE(S)</th>
                                    <th>Motivation</th>
                                    <th>Options</th>
                                    <th> </th>
                                    <th>Envoyé le</th>
                                </tr>
                                </thead>
                                <?php get_participe_toadd(); ?>
                            </table>

                            </br></br>
                            <h3>New tutorials</h3>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Pseudo</th>
                                    <th>Title</th>
                                    <th>Language</th>
                                    <th>Difficult</th>
                                    <th>Description</th>
                                    <th>Settings</th>
                                    <th>Sent the</th>
                                </tr>
                                </thead>
                                <?php get_tutorials_toadd(); ?>
                            </table>

                            </br></br>
                            <h3>Nouveaux programmes</h3>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Développeur(s) / Désigner(s)</th>
                                    <th>Nom du programme</th>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Description</th>
                                    <th>Prochaines nouveautés</th>
                                    <th>Informations</th>
                                    <th>Options</th>
                                    <th>Envoyé le</th>
                                </tr>
                                </thead>
                                <?php get_programs_toadd(); ?>
                            </table>

                        <?php
                        }
                        else
                        {
                            echo '<script type="text/javascript">window.top.window.location.href = "account.php?type=mails";</script>';
                        }
                    }
                    else
                    {
                        get_mails($_GET['page']);
                        number_page_mails();
                    }?>
                </div>
            </div>

        <?php
        }
        elseif($_GET['type'] == 'tutorials')
        {
            echo '<script type="text/javascript">tab_change_focus("li_15","1","2")</script>'; ?>

            <center>
                <div class="btn-toolbar" style="margin: 0;">
                    <a class="btn" href="?type=tutorials"><?php echo getText1('account.tutorials.button.1'); ?></a>
                    <a class="btn" href="?type=tutorials&a=new"><?php echo getText1('account.tutorials.button.2'); ?></a>
                </div>
            </center>
            </br>

            <?php
            if($_GET['a'] == 'new')
            {?>
            <center>
                <form name="formulaire" enctype="multipart/form-data" method="post" class="form-vertical" action="bdd.php?a=addtutorial">
                    <h1>Form for new tutorial</h1></br>
                    <p>Bonjour, si vous avez choisit ce formulaire, c'est qui vous voulez contribuer au projet et donc ajouter un de vos tutoriel sur notre site. Nous vous demanderons juste quelques informations sur celui-ci puis après ce formulaire, vous pourrez le modifier ou ajouter du contenu.</p></br>
                    <div class="control-group">
                        <label class="control-label">Name of tutorial</label>

                        <div class="controls">
                            <input type="text" name="title">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Language</label>

                        <div class="controls">
                            <select name="language">
                                <option>English</option>
                                <option>French</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Difficult</label>

                        <div class="controls">
                            <select name="difficult">
                                <option>easy</option>
                                <option>medium</option>
                                <option>hard</option>
                            </select>
                        </div>
                    </div>

                    Description
                    <br/>
                    <textarea rows="4" name="description"></textarea>
                    <br/>
                    <input name="Submit" value="Ajouter" type="submit" class="btn btn-primary">
                    <p></br>Votre tutoriel passera d'abbord par une équipe de vérification avant d'être publié.</p>
                </form>
            </center>

            <?php
            }
            elseif($_GET['t'] != NULL)
            {
                get_change_tutorial($_GET['t']);
            }
            else
            {
                get_list_tutorials_user();
            }
        }
        elseif($_GET['type'] == 'programs')
        {
            echo '<script type="text/javascript">tab_change_focus("li_16","1","2")</script>';

            $bdd = bdd_connect_base();

            $req = $bdd->prepare('SELECT participe FROM user WHERE pseudo = ?');
            $req->execute(array($_SESSION['pseudo']));

            $donnees = $req->fetch();

            if($donnees['participe'] == '1')
            {?>
            <center>
                <div class="btn-toolbar" style="margin: 0;">
                    <a class="btn" href="?type=programs"><?php echo getText1('account.programs.button.1'); ?></a>
                    <a class="btn" href="?type=programs&a=new"><?php echo getText1('account.programs.button.2'); ?></a>
                </div>
            </center>
            </br>

            <?php
                if($_GET['a'] == 'new')
                {?>
                <center>
                    <form name="formulaire" enctype="multipart/form-data" method="post" class="form-vertical" action="bdd.php?a=addprogram">
                        <h1>Formulaire pour un nouveau programme</h1></br>
                        <p>Bonjour, si vous avez choisit ce formulaire, c'est que vous voulez ajouter votre programme sur le site. Mais avant cela vous devez suivre les consignes ci-dessous et remplir tous les champs. Vous devrez, lors de la demande du programme, fournir un installateur de celui-ci.</p><p class="text-warning">ATTENTION : Vous devez séparez les mots d'une virgule pour les champs avec plusieurs choix (sauf pour la description).</p></br>
                        <div class="control-group">
                            <label class="control-label">Nom du programme (accents, espaces, majuscules, ...)</label>

                            <div class="controls">
                                <input type="text" name="truename">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Nom du programme (sans accents, espaces, majuscules, ...)</label>

                            <div class="controls">
                                <input type="text" name="program">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Catégorie</label>

                            <div class="controls">
                                <select name="category">
                                    <option>Programs</option>
                                    <option>Web</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Version (sans les points) EX : 100 pour 1.0.0</label>

                            <div class="controls">
                                <input type="text" name="version">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Langue</label>

                            <div class="controls">
                                <input type="text" name="langue">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Développeur (sans vous compter)</label>

                            <div class="controls">
                                <input type="text" name="developpeur">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Désigner</label>

                            <div class="controls">
                                <input type="text" name="designer">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Nouveautés à venir</label>

                            <div class="controls">
                                <input type="text" name="nextnews">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Programme</label>
                            <input type="file" name="file_program"/>
                        </div>

                        Description
                        <br/>
                        <textarea rows="4" name="description"></textarea>
                        <br/>
                        <input name="Submit" value="Ajouter" type="submit" class="btn btn-primary">
                        <p></br>Votre programme ne sera pas ajouté directement mais passera par une équipe de vérification. Si votre programme est accepté, vous pourrez modifier sa page de présentation (avec vérification par la suite), sortir de nouvelles versions ou le supprimer sur demande.</p>
                    </form>
                </center>

                <?php
                }
                elseif($_GET['p'] != NULL)
                {?>

                <div class="row">
                    <div class="span2">
                        <ul class="nav nav-pills nav-stacked">
                            <li class="nav-header"><?php echo $_GET['p']; ?></li>
                            <li id="li_1p" class="active"><a onclick="tab_change_focus('li_1p','8');" href="?type=programs&p=<?php echo $_GET['p']; ?>"><?php echo getText1('account.programs.nav.1'); ?></a></li>
                            <li id="li_2p"><a onclick="tab_change_focus('li_2p','8');" href="?type=programs&p=<?php echo $_GET['p']; ?>&a=change"><?php echo getText1('account.programs.nav.2'); ?></a></li>
                            <li id="li_3p"><a onclick="tab_change_focus('li_3p','8');" href="?type=programs&p=<?php echo $_GET['p']; ?>&a=update"><?php echo getText1('account.programs.nav.3'); ?></a></li>
                        </ul>
                        </br>
                        <?php get_statut_program($_GET['p']); ?>
                    </div>

                    <?php
                    if($_GET['a'] == 'change')
                    {
                        echo '<script type="text/javascript">tab_change_focus("li_2p","8")</script>';

                        get_program_change($_GET['p']);
                    }
                    elseif($_GET['a'] == 'update')
                    {
                        echo '<script type="text/javascript">tab_change_focus("li_3p","8")</script>';

                        get_list_update_program($_GET['p'],$_GET['page']);
                    }
                    else
                    {
                        echo '<script type="text/javascript">tab_change_focus("li_1p","8")</script>';

                        get_program($_GET['p']);
                    }

                    echo '</div>';
                }
                else
                {
                    get_list_program();
                }
            }
            else
            {
                $req = $bdd->prepare('SELECT pseudo FROM participants WHERE pseudo = ?');
                $req->execute(array($_SESSION['pseudo']));

                $donnees = $req->fetch();

                if($donnees['pseudo'] != NULL)
                {
                    echo '<p>You must wait.</p>';
                }
                else
                {
                    if($_GET['a'] == 'participe')
                    {?>
                    <center>
                        <form name="formulaire" enctype="application/x-www-form-urlencoded" method="post" class="form-vertical" action="bdd.php?a=addparticipation">
                            <h1><?php echo getText1('participe.title.1'); ?></h1></br>
                            <p><?php echo getText1('participe.text.1'); ?></p><p class="text-warning"><?php echo getText1('participe.text.2'); ?></p></br>
                            <div class="control-group">
                                <label class="control-label"><?php echo getText1('participe.text.3'); ?></label>

                                <div class="controls">
                                    <input type="text" name="language">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo getText1('participe.text.4'); ?></label>

                                <div class="controls">
                                    <input type="text" name="ide">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><?php echo getText1('participe.text.5'); ?></label>

                                <div class="controls">
                                    <input type="text" name="appris">
                                </div>
                            </div>

                            <?php echo getText1('participe.text.6'); ?>
                            <br/>
                            <textarea rows="3" name="motivation"></textarea>
                            <br/>
                            <div class="btn-group">
                                <input name="Submit" value="<?php echo getText1('participe.button.1'); ?>" type="submit" class="btn btn-primary">
                            </div>
                        </form>
                    </center>

                        <?php
                    }
                    else
                    {
                        echo '<center>
                            <h1>Participe to Pox project</h1>
                            <p>Hi ! To publish programs, you must to inform informations of you. Your informations will help the community when she download your programs and when she will invite you to participe in other projects.</p>
                            </br>
                            <a href ="?type=programs&a=participe" class="btn btn-success btn-large">Participe now !</a>
                          </center>';
                    }
                }
            }

            $req->closeCursor();
        }
        elseif($_GET['type'] == 'news')
        {
            if($_SESSION['rang'] == 'superadmin')
            {
                echo '<script type="text/javascript">tab_change_focus("li_13","1","2","1")</script>';

                echo '<div class="row"><div class="span5 offset4"><h1>' . getText1('account.news.title.1') . '</h1></div><div class="span2"><a class="btn btn-primary btn-large" href="news.php">' . getText1('account.news.button.1') . '</a></div></div>';

                get_list_news_account($_GET['page']); ?>

                <form name="formulaire" enctype="application/x-www-form-urlencoded" method="post" action="bdd.php?a=addnews">
                    <center><h4><?php echo getText1('account.news.title.2'); ?></h4></center>
					<div class="row">
						<div class="span2">
							<select name="class" style="width: 195px;">
								<option>website</option>
								<option>program</option>
							</select>
						</div>
						<div class="span10">
							<input type="text" name="title" style="width: 954px;" placeholder="<?php echo getText1('account.news.text.2'); ?>">
						</div>
					</div>
                    <textarea rows="3"  style="width: 1155px;" name="text" placeholder="<?php echo getText1('account.news.text.3'); ?>"></textarea>
                    <br/>
                    <center><input name="Submit" value="<?php echo getText1('account.news.button.2'); ?>" type="submit" class="btn btn-primary"></center>
                </form>

            <?php
            }
            else
            {
                echo '<script type="text/javascript">window.top.window.location.href = "account.php";</script>';
            }
        }
        elseif($_GET['type'] == 'faq')
        {
            if($_SESSION['rang'] == 'superadmin')
            {
                echo '<script type="text/javascript">tab_change_focus("li_14","1","2","1")</script>';

                echo '<div class="row"><div class="span5 offset4"><h1>' . getText1('account.faq.title.1') . '</h1></div><div class="span2"><a class="btn btn-primary btn-large" href="faq.php">' . getText1('account.faq.button.1') . '</a></div></div></br>';

                get_list_faq(); ?>

                <form name="formulaire" enctype="application/x-www-form-urlencoded" method="post" action="bdd.php?a=addfaq">
                    <center><h4><?php echo getText1('account.faq.title.2'); ?></h4></center>
                    <input type="text" name="question" style="width: 1155px;" placeholder="<?php echo getText1('account.faq.text.1'); ?>">
                    <textarea rows="3" name="text" style="width: 1155px;" placeholder="<?php echo getText1('account.faq.text.2'); ?>"></textarea>
                    <br/>
                    <center><input name="Submit" value="<?php echo getText1('account.faq.button.2'); ?>" type="submit" class="btn btn-primary"></center>
                </form>

            <?php
            }
            else
            {
                echo '<script type="text/javascript">window.top.window.location.href = "account.php";</script>';
            }
        }
        else
        {
            echo '<script type="text/javascript">tab_change_focus("li_10","1","2")</script>';

            if($_GET['a'] != NULL)
            {?>
               <div class="row">
                   <div class="span3">
                       <?php
                       if($_SESSION['img_name'])
                       {
                           echo '<a href="#" class="thumbnail">
                                    <img data-src="holder.js/260x180" alt="' . $_SESSION['pseudo'] . '" style="width: 260px; height: 180px;" src="program/img_profile/' . $_SESSION['img_name'] . '">
                                 </a>';
                       }
                       else
                       {
                           echo '<a href="#" class="thumbnail">
                                    <img data-src="holder.js/260x180" alt="No image" style="width: 260px; height: 180px;" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image">
                                 </a>';
                       }?>
                   </div>
                   <div class="span9">
                       <form name="upload_img_profile" method="POST" action="bdd.php?a=uploadimgprofile" enctype="multipart/form-data">
                           <input type="hidden" name="MAX_FILE_SIZE" value="100000">
                           Fichier : <input type="file" name="img_profile">
                           <input type="submit" name="envoyer" value="Send">
                       </form>
                   </div>
               </div>

            <?php
            }
            else
            {?>

            <center><h1><?php echo getText1('account.title.1'); ?> <?php echo $_SESSION['pseudo']; ?></h1></center></br>

            <div class="row">
            <div class="span2">
            <form name="saveprofile" method="post" action="#" onsubmit="save_profile(this.password1.value, this.password2.value, this.email.value, this.age.value, this.country.value, this.firstname.value, this.secondname.value, this.msg_perso.value);return false;">
            <center><span class="label label-inverse"><?php echo getText1('account.title.2'); ?></span></center>
            <p></br></p>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-chevron-right"></i></span>
                        <input class="span2" id="inputIcon" type="text" placeholder="<?php echo getText1('account.text.5'); ?>" name="firstname" value="<?php echo $_SESSION['firstname']; ?>">
                    </div>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-chevron-right"></i></span>
                        <input class="span2" id="inputIcon" type="text" placeholder="<?php echo getText1('account.text.6'); ?>" name="secondname" value="<?php echo $_SESSION['secondname']; ?>">
                    </div>
                </div>
            </div>
            <p></br></p>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-lock"></i></span>
                        <input class="span2" id="inputIcon" type="password" placeholder="<?php echo getText1('account.text.1'); ?>" name="password1">
                    </div>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-circle-arrow-right"></i></span>
                        <input class="span2" id="inputIcon" type="password" placeholder="<?php echo getText1('account.text.2'); ?>" name="password2">
                    </div>
                </div>
            </div>
            <p></br></p>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-envelope"></i></span>
                        <input class="span2" id="inputIcon" type="text" placeholder="<?php echo getText1('account.text.3'); ?>" name="email" value="<?php echo $_SESSION['email']; ?>">
                    </div>
                </div>
            </div>
            <div class="control-group">
            <div class="controls">
            <div class="input-prepend">
            <span class="add-on"><i class="icon-globe"></i></span>
            <select name="country" style="width: 170px;">
            <option value="France">France </option>
            <option value="Afghanistan">Afghanistan </option>
            <option value="Afrique_Centrale">Afrique_Centrale </option>
            <option value="Afrique_du_sud">Afrique_du_Sud </option>
            <option value="Albanie">Albanie </option>
            <option value="Algerie">Algerie </option>
            <option value="Allemagne">Allemagne </option>
            <option value="Andorre">Andorre </option>
            <option value="Angola">Angola </option>
            <option value="Anguilla">Anguilla </option>
            <option value="Arabie_Saoudite">Arabie_Saoudite </option>
            <option value="Argentine">Argentine </option>
            <option value="Armenie">Armenie </option>
            <option value="Australie">Australie </option>
            <option value="Autriche">Autriche </option>
            <option value="Azerbaidjan">Azerbaidjan </option>
            <option value="Bahamas">Bahamas </option>
            <option value="Bangladesh">Bangladesh </option>
            <option value="Barbade">Barbade </option>
            <option value="Bahrein">Bahrein </option>
            <option value="Belgique">Belgique </option>
            <option value="Belize">Belize </option>
            <option value="Benin">Benin </option>
            <option value="Bermudes">Bermudes </option>
            <option value="Bielorussie">Bielorussie </option>
            <option value="Bolivie">Bolivie </option>
            <option value="Botswana">Botswana </option>
            <option value="Bhoutan">Bhoutan </option>
            <option value="Boznie_Herzegovine">Boznie_Herzegovine </option>
            <option value="Bresil">Bresil </option>
            <option value="Brunei">Brunei </option>
            <option value="Bulgarie">Bulgarie </option>
            <option value="Burkina_Faso">Burkina_Faso </option>
            <option value="Burundi">Burundi </option>
            <option value="Caiman">Caiman </option>
            <option value="Cambodge">Cambodge </option>
            <option value="Cameroun">Cameroun </option>
            <option value="Canada">Canada </option>
            <option value="Canaries">Canaries </option>
            <option value="Cap_vert">Cap_Vert </option>
            <option value="Chili">Chili </option>
            <option value="Chine">Chine </option>
            <option value="Chypre">Chypre </option>
            <option value="Colombie">Colombie </option>
            <option value="Comores">Colombie </option>
            <option value="Congo">Congo </option>
            <option value="Congo_democratique">Congo_democratique </option>
            <option value="Cook">Cook </option>
            <option value="Coree_du_Nord">Coree_du_Nord </option>
            <option value="Coree_du_Sud">Coree_du_Sud </option>
            <option value="Costa_Rica">Costa_Rica </option>
            <option value="Cote_d_Ivoire">Côte_d_Ivoire </option>
            <option value="Croatie">Croatie </option>
            <option value="Cuba">Cuba </option>
            <option value="Danemark">Danemark </option>
            <option value="Djibouti">Djibouti </option>
            <option value="Dominique">Dominique </option>
            <option value="Egypte">Egypte </option>
            <option value="Emirats_Arabes_Unis">Emirats_Arabes_Unis </option>
            <option value="Equateur">Equateur </option>
            <option value="Erythree">Erythree </option>
            <option value="Espagne">Espagne </option>
            <option value="Estonie">Estonie </option>
            <option value="Etats_Unis">Etats_Unis </option>
            <option value="Ethiopie">Ethiopie </option>
            <option value="Falkland">Falkland </option>
            <option value="Feroe">Feroe </option>
            <option value="Fidji">Fidji </option>
            <option value="Finlande">Finlande </option>
            <option value="France">France </option>
            <option value="Gabon">Gabon </option>
            <option value="Gambie">Gambie </option>
            <option value="Georgie">Georgie </option>
            <option value="Ghana">Ghana </option>
            <option value="Gibraltar">Gibraltar </option>
            <option value="Grece">Grece </option>
            <option value="Grenade">Grenade </option>
            <option value="Groenland">Groenland </option>
            <option value="Guadeloupe">Guadeloupe </option>
            <option value="Guam">Guam </option>
            <option value="Guatemala">Guatemala</option>
            <option value="Guernesey">Guernesey </option>
            <option value="Guinee">Guinee </option>
            <option value="Guinee_Bissau">Guinee_Bissau </option>
            <option value="Guinee equatoriale">Guinee_Equatoriale </option>
            <option value="Guyana">Guyana </option>
            <option value="Guyane_Francaise ">Guyane_Francaise </option>
            <option value="Haiti">Haiti </option>
            <option value="Hawaii">Hawaii </option>
            <option value="Honduras">Honduras </option>
            <option value="Hong_Kong">Hong_Kong </option>
            <option value="Hongrie">Hongrie </option>
            <option value="Inde">Inde </option>
            <option value="Indonesie">Indonesie </option>
            <option value="Iran">Iran </option>
            <option value="Iraq">Iraq </option>
            <option value="Irlande">Irlande </option>
            <option value="Islande">Islande </option>
            <option value="Israel">Israel </option>
            <option value="Italie">italie </option>
            <option value="Jamaique">Jamaique </option>
            <option value="Jan Mayen">Jan Mayen </option>
            <option value="Japon">Japon </option>
            <option value="Jersey">Jersey </option>
            <option value="Jordanie">Jordanie </option>
            <option value="Kazakhstan">Kazakhstan </option>
            <option value="Kenya">Kenya </option>
            <option value="Kirghizstan">Kirghizistan </option>
            <option value="Kiribati">Kiribati </option>
            <option value="Koweit">Koweit </option>
            <option value="Laos">Laos </option>
            <option value="Lesotho">Lesotho </option>
            <option value="Lettonie">Lettonie </option>
            <option value="Liban">Liban </option>
            <option value="Liberia">Liberia </option>
            <option value="Liechtenstein">Liechtenstein </option>
            <option value="Lituanie">Lituanie </option>
            <option value="Luxembourg">Luxembourg </option>
            <option value="Lybie">Lybie </option>
            <option value="Macao">Macao </option>
            <option value="Macedoine">Macedoine </option>
            <option value="Madagascar">Madagascar </option>
            <option value="Madère">Madère </option>
            <option value="Malaisie">Malaisie </option>
            <option value="Malawi">Malawi </option>
            <option value="Maldives">Maldives </option>
            <option value="Mali">Mali </option>
            <option value="Malte">Malte </option>
            <option value="Man">Man </option>
            <option value="Mariannes du Nord">Mariannes du Nord </option>
            <option value="Maroc">Maroc </option>
            <option value="Marshall">Marshall </option>
            <option value="Martinique">Martinique </option>
            <option value="Maurice">Maurice </option>
            <option value="Mauritanie">Mauritanie </option>
            <option value="Mayotte">Mayotte </option>
            <option value="Mexique">Mexique </option>
            <option value="Micronesie">Micronesie </option>
            <option value="Midway">Midway </option>
            <option value="Moldavie">Moldavie </option>
            <option value="Monaco">Monaco </option>
            <option value="Mongolie">Mongolie </option>
            <option value="Montserrat">Montserrat </option>
            <option value="Mozambique">Mozambique </option>
            <option value="Namibie">Namibie </option>
            <option value="Nauru">Nauru </option>
            <option value="Nepal">Nepal </option>
            <option value="Nicaragua">Nicaragua </option>
            <option value="Niger">Niger </option>
            <option value="Nigeria">Nigeria </option>
            <option value="Niue">Niue </option>
            <option value="Norfolk">Norfolk </option>
            <option value="Norvege">Norvege </option>
            <option value="Nouvelle_Caledonie">Nouvelle_Caledonie </option>
            <option value="Nouvelle_Zelande">Nouvelle_Zelande </option>
            <option value="Oman">Oman </option>
            <option value="Ouganda">Ouganda </option>
            <option value="Ouzbekistan">Ouzbekistan </option>
            <option value="Pakistan">Pakistan </option>
            <option value="Palau">Palau </option>
            <option value="Palestine">Palestine </option>
            <option value="Panama">Panama </option>
            <option value="Papouasie_Nouvelle_Guinee">Papouasie_Nouvelle_Guinee </option>
            <option value="Paraguay">Paraguay </option>
            <option value="Pays_Bas">Pays_Bas </option>
            <option value="Perou">Perou </option>
            <option value="Philippines">Philippines </option>
            <option value="Pologne">Pologne </option>
            <option value="Polynesie">Polynesie </option>
            <option value="Porto_Rico">Porto_Rico </option>
            <option value="Portugal">Portugal </option>
            <option value="Qatar">Qatar </option>
            <option value="Republique_Dominicaine">Republique_Dominicaine </option>
            <option value="Republique_Tcheque">Republique_Tcheque </option>
            <option value="Reunion">Reunion </option>
            <option value="Roumanie">Roumanie </option>
            <option value="Royaume_Uni">Royaume_Uni </option>
            <option value="Russie">Russie </option>
            <option value="Rwanda">Rwanda </option>
            <option value="Sahara Occidental">Sahara Occidental </option>
            <option value="Sainte_Lucie">Sainte_Lucie </option>
            <option value="Saint_Marin">Saint_Marin </option>
            <option value="Salomon">Salomon </option>
            <option value="Salvador">Salvador </option>
            <option value="Samoa_Occidentales">Samoa_Occidentales</option>
            <option value="Samoa_Americaine">Samoa_Americaine </option>
            <option value="Sao_Tome_et_Principe">Sao_Tome_et_Principe </option>
            <option value="Senegal">Senegal </option>
            <option value="Seychelles">Seychelles </option>
            <option value="Sierra Leone">Sierra Leone </option>
            <option value="Singapour">Singapour </option>
            <option value="Slovaquie">Slovaquie </option>
            <option value="Slovenie">Slovenie</option>
            <option value="Somalie">Somalie </option>
            <option value="Soudan">Soudan </option>
            <option value="Sri_Lanka">Sri_Lanka </option>
            <option value="Suede">Suede </option>
            <option value="Suisse">Suisse </option>
            <option value="Surinam">Surinam </option>
            <option value="Swaziland">Swaziland </option>
            <option value="Syrie">Syrie </option>
            <option value="Tadjikistan">Tadjikistan </option>
            <option value="Taiwan">Taiwan </option>
            <option value="Tonga">Tonga </option>
            <option value="Tanzanie">Tanzanie </option>
            <option value="Tchad">Tchad </option>
            <option value="Thailande">Thailande </option>
            <option value="Tibet">Tibet </option>
            <option value="Timor_Oriental">Timor_Oriental </option>
            <option value="Togo">Togo </option>
            <option value="Trinite_et_Tobago">Trinite_et_Tobago </option>
            <option value="Tristan da cunha">Tristan de cuncha </option>
            <option value="Tunisie">Tunisie </option>
            <option value="Turkmenistan">Turmenistan </option>
            <option value="Turquie">Turquie </option>
            <option value="Ukraine">Ukraine </option>
            <option value="Uruguay">Uruguay </option>
            <option value="Vanuatu">Vanuatu </option>
            <option value="Vatican">Vatican </option>
            <option value="Venezuela">Venezuela </option>
            <option value="Vierges_Americaines">Vierges_Americaines </option>
            <option value="Vierges_Britanniques">Vierges_Britanniques </option>
            <option value="Vietnam">Vietnam </option>
            <option value="Wake">Wake </option>
            <option value="Wallis et Futuma">Wallis et Futuma </option>
            <option value="Yemen">Yemen </option>
            <option value="Yougoslavie">Yougoslavie </option>
            <option value="Zambie">Zambie </option>
            <option value="Zimbabwe">Zimbabwe </option>
            </select>
            </div>
            </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-arrow-up"></i></span>
                        <input class="span2" id="inputIcon" type="text" placeholder="<?php echo getText1('account.text.4'); ?>" name="age" value="<?php echo $_SESSION['age']; ?>">
                    </div>
                </div>
            </div>
            <p></br></p>
            <textarea name="msg_perso" placeholder="<?php echo getText1('account.text.7'); ?>" style="width: 185px;height: 100px;" rows="3"><?php echo $_SESSION['msg_perso']; ?></textarea>
            <p></br></p>
            <div class="btn-group">
                <button type="submit" class="btn"><i class="icon-check"></i></button>
                <a href="?a=img_profile" class="btn"><i class="icon-picture"></i></a>
                <a class="btn Tooltip" href="bdd.php?a=deleteaccount" data-toggle="tooltip" title="<?php echo getText1('account.text.18'); ?>"><i class="icon-trash"></i></a>
            </div>
            </form>
            </div>
            <div class="span9 offset1">
                <center><span class="label label-inverse"><?php echo getText1('account.title.3'); ?></span></center>
                <p></br></p>

                <?php get_last_news($_GET['page']); ?>
            </div>
            </div>
            </form>


            <?php
            }
        }
    }
    elseif($_GET['type'] == 'newpassword')
    {
        if($_SESSION['pseudo'] != NULL)
        {
            echo '<script>window.top.window.location.href = "account.php";</script>';
        }
        else
        {
            if($_GET['crypte'] != NULL)
            {
                $bdd = bdd_connect_base();

                $req = $bdd->prepare('SELECT pseudo FROM user WHERE crypte = ?') or die(mysql_error());
                $req->execute(array($_GET['crypte']));

                $donnees = $req->fetch();


                if($donnees['pseudo'] != NULL)
                {?>
                    <center>
                        <h1>Reset your password</h1>
                        </br>
                        <form method="POST" onsubmit="Reset_password(this.pseudo.value,this.password1.value,this.password2.value);return false" action="#">
                            <div class="control-group">
                                <div class="controls">
                                    <input type="hidden" name="pseudo" value="<?php echo $donnees['pseudo']; ?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <div class="input-prepend">
                                        <span class="add-on"><i class="icon-lock"></i></span>
                                        <input class="span2" id="password1" type="password" placeholder="Password" name="password1">
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <div class="input-prepend">
                                        <span class="add-on"><i class="icon-lock"></i></span>
                                        <input class="span2" id="password2" type="password" placeholder="Confirmation" name="password2">
                                    </div>
                                </div>
                            </div>
                            </br>
                            <button type="submit" class="btn btn-primary btn-large">Reset my password</button>
                        </form>
                    </center>

                <?php
                }
                else
                {
                    echo '<script>window.top.window.location.href = "account.php?type=newpassword";</script>';
                }
            }
            else
            {?>
                <center><h1>Enter your Email or your crypte code</h1></center>
                </br>
                <form method="POST" action="bdd.php?a=newpassword" enctype="application/x-www-form-urlencoded" class="form-inline">
                    <div class="row">
                        <div class="span4 offset2">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-envelope"></i></span>
                                <input id="inputIcon" type="text" placeholder="Email" name="email">
                            </div>
                        </div>
                        <div class="span4">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-qrcode"></i></span>
                                <input id="inputIcon" type="text" placeholder="Crypte code" name="cryptecode">
                            </div>
                        </div>
                    </div>
                    </br>
                    <center><button type="submit" class="btn btn-primary btn-large">Send</button></center>
                </form>

            <?php
            }
        }
    }
    else
    {?>
        <form class="form-signin" name="ident" method="post" action="#" onsubmit="Co(this.pseudo.value, this.password.value);return false;">
            <h2 class="form-signin-heading"><?php echo getText1('account.text.19'); ?></h2>
            <input class="input-block-level" type="text" name="pseudo" placeholder="<?php echo getText1('menu.signin.text.1'); ?>" value="<?php echo $_GET['login']; ?>">
            <input class="input-block-level" type="password" name="password" placeholder="<?php  echo getText1('menu.signin.text.2'); ?>">
            <button type="submit" class="btn btn-primary"><?php echo getText1('menu.nav.9'); ?></button>
        </form>

    <?php
    }

show_footer(); ?>
