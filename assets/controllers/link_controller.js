import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        url: String
    }

    click(event) {
        window.location.href = this.urlValue;
    }
}