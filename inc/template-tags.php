<?php
/**
 * Reusable template tags: breadcrumbs, meta rows, social links,
 * pagination, ad slots, comment callback and related posts.
 *
 * @package Styleumax
 * @since   1.0.0
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

/* =========================================================================
 * Term links
 * ======================================================================= */

/**
 * Error-safe term link: get_term_link()/get_category_link() can return a
 * WP_Error object (e.g. for just-deleted terms); casting that to string
 * with strict_types throws a fatal Error, so guard it here.
 *
 * @param int $term_id Term ID.
 * @return string Term archive URL, or '' when the link cannot be built.
 * @since 1.0.1
 */
function styleumax_term_link( int $term_id ): string {
        if ( 0 === $term_id ) {
                return '';
        }

        $link = get_term_link( $term_id );

        return is_wp_error( $link ) ? '' : (string) $link;
}

/* =========================================================================
 * Social links
 * ======================================================================= */

/**
 * Build the social links list from Customizer settings.
 *
 * @return array<string,array{url:string,icon:string,label:string}>
 * @since 1.0.0
 */
function styleumax_get_social_links(): array {
        $networks = array(
                'facebook'  => array( __( 'Facebook', 'styleumax' ), 'fa-brands fa-facebook-f' ),
                'x'         => array( __( 'X (Twitter)', 'styleumax' ), 'fa-brands fa-x-twitter' ),
                'instagram' => array( __( 'Instagram', 'styleumax' ), 'fa-brands fa-instagram' ),
                'youtube'   => array( __( 'YouTube', 'styleumax' ), 'fa-brands fa-youtube' ),
                'linkedin'  => array( __( 'LinkedIn', 'styleumax' ), 'fa-brands fa-linkedin-in' ),
                'pinterest' => array( __( 'Pinterest', 'styleumax' ), 'fa-brands fa-pinterest-p' ),
                'tiktok'    => array( __( 'TikTok', 'styleumax' ), 'fa-brands fa-tiktok' ),
                'telegram'  => array( __( 'Telegram', 'styleumax' ), 'fa-brands fa-telegram' ),
                'whatsapp'  => array( __( 'WhatsApp', 'styleumax' ), 'fa-brands fa-whatsapp' ),
                'bluesky'   => array( __( 'Bluesky', 'styleumax' ), 'fa-brands fa-bluesky' ),
                'mastodon'  => array( __( 'Mastodon', 'styleumax' ), 'fa-brands fa-mastodon' ),
        );

        $links = array();

        foreach ( $networks as $key => $data ) {
                $url = styleumax_get_option( 'social_' . $key, '' );
                if ( '' !== $url ) {
                        $links[ $key ] = array(
                                'url'   => $url,
                                'icon'  => $data[1],
                                'label' => $data[0],
                        );
                }
        }

        $email = styleumax_get_option( 'social_email', '' );
        if ( '' !== $email ) {
                $links['email'] = array(
                        'url'   => 'mailto:' . antispambot( $email ),
                        'icon'  => 'fa-solid fa-envelope',
                        'label' => __( 'E-posta', 'styleumax' ),
                );
        }

        $rss = styleumax_get_option( 'social_rss', '' );
        $links['rss'] = array(
                'url'   => '' !== $rss ? $rss : get_bloginfo( 'rss2_url' ),
                'icon'  => 'fa-solid fa-rss',
                'label' => __( 'RSS', 'styleumax' ),
        );

        return apply_filters( 'styleumax_social_links', $links );
}

/* =========================================================================
 * Ad slots
 * ======================================================================= */

/**
 * Print an ad slot: Customizer code first, then the matching
 * template-parts/ads/{slot}.php file if present.
 *
 * @param string $slot Slot key: header, sidebar_top, sidebar_bot, archive_top, single_bottom.
 * @return void
 * @since 1.0.0
 */
