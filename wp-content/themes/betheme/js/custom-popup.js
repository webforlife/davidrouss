function loadVideoIframe(videoPlayer) {

    if (!videoPlayer) {
        return;
    }

    const videoSrc = videoPlayer.getAttribute('data-src');

    if (videoSrc) {
        videoPlayer.innerHTML = `<iframe class="video__iframe" src="https://www.youtube.com/embed/${videoSrc}" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen"></iframe>`;
    }

    videoPlayer.parentElement.classList.add('is-loaded');
}

const dialogs = document.querySelectorAll('.js-dialog');

dialogs.forEach(dialog => {

    const dialogOpenButton = dialog.previousElementSibling;
    const dialogCloseButton = dialog.querySelector('.js-close-dialog');

    if (dialogOpenButton && dialog) {
        dialogOpenButton.addEventListener('click', () => {
            const currentVideo = dialog.querySelector('.js-dialog-video');

            if (currentVideo) {
                loadVideoIframe(currentVideo);
            }

            dialog.showModal();

            dialog.setAttribute('open', 'open');
        });
    }

    if (dialogCloseButton && dialog) {
        dialogCloseButton.addEventListener('click', () => {
            dialog.close();

            dialog.removeAttribute('open');
        });
    }
});