<?php

$html = $imgHtml = $contentHtml = null;
$html .= "<div class='{$grid} {$class} padding0 layout4item' data-id='{$pID}'>";
    $html .= '<div class="rt-holder">';
			if($imgSrc) {
            $imgHtml .= "<div class='{$image_area} padding0 layoutInner  layoutInner-img'>";
                $imgHtml .= '<div class="rt-img-holder">';
                    $imgHtml .= "<a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
                $imgHtml .= '</div>';
            $imgHtml .= '</div>';
			}else{
				$content_area = "rt-col-xs-12";
			}
            $contentHtml .= "<div class='{$content_area} padding0  layoutInner  layoutInner-content'>";
                    $contentHtml .= '<div class="rt-detail">';
                        if(in_array('title', $items)){
                            $contentHtml .= "<h3 class='entry-title'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}>{$title}</a></h3>";
                        }
                        $metaHtml = null;
                        if(in_array('post_date', $items) && $date){
                            $metaHtml .= "<span class='date-meta'><i class='fa fa-calendar'></i> {$date}</span>";
                        }
                        if(in_array('author', $items)){
                            $metaHtml .= "<span class='author'><i class='fa fa-user'></i>{$author}</span>";
                        }
                        if(in_array('categories', $items) && $categories){
                            $metaHtml .= "<span class='categories-links'><i class='fa fa-folder-open-o'></i>{$categories}</span>";
                        }
                        if(in_array('tags', $items) && $tags){
                            $metaHtml .= "<span class='post-tags-links'><i class='fa fa-tags'></i>{$tags}</span>";
                        }
                        if(in_array('comment_count', $items) && $comment){
                            $metaHtml .= "<span class='comment-link'><i class='fa fa-comments-o'></i>{$comment}</span>";
                        }
                        if(!empty($metaHtml)){
                            $contentHtml .="<div class='post-meta-user'>{$metaHtml}</div>";
                        }
                        if(in_array('excerpt', $items)){
                            $contentHtml .= "<div class='post-content'>{$excerpt}</div>";
                        }
                        if(in_array('social_share', $items)){
                            $contentHtml .= rtTPG()->rtShare($pID);
                        }
                        if(in_array('read_more', $items)){
                            $contentHtml .= "<span class='read-more'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'{$link_target}>{$read_more_text}</a></span>";
                        }
                    $contentHtml .= '</div>';
            $contentHtml .= '</div>';

            if($toggle){
                $html .= $contentHtml . $imgHtml;
            }else{
                $html .= $imgHtml . $contentHtml;
            }

        $html .= '</div>';
$html .='</div>';

echo $html;