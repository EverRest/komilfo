<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');
?>
 <section id="reviews" class="fm reviews_wrap pd_tp_46 pd_bt_60">
    <div class="centre">
        <div class="fm blue_title mr_bt_48">Відгуки</div>
        <!-------------- слайдер відгуків -------------->
        <div class="fm sleder_reviews">
           <a href="" class="fm arrow btn_slider btn_sl_left" id="left"><b class="line_1"></b><b class="line_2"></b></a>
            <!-------------- контент слайдера -------------->
            <div class="fm revs_slider_cont">
                <!-------------- фото клієнта -------------->
                <div class="fm revs_slide_place">
                    <div class="fm width_slide_place" style="width:5000px">
                        <?php
                            if (count($slides) > 0)
                            {
                                $i=0;
                                foreach ($slides as $key => $row)
                                {
                                    if($i==1){$active = 'active';}else{$active = '';}
                                    echo '<div class="fm revs_box_slid '.$active.'">
                                    <div class="revs_photo">';
                                    if ($row['file_name'] != '')
                                    {
                                        $dir = 'upload/slider/' . $menu_id . '/' . $row['slide_id'] . '/' . $row['file_name'];
                                        echo '<img src="'.$dir.'" alt="">';
                                    }
                                    echo '</div>
                                    </div>';
                                    $i++; 
                                }
                            }
                        ?>
                    </div>
                </div>
                <!-------------- відгук клієнта -------------->
                <div class="fm revs_info">
                <?php
                    if (count($slides) > 0)
                    {
                        $i=0;
                        foreach ($slides as $key => $row)
                        {
                            if($i==1){$active = 'active';}else{$active = '';}
                            echo '<div class="fm inner_revs visible">
                                <div class="fm small_title">'.$row["title"].'</div>
                                <div class="fm text_revs">'.$row["description"].'</div>
                            </div>';
                            $i++; 
                        }
                    }
                ?>
                </div>
            </div>
            <a href="" class="fmr arrow btn_slider" id="right"><b class="line_1"></b><b class="line_2"></b></a>
        </div>
    </div>
</section>