<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');
?>
 <section class="fm gallery_wrap pd_tp_46 pd_bt_60" id="gallery">
    <div class="centre">
        <div class="fm blue_title mr_bt_48">ФОТОГАЛЕРЕЯ</div>
        <div class="fm gallery_place">
            <!-------------- фото -------------->
            <?php
                if (count($slides) > 0)
                {
                    foreach ($slides as $key => $row)
                    {
                        echo '<figure class="fm photo_item">
                        <a href="upload/gallery/' . $menu_id . '/' . $row['slide_id'] . '/s_' . $row['file_name'].'" rel="photo">';
                        if ($row['file_name'] != '')
                        {
                            $dir = 'upload/gallery/' . $menu_id . '/' . $row['slide_id'] . '/t_' . $row['file_name'];
                            echo '<img src="'.$dir.'" alt="">';
                        }
                        echo '</a>
                        </figure>';
                    }
                }
            ?>
        </div>
    </div>
</section>