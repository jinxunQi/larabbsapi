<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content,'user_topic_body');
    }
    
    public function created(Reply $reply)
    {
        //$reply->topic->increment('reply_count',1); //方法1

        //方法2
        /*$reply->topic->reply_count = $reply->topic->replies->count();
        $reply->topic->save();*/

        //方法2 的 简化代码
        $reply->topic->updateReplyCount();

        // 通知话题作者有新的评论
        $reply->topic->user->notify(new TopicReplied($reply));
    }

    public function deleted(Reply $reply)
    {
        /*$reply->topic->reply_count = $reply->topic->replies->count();
        $reply->topic->save();*/
        //简化代码
        $reply->topic->updateReplyCount();
    }

    public function updating(Reply $reply)
    {
        //
    }
}