<div id="hot-deal" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hot-deal">
                    <ul class="hot-deal-countdown" id="clock">

                    </ul>
                    <h2 class="text-uppercase">hot deal this week</h2>
                    <p>New Collection Up to 50% OFF</p>
                    <a class="primary-btn cta-btn" href="#">Shop now</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    $('#clock').countdown('2020/10/10').on('update.countdown', function(event) {
    const $this = $(this).html(event.strftime(
        `<li>
            <div>
                <h3>%D</h3>
                <span>Day%!d</span>
            </div>
        </li>
        <li>
            <div>
                <h3>%H</h3>
                <span>Hour%!H</span>
            </div>
        </li>
        <li>
            <div>
                <h3>%M</h3>
                <span>Min</span>
            </div>
        </li>
        <li>
            <div>
                <h3>%S</h3>
                <span>Sec%!S</span>
            </div>
        </li>`
    ));
});
</script>
@endpush
