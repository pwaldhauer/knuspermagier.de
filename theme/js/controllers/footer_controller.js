import {Controller} from "stimulus"

export default class extends Controller {
    static targets = ['footer'];

    connect() {

        const footer = Math.floor(Math.random() * 3);
        const elem = this.footerTarget;
        elem.classList.add('random-footer-' + footer);

        const height = elem.getBoundingClientRect().height;

        let v = false;
        window.addEventListener('scroll', function (ev) {
            if (v) {
                return;
            }

            window.requestAnimationFrame(function () {
                if (window.scrollY > (document.body.scrollHeight - document.body.clientHeight - height - 500)) {
                    elem.classList.add('visible');
                    v = true;
                }
            })
        })
    }
}