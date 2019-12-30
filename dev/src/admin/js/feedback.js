const $ = jQuery

export default class WpeFeedback{
    constructor($containerEl) {
        this.containerEl = $containerEl
    }

    showFeedback = (type, message) => {
        this.containerEl.removeClass('wpe-success wpe-error wpe-warning').addClass('wpe-' + type)
        this.containerEl.html(message)
        this.containerEl.show().fadeIn()
    }

    hideFeedback = () => {
        this.containerEl.fadeOut()
    }

}