<?php

/**
 * Like functions
 */

// Add AJAX actions
add_action('wp_ajax_ufo_liked_ajax', 'ufo_update_liked_count');
add_action('wp_ajax_nopriv_ufo_liked_ajax', 'ufo_update_liked_count');

function ufo_update_liked_count() {
    // get post ID
    $id = $_POST['id'];
    $metakey='_ufo_liked';
    // get liked count
    $count=get_post_meta($id,$metakey,true);
    // check for count
    if(!$count)
        $count=0;
    // increase by 1
    $count++;
    if(isset($_COOKIE['_ufo_liked'])) {
        $cookie=  $_COOKIE['_ufo_liked'];
        $liked=explode('|',$cookie);
        if(!in_array($id,$liked)) {
            $status=update_post_meta($id,$metakey,$count);
            $cookie=$cookie.'|'.$id;
        } else {
            $status=FALSE;
        }
    } else {
        // update liked count
        $status=update_post_meta($id,$metakey,$count);
        $cookie=$id;
    }
    if($status) {
        setcookie('_ufo_liked', $cookie ,time()+3600*24*365,'/');
        $status=true;
    }
    // generate the response
    $response = json_encode(
        array(
            'success' => $status,
            'postID' => $id,
            'count' => $count
        )
    );
    // JSON header
    header('Content-type: application/json');
    echo $response;
    die();
}

function ufo_post_liked_count() {
    global $post;
    $liked=get_post_meta($post->ID,'_ufo_liked',TRUE);
    echo $liked?$liked:'0';
}

function ufo_liked_class() {
    global $post;
    $liked=array();
    if(isset($_COOKIE['_ufo_liked'])) {
        $cookie= $_COOKIE['_ufo_liked'];
        $liked=explode('|',$cookie);
    }
    if(in_array($post->ID,$liked))
        echo 'class="active"';
}
?>