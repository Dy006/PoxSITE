<?php include "menu.php"; ?>

<script type="text/javascript">tab_change_focus("li_5","1")</script>

<?php
$mac1 = str_replace ( "-", "",$_GET['MAC'] ) ;
$mac = str_replace ( ":", "",$mac1 ) ;
$IP_ADDRESS=$_GET['IP'];
$MAC_ADDRESS=$mac;

class Wol{
    private $nic;
    public function wake($mac,$ip){
        $this->nic = fsockopen("udp://$ip", 9);
        if( !$this->nic ){
            fclose($this->nic);
            return false;
        }
        else{
            fwrite($this->nic, $this->pacquet($mac));
            fclose($this->nic);
            return true;
        }
    }
    private function pacquet($Mac){
        $packet = "";
        //for($i = 0; $i < 6; $i++){$packet .= chr(0xFF);}
        $packet = "\xFF\xFF\xFF\xFF\xFF\xFF";
        for ($j = 0; $j < 16; $j++){
            for($i = 0; $i < 12; $i=$i + 2){$packet .= chr(hexdec(substr($Mac, $i, 2)));}
        }
        return $packet;
    }
}

$wol = new Wol();

if ($_GET['WOL'] != "OK")
{
    if($_GET['WOL'] == 'local')
    {
        echo '<center><h2>Wake on lan - Local</h2><applet  codebase="."  code="WOL.WOL_class.class"  name="WakeOnLan Applet By Pox"  ARCHIVE="applet/wol.jar"  width="800"  height="200"></applet></br><a href="wol.php" class="btn btn-primary">' . getText1('wol.button.2') . '</a></center>';
    }
    else
    {?>
    <center><h1>Wake on lan</h1></br>

        <form action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="get" class="form-inline">
            <input type="hidden" name="WOL" value="OK" >
            <input type="text" name="IP" placeholder="<?php echo getText1('wol.text.1'); ?>">
            <input type="text" name="MAC" placeholder="<?php echo getText1('wol.text.2'); ?>">
            <input type="text" name="PORT" placeholder="<?php echo getText1('wol.text.3'); ?>" value="9">
            <input type="submit" class="btn btn-primary" value="<?php echo getText1('wol.button.1'); ?>">
        </form></center></br>

    <span class="label label-inverse"><?php echo getText1('wol.title.1'); ?></span>
    <p></br><?php echo getText1('wol.text.4'); ?></p>
    </br>

    <span class="label label-inverse"><?php echo getText1('wol.title.2'); ?></span>
    <p></br><?php echo getText1('wol.text.5'); ?> <a href="http://www.siteduzero.com/informatique/tutoriels/wake-on-lan" target="_blank"><?php echo getText1('wol.button.3'); ?></a>. <?php echo getText1('wol.text.6'); ?></p>
    </br>

    <span class="label label-inverse"><?php echo getText1('wol.title.3'); ?></span>
    <p></br><?php echo getText1('wol.text.7'); ?> <a href="?WOL=local"><?php echo getText1('wol.button.3'); ?></a>.</p>

    <?php
    }?>

    <?php show_footer();
    exit();
}
else
{
    echo '<h2>' . getText1('wol.text.8') . '</h2><p>' . getText1('wol.text.9') . '</br>' . getText1('wol.text.10') . '</p><a href="wol.php" class="btn btn-primary">' . getText1('wol.button.2') . '</a>';
}

$wol->wake("$MAC_ADDRESS","$IP_ADDRESS");

show_footer(); ?>