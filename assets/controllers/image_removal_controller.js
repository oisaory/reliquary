import { Controller } from '@hotwired/stimulus';

/**
 * Image removal controller
 *
 * This controller handles the UI for marking images for removal
 */
export default class extends Controller {
    static targets = ['imageContainer', 'removeButton', 'removeInput'];

    connect() {
        // Initialize controller
    }

    markForRemoval(event) {
        event.preventDefault();

        // Get the image container
        const container = event.currentTarget.closest('[data-image-removal-target="imageContainer"]');

        // Add a visual indication that the image is marked for removal
        container.classList.add('opacity-50');

        // Change the button to indicate it can be undone
        const button = event.currentTarget;
        button.classList.remove('btn-danger');
        button.classList.add('btn-secondary');
        button.innerHTML = '<i class="fas fa-arrow-rotate-left"></i>';

        // Update the action to undo
        button.setAttribute('data-action', 'image-removal#undoRemoval');

        // Enable the hidden input to mark this image for removal
        const input = container.querySelector('[data-image-removal-target="removeInput"]');
        input.disabled = false;
    }

    undoRemoval(event) {
        event.preventDefault();

        // Get the image container
        const container = event.currentTarget.closest('[data-image-removal-target="imageContainer"]');

        // Remove the visual indication
        container.classList.remove('opacity-50');

        // Change the button back to the remove button
        const button = event.currentTarget;
        button.classList.remove('btn-secondary');
        button.classList.add('btn-danger');
        button.innerHTML = '<i class="fas fa-circle-xmark"></i>';

        // Update the action back to mark for removal
        button.setAttribute('data-action', 'image-removal#markForRemoval');

        // Disable the hidden input to unmark this image for removal
        const input = container.querySelector('[data-image-removal-target="removeInput"]');
        input.disabled = true;
    }
}
