import { Controller } from '@hotwired/stimulus';

/**
 * Filter Toggle controller
 *
 * This controller toggles the visibility of the filter section
 * and changes the eye icon accordingly.
 */
export default class extends Controller {
    static targets = ['filter', 'icon'];

    connect() {
        console.log(this.hasFilterTarget);
        console.log(this.hasIconTarget)
        // Ensure we have the required targets
        if (!this.hasFilterTarget || !this.hasIconTarget) {
            console.error('Filter toggle controller requires filter and icon targets');
            return;
        }
    }

    toggle() {
        // Toggle the visibility of the filter section
        this.filterTarget.classList.toggle('d-none');
        
        // Toggle the icon
        const useElement = this.iconTarget.querySelector('use');
        if (useElement) {
            const currentIcon = useElement.getAttribute('href');
            useElement.setAttribute('href', currentIcon === '#eye' ? '#eye-slash' : '#eye');
        }
    }
}