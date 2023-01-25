import 'bootstrap'
import { tns } from 'tiny-slider'
import WebSite from '.'

$(function () {
    WebSite.initMenu()

    const slider = tns({
        container: '.slider-home',
        items: 2,
        slideBy: 'page',
        mouseDrag: false,
        autoplay: false,
        autoplayTimeout: 5000,
        autoplayDirection: 'forward',
        autoplayText: ['start', 'stop'],
        autoplayHoverPause: true,
        autoplayButton: false,
        autoplayButtonOutput: false,
        autoplayResetOnVisibility: true,
        autoHeight: false,
        controls: false,
        controlsPosition: 'bottom',
        controlsText: ['prev', 'next'],
        nav: true,
        navPosition: 'bottom',
        navAsThumbnails: true,
        responsive: {}
    })
})
