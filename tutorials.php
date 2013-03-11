<?php include "menu.php" ;?>

<script type="text/javascript">tab_change_focus("li_4","1")</script>

<div class="row">
	<div class="span2">
		<div class="well" style="max-width: 340px; padding: 8px 0;">
              <ul class="nav nav-list">
                <li class="nav-header">Tutorials</li>
                <li id="li_1t" class="active"><a href="tutorials.php" onclick="tab_change_focus('li_1t','4');">Home</a></li>
                <li id="li_2t" ><a href="?t=list" onclick="tab_change_focus('li_2t','4');">List</a></li>
                  <?php
                  if($_SESSION['pseudo'] != NULL)
                  {?>
                      <li class="nav-header">Other</li>
                      <li><a href="account.php?type=tutorials&a=new" onclick="tab_change_focus('li_3t','4');">Add tutorial</a></li>
                      <li><a href="account.php?type=tutorials" onclick="tab_change_focus('li_3t','4');">Edit tutorials</a></li>

                  <?php
                  }?>
                <li class="divider"></li>
                <li><a href="faq.php">Help</a></li>
              </ul>
        </div>
	</div>
	<div class="span10">

<?php
if($_GET['id'] != NULL)
{
    get_tutorial($_GET['id'],$_GET['id_chapter']);
}
else
{
    if($_GET['t'] == 'list')
    {
        echo '<script type="text/javascript">tab_change_focus("li_2t","4")</script>';

        get_list_tutorials();
    }
    else
    {
        echo '<script type="text/javascript">tab_change_focus("li_1t","4")</script>'; ?>

        <h1>New tutorial</h1>

        <div class="row-fluid">
            <div class="span4">
                <h2>Heading</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn" href="#">View details »</a></p>
            </div>
        </div>

        <h1>Last tutorials</h1>

        <h1>Best tutorials</h1>

        <div class="media">
            <a class="pull-left" href="#">
                <img class="media-object" data-src="holder.js/64x64" alt="64x64" style="width: 64px; height: 64px;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAACDUlEQVR4Xu2Yz6/BQBDHpxoEcfTjVBVx4yjEv+/EQdwa14pTE04OBO+92WSavqoXOuFp+u1JY3d29rvfmQ9r7Xa7L8rxY0EAOAAlgB6Q4x5IaIKgACgACoACoECOFQAGgUFgEBgEBnMMAfwZAgaBQWAQGAQGgcEcK6DG4Pl8ptlsRpfLxcjYarVoOBz+knSz2dB6vU78Lkn7V8S8d8YqAa7XK83ncyoUCjQej2m5XNIPVmkwGFC73TZrypjD4fCQAK+I+ZfBVQLwZlerFXU6Her1eonreJ5HQRAQn2qj0TDukHm1Ws0Ix2O2260RrlQqpYqZtopVAoi1y+UyHY9Hk0O32w3FkI06jkO+74cC8Dh1y36/p8lkQovFgqrVqhFDEzONCCoB5OSk7qMl0Gw2w/Lo9/vmVMUBnGi0zi3Loul0SpVKJXRDmphvF0BOS049+n46nW5sHRVAXMAuiTZObcxnRVA5IN4DJHnXdU3dc+OLP/V63Vhd5haLRVM+0jg1MZ/dPI9XCZDUsbmuxc6SkGxKHCDzGJ2j0cj0A/7Mwti2fUOWR2Km2bxagHgt83sUgfcEkN4RLx0phfjvgEdi/psAaRf+lHmqEviUTWjygAC4EcKNEG6EcCOk6aJZnwsKgAKgACgACmS9k2vyBwVAAVAAFAAFNF0063NBAVAAFAAFQIGsd3JN/qBA3inwDTUHcp+19ttaAAAAAElFTkSuQmCC">
            </a>
            <div class="media-body">
                <h4 class="media-heading">Media heading</h4>
                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
            </div>
        </div>

        <h1>Informations</h1>

        <?php
    }
}?>
	</div>
</div>

<?php show_footer(); ?>