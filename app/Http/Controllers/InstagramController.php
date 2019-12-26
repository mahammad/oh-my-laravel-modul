<?php

namespace App\Http\Controllers;
use Phpfastcache\Helper\Psr16Adapter;
class InstagramController extends Controller
{
    public function index()
    {
        $instagram = \InstagramScraper\Instagram::withCredentials('', '', new Psr16Adapter('Files'));
        $instagram->login();
        // Get media comments by shortcode
        $comments = $instagram->getMediaCommentsByCode('B6Szy6NJcCb', 2000);
        // or by id
        //$comments = $instagram->getMediaCommentsById('1130748710921700586', 10000);
        // Let's take first comment in array and explore available fields
        $comment = $comments[0];
        echo "Comment info: \n";
        echo "Id: {$comment->getId()}\n";
        echo "Created at: {$comment->getCreatedAt()}\n";
        echo "Comment text: {$comment->getText()}\n";
        $account = $comment->getOwner();
        echo "Comment owner: \n";
        echo "Id: {$account->getId()}";
        echo "Username: {$account->getUsername()}";
        echo "Profile picture url: {$account->getProfilePicUrl()}\n";
        // You can start loading comments from specific comment by providing comment id
        //$comments = $instagram->getMediaCommentsByCode('BG3Iz-No1IZ', 2000, $comment->getId());
        dd($comments);
        return;
    }
}
