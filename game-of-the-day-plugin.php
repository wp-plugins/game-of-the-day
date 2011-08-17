<?php
/*
Plugin Name: Game of the Day
Plugin URI:
Description: Each day Games.com Editors features 'Game of the day'.Invite Friends/Share/Play Online for free from Games.com.Click on the Game image or link, lightbox popup will appear and you can play the game. You can login with AOL,Yahoo,Gmail,Facebook creditenials to save your scores,earn stamps and stars and be a part of Games.com Community.
Version: 1.0
Author: Games Team
Author URI: http://www.games.com/
License: GPL3
*/

function gameoftheday()
{
  $options = get_option("widget_gameoftheday");
  if (!is_array($options)){
    $options = array(
      'title' => 'Game of the Day'
    );
  }
  
  // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, "http://www.games.com/component/game-of-the-day/"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $result = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch); 
  
$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
$regexp1 = "<div\s[^>]*id=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/div>";
$regexp2 = "<span\s[^>]*id=(\"??)([^\" >]*?)\\1[^>]*class=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/span>";
  if(preg_match_all("/$regexp/siU", $result, $matches, PREG_SET_ORDER)) {
     $gameimg  = $matches[0][3];
     $gamename = $matches[0][4];
     $pos = strrpos($matches[0][2], "games.com");
     if($pos === false){
     $glink = "http://www.games.com".$matches[0][2];
     }else{
     $glink = $matches[0][2];
     }
     
     $gamelink2 = $matches[1][3];
         
  }
  if(preg_match_all("/$regexp1/siU", $result, $matches, PREG_SET_ORDER)) {
    for($i=0; $i<=count($matches);$i++) {
     
      $str = $matches[$i][2];
       if($str == "'gid'"){
         $gameid = $matches[$i][3];
}         
    }
  }
  
  if(preg_match_all("/$regexp2/siU", $result, $matches, PREG_SET_ORDER)) {
      $gamedesc = $matches[1][0];
  }
  
  $playurl = "http://destiny-wrapper.games.com/gameWrapper/jsp/playNow.jsp?game=".$gameid."&wt=wp_gotd";
  $game = "<a id='gameplay' href='".$playurl."'>".$gameimg."<h1>".$gamelink2."</h1></a>";
  $fblike ='<fb:like href="'.$glink.'" send="true" layout="box_count" width="50" show_faces="true" action="like" font="tahoma"></fb:like>';
  
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://o.aolcdn.com/os/games/styles/fancybox.css"/>
	
<script type="text/javascript" src="http://o.aolcdn.com/os/games/scripts/fancybox.js"></script>
<script>
$(document).ready(function() {
	
	$("a#gameplay").fancybox({
		'type' : 'iframe',
		'transitionIn' : 'elastic',
		'transitionOut' : 'elastic',
		'speedIn'	 : 600, 
		'speedOut'		:	200, 
		'overlayShow'	:	false,
		'width':980,
		'height':600
	});
      
	
});

</script>

<style>
#gotd {width:220px; margin-top:5px;}
#gotd a{outline: 0 none;text-decoration: none;}
#gotd img {  border: 1px solid #DADADA;box-shadow: 0 0 5px #CCCCCC;margin: 0 14px 4px 0;padding: 10px;width: 196px; }
#gotd h1 {color: #1D5287;font-size: 18px; }
#gotd .link { color:#DD490B;}
</style>
<?php  
  echo '<div id="gotd">';
  echo $game;
  echo "<p>".$gamedesc."..<a class='link' href='http://www.games.com/browse-games/all/' title='play free online games'>click to play more games</a></p>";
echo '<iframe src="http://www.facebook.com/plugins/like.php?app_id=129148450444136&amp;href='.$glink.'&amp;send=false&amp;layout=box_count&amp;width=60&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:60px; height:90px;margin-top:5px;" allowTransparency="true"></iframe>';
echo '<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>';
echo '<div style="float:left;padding:3px;top:-5px;">';
echo '<a href="http://twitter.com/share" class="twitter-share-button" data-url="'.$glink.'" data-via="gamesdotcom-game of the day" data-text="OMG! I love this game.." data-related="" data-count="vertical">Tweet</a>';
echo '<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>';

echo '<script type="IN/Share" data-url="'.$glink.'" data-counter="top"></script>';
echo '</div></div>';
  
 
}

function widget_gameoftheday($args)
{
  extract($args);
  
  $options = get_option("widget_gameoftheday");
  if (!is_array($options)){
    $options = array(
      'title' => 'Game Of the Day'
    );
  }
  
  echo "<h3 class='widget-title'>".$options['title']."</h3>";
  
  gameoftheday();
  
}

function gameoftheday_control()
{
  $options = get_option("widget_gameofthday");
  if (!is_array($options)){
    $options = array(
      'title' => 'Game Of the Day'
    );
  }
  
  if($_POST['gameoftheday-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['gameoftheday-WidgetTitle']);
    update_option("widget_gameoftheday", $options);
  }
?> 
  <p>
    <label for="gameoftheday-WidgetTitle">Widget Title: </label>
    <input type="text" id="gameoftheday-WidgetTitle" name="gameoftheday-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <input type="hidden" id="gameoftheday-Submit"  name="gameoftheday-Submit" value="1" />
  </p>
  
<?php
}

function gameoftheday_init()
{
  register_sidebar_widget(__('Game Of the Day'), 'widget_gameoftheday');    
  register_widget_control('Game Of the Day', 'gameoftheday_control', 300, 200);
}
add_action("plugins_loaded", "gameoftheday_init");
?>
