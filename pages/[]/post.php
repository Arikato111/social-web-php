<?php
$getParams = import('wisit-router/getParams');
$db = new Database;
$Post = import('./components/Post');
$ProfileCard = import('./components/ProfileCard');

$username = $getParams(0);
$usr_profile = $db->getUser_ByUsername($username);

if (!$usr_profile) return require('./pages/_error.php');
?>

<title>โพสต์ | <?php echo $username; ?> | โปรไฟล์</title>
<div class="row">
    <div class="col-span-3">
        <?php import('./components/profile/Nav'); ?>
    </div>
    <div class="col-span-6 flex px-3 flex-col items-center">
        <!-- Content -->
        <?php $ProfileCard($usr_profile); ?>
        <!-- Content -->
        <div class="w-full">

            <?php
            $allPost = $db->getAllPost_ByUsrID($usr_profile['usr_id']);
            if (sizeof($allPost) == 0) : ?>
                <div class="heading w-full block">
                    ยังไม่มีโพสต์
                </div>
            <?php
            endif;
            foreach ($allPost as $post) {
                $Post($post);
            }
            ?>
        </div>
    </div>
    <div class="col-span-3">
        <?php import('./components/NavContact'); ?>
    </div>
</div>