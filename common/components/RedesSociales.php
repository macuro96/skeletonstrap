<?php

namespace common\components;

class RedesSociales
{
    public static function twitter($mensaje)
    {
        ?>
        <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-size="large" data-text="<?= $mensaje ?>" data-related="SkeletonsTrapCR" data-lang="es" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        <?php
    }

    public static function facebook()
    {
        ?>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = 'https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.12';
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

        <div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button_count" data-size="small" data-mobile-iframe="false"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>
        <?php
    }

    public static function timelineTwitter()
    {
        ?>
        <a class="twitter-timeline" data-lang="es" data-width="1200" data-height="800" data-theme="dark" href="https://twitter.com/SkeletonsTrapCR?ref_src=twsrc%5Etfw">Tweets by SkeletonsTrapCR</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script> 
        <?php
    }
}
