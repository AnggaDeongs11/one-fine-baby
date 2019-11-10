<?php

if (!$url) {
    return;
}
wp_enqueue_script('giftregistry-share');

$not_encode_url = $url;
// convert & thanh ?
$not_encode_url = str_replace('&', '?', $not_encode_url);
$url = urlencode($url);
$replace = array('{giftregistry_url}' => $not_encode_url);
$content = strtr(get_option('giftregistry_share_text'), $replace);
/*remove {giftregistry_url} in share twitter, it exist*/
$twitter_summary = str_replace('{giftregistry_url}', '', get_option('giftregistry_share_text'));
$summary = urlencode($content);//urlencode( get_option( 'giftregistry_share_text' ));//urlencode( str_replace( '{giftregistry_url}', $not_encode_url, get_option( 'giftregistry_share_text' ) ) );
$imageurl = urlencode(get_option('giftregistry_share_image_url'));

$facebook = get_option('giftregistry_share_facebook', 'yes');
//if ($facebook =='yes')
$facebook_share_link = "https://www.facebook.com/sharer.php?s=100" . "&amp;p[url]=" . $url . "&amp;p[summary]=" . $summary . "&amp;p[images][0]=" . $imageurl;
$twitter_share_link = "https://twitter.com/share?url=" . $url . "&amp;text=" . $twitter_summary;
//http://twitter.com/share?text=text goes here&url=http://url goes here&hashtags=hashtag1,hashtag2,hashtag3
$google_share_link = "https://plus.google.com/share?url=" . $url;
$facebook_share_link = str_replace('%26', '%3F', $facebook_share_link);
$twitter_share_link = str_replace('%26', '%3F', $twitter_share_link);
$google_share_link = str_replace('%26', '%3F', $google_share_link);

$wishlist = Magenest_Giftregistry_Model::get_wishlist_id(get_current_user_id());
if($wishlist) {
    ?>
    <div class="tab-pane" id="tab3">
        <div>
            <h3><strong> <?php echo __('Share your registry URL', GIFTREGISTRY_TEXT_DOMAIN) ?> </strong></h3>
            <p style="width: 50%" id="link-text"><?php echo $not_encode_url ?> <img id="icon-copy" class="vote-up-off"
                                                                                    title="Copy to clipboard"
                                                                                    src="<?= GIFTREGISTRY_URL . '/assets/img/copy.png' ?>"
                                                                                    style="float: right"></p>
        </div>
        <hr/>
        <div class="giftregistry-share">
            <h3>
                <strong><?php echo __('Share your registry with your social network', GIFTREGISTRY_TEXT_DOMAIN) ?></strong>
            </h3>
            <ul>
                <?php if (get_option('giftregistry_share_facebook') == 'yes') : ?>
                    <li style="list-style-type: none; display: inline-block;"><a target="_blank" class="facebook"
                                                                                 href="<?php echo $facebook_share_link ?>"><img
                                    src="<?= GIFTREGISTRY_URL . '/assets/img/fb.png'; ?>" class="icon_share vote-up-off"
                                    title="Share on Facebook"></a>
                    </li>
                <?php endif ?>
                <?php if (get_option('giftregistry_share_twitter') == 'yes') : ?>

                    <li style="list-style-type: none; display: inline-block;"><a target="_blank" class="twitter"
                                                                                 href="<?php echo $twitter_share_link ?>"><img
                                    src="<?= GIFTREGISTRY_URL . '/assets/img/tw.png'; ?>" class="icon_share vote-up-off"
                                    title="Share on Twitter"></a>
                    </li>
                <?php endif ?>
                <?php if (get_option('giftregistry_share_email') == 'yes') : ?>

                    <li style="list-style-type: none; display: inline-block;"><a id="share-email" class="email" href="#"
                                                                                 onclick="showsharegiftregistryform()"><img
                                    src="<?= GIFTREGISTRY_URL . '/assets/img/email.png'; ?>"
                                    class="icon_share vote-up-off"
                                    title="Share via Email"></a>
                        <div>
                            <form method="POST" id="share_via_email_form" class="form email" style="display: none">
                                <input type="hidden" name="giftregistry-share-email" value="1"/>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="recipient"><?php echo __('Recipient', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                                        </td>
                                        <td>
                                            <input name="recipient" id="recipient" type="text" size="40"><br/>
                                            <span class="note"><?php echo __("separate email by commas", GIFTREGISTRY_TEXT_DOMAIN) ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="email_subject"><?php echo __('Subject', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                                        </td>
                                        <td>
                                            <input name="email_subject" id="email_subject" type="text"
                                                   value="" size="40">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="message_share"><?php echo __('Message', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
                                        </td>
                                        <td>
                                    <textarea id="message_share" name="message_share" rows=""
                                              cols=""><?php $re = array('{giftregistry_url}' => $not_encode_url);
                                        $content = strtr(get_option('giftregistry_share_text'), $re);
                                        echo $content; ?> </textarea>
                                        </td>
                                    </tr>
                                </table>
                                <input type="submit" value="Send"/>
                            </form>
                        </div>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
    </div>
    </div>
    <?php
}else{
    ?>
    <div class="tab-pane" id="tab3">
        <p style="margin-top: 100px"> <?=__('You have to create giftregistry to share',GIFTREGISTRY_TEXT_DOMAIN)?></p>
    </div>
    </div>
    </div>
    <?php
}
?>