<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;

/**
 * MW Showband — "Watch MW Live" video showcase grid.
 * Author: Aqib Dev
 */
class MWSB_Video_Gallery_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-video-gallery';
	}

	public function get_title() {
		return __( 'MW Video Gallery', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-video-playlist';
	}

	public function get_categories() {
		return array( 'mw-showband' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Header', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '4K BROADCAST VAULT' ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'WATCH MW LIVE' ) );
		$this->add_control( 'badge', array( 'label' => __( 'Badge', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '4K ULTRA HDR • MULTI-TRACK STEREO' ) );
		$this->add_control(
			'layout_mode',
			array(
				'label'       => __( 'Layout', 'mw-showband' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'grid',
				'options'     => array(
					'grid'      => __( 'Thumbnail Grid', 'mw-showband' ),
					'instagram' => __( 'Instagram Carousel', 'mw-showband' ),
				),
				'separator'   => 'before',
				'description' => __( 'Instagram Carousel embeds each video\'s own Instagram player in a swipeable row — paste the Instagram reel/post link into "Video URL" below for each item.', 'mw-showband' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'videos_section',
			array(
				'label' => __( 'Videos', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'thumbnail', array( 'label' => __( 'Thumbnail', 'mw-showband' ), 'type' => Controls_Manager::MEDIA, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => Utils::get_placeholder_image_src() ) ) );
		$repeater->add_control( 'video_url', array( 'label' => __( 'Video URL', 'mw-showband' ), 'type' => Controls_Manager::URL, 'dynamic' => array( 'active' => true ), 'default' => array( 'url' => '' ), 'placeholder' => 'https://youtube.com/watch?v=...' ) );
		$repeater->add_control( 'duration', array( 'label' => __( 'Duration', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '03:45' ) );
		$repeater->add_control( 'video_title', array( 'label' => __( 'Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'THE VOCALS' ) );
		$repeater->add_control( 'video_desc', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => '4-part powerhouse vocal harmony live concert breakdown.' ) );
		$repeater->add_control(
			'accent',
			array(
				'label'   => __( 'Play Button Accent', 'mw-showband' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'purple',
				'options' => array( 'purple' => 'Purple', 'magenta' => 'Magenta', 'cyan' => 'Cyan' ),
			)
		);

		$this->add_control(
			'videos',
			array(
				'label'       => __( 'Video Items', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'video_title' => 'THE VOCALS', 'video_desc' => '4-part powerhouse vocal harmony live concert breakdown.', 'duration' => '03:45', 'accent' => 'purple' ),
					array( 'video_title' => 'THE BAND & HORNS', 'video_desc' => 'Blistering 4-piece horn section battle and drum climax.', 'duration' => '05:12', 'accent' => 'magenta' ),
					array( 'video_title' => 'THE CROWD IGNITION', 'video_desc' => 'Pure audience takeover, interactive medleys & euphoria.', 'duration' => '04:20', 'accent' => 'cyan' ),
				),
				'title_field' => '{{{ video_title }}}',
			)
		);

		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'head' => '.mwsb-videos__head', 'content_gap' => '.mwsb-videos__grid' ) );

		$this->start_controls_section(
			'mwsb_video_style',
			array(
				'label' => __( 'Header & Card Text', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'video_eyebrow_color',
			array(
				'label'     => __( 'Eyebrow Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-tertiary)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-videos__eyebrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'video_card_title_typography',
				'label'     => __( 'Card Title Typography', 'mw-showband' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .mwsb-video-card__body h3',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'video_card_desc_typography',
				'label'    => __( 'Card Description Typography', 'mw-showband' ),
				'selector' => '{{WRAPPER}} .mwsb-video-card__body p',
			)
		);

		$this->end_controls_section();
	}

	/** Instagram's oEmbed iframe src for any public post/reel link — no API key or access token needed. */
	private function mwsb_instagram_embed_url( $url ) {
		$url = trim( (string) $url );
		if ( '' === $url || false === strpos( $url, 'instagram.com' ) ) {
			return '';
		}
		$url = strtok( $url, '?' );
		$url = rtrim( $url, '/' ) . '/';
		return $url . 'embed/captioned/';
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="mwsb-widget mwsb-section mwsb-videos">
			<div class="mwsb-container">
				<div class="mwsb-videos__head">
					<div>
						<span class="mwsb-eyebrow mwsb-videos__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
						<h2 class="mwsb-heading-display mwsb-heading-display--xl"><?php echo esc_html( $s['title'] ); ?></h2>
					</div>
					<span class="mwsb-pill"><?php echo esc_html( $s['badge'] ); ?></span>
				</div>

				<?php if ( 'instagram' === $s['layout_mode'] ) : ?>
					<div class="mwsb-videos__carousel swiper">
						<div class="swiper-wrapper">
							<?php
							foreach ( $s['videos'] as $v ) :
								$embed = $this->mwsb_instagram_embed_url( ! empty( $v['video_url']['url'] ) ? $v['video_url']['url'] : '' );
								if ( '' === $embed ) {
									continue;
								}
								?>
								<div class="swiper-slide mwsb-videos__ig-slide">
									<iframe src="<?php echo esc_url( $embed ); ?>" title="<?php echo esc_attr( $v['video_title'] ); ?>" loading="lazy" allowtransparency="true" frameborder="0" scrolling="no"></iframe>
								</div>
							<?php endforeach; ?>
						</div>
						<button type="button" class="mwsb-videos__nav mwsb-videos__nav--prev" aria-label="<?php esc_attr_e( 'Previous video', 'mw-showband' ); ?>">&#10094;</button>
						<button type="button" class="mwsb-videos__nav mwsb-videos__nav--next" aria-label="<?php esc_attr_e( 'Next video', 'mw-showband' ); ?>">&#10095;</button>
					</div>
				<?php else : ?>
					<div class="mwsb-videos__grid">
						<?php foreach ( $s['videos'] as $v ) : ?>
							<a class="mwsb-video-card" href="<?php echo esc_url( $v['video_url']['url'] ? $v['video_url']['url'] : '#' ); ?>">
								<div class="mwsb-video-card__thumb">
									<?php if ( ! empty( $v['thumbnail']['url'] ) ) : ?>
										<img src="<?php echo esc_url( $v['thumbnail']['url'] ); ?>" alt="<?php echo esc_attr( $v['video_title'] ); ?>" />
									<?php endif; ?>
									<div class="mwsb-video-card__scrim">
										<span class="mwsb-video-card__play mwsb-video-card__play--<?php echo esc_attr( $v['accent'] ); ?>">&#9658;</span>
									</div>
									<span class="mwsb-video-card__duration"><?php echo esc_html( $v['duration'] ); ?></span>
								</div>
								<div class="mwsb-video-card__body">
									<h3><?php echo esc_html( $v['video_title'] ); ?></h3>
									<p><?php echo esc_html( $v['video_desc'] ); ?></p>
								</div>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
