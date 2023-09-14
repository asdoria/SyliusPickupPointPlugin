export const ScrollIntoDiv = () => {
    const modalContainer = document.querySelector('.js-pkp-modal-points')
    const modalPointActive = modalContainer.querySelector('.pkp-modal-point--active')

    if (!modalContainer || !modalPointActive) return

    modalContainer.scrollTo({
        top: modalPointActive.offsetTop,
        behavior: 'smooth'
    })
}
