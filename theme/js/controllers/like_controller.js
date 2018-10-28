import {Controller} from "stimulus"

export default class extends Controller {
    static targets = ['count'];

    connect() {
        const liked = parseInt(window.localStorage.getItem(`${this.data.get('slug')}`), 10) === 1;
        this.element.classList.toggle('liked', liked);
    }

    like(event) {
        if(this.element.classList.contains('liked')) {
            return;
        }

        fetch(`/api/post/${this.data.get('slug')}/counters/likes`, {
            method: 'POST',
            body: JSON.stringify({mode: 'increment'})
        }).then(() => {
            this.data.set('current', parseInt(this.data.get('current'), 10) + 1);
            this.countTarget.innerHTML = this.data.get('current');
            this.element.classList.add('liked');
            window.localStorage.setItem(`${this.data.get('slug')}`, 1);
        });
    }
}