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
                src="<?php echo esc_url( $base_url . '/nrgph-screenshot-01.png' ); ?>"
                alt="Github instruction #1"
        >
    </section>

    <section style="display: none;">
        <h3>2. 사이드바 메뉴에서 'Webhooks'를 찾아 클릭합니다.</h3>
        <img
                class="instruction-image"
                src="<?php echo esc_url( $base_url . '/nrgph-screenshot-02.png' ); ?>"
                alt="Github instruction #2"
        >
    </section>

    <section style="display: none;">
        <h3>3. 'Add webhook을 클릭합니다.'.</h3>
        <img
                class="instruction-image"
                src="<?php echo esc_url( $base_url . '/nrgph-screenshot-03.png' ); ?>"
                alt="Github instruction #3"
        >
    </section>

    <section style="display: none;">
        <h3>4. 패스워드를 입력하여 다음 화면으로 진행합니다.</h3>
        <img
                class="instruction-image"
                src="<?php echo esc_url( $base_url . '/nrgph-screenshot-04.png' ); ?>"
                alt="Github instruction #4"
        >
    </section>

    <section style="display: none;">
        <h3>5. 위 폼의 데이터를 복사해 붙여 넣습니다.</h3>
        <ul>
            <li>Payload URL에 위 폼의 'Webhook URL'을 복사하여 붙여 넣습니다.</li>
            <li>'Secret'에는 위 폼의 입력한 'Secret Token'을 복사하여 붙여 넣습니다.</li>
            <li>'Add webhook' 버튼을 눌러 저장합니다.</li>
        </ul>
        <img
                class="instruction-image"
                src="<?php echo esc_url( $base_url . '/nrgph-screenshot-05.png' ); ?>"
                alt="Github instruction #5"
        >
    </section>

    <section style="display: none;">
        <h3>6. 웹훅 설정이 정확하게 저장되었는지 확인합니다. </h3>
        <img
                class="instruction-image"
                src="<?php echo esc_url( $base_url . '/nrgph-screenshot-06.png' ); ?>"
                alt="Github instruction #6"
        >
    </section>

</article>
