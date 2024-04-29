<?php

function objectiv_do_speaking_topics( $topic_tabs = null ) {
	if ( is_array( $topic_tabs ) && ! empty( $topic_tabs ) ) {
		echo "<div class='speaking-topics-tabs__outer'>";
			objectiv_do_topic_filters( $topic_tabs );
		?>
				<div class="speaking-topics-tabs">
					<div id="tabs">
						<ul class="resp-tabs-list">
							<?php foreach ( $topic_tabs as $tab ) : ?>
								<li class="speaking-tab__nav-item"><?php echo $tab['topic_group_name']; ?></li>
							<?php endforeach; ?>
						</ul>

						<div class="resp-tabs-container">
								<?php foreach ( $topic_tabs as $tab ) : ?>
								<div>
									<h2><?php echo $tab['topic_group_name']; ?></h2>
									<?php if ( is_array( $tab['topics'] ) && ! empty( $tab['topics'] ) ) : ?>
										<?php
										foreach ( $tab['topics'] as $topic ) :
											$speaker_classes = objectiv_get_speaker_filter_options_classes( $topic );
											$ce_available    = $topic['ce_available'];
											$ce_class        = null;

											if ( $ce_available ) {
												$ce_class = 'is-ce-eligible';
											}
											?>
											<div class="tab-presentation <?php echo $speaker_classes; ?> <?php echo $ce_class; ?> is-visible">
												<h3><?php echo $topic['title']; ?></h3>
												<div class="tab-presentation-labels">
													<div class="tab-presentation-tags">
														<?php if ( is_array( $topic['topic_tags_select'] ) && ! empty( $topic['topic_tags_select'] ) ) : ?>
															<?php
															foreach ( $topic['topic_tags_select'] as $tag ) :
																$tag_class_spec = ! empty( $tag_class_spec ) ? $tag_class_spec : '';
																?>
																<span class="tab-label <?php echo $tag_class_spec; ?> tag-<?php echo obj_id_from_string( $tag['value'], false ); ?>"><?php echo $tag['label']; ?></span>
															<?php endforeach; ?>
														<?php endif; ?>
													</div>
													<div class="tab-presentation-ce-eligible">
														<?php if ( $ce_available ) : ?>
															<span class="tab-label CEEligible">CE Eligible</span>
														<?php endif; ?>
													</div>
													<div class="tab-presentation-speakers">
														<?php if ( is_array( $topic['speakers'] ) && ! empty( $topic['speakers'] ) ) : ?>
															<?php foreach ( $topic['speakers'] as $speaker ) : ?>
																<span class="tab-label speaker-<?php echo $speaker['value']; ?>"><?php echo $speaker['label']; ?></span>
															<?php endforeach; ?>
														<?php endif; ?>
													</div>
												</div>
												<div class="tab-presentation-blurb"><?php echo $topic['topic_blurb']; ?></div>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
								<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php
			echo '</div>';
	}
}

function objectiv_do_topic_filters( $topic_tabs = null ) {
	$speaker_options   = objectiv_get_speaker_filter_options( $topic_tabs );
	$topic_tag_options = objectiv_get_topic_tags_filter_options( $topic_tabs );
	?>
	<div class="speaking-topics__filter">
		<div class="filter-by-text">Filter by Speaker: </div>
		<div class="select-wrap">
			<select name="speaking-topics__speakers-filter" id="speaking-topics__speakers-filter" class="speaking-topics__speakers-filter">
				<option value="all">All Speakers</option>
				<?php foreach ( $speaker_options as $abbr => $speaker ) : ?>
					<option value="<?php echo $abbr; ?>"><?php echo $speaker; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="filter-by-middle-text">and/or CE Eligibility</div>
		<div class="select-wrap">
			<select name="speaking-topics__tag-filter" id="speaking-topics__tag-filter" class="speaking-topics__tag-filter">
				<option value="all">All Eligibility</option>
					<option value="is-ce-eligible">Only CE Eligible</option>
			</select>
		</div>
		<div class="filter-reset">Reset</div>
	</div>
	<div class="speaking-topics__no-results tac">Sorry, no topics match your filter.</div>
	<?php
}

function objectiv_get_speaker_filter_options( $topic_tabs = null ) {

	$speaker_options = array();
	$speakers        = get_field( 'remain_persons', 'option' ); // Pull speakers from theme options

	if ( ! empty( $speakers ) && is_array( $speakers ) ) {
		foreach ( $speakers as $speaker ) {
			$full_name  = mk_key_value( $speaker, 'name' );
			$first_name = mk_key_value( $speaker, 'first_name' );
			$first_name = trim( $first_name );
			$first_name = strtolower( $first_name );

			$speaker_options[ 'speaker-' . $first_name ] = $full_name;
		}
	}

	return $speaker_options;
}

function objectiv_get_topic_tags_filter_options( $topic_tabs = null ) {

	$topic_tag_options = array();

	foreach ( $topic_tabs as $tab ) {
		if ( is_array( $tab['topics'] ) ) {
			foreach ( $tab['topics'] as $topic ) {
				if ( is_array( $topic['topic_tags_select'] ) && ! empty( $topic['topic_tags_select'] ) ) {
					foreach ( $topic['topic_tags_select'] as $tag ) {
						if ( is_array( $tag ) && ! empty( $tag ) ) {
							if ( ! array_key_exists( $tag['value'], $topic_tag_options ) ) {
								$topic_tag_options[ 'tag-' . $tag['value'] ] = $tag['label'];
							}
						}
					}
				}
			}
		}
	}

	return $topic_tag_options;
}

function objectiv_get_speaker_filter_options_classes( $topic = null ) {
	$classes = '';
	if ( is_array( $topic['speakers'] ) && ! empty( $topic['speakers'] ) ) {
		foreach ( $topic['speakers'] as $speaker ) {
			if ( is_array( $speaker ) && ! empty( $speaker ) ) {
				$full_name  = mk_key_value( $speaker, 'label' );
				$full_name  = trim( $full_name );
				$name_items = explode( ' ', $full_name );
				$first_name = strtolower( $name_items[0] );
				$classes   .= ' speaker-' . $first_name;
			}
		}
	}
	return $classes;
}

function objectiv_get_tag_filter_options_classes( $topic = null ) {
	$classes = '';
	if ( is_array( $topic['topic_tags_select'] ) && ! empty( $topic['topic_tags_select'] ) ) {
		foreach ( $topic['topic_tags_select'] as $tag ) {
			if ( is_array( $tag ) && ! empty( $tag ) ) {
				$classes .= ' tag-' . $tag['value'];
			}
		}
	}
	return $classes;
}