function styleumax_ad_slot( string $slot ): void {
        $allowed = array( 'header', 'sidebar_top', 'sidebar_bot', 'archive_top', 'single_bottom' );
        if ( ! in_array( $slot, $allowed, true ) ) {
                return;
        }

        $code = (string) styleumax_get_option( 'ad_' . $slot, '' );

        // Start output buffer for the fallback part; only shown when empty code.
        $part = STYLEUMAX_DIR . '/template-parts/ads/' . $slot . '.php';

        if ( '' === trim( $code ) && file_exists( $part ) ) {
                ob_start();
                include $part; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable -- path is whitelisted above.
                $code = (string) ob_get_clean();
        }

        if ( '' === trim( $code ) ) {
                return;
        }

        $html = apply_filters( 'styleumax_ad_code', $code, $slot );
        if ( '' === trim( $html ) ) {
                return;
        }

        printf(
                '<div class="sumx-ad sumx-ad-%1$s" aria-hidden="true">%2$s</div>',
                esc_attr( str_replace( '_', '-', $slot ) ),
                $html // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-provided ad/embed code, filtered via styleumax_ad_code.
        );
}

/* =========================================================================
 * Post meta
 * ======================================================================= */

/**
 * Estimated reading time in minutes (filterable).
 *
 * @param int|null $post_id Post ID.
 * @return int
 * @since 1.0.0
 */
function styleumax_reading_time( ?int $post_id = null ): int {
        $post_id = $post_id ?? get_the_ID();
        $content = $post_id ? (string) get_post_field( 'post_content', $post_id ) : '';
        $words   = str_word_count( wp_strip_all_tags( $content ) );
        $minutes = (int) max( 1, (int) ceil( $words / 220 ) );

        return (int) apply_filters( 'styleumax_reading_time', $minutes, $post_id, $words );
}

/**
 * Print the compact card meta row (date + reading time).
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_card_meta(): void {
        echo '<p class="sumx-card-meta">';
        echo '<span><i class="fa-regular fa-clock" aria-hidden="true"></i>' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( (string) get_the_date( 'c' ) ) ) ) . '</span>';
        echo '<span><i class="fa-regular fa-bookmark" aria-hidden="true"></i>' . esc_html(
                /* translators: %d: reading time in minutes. */
                sprintf( _n( '%d dk okuma', '%d dk okuma', styleumax_reading_time(), 'styleumax' ), styleumax_reading_time() )
        ) . '</span>';
        echo '</p>';
}

/**
 * Print the single post meta list.
 *
 * @return void
 * @since 1.0.0
 * @since 1.1.0 Author row now shows the avatar for a modern meta layout.
 */
