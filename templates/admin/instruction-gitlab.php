<?php
/**
 * Context:
 * @var string $base_url
 */
?>
<hr>

<article id="instructions">
    <h2>Webhook guide for Github
        <a
                id="toggle-instructions"
                href="#"
                role="button"
        >[toggle]</a>
    </h2>

    <section style="display: none;">
        <h3>1. Settings 메뉴를 찾아 클릭합니다.</h3>
        <img
                class="instruction-image"
                src="<?php echo esc_url( $base_url . '/nrgph-screenshot-07.png' ); ?>"
                alt="Gitlab instruction #1"
        >
    </section>

    <section style="display: none;">
        <h3>2. 사이드바 메뉴에서 'Webhooks'를 찾아 클릭합니다.</h3>
        <img
                class="instruction-image"
                src="<?php echo esc_url( $base_url . '/nrgph-screenshot-08.png' ); ?>"
                alt="Gitlab instruction #2"
        >
    </section>

    <section style="display: none;">
        <h3>3. 'URL' 필드에 위 폼의 'Webhook URL'을, 'Secret Token'에는 위 폼의 'Secret Token'을 각각 붙여 넣습니다.</h3>
        <img
                class="instruction-image"
                src="<?php echo esc_url( $base_url . '/nrgph-screenshot-09.png' ); ?>"
                alt="Gitlab instruction #3"
        >
    </section>

    <section style="display: none;">
        <h3>4. 'Add webhook' 버튼을 눌러 설정을 저장합니다.</h3>
        <img
                class="instruction-image"
                src="<?php echo esc_url( $base_url . '/nrgph-screenshot-10.png' ); ?>"
                alt="Gitlab instruction #4"
        >
    </section>
</article>
