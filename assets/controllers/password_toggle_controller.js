import { Controller } from '@hotwired/stimulus';

/**
 * Password Toggle controller
 *
 * This controller toggles the visibility of password fields
 * and changes the eye icon accordingly.
 */
export default class extends Controller {
    static targets = ['input', 'icon'];

    connect() {
        // Ensure we have the required targets
        if (!this.hasInputTarget || !this.hasIconTarget) {
            console.error('Password toggle controller requires input and icon targets');
            return;
        }
    }

    toggle() {
        // Toggle the type attribute of the input field
        const type = this.inputTarget.getAttribute('type') === 'password' ? 'text' : 'password';
        this.inputTarget.setAttribute('type', type);

        // Toggle the icon
        const useElement = this.iconTarget.querySelector('use');
        if (useElement) {
            const currentIcon = useElement.getAttribute('href');
            useElement.setAttribute('href', currentIcon === '#eye' ? '#eye-slash' : '#eye');
        }
    }
}