const minusBtn = document.querySelector('#minusProduct')
const plusBtn = document.querySelector('#plusProduct')
const amountProduct = document.querySelector('#labelamount')


minusBtn.onclick  = () => {
    amountProduct.stepDown()
}

plusBtn.onclick  = () => {
    amountProduct.stepUp()
}