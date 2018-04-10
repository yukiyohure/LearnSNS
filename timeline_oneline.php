          <div class="thumbnail">
            <div class="row">
              <div class="col-xs-1">
                <img src="user_profile_img/<?php echo $timeline_each["profile_image"]; ?>" width="40">
              </div>
              <div class="col-xs-11">
                名前 <?php echo $timeline_each["name"]; ?><br>
                <a href="#" style="color: #7F7F7F;"><?php echo $timeline_each["updated"]; ?></a>
              </div>
            </div>
            <div class="row feed_content">
              <div class="col-xs-12" >
                <span style="font-size: 24px;"><?php echo $timeline_each["feed"]; ?></span>
              </div>
            </div>
            <div class="row feed_sub">
              <div class="col-xs-12">
                <?php if($timeline_each["like_flag"] == 0){ ?>
                    <a href="like.php?feed_id=<?php echo $timeline_each["id"]; ?>">
                      <button type="button" class="btn btn-default btn-xs"><i class="fa fa-thumbs-up" aria-hidden="true"></i>いいね！</button>
                    </a>
                <?php }else{ ?>
                    <a href="unlike.php?feed_id=<?php echo $timeline_each["id"]; ?>">
                      <button type="button" class="btn btn-info btn-xs"><i class="fa fa-thumbs-up" aria-hidden="true"></i>いいね！を取り消す</button>
                    </a>
                <?php } ?>
                <?php if ($timeline_each["like_count"] > 0) { ?>
                <span class="like_count">いいね数 : <?php echo $timeline_each["like_count"]; ?></span>
                <?php } ?>
                <span class="comment_count">コメント数 : 9</span>
                <?php if ($_SESSION["id"] == $timeline_each["user_id"]){ ?>
                  <a href="#" class="btn btn-success btn-xs">編集</a>
                  <a href="#" class="btn btn-danger btn-xs">削除</a>
                <?php } ?>
              </div>
            </div>
          </div>