function styleumax_single_meta(): void {
        $author_id = (int) get_the_author_meta( 'ID' );
        ?>
        <ul class="sumx-single-meta">
                <li class="sumx-single-meta-author">
                        <?php if ( $author_id > 0 ) : ?>
                                <?php echo get_avatar( $author_id, 28, '', get_the_author_meta( 'display_name', $author_id ), array( 'class' => 'sumx-meta-avatar' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php the_author(); ?></a>
                </li>
                <li><i class="fa-regular fa-clock" aria-hidden="true"></i><?php echo esc_html( get_the_date() ); ?></li>
                <li><i class="fa-regular fa-folder-open" aria-hidden="true"></i><?php echo wp_kses_post( get_the_category_list( ', ' ) ); ?></li>
                <li><i class="fa-regular fa-comment" aria-hidden="true"></i><a href="<?php echo esc_url( get_comments_link() ); ?>"><?php comments_number( '0', '1', '%' ); ?></a></li>
                <li><i class="fa-regular fa-bookmark" aria-hidden="true"></i><?php echo esc_html(
                        /* translators: %d: reading time in minutes. */
                        sprintf( _n( '%d dakikalık okuma', '%d dakikalık okuma', styleumax_reading_time(), 'styleumax' ), styleumax_reading_time() )
                ); ?></li>
        </ul>
        <?php
}

/**
 * Print tag list for the single view.
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_the_tags(): void {
        $tags = get_the_tags();
        if ( ! is_array( $tags ) || array() === $tags ) {
                return;
        }
        echo '<ul class="sumx-tags">';
        foreach ( $tags as $tag ) {
                $tag_link = styleumax_term_link( (int) $tag->term_id );
                if ( '' !== $tag_link ) {
                        printf(
                                '<li><a href="%1$s"><i class="fa-solid fa-tag" aria-hidden="true"></i> %2$s</a></li>',
                                esc_url( $tag_link ),
                                esc_html( $tag->name )
                        );
                } else {
                        printf(
                                '<li><span><i class="fa-solid fa-tag" aria-hidden="true"></i> %1$s</span></li>',
                                esc_html( $tag->name )
                        );
                }
        }
        echo '</ul>';
}

/* =========================================================================
 * Thumbnails
 * ======================================================================= */

/**
 * Print a responsive post thumbnail (or a decorative fallback).
 *
 * @param string $size  Image size slug.
 * @param string $class Extra class for the wrapper img.
 * @return void
 * @since 1.0.0
 */
function styleumax_thumbnail( string $size = 'styleumax-grid', string $class = '' ): void {
        if ( has_post_thumbnail() ) {
                the_post_thumbnail(
                        $size,
                        array(
                                'class'     => trim( 'img-fluid ' . $class ),
                                'loading'   => 'lazy',
                                'alt'       => the_title_attribute( array( 'echo' => false ) ),
                        )
                );
                return;
        }

        printf(
                '<span class="sumx-no-thumb %1$s" role="img" aria-label="%2$s"><i class="fa-regular fa-image" aria-hidden="true"></i></span>',
                esc_attr( $class ),
                esc_attr( get_bloginfo( 'name' ) )
        );
}

/**
 * Primary category chip link (first assigned category).
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_category_chip(): void {
        $cats = get_the_category();
        if ( array() === $cats || ! isset( $cats[0] ) ) {
                return;
        }
        $chip_link = styleumax_term_link( (int) $cats[0]->term_id );
        if ( '' === $chip_link ) {
                return;
        }
        printf(
                '<a class="sumx-cat-chip" href="%1$s">%2$s</a>',
                esc_url( $chip_link ),
                esc_html( $cats[0]->name )
        );
}

/* =========================================================================
 * Breadcrumbs
 * ======================================================================= */

/**
 * Visible breadcrumb trail (also mirrored as JSON-LD in inc/schema.php).
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_breadcrumbs(): void {
        if ( is_front_page() ) {
                return;
        }

        $items = array(
                array(
                        'label' => __( 'Anasayfa', 'styleumax' ),
                        'url'   => home_url( '/' ),
                ),
        );

        if ( is_singular( 'post' ) ) {
                $cats = get_the_category();
                if ( isset( $cats[0] ) ) {
                        $cat_link = styleumax_term_link( (int) $cats[0]->term_id );
                        if ( '' !== $cat_link ) {
                                $items[] = array(
                                        'label' => $cats[0]->name,
                                        'url'   => $cat_link,
                                );
                        }
                }
                $items[] = array( 'label' => get_the_title(), 'url' => '' );
        } elseif ( is_page() ) {
                $ancestors = get_post_ancestors( (int) get_the_ID() );
                foreach ( array_reverse( $ancestors ) as $ancestor ) {
                        $items[] = array(
                                'label' => (string) get_the_title( $ancestor ),
                                'url'   => get_permalink( $ancestor ),
                        );
                }
                $items[] = array( 'label' => get_the_title(), 'url' => '' );
        } elseif ( is_category() || is_tag() ) {
                $items[] = array( 'label' => single_term_title( '', false ), 'url' => '' );
        } elseif ( is_search() ) {
                $items[] = array(
                        /* translators: %s: search query. */
                        'label' => sprintf( __( 'Arama: %s', 'styleumax' ), get_search_query() ),
                        'url'   => '',
                );
        } elseif ( is_author() ) {
                $items[] = array( 'label' => get_the_author(), 'url' => '' );
        } elseif ( is_date() ) {
                $items[] = array( 'label' => get_the_archive_title(), 'url' => '' );
        } elseif ( is_404() ) {
                $items[] = array( 'label' => __( 'Sayfa bulunamadı', 'styleumax' ), 'url' => '' );
        } elseif ( is_attachment() ) {
                $items[] = array( 'label' => get_the_title(), 'url' => '' );
        }

        if ( is_paged() ) {
                $items[] = array(
                        /* translators: %s: page number. */
                        'label' => sprintf( __( 'Sayfa %s', 'styleumax' ), (string) get_query_var( 'paged' ) ),
                        'url'   => '',
                );
        }

        echo '<nav class="sumx-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb navigasyonu', 'styleumax' ) . '">';
        $last = count( $items ) - 1;
        foreach ( $items as $index => $item ) {
                if ( $index === $last || '' === $item['url'] ) {
                        echo '<span aria-current="page">' . esc_html( (string) $item['label'] ) . '</span>';
                } else {
                        echo '<a href="' . esc_url( (string) $item['url'] ) . '">' . esc_html( (string) $item['label'] ) . '</a>';
                }
                if ( $index < $last ) {
                        echo ' <span aria-hidden="true">›</span> ';
                }
        }
        echo '</nav>';
}

