import {Controller} from "stimulus"

export default class extends Controller {

    connect() {
        this.element.addEventListener('keydown', function(ev) {
            if(ev.keyCode === 13) {
                location.href = '/search/' + encodeURIComponent(ev.target.value);
            }
        });
    }
}