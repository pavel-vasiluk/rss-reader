class FormVisibilityEvents {
    constructor() {
        this.$ripples = $('.ripples');
        this.$input = $('input');
        this.subscribeVisibilityEvents();
    }

    subscribeVisibilityEvents() {
        this.onInputBlur();
        this.onRipplesClick();
        this.onAnimationEnd();
    }

    onInputBlur() {
        this.$input.blur(function () {
            let $this = $(this);
            if ($this.val())
                $this.addClass('used');
            else
                $this.removeClass('used');
        });
    }

    onRipplesClick() {
        this.$ripples.on('click.Ripples', (e) => {
            let $offset = this.$ripples.parent().offset();
            let $circle = this.$ripples.find('.ripplesCircle');

            let x = e.pageX - $offset.left;
            let y = e.pageY - $offset.top;

            $circle.css({
                top: y + 'px',
                left: x + 'px'
            });

            this.$ripples.addClass('is-active');
        });
    }

    onAnimationEnd() {
        this.$ripples.on('animationend webkitAnimationEnd mozAnimationEnd oanimationend MSAnimationEnd', () => {
            this.$ripples.removeClass('is-active');
        });
    }
}

export {FormVisibilityEvents}