/* =========================================================================
 * Pagination & navigation
 * ======================================================================= */

/**
 * Bootstrap-styled archive pagination.
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_pagination(): void {
        the_posts_pagination(
                array(
                        'mid_size'  => 2,
                        'class'     => 'sumx-pagination',
                        'prev_text' => '<i class="fa-solid fa-chevron-left" aria-hidden="true"></i><span class="visually-hidden">' . esc_html__( 'Önceki sayfa', 'styleumax' ) . '</span>',
                        'next_text' => '<i class="fa-solid fa-chevron-right" aria-hidden="true"></i><span class="visually-hidden">' . esc_html__( 'Sonraki sayfa', 'styleumax' ) . '</span>',
                )
        );
}

/* =========================================================================
 * Author box & related posts
 * ======================================================================= */

/**
 * Author box for single posts.
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_author_box(): void {
        $author_id = (int) get_the_author_meta( 'ID' );
        if ( 0 === $author_id ) {
                return;
        }
        $bio = get_the_author_meta( 'description', $author_id );
        ?>
        <section class="sumx-authorbox" aria-label="<?php esc_attr_e( 'Yazar hakkında', 'styleumax' ); ?>">
                <?php echo get_avatar( $author_id, 96, '', get_the_author_meta( 'display_name', $author_id ), array( 'class' => 'img-fluid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <div>
                        <h3>
                                <?php if ( get_the_author_meta( 'url', $author_id ) ) : ?>
                                        <a href="<?php echo esc_url( (string) get_the_author_meta( 'url', $author_id ) ); ?>" rel="nofollow"><?php the_author_meta( 'display_name', $author_id ); ?></a>
                                <?php else : ?>
                                        <?php the_author_meta( 'display_name', $author_id ); ?>
                                <?php endif; ?>
                        </h3>
                        <?php if ( '' !== $bio ) : ?>
                                <p><?php echo esc_html( $bio ); ?></p>
                        <?php endif; ?>
                </div>
        </section>
        <?php
}

/**
 * Related posts query (same categories, exclude current).
 *
 * @param int $limit Number of posts.
 * @return WP_Post[]
 * @since 1.0.0
 */
function styleumax_get_related_posts( int $limit = 3 ): array {
        $post_id = (int) get_the_ID();
        if ( 0 === $post_id ) {
                return array();
        }

        $cats = wp_get_post_categories( $post_id );
        $args = array(
                'post__not_in'        => array( $post_id ),
                'posts_per_page'      => $limit,
                'ignore_sticky_posts' => true,
                'no_found_rows'       => true,
                'post_status'         => 'publish',
        );

        if ( array() !== $cats ) {
                $args['category__in'] = $cats;
        }

        $query = new WP_Query( $args );

        return $query->have_posts() ? $query->posts : array();
}

