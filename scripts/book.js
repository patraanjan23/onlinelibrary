const btn = document.getElementById('btn-borrow')
const modal = document.getElementById('modal-borrow')
const modalCloseBtn = document.getElementById('modal-close-btn')

btn.addEventListener('click', showModal)
modalCloseBtn.addEventListener('click', hideModal)

function showModal() {
    modal.style.display = 'block'
}

function hideModal(e) {
    e.preventDefault()
    modal.style.display = 'none'
}
