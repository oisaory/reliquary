import { Controller } from '@hotwired/stimulus';
import { Tooltip } from 'bootstrap';

/*
 * This controller handles Bootstrap tooltips
 *
 * Any element with a data-controller="tooltip" attribute will cause
 * this controller to be executed.
 */
export default class extends Controller {
    static values = {
        options: { type: Object, default: {} }
    }

    connect() {
        this.tooltip = new Tooltip(this.element, this.optionsValue);
    }

    disconnect() {
        // Clean up the tooltip when the element is removed from the DOM
        if (this.tooltip) {
            this.tooltip.dispose();
        }
    }
}