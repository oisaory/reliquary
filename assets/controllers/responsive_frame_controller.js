import { Controller } from '@hotwired/stimulus';

/**
 * Responsive Frame controller
 *
 * This controller updates the src attribute of a turbo-frame based on screen size
 */
export default class extends Controller {
    static values = {
        desktopSrc: String,
        mobileSrc: String,
        breakpoint: { type: Number, default: 768 } // Bootstrap md breakpoint
    }

    connect() {
        // Set initial src based on screen size
        this.updateFrameSrc();
        
        // Update src when window is resized
        // this.resizeObserver = new ResizeObserver(this.updateFrameSrc.bind(this));
        // this.resizeObserver.observe(document.body);
    }

    disconnect() {
        // Clean up the observer when the controller is disconnected
        if (this.resizeObserver) {
            this.resizeObserver.disconnect();
        }
    }

    updateFrameSrc() {
        const frame = this.element;
        const isMobile = window.innerWidth < this.breakpointValue;
        
        // Only update if the src needs to change
        const newSrc = isMobile ? this.mobileSrcValue : this.desktopSrcValue;
        
        if (frame.src !== newSrc) {
            frame.src = newSrc;
        }
    }
}