/* =========================================================================
 * Comments
 * ======================================================================= */

/**
 * Custom comment renderer used by wp_list_comments().
 *
 * Intentionally untyped on $comment: some comment-related plugins replace
 * the object with other shapes, and a strict-type mismatch here would be
 * a site-wide fatal. Anything that is not a WP_Comment is skipped safely.
 *
 * @param mixed  $comment Comment object.
 * @param array  $args    Display args.
 * @param int    $depth   Depth level.
 * @return void
 * @since 1.0.0
 */
function styleumax_comment_callback( $comment, array $args = array(), int $depth = 0 ): void {
        if ( ! $comment instanceof WP_Comment ) {
                return;
        }

        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
        ?>
        <<?php echo esc_html( $tag ); ?> <?php comment_class( 'sumx-comment' . ( (int) $comment->user_id === (int) get_post()->post_author ? ' bypostauthor' : '' ), $comment ); ?> id="comment-<?php comment_ID(); ?>">
                <article>
                        <header class="sumx-comment-header">
                                <?php echo get_avatar( $comment, 48, '', esc_attr( $comment->comment_author ), array( 'class' => 'img-fluid' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                <div>
                                        <span class="sumx-comment-author"><?php comment_author( $comment ); ?></span>
                                        <span class="sumx-comment-metadata d-block">
                                                <a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>">
                                                        <?php
                                                        printf(
                                                                /* translators: 1: comment date, 2: comment time. */
                                                                esc_html__( '%1$s, %2$s', 'styleumax' ),
                                                                esc_html( get_comment_date( '', $comment ) ),
                                                                esc_html( get_comment_time() )
                                                        );
                                                        ?>
                                                </a>
                                        </span>
                                </div>
                        </header>

                        <?php if ( '0' === $comment->comment_approved ) : ?>
                                <p class="alert alert-warning py-2 px-3 my-2 small"><em><?php esc_html_e( 'Yorumunuz onay bekliyor.', 'styleumax' ); ?></em></p>
                        <?php endif; ?>

                        <div class="sumx-comment-content">
                                <?php comment_text( $comment ); ?>
                        </div>

                        <?php
                        comment_reply_link(
                                array_merge(
                                        $args,
                                        array(
                                                'depth'     => $depth,
                                                'add_below' => 'comment',
                                                'before'    => '<span class="reply d-inline-block mt-2">',
                                                'after'     => '</span>',
                                        )
                                )
                        );
                        ?>
                </article>
        <?php
}

/**
 * Bootstrap 5 flavored comment form arguments.
 *
 * @return array
 * @since 1.0.0
 */
function styleumax_comment_form_args(): array {
        $commenter = wp_get_current_commenter();
        $req       = get_option( 'require_name_email' ) ? true : false;
        $aria_req  = $req ? " aria-required='true' required" : '';

        $fields = array(
                'author' => sprintf(
                        '<div class="col-md-4"><label for="author" class="form-label">%1$s%2$s</label><input id="author" name="author" type="text" class="form-control" value="%3$s"%4$s></div>',
                        esc_html__( 'Adınız', 'styleumax' ),
                        $req ? ' <span class="text-danger">*</span>' : '',
                        esc_attr( $commenter['comment_author'] ?? '' ),
                        $aria_req
                ),
                'email'  => sprintf(
                        '<div class="col-md-4"><label for="email" class="form-label">%1$s%2$s</label><input id="email" name="email" type="email" class="form-control" value="%3$s"%4$s></div>',
                        esc_html__( 'E-posta', 'styleumax' ),
                        $req ? ' <span class="text-danger">*</span>' : '',
                        esc_attr( $commenter['comment_author_email'] ?? '' ),
                        $aria_req
                ),
                'url'    => sprintf(
                        '<div class="col-md-4"><label for="url" class="form-label">%1$s</label><input id="url" name="url" type="url" class="form-control" value="%2$s"></div>',
                        esc_html__( 'Web sitesi', 'styleumax' ),
                        esc_attr( $commenter['comment_author_url'] ?? '' )
                ),
        );

        return array(
                'class_form'           => 'sumx-comment-form row g-3 needs-validation',
                'class_submit'         => 'submit btn btn-primary',
                'title_reply'          => __( 'Yanıt yazın', 'styleumax' ),
                'title_reply_before'   => '<h3 class="comment-reply-title">',
                'title_reply_after'    => '</h3>',
                'cancel_reply_link'    => __( 'Yanıtı iptal et', 'styleumax' ),
                'label_submit'         => __( 'Yorumu gönder', 'styleumax' ),
                'comment_notes_before' => '<p class="small text-body-secondary">' . esc_html__( 'E-posta adresiniz yayınlanmayacaktır.', 'styleumax' ) . '</p>',
                'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
                'comment_field'        => sprintf(
                        '<div class="col-12"><label for="comment" class="form-label">%1$s <span class="text-danger">*</span></label><textarea id="comment" name="comment" rows="6" class="form-control" aria-required="true" required></textarea></div>',
                        esc_html__( 'Yorumunuz', 'styleumax' )
                ),
        );
}

/* =========================================================================
 * Floating page tools (legacy Stylebook feature, modernized)
 * ======================================================================= */

/**
 * Left floating tool bar: prev/next post, contact, home, search.
 *
 * @return void
 * @since 1.0.0
 */
function styleumax_page_tools(): void {
        if ( ! styleumax_get_option( 'show_pagetools', true ) ) {
                return;
        }

        $tools = array();

        if ( is_singular( 'post' ) ) {
                $prev = get_previous_post();
                $next = get_next_post();

                if ( $prev instanceof WP_Post ) {
                        $tools[] = array(
                                'url'   => get_permalink( $prev ),
                                'title' => __( 'Önceki yazı', 'styleumax' ) . ': ' . (string) get_the_title( $prev ),
                                'icon'  => 'fa-solid fa-arrow-left',
                        );
                }
                if ( $next instanceof WP_Post ) {
                        $tools[] = array(
                                'url'   => get_permalink( $next ),
                                'title' => __( 'Sonraki yazı', 'styleumax' ) . ': ' . (string) get_the_title( $next ),
                                'icon'  => 'fa-solid fa-arrow-right',
                        );
                }
        }

        $contact = (string) styleumax_get_option( 'contact_url', '' );
        if ( '' !== $contact ) {
                $tools[] = array(
                        'url'   => $contact,
                        'title' => __( 'Bize ulaşın', 'styleumax' ),
                        'icon'  => 'fa-regular fa-envelope',
                );
        }

        if ( ! is_front_page() ) {
                $tools[] = array(
                        'url'   => home_url( '/' ),
                        'title' => __( 'Anasayfaya dön', 'styleumax' ),
                        'icon'  => 'fa-solid fa-house',
                );
        }

        $tools[] = array(
                'url'   => '#sumx-search-collapse',
                'title' => __( 'Sitede ara', 'styleumax' ),
                'icon'  => 'fa-solid fa-magnifying-glass',
        );

        if ( array() === $tools ) {
                return;
        }

        echo '<div class="sumx-pagetools is-visible" aria-label="' . esc_attr__( 'Sayfa kısayolları', 'styleumax' ) . '">';
        foreach ( $tools as $tool ) {
                printf(
                        '<a class="sumx-pagetool" href="%1$s" title="%2$s"><i class="%3$s" aria-hidden="true"></i><span class="visually-hidden">%4$s</span></a>',
                        esc_url( $tool['url'] ),
                        esc_attr( $tool['title'] ),
                        esc_attr( $tool['icon'] ),
                        esc_html( $tool['title'] )
                );
        }
        echo '</div>';
}
