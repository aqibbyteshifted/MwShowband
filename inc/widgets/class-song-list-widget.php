<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

/**
 * MW Showband — Repertoire section with genre pills + animated waveform track rows.
 * Author: Aqib Dev
 */
class MWSB_Song_List_Widget extends Widget_Base {

	use MWSB_Section_Controls;

	public function get_name() {
		return 'mwsb-song-list';
	}

	public function get_title() {
		return __( 'MW Song List', 'mw-showband' );
	}

	public function get_icon() {
		return 'eicon-play';
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

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'SONIC ARSENAL' ) );
		$this->add_control( 'title_line1', array( 'label' => __( 'Title Line 1', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '800+ SONGS.' ) );
		$this->add_control( 'title_line2', array( 'label' => __( 'Title Line 2', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'ONE NIGHT. YOUR SOUND.' ) );
		$this->add_control( 'description', array( 'label' => __( 'Description', 'mw-showband' ), 'type' => Controls_Manager::TEXTAREA, 'dynamic' => array( 'active' => true ), 'default' => 'Unbroken 60-minute medleys with no dead air. From Earth Wind & Fire to Calvin Harris, flawlessly blended in realtime.' ) );

		$genre_repeater = new Repeater();
		$genre_repeater->add_control( 'genre', array( 'label' => __( 'Genre', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'ALL GENRES' ) );
		$this->add_control(
			'genres',
			array(
				'label'   => __( 'Genre Pills', 'mw-showband' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $genre_repeater->get_controls(),
				'default' => array(
					array( 'genre' => 'ALL GENRES' ),
					array( 'genre' => 'POP & CHART' ),
					array( 'genre' => 'FUNK & SOUL' ),
					array( 'genre' => 'MOTOWN CLASSICS' ),
					array( 'genre' => 'STADIUM ROCK' ),
					array( 'genre' => 'R&B ANTHEMS' ),
				),
				'title_field' => '{{{ genre }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tracks_section',
			array(
				'label' => __( 'Tracks', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'track_title', array( 'label' => __( 'Medley Title', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => 'BRUNO MARS x EARTH WIND & FIRE MEDLEY' ) );
		$repeater->add_control( 'track_songs', array( 'label' => __( 'Song List', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => "24K Magic • September • Uptown Funk • Let's Groove" ) );
		$repeater->add_control( 'bpm', array( 'label' => __( 'BPM', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '124 BPM' ) );
		$repeater->add_control( 'duration', array( 'label' => __( 'Duration', 'mw-showband' ), 'type' => Controls_Manager::TEXT, 'dynamic' => array( 'active' => true ), 'default' => '08:42 LIVE' ) );
		$repeater->add_control(
			'track_audio',
			array(
				'label'       => __( 'Audio Clip (optional)', 'mw-showband' ),
				'type'        => Controls_Manager::MEDIA,
				'media_type'  => 'audio',
				'default'     => array( 'url' => '' ),
				'description' => __( 'Upload a short clip of the band\'s own performance/demo recording. Only use audio you own the rights to — do not upload copyrighted commercial recordings.', 'mw-showband' ),
			)
		);

		$this->add_control(
			'tracks',
			array(
				'label'       => __( 'Track Rows', 'mw-showband' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'track_title' => 'BRUNO MARS x EARTH WIND & FIRE MEDLEY', 'track_songs' => "24K Magic • September • Uptown Funk • Let's Groove", 'bpm' => '124 BPM', 'duration' => '08:42 LIVE' ),
					array( 'track_title' => 'DUA LIPA x FUTURE NOSTALGIA HYBRID', 'track_songs' => "Levitating • Don't Start Now • Physical • Club Electro Stems", 'bpm' => '128 BPM', 'duration' => '10:15 LIVE' ),
					array( 'track_title' => 'ISRAELI HORA HIGH-OCTANE SUITE', 'track_songs' => 'Mashiach • Od Yishama • Siman Tov • Contemporary Electronic Fusion', 'bpm' => '142 BPM', 'duration' => '12:30 LIVE' ),
				),
				'title_field' => '{{{ track_title }}}',
			)
		);

		$this->end_controls_section();

		$this->register_section_style_controls();
		$this->register_text_style_controls( array( 'head' => '.mwsb-songlist__head', 'desc' => '.mwsb-songlist__desc', 'content_gap' => '.mwsb-tracklist' ) );

		$this->start_controls_section(
			'mwsb_songlist_header_style',
			array(
				'label' => __( 'Header & Genres', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'songlist_desc_typography',
				'label'    => __( 'Description Typography', 'mw-showband' ),
				'selector' => '{{WRAPPER}} .mwsb-songlist__desc',
			)
		);

		$this->add_control(
			'genre_pill_heading',
			array(
				'label'     => __( 'Genre Pills', 'mw-showband' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'genre_pill_active_bg',
			array(
				'label'     => __( 'Active Background', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-primary)',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-genre-pill.is-active' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mwsb_songlist_track_style',
			array(
				'label' => __( 'Track Rows', 'mw-showband' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'track_title_typography',
				'label'    => __( 'Medley Title Typography', 'mw-showband' ),
				'selector' => '{{WRAPPER}} .mwsb-track__title',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'track_songs_typography',
				'label'    => __( 'Song List Typography', 'mw-showband' ),
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .mwsb-track__songs',
			)
		);

		$this->add_control(
			'track_bpm_color',
			array(
				'label'     => __( 'BPM Badge Color', 'mw-showband' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'var(--mwsb-stage-gold)',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .mwsb-track__bpm' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$bar_pattern = array( 3, 6, 4, 7, 5, 8, 4, 6, 3, 7, 4, 2 );
		?>
		<section class="mwsb-widget mwsb-section mwsb-songlist">
			<div class="mwsb-container">
				<div class="mwsb-songlist__head">
					<div>
						<span class="mwsb-eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
						<h2 class="mwsb-heading-display mwsb-heading-display--xl">
							<?php echo esc_html( $s['title_line1'] ); ?><br />
							<span class="mwsb-gradient-text"><?php echo esc_html( $s['title_line2'] ); ?></span>
						</h2>
					</div>
					<p class="mwsb-songlist__desc"><?php echo esc_html( $s['description'] ); ?></p>
				</div>

				<div class="mwsb-pill-row mwsb-pill-row--buttons">
					<?php foreach ( $s['genres'] as $i => $g ) : ?>
						<button type="button" class="mwsb-genre-pill <?php echo 0 === $i ? 'is-active' : ''; ?>"><?php echo esc_html( $g['genre'] ); ?></button>
					<?php endforeach; ?>
				</div>

				<div class="mwsb-tracklist">
					<?php foreach ( $s['tracks'] as $track ) : ?>
						<?php $audio_url = ! empty( $track['track_audio']['url'] ) ? $track['track_audio']['url'] : ''; ?>
						<div class="mwsb-track<?php echo '' === $audio_url ? ' mwsb-track--no-audio' : ''; ?>">
							<div class="mwsb-track__left">
								<button type="button" class="mwsb-track__play"<?php echo '' === $audio_url ? ' disabled aria-disabled="true"' : ''; ?> aria-label="<?php esc_attr_e( 'Play clip', 'mw-showband' ); ?>">&#9658;</button>
								<?php if ( '' !== $audio_url ) : ?>
									<audio class="mwsb-track__audio" src="<?php echo esc_url( $audio_url ); ?>" preload="none"></audio>
								<?php endif; ?>
								<div>
									<div class="mwsb-track__title"><?php echo esc_html( $track['track_title'] ); ?></div>
									<div class="mwsb-track__songs"><?php echo esc_html( $track['track_songs'] ); ?></div>
								</div>
							</div>
							<div class="mwsb-track__waveform">
								<?php foreach ( $bar_pattern as $h ) : ?>
									<span style="height: <?php echo (int) ( $h * 4 ); ?>px;"></span>
								<?php endforeach; ?>
							</div>
							<div class="mwsb-track__meta">
								<span class="mwsb-track__bpm"><?php echo esc_html( $track['bpm'] ); ?></span>
								<span class="mwsb-track__duration"><?php echo esc_html( $track['duration'] ); ?></span>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
