<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');
	// echo "<pre>";
	// print_r($menu);
	// echo "</pre>";exit();
	if (isset($menu[0]))
	{
		echo '<div class="cd-dropdown-wrapper col-xs-4">';
			echo '<div class="fm left_trapeze"></div>';
			echo '<a class="cd-dropdown-trigger" href="#">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>';
				echo '<nav class="cd-dropdown">';
					echo '<a href="#0" class="cd-close"></a>';
						echo '<ul class="cd-dropdown-content">';
							foreach ($menu[0] as $key => $v) {

								echo '<li '.(isset($menu[$v['id']]) ? 'class="has-children"' : '').'>';

								$url = '';
								$target = '';

								extract(link_attributes($v['url'], $v['static_url'], $v['main'], $v['target']));

								$class = array();

								if(in_array($v['id'], $parents) || ($v['main'] == 1 AND $is_main)) $class[] = 'active';
								$class = (count($class) > 0) ? ' class="' . implode(' ', $class) . '"' : '';

								echo '<a href="' . $url . '"' . $class . $target . '>' . $v['name'] . '</a>';

								if (isset($menu[$v['id']]))
								{
									echo '<ul class="cd-secondary-dropdown is-hidden">';
									echo '<li class="go-back"><a href="#0">Меню</a></li>';
									if(isset($v['menu_component']) && $v['menu_component'] == true) {
											echo '<li class="main_click_item"><a href="' . $url . '"' . $class . $target . '>' . $v['name'] . '</a></li>';
									}
										foreach ($menu[$v['id']] as $_v)
										{
											echo '<li '.(isset($menu[$_v['id']]) ? 'class="has-children"' : '').'>';
												$url = '';
												$target = '';
												extract(link_attributes($_v['url'], $_v['static_url'], $_v['main'], $_v['target']));

												$class = array();

												if(in_array($_v['id'], $parents) || ($_v['main'] == 1 AND $is_main)) $class[] = 'active';
												$class = (count($class) > 0) ? ' class="' . implode(' ', $class) . '"' : '';

												echo '<a href="' . $url . '"' . $class . $target . '>' . $_v['name'] . '</a>';

													if (isset($menu[$_v['id']]))
													{
														echo '<ul class="cd-secondary-dropdown is-hidden">';
														echo '<li class="go-back"><a href="#0">Меню</a></li>';
														if(isset($v['menu_component']) && $v['menu_component'] == true) {
															echo '<li class="main_click_item"><a href="' . $url . '"' . $class . $target . '>' . $v['name'] . '</a></li>';
														}
															foreach ($menu[$_v['id']] as $__v)
															{
																echo '<li '.(isset($menu[$__v['id']]) ? 'class="has-children"' : '').'>';
																	$url = '';
																	$target = '';
																	extract(link_attributes($__v['url'], $__v['static_url'], $__v['main'], $__v['target']));

																	$class = array();

																	if(in_array($__v['id'], $parents) || ($__v['main'] == 1 AND $is_main)) $class[] = 'active';
																	$class = (count($class) > 0) ? ' class="' . implode(' ', $class) . '"' : '';

																	echo '<a href="' . $url . '"' . $class . $target . '>' . $__v['name'] . '</a>';

																	if (isset($menu[$__v['id']]))
																	{
																		echo '<ul class="cd-secondary-dropdown is-hidden">';
																		echo '<li class="go-back"><a href="#0">Меню</a></li>';
																		if(isset($v['menu_component']) && $v['menu_component'] == true) {
																			echo '<li class="main_click_item"><a href="' . $url . '"' . $class . $target . '>' . $v['name'] . '</a></li>';
																		}
																			foreach ($menu[$__v['id']] as $___v)
																			{
																				echo '<li '.(isset($menu[$___v['id']]) ? 'class="has-children"' : '').'>';
																					$url = '';
																					$target = '';
																					extract(link_attributes($___v['url'], $___v['static_url'], $___v['main'], $___v['target']));

																					$class = array();

																					if(in_array($___v['id'], $parents) || ($___v['main'] == 1 AND $is_main)) $class[] = 'active';
																					$class = (count($class) > 0) ? ' class="' . implode(' ', $class) . '"' : '';

																					echo '<a href="' . $url . '"' . $class . $target . '>' . $___v['name'] . '</a>';

																				echo '</li>';
																			}

																		echo '</ul>';
																	}

																echo '</li>';
															}

														echo '</ul>';
													}

											echo '</li>';
										}

									echo '</ul>';
								}

								echo '</li>';
							}

						echo '</ul>';

				echo '</nav>';
			echo '<div class="fmr right_trapeze"></div>	';
		echo '</div>';
	}