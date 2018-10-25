import {Controller} from "stimulus"

export default class extends Controller {
    static targets = ['input'];

    connect() {
        this.inputTarget.addEventListener('keydown', function(ev) {
            if(ev.keyCode === 13) {
                location.href = '/search/' + encodeURIComponent(ev.target.value);
            }
        });
    }
}