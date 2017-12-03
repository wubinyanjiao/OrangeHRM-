<div id="profile-pic">
    
<h1><?php echo $fullName; ?></h1>
  <div class="imageHolder">

<?php if ($photographPermissions->canUpdate() || $photographPermissions->canDelete()) : ?>
  <a href="<?php echo url_for('pim/viewPhotograph?empNumber=' . $empNumber); ?>" title="<?php echo __('修改图片'); ?>" class="tiptip">
<?php else: ?>
  <a href="#">
<?php endif; ?>
    <!-- <img alt="Employee Photo" border="0" id="empPic" 
     width="<?php echo $width; ?>"  src="<?php echo $imgurl?$imgurl:'/symfony/web/public/head_pic/default-photo.png'; ?>" height="<?php echo $height; ?>"/> -->
     <img alt="Employee Photo" border="0" id="empPic" 
     width="<?php echo $width; ?>"  src="<?php echo $imgurl?public_path($imgurl):public_path('/public/head_pic/default-photo.png'); ?>" height="<?php echo $height; ?>"/>

  </a>

  </div>    
</div> <!-- profile-pic -->