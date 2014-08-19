<h3><a href="users/<?=$user['username']?>"><?=rank_symbol($user['rank'])?><?=$user['username']?></a><div class="right"><a href="users/<?=$user['username']?>/newest">Newest Quotes</a> | <a href="users/<?=$user['username']?>/popular">Popular Quotes</a> | <a href="users/<?=$user['username']?>/random">Random Quotes</a></div></h3>
<img style="float: left; margin-right: 20px;" src="http://www.gravatar.com/avatar/<?= md5( strtolower( trim( $user['email'] ) ) );?>.jpg?s=128&d=http%3A%2F%2Fobsceneart.net%2Fimages%2Flogo.png" alt="<?=$user['username']?>" />
<table>
	<?php if ($user['aim']) { ?><tr><td class="label-column">AIM:</td><td><a href="aim:goim?screenname=<?=$user['aim']?>"><?=$user['aim']?></a></td></tr><?php } ?>
	<?php if ($user['yahoo']) { ?><tr><td class="label-column">Yahoo:</td><td><a href="ymsgr:sendIM?<?=$user['yahoo']?>"><?=$user['yahoo']?></a></td></tr><?php } ?>
	<?php if ($user['website']) { ?><tr><td class="label-column">Website:</td><td><a href="<?=$user['website']?>" target="_blank"><?=$user['website']?></a></td></tr><?php } ?>
	<tr><td class="label-column">Created:</td><td><?=date('M d, Y', strtotime($user['created']))?></td></tr>
	<tr><td class="label-column">Last Login:</td><td><?php if ($user['last_login'] && $user['last_login'] != '0000-00-00 00:00:00') { echo date('M d, Y', strtotime($user['last_login'])); } else { echo "<em>User Hasn't Logged In Since ObsceneArt 2.0</em>"; } ?></td></tr>
</table>
<?php if ($user['banned']) { ?>
<div class="warning"><strong>User is banned</strong>: <?=$user['ban_reason']?></div>
<?php } ?>