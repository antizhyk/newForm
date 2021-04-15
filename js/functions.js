document.addEventListener('DOMContentLoaded', function () {
    const formBlock = document.querySelector('.form__block');
    const wrapBlock = document.querySelector('.form__wrap');
    const telField = document.querySelector('.form__input_tel');

    formBlock.addEventListener('click', e =>{
        if(e.target.classList.contains('form__item_add-field')){
           const newBlock = wrapBlock.cloneNode();
           const newTel = telField.cloneNode();
            newTel.value = '';
           const removeField = document.createElement('div');
            removeField.classList.add('form__item_remove-field');
            removeField.innerText = '-';
            removeField.addEventListener('click', ()=>{
                removeField.closest('.form__wrap').remove();
            })
            newBlock.append(newTel);
            newBlock.append(removeField);
           formBlock.append(newBlock);
        }
    })